<?php
$router->get('/api/products', 'ApiController', 'getProducts');
$router->post('/api/pricing', 'ApiController', 'calculatePrice');
$router->post('/api/cart/add', 'ApiController', 'addToCart');
$router->post('/api/order', 'ApiController', 'placeOrder');
