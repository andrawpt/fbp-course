<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;

class InventoryController extends BaseController
{
    public function index(): string
    {
        $tab = (string) ($this->request->getGet('tab') ?: 'low');

        $productModel = new ProductModel();
        $productModel->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left');

        if ($tab === 'low') {
            $productModel->where('products.stock >=', 1)->where('products.stock <=', 5);
        } elseif ($tab === 'out') {
            $productModel->where('products.stock', 0);
        }

        $products = $productModel->orderBy('products.stock', 'ASC')->orderBy('products.id', 'DESC')->findAll();

        $db = \Config\Database::connect();
        $counts = [
            'low' => $db->table('products')->where('stock >=', 1)->where('stock <=', 5)->countAllResults(),
            'out' => $db->table('products')->where('stock', 0)->countAllResults(),
            'all' => $db->table('products')->countAllResults(),
        ];

        return view('admin/inventory/index', [
            'title'    => 'Kelola Inventori – ShopEase',
            'tab'      => $tab,
            'products' => $products,
            'counts'   => $counts,
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! is_array($product)) {
            return redirect()->to(site_url('admin/inventory'))
                ->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'stock' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[1000000000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Nilai stok harus bilangan bulat 0 hingga 1.000.000.000.');
        }

        $stock      = (int) $this->request->getPost('stock');
        $updateData = ['stock' => $stock];

        // Stok nol otomatis menonaktifkan produk; stok > 0 mempertahankan status sebelumnya
        if ($stock === 0) {
            $updateData['status'] = 'inactive';
        }

        $productModel->update($id, $updateData);

        return redirect()->back()
            ->with('success', 'Stok produk "' . $product['name'] . '" berhasil diperbarui menjadi ' . $stock . '.');
    }
}
