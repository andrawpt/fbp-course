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
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'icon',
        'badge',
        'status',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil daftar produk aktif dengan filter kategori dan kata kunci (FR-008, FR-009, BR-002).
     *
     * @return list<array<string, mixed>>
     */
    public function getActiveCatalog(string $keyword = '', string $categorySlug = ''): array
    {
        $builder = $this->db->table('products p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug')
            ->join('categories c', 'c.id = p.category_id', 'inner')
            ->where('p.status', 'active')
            ->where('c.is_active', 1);

        if ($categorySlug !== '') {
            $builder->where('c.slug', $categorySlug);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('p.name', $keyword)
                ->orLike('p.description', $keyword)
                ->orLike('c.name', $keyword)
                ->groupEnd();
        }

        return $builder->orderBy('p.id', 'ASC')->get()->getResultArray();
    }

    /**
     * Ambil detail satu produk aktif berdasarkan slug.
     */
    public function findActiveBySlug(string $slug): ?array
    {
        $row = $this->db->table('products p')
            ->select('p.*, c.name AS category_name, c.slug AS category_slug')
            ->join('categories c', 'c.id = p.category_id', 'inner')
            ->where('p.slug', $slug)
            ->where('p.status', 'active')
            ->get()
            ->getRowArray();

        return is_array($row) ? $row : null;
    }
}
