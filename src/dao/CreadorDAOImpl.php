<?php
require_once __DIR__ . '/../config/Database.php';
require_once 'CreadorDAO.php';

class CreadorDAOImpl implements CreadorDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function agregarCreador(CreadorVO $creador) {
        // Primero insertar en Usuario
        $usuarioDAO = new UsuarioDAOImpl($this->conn);
        if (!$usuarioDAO->agregarUsuario($creador)) {
            return false;
        }

        // Luego insertar en Creador
        $sql = "INSERT INTO Creador (usuario_id, biografia, numero_seguidores) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $creador->getUsuarioId());
            $stmt->bindValue(2, $creador->getBiografia());
            $stmt->bindValue(3, $creador->getNumeroSeguidores());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando creador: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerCreadorPorId($usuario_id) {
        $sql = "SELECT u.*, c.biografia, c.numero_seguidores 
                FROM Usuario u 
                JOIN Creador c ON u.usuario_id = c.usuario_id 
                WHERE u.usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $creador = new CreadorVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion'],
                    $row['biografia'],
                    $row['numero_seguidores']
                );
                return $creador;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error buscando creador: " . $e->getMessage());
            return null;
        }
    }


    public function obtenerTodosLosCreadores() {
        $sql = "SELECT u.*, c.biografia, c.numero_seguidores 
                FROM Usuario u 
                JOIN Creador c ON u.usuario_id = c.usuario_id";
        $creadores = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $creadores[] = new CreadorVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion'],
                    $row['biografia'],
                    $row['numero_seguidores']
                );
            }
            return $creadores;
        } catch (PDOException $e) {
            error_log("Error listando creadores: " . $e->getMessage());
            return [];
        }
    }


    public function actualizarCreador(CreadorVO $creador) {
        // Actualizar en usuario
        $usuarioDAO = new UsuarioDAOImpl($this->conn);
        if (!$usuarioDAO->actualizarUsuario($creador)) {
            return false;
        }

        // Actualizar en creador
        $sql = "UPDATE Creador SET biografia = ?, numero_seguidores = ? WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $creador->getBiografia());
            $stmt->bindValue(2, $creador->getNumeroSeguidores());
            $stmt->bindValue(3, $creador->getUsuarioId());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizando creador: " . $e->getMessage());
            return false;
        }
    }

    
    public function eliminarCreador($usuario_id) {
        // Primero eliminar de creador
        $sql = "DELETE FROM Creador WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            $stmt->execute();

            // Luego eliminar de usuario
            $usuarioDAO = new UsuarioDAOImpl($this->conn);
            return $usuarioDAO->eliminarUsuario($usuario_id);
            
        } catch (PDOException $e) {
            error_log("Error eliminando creador: " . $e->getMessage());
            return false;
        }
    }
}
?>