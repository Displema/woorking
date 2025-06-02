<?php

namespace Routes;

use Core\Router;

$router = new Router();

$router->get('/', function () {
    echo "Home page";
});

$uuidRegexPattern = '/[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}/i';

// Auth routes
$router->get('/login', 'CAuth@showLoginForm');
$router->get('/register', 'CAuth@showRegisterForm');
$router->post('/register', 'CAuth@registerUser');
$router->post('/login', 'CAuth@loginUser');

$router->get('/static/img/{id}', 'CPhoto@view');
// Office routes
$router->get('/startsearch', 'COffice@startsearch');
$router->get('/showoffice', 'COffice@search');
$router->get('/DetailsOffice/{id}/{data}/{fascia}', 'COffice@Show');
$router->get('/conferma/Prenotato/{data}/{idUfficio}/{fascia}', 'COffice@showconfirmedPrenotation');
$router->get('/recensioni/{id}', 'COffice@showRecensioni');
$router->get('/Report/{id}', 'CReport@showFormReport');
$router->post('/ConfirmReport/{id}', 'CReport@showConfirmOfReport');
//$router->get('/ShowPrenotation', 'CPrenotation@showPrenotation');
$router->get('/ShowPrenotationDetails/{id}', 'CPrenotation@showPrenotationDetails');
$router->get('/SendReview/{id}', 'CPrenotation@SendReview');

$router->post(
    "/office/{id}/delete",
    'COffice@deleteOffice'
);

// Pending offices routes
$router->get('/office/pending/${id}', 'COffice@showPending');
$router->post('/office/pending/${id}/approve', 'COffice@sendPending');
$router->post('/office/pending/${id}/reject', 'COffice@sendReject');

// Debug endpoints
$router->get('/api/user', 'CAuth@getUser');

return $router;
