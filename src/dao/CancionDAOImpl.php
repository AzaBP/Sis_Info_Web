<?php
require_once __DIR__ . '/../../config/Database.php';
require_once 'CancionDAO.php';

class CancionDAOImpl implements CancionDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function agregarCancion(CancionVO $cancion) {
        $sql = "INSERT INTO Cancion (nombre, nombre_creador, duración, valoración) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $cancion->getNombre());
            $stmt->bindValue(2, $cancion->getNombreCreador());
            $stmt->bindValue(3, $cancion->getduración());
            $stmt->bindValue(4, $cancion->getvaloración());
            
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
                return new CancionVO($row['nombre'], $row['nombre_creador'], $row['duración'], $row['valoración']);
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
            $stmt->bindValue(1, $nombre_creador);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $canciones[] = new CancionVO(
                    $row['nombre'],
                    $row['nombre_creador'],
                    $row['duración'],
                    $row['valoración']
                );
            }
            return $canciones;
        } catch (PDOException $e) {
            error_log("Error obteniendo canciones por creador: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodasLasCanciones() {
        $sql = "SELECT * FROM Cancion LIMIT 8";
        $canciones = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Crear objeto CancionVO
                $cancion = new CancionVO(
                    $row['nombre'],
                    $row['nombre_creador'],
                    $row['duración'],
                    $row['valoración']
                );
                $canciones[] = $cancion;
            }
            return $canciones;

        } catch (PDOException $e) {
            error_log("Error listando canciones: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarCancion(CancionVO $cancion) {
        $sql = "UPDATE Cancion SET duración = ?, valoración = ? WHERE nombre = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $cancion->getduración());
            $stmt->bindValue(2, $cancion->getvaloración());
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

    // MÉTODO ADICIONAL: Para obtener canciones con imágenes (para la página principal)
    public function obtenerCancionesConImagenes() {
        $sql = "SELECT * FROM Cancion LIMIT 8";
        $canciones = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $imagenes = [
                'Bohemian Rhapsody' => '../imagenes/Estopa.jpg',
                'Blinding Lights' => '../imagenes/ACDC.jpg',
                'Shape of You' => '../imagenes/nectar.jpg',
                'Bad Guy' => '../imagenes/trench.jpg',
                'Dakiti' => '../imagenes/Estopa.jpg',
                'Watermelon Sugar' => '../imagenes/ACDC.jpg',
                'Levitating' => '../imagenes/nectar.jpg',
                'Stay' => '../imagenes/trench.jpg'
            ];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $canciones[] = [
                    'nombre' => $row['nombre'],
                    'creador' => $row['nombre_creador'],
                    'duración' => $row['duración'],
                    'valoración' => $row['valoración'],
                    'imagen' => $imagenes[$row['nombre']] ?? '../imagenes/Estopa.jpg'
                ];
            }
            return $canciones;

        } catch (PDOException $e) {
            error_log("Error listando canciones con imágenes: " . $e->getMessage());
            return [];
        }
    }
}