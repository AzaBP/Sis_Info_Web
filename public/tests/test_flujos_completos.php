<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/PlaylistController.php';
require_once __DIR__ . '/../../src/lib/Session.php';
echo "<h2> Test Flujos Completos</h2>";

$auth = new AuthController();
$playlist = new PlaylistController();

// Generar datos de prueba únicos
$testEmail = "test_" . time() . "@test.com";
$testPassword = "Test123!";
$testNombre = "Usuario Test";

echo "<h3>1. Test Registro</h3>";
$result = $auth->registrar($testNombre, $testEmail, $testPassword, "123456789");
if ($result['ok']) {
    echo " Registro exitoso - Usuario: $testEmail<br>";
    $userId = $result['uid'];
} else {
    echo " Error en registro: " . json_encode($result['errors']) . "<br>";
    exit;
}

echo "<h3>2. Test Login</h3>";
$result = $auth->login($testEmail, $testPassword);
if ($result['ok']) {
    echo " Login exitoso<br>";
    
    // Verificar sesión
    Session::start();
    if (isset($_SESSION['uid'])) {
        echo " Sesión iniciada correctamente - UID: " . $_SESSION['uid'] . "<br>";
    } else {
        echo " Error: Sesión no iniciada<br>";
    }
} else {
    echo " Error en login: " . json_encode($result['errors']) . "<br>";
}

echo "<h3>3. Test Crear Playlist</h3>";
$playlistId = "test_playlist_" . time();
$result = $playlist->crearLista($userId, $playlistId);
if ($result['ok']) {
    echo " Playlist creada: $playlistId<br>";
} else {
    echo " Error creando playlist: " . json_encode($result['errors']) . "<br>";
}

echo "<h3>4. Test Añadir Canción</h3>";
$result = $playlist->agregarCancion($playlistId, "Canción Test", "creador_test");
if ($result['ok']) {
    echo " Canción añadida a playlist<br>";
} else {
    echo "Error añadiendo canción: " . json_encode($result['errors']) . "<br>";
}

echo "<h3> Todos los tests completados</h3>";
?>