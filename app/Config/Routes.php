<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Login Routes
$routes->get('/login', 'AuthController::index');
$routes->post('/auth/login-verify', 'AuthController::loginVerify');
$routes->get('/logout', 'AuthController::logout');

// Product Routes
$routes->get('/product-list', 'ProductController::index');
$routes->post('products/get-product-list', 'ProductController::getProductList');
$routes->post('products/get-product-details', 'ProductController::getProductDetails');
$routes->post('products/delete-product', 'ProductController::deleteProduct');
$routes->post('products/add-product', 'ProductController::addProduct');
$routes->post('products/edit-product', 'ProductController::editProduct');

// Product Price Routes
$routes->get('/product-pricing', 'ProductController::productPricing');
$routes->post('products/get-product-price-list', 'ProductController::getProductPriceList');
$routes->post('products/get-product-price-details', 'ProductController::getProductPriceDetails');
$routes->post('products/add-product-price', 'ProductController::addProductPrice');
$routes->post('products/edit-product-price', 'ProductController::editProductPrice');
$routes->post('products/delete-product-price', 'ProductController::deleteProductPrice');

// Transaction Routes
$routes->get('/transaction-list', 'TransactionController::index');

// Management Routes
$routes->get('/management/(:any)', 'ManagementController::managementList/$1');
$routes->post('management/get-management-list', 'ManagementController::getManagementList');
$routes->post('management/add-entry', 'ManagementController::addEntry');
$routes->post('management/edit-entry', 'ManagementController::editEntry');
$routes->post('management/delete-entry', 'ManagementController::deleteEntry');
$routes->post('management/get-management-details', 'ManagementController::getManagementDetails');