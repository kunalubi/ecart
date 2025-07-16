<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'register', 'Register::index');
$routes->match(['get', 'post'], 'login', 'Login::index');
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->match(['get', 'post'], 'admin/add-user', 'Admin::addUser');
$routes->match(['get', 'post'], 'admin/logout', 'Admin::logout');
$routes->get('admin/categories', 'Admin::categories');
$routes->match(['get', 'post'], 'admin/add-category', 'Admin::addCategory');
$routes->get('admin/products', 'Admin::products');
$routes->match(['get', 'post'], 'admin/add-product', 'Admin::addProduct');
$routes->get('admin/orders', 'Admin::orders');
$routes->get('admin/order-details/(:num)', 'Admin::orderDetails/$1');
$routes->get('cart', 'Cart::index');
$routes->post('cart/add', 'Cart::add');
$routes->post('cart/update', 'Cart::update');
$routes->post('cart/remove', 'Cart::remove');
$routes->get('checkout', 'Cart::checkout');
$routes->post('checkout', 'Cart::placeOrder');
$routes->get('admin/orders', 'Admin::orders');
$routes->post('admin/orders/update-status', 'Admin::updateOrderStatus');
$routes->get('admin/orders/invoice/(:num)', 'Admin::invoice/$1');
$routes->match(['get','post'], 'track-order', 'Storefront::trackOrder');
$routes->get('(:any)', 'Storefront::index'); // Always last!

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
