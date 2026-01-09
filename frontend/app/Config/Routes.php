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
$routes->get('register', 'Auth::register');
$routes->post('auth/storeRegister', 'Auth::storeRegister'); // Penting untuk proses simpan data
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/clear-storage', 'Auth::clearStorage'); 
// Route untuk halaman Shop (Katalog Pelanggan)
$routes->get('shop', 'Shop::index'); 
$routes->get('shop/add/(:num)', 'Shop::add/$1'); // Tambah ke keranjang
$routes->get('shop/remove/(:num)', 'Shop::remove/$1'); // Hapus item
$routes->get('shop/clear', 'Shop::clear'); // Hapus semua

// Route untuk Keranjang (Persiapan langkah berikutnya)
$routes->get('cart', 'Shop::cart');
$routes->get('checkout', 'Checkout::index'); // Kita akan buat ini di langkah berikutnya

// ---> INI ROUTE ABOUT<---
$routes->get('about', 'Shop::about');

$routes->post('api/sync-session', 'SessionSync::syncSession');
$routes->post('membership/popup-seen', 'Membership::popupSeen');

// ✅ CHECKOUT ROUTE - BUAT ORDER + SNAP TOKEN
$routes->post('api/payment/checkout', 'Checkout::processCheckout');

// ✅ Proxy pembayaran langganan
$routes->post('api/subscription/checkout', 'SubscriptionProxy::checkout');

$routes->get('auth/google', 'Auth::google');
$routes->get('auth/google/callback', 'Auth::googleCallback');

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
    $routes->get('subscriptions', 'Subscriptions::index');
    $routes->get('analytics', 'Analytics::index');
    $routes->post('shop/dismiss-popup', 'Shop::dismissPopup');
    $routes->post('checkout/successPay', 'Checkout::successPay');
    
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
    
    // PROXY ORDERS JSON
    $routes->get('orders/json', 'Admin\OrdersProxy::getOrders');
    
    // CRUD Produk
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/store', 'Admin\Products::store');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

    // ✅ JSON daftar langganan untuk dashboard admin
    $routes->get('subscriptions/json', 'Admin\SubscriptionAdminProxy::index');

    // ✅ MEMBERSHIP MANAGEMENT (TAMBAHKAN INI)
    $routes->post('membership/approve/(:num)', 'Admin\MembershipProxy::approve/$1');
    $routes->post('membership/reject/(:num)', 'Admin\MembershipProxy::reject/$1');
    $routes->post('membership/revoke/(:num)', 'Admin\MembershipProxy::revoke/$1');
    $routes->get('membership/active', 'Admin\MembershipProxy::active');

    // ✅ CANCEL SUBSCRIPTION (BATALKAN LANGGANAN)
    $routes->post('subscriptions/cancel/(:num)', 'Admin\SubscriptionAdminProxy::cancelSubscription/$1');

    // ✅ DELETE SUBSCRIPTION (HAPUS RIWAYAT)
    $routes->post('subscriptions/delete/(:num)', 'Admin\SubscriptionAdminProxy::deleteSubscription/$1');

});
