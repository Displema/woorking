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


//route to manage the offices
$router->get('/uffici', 'CSearchOffice@showOfficesLocatore');

//route to print photos
$router->get('/foto/{id}', 'CPhoto@serveImage');

//route to home
$router->get('/home','CHome@showHome');

//route to manage reservations
$router->get('/prenotazioni', 'CSearchOffice@showPrenotazioni');

//route to add office
$router->get('/aggiunta', 'CSearchOffice@addOffice');

return $router;
