<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAOImpl.php';
require_once __DIR__ . '/../lib/Validation.php';
require_once __DIR__ . '/../lib/Session.php';
require_once __DIR__ . '/../../config/Database.php';

class AuthController {
    private UsuarioDAO $usuarioDAO; 
    

    public function __construct() { 
        $database = new Database();
        $connection = $database->getConnection();
        $this->usuarioDAO = new UsuarioDAOImpl($connection); 
    }

    private function nuevoUsuarioId(): string {
        return bin2hex(random_bytes(12)); // 24 hex chars
    }

    public function registrar(string $nombre, string $correo, string $password, string $telefono, ?string $codigoSuscripcion='FREE'): array {
        $nombre  = Validation::clean($nombre);
        $correo  = Validation::clean($correo);
        $tel     = Validation::clean($telefono);
        $codigo  = $codigoSuscripcion ?? 'FREE';

        $errors = [];
        if (!Validation::texto($nombre, 2, 100))     $errors['nombre']  = 'Nombre 2-100';
        if (!Validation::correo($correo))            $errors['correo']  = 'Correo no válido';
        if (!Validation::passwordFuerte($password))  $errors['password']= 'Contraseña poco segura';
        if (!Validation::tel($tel))                  $errors['telefono']= 'Teléfono 7-15 dígitos';

        if ($errors) return ['ok'=>false,'errors'=>$errors];

        if ($this->usuarioDAO->getPorCorreo($correo)) {
            return ['ok'=>false,'errors'=>['correo'=>'Ya existe un usuario con ese correo']];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $uid  = $this->nuevoUsuarioId();

        $ok = $this->usuarioDAO->crearUsuario($uid, $nombre, $hash, $correo, (int)$tel, $codigo);
        if (!$ok) return ['ok'=>false,'errors'=>['global'=>'Error creando usuario']];

        Session::login($uid, $correo);
        return ['ok'=>true,'uid'=>$uid];
    }

    public function login(string $correo, string $password): array {
        $correo = Validation::clean($correo);
        if (!Validation::correo($correo)) return ['ok'=>false,'errors'=>['correo'=>'Correo no válido']];

        $row = $this->usuarioDAO->getPorCorreo($correo);
        if (!$row) return ['ok'=>false,'errors'=>['global'=>'Credenciales incorrectas']];

        $hash = $row['password'] ?? '';
        
        if (password_verify($password, $hash)) {
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                // Aquí podrías añadir un método DAO para actualizar el hash
            }
        } elseif ($password === $hash) {
            // Contraseñas antiguas en claro -> considerar rehash aquí
        } else {
            return ['ok'=>false,'errors'=>['global'=>'Credenciales incorrectas']];
        }
        
        Session::login($row['usuario_id'], $row['correo']);
        return ['ok'=>true];
    }
}
?>