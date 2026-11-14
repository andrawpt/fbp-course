<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\RedirectResponse;

class ProductController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $products = $db->table('products p')
            ->select('p.*, c.name AS category_name')
            ->join('categories c', 'c.id = p.category_id', 'inner')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        $categories = (new CategoryModel())->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        return view('admin/products', [
            'title'      => 'Kelola Produk – Admin ShopEase',
            'products'   => $products,
            'categories' => $categories,
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'name'        => 'required|min_length[3]|max_length[180]',
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name         = trim((string) $this->request->getPost('name'));
        $slug         = url_title($name, '-', true);
        $productModel = new ProductModel();

        if ($productModel->where('slug', $slug)->countAllResults() > 0) {
            $slug .= '-' . substr((string) time(), -4);
        }

        $productModel->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'icon'        => trim((string) $this->request->getPost('icon')) ?: 'bi-box',
            'badge'       => trim((string) $this->request->getPost('badge')) ?: 'Baru',
            'status'      => 'active',
        ]);

        return redirect()->to(site_url('admin/produk'))
            ->with('success', 'Produk "' . $name . '" berhasil ditambahkan.');
    }

    public function updateStock(int $id): RedirectResponse
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! is_array($product)) {
            return redirect()->to(site_url('admin/produk'))->with('error', 'Produk tidak ditemukan.');
        }

        $stock = (int) $this->request->getPost('stock');
        $price = (float) $this->request->getPost('price');

        if ($stock < 0 || $price < 0) {
            return redirect()->to(site_url('admin/produk'))->with('error', 'Harga dan stok tidak boleh negatif.');
        }

        $productModel->update($id, [
            'stock' => $stock,
            'price' => $price,
        ]);

        return redirect()->to(site_url('admin/produk'))
            ->with('success', 'Stok & harga "' . $product['name'] . '" diperbarui.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if (! is_array($product)) {
            return redirect()->to(site_url('admin/produk'))->with('error', 'Produk tidak ditemukan.');
        }

        $newStatus = ($product['status'] === 'active') ? 'inactive' : 'active';
        $productModel->update($id, ['status' => $newStatus]);

        return redirect()->to(site_url('admin/produk'))
            ->with('success', 'Status produk "' . $product['name'] . '" diubah menjadi ' . $newStatus . '.');
    }
}
