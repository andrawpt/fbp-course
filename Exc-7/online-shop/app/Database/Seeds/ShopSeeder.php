<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        
        $passwordHash = password_hash('admin123', PASSWORD_BCRYPT);
        $userHash     = password_hash('user123', PASSWORD_BCRYPT);

        $users = [
            [
                'username'     => 'admin',
                'password'     => $passwordHash,
                'nama_lengkap' => 'Administrator Toko',
                'email'        => 'admin@onlineshop.com',
                'role'         => 'admin',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'budi',
                'password'     => $userHash,
                'nama_lengkap' => 'Budi Santoso',
                'email'        => 'budi@example.com',
                'role'         => 'customer',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('users')->insertBatch($users);

        
        $categories = [
            [
                'nama_kategori' => 'Elektronik & Gadget',
                'slug'          => 'elektronik-gadget',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_kategori' => 'Fashion & Pakaian',
                'slug'          => 'fashion-pakaian',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_kategori' => 'Aksesoris & Perlengkapan',
                'slug'          => 'aksesoris-perlengkapan',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('categories')->insertBatch($categories);

        
        $products = [
            [
                'category_id' => 1,
                'nama_produk' => 'Mechanical Keyboard Minimalist Wireless',
                'slug'        => 'mechanical-keyboard-minimalist-wireless',
                'harga'       => 750000,
                'stok'        => 25,
                'deskripsi'   => 'Keyboard mekanikal nirkabel dengan switch taktil premium, konektivitas Bluetooth 5.0 dan USB-C, desain monokrom hitam minimalis.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 1,
                'nama_produk' => 'Wireless Mouse Ergonomic Matte Black',
                'slug'        => 'wireless-mouse-ergonomic-matte-black',
                'harga'       => 320000,
                'stok'        => 40,
                'deskripsi'   => 'Mouse nirkabel ergonomis presisi tinggi hingga 4000 DPI, silent click, dan daya tahan baterai hingga 3 bulan.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 2,
                'nama_produk' => 'Oversized Heavyweight Cotton T-Shirt',
                'slug'        => 'oversized-heavyweight-cotton-t-shirt',
                'harga'       => 149000,
                'stok'        => 60,
                'deskripsi'   => 'Kaos katun premium 24s dengan potongan oversized modern, tebal, sejuk, dan nyaman dipakai harian.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 2,
                'nama_produk' => 'Minimalist Canvas Tote Bag Black',
                'slug'        => 'minimalist-canvas-tote-bag-black',
                'harga'       => 95000,
                'stok'        => 35,
                'deskripsi'   => 'Tas jinjing kanvas tebal dengan ritsleting utama dan kantong laptop 14 inci. Desain simpel serbaguna.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 3,
                'nama_produk' => 'Stainless Steel Tumbler 600ml Matte',
                'slug'        => 'stainless-steel-tumbler-600ml-matte',
                'harga'       => 185000,
                'stok'        => 50,
                'deskripsi'   => 'Tumbler tahan panas dan dingin hingga 12 jam, bahan stainless steel 304 food grade anti bocor.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 3,
                'nama_produk' => 'Desk Mat Leather Waterproof 80x40cm',
                'slug'        => 'desk-mat-leather-waterproof-80x40cm',
                'harga'       => 120000,
                'stok'        => 30,
                'deskripsi'   => 'Alas meja kerja kulit sintetis elegan, tahan air dan mudah dibersihkan. Melindungi permukaan meja kerja Anda.',
                'gambar'      => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('products')->insertBatch($products);
    }
}

