<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Home extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        helper(['url', 'number']);
    }

    public function index()
    {
        $categoryId = $this->request->getGet('kategori');
        $keyword    = $this->request->getGet('cari');

        $categories = $this->categoryModel->findAll();
        $products   = $this->productModel->getWithCategory($categoryId, $keyword);

        return view('home/index', [
            'title'             => 'Katalog Produk - Minimalist Online Shop',
            'categories'        => $categories,
            'products'          => $products,
            'selectedCategory'  => $categoryId,
            'keyword'           => $keyword,
        ]);
    }

    public function detail($slug)
    {
        $product = $this->productModel
                        ->select('products.*, categories.nama_kategori')
                        ->join('categories', 'categories.id = products.category_id', 'left')
                        ->where('products.slug', $slug)
                        ->first();

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $relatedProducts = $this->productModel
                                ->where('category_id', $product['category_id'])
                                ->where('id !=', $product['id'])
                                ->findAll(4);

        return view('home/detail', [
            'title'           => $product['nama_produk'] . ' - Online Shop',
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
