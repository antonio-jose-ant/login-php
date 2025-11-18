<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use \App\controllers\AuthController;
use App\Config\Database;

$user = $_POST['user'] ?? '';
$pass = $_POST['password'] ?? '';
echo "<br>";
echo $user . " " . $pass;
echo "<br>";
try {
    $con = new Database();
    $auth = new AuthController($user, $pass,$con->getConnection());
    if (!$response = $auth->login()) {
        echo "<br>";
        echo $response;
        echo "<br>";
    }
    header("Location: /php-crs/frontend/app/pages/dashboard.php");
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
