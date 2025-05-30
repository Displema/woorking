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
$router->get('/startsearch', 'COffice@startsearch');
$router->get('/showoffice', 'COffice@search');
$router->get('/DetailsOffice/{id}/{data}/{fascia}', 'COffice@Show');
$router->get('/conferma/Prenotato/{data}/{idUfficio}/{fascia}', 'COffice@showconfirmedPrenotation');
$router->get('/recensioni/{id}', 'COffice@showRecensioni');
$router->get('/Report/{id}','CReport@showFormReport');
$router->post('/ConfirmReport/{id}','CReport@showConfirmOfReport');
$router->get('/ShowPrenotation', 'CPrenotation@showPrenotation');
$router->get('/ShowPrenotationDetails/{id}', 'CPrenotation@showPrenotationDetails');
$router->get('/SendReview/{id}', 'CPrenotation@SendReview');
return $router;
