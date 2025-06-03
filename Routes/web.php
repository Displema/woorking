<?php

namespace Routes;

use Core\Router;

$router = new Router();

$router->get('/', 'CHome@indexRedirect');

// Auth routes
$router->get('/login', 'CAuth@showLoginForm');
$router->get('/register', 'CAuth@showRegisterForm');
$router->post('/login', 'CAuth@loginUser');
$router->post('/register', 'CAuth@registerUser');

// Static content routes
$router->get('/static/img/{id}', 'CPhoto@view');




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

// Office routes
$router->get('/home', 'CHome@index');
$router->get('/search', 'COffice@startsearch');
$router->get('/search/showoffice', 'COffice@search');
$router->get('/search/showoffice/detailsoffice/{id}/{date}/{fascia}', 'COffice@show');
$router->get('/search/showoffice/detailsoffice/confirm/reservated/{date}/{idOffice}/{fascia}', 'COffice@onfirmReservation');
$router->get('/search/showoffice/detailsoffice/review/{id}', 'COffice@showReview');
$router->get('/search/showoffice/detailsoffice/Report/{id}','CReport@showFormReport');
$router->post('/search/showoffice/detailsoffice/Report/ConfirmReport/{id}','CReport@showConfirmOfReport');
$router->get('/showreservation', 'CReservation@showreservation');
$router->get('/showreservation/showreservationdetails/{id}', 'CReservation@showReservationDetails');
$router->get('/showreservation/sendreview/{idreservation}', 'CReservation@sendreview');
$router->post('/showreservation/sendreview/confirmreview/{idreservation}' , 'CReservation@confirmReview');

return $router;
