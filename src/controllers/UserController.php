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

    public function actualizarPerfilCompleto(string $usuarioId, array $datos): array {
        $nombre = Validation::clean($datos['nombre'] ?? '');
        $apellidos = Validation::clean($datos['apellidos'] ?? '');
        $email = Validation::clean($datos['email'] ?? '');
        $telefono = Validation::clean($datos['telefono'] ?? '');
        
        $errors = [];
        
        // Validaciones
        if (!Validation::texto($nombre, 2, 100)) {
            $errors['nombre'] = 'El nombre debe tener entre 2 y 100 caracteres';
        }
        
        if (!Validation::correo($email)) {
            $errors['email'] = 'El correo electrónico no es válido';
        }
        
        if (!empty($telefono) && !Validation::tel($telefono)) {
            $errors['telefono'] = 'El teléfono debe tener entre 7 y 15 dígitos';
        }
        
        if (!empty($apellidos) && !Validation::texto($apellidos, 0, 200)) {
            $errors['apellidos'] = 'Los apellidos son demasiado largos';
        }
        
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }
        
        $ok = $this->users->actualizarPerfil($usuarioId, $nombre, (int)$telefono);
        
        return ['ok' => $ok];
    }
}
?>