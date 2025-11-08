<?php
require_once __DIR__ . '/../../config/Database.php';
require_once 'UsuarioDAO.php';

class UsuarioDAOImpl implements UsuarioDAO {
    private $conn;
    private PDO $db;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->db = Database::getConnection();
    }

    
    public function agregarUsuario(UsuarioVO $usuario) {
        $sql = "INSERT INTO Usuario (usuario_id, nombre, correo, password, telefono, codigo_suscripcion) VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getUsuarioId());
            $stmt->bindValue(2, $usuario->getNombre());
            $stmt->bindValue(3, $usuario->getCorreo());
            $stmt->bindValue(4, $usuario->getPassword());
            $stmt->bindValue(5, $usuario->getTelefono());
            $stmt->bindValue(6, $usuario->getCodigoSuscripcion());
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error insertando usuario: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerUsuarioPorId($usuario_id) {
        $sql = "SELECT * FROM Usuario WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new UsuarioVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion']
                );
            }
            return null;

        } catch (PDOException $e) {
            error_log("Error buscando usuario: " . $e->getMessage());
            return null;
        }
    }


    public function obtenerTodosLosUsuarios() {
        $sql = "SELECT * FROM Usuario";
        $usuarios = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuarios[] = new UsuarioVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion']
                );
            }
            return $usuarios;
        } catch (PDOException $e) {
            error_log("Error listando usuarios: " . $e->getMessage());
            return [];
        }
    }


    public function actualizarUsuario(UsuarioVO $usuario) {
        $sql = "UPDATE Usuario SET nombre = ?, correo = ?, password = ?, telefono = ?, codigo_suscripcion = ? WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getNombre());
            $stmt->bindValue(2, $usuario->getCorreo());
            $stmt->bindValue(3, $usuario->getPassword());
            $stmt->bindValue(4, $usuario->getTelefono());
            $stmt->bindValue(5, $usuario->getCodigoSuscripcion());
            $stmt->bindValue(6, $usuario->getUsuarioId());
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error actualizando usuario: " . $e->getMessage());
            return false;
        }
    }

    
    public function eliminarUsuario($usuario_id) {
        $sql = "DELETE FROM Usuario WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error eliminando usuario: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT * FROM Usuario WHERE correo = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $correo);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new UsuarioVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion']
                );
            }
            return null;

        } catch (PDOException $e) {
            error_log("Error buscando usuario por correo: " . $e->getMessage());
            return null;
        }
    }

    
    public function crearUsuario(string $usuarioId, string $nombre, string $hash, string $correo, int $telefono, string $codigoSuscripcion): bool {
        $sql = "INSERT INTO Usuario (usuario_id, nombre, password, correo, telefono, codigo_suscripcion)
                VALUES (?,?,?,?,?,?)";
        $st = $this->db->prepare($sql);
        $st->bindValue(1, $usuarioId);
        $st->bindValue(2, $nombre);
        $st->bindValue(3, $hash);
        $st->bindValue(4, $correo);
        $st->bindValue(5, $telefono, PDO::PARAM_INT);
        $st->bindValue(6, $codigoSuscripcion);
        return $st->execute();
    }

    public function getPorCorreo(string $correo): ?array {
        $st = $this->db->prepare("SELECT usuario_id, nombre, password, correo, telefono, codigo_suscripcion
                                  FROM Usuario WHERE correo=? LIMIT 1");
        $st->bindValue(1, $correo);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function actualizarPerfil(string $usuarioId, string $nombre, int $telefono): bool {
        $st = $this->db->prepare("UPDATE Usuario SET nombre=?, telefono=? WHERE usuario_id=?");
        $st->bindValue(1, $nombre);
        $st->bindValue(2, $telefono, PDO::PARAM_INT);
        $st->bindValue(3, $usuarioId);
        return $st->execute();
    }


}
?>