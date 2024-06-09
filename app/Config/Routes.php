<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// ======================== Register & Login Routes ========================
$routes->group('auth', function ($routes) {
    $routes->post('register', 'Api\UserController::register');
    $routes->post('login', 'Api\UserController::login');
});

