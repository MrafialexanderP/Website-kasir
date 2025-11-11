<?php
// Sample routes for the CI4 stubs. Copy relevant lines into app/Config/Routes.php in your project.

$routes->get('products', 'ProductController::index');
$routes->post('products', 'ProductController::store');
$routes->delete('products/(:num)', 'ProductController::delete/$1');

$routes->post('transactions', 'TransactionController::store');

// Example report route (implement ReportController separately)
$routes->get('reports/monthly/(:num)/(:num)', 'ReportController::monthly/$1/$2');
