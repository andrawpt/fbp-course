<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'HomeController::index', ['as' => 'home']);
$routes->get('katalog', 'HomeController::index', ['as' => 'catalog']);
$routes->get('produk/(:segment)', 'ProductController::detail/$1', ['as' => 'product.detail']);

// Authentication Routes (FR-001, FR-002, FR-003)
$routes->get('login', 'AuthController::login', ['as' => 'login']);
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('register', 'AuthController::register', ['as' => 'register']);
$routes->post('register', 'AuthController::attemptRegister');
$routes->post('logout', 'AuthController::logout', ['as' => 'logout']);

// Protected Customer Shopping Routes (FR-011, FR-012, FR-014, FR-017)
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
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

// Administrator Panel Routes (FR-004, FR-005, FR-006, FR-015, FR-018)
$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\DashboardController::index', ['as' => 'admin.dashboard']);

    $routes->get('kategori', 'Admin\CategoryController::index', ['as' => 'admin.categories']);
    $routes->post('kategori', 'Admin\CategoryController::store');
    $routes->post('kategori/(:num)/toggle', 'Admin\CategoryController::toggle/$1');

    $routes->get('produk', 'Admin\ProductController::index', ['as' => 'admin.products']);
    $routes->post('produk', 'Admin\ProductController::store');
    $routes->post('produk/(:num)/stok', 'Admin\ProductController::updateStock/$1');
    $routes->post('produk/(:num)/toggle', 'Admin\ProductController::toggle/$1');

    $routes->get('pesanan', 'Admin\OrderController::index', ['as' => 'admin.orders']);
    $routes->get('pesanan/(:segment)', 'Admin\OrderController::detail/$1');
    $routes->post('pesanan/(:segment)/verifikasi', 'Admin\OrderController::verifyPayment/$1');
});

// Operator Toko Panel Routes (FR-004, FR-016)
$routes->group('operator', ['filter' => 'auth:operator'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Operator\OrderController::index', ['as' => 'operator.orders']);
    $routes->get('pesanan/(:segment)', 'Operator\OrderController::detail/$1');
    $routes->post('pesanan/(:segment)/kirim', 'Operator\OrderController::updateShipment/$1');
});
