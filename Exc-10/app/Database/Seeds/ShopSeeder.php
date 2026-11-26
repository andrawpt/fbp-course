<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => 'bi-laptop', 'description' => 'Gadget, audio, dan perangkat kerja harian.', 'is_active' => 1, 'created_at' => $now],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon' => 'bi-bag-heart', 'description' => 'Pakaian kasual, sepatu, dan tas pilihan.', 'is_active' => 1, 'created_at' => $now],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'icon' => 'bi-smartwatch', 'description' => 'Jam tangan, kacamata, dan pelengkap gaya.', 'is_active' => 1, 'created_at' => $now],
            ['name' => 'Rumah Tangga', 'slug' => 'rumah-tangga', 'icon' => 'bi-house-heart', 'description' => 'Peralatan rumah praktis dan estetik.', 'is_active' => 1, 'created_at' => $now],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'icon' => 'bi-bicycle', 'description' => 'Perlengkapan kebugaran dan aktivitas luar.', 'is_active' => 1, 'created_at' => $now],
            ['name' => 'Buku & Alat Tulis', 'slug' => 'buku-alat-tulis', 'icon' => 'bi-journal-bookmark', 'description' => 'Kebutuhan belajar, kuliah, dan kantor.', 'is_active' => 1, 'created_at' => $now],
        ];

        $catBuilder = $this->db->table('categories');
        $catMap     = [];

        foreach ($categories as $cat) {
            $existing = $catBuilder->where('slug', $cat['slug'])->get()->getRowArray();
            if ($existing === null) {
                $catBuilder->insert($cat);
                $catMap[$cat['slug']] = (int) $this->db->insertID();
            } else {
                $catMap[$cat['slug']] = (int) $existing['id'];
            }
        }

        $products = [
            [
                'category_id' => $catMap['elektronik'],
                'name'        => 'Headphone Wireless Studio Pro',
                'slug'        => 'headphone-wireless-studio-pro',
                'description' => 'Audio jernih dengan peredam bising aktif dan baterai tahan hingga 30 jam.',
                'price'       => 485000,
                'stock'       => 18,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-headphones',
                'badge'       => 'Terlaris',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['elektronik'],
                'name'        => 'Mechanical Keyboard Compact 75%',
                'slug'        => 'mechanical-keyboard-compact-75',
                'description' => 'Switch tactile responsif, lampu latar putih, cocok untuk coding dan kerja.',
                'price'       => 620000,
                'stock'       => 4,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-keyboard',
                'badge'       => 'Unggulan',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['fashion'],
                'name'        => 'Jaket Oversize Canvas Minimalis',
                'slug'        => 'jaket-oversize-canvas-minimalis',
                'description' => 'Bahan katun kanvas premium yang nyaman dipakai harian di iklim tropis.',
                'price'       => 279000,
                'stock'       => 25,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-person-standing',
                'badge'       => 'Baru',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['fashion'],
                'name'        => 'Ransel Laptop Anti Air 15.6"',
                'slug'        => 'ransel-laptop-anti-air',
                'description' => 'Kompartemen empuk untuk laptop, banyak saku organizer, dan bahan tahan percikan air.',
                'price'       => 315000,
                'stock'       => 14,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-backpack',
                'badge'       => 'Favorit',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['aksesoris'],
                'name'        => 'Smartband Fitness Tracker V4',
                'slug'        => 'smartband-fitness-tracker-v4',
                'description' => 'Pantau langkah harian, detak jantung, dan notifikasi pesan langsung di pergelangan.',
                'price'       => 349000,
                'stock'       => 20,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-smartwatch',
                'badge'       => 'Promo',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['rumah-tangga'],
                'name'        => 'Lampu Meja Belajar LED Touch',
                'slug'        => 'lampu-meja-belajar-led-touch',
                'description' => '3 mode pencahayaan ramah mata dengan pengatur kecerahan sentuh dan port USB.',
                'price'       => 145000,
                'stock'       => 32,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-lamp',
                'badge'       => 'Hemat',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['olahraga'],
                'name'        => 'Botol Minum Insulated 750ml',
                'slug'        => 'botol-minum-insulated-750ml',
                'description' => 'Stainless steel food-grade menjaga suhu dingin hingga 12 jam saat beraktivitas.',
                'price'       => 129000,
                'stock'       => 40,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-cup-straw',
                'badge'       => 'Stok Banyak',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'category_id' => $catMap['buku-alat-tulis'],
                'name'        => 'Paket Notebook Dotted & Pulpen Gel',
                'slug'        => 'paket-notebook-dotted-pulpen-gel',
                'description' => 'Kertas tebal 100gsm anti tembus tinta, cocok untuk catatan kuliah dan sketsa.',
                'price'       => 89000,
                'stock'       => 45,
                'image'       => '/images/placeholder.svg',
                'icon'        => 'bi-journal-text',
                'badge'       => 'Pilihan',
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        $prodBuilder = $this->db->table('products');
        foreach ($products as $prod) {
            if ($prodBuilder->where('slug', $prod['slug'])->countAllResults() === 0) {
                $prodBuilder->insert($prod);
            }
        }

        // Seed alamat contoh untuk customer@shopease.com
        $customer = $this->db->table('users')->where('email', 'customer@shopease.com')->get()->getRowArray();
        if ($customer !== null) {
            $addrBuilder = $this->db->table('addresses');
            if ($addrBuilder->where('user_id', $customer['id_user'])->countAllResults() === 0) {
                $addrBuilder->insert([
                    'user_id'        => $customer['id_user'],
                    'recipient_name' => 'Budi Santoso',
                    'phone'          => '081234567890',
                    'address'        => 'Jl. Merdeka Belajar No. 42, Kel. Sukamaju',
                    'city'           => 'Bandung',
                    'province'       => 'Jawa Barat',
                    'postal_code'    => '40115',
                    'is_default'     => 1,
                ]);
            }
        }

        // Seed data karyawan contoh (tabel terpisah dari users)
        $karyawanList = [
            [
                'nip'        => '20240001',
                'name'       => 'Rina Kusuma',
                'position'   => 'Admin Toko',
                'email'      => 'rina.kusuma@shopease.id',
                'phone'      => '081211112222',
                'hire_date'  => '2024-02-10',
                'status'     => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nip'        => '20240002',
                'name'       => 'Dimas Pratama',
                'position'   => 'Staf Gudang',
                'email'      => 'dimas.pratama@shopease.id',
                'phone'      => '081233334444',
                'hire_date'  => '2024-05-18',
                'status'     => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nip'        => '20250003',
                'name'       => 'Siti Aminah',
                'position'   => 'Customer Service',
                'email'      => 'siti.aminah@shopease.id',
                'phone'      => '081255556666',
                'hire_date'  => '2025-01-06',
                'status'     => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $karBuilder = $this->db->table('karyawan');
        foreach ($karyawanList as $kar) {
            if ($karBuilder->where('nip', $kar['nip'])->countAllResults() === 0) {
                $karBuilder->insert($kar);
            }
        }
    }
}
