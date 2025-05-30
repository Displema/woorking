<?php

namespace Routes;

use Core\Router;

$router = new Router();

$router->get('/', function () {
    echo "Home page";
});

// Auth related routes
$router->get('/register', 'CAuth@showRegisterForm');
$router->get('/login', 'CAuth@showLoginForm');
$router->post('/register', 'CAuth@registerUser');
$router->post('/login', 'CAuth@loginUser');

// Office related

$router->get('/home/search', 'COffice@startsearch');
$router->get('/office/{id}', 'COffice@search');
$router->get('/DetailsOffice/{id}/{data}/{fascia}', 'COffice@Show');
$router->get('/conferma/Prenotato/{data}/{idUfficio}/{fascia}', 'COffice@showconfirmedPrenotation');
$router->get('/recensioni/{id}', 'COffice@showRecensioni');
$router->get('/Report/{id}', 'CReport@showFormReport');
$router->post('/ConfirmReport/{id}', 'CReport@showConfirmOfReport');
$router->get('/ShowPrenotation', 'CPrenotation@showPrenotation');
$router->get('/ShowPrenotationDetails/{id}', 'CPrenotation@showPrenotationDetails');
$router->get('/SendReview/{id}', 'CPrenotation@SendReview');

$router->get('/static/img/{id}', 'CPhoto@view');

return $router;
