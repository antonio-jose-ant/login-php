<?php
namespace App\Services;
class IpResolver
{
    public function obtenerIP(array $trustedProxies = []): ?string
    {
        // REMOTE_ADDR siempre existe (salvo ejecución CLI)
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;
        // Si no hay REMOTE_ADDR (ej: CLI), retornamos null
        if (!$remoteAddr)
            return null;
        // Si estamos detrás de proxies confiables, permitir X-Forwarded-For pero sólo si REMOTE_ADDR es un proxy confiable
        $forwarded = null;
        if (!empty($trustedProxies) && in_array($remoteAddr, $trustedProxies, true)) {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // X-Forwarded-For puede contener "client, proxy1, proxy2"
                $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $forwarded = trim($parts[0]);
            } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
                $forwarded = trim($_SERVER['HTTP_X_REAL_IP']);
            } elseif (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $forwarded = trim($_SERVER['HTTP_CF_CONNECTING_IP']);
            }
        }
        $candidate = $forwarded ?? $remoteAddr;
        // Validar que sea IP (IPv4 o IPv6)
        if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            // Si quieres permitir privadas internas, quita flags; aquí retornamos la IP aunque sea privada:
            if (filter_var($candidate, FILTER_VALIDATE_IP) === false) {
                return null;
            }
        }
        return $candidate;
    }
}