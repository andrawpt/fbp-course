<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Checkout extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $productModel;

    public function __construct()
    {
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->productModel   = new ProductModel();
        helper(['form', 'url', 'number']);
    }

    public function index()
    {
        $cart = session()->get('cart') ?? [];

        if (empty($cart)) {
            return redirect()->to(site_url('cart'))->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
        }

        return view('checkout/index', [
            'title'      => 'Checkout Pembelian - Online Shop',
            'cart'       => $cart,
            'totalHarga' => $totalHarga,
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function process()
    {
        $cart = session()->get('cart') ?? [];

        if (empty($cart)) {
            return redirect()->to(site_url('cart'))->with('error', 'Keranjang belanja Anda kosong.');
        }

        $rules = [
            'nama_penerima' => [
                'rules'  => 'required|min_length[2]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama penerima wajib diisi.',
                    'min_length' => 'Nama penerima minimal 2 karakter.',
                ],
            ],
            'telepon' => [
                'rules'  => 'required|min_length[5]|max_length[25]',
                'errors' => [
                    'required'   => 'Nomor telepon/WA wajib diisi.',
                    'min_length' => 'Nomor telepon minimal 5 karakter.',
                ],
            ],
            'alamat' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Alamat pengiriman wajib diisi.',
                    'min_length' => 'Alamat pengiriman minimal 3 karakter.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
        }

        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        $userId  = session()->get('userId') ?? null;

        
        $orderId = $this->orderModel->insert([
            'user_id'       => $userId,
            'invoice'       => $invoice,
            'total_harga'   => $totalHarga,
            'status'        => 'pending',
            'nama_penerima' => $this->request->getPost('nama_penerima'),
            'telepon'       => $this->request->getPost('telepon'),
            'alamat'        => $this->request->getPost('alamat'),
        ]);

        
        foreach ($cart as $item) {
            $this->orderItemModel->insert([
                'order_id'    => $orderId,
                'product_id'  => $item['id'],
                'nama_produk' => $item['nama_produk'],
                'harga'       => $item['harga'],
                'jumlah'      => $item['qty'],
                'subtotal'    => $item['harga'] * $item['qty'],
            ]);

            
            $product = $this->productModel->find($item['id']);
            if ($product) {
                $newStock = max(0, $product['stok'] - $item['qty']);
                $this->productModel->update($item['id'], ['stok' => $newStock]);
            }
        }

        
        session()->remove('cart');

        return redirect()->to(site_url('checkout/success/' . $invoice));
    }

    public function success($invoice)
    {
        $order = $this->orderModel->where('invoice', $invoice)->first();

        if (!$order) {
            throw PageNotFoundException::forPageNotFound('Invoice pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->where('order_id', $order['id'])->findAll();

        return view('checkout/success', [
            'title' => 'Pesanan Berhasil - ' . $invoice,
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function history()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login untuk melihat riwayat pesanan.');
        }

        $userId = session()->get('userId');
        $orders = $this->orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return view('checkout/history', [
            'title'  => 'Riwayat Pesanan Saya - Online Shop',
            'orders' => $orders,
        ]);
    }
}

