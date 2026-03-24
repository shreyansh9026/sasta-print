<?php
// ── Web Routes ─────────────────────────────────────────────────────────────────

// Public
$router->get('/',                  'HomeController',    'index');
$router->get('/products',          'ProductController', 'index');
$router->get('/product/{slug}',    'ProductController', 'show');
$router->get('/design/{id}',       'ProductController', 'design');
$router->post('/review',           'ProductController', 'submitReview');

// Cart & Checkout
$router->get('/cart',              'CartController',    'index');
$router->get('/checkout',          'CartController',    'checkout');
$router->get('/order/success/{id}','CartController',    'success');
$router->get('/order/track/{id}',  'CartController',    'track');

// Auth
$router->get('/login',             'UserController',    'loginForm');
$router->post('/login',            'UserController',    'login');
$router->get('/register',          'UserController',    'registerForm');
$router->post('/register',         'UserController',    'register');
$router->get('/logout',            'UserController',    'logout');

// User Dashboard
$router->get('/dashboard',            'UserController', 'dashboard');
$router->get('/dashboard/order/{id}', 'UserController', 'orderDetail');

// Admin Panel
$router->get('/admin',                      'AdminController', 'dashboard');
$router->get('/admin/orders',               'AdminController', 'orders');
$router->get('/admin/orders/{id}',          'AdminController', 'orderDetail');
$router->get('/admin/products',             'AdminController', 'products');
$router->post('/admin/products/add',        'AdminController', 'addProduct');
$router->get('/admin/products/edit/{id}',   'AdminController', 'editProduct');
$router->post('/admin/products/edit/{id}',  'AdminController', 'editProduct');
$router->post('/admin/products/delete/{id}','AdminController', 'deleteProduct');
$router->get('/admin/categories',           'AdminController', 'categories');
$router->post('/admin/categories',          'AdminController', 'categories');
$router->get('/admin/coupons',              'AdminController', 'coupons');
$router->post('/admin/coupons/add',         'AdminController', 'addCoupon');
$router->post('/admin/coupons/delete/{id}', 'AdminController', 'deleteCoupon');
$router->get('/admin/users',                'AdminController', 'users');
$router->get('/admin/invoice/{id}',         'AdminController', 'downloadInvoice');
