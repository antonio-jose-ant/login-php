<?php
namespace App\Repositories;
/**
 * Summary of SessionRepository
 *  crea el registro de un iniiicio de secion 
 */
class SessionRepository
{
    public function __construct(private \PDO $pdo)
    {
    }
    public function create(int $id, string $tokenHash, string $ip, string $ua, string $expires): bool
    {
        $sql = "INSERT INTO sessions (user_id, token_hash, ip, user_agent, expires_at)
                VALUES (:id, :t, :ip, :ua, :ex)";

        return $this->pdo->prepare($sql)->execute([
            'id' => $id,
            't' => $tokenHash,
            'ip' => $ip,
            'ua' => $ua,
            'ex' => $expires
        ]);
    }
    public function verifyToken(string $tokenHash, int $uId): bool
    {
        $sql = "SELECT token_hash FROM sessions WHERE user_id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $uId]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row)
            return false;

        return hash_equals($row['token_hash'], $tokenHash);
    }
}