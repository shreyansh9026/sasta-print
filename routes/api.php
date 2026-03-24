<?php
// ── API Routes (v1) ────────────────────────────────────────────────────────────
// Products
$router->get('/api/v1/products',           'ApiController', 'getProducts');
$router->post('/api/v1/pricing',           'ApiController', 'calculatePrice');

// Cart
$router->post('/api/v1/cart/add',          'ApiController', 'addToCart');
$router->post('/api/v1/cart/remove',       'ApiController', 'removeFromCart');

// Coupon
$router->post('/api/v1/coupon/validate',   'ApiController', 'validateCoupon');

// Orders & Payment
$router->post('/api/v1/order',             'ApiController', 'placeOrder');
$router->post('/api/v1/payment/verify',    'ApiController', 'verifyPayment');

// Reviews
$router->post('/api/v1/review',            'ApiController', 'addReview');

// Admin API
$router->post('/api/v1/admin/order-status','AdminController', 'updateOrderStatus');
$router->post('/api/v1/admin/review/approve/{id}', 'AdminController', 'approveReview');
$router->get('/api/v1/admin/analytics',    'AdminController', 'analyticsData');

// Legacy routes (kept for backwards compatibility)
$router->get('/api/products',              'ApiController', 'getProducts');
$router->post('/api/pricing',              'ApiController', 'calculatePrice');
$router->post('/api/cart/add',             'ApiController', 'addToCart');
$router->post('/api/order',                'ApiController', 'placeOrder');
