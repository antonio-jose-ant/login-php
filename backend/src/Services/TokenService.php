<?php
namespace App\Services;
class TokenService
{
    public function createToken(): array
    {
        $raw = bin2hex(random_bytes(32));
        return [
            'raw' => $raw,
            'hash' => hash('sha256', $raw)
        ];
    }
    public function searchTokenBD($token)
    {
        return hash('sha256', $token);
    }
}