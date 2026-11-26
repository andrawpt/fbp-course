<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table            = 'product_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['product_id', 'image_path', 'is_primary', 'created_at'];

    /**
     * Ambil semua foto galeri milik suatu produk, foto utama diurutkan pertama.
     *
     * @return list<array<string, mixed>>
     */
    public function getByProduct(int $productId): array
    {
        return $this->where('product_id', $productId)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}
