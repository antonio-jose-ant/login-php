<?php
namespace App\Controllers;
class AuthController
{
    private $Services;
    private $usuario;
    private $contraseña;
    public function __construct($user, $pass, private \PDO $con)
    {
        $this->usuario = $user;
        $this->contraseña = $pass;
        $this->Services = new \App\services\AuthService\AuthService($con);
    }
    public function login()
    {
        $this->Services->auth(
            $this->usuario,
            $this->contraseña,
            $_SERVER['HTTP_USER_AGENT']
        );
    }
    public function logout()
    {
        // 1. Obtener el token crudo de la cookie
        $token = $_COOKIE['session_token'] ?? null;
        // Si no hay token, no hay sesión activa que cerrar (o ya fue cerrada)
        if (empty($token)) {
            return true;
        }
        return $this->Services->exit($token);
    }
    public function Authorized(): bool
    {
        $uid = $_COOKIE['uid'] ?? null;
        $token = $_COOKIE['session_token'] ?? null;

        if (!$uid || !$token) {
            $this->logout();
            // header("Location: /php-crs/frontend/app/");
            return false; // detener ejecución
        }

        if (!$this->Services->isItAuthorized((int) $uid, $token)) {
            $this->logout();
            // header("Location: /php-crs/frontend/app/");
            return false; // detener ejecución
        }

        return true;
    }
    public function register()
    {

        $this->Services->addUser(
            $this->usuario,
            $this->contraseña
        );
    }
}