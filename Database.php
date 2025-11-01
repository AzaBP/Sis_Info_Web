<?php
class Database {
    private $host = "localhost";
    private $db_name = "recomendador_musica_bd";  // Ajustar si se cambia el nombre de la BD
    private $username = "root";
    private $password = "";
    private $conn;

    // Obtener conexion PDO a la BD
    public function getConnection() {
        $this->conn = null;
        try {
            // Crear nueva conexion PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            // Activar el modo de errores para lanzar excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        // Devolver la conexion (null si fallo)
        return $this->conn;
    }
}
?>