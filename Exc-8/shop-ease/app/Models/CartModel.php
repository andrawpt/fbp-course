<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table            = 'carts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Ambil atau buat keranjang milik user.
     */
    public function getOrCreateCartId(int $userId): int
    {
        $cart = $this->where('user_id', $userId)->first();
        if (is_array($cart)) {
            return (int) $cart['id'];
        }

        $this->insert(['user_id' => $userId]);

        return (int) $this->getInsertID();
    }

    /**
     * Hitung total kuantitas item di keranjang user.
     */
    public function getItemCountForUser(int $userId): int
    {
        $cart = $this->where('user_id', $userId)->first();
        if (! is_array($cart)) {
            return 0;
        }

        $row = $this->db->table('cart_items')
            ->selectSum('quantity', 'total_qty')
            ->where('cart_id', $cart['id'])
            ->get()
            ->getRowArray();

        return (int) ($row['total_qty'] ?? 0);
    }

    /**
     * Ambil seluruh item keranjang beserta informasi produk & kategori.
     *
     * @return list<array<string, mixed>>
     */
    public function getCartItems(int $userId): array
    {
        $cart = $this->where('user_id', $userId)->first();
        if (! is_array($cart)) {
            return [];
        }

        return $this->db->table('cart_items ci')
            ->select('ci.id AS item_id, ci.quantity, p.id AS product_id, p.name, p.slug, p.price, p.stock, p.icon, p.status, c.name AS category_name')
            ->join('products p', 'p.id = ci.product_id', 'inner')
            ->join('categories c', 'c.id = p.category_id', 'inner')
            ->where('ci.cart_id', $cart['id'])
            ->orderBy('ci.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Kosongkan semua item di keranjang user.
     */
    public function clearCart(int $userId): void
    {
        $cart = $this->where('user_id', $userId)->first();
        if (is_array($cart)) {
            $this->db->table('cart_items')->where('cart_id', $cart['id'])->delete();
        }
    }
}
