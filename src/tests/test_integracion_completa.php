<?php
echo "<h1>🧪 PRUEBAS DE INTEGRACIÓN - PERSONA 3</h1>";
echo "<p>VMusic - Estructura corregida</p>";

require_once __DIR__ . '/../../config/Database.php';

try {
    // 1. Probar conexión
    $database = new Database();
    $conn = $database->getConnection();
    echo "<p style='color: green; font-size: 18px;'>✅ CONEXIÓN BD EXITOSA (Puerto 3307)</p>";
    
    // 2. Verificar tablas esenciales
    echo "<h2>1. Verificación de Tablas:</h2>";
    $tablas_esenciales = ['usuario', 'cancion', 'playlist', 'creador', 'oyente', 'suscripcion'];
    
    foreach ($tablas_esenciales as $tabla) {
        $query = "SHOW TABLES LIKE '$tabla'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Tabla '$tabla' existe</p>";
            
            // Contar registros en cada tabla
            $query_count = "SELECT COUNT(*) as total FROM $tabla";
            $stmt_count = $conn->prepare($query_count);
            $stmt_count->execute();
            $result_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
            echo "<p style='margin-left: 20px;'>Registros: <strong>" . $result_count['total'] . "</strong></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Tabla '$tabla' NO existe</p>";
        }
    }
    
    // 3. Verificar archivos PHP del frontend (RUTAS CORREGIDAS)
    echo "<h2>2. Archivos PHP del Frontend (rutas corregidas):</h2>";
    
    $archivos_frontend = [
        'pagina_principal.php' => 'Página Principal',
        'registro.php' => 'Registro', 
        'login.php' => 'Inicio de Sesión',  // ← CORREGIDO: login.php en lugar de inicio_sesion.php
        'playlist.php' => 'Playlists',      // ← CORREGIDO: playlist.php en lugar de playlists.php
        'perfil.php' => 'Perfil',
        'gestionar_playlist.php' => 'Gestión Playlists',
        'procesar_registro.php' => 'Procesar Registro',
        'procesar_login.php' => 'Procesar Login'
    ];
    
    foreach ($archivos_frontend as $archivo => $descripcion) {
        $ruta = __DIR__ . '/../../public/php/' . $archivo;  // ← CORREGIDO: añadido /php/
        if (file_exists($ruta)) {
            echo "<p style='color: green;'>✅ $descripcion ($archivo)</p>";
        } else {
            echo "<p style='color: red;'>❌ $descripcion ($archivo) - NO ENCONTRADO</p>";
            echo "<p style='margin-left: 20px;'>Buscado en: $ruta</p>";
        }
    }
    
    // 4. Enlaces de prueba (RUTAS CORREGIDAS)
    echo "<h2>3. Enlaces para Probar:</h2>";
    $enlaces = [
        'Página Principal' => '/recomendador_musica/public/php/pagina_principal.php',
        'Registro' => '/recomendador_musica/public/php/registro.php',
        'Inicio Sesión' => '/recomendador_musica/public/php/login.php',
        'Perfil' => '/recomendador_musica/public/php/perfil.php',
        'Playlists' => '/recomendador_musica/public/php/playlist.php'
    ];
    
    foreach ($enlaces as $nombre => $enlace) {
        echo "<p><a href='$enlace' target='_blank'>🔗 $nombre</a> - <code>$enlace</code></p>";
    }
    
    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 DIAGNÓSTICO COMPLETO</h2>";
    echo "<p><strong>Persona 3:</strong> Tu siguiente paso es probar cada enlace y reportar:</p>";
    echo "<ul>";
    echo "<li>¿Qué páginas se cargan sin errores?</li>";
    echo "<li>¿Qué páginas muestran errores PHP?</li>";
    echo "<li>¿La navegación entre páginas funciona?</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>