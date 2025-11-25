<?php
echo "<h1>🎵 VMusic - Verificación Inicial</h1>";
echo "<p>Estructura correcta detectada</p>";

// Verificar que podemos acceder a los archivos importantes
$archivos = [
    'config/Database.php',
    'src/dao/UsuarioDAO.php',
    'src/vo/UsuarioVO.php',
    'public/interfaz/pagina_principal.html'
];

echo "<h2>Archivos esenciales:</h2>";
foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "<p style='color: green;'>✅ $archivo</p>";
    } else {
        echo "<p style='color: red;'>❌ $archivo - NO encontrado</p>";
    }
}

// Probar conexión a BD
echo "<h2>Probando conexión a BD:</h2>";
try {
    require_once 'config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
    
    // Contar usuarios
    $query = "SELECT COUNT(*) as total FROM usuario";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Usuarios en BD: " . $result['total'] . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error BD: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>🎯 Próximos pasos:</h3>";
echo "<p>1. Crear sistema de plantillas en public/includes/</p>";
echo "<p>2. Convertir HTML a PHP en public/interfaz/</p>";
echo "<p>3. Integrar con los DAO</p>";
?>