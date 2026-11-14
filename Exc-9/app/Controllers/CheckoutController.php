<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use CodeIgniter\HTTP\RedirectResponse;

class CheckoutController extends BaseController
{
    private const SHIPPING_COST = 15000.0;

    /**
     * Menampilkan halaman checkout (FR-012).
     */
    public function index(): string|RedirectResponse
    {
        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $items     = $cartModel->getCartItems($userId);

        if ($items === []) {
            return redirect()->to(site_url('keranjang'))
                ->with('error', 'Keranjang Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $subtotal = 0.0;
        foreach ($items as &$item) {
            $itemSubtotal     = (float) $item['price'] * (int) $item['quantity'];
            $item['subtotal'] = $itemSubtotal;
            $subtotal        += $itemSubtotal;
        }

        $db        = \Config\Database::connect();
        $addresses = $db->table('addresses')
            ->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('frontend/checkout', [
            'title'        => 'Checkout Pesanan – ShopEase',
            'items'        => $items,
            'addresses'    => $addresses,
            'subtotal'     => $subtotal,
            'shippingCost' => self::SHIPPING_COST,
            'total'        => $subtotal + self::SHIPPING_COST,
            'cartCount'    => $this->getCartCount(),
        ]);
    }

    /**
     * Memproses checkout dalam database transaction (FR-012, FR-013, BR-004, BR-005, BR-009, BR-010).
     */
    public function process(): RedirectResponse
    {
        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $items     = $cartModel->getCartItems($userId);

        if ($items === []) {
            return redirect()->to(site_url('keranjang'))
                ->with('error', 'Keranjang Anda kosong.');
        }

        $db          = \Config\Database::connect();
        $addressMode = (string) $this->request->getPost('address_mode');
        $addressId   = (int) $this->request->getPost('address_id');

        if ($addressMode === 'new' || $addressId <= 0) {
            $rules = [
                'recipient_name' => 'required|min_length[3]|max_length[120]',
                'phone'          => 'required|min_length[8]|max_length[30]',
                'address'        => 'required|min_length[8]',
                'city'           => 'required|max_length[100]',
                'province'       => 'required|max_length[100]',
                'postal_code'    => 'required|max_length[20]',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            $db->table('addresses')->insert([
                'user_id'        => $userId,
                'recipient_name' => trim((string) $this->request->getPost('recipient_name')),
                'phone'          => trim((string) $this->request->getPost('phone')),
                'address'        => trim((string) $this->request->getPost('address')),
                'city'           => trim((string) $this->request->getPost('city')),
                'province'       => trim((string) $this->request->getPost('province')),
                'postal_code'    => trim((string) $this->request->getPost('postal_code')),
                'is_default'     => 0,
            ]);
            $addressId = (int) $db->insertID();
        } else {
            $ownedAddress = $db->table('addresses')
                ->where('id', $addressId)
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            if (! is_array($ownedAddress)) {
                return redirect()->back()->with('error', 'Alamat pengiriman tidak valid.');
            }
        }

        $notes         = trim((string) $this->request->getPost('notes'));
        $paymentMethod = trim((string) ($this->request->getPost('payment_method') ?? 'transfer_bank'));
        $orderNumber   = 'SE-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        $db->transBegin();

        try {
            $subtotal       = 0.0;
            $preparedItems  = [];

            foreach ($items as $cartItem) {
                $product = $db->table('products')
                    ->where('id', $cartItem['product_id'])
                    ->get()
                    ->getRowArray();

                if (! is_array($product) || $product['status'] !== 'active') {
                    throw new \RuntimeException('Produk "' . $cartItem['name'] . '" sudah tidak tersedia.');
                }

                $qty = (int) $cartItem['quantity'];
                if ($qty < 1 || $qty > (int) $product['stock']) {
                    throw new \RuntimeException('Stok "' . $product['name'] . '" tidak mencukupi (tersisa ' . $product['stock'] . ').');
                }

                $price        = (float) $product['price'];
                $itemSubtotal = $price * $qty;
                $subtotal    += $itemSubtotal;

                $preparedItems[] = [
                    'product_id'   => (int) $product['id'],
                    'product_name' => (string) $product['name'],
                    'price'        => $price,
                    'quantity'     => $qty,
                    'subtotal'     => $itemSubtotal,
                    'new_stock'    => (int) $product['stock'] - $qty,
                ];
            }

            $total = $subtotal + self::SHIPPING_COST;
            $now   = date('Y-m-d H:i:s');

            $orderModel = new OrderModel();
            $orderId    = $orderModel->insert([
                'user_id'       => $userId,
                'address_id'    => $addressId,
                'order_number'  => $orderNumber,
                'order_date'    => $now,
                'subtotal'      => $subtotal,
                'shipping_cost' => self::SHIPPING_COST,
                'total'         => $total,
                'order_status'  => 'pending_payment',
                'notes'         => $notes !== '' ? $notes : null,
            ], true);

            foreach ($preparedItems as $row) {
                $db->table('order_items')->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $row['product_id'],
                    'product_name' => $row['product_name'],
                    'price'        => $row['price'],
                    'quantity'     => $row['quantity'],
                    'subtotal'     => $row['subtotal'],
                ]);

                $db->table('products')
                    ->where('id', $row['product_id'])
                    ->update([
                        'stock'      => $row['new_stock'],
                        'updated_at' => $now,
                    ]);
            }

            $db->table('payments')->insert([
                'order_id'       => $orderId,
                'method'         => $paymentMethod,
                'amount'         => $total,
                'payment_status' => 'unpaid',
            ]);

            $db->table('shipments')->insert([
                'order_id'        => $orderId,
                'shipment_status' => 'pending',
            ]);

            $cartModel->clearCart($userId);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan transaksi pesanan.');
            }

            $db->transCommit();

            return redirect()->to(site_url('pesanan/' . $orderNumber))
                ->with('success', 'Pesanan ' . $orderNumber . ' berhasil dibuat! Silakan unggah bukti pembayaran.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->to(site_url('keranjang'))
                ->with('error', $e->getMessage());
        }
    }
}
