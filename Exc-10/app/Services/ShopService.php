<?php

namespace App\Services;

use App\Models\AddressModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class ShopService
{
    private const SHIPPING_COST = 15000.0;

    private BaseConnection $db;
    private AddressModel $addressModel;

    public function __construct()
    {
        $this->db           = \Config\Database::connect();
        $this->addressModel = new AddressModel();
    }

    /**
     * Validasi payload alamat pengiriman.
     *
     * @param array<string, mixed> $input
     *
     * @return array{ok: bool, error?: string, data?: array<string, mixed>}
     */
    public function validateAddressInput(array $input): array
    {
        $recipient = trim((string) ($input['recipient_name'] ?? ''));
        $phone     = trim((string) ($input['phone'] ?? ''));
        $address   = trim((string) ($input['address'] ?? ''));
        $city      = trim((string) ($input['city'] ?? ''));
        $province  = trim((string) ($input['province'] ?? ''));
        $postal    = trim((string) ($input['postal_code'] ?? ''));

        if (mb_strlen($recipient) < 3 || mb_strlen($recipient) > 120) {
            return ['ok' => false, 'error' => 'Nama penerima harus 3–120 karakter.'];
        }
        if (! preg_match('/^[0-9+\-\s]{8,20}$/', $phone)) {
            return ['ok' => false, 'error' => 'Nomor telepon tidak valid (8–20 karakter).'];
        }
        if (mb_strlen($address) < 8 || mb_strlen($address) > 500) {
            return ['ok' => false, 'error' => 'Alamat lengkap minimal 8 karakter.'];
        }
        if (mb_strlen($city) < 2 || mb_strlen($city) > 100) {
            return ['ok' => false, 'error' => 'Kota harus 2–100 karakter.'];
        }
        if (mb_strlen($province) < 2 || mb_strlen($province) > 100) {
            return ['ok' => false, 'error' => 'Provinsi harus 2–100 karakter.'];
        }
        if (! preg_match('/^\d{5}$/', $postal)) {
            return ['ok' => false, 'error' => 'Kode pos harus terdiri dari tepat 5 angka.'];
        }

        return [
            'ok'   => true,
            'data' => [
                'recipient_name' => $recipient,
                'phone'          => $phone,
                'address'        => $address,
                'city'           => $city,
                'province'       => $province,
                'postal_code'    => $postal,
                'is_default'     => ! empty($input['is_default']) ? 1 : 0,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listAddresses(int $userId): array
    {
        $rows = $this->addressModel->getByUser($userId);
        foreach ($rows as &$row) {
            $row['in_use'] = $this->addressModel->isUsedInOrders((int) $row['id']);
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $input
     *
     * @return array{ok: bool, status: int, message?: string, addresses?: list<array<string, mixed>>}
     */
    public function createAddress(int $userId, array $input): array
    {
        $validated = $this->validateAddressInput($input);
        if (! $validated['ok']) {
            return ['ok' => false, 'status' => 422, 'message' => $validated['error']];
        }

        $data            = $validated['data'];
        $data['user_id'] = $userId;

        if ($data['is_default'] === 1) {
            $this->addressModel->where('user_id', $userId)->set(['is_default' => 0])->update();
        }

        $this->addressModel->insert($data);

        return [
            'ok'        => true,
            'status'    => 201,
            'message'   => 'Alamat pengiriman berhasil ditambahkan.',
            'addresses' => $this->listAddresses($userId),
        ];
    }

    /**
     * @param array<string, mixed> $input
     *
     * @return array{ok: bool, status: int, message?: string, addresses?: list<array<string, mixed>>}
     */
    public function updateAddress(int $userId, int $addressId, array $input): array
    {
        $owned = $this->addressModel->findOwned($addressId, $userId);
        if ($owned === null) {
            return ['ok' => false, 'status' => 404, 'message' => 'Alamat tidak ditemukan.'];
        }

        if ($this->addressModel->isUsedInOrders($addressId)) {
            return [
                'ok'      => false,
                'status'  => 409,
                'message' => 'Alamat yang sudah dipakai dalam pesanan tidak dapat diubah.',
            ];
        }

        $validated = $this->validateAddressInput($input);
        if (! $validated['ok']) {
            return ['ok' => false, 'status' => 422, 'message' => $validated['error']];
        }

        $data = $validated['data'];
        if ($data['is_default'] === 1) {
            $this->addressModel->where('user_id', $userId)->set(['is_default' => 0])->update();
        }

        $this->addressModel->update($addressId, $data);

        return [
            'ok'        => true,
            'status'    => 200,
            'message'   => 'Alamat pengiriman berhasil diperbarui.',
            'addresses' => $this->listAddresses($userId),
        ];
    }

    /**
     * @return array{ok: bool, status: int, message?: string, addresses?: list<array<string, mixed>>}
     */
    public function deleteAddress(int $userId, int $addressId): array
    {
        $owned = $this->addressModel->findOwned($addressId, $userId);
        if ($owned === null) {
            return ['ok' => false, 'status' => 404, 'message' => 'Alamat tidak ditemukan.'];
        }

        if ($this->addressModel->isUsedInOrders($addressId)) {
            return [
                'ok'      => false,
                'status'  => 409,
                'message' => 'Alamat yang sudah dipakai dalam pesanan tidak dapat dihapus.',
            ];
        }

        $this->addressModel->delete($addressId);

        return [
            'ok'        => true,
            'status'    => 200,
            'message'   => 'Alamat pengiriman berhasil dihapus.',
            'addresses' => $this->listAddresses($userId),
        ];
    }

    /**
     * @return array{items: list<array<string, mixed>>, subtotal: float, count: int}
     */
    public function getCart(int $userId): array
    {
        $cartModel = new CartModel();
        $items     = $cartModel->getCartItems($userId);
        $subtotal  = 0.0;
        $count     = 0;

        foreach ($items as &$item) {
            $lineTotal        = (float) $item['price'] * (int) $item['quantity'];
            $item['subtotal'] = $lineTotal;
            $subtotal        += $lineTotal;
            $count           += (int) $item['quantity'];
        }

        return [
            'items'    => $items,
            'subtotal' => $subtotal,
            'count'    => $count,
        ];
    }

    /**
     * Tambah item atau ubah jumlah di keranjang. Jumlah 0 menghapus item.
     *
     * @return array{ok: bool, status: int, message: string, cart?: array<string, mixed>}
     */
    public function upsertCartItem(int $userId, int $productId, int $quantity, string $mode = 'set'): array
    {
        if ($productId <= 0) {
            return ['ok' => false, 'status' => 422, 'message' => 'Produk tidak valid.'];
        }

        $cartModel = new CartModel();
        $cartId    = $cartModel->getOrCreateCartId($userId);

        $existing = $this->db->table('cart_items')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->get()
            ->getRowArray();

        $targetQty = $mode === 'add' && is_array($existing)
            ? (int) $existing['quantity'] + $quantity
            : $quantity;

        if ($targetQty <= 0) {
            if (is_array($existing)) {
                $this->db->table('cart_items')->where('id', $existing['id'])->delete();
            }

            return [
                'ok'      => true,
                'status'  => 200,
                'message' => 'Item dihapus dari keranjang.',
                'cart'    => $this->getCart($userId),
            ];
        }

        $product = (new ProductModel())->find($productId);
        if (! is_array($product) || $product['status'] !== 'active') {
            return ['ok' => false, 'status' => 422, 'message' => 'Produk tidak tersedia atau nonaktif.'];
        }

        if ($targetQty > (int) $product['stock']) {
            return ['ok' => false, 'status' => 422, 'message' => 'Jumlah melebihi stok tersedia (' . $product['stock'] . ').'];
        }

        $now = date('Y-m-d H:i:s');
        if (is_array($existing)) {
            $this->db->table('cart_items')
                ->where('id', $existing['id'])
                ->update(['quantity' => $targetQty, 'updated_at' => $now]);
        } else {
            $this->db->table('cart_items')->insert([
                'cart_id'    => $cartId,
                'product_id' => $productId,
                'quantity'   => $targetQty,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return [
            'ok'      => true,
            'status'  => 200,
            'message' => 'Keranjang berhasil diperbarui.',
            'cart'    => $this->getCart($userId),
        ];
    }

    /**
     * Memperbarui profil dan (opsional) password customer.
     *
     * @param array<string, mixed> $input
     *
     * @return array{ok: bool, status: int, message: string}
     */
    public function updateProfile(int $userId, array $input): array
    {
        $name    = trim((string) ($input['name'] ?? ''));
        $phone   = trim((string) ($input['phone'] ?? ''));
        $address = trim((string) ($input['address'] ?? ''));
        $oldPass = (string) ($input['old_password'] ?? '');
        $newPass = (string) ($input['new_password'] ?? '');

        if (mb_strlen($name) < 3 || mb_strlen($name) > 100) {
            return ['ok' => false, 'status' => 422, 'message' => 'Nama minimal 3–100 karakter.'];
        }

        $userModel = new UserModel();
        $user      = $userModel->find($userId);
        if (! is_array($user)) {
            return ['ok' => false, 'status' => 404, 'message' => 'Pengguna tidak ditemukan.'];
        }

        $updateData = [
            'name'    => $name,
            'phone'   => $phone !== '' ? $phone : null,
            'address' => $address !== '' ? $address : null,
        ];

        if ($newPass !== '') {
            if ($oldPass === '' || ! password_verify($oldPass, (string) $user['password'])) {
                return ['ok' => false, 'status' => 422, 'message' => 'Password lama tidak sesuai.'];
            }
            if (mb_strlen($newPass) < 8) {
                return ['ok' => false, 'status' => 422, 'message' => 'Password baru minimal 8 karakter.'];
            }
            $updateData['password'] = password_hash($newPass, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $updateData);
        session()->set('name', $name);

        return ['ok' => true, 'status' => 200, 'message' => 'Profil berhasil diperbarui.'];
    }

    /**
     * Proses checkout via API dalam satu transaksi database.
     *
     * @param array<string, mixed> $input
     *
     * @return array{ok: bool, status: int, message: string, order_number?: string}
     */
    public function checkout(int $userId, array $input): array
    {
        $cartModel = new CartModel();
        $items     = $cartModel->getCartItems($userId);
        if ($items === []) {
            return ['ok' => false, 'status' => 422, 'message' => 'Keranjang masih kosong.'];
        }

        $addressId = (int) ($input['address_id'] ?? 0);
        if ($addressId <= 0 || $this->addressModel->findOwned($addressId, $userId) === null) {
            return ['ok' => false, 'status' => 422, 'message' => 'Alamat pengiriman tidak valid.'];
        }

        $notes         = trim((string) ($input['notes'] ?? ''));
        $paymentMethod = trim((string) ($input['payment_method'] ?? 'transfer_bank')) ?: 'transfer_bank';
        $orderNumber   = 'SE-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        $this->db->transBegin();

        try {
            $subtotal      = 0.0;
            $preparedItems = [];

            foreach ($items as $cartItem) {
                $product = $this->db->table('products')
                    ->where('id', $cartItem['product_id'])
                    ->get()
                    ->getRowArray();

                if (! is_array($product) || $product['status'] !== 'active') {
                    throw new \RuntimeException('Produk "' . $cartItem['name'] . '" tidak tersedia.');
                }

                $qty = (int) $cartItem['quantity'];
                if ($qty < 1 || $qty > (int) $product['stock']) {
                    throw new \RuntimeException('Stok "' . $product['name'] . '" tidak mencukupi.');
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
                $this->db->table('order_items')->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $row['product_id'],
                    'product_name' => $row['product_name'],
                    'price'        => $row['price'],
                    'quantity'     => $row['quantity'],
                    'subtotal'     => $row['subtotal'],
                ]);

                $updateProduct = [
                    'stock'      => $row['new_stock'],
                    'updated_at' => $now,
                ];
                if ($row['new_stock'] === 0) {
                    $updateProduct['status'] = 'inactive';
                }

                $this->db->table('products')->where('id', $row['product_id'])->update($updateProduct);
            }

            $this->db->table('payments')->insert([
                'order_id'       => $orderId,
                'method'         => $paymentMethod,
                'amount'         => $total,
                'payment_status' => 'unpaid',
            ]);

            $this->db->table('shipments')->insert([
                'order_id'        => $orderId,
                'shipment_status' => 'pending',
            ]);

            $cartModel->clearCart($userId);

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan transaksi checkout.');
            }

            $this->db->transCommit();

            return [
                'ok'           => true,
                'status'       => 201,
                'message'      => 'Pesanan berhasil dibuat.',
                'order_number' => $orderNumber,
            ];
        } catch (\Throwable $e) {
            $this->db->transRollback();

            return ['ok' => false, 'status' => 422, 'message' => $e->getMessage()];
        }
    }
}
