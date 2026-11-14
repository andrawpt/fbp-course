<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    /**
     * Menampilkan halaman detail produk (FR-010).
     */
    public function detail(string $slug): string
    {
        $productModel = new ProductModel();
        $product      = $productModel->findActiveBySlug($slug);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan atau sudah tidak aktif.');
        }

        return view('frontend/product_detail', [
            'title'     => $product['name'] . ' – ShopEase',
            'product'   => $product,
            'cartCount' => $this->getCartCount(),
        ]);
    }
}
