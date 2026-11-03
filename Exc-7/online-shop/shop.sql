CREATE DATABASE IF NOT EXISTS `online_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `online_shop`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`) VALUES
(1, 'admin', '$2y$12$QaZsipMKcqRgxOsgiMVaau.B4x4VASLCvhoyl/U2eV8G8RUrhzJti', 'Administrator Toko', 'admin@onlineshop.com', 'admin'),
(2, 'budi', '$2y$12$QaZsipMKcqRgxOsgiMVaau.B4x4VASLCvhoyl/U2eV8G8RUrhzJti', 'Budi Santoso', 'budi@example.com', 'customer')
ON DUPLICATE KEY UPDATE `username`=`username`;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `nama_kategori`, `slug`) VALUES
(1, 'Elektronik & Gadget', 'elektronik-gadget'),
(2, 'Fashion & Pakaian', 'fashion-pakaian'),
(3, 'Aksesoris & Perlengkapan', 'aksesoris-perlengkapan')
ON DUPLICATE KEY UPDATE `slug`=`slug`;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `category_id`, `nama_produk`, `slug`, `harga`, `stok`, `deskripsi`, `gambar`) VALUES
(1, 1, 'Mechanical Keyboard Minimalist Wireless', 'mechanical-keyboard-minimalist-wireless', 750000, 25, 'Keyboard mekanikal nirkabel dengan switch taktil premium, konektivitas Bluetooth 5.0 dan USB-C, desain monokrom hitam minimalis.', NULL),
(2, 1, 'Wireless Mouse Ergonomic Matte Black', 'wireless-mouse-ergonomic-matte-black', 320000, 40, 'Mouse nirkabel ergonomis presisi tinggi hingga 4000 DPI, silent click, dan daya tahan baterai hingga 3 bulan.', NULL),
(3, 2, 'Oversized Heavyweight Cotton T-Shirt', 'oversized-heavyweight-cotton-t-shirt', 149000, 60, 'Kaos katun premium 24s dengan potongan oversized modern, tebal, sejuk, dan nyaman dipakai harian.', NULL),
(4, 2, 'Minimalist Canvas Tote Bag Black', 'minimalist-canvas-tote-bag-black', 95000, 35, 'Tas jinjing kanvas tebal dengan ritsleting utama dan kantong laptop 14 inci. Desain simpel serbaguna.', NULL),
(5, 3, 'Stainless Steel Tumbler 600ml Matte', 'stainless-steel-tumbler-600ml-matte', 185000, 50, 'Tumbler tahan panas dan dingin hingga 12 jam, bahan stainless steel 304 food grade anti bocor.', NULL),
(6, 3, 'Desk Mat Leather Waterproof 80x40cm', 'desk-mat-leather-waterproof-80x40cm', 120000, 30, 'Alas meja kerja kulit sintetis elegan, tahan air dan mudah dibersihkan. Melindungi permukaan meja kerja Anda.', NULL)
ON DUPLICATE KEY UPDATE `slug`=`slug`;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `invoice` varchar(50) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `nama_penerima` varchar(150) NOT NULL,
  `telepon` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
