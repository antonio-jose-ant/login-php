<?php
namespace App\services;
use App\repositories\UserRepository;
use App\repositories\LoginAttemptRepository;
use App\repositories\SessionRepository;
use App\repositories\SessionRepositoryLogout;
use App\services\IpResolver;
use App\services\PasswordValidator;
use App\services\TokenService;
use BcMath\Number;
class AuthService
{
    private $users;
    private $sessions;
    private $logs;
    private $validator;
    private $tokens;
    private $ipResolve;
    private $justLogaut;
    public function __construct(private \PDO $conexion)
    {
        $this->users = new UserRepository($conexion);
        $this->sessions = new SessionRepository($conexion);
        $this->logs = new LoginAttemptRepository($conexion);
        $this->validator = new PasswordValidator();
        $this->tokens = new TokenService();
        $this->ipResolve = new IpResolver();
        $this->justLogaut = new SessionRepositoryLogout($conexion);
    }
    public function auth(string $user, string $pass, string $ua)
    {
        $logValidaUser = $this->users->findByEmail($user);
        $ip = $this->ipResolve->obtenerIP();
        if (!$logValidaUser) {
            $this->logs->registrarIntento($user, $ip, $ua, 'fail', 'No existe usuario');
            throw new \Exception("Usuario y/o contraseña incorrecto use", -10);
        }
        $hashBD = $logValidaUser['password_hash'];
        // 2. Validar contraseña
        if (empty($user)) {
            throw new \Exception("Parametro usuario vacio", -9);
        }
        if (empty($pass)) {
            throw new \Exception("Parametro Contraseña vacio", -9);
        }
        if (!password_verify($pass, $hashBD)) {
            $this->logs->registrarIntento($user, $ip, $ua, 'fail', 'Contraseña Incorecta');
            throw new \Exception("Usuario y/o contraseña incorrecto pas", -11);
        }
        $this->logs->registrarIntento($user, $ip, $ua, 'fail', 'Contraseña Incorecta');
        $this->createSession($logValidaUser['id'], $ip, $ua);
        return true;
    }
    public function exit($token)
    {
        $hash = $this->tokens->searchTokenBD($token);
        try {
            $this->justLogaut->Delete($hash);
        } catch (\Exception $e) {
            throw $e;
        }
        setcookie('session_token', '', [
            'expires' => time() - 3600, // Hace que expire inmediatamente (hace 1 hora)
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        setcookie('uid', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true, // o false si lo necesitas leer con JS
            'samesite' => 'Strict'
        ]);
        return true;

    }
    public function isItAuthorized(int $uid, string $tokenRaw): bool
    {
        $tokenHash = hash('sha256', $tokenRaw);
        return $this->sessions->verifyToken($tokenHash, $uid);
    }
    public function addUser(string $user, string $pass)
    {
        if (empty($user) || !filter_var($user, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Formato de email inválido o vacío.", -12);
        }
        try {
            $this->validator->validarPassword($pass);
        } catch (\Exception $e) {
            throw $e;
        }
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new \Exception("Error al generar el hash de la contraseña.", -13);
        }
        try {
            if ($this->users->create($user, $hash)) {
                return true;
            }
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \Exception("El email ya está registrado.", -14);
            }
            throw new \Exception("Error al registrar el usuario: " . $e->getMessage(), -15);
        }
        throw new \Exception("El registro del usuario falló por una razón desconocida.", -16);
    }
    private function createSession($id, $ip, $ua, )
    {
        $hashes = $this->tokens->createToken();
        $expires = new \DateTime('+7 days');
        try {
            $this->sessions->create($id, $hashes['hash'], $ip, $ua, $expires->format('Y-m-d H:i:s'));
        } catch (\Exception $e) {
            throw $e;
        }
        setcookie('session_token', $hashes['raw'], [
            'expires' => $expires->getTimestamp(),
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        setcookie('uid', $id, [
            'expires' => $expires->getTimestamp(),
            'path' => '/',
            'secure' => true,
            'httponly' => true, // o false si lo necesitas leer con JS
            'samesite' => 'Strict'
        ]);
        return $hashes['raw'];
    }
}