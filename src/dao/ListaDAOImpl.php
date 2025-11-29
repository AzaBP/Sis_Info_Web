<?php
require_once __DIR__ . '/../../config/Database.php';
require_once 'ListaDAO.php';

class ListaDAOImpl implements ListaDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function agregarLista(ListaVO $lista) {
        $sql = "INSERT INTO Lista (lista_id) VALUES (?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista->getListaId());
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertando lista: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerListaPorId($lista_id) {
        $sql = "SELECT * FROM Lista WHERE lista_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
            $stmt->execute();
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new ListaVO($row['lista_id']);
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error buscando lista: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerTodasLasListas() {
        $sql = "SELECT * FROM Lista";
        $listas = [];
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $listas[] = new ListaVO($row['lista_id']);
            }
            return $listas;
        } catch (PDOException $e) {
            error_log("Error listando listas: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarLista(ListaVO $lista) {
        // Solo tiene lista_id, no hay mucho que actualizar
        return true;
    }

    public function eliminarLista($lista_id) {
        $sql = "DELETE FROM Lista WHERE lista_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminando lista: " . $e->getMessage());
            return false;
        }
    }
    public function crearLista(string $listaId, string $usuarioId): bool {
        $sql = "INSERT INTO lista (lista_id, usuario_id, nombre) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $listaId);
            $stmt->bindValue(2, $usuarioId);
            $stmt->bindValue(3, $listaId); // Asumimos que nombre = lista_id
            
            $result = $stmt->execute();
            
            // DEBUG
            error_log("DAO - Crear lista: '$listaId' para usuario: '$usuarioId'");
            error_log("DAO - SQL ejecutado: " . ($result ? 'ÉXITO' : 'FALLÓ'));
            
            if (!$result) {
                error_log("DAO - Error info: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("DAO - Excepción al crear lista: " . $e->getMessage());
            error_log("DAO - Código error: " . $e->getCode());
            return false;
        }
    }

    public function obtenerListasDeUsuario(string $usuarioId): array {
        try {
            // ✅ CORREGIDO: Buscar por usuario_id en la tabla Lista
            $sql = "SELECT lista_id, nombre 
                    FROM lista 
                    WHERE usuario_id = ? 
                    ORDER BY lista_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $usuarioId);
            $stmt->execute();
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // DEBUG
            error_log("Listas encontradas para usuario $usuarioId: " . count($resultados));
            foreach ($resultados as $lista) {
                error_log(" - Lista: " . $lista['lista_id']);
            }
            
            return $resultados;
            
        } catch (PDOException $e) {
            error_log("Error obteniendo listas de usuario: " . $e->getMessage());
            return [];
        }
    }
}
?>