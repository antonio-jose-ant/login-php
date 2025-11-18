<?php
namespace App\repositories;
class SessionRepositoryLogout
{
    public function __construct(private \PDO $pdo)
    {
    }
    public function Delete(string $tokenHash)
    {
        $sql = "DELETE FROM sessions WHERE token_hash = :tokenHash";
        $params = ['tokenHash' => $tokenHash];
        return $this->pdo->prepare($sql)->execute($params);
    }
}