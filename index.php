<?php

use Model\EIndirizzo;
use Core\Router;

require __DIR__ . '/src/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required('DB_NAME')->notEmpty();
$dotenv->required('DB_USER')->notEmpty();
$dotenv->required('DB_PASSWORD');
$dotenv->required('DB_HOST')->notEmpty();
$dotenv->required('DB_PORT')->notEmpty();
$dotenv->required('DB_DRIVER')->allowedValues(['pdo_mysql']);




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
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (JsonException $e) {
    die("An error occurred: " . $e->getMessage());
}
