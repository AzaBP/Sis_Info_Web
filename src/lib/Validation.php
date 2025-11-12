<?php
declare(strict_types=1);

class Validation {
    public static function correo(string $c): bool {
        return (bool)filter_var($c, FILTER_VALIDATE_EMAIL);
    }
    public static function texto(string $s, int $min=1, int $max=255): bool {
        $len = mb_strlen(trim($s));
        return $len >= $min && $len <= $max;
    }
    public static function passwordFuerte(string $p): bool {
        return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,72}$/', $p);
    }
    public static function tel(string $t): bool {
        return (bool)preg_match('/^[0-9]{7,15}$/', $t);
    }
    public static function clean(string $s): string { return trim($s); }
}
?>