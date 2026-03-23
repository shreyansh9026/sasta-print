<?php
$router->get('/', 'HomeController', 'index');
$router->get('/products', 'ProductController', 'index');
$router->get('/product/{slug}', 'ProductController', 'show');
$router->get('/design/{id}', 'ProductController', 'design');

$router->get('/cart', 'CartController', 'index');
$router->get('/checkout', 'CartController', 'checkout');

$router->get('/login', 'UserController', 'loginForm');
$router->post('/login', 'UserController', 'login');
$router->get('/register', 'UserController', 'registerForm');
$router->post('/register', 'UserController', 'register');
$router->get('/dashboard', 'UserController', 'dashboard');
$router->get('/logout', 'UserController', 'logout');

$router->get('/admin', 'AdminController', 'dashboard');
