<?php
namespace App\Services\AuthService;

class ReposeServer
{
    /**
     * Respuesta exitosa
     */
    public function success($data = null,int $codeInternal=1, int $code = 200)
    {
        $this->sendJson([
            'status' => 'success',
            'code' => $codeInternal,
            'data' => $data
        ], $code);
    }

    /**
     * Respuesta de error controlado
     */
    public function error(string $message,int $codeInternal=-1, int $code = 400)
    {
        $this->sendJson([
            'status' => 'error',
            'code' => $codeInternal,
            'message' => $message,
            'httpError'=>$code
        ], $code);
    }

    /**
     * Respuesta general
     */
    private function sendJson(array $payload, int $httpCode)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json");
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
