<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use \App\controllers\AuthController;
use App\Config\Database;
try {
    $con = new Database();
    $auth = new AuthController($user, $pass,$con->getConnection());
    $response = $auth->logout();
    header("Location: /php-crs/frontend/app/");
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}