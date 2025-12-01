<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// 1. Home langsung ke Shop (Katalog Terbuka)
$routes->get('/', 'Shop::index'); 

// 2. Auth (Login/Register/Logout)
$routes->get('auth', 'Auth::index');
$routes->post('auth/loginProcess', 'Auth::loginProcess');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('auth/registerProcess', 'Auth::registerProcess');

// 3. Shop (Katalog PUBLIK - Siapa saja bisa lihat)
$routes->get('shop', 'Shop::index');

// ---> INI ROUTE ABOUT<---
$routes->get('about', 'Shop::about');

// 4. Fitur Belanja (PRIVATE - Harus Login)

// Kita gunakan grouping dengan filter 'authFilter' agar aman
$routes->group('', ['filter' => 'authFilter'], function($routes) {
    $routes->get('shop/add/(:num)', 'Shop::add/$1');       // Tambah Keranjang
    $routes->get('cart', 'Shop::cart');                    // Lihat Keranjang
    $routes->get('shop/remove/(:num)', 'Shop::remove/$1'); // Hapus Item
    $routes->get('shop/clear', 'Shop::clear');             // Kosongkan
    $routes->get('checkout', 'Checkout::index');           // Checkout
    $routes->get('profile', 'Profile::index');             //Profile
    $routes->post('profile/saveAddress', 'Profile::saveAddress'); //Profile
    $routes->post('checkout/process', 'Checkout::process'); // Proses Bayar
    $routes->get('orders', 'Orders::index'); //Riwayat Pesanan
    
    // Rute Admin juga wajib login
    $routes->get('admin/dashboard', 'Admin\Dashboard::index');
    // admin Halaman List Semua Pesanan
    $routes->get('admin/orders', 'Admin\Orders::index');
    
    // admin Halaman Detail Pesanan Spesifik
    $routes->get('admin/orders/show/(:num)', 'Admin\Orders::show/$1');
    
    // admin Proses Update Status (misal: Pending -> Dikirim)
    $routes->post('admin/orders/update-status/(:num)', 'Admin\Orders::updateStatus/$1');
});

//admin crud
$routes->group('admin', ['filter' => 'authFilter'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // CRUD Produk
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

});
