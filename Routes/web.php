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
