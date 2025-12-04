<?php
namespace App\Controllers;
use \App\controllers\AuthController;
use App\Config\Database;
use App\Services\AuthService\ReposeServer;
class SessionInit
{
    public function sessionVeryfy()
    {
        $con = new Database();
        $auth = new AuthController('', '', $con->getConnection());
        if (!$rs = $auth->Authorized()) {
            (new ReposeServer())->error('Sin inicio de session',-1,401);
        }
        return $rs;
    }
}