<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use \App\controllers\SessionInit;
$SESSIONIN= (new SessionInit)->sessionVeryfy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
</head>
<body>
    <a href="../../../backend/auth/logout.php">salirr</a>
</body>
</html>