<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('search', 'SearchController::index');

//Heatmap
$routes->get('/heatmap', 'HeatmapController::index');

// This is where you define the URL routes for your application.
// We'll create two routes for our registration process:
// 1. A GET route to display the registration form.
// 2. A POST route to handle the form submission.

$routes->group('register', function($routes) {
    // This route will handle GET requests to '/register'
    // It will call the 'index' method in the 'RegisterController'.
    $routes->get('/', 'RegisterController::index');

    // This route will handle POST requests to '/register/process'.
    // It will call the 'processRegistration' method in the 'RegisterController'.
    $routes->post('process', 'RegisterController::processRegistration');
});

//$routes->post('register/process', 'RegisterController::processRegistration');
$routes->match(['GET', 'POST'],'register/process', 'RegisterController::processRegistration');

$routes->match(['GET', 'POST'], '/add_authors', 'AddAuthor::index');

// Route to handle the POST request for updating an author via AJAX
$routes->post('authors/update', 'AddAuthor::update');

// Route for handling the delete operation
$routes->get('authors/delete/(:num)', 'AddAuthor::delete/$1');


//testing ajax
$routes->get('/ajax', 'AjaxController::index');
$routes->get('/ajax/getData', 'AjaxController::getData');
$routes->get('ajax/suggestions', 'AjaxController::suggestions');


//for the AJAX endpoint.
$routes->post('authors/update_inline/(:num)', 'AddAuthor::update_inline/$1');

// Route for adding items to the cart
$routes->post('cart/add', 'CartController::addItem');

// Route for viewing the cart
$routes->get('cart', 'CartController::viewCart');

// Route for removing items from the cart
$routes->post('cart/remove/(:num)', 'CartController::removeItem/$1');

// Route for updating items in the cart
$routes->post('cart/update', 'CartController::updateItem');

// Route for checkout
$routes->get('cart/checkout', 'CartController::checkout');

service('auth')->routes($routes);