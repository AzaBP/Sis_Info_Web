<?php
echo "<h1>🔧 Diagnóstico de Conexión BD - VMusic</h1>";

// 1. Verificar que el archivo Database.php existe y es legible
$database_file = __DIR__ . '/../../config/Database.php';
echo "<h2>1. Verificando archivo Database.php:</h2>";

if (file_exists($database_file)) {
    echo "<p style='color: green;'>✅ Archivo encontrado: " . $database_file . "</p>";
    
    // Verificar permisos
    if (is_readable($database_file)) {
        echo "<p style='color: green;'>✅ Archivo es legible</p>";
    } else {
        echo "<p style='color: red;'>❌ Archivo NO es legible</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Archivo NO encontrado: " . $database_file . "</p>";
    echo "<p>Buscando en: " . realpath(__DIR__ . '/../../') . "</p>";
}

// 2. Intentar cargar la clase Database
echo "<h2>2. Cargando clase Database:</h2>";
try {
    require_once $database_file;
    echo "<p style='color: green;'>✅ Clase Database cargada</p>";
    
    // 3. Crear instancia
    $database = new Database();
    echo "<p style='color: green;'>✅ Instancia de Database creada</p>";
    
    // 4. Obtener conexión
    $conn = $database->getConnection();
    
    if ($conn === null) {
        echo "<p style='color: red;'>❌ La conexión es NULL - Revisa la configuración</p>";
    } else {
        echo "<p style='color: green;'>✅ Conexión obtenida correctamente</p>";
        
        // 5. Probar consulta simple
        $query = "SELECT 1 as test";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p style='color: green;'>✅ Consulta de prueba exitosa: " . $result['test'] . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// 6. Verificar configuración de XAMPP
echo "<h2>3. Verificación XAMPP:</h2>";
echo "<p>Puerto MySQL: 3306 (por defecto)</p>";
echo "<p>Si usas otro puerto, edita config/Database.php</p>";

echo "<hr>";
echo "<h3>🎯 Solución rápida:</h3>";
echo "<p>Si la conexión sigue fallando, usa este archivo temporal:</p>";
echo "<a href='conexion_temporal.php'>Probar conexión temporal</a>";
?>