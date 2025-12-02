<?php
namespace App\Config;
class Database
{
    private $host = '127.0.0.1';
    private $db_name = 'loggin';
    private $user = 'root';
    private $passBD = '';
    private static $instance = null;
    // Propiedad para la conexión PDO
    private $pdo;
    private $charset = 'utf8mb4';
    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // Manejo de errores por excepciones
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,     // Devolver resultados como arrays asociativos
            \PDO::ATTR_EMULATE_PREPARES => false,                 // Desactiva la emulación para consultas preparadas más seguras
        ];

        try {
            // Intenta crear la conexión PDO
            $this->pdo = new \PDO($dsn, $this->user, $this->passBD, $options);
        } catch (\PDOException $e) {
            // Lanza una excepción si la conexión falla
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Método estático para obtener la única instancia de la clase (Singleton).
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Método para obtener la conexión PDO real (si necesitas ejecutar comandos directos).
     */
    public function getConnection(): \PDO
    {
        return $this->pdo;
    }


}