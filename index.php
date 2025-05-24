<?php

use Model\EIndirizzo;
use Core\Router;

require_once __DIR__ . '/src/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/Model/EIndirizzo.php';


$entityManager = getEntityManager();
 $products = $entityManager->getRepository(EIndirizzo::class)->find("8de01155-3571-11f0-b19d-b48c9d833b56");
 echo"". $products->getId() ."";


$router = new Router();

// Register routes
$router->get('/user/(\d+)', function ($id) {
    //$controller = new UserController();
    //$controller->show($id);
});

$router->post('/submit/(\d+)', function ($id, $query, $body) {
    echo "ID: $id<br>";
    echo "Query: " . json_encode($query, JSON_THROW_ON_ERROR) . "<br>";
    echo "Body: " . json_encode($body, JSON_THROW_ON_ERROR);
});

$router->get('/product/(\d+)', function ($id) {
    //$controller = new ProductController();
    //$controller->show($id);
});

// Dispatch the current request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
