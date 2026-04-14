<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/produk', 'Produkcontroller::index');
$routes->get('/keranjang', 'Keranjangcontroller::index');
