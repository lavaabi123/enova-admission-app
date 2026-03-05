<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('/',          'Auth\AuthController::login');
$routes->get('login',      'Auth\AuthController::login');
$routes->post('login',     'Auth\AuthController::loginProcess');
$routes->get('signup',     'Auth\AuthController::signup');
$routes->post('signup',    'Auth\AuthController::signupProcess');
$routes->get('logout',     'Auth\AuthController::logout');

$routes->get('uploads/(:segment)/(:segment)', 'FileController::serve/$1/$2');

// Student Routes
$routes->group('student', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('dashboard',          'Student\StudentController::dashboard');
    $routes->get('biodata',            'Student\StudentController::biodata');
    $routes->post('biodata',           'Student\StudentController::biodataProcess');
    $routes->get('courses',            'Student\StudentController::courses');
    $routes->post('apply',             'Student\StudentController::apply');
    $routes->get('status/check',       'Student\StudentController::statusCheck');
});

// Admin Routes
$routes->group('admin', ['filter' => 'adminFilter'], function ($routes) {
    $routes->get('dashboard',                   'Admin\AdminController::dashboard');
    $routes->get('applications',                'Admin\AdminController::applications');
    $routes->post('applications/update/(:num)', 'Admin\AdminController::updateStatus/$1');
    $routes->get('students',                    'Admin\AdminController::students');
});

// Admin login
$routes->get('admin/login',   'Admin\AdminController::login');
$routes->post('admin/login',  'Admin\AdminController::loginProcess');
$routes->get('admin/logout',  'Admin\AdminController::logout');
