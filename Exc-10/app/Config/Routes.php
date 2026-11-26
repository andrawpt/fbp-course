<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index', ['as' => 'home']);
$routes->get('katalog', 'HomeController::index', ['as' => 'catalog']);
$routes->get('produk/(:segment)', 'ProductController::detail/$1', ['as' => 'product.detail']);

// Authentication Routes
$routes->get('register', 'AuthController::register', ['as' => 'register']);
$routes->post('register', 'AuthController::store');
$routes->get('login', 'AuthController::login', ['as' => 'login']);
$routes->post('login', 'AuthController::authenticate');
$routes->post('logout', 'AuthController::logout', ['as' => 'logout']);

// Protected Customer Routes
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('akun', 'CustomerController::index', ['as' => 'customer.dashboard']);
    $routes->post('akun/profil', 'CustomerController::updateProfile', ['as' => 'customer.profile.update']);

    $routes->get('keranjang', 'CartController::index', ['as' => 'cart']);
    $routes->post('keranjang/tambah', 'CartController::add', ['as' => 'cart.add']);
    $routes->post('keranjang/update', 'CartController::update', ['as' => 'cart.update']);
    $routes->post('keranjang/hapus', 'CartController::remove', ['as' => 'cart.remove']);

    $routes->get('checkout', 'CheckoutController::index', ['as' => 'checkout']);
    $routes->post('checkout', 'CheckoutController::process', ['as' => 'checkout.process']);

    $routes->get('pesanan', 'OrderController::index', ['as' => 'orders']);
    $routes->get('pesanan/(:segment)', 'OrderController::detail/$1', ['as' => 'orders.detail']);
    $routes->post('pesanan/(:segment)/bayar', 'OrderController::uploadPayment/$1', ['as' => 'orders.pay']);
});

// Admin & Operator Management Routes (Categories, Products + Gallery, Inventory)
$routes->group('admin', ['filter' => 'auth:admin,operator'], static function (RouteCollection $routes): void {
    // CRUD Kategori
    $routes->get('categories', 'Admin\CategoryController::index', ['as' => 'admin.categories']);
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->post('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // CRUD Produk & Galeri Produk
    $routes->get('products', 'Admin\ProductController::index', ['as' => 'admin.products']);
    $routes->get('products/create', 'Admin\ProductController::create');
    $routes->post('products/store', 'Admin\ProductController::store');
    $routes->get('products/edit/(:num)', 'Admin\ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->post('products/delete/(:num)', 'Admin\ProductController::delete/$1');
    $routes->post('products/(:num)/images/(:num)/primary', 'Admin\ProductController::setPrimaryImage/$1/$2');
    $routes->post('products/(:num)/images/(:num)/delete', 'Admin\ProductController::deleteImage/$1/$2');

    // Inventori (Read & Update)
    $routes->get('inventory', 'Admin\InventoryController::index', ['as' => 'admin.inventory']);
    $routes->post('inventory/update/(:num)', 'Admin\InventoryController::update/$1');
});

// Admin-Only Panel Routes
$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\DashboardController::index', ['as' => 'admin.dashboard']);

    // CRUD Data Karyawan
    $routes->get('karyawan', 'Admin\KaryawanController::index', ['as' => 'admin.karyawan']);
    $routes->get('karyawan/create', 'Admin\KaryawanController::create');
    $routes->post('karyawan/store', 'Admin\KaryawanController::store');
    $routes->get('karyawan/edit/(:num)', 'Admin\KaryawanController::edit/$1');
    $routes->post('karyawan/update/(:num)', 'Admin\KaryawanController::update/$1');
    $routes->post('karyawan/delete/(:num)', 'Admin\KaryawanController::delete/$1');

    // Pesanan & Verifikasi
    $routes->get('pesanan', 'Admin\OrderController::index', ['as' => 'admin.orders']);
    $routes->get('pesanan/(:segment)', 'Admin\OrderController::detail/$1');
    $routes->post('pesanan/(:segment)/verifikasi', 'Admin\OrderController::verifyPayment/$1');
});

// Operator Panel Routes
$routes->group('operator', ['filter' => 'auth:operator'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Operator\OrderController::index', ['as' => 'operator.orders']);
    $routes->get('pesanan/(:segment)', 'Operator\OrderController::detail/$1');
    $routes->post('pesanan/(:segment)/kirim', 'Operator\OrderController::updateShipment/$1');
});

// JSON API Routes
$routes->group('api', static function (RouteCollection $routes): void {
    $routes->get('session', 'ApiController::session');
    $routes->get('catalog', 'ApiController::catalog');

    // Customer API
    $routes->group('', ['filter' => 'auth:customer'], static function (RouteCollection $routes): void {
        $routes->get('addresses', 'ApiController::addresses');
        $routes->post('addresses', 'ApiController::createAddress');
        $routes->post('addresses/(:num)/update', 'ApiController::updateAddress/$1');
        $routes->post('addresses/(:num)/delete', 'ApiController::deleteAddress/$1');

        $routes->get('cart', 'ApiController::cart');
        $routes->post('cart', 'ApiController::upsertCart');

        $routes->post('profile', 'ApiController::profile');
        $routes->post('checkout', 'ApiController::checkout');

        $routes->get('orders', 'ApiController::orders');
        $routes->get('orders/(:segment)', 'ApiController::orderDetail/$1');
        $routes->post('order-action', 'ApiController::orderAction');
    });

    // Legacy Admin API
    $routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes): void {
        $routes->post('category', 'ApiController::adminCategory');
        $routes->post('product', 'ApiController::adminProduct');
    });
});
