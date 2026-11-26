<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class CategoryController extends BaseController
{
    public function index(): string
    {
        $categoryModel = new CategoryModel();
        $categories    = $categoryModel->orderBy('name', 'ASC')->findAll();

        $db = \Config\Database::connect();
        foreach ($categories as &$cat) {
            $cat['product_count'] = $db->table('products')
                ->where('category_id', $cat['id'])
                ->countAllResults();
        }

        return view('admin/categories/index', [
            'title'      => 'Kelola Kategori – ShopEase',
            'categories' => $categories,
        ]);
    }

    public function create(): string
    {
        return view('admin/categories/form', [
            'title'    => 'Tambah Kategori – ShopEase',
            'category' => null,
            'action'   => site_url('admin/categories/store'),
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[10000]',
            'icon'        => 'permit_empty|max_length[60]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name        = trim((string) $this->request->getPost('name'));
        $description = trim((string) $this->request->getPost('description'));
        $icon        = trim((string) $this->request->getPost('icon')) ?: 'bi-tag';
        $randomId    = bin2hex(random_bytes(3));
        $slug        = url_title($name, '-', true) . '-' . $randomId;

        $categoryModel = new CategoryModel();
        $categoryModel->insert([
            'name'        => $name,
            'slug'        => $slug,
            'icon'        => $icon,
            'description' => $description !== '' ? $description : null,
            'is_active'   => 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/categories'))
            ->with('success', 'Kategori "' . $name . '" berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $categoryModel = new CategoryModel();
        $category      = $categoryModel->find($id);

        if (! is_array($category)) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        return view('admin/categories/form', [
            'title'    => 'Edit Kategori – ShopEase',
            'category' => $category,
            'action'   => site_url('admin/categories/update/' . $id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $categoryModel = new CategoryModel();
        $category      = $categoryModel->find($id);

        if (! is_array($category)) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[10000]',
            'icon'        => 'permit_empty|max_length[60]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name        = trim((string) $this->request->getPost('name'));
        $description = trim((string) $this->request->getPost('description'));
        $icon        = trim((string) $this->request->getPost('icon')) ?: 'bi-tag';
        $isActive    = $this->request->getPost('is_active') !== null ? (int) $this->request->getPost('is_active') : (int) $category['is_active'];

        $categoryModel->update($id, [
            'name'        => $name,
            'icon'        => $icon,
            'description' => $description !== '' ? $description : null,
            'is_active'   => $isActive ? 1 : 0,
        ]);

        return redirect()->to(site_url('admin/categories'))
            ->with('success', 'Kategori "' . $name . '" berhasil diperbarui.');
    }

    public function delete(int $id): RedirectResponse
    {
        $categoryModel = new CategoryModel();
        $category      = $categoryModel->find($id);

        if (! is_array($category)) {
            return redirect()->to(site_url('admin/categories'))
                ->with('error', 'Kategori tidak ditemukan.');
        }

        $db           = \Config\Database::connect();
        $productCount = $db->table('products')
            ->where('category_id', $id)
            ->countAllResults();

        if ($productCount > 0) {
            return redirect()->to(site_url('admin/categories'))
                ->with('error', 'Kategori "' . $category['name'] . '" tidak dapat dihapus karena masih digunakan oleh ' . $productCount . ' produk.');
        }

        $categoryModel->delete($id);

        return redirect()->to(site_url('admin/categories'))
            ->with('success', 'Kategori "' . $category['name'] . '" berhasil dihapus.');
    }
}
