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
$router->get('/search', 'COffice@startsearch');
$router->get('/search/showoffice', 'COffice@search');
$router->get('/search/showoffice/detailsoffice/{id}/{date}/{fascia}', 'COffice@Show');
$router->get('/search/showoffice/detailsoffice/confirm/reservated/{date}/{idOffice}/{fascia}', 'COffice@showconfirmedReservation');
$router->get('/search/showoffice/detailsoffice/review/{id}', 'COffice@showReview');
$router->get('/search/showoffice/detailsoffice/Report/{id}','CReport@showFormReport');
$router->post('/search/showoffice/detailsoffice/Report/ConfirmReport/{id}','CReport@showConfirmOfReport');
$router->get('/showreservation', 'CReservation@showReservation');
$router->get('/showreservation/showreservationdetails/{id}', 'CReservation@showReservationDetails');
$router->get('/showreservation/sendreview/{idreservation}', 'CReservation@sendreview');
$router->post('/showreservation/sendreview/confirmreview/{idreservation}' , 'CReservation@confirmReview');
return $router;
