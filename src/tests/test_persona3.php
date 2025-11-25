<?php
echo "<h1>🧪 Pruebas Persona 3 - VMusic</h1>";
echo "<p>Proyecto en: recomendador_musica</p>";

// 1. Verificar que podemos incluir archivos
echo "<h2>1. Verificando archivos esenciales:</h2>";

$archivos = [
    __DIR__ . '/../../config/Database.php',
    __DIR__ . '/../dao/UsuarioDAO.php',
    __DIR__ . '/../vo/UsuarioVO.php'
];

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "<p style='color: green;'>✅ " . basename($archivo) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ " . basename($archivo) . " - NO ENCONTRADO</p>";
        echo "<p>Buscando en: " . $archivo . "</p>";
    }
}

// 2. Probar conexión a BD (solo si Database.php existe)
if (file_exists(__DIR__ . '/../../config/Database.php')) {
    echo "<h2>2. Probando conexión a BD:</h2>";
    try {
        require_once __DIR__ . '/../../config/Database.php';
        $database = new Database();
        $conn = $database->getConnection();
        echo "<p style='color: green;'>✅ Conexión a BD EXITOSA</p>";
        
        // Contar usuarios
        $query = "SELECT COUNT(*) as total FROM usuario";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Usuarios en BD: <strong>" . $result['total'] . "</strong></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error BD: " . $e->getMessage() . "</p>";
    }
}

// 3. Verificar archivos PHP del frontend
echo "<h2>3. Archivos PHP del frontend:</h2>";
$archivos_php = [
    __DIR__ . '/../../public/interfaz/php/pagina_principal.php',
    __DIR__ . '/../../public/interfaz/php/registro.php',
    __DIR__ . '/../../public/interfaz/php/inicio_sesion.php'
];

foreach ($archivos_php as $archivo) {
    if (file_exists($archivo)) {
        echo "<p style='color: green;'>✅ " . basename($archivo) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ " . basename($archivo) . " - NO ENCONTRADO</p>";
    }
}

echo "<hr>";
echo "<h2>🎯 Enlaces para probar:</h2>";
echo "<a href='/recomendador_musica/public/interfaz/php/pagina_principal.php' target='_blank'>Página Principal</a><br>";
echo "<a href='/recomendador_musica/public/interfaz/php/registro.php' target='_blank'>Registro</a><br>";
echo "<a href='/recomendador_musica/public/interfaz/php/inicio_sesion.php' target='_blank'>Inicio Sesión</a>";
?>