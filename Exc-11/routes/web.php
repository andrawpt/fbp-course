<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halo', function () {
    return 'Halo, bro';
});

Route::get('/halo-juga', [SiteController::class, 'haloJuga']);
