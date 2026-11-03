<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');
$routes->get('/katalog', 'Home::index');
$routes->get('/produk/(:segment)', 'Home::detail/$1');

$routes->get('/cart', 'Cart::index');
$routes->post('/cart/add/(:num)', 'Cart::add/$1');
$routes->post('/cart/update', 'Cart::update');
$routes->get('/cart/remove/(:num)', 'Cart::remove/$1');
$routes->get('/cart/clear', 'Cart::clear');

$routes->get('/checkout', 'Checkout::index');
$routes->post('/checkout/process', 'Checkout::process');
$routes->get('/checkout/success/(:segment)', 'Checkout::success/$1');
$routes->get('/riwayat-pesanan', 'Checkout::history', ['filter' => 'auth']);

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::attemptRegister');
$routes->get('/logout', 'Auth::logout');

$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');

    
    $routes->get('products', 'Admin::products');
    $routes->get('products/create', 'Admin::createProduct');
    $routes->post('products/store', 'Admin::storeProduct');
    $routes->get('products/edit/(:num)', 'Admin::editProduct/$1');
    $routes->post('products/update/(:num)', 'Admin::updateProduct/$1');
    $routes->get('products/delete/(:num)', 'Admin::deleteProduct/$1');

    
    $routes->get('orders', 'Admin::orders');
    $routes->get('orders/detail/(:num)', 'Admin::orderDetail/$1');
    $routes->post('orders/update-status/(:num)', 'Admin::updateOrderStatus/$1');
});
