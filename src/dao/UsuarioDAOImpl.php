<?php
require_once __DIR__ . '/../config/Database.php';
require_once 'UsuarioDAO.php';

class UsuarioDAOImpl implements UsuarioDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
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
}
?>