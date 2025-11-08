<?php
declare(strict_types=1);

class Session {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0, 'path' => '/',
                'secure' => false,           // pon true con HTTPS
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }
    public static function login(string $usuarioId, string $correo): void {
        self::start();
        session_regenerate_id(true);
        $_SESSION['uid']    = $usuarioId;
        $_SESSION['correo'] = $correo;
    }
    public static function logout(): void {
        self::start(); $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'],$p['domain'],$p['secure'],$p['httponly']);
        }
        session_destroy();
    }
    public static function check(): bool { self::start(); return isset($_SESSION['uid']); }
    public static function requireLogin(): void {
        if (!self::check()) { header('Location: /vmusic/public/login.php?e=login_required'); exit; }
    }
    public static function csrfToken(): string {
        self::start();
        if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf'];
    }
    public static function verifyCsrf(string $t): bool {
        self::start(); return hash_equals($_SESSION['csrf'] ?? '', $t);
    }
}