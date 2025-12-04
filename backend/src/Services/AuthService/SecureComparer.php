<?php
namespace App\Services\AuthService;

class SecureComparer {
    /**
     * Compara dos strings en un tiempo constante para prevenir timing attacks.
     *
     * Utiliza la función nativa de PHP hash_equals(), que es criptográficamente segura
     * para la comparación de strings (incluyendo hashes y claves).
     *
     * @param string $known_string El string que se espera (el valor conocido/correcto).
     * @param string $user_string El string proporcionado por el usuario (el valor a verificar).
     * @return bool True si los strings son idénticos, false en caso contrario.
     */
    public static function compare(string $known_string, string $user_string): bool {
        // Validación básica de tipos para asegurar que ambos son strings
        if (!is_string($known_string) || !is_string($user_string)) {
            // Manejo de error o simplemente devuelve false. Es crucial que el manejo
            // de errores no revele información de sincronización.
            return false;
        }

        // Esta es la función clave de PHP para prevenir timing attacks.
        // Siempre toma el mismo tiempo, sin importar si los strings
        // coinciden o la posición del primer byte diferente.
        if (function_exists('hash_equals')) {
            return hash_equals($known_string, $user_string);
        }

        // Fallback: Si por alguna razón hash_equals no está disponible (ej. PHP < 5.6)
        // Se recomienda enfáticamente usar PHP 5.6 o superior.
        // Este fallback es solo para demostrar el principio de comparación a tiempo constante.
        $len = min(strlen($known_string), strlen($user_string));
        $xor = strlen($known_string) ^ strlen($user_string);
        for ($i = 0; $i < $len; $i++) {
            $xor |= (ord($known_string[$i]) ^ ord($user_string[$i]));
        }

        return $xor === 0;
    }
}

?>