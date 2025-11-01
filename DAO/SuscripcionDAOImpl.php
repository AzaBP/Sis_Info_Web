<?php
require_once __DIR__ . '/../Database.php';
require_once 'SuscripcionDAO.php';

class SuscripcionDAOImpl implements SuscripcionDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function agregarSuscripcion(SuscripcionVO $suscripcion) {
        $sql = "INSERT INTO Suscripcion (codigo_suscripcion, tipo, precio) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $suscripcion->getCodigo());
            $stmt->bindValue(2, $suscripcion->getTipo());
            $stmt->bindValue(3, $suscripcion->getPrecio());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando suscripción: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerSuscripcionPorCodigo($codigo_suscripcion) {
        $sql = "SELECT * FROM Suscripcion WHERE codigo_suscripcion = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $codigo_suscripcion);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new SuscripcionVO(
                    $row['precio'],
                    $row['tipo'],
                    $row['codigo_suscripcion']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error buscando suscripción: " . $e->getMessage());
            return null;
        }
    }


    public function obtenerTodasLasSuscripciones() {
        $sql = "SELECT * FROM Suscripcion";
        $suscripciones = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $suscripciones[] = new SuscripcionVO(
                    $row['precio'],
                    $row['tipo'],
                    $row['codigo_suscripcion']
                );
            }
            return $suscripciones;
        } catch (PDOException $e) {
            error_log("Error listando suscripciones: " . $e->getMessage());
            return [];
        }
    }


    public function actualizarSuscripcion(SuscripcionVO $suscripcion) {
        $sql = "UPDATE Suscripcion SET tipo = ?, precio = ? WHERE codigo_suscripcion = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $suscripcion->getTipo());
            $stmt->bindValue(2, $suscripcion->getPrecio());
            $stmt->bindValue(3, $suscripcion->getCodigo());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizando suscripción: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarSuscripcion($codigo_suscripcion) {
        $sql = "DELETE FROM Suscripcion WHERE codigo_suscripcion = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $codigo_suscripcion);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminando suscripción: " . $e->getMessage());
            return false;
        }
    }
}
?>