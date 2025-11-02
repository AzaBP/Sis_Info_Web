<?php
require_once __DIR__ . '/../Database.php';
require_once 'CancionDAO.php';

class CancionDAOImpl implements CancionDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function agregarCancion(CancionVO $cancion) {
        $sql = "INSERT INTO Cancion (nombre, nombre_creador, duracion, valoracion) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $cancion->getNombre());
            $stmt->bindValue(2, $cancion->getNombreCreador());
            $stmt->bindValue(3, $cancion->getDuracion());
            $stmt->bindValue(4, $cancion->getValoracion());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando canción: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerCancionPorId($nombre, $nombre_creador) {
        $sql = "SELECT * FROM Cancion WHERE nombre = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $nombre_creador);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new CancionVO($row['nombre'], $row['nombre_creador'], $row['duracion'], $row['valoracion']);
            }
            return null;

        } catch (PDOException $e) {
            error_log("Error buscando canción: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerCancionesPorCreador($nombre_creador) {
        $sql = "SELECT * FROM Cancion WHERE nombre_creador = ?";
        $canciones = [];
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $nombreCreador);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $canciones[] = new CancionVO(
                    $row['nombre'],
                    $row['nombre_creador'],
                    $row['duracion'],
                    $row['valoracion']
                );
            }
            return $canciones;
        } catch (PDOException $e) {
            error_log("Error obteniendo canciones por creador: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodasLasCanciones() {
        $sql = "SELECT * FROM Cancion";
        $canciones = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $canciones[] = new CancionVO($row['nombre'], $row['nombre_creador'], $row['duracion'], $row['valoracion']);
            }
            return $canciones;

        } catch (PDOException $e) {
            error_log("Error listando canciones: " . $e->getMessage());
            return [];
        }
    }


    public function actualizarCancion(CancionVO $cancion) {
        $sql = "UPDATE Cancion SET duracion = ?, valoracion = ? WHERE nombre = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $cancion->getDuracion());
            $stmt->bindValue(2, $cancion->getValoracion());
            $stmt->bindValue(3, $cancion->getNombre());
            $stmt->bindValue(4, $cancion->getNombreCreador());
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error actualizando canción: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarCancion($nombre, $nombre_creador) {
        $sql = "DELETE FROM Cancion WHERE nombre = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $nombre);
            $stmt->bindValue(2, $nombre_creador);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error eliminando canción: " . $e->getMessage());
            return false;
        }
    }
}
?>