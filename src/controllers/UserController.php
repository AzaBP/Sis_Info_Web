<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAOImpl.php';
require_once __DIR__ . '/../lib/Validation.php';

class UserController {
    private UsuarioDAO $users;
    public function __construct() { $this->users = new UsuarioDAOImpl(); }

    public function actualizarPerfil(string $usuarioId, string $nombre, string $telefono): array {
        $nombre = Validation::clean($nombre);
        $telefono = Validation::clean($telefono);

        $errors = [];
        if (!Validation::texto($nombre, 2, 100)) $errors['nombre']='Nombre 2-100';
        if (!Validation::tel($telefono))         $errors['telefono']='Teléfono 7-15 dígitos';
        if ($errors) return ['ok'=>false,'errors'=>$errors];

        $ok = $this->users->actualizarPerfil($usuarioId, $nombre, (int)$telefono);
        return ['ok'=>$ok];
    }
}
?>