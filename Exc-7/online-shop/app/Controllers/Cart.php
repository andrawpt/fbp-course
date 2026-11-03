<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        helper(['url', 'number']);
    }

    public function index()
    {
        $cart = session()->get('cart') ?? [];
        $totalHarga = 0;

        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
        }

        return view('cart/index', [
            'title'      => 'Keranjang Belanja - Online Shop',
            'cart'       => $cart,
            'totalHarga' => $totalHarga,
        ]);
    }

    public function add($id)
    {
        $product = $this->productModel->find($id);

        if (!$product || $product['stok'] <= 0) {
            return redirect()->back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        $cart = session()->get('cart') ?? [];
        $qty = (int) ($this->request->getPost('qty') ?? 1);

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['qty'] + $qty;
            if ($newQty > $product['stok']) {
                $newQty = $product['stok'];
            }
            $cart[$id]['qty'] = $newQty;
        } else {
            if ($qty > $product['stok']) {
                $qty = $product['stok'];
            }
            $cart[$id] = [
                'id'          => $product['id'],
                'nama_produk' => $product['nama_produk'],
                'slug'        => $product['slug'],
                'harga'       => $product['harga'],
                'gambar'      => $product['gambar'],
                'qty'         => $qty,
                'stok'        => $product['stok'],
            ];
        }

        session()->set('cart', $cart);
        return redirect()->to(site_url('cart'))->with('success', $product['nama_produk'] . ' ditambahkan ke keranjang!');
    }

    public function update()
    {
        $quantities = $this->request->getPost('qty') ?? [];
        $cart = session()->get('cart') ?? [];

        foreach ($quantities as $id => $qty) {
            $qty = (int) $qty;
            if (isset($cart[$id])) {
                if ($qty <= 0) {
                    unset($cart[$id]);
                } else {
                    $product = $this->productModel->find($id);
                    if ($product && $qty > $product['stok']) {
                        $qty = $product['stok'];
                    }
                    $cart[$id]['qty'] = $qty;
                }
            }
        }

        session()->set('cart', $cart);
        return redirect()->to(site_url('cart'))->with('success', 'Keranjang belanja berhasil diperbarui.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }

        return redirect()->to(site_url('cart'))->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->remove('cart');
        return redirect()->to(site_url('cart'))->with('success', 'Keranjang belanja telah dikosongkan.');
    }
}

