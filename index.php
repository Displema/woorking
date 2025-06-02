<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';




use TechnicalServiceLayer\Utility\USession;

USession::getInstance();

$router = require __DIR__ . '/Routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
