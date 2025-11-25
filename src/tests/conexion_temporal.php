<?php
echo "<h1>🔧 Conexión Temporal BD - VMusic</h1>";

// Configuración directa sin usar Database.php
$host = "localhost";
$db_name = "recomendador_musica_bd";
$username = "root";
$password = "";
$port = "3306";

echo "<h2>Intentando conectar...</h2>";
echo "<p>Host: $host</p>";
echo "<p>BD: $db_name</p>";
echo "<p>Usuario: $username</p>";
echo "<p>Puerto: $port</p>";

try {
    // Intentar con puerto 3306
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green; font-size: 20px;'>✅ CONEXIÓN EXITOSA con puerto 3306</p>";
    
    // Probar consultas
    $query = "SHOW TABLES";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tablas en la base de datos:</h3>";
    foreach ($tablas as $tabla) {
        echo "<p style='color: green;'>✅ $tabla</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error con puerto 3306: " . $e->getMessage() . "</p>";
    
    // Intentar con puerto 3307
    try {
        $conn = new PDO("mysql:host=$host;port=3307;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "<p style='color: green; font-size: 20px;'>✅ CONEXIÓN EXITOSA con puerto 3307</p>";
        
        // Probar consultas
        $query = "SHOW TABLES";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h3>Tablas en la base de datos:</h3>";
        foreach ($tablas as $tabla) {
            echo "<p style='color: green;'>✅ $tabla</p>";
        }
        
    } catch (PDOException $e2) {
        echo "<p style='color: red;'>❌ Error con puerto 3307: " . $e2->getMessage() . "</p>";
        echo "<h3>🔍 Soluciones:</h3>";
        echo "<p>1. Verifica que MySQL esté ejecutándose en XAMPP</p>";
        echo "<p>2. Revisa el puerto en XAMPP → MySQL → Config → my.ini</p>";
        echo "<p>3. Verifica que la BD 'recomendador_musica_bd' exista en phpMyAdmin</p>";
    }
}

echo "<hr>";
echo "<h3>📊 Si la conexión temporal funciona:</h3>";
echo "<p>Actualiza config/Database.php con la configuración correcta</p>";
?>