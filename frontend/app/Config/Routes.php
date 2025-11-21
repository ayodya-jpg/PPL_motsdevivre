<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/', 'Auth::index'); // Halaman awal langsung login
$routes->get('auth', 'Auth::index');
$routes->post('auth/loginProcess', 'Auth::loginProcess');
$routes->get('auth/logout', 'Auth::logout');
// Route untuk halaman Shop (Katalog Pelanggan)
$routes->get('shop', 'Shop::index'); 
$routes->get('shop/add/(:num)', 'Shop::add/$1'); // Tambah ke keranjang
$routes->get('shop/remove/(:num)', 'Shop::remove/$1'); // Hapus item
$routes->get('shop/clear', 'Shop::clear'); // Hapus semua

// Route untuk Keranjang (Persiapan langkah berikutnya)
$routes->get('cart', 'Shop::cart');
$routes->get('checkout', 'Checkout::index'); // Kita akan buat ini di langkah berikutnya

// Contoh memproteksi rute Admin (Opsional tapi penting)
$routes->group('admin', ['filter' => 'authFilter'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    // ... rute admin lainnya
});
