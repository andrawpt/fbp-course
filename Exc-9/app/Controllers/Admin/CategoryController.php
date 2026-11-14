<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\RedirectResponse;

class CategoryController extends BaseController
{
    public function index(): string
    {
        $categoryModel = new CategoryModel();
        $categories    = $categoryModel->orderBy('id', 'ASC')->findAll();

        return view('admin/categories', [
            'title'      => 'Kelola Kategori – Admin ShopEase',
            'categories' => $categories,
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'icon' => 'permit_empty|max_length[60]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $slug = url_title($name, '-', true);

        $categoryModel = new CategoryModel();
        if ($categoryModel->where('slug', $slug)->countAllResults() > 0) {
            $slug .= '-' . substr((string) time(), -4);
        }

        $categoryModel->insert([
            'name'        => $name,
            'slug'        => $slug,
            'icon'        => trim((string) $this->request->getPost('icon')) ?: 'bi-tag',
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'is_active'   => 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('admin/kategori'))
            ->with('success', 'Kategori "' . $name . '" berhasil ditambahkan.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $categoryModel = new CategoryModel();
        $category      = $categoryModel->find($id);

        if (! is_array($category)) {
            return redirect()->to(site_url('admin/kategori'))->with('error', 'Kategori tidak ditemukan.');
        }

        $newState = ((int) $category['is_active'] === 1) ? 0 : 1;
        $categoryModel->update($id, ['is_active' => $newState]);

        return redirect()->to(site_url('admin/kategori'))
            ->with('success', 'Status kategori "' . $category['name'] . '" berhasil diperbarui.');
    }
}
