<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \App\controllers\AuthController;
try {
    $con = new App\Config\Database();
    $auth = new AuthController($user, $pass,$con->getConnection());
    $response = $auth->logout();
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}