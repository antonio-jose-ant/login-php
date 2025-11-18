<?php
namespace App\Config;
class Database
{
private $host;
    private $db_name;
    private $user;
    private $passBD;
    private static $instance = null;
    private $pdo;
    private $charset = 'utf8mb4';
    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'loggin';
        $this->user = $_ENV['DB_USER'] ?? 'root';
        $this->passBD = $_ENV['DB_PASS'] ?? '';

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