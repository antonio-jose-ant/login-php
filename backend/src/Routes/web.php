<?php
require_once __DIR__ . '/../includes/displayErrors.php';
use App\Router\Router;
use App\controllers\AuthController;
use App\Config\Database;
Router::post('/login', function () {
    $db  = new Database();
    $auth = new AuthController(
        $_POST['User'] ?? '',
        $_POST['Pass'] ?? '',
        $db->getConnection()
    );
    $auth->login();
});

Router::post('/registrar', function () {
    $db  = new Database();
    $auth = new AuthController(
        $_POST['User'] ?? '',
        $_POST['Pass'] ?? '',
        $db->getConnection()
    );
    $auth->register();
});

Router::get('/users/{id}', function ($id) {
    echo json_encode([
        "user" => $id
    ]);
});

Router::post('/logout', function () {
    echo json_encode(["logout" => true]);
});
