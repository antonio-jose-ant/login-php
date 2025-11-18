<?php
namespace App\repositories;

class UserRepository
{
    public function __construct(private \PDO $pdo)
    {
    }
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, password_hash FROM users WHERE email = :e");
        $stmt->execute(['e' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $email, string $hash): bool
    {
        $sql = "INSERT INTO users (email, password_hash) VALUES (:e, :h)";
        return $this->pdo->prepare($sql)->execute([
            'e' => $email,
            'h' => $hash
        ]);
    }
}