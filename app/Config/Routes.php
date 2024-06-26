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

// ============================= Tasks Routes ==============================
$routes->group('tasks', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Api\TaskController::index'); // Retrieve All Tasks
    $routes->post('/', 'Api\TaskController::create'); // Create a Task
    $routes->get('(:num)', 'Api\TaskController::show/$1'); // Retrieve a Specific Task
    $routes->put('(:num)', 'Api\TaskController::update/$1'); // Update a Task
    $routes->delete('(:num)', 'Api\TaskController::delete/$1'); // Delete a Task
});
