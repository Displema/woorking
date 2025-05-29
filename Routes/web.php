<?php

namespace Routes;

use Core\Router;

$router = new Router();

$router->get('/', function () {
    echo "Home page";
});

$router->post('/register', 'CAuth@registerUser');

$router->get('/user/{id}', 'UserController@show');
$router->post('/login', 'CAuth@loginUser');
$router->get('/static/img/{id}', 'CPhoto@view');

return $router;
