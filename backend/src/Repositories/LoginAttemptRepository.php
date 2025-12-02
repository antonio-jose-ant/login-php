<?php
namespace App\Repositories;
class LoginAttemptRepository
{
    public function __construct(private \PDO $pdo)
    {
    }
    public function registrarIntento($usuario, $ip, $ua, $res, $razon)
    {
        $params = [
            'user' => $usuario,
            'ip' => $ip,
            'ua' => $ua,
            'res' => $res,
            'rf' => $razon
        ];
        $sql = "
        INSERT INTO login_attempts 
            (usuario, ip, user_agent, resultado, razon_fallo)
        VALUES 
            (:user, :ip, :ua, :res, :rf)";
        return $this->pdo->prepare($sql)->execute($params);
    }
}