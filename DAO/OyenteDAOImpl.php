<?php
require_once 'Database.php';
require_once 'OyenteDAO.php';
require_once 'UsuarioDAOImpl.php'; // Necesario para herencia

class OyenteDAOImpl implements OyenteDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function agregarOyente(OyenteVO $oyente) {
        // Primero insertar en Usuario
        $usuarioDAO = new UsuarioDAOImpl($this->conn);
        if (!$usuarioDAO->agregarUsuario($oyente)) {
            return false;
        }

        // Luego insertar en Oyente
        $sql = "INSERT INTO Oyente (usuario_id, preferencias, historial_reproduccion) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $oyente->getUsuarioId());
            $stmt->bindValue(2, $oyente->getPreferencias());
            $stmt->bindValue(3, $oyente->getHistorialReproduccion());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando oyente: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerOyentePorId($usuario_id) {
        $sql = "SELECT u.*, o.preferencias, o.historial_reproduccion 
                FROM Usuario u 
                JOIN Oyente o ON u.usuario_id = o.usuario_id 
                WHERE u.usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new OyenteVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion'],
                    $row['preferencias'],
                    $row['historial_reproduccion']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error buscando oyente: " . $e->getMessage());
            return null;
        }
    }

    
    public function obtenerTodosLosOyentes() {
        $sql = "SELECT u.*, o.preferencias, o.historial_reproduccion 
                FROM Usuario u 
                JOIN Oyente o ON u.usuario_id = o.usuario_id";
        $oyentes = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $oyentes[] = new OyenteVO(
                    $row['usuario_id'],
                    $row['nombre'],
                    $row['correo'],
                    $row['password'],
                    $row['telefono'],
                    $row['codigo_suscripcion'],
                    $row['preferencias'],
                    $row['historial_reproduccion']
                );
            }
            return $oyentes;
        } catch (PDOException $e) {
            error_log("Error listando oyentes: " . $e->getMessage());
            return [];
        }
    }


    public function actualizarOyente(OyenteVO $oyente) {
        // Actualizar en Usuario
        $usuarioDAO = new UsuarioDAOImpl($this->conn);
        if (!$usuarioDAO->actualizarUsuario($oyente)) {
            return false;
        }

        // Actualizar en Oyente
        $sql = "UPDATE Oyente SET preferencias = ?, historial_reproduccion = ? WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $oyente->getPreferencias());
            $stmt->bindValue(2, $oyente->getHistorialReproduccion());
            $stmt->bindValue(3, $oyente->getUsuarioId());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizando oyente: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarOyente($usuario_id) {
        // Primero eliminar de Oyente
        $sql = "DELETE FROM Oyente WHERE usuario_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuario_id);
            $stmt->execute();

            // Luego eliminar de Usuario
            $usuarioDAO = new UsuarioDAOImpl($this->conn);
            return $usuarioDAO->eliminarUsuario($usuario_id);
            
        } catch (PDOException $e) {
            error_log("Error eliminando oyente: " . $e->getMessage());
            return false;
        }
    }
}
?>