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
        
        // Buscar playlists donde el usuario_id sea el usuario actual
        $sql = "SELECT DISTINCT lista_id FROM playlist WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$uid]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("DEBUG listasDeUsuario - Usuario: $uid, Playlists encontradas: " . count($resultados));
        return $resultados;
    }

    public function cancionesDeLista(string $listaId): array {
        if (!$this->db) return [];
        
        try {
            // Obtener la primera canción de la base (que usamos como marcador)
            $sqlPrimeraCancion = "SELECT nombre, nombre_creador FROM cancion LIMIT 1";
            $stmtPrimera = $this->db->query($sqlPrimeraCancion);
            $marcador = $stmtPrimera->fetch(PDO::FETCH_ASSOC);
            
            if (!$marcador) {
                // Si no hay canción marcadora, devolver todas las canciones
                $sql = "SELECT nombre_cancion, nombre_creador FROM playlist WHERE lista_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$listaId]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Excluir la canción marcadora
            $sql = "SELECT nombre_cancion, nombre_creador FROM playlist 
                    WHERE lista_id = ? 
                    AND NOT (nombre_cancion = ? AND nombre_creador = ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$listaId, $marcador['nombre'], $marcador['nombre_creador']]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error en cancionesDeLista: " . $e->getMessage());
            return [];
        }
}
            
    public function crearLista(string $uid, string $listaId): array {
        if (!$this->db) return ['ok' => false, 'error' => 'Database connection error'];
        
        try {
            $this->db->beginTransaction();
            
            // 1. Verificar/crear en tabla 'lista'
            $checkListaSql = "SELECT COUNT(*) FROM lista WHERE lista_id = ?";
            $checkListaStmt = $this->db->prepare($checkListaSql);
            $checkListaStmt->execute([$listaId]);
            
            if ($checkListaStmt->fetchColumn() == 0) {
                $insertListaSql = "INSERT INTO lista (lista_id) VALUES (?)";
                $insertListaStmt = $this->db->prepare($insertListaSql);
                $insertListaStmt->execute([$listaId]);
                error_log("DEBUG crearLista - Lista creada en tabla 'lista': $listaId");
            }
            
            // 2. Verificar si ya existe para este usuario
            $checkSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND usuario_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$listaId, $uid]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $this->db->rollBack();
                return ['ok' => false, 'error' => 'Ya tienes una playlist con ese nombre'];
            }
            
            // 3. SOLUCIÓN: Buscar una canción existente para usar como marcador
            $sqlCancion = "SELECT nombre, nombre_creador FROM cancion LIMIT 1";
            $stmtCancion = $this->db->query($sqlCancion);
            $cancion = $stmtCancion->fetch(PDO::FETCH_ASSOC);
            
            if (!$cancion) {
                $this->db->rollBack();
                return ['ok' => false, 'error' => 'No hay canciones disponibles para crear la playlist'];
            }
            
            // 4. Insertar usando una canción real como marcador
            $insertSql = "INSERT INTO playlist (lista_id, nombre_cancion, nombre_creador, usuario_id, es_publica) 
                        VALUES (?, ?, ?, ?, TRUE)";
            $insertStmt = $this->db->prepare($insertSql);
            $resultado = $insertStmt->execute([
                $listaId, 
                $cancion['nombre'],  // Usar una canción real
                $cancion['nombre_creador'],  // Usar un creador real
                $uid
            ]);
            
            if (!$resultado) {
                $this->db->rollBack();
                error_log("DEBUG crearLista - Error en inserción: " . print_r($insertStmt->errorInfo(), true));
                return ['ok' => false, 'error' => 'Error al insertar en la base de datos'];
            }
            
            $this->db->commit();
            
            error_log("DEBUG crearLista - Playlist creada exitosamente: $listaId para usuario: $uid");
            return ['ok' => true];
            
        } catch (PDOException $e) {
            if (isset($this->db) && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error creating playlist: " . $e->getMessage());
            error_log("Error SQL: " . $e->getMessage());
            return ['ok' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()];
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

 public function eliminarPlaylist(string $listaId, string $usuarioId): array {
        if (!$this->db) return ['ok' => false, 'error' => 'Database connection error'];
        
        try {
            $this->db->beginTransaction();
            
            // Verificar que la playlist pertenece al usuario
            $checkSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ? AND usuario_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$listaId, $usuarioId]);
            
            if ($checkStmt->fetchColumn() == 0) {
                $this->db->rollBack();
                return ['ok' => false, 'error' => 'No tienes permisos para eliminar esta playlist'];
            }
            
            // Eliminar todas las canciones de la playlist de este usuario
            $deleteSongsSql = "DELETE FROM playlist WHERE lista_id = ? AND usuario_id = ?";
            $deleteSongsStmt = $this->db->prepare($deleteSongsSql);
            $deleteSongsStmt->execute([$listaId, $usuarioId]);
            
            // Verificar si quedan otras canciones en la playlist (de otros usuarios)
            $checkOtrasSql = "SELECT COUNT(*) FROM playlist WHERE lista_id = ?";
            $checkOtrasStmt = $this->db->prepare($checkOtrasSql);
            $checkOtrasStmt->execute([$listaId]);
            
            // Si no quedan más canciones, eliminar también de la tabla 'lista'
            if ($checkOtrasStmt->fetchColumn() == 0) {
                $deleteListaSql = "DELETE FROM lista WHERE lista_id = ?";
                $deleteListaStmt = $this->db->prepare($deleteListaSql);
                $deleteListaStmt->execute([$listaId]);
            }
            
            $this->db->commit();
            
            error_log("DEBUG eliminarPlaylist - Playlist eliminada: $listaId por usuario: $usuarioId");
            return ['ok' => true];
            
        } catch (PDOException $e) {
            if (isset($this->db) && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error deleting playlist: " . $e->getMessage());
            return ['ok' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }
}
?>