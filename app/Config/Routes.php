<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes (no auth required)
$routes->get('login', 'AuthController::login');
$routes->post('auth/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Protected routes (require auth)
$routes->get('/', 'ProductController::index', ['filter' => 'auth']);

// Product routes
$routes->get('products', 'ProductController::index', ['filter' => 'auth']);
$routes->post('products/store', 'ProductController::store', ['filter' => 'auth']);
$routes->post('products/update', 'ProductController::update', ['filter' => 'auth']);
$routes->get('products/delete/(:num)', 'ProductController::delete/$1', ['filter' => 'auth']);

// Kasir (POS) routes
$routes->get('kasir', 'KasirController::index', ['filter' => 'auth']);

// Transaction routes (API for AJAX)
$routes->post('transactions/store', 'TransactionController::store', ['filter' => 'auth']);

// Laporan routes
$routes->get('laporan', 'LaporanController::index', ['filter' => 'auth']);
$routes->get('laporan/exportPdf', 'LaporanController::exportPdf', ['filter' => 'auth']);

// About routes
$routes->get('about', 'AboutController::index', ['filter' => 'auth']);
