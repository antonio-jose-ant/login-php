<?php
namespace App\Services;

class ReposeServer
{
    /**
     * Respuesta exitosa
     */
    public function success($data = null, int $code = 200)
    {
        $this->sendJson([
            'status' => 'success',
            'code'   => $code,
            'data'   => $data
        ], $code);
    }

    /**
     * Respuesta de error controlado
     */
    public function error(string $message, int $code = 400)
    {
        $this->sendJson([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message
        ], $code);
    }

    /**
     * Respuesta general
     */
    private function sendJson(array $payload, int $httpCode)
    {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
