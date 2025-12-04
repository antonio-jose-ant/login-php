<?php
namespace App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Repositories\LoginAttemptRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionRepositoryLogout;
use App\Services\AuthService\IpResolver;
use App\Services\AuthService\PasswordValidator;
use App\Services\AuthService\TokenService;
use App\Services\AuthService\ReposeServer;
class AuthService
{
    private $users;
    private $rs;
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
        $this->rs = new ReposeServer();
        $this->justLogaut = new SessionRepositoryLogout($conexion);
    }
    public function auth(string $user, string $pass, string $ua)
    {
        $logValidaUser = $this->users->findByEmail($user);
        $ip = $this->ipResolve->obtenerIP();
        if (!$logValidaUser) {
            $this->logs->registrarIntento($user, $ip, $ua, 'fail', 'No existe usuario');
            $this->rs->error("Usuario y/o contraseña incorrecto", -11,200);
        }
        $hashBD = $logValidaUser['password_hash'];
        // 2. Validar contraseña
        if (empty($user)) {
            $this->rs->error("Parametro usuario vacio", -10,200);

        }
        if (empty($pass)) {
            $this->rs->error("Parametro Contraseña vacio", -10,200);

        }
        if (!password_verify($pass, $hashBD)) {
            $this->logs->registrarIntento($user, $ip, $ua, 'fail', 'Contraseña Incorecta');
            $this->rs->error("Usuario y/o contraseña incorrecto", -11,200);
        }
        $this->logs->registrarIntento($user, $ip, $ua, 'success', '');
        $this->createSession($logValidaUser['id'], $ip, $ua);
        $this->rs->success(null, 1);
    }
    public function exit($token)
    {
        session_start();
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
            'samesite' => 'Lax'
        ]);
        $_SESSION = [];                    // limpiar variables internas
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 3600,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
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
        session_start();
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
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        $_SESSION['uid'] = $id;
        return $hashes['raw'];
    }
}