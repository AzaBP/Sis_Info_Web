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
        // En este caso, como solo tiene lista_id, no hay mucho que actualizar
        // Pero mantenemos la interfaz por consistencia
        $sql = "UPDATE Lista SET lista_id = ? WHERE lista_id = ?";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $lista->getListaId());
            $stmt->bindValue(2, $lista->getListaId());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizando lista: " . $e->getMessage());
            return false;
        }
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
}
?>