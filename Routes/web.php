<?php

namespace Routes;

use Core\Router;

$router = new Router();

$router->get('/', function () {
    echo "Home page";
});



// AUTH RELATED ROUTES
$router->get('/login', 'CAuth@showLoginForm');
$router->get('/register', 'CAuth@showLoginForm');
$router->post('/login', 'CAuth@loginUser');
$router->post('/register', 'CAuth@registerUser');

// STATIC RESOURCES
$router->get('/static/img/{id}', 'CPhoto@view');


// Home
$router->get('/home', 'CHome@index');
return $router;
