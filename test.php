<?php
require_once __DIR__ . "/bootstrap.php";

use Controller\CAuth;

// Dati di prova (modifica come vuoi)
$email = "test4@example.com";
$password = "password1234";
$name = "Mario";
$surname = "Rossi";
$date = "1990-01-01";
$userType = "LOCATORE";  // o "Locatore"
$phone = "1234567890";
$piva = 989786756;  // solo se userType è "Locatore", altrimenti null

// Crea l'istanza del controller
$authController = new CAuth();

// Chiama direttamente la funzione di registrazione
$authController->registerUser($email, $password, $name, $surname, $date, $userType, $phone, $piva);

echo "Registrazione completata con successo.\n";
