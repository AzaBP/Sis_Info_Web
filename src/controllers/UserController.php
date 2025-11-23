<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAOImpl.php';
require_once __DIR__ . '/../lib/Validation.php';
require_once __DIR__ . '/../../config/Database.php'; // Agregar para conexión

class UserController {
    private UsuarioDAO $users;
    public function __construct() { 
        $db = new Database();
        $this->users = new UsuarioDAOImpl($db->getConnection()); // Pasar conexión
    }

    // Obtener el objeto VO del usuario
    public function obtenerUsuarioPorId(string $usuarioId): ?UsuarioVO { 
        // El DAO devuelve un UsuarioVO / null si no se encuentra
        return $this->users->obtenerUsuarioPorId($usuarioId); 
    }

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
    public function eliminarUsuario(string $usuarioId): bool {
        return $this->users->eliminarUsuario($usuarioId);
    }
}
?>