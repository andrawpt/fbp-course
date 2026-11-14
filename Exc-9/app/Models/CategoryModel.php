<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'slug', 'icon', 'description', 'is_active', 'created_at'];

    /**
     * Ambil kategori aktif beserta jumlah produk aktif di dalamnya.
     *
     * @return list<array<string, mixed>>
     */
    public function getActiveWithProductCount(): array
    {
        $categories = $this->where('is_active', 1)->orderBy('id', 'ASC')->findAll();
        $db         = $this->db;

        foreach ($categories as &$category) {
            $category['product_count'] = $db->table('products')
                ->where('category_id', $category['id'])
                ->where('status', 'active')
                ->countAllResults();
        }

        return $categories;
    }
}
