use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ProductController::index');

// Product routes
$routes->get('products', 'ProductController::index');
$routes->post('products/store', 'ProductController::store');
$routes->post('products/update', 'ProductController::update');
$routes->get('products/delete/(:num)', 'ProductController::delete/$1');

// Kasir (POS) routes
$routes->get('kasir', 'KasirController::index');

// Transaction routes (API for AJAX)
$routes->post('transactions/store', 'TransactionController::store');

// Laporan routes
$routes->get('laporan', 'LaporanController::index');
