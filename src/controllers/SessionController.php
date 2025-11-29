<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/Session.php';

class SessionController {
    
    public static function getCurrentUser(): ?string {
        Session::start();
        return $_SESSION['uid'] ?? null;
    }
    
    public static function getUserEmail(): ?string {
        Session::start();
        return $_SESSION['correo'] ?? null;
    }
    
    public static function isLoggedIn(): bool {
        return self::getCurrentUser() !== null;
    }
    
    public static function requireAuth(): void {
        if (!self::isLoggedIn()) {
            header('Location: /vmusic/public/login.php?e=auth_required');
            exit;
        }
    }
    
    public static function redirectIfLoggedIn(string $redirectTo = 'perfil.php'): void {
        if (self::isLoggedIn()) {
            header("Location: $redirectTo");
            exit;
        }
    }
    
    public static function logout(): void {
        Session::logout();
    }
    
    public static function flashMessage(string $type, string $message): void {
        Session::start();
        $_SESSION['flash_' . $type] = $message;
    }
    
    public static function getFlashMessage(string $type): ?string {
        Session::start();
        $message = $_SESSION['flash_' . $type] ?? null;
        unset($_SESSION['flash_' . $type]);
        return $message;
    }

    public static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
?>