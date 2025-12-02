<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \App\controllers\AuthController;
$user = $_POST['user'] ?? '';
$pass = $_POST['password'] ?? '';

try {
    $con = new App\Config\Database();
    $auth = new AuthController($user, $pass,$con->getConnection());
    if ($response = $auth->register()) {
        echo "usuario agregado correctamente";
    }
    header("Location: /php-crs/frontend/app/pages/dashboard.php");
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}