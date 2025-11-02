<?php
require_once __DIR__ . '/../config/Database.php';
require_once 'PlaylistDAO.php';

class PlaylistDAOImpl implements PlaylistDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function agregarPlaylist(PlaylistVO $playlist) {
        $sql = "INSERT INTO Playlist (lista_id, nombre_cancion, nombre_creador) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $playlist->getListaId());
            $stmt->bindValue(2, $playlist->getNombreCancion());
            $stmt->bindValue(3, $playlist->getNombreCreador());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando en playlist: " . $e->getMessage());
            return false;
        }
    }


    public function obtenerPlaylistPorId($lista_id, $nombre_cancion, $nombre_creador) {
        $sql = "SELECT * FROM Playlist WHERE lista_id = ? AND nombre_cancion = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
            $stmt->bindValue(2, $nombre_cancion);
            $stmt->bindValue(3, $nombre_creador);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new PlaylistVO(
                    $row['lista_id'],
                    $row['nombre_cancion'],
                    $row['nombre_creador']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error buscando en playlist: " . $e->getMessage());
            return null;
        }
    }


    public function obtenerTodasLasPlaylists() {
        $sql = "SELECT * FROM Playlist";
        $playlists = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $playlists[] = new PlaylistVO(
                    $row['lista_id'],
                    $row['nombre_cancion'],
                    $row['nombre_creador']
                );
            }
            return $playlists;
        } catch (PDOException $e) {
            error_log("Error listando playlists: " . $e->getMessage());
            return [];
        }
    }


    public function obtenerPlaylistsPorLista($lista_id) {
        $sql = "SELECT * FROM Playlist WHERE lista_id = ?";
        $playlists = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $playlists[] = new PlaylistVO(
                    $row['lista_id'],
                    $row['nombre_cancion'],
                    $row['nombre_creador']
                );
            }
            return $playlists;
        } catch (PDOException $e) {
            error_log("Error buscando playlists por lista: " . $e->getMessage());
            return [];
        }
    }


    public function obtenerCancionesPorLista($lista_id) {
        $sql = "SELECT c.* FROM Cancion c 
                JOIN Playlist p ON c.nombre = p.nombre_cancion AND c.nombre_creador = p.nombre_creador
                WHERE p.lista_id = ?";
        $canciones = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
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
            error_log("Error obteniendo canciones por lista: " . $e->getMessage());
            return [];
        }
    }


    public function eliminarPlaylist($lista_id, $nombre_cancion, $nombre_creador) {
        $sql = "DELETE FROM Playlist WHERE lista_id = ? AND nombre_cancion = ? AND nombre_creador = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
            $stmt->bindValue(2, $nombre_cancion);
            $stmt->bindValue(3, $nombre_creador);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminando de playlist: " . $e->getMessage());
            return false;
        }
    }
}
?>