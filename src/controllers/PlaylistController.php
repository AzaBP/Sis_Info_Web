<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/Database.php';

class PlaylistController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        error_log("DEBUG PlaylistController - Conexión a BD: " . ($this->db ? "ÉXITO" : "FALLO"));
    }
    
    public function obtenerPlaylistsPublicas(): array {
        if (!$this->db) return [];
        
        $sql = "SELECT DISTINCT lista_id FROM playlist WHERE es_publica = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listasDeUsuario(string $uid): array {
        if (!$this->db) return [];
        
        $sql = "SELECT DISTINCT lista_id FROM playlist WHERE nombre_creador = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$uid]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("DEBUG listasDeUsuario - Usuario: $uid, Playlists encontradas: " . count($resultados));
        return $resultados;
    }
    
    public function cancionesDeLista(string $listaId): array {
        if (!$this->db) return [];
        
        // PRIMERO: Verificar si la playlist existe
        $checkSql = "SELECT COUNT(*) as total FROM playlist WHERE lista_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([$listaId]);
        $totalRegistros = $checkStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        error_log("DEBUG cancionesDeLista - Playlist: $listaId, Total registros: $totalRegistros");
        
        // LUEGO: Obtener las canciones
        $sql = "SELECT nombre_cancion, nombre_creador FROM playlist 
                WHERE lista_id = ? AND nombre_cancion != '' AND nombre_cancion != '[PLAYLIST_CREADA]'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$listaId]);
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("DEBUG cancionesDeLista - Playlist: $listaId, Canciones encontradas: " . count($resultados));
        
        if (count($resultados) > 0) {
            foreach ($resultados as $index => $fila) {
                error_log("DEBUG - Canción $index: " . $fila['nombre_cancion'] . " - " . $fila['nombre_creador']);
            }
        } else {
            error_log("DEBUG - No se encontraron canciones para la playlist: $listaId");
            
            // Debug adicional: ver todos los registros de esta playlist
            $debugSql = "SELECT * FROM playlist WHERE lista_id = ?";
            $debugStmt = $this->db->prepare($debugSql);
            $debugStmt->execute([$listaId]);
            $todosLosRegistros = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("DEBUG - Todos los registros de $listaId: " . print_r($todosLosRegistros, true));
        }
        
        return $resultados;
    }
    
    public function crearLista(string $uid, string $listaId): array {
        if (!$this->db) return ['ok' => false, 'error' => 'Database connection error'];
        
        try {
            // Verificar si la lista ya existe para este usuario
            $checkSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND usuario_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$listaId, $uid]);
            
            if ($checkStmt->fetchColumn() > 0) {
                return ['ok' => false, 'error' => 'Ya tienes una playlist con ese nombre'];
            }
            
            // Insertar marcador de playlist creada
            $insertSql = "INSERT INTO playlist (lista_id, nombre_cancion, nombre_creador, usuario_id) VALUES (?, '[PLAYLIST_CREADA]', ?, ?)";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->execute([$listaId, $uid, $uid]);
            
            error_log("DEBUG crearLista - Playlist creada: $listaId para usuario: $uid");
            return ['ok' => true];
            
        } catch (PDOException $e) {
            error_log("Error creating playlist: " . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function agregarCancion(string $listaId, string $cancion, string $creador, string $usuarioId): array {
        if (!$this->db) return ['ok' => false, 'error' => 'Database connection error'];
        
        try {
            error_log("DEBUG agregarCancion - INICIANDO: Lista: $listaId, Canción: $cancion, Creador: $creador, Usuario: $usuarioId");
            
            // Verificar si la playlist existe y pertenece al usuario
            $checkPlaylistSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND usuario_id = ?";
            $checkPlaylistStmt = $this->db->prepare($checkPlaylistSql);
            $checkPlaylistStmt->execute([$listaId, $usuarioId]);
            
            if ($checkPlaylistStmt->fetchColumn() == 0) {
                return ['ok' => false, 'error' => 'Playlist no encontrada o no tienes permisos'];
            }
            
            // Verificar si la canción ya existe en la playlist
            $checkSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND nombre_cancion = ? AND nombre_creador = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$listaId, $cancion, $creador]);
            $existe = $checkStmt->fetchColumn();
            
            error_log("DEBUG agregarCancion - ¿Canción ya existe?: " . ($existe ? 'SÍ' : 'NO'));
            
            if ($existe > 0) {
                return ['ok' => false, 'error' => 'Esta canción ya está en la playlist'];
            }
            
            // Insertar la canción
            $sql = "INSERT INTO playlist (lista_id, nombre_cancion, nombre_creador, usuario_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([$listaId, $cancion, $creador, $usuarioId]);
            
            error_log("DEBUG agregarCancion - Resultado inserción: " . ($resultado ? 'ÉXITO' : 'FALLO'));
            
            return ['ok' => $resultado];
            
        } catch (PDOException $e) {
            error_log("ERROR agregarCancion: " . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function eliminarCancion(string $listaId, string $cancion, string $creador): array {
        if (!$this->db) return ['ok' => false, 'error' => 'Database connection error'];
        
        try {
            $sql = "DELETE FROM playlist WHERE lista_id = ? AND nombre_cancion = ? AND nombre_creador = ?";
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([$listaId, $cancion, $creador]);
            
            return ['ok' => $resultado];
            
        } catch (PDOException $e) {
            error_log("Error deleting song: " . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function obtenerPlaylistPublica($listaId) {
    if (!$this->db) return null;
    
    try {
        // Verificar si la playlist es pública
        $sql = "SELECT DISTINCT lista_id FROM playlist WHERE lista_id = ? AND es_publica = TRUE LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$listaId]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['lista_id'] : null;
        
    } catch (PDOException $e) {
        error_log("Error obteniendo playlist pública: " . $e->getMessage());
        return null;
    }
}

    public function esPlaylistPublica($listaId) {
        if (!$this->db) return false;
        
        try {
            $sql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND es_publica = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$listaId]);
            
            return $stmt->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log("Error verificando playlist pública: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPlaylistsRecomendadas(string $uidExcluir): array {
        if (!$this->db) return [];
        
        try {
            // Obtener playlists públicas que no sean del usuario actual
            // Si no tienes columna 'es_publica', usa esta consulta alternativa:
            $sql = "SELECT DISTINCT lista_id 
                    FROM playlist 
                    WHERE nombre_creador != ? 
                    AND lista_id NOT IN (
                        SELECT DISTINCT lista_id 
                        FROM playlist 
                        WHERE nombre_creador = ?
                    )
                    LIMIT 6";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$uidExcluir, $uidExcluir]);
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Si no hay suficientes, obtener algunas playlists públicas aleatorias
            if (count($resultados) < 3) {
                $sqlBackup = "SELECT DISTINCT lista_id 
                            FROM playlist 
                            WHERE lista_id NOT IN (
                                SELECT DISTINCT lista_id 
                                FROM playlist 
                                WHERE nombre_creador = ?
                            )
                            ORDER BY RAND() 
                            LIMIT 3";
                
                $stmtBackup = $this->db->prepare($sqlBackup);
                $stmtBackup->execute([$uidExcluir]);
                $resultadosBackup = $stmtBackup->fetchAll(PDO::FETCH_ASSOC);
                
                $resultados = array_merge($resultados, $resultadosBackup);
            }
            
            // Eliminar duplicados y limitar a 3
            $resultadosUnicos = [];
            foreach ($resultados as $playlist) {
                if (!in_array($playlist['lista_id'], array_column($resultadosUnicos, 'lista_id'))) {
                    $resultadosUnicos[] = $playlist;
                }
                if (count($resultadosUnicos) >= 3) break;
            }
            
            return $resultadosUnicos;
            
        } catch (PDOException $e) {
            error_log("Error obteniendo playlists recomendadas: " . $e->getMessage());
            return [];
        }
}
}
?>