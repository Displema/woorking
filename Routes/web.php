<?php

namespace Routes;

use Controller\CReport;
use Core\Router;

$router = new Router();

$router->get('/', 'CHome@redirect');

// Auth routes
$router->get('/login', 'CAuth@showLoginForm');
$router->get('/register', 'CAuth@showRegisterForm');
$router->post('/login', 'CAuth@loginUser');
$router->post('/register', 'CAuth@registerUser');
$router->get('/logout', 'CAuth@logoutUser');
$router->post('/salvaProfilo', 'CAuth@modifyUser');

// Static content routes
$router->get('/static/img/{id}', 'CResource@serveImg');
$router->get('/static/css/{key}', 'CResource@serveCss');
$router->get('/static/js/{key}', 'CResource@serveJs');
$router->get('/static/asset/{key}', 'CResource@serveAsset');

// Admin routes
$router->get('/admin/home', 'CAdmin@index');
$router->get('/admin/offices/{id}', 'COffice@showAdminOfficeDetails');
$router->get('/admin/offices/pending/{id}', 'COffice@showPendingDetails');
$router->get('/admin/offices/rejected/{id}', 'COffice@showRejectedDetails');
$router->get('/admin/reports', 'CReport@index');
$router->get('/admin/reports/{id}', 'CReport@show');

$router->get('/profile', 'CHome@profile');

//route to manage the offices
$router->get('/uffici', 'COffice@showOfficesLocatore');


//route to manage reservations
$router->get('/prenotazioni', 'COffice@showPrenotazioni');

//route to the layout to add an office
$router->get('/aggiunta', 'COffice@addOffice');

//route to add office
$router->post('/aggiuntaUfficio', 'COffice@addOfficeInDB');


//route to reviews
$router->get('/recensioni', 'CReview@getReviews');

//stats
$router->get('/api/grafici/entrate-mensili', 'CStats@entrateMensili');
$router->get('/api/grafici/utilizzo-uffici', 'CStats@utilizzoUffici');
$router->get('/api/recensioni/casuali', 'CStats@recensioniCasualiPerLocatore');
$router->get('/api/recensioni/per-ufficio', 'CStats@recensioniPerUfficio');


$router->post(
    "/offices/{id}/delete",
    'COffice@deleteOffice'
);

// Pending offices routes
$router->get('/offices/pending/${id}', 'COffice@showPending');
$router->post('/admin/offices/pending/{id}/approve', 'COffice@confirmPending');
$router->post('/admin/offices/pending/{id}/reject', 'COffice@rejectPending');

// Debug endpoints
$router->get('/api/user', 'CAuth@printUser');
$router->get('/admin/register', 'CAuth@registerAdmin');

// Office routes
//// home
$router->get('/home', 'CHome@index');

//// officesearch and avability
$router->get('/offices', 'COffice@searchResults');                                  //show result of search
$router->get('/offices/{id}/availability/{date}/{slot}', 'COffice@availability'); // availabilty details
//
//// Office reservation
$router->get('/offices/{id}/availability/{date}/{fascia}/confirm', 'CReservation@confirmReservation'); // confirm reservation
//
//// review on reservation and report
$router->get('/offices/{id}/reviews', 'CReview@showReviews');     // show review
$router->get('/offices/{id}/report', 'CReport@showForm');         // show report form
$router->post('/offices/{id}/report', 'CReport@store');          // send report
//
//// reservations of user
$router->get('/reservations', 'CReservation@index');             // show reservation
$router->get('/reservations/{id}', 'CReservation@show');        // show reservation details
$router->get('/user/report/{idoffice}', 'CReport@show');            //  show report for a specific office
//
//// review on reservation
$router->get('/reservations/{id}/review', 'CReview@reviewForm');     //show review form
$router->post('/reservations/{id}/review', 'CReview@storeReview');  //confirm review

// route to resetpassword
$router->get('/auth/reset', 'CAuth@ResetPassword');


return $router;
