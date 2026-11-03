<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Admin extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $userModel;

    public function __construct()
    {
        $this->productModel   = new ProductModel();
        $this->categoryModel  = new CategoryModel();
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel      = new UserModel();
        helper(['form', 'url', 'number', 'text']);
    }

    public function dashboard()
    {
        $totalProducts = $this->productModel->countAllResults();
        $totalOrders   = $this->orderModel->countAllResults();
        $totalUsers    = $this->userModel->countAllResults();

        
        $orders = $this->orderModel->findAll();
        $totalRevenue = 0;
        foreach ($orders as $o) {
            if ($o['status'] !== 'cancelled') {
                $totalRevenue += $o['total_harga'];
            }
        }

        $recentOrders = $this->orderModel->orderBy('created_at', 'DESC')->findAll(5);

        return view('admin/dashboard', [
            'title'         => 'Dashboard Admin - Toko Online',
            'totalProducts' => $totalProducts,
            'totalOrders'   => $totalOrders,
            'totalUsers'    => $totalUsers,
            'totalRevenue'  => $totalRevenue,
            'recentOrders'  => $recentOrders,
        ]);
    }

    public function products()
    {
        $products = $this->productModel->getWithCategory();

        return view('admin/products/index', [
            'title'    => 'Kelola Produk - Admin Online Shop',
            'products' => $products,
        ]);
    }

    public function createProduct()
    {
        return view('admin/products/create', [
            'title'      => 'Tambah Produk Baru',
            'categories' => $this->categoryModel->findAll(),
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
        ]);
    }

    public function storeProduct()
    {
        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[150]',
            'category_id' => 'required',
            'harga'       => 'required|numeric|greater_than[0]',
            'stok'        => 'required|numeric|greater_than_equal_to[0]',
            'deskripsi'   => 'permit_empty',
            'gambar'      => 'permit_empty|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]|max_size[gambar,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = null;

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/products', $namaGambar);
        }

        $namaProduk = $this->request->getPost('nama_produk');
        $slug = url_title($namaProduk, '-', true) . '-' . substr(uniqid(), -4);

        $this->productModel->save([
            'category_id' => $this->request->getPost('category_id'),
            'nama_produk' => $namaProduk,
            'slug'        => $slug,
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'gambar'      => $namaGambar,
        ]);

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduct($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        return view('admin/products/edit', [
            'title'      => 'Edit Produk - ' . $product['nama_produk'],
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
        ]);
    }

    public function updateProduct($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $rules = [
            'nama_produk' => 'required|min_length[3]|max_length[150]',
            'category_id' => 'required',
            'harga'       => 'required|numeric|greater_than[0]',
            'stok'        => 'required|numeric|greater_than_equal_to[0]',
            'deskripsi'   => 'permit_empty',
            'gambar'      => 'permit_empty|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]|max_size[gambar,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $product['gambar'];

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            if (!empty($product['gambar']) && file_exists(FCPATH . 'uploads/products/' . $product['gambar'])) {
                unlink(FCPATH . 'uploads/products/' . $product['gambar']);
            }
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads/products', $namaGambar);
        }

        $this->productModel->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'gambar'      => $namaGambar,
        ]);

        return redirect()->to(site_url('admin/products'))->with('success', 'Data produk berhasil diperbarui!');
    }

    public function deleteProduct($id)
    {
        $product = $this->productModel->find($id);

        if ($product) {
            if (!empty($product['gambar']) && file_exists(FCPATH . 'uploads/products/' . $product['gambar'])) {
                unlink(FCPATH . 'uploads/products/' . $product['gambar']);
            }
            $this->productModel->delete($id);
            return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->to(site_url('admin/products'))->with('error', 'Produk tidak ditemukan.');
    }

    public function orders()
    {
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/orders/index', [
            'title'  => 'Kelola Pesanan - Admin Online Shop',
            'orders' => $orders,
        ]);
    }

    public function orderDetail($id)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->where('order_id', $order['id'])->findAll();

        return view('admin/orders/detail', [
            'title' => 'Detail Pesanan - ' . $order['invoice'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function updateOrderStatus($id)
    {
        $status = $this->request->getPost('status');

        if (in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $this->orderModel->update($id, ['status' => $status]);
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Status tidak valid.');
    }
}

