<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;

class CartController extends BaseController
{
    /**
     * Menampilkan halaman keranjang belanja (FR-011).
     */
    public function index(): string
    {
        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $items     = $cartModel->getCartItems($userId);

        $subtotal = 0.0;
        foreach ($items as &$item) {
            $itemSubtotal     = (float) $item['price'] * (int) $item['quantity'];
            $item['subtotal'] = $itemSubtotal;
            $subtotal        += $itemSubtotal;
        }

        return view('frontend/cart', [
            'title'     => 'Keranjang Belanja – ShopEase',
            'items'     => $items,
            'subtotal'  => $subtotal,
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /**
     * Menambahkan produk ke keranjang (BR-002, BR-003).
     */
    public function add(): RedirectResponse
    {
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) ($this->request->getPost('quantity') ?? 1);

        if ($productId <= 0 || $quantity < 1) {
            return redirect()->back()->with('error', 'Jumlah produk tidak valid (minimal 1).');
        }

        $productModel = new ProductModel();
        $product      = $productModel->find($productId);

        if (! is_array($product) || $product['status'] !== 'active') {
            return redirect()->back()->with('error', 'Produk tidak tersedia atau sedang nonaktif.');
        }

        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $cartId    = $cartModel->getOrCreateCartId($userId);

        $db           = \Config\Database::connect();
        $itemsTable   = $db->table('cart_items');
        $existingItem = $itemsTable
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->get()
            ->getRowArray();

        $newQty = $quantity + (is_array($existingItem) ? (int) $existingItem['quantity'] : 0);

        if ($newQty > (int) $product['stock']) {
            return redirect()->back()->with('error', 'Stok produk "' . $product['name'] . '" hanya tersisa ' . $product['stock'] . '.');
        }

        $now = date('Y-m-d H:i:s');
        if (is_array($existingItem)) {
            $itemsTable->where('id', $existingItem['id'])->update([
                'quantity'   => $newQty,
                'updated_at' => $now,
            ]);
        } else {
            $itemsTable->insert([
                'cart_id'    => $cartId,
                'product_id' => $productId,
                'quantity'   => $newQty,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return redirect()->back()->with('success', '"' . $product['name'] . '" berhasil ditambahkan ke keranjang.');
    }

    /**
     * Memperbarui kuantitas item di keranjang (FR-011, BR-003).
     */
    public function update(): RedirectResponse
    {
        $itemId   = (int) $this->request->getPost('item_id');
        $quantity = (int) $this->request->getPost('quantity');

        if ($itemId <= 0 || $quantity < 1) {
            return redirect()->to(site_url('keranjang'))->with('error', 'Kuantitas item minimal 1.');
        }

        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $cartId    = $cartModel->getOrCreateCartId($userId);

        $db   = \Config\Database::connect();
        $item = $db->table('cart_items ci')
            ->select('ci.id, p.name, p.stock, p.status')
            ->join('products p', 'p.id = ci.product_id', 'inner')
            ->where('ci.id', $itemId)
            ->where('ci.cart_id', $cartId)
            ->get()
            ->getRowArray();

        if (! is_array($item)) {
            return redirect()->to(site_url('keranjang'))->with('error', 'Item keranjang tidak ditemukan.');
        }

        if ($item['status'] !== 'active') {
            return redirect()->to(site_url('keranjang'))->with('error', 'Produk sudah tidak aktif.');
        }

        if ($quantity > (int) $item['stock']) {
            return redirect()->to(site_url('keranjang'))->with('error', 'Stok "' . $item['name'] . '" hanya tersedia ' . $item['stock'] . '.');
        }

        $db->table('cart_items')->where('id', $itemId)->update([
            'quantity'   => $quantity,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('keranjang'))->with('success', 'Kuantitas produk berhasil diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove(): RedirectResponse
    {
        $itemId    = (int) $this->request->getPost('item_id');
        $userId    = (int) session()->get('user_id');
        $cartModel = new CartModel();
        $cartId    = $cartModel->getOrCreateCartId($userId);

        $db = \Config\Database::connect();
        $db->table('cart_items')
            ->where('id', $itemId)
            ->where('cart_id', $cartId)
            ->delete();

        return redirect()->to(site_url('keranjang'))->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
