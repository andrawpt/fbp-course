<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    
    $routes->get('/dashboard', 'Pages::dashboard');
    $routes->get('/about', 'Pages::about');
    $routes->get('/contact', 'Pages::contact');
    $routes->get('/profile', 'Pages::profile');

    
    $routes->get('/mahasiswa', 'Mahasiswa::index');
    $routes->get('/mahasiswa/create', 'Mahasiswa::create');
    $routes->post('/mahasiswa/store', 'Mahasiswa::store');
    $routes->get('/mahasiswa/edit/(:num)', 'Mahasiswa::edit/$1');
    $routes->post('/mahasiswa/update/(:num)', 'Mahasiswa::update/$1');
    $routes->get('/mahasiswa/delete/(:num)', 'Mahasiswa::delete/$1');
});
