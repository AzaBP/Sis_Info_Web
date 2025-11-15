<?php
require_once __DIR__ . '/../src/config/Database.php';

echo "<h2> Test Conexión Base de Datos</h2>";

try {
    $db = Database::getConnection();
    echo " <strong>Conexión a BD exitosa</strong><br>";
    
    // Probar consulta simple
    $stmt = $db->query("SELECT COUNT(*) as total FROM Usuario");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo " Usuarios en BD: " . $result['total'] . "<br>";
    
} catch (Exception $e) {
    echo " <strong>Error de conexión:</strong> " . $e->getMessage() . "<br>";
    echo "Revisa: host, puerto 3307, nombre BD 'recommendador_musica_bd'<br>";
}
?>