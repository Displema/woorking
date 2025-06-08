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
$router->post('/salvaProfilo', 'CAuth@modifyUser');

// Static content routes
$router->get('/static/img/{id}', 'CPhoto@view');

// Admin routes
$router->get('/admin/home', 'CAdmin@index');
$router->get('/admin/offices/{id}', 'COffice@showAdminOfficeDetails');
$router->get('/admin/offices/pending/{id}', 'COffice@showPendingDetails');
$router->get('/reports', 'CReport@index');
$router->get('/reports/{id}', 'CReport@show');

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

//route to reviews
$router->get('/recensioni', 'CReview@getReviews');

//stats
$router->get('/api/grafici/entrate-mensili', 'CStats@entrateMensili');
$router->get('/api/grafici/utilizzo-uffici', 'CStats@utilizzoUffici');
$router->get('/api/recensioni/casuali', 'CStats@recensioniCasualiPerLocatore');


$router->post(
    "/offices/{id}/delete",
    'COffice@deleteOffice'
);

// Pending offices routes
$router->get('/offices/pending/${id}', 'COffice@showPending');
$router->post('/admin/offices/pending/{id}/approve', 'COffice@confirmPending');
$router->post('/admin/offices/pending/{id}/reject', 'COffice@rejectPending');

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
$router->get('/search/showoffice/detailsoffice/Report/{id}', 'CReport@showFormReport');
$router->post('/search/showoffice/detailsoffice/Report/ConfirmReport/{id}', 'CReport@showConfirmOfReport');
$router->get('/showreservation', 'CReservation@showreservation');
$router->get('/showreservation/showreservationdetails/{id}', 'CReservation@showReservationDetails');
$router->get('/showreservation/sendreview/{idreservation}', 'CReservation@sendreview');
$router->post('/showreservation/sendreview/confirmreview/{idreservation}', 'CReservation@confirmReview');


// Home
//$router->get('/home', 'CHome@index');
//
//// Ricerca uffici e disponibilità
//$router->get('/offices/search', 'COffice@searchForm');                              // mostra form di ricerca
//$router->get('/offices', 'COffice@searchResults');                                  // mostra risultati della ricerca
//$router->get('/offices/{id}/availability/{date}/{fascia}', 'COffice@availability'); // dettaglio disponibilità
//
//// Prenotazione ufficio
//$router->get('/offices/{id}/availability/{date}/{fascia}/confirm', 'COffice@confirmReservation'); // conferma prenotazione
//
//// Recensioni e segnalazioni su ufficio
//$router->get('/offices/{id}/reviews', 'COffice@showReviews');                       // mostra recensioni
//$router->get('/offices/{id}/report', 'CReport@showForm');                           // mostra form segnalazione
//$router->post('/offices/{id}/report', 'CReport@store');                             // invia segnalazione
//
//// Prenotazioni dell’utente
//$router->get('/reservations', 'CReservation@index');                                // mostra elenco prenotazioni
//$router->get('/reservations/{id}', 'CReservation@show');                            // mostra dettagli prenotazione
//
//// Recensioni sulle prenotazioni
//$router->get('/reservations/{id}/review', 'CReservation@reviewForm');              // mostra form recensione
//$router->post('/reservations/{id}/review', 'CReservation@storeReview');            // conferma recensione



return $router;
