<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('siswa', 'Home::index');
$routes->match(['get', 'post'], 'home/tambah', 'Home::tambah');
$routes->match(['get', 'post'], 'tambah', 'Home::tambah');
$routes->get('home/hapus/(:segment)', 'Home::hapus/$1');
$routes->get('hapus/(:segment)', 'Home::hapus/$1');
$routes->get('welcome', 'Home::welcome');
