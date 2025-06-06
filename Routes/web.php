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
$router->get('/logout', 'CAuth@logoutUser');
$router->post('/salvaProfilo','CAuth@modifyUser');

// Static content routes
$router->get('/static/img/{id}', 'CPhoto@view');

// Admin routes
$router->get('/admin/home', 'CAdmin@home');
$router->get('/admin/offices/{id}', 'CAdmin@showOfficeDetails');
$router->get('/reports', 'CReport@index');

$router->get('/profile', 'CHome@showprofile');

//route to manage the offices
$router->get('/uffici', 'COffice@showOfficesLocatore');

//route to print photos
$router->get('/foto/{id}', 'CPhoto@serveImage');

//route to manage reservations
$router->get('/prenotazioni', 'COffice@showPrenotazioni');

//route to the layout to add an office
$router->get('/aggiunta', 'COffice@addOffice');

//route to add office
$router->post('/aggiuntaUfficio', 'COffice@addOfficeInDB');

//route to landlord's profile
$router->get('/profilo', 'CLocatore@profilo');

//stats
$router->get('/api/grafici/entrate-mensili', 'CStats@entrateMensili');
$router->get('/api/grafici/utilizzo-uffici', 'CStats@utilizzoUffici');


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
$router->get('/admin/register', 'CAuth@registerAdmin');

// Office routes
$router->get('/home', 'CHome@index');
$router->get('/search', 'COffice@startsearch');
$router->get('/search/showoffice', 'COffice@search');
$router->get('/search/showoffice/detailsoffice/{id}/{date}/{fascia}', 'COffice@show');
$router->get('/search/showoffice/detailsoffice/confirm/reservated/{date}/{idOffice}/{fascia}', 'COffice@confirmReservation');
$router->get('/search/showoffice/detailsoffice/review/{id}', 'COffice@showReview');
$router->get('/search/showoffice/detailsoffice/Report/{id}','CReport@showFormReport');
$router->post('/search/showoffice/detailsoffice/Report/ConfirmReport/{id}','CReport@showConfirmOfReport');
$router->get('/showreservation', 'CReservation@showreservation');
$router->get('/showreservation/showreservationdetails/{id}', 'CReservation@showReservationDetails');
$router->get('/showreservation/sendreview/{idreservation}', 'CReservation@sendreview');
$router->post('/showreservation/sendreview/confirmreview/{idreservation}' , 'CReservation@confirmReview');


return $router;
