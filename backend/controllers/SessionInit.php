<?php
namespace App\controllers;
use \App\controllers\AuthController;
use App\Config\Database;

class SessionInit
{
    public function sessionVeryfy()
    {
        $con = new Database();
        $auth = new AuthController('', '', $con->getConnection());
        if (!$rs = $auth->Authorized()) {
            header('Location: /php-crs');
        }
        return $rs;
    }
}