<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class HomeController extends BaseController
{
    /**
     * Menampilkan halaman landing page (Homepage) ShopEase.
     */
    public function index(): string
    {
        $keyword          = trim((string) $this->request->getGet('q'));
        $selectedCategory = trim((string) $this->request->getGet('kategori'));

        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $categories     = $categoryModel->getActiveWithProductCount();
        $activeProducts = $productModel->getActiveCatalog($keyword, $selectedCategory);

        $data = [
            'title'            => 'ShopEase – Online Store | Belanja Mudah & Terpercaya',
            'keyword'          => $keyword,
            'selectedCategory' => $selectedCategory,
            'categories'       => $categories,
            'products'         => $activeProducts,
            'steps'            => $this->getShoppingSteps(),
            'cartCount'        => $this->getCartCount(),
        ];

        return view('frontend/home', $data);
    }

    /**
     * Alur belanja customer sesuai Section 5.1 pada PRD.
     *
     * @return list<array<string, string>>
     */
    private function getShoppingSteps(): array
    {
        return [
            [
                'number' => '1',
                'icon'   => 'bi-search',
                'title'  => 'Cari & Pilih Produk',
                'desc'   => 'Jelajahi katalog berdasarkan kategori, periksa harga Rupiah dan ketersediaan stok secara langsung.',
            ],
            [
                'number' => '2',
                'icon'   => 'bi-cart-plus',
                'title'  => 'Tambah ke Keranjang',
                'desc'   => 'Atur kuantitas produk pilihan Anda di keranjang belanja sebelum melanjutkan ke proses checkout.',
            ],
            [
                'number' => '3',
                'icon'   => 'bi-receipt',
                'title'  => 'Checkout & Upload Bukti',
                'desc'   => 'Pilih alamat pengiriman, konfirmasi total pesanan, dan unggah bukti pembayaran manual dengan mudah.',
            ],
            [
                'number' => '4',
                'icon'   => 'bi-truck',
                'title'  => 'Pantau Status & Resi',
                'desc'   => 'Lacak verifikasi pembayaran, pemrosesan pesanan, hingga nomor resi pengiriman sampai tujuan.',
            ],
        ];
    }
}
