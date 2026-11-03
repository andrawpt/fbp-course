<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'category_id',
        'nama_produk',
        'slug',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getWithCategory($categoryId = null, $keyword = null)
    {
        $builder = $this->select('products.*, categories.nama_kategori')
                        ->join('categories', 'categories.id = products.category_id', 'left');

        if ($categoryId) {
            $builder->where('products.category_id', $categoryId);
        }

        if ($keyword) {
            $builder->like('products.nama_produk', $keyword);
        }

        return $builder->orderBy('products.created_at', 'DESC')->findAll();
    }
}

