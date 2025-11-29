<?php
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';
require_once __DIR__ . '/../../config/Database.php';

Session::start();
$uid = $_SESSION['uid'] ?? 'no_logueado';

echo "<h1>Debug Creación Playlist</h1>";
echo "<p>Usuario: $uid</p>";

if ($_POST) {
    $listaId = $_POST['lista_id'] ?? '';
    $ctl = new PlaylistController();
    $result = $ctl->crearLista($uid, $listaId);
    
    echo "<h2>Resultado:</h2>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    
    if ($result['ok']) {
        echo "<p style='color:green'>✅ Playlist creada exitosamente</p>";
    } else {
        echo "<p style='color:red'>❌ Error creando playlist</p>";
        echo "<p>Errores: " . print_r($result['errors'] ?? [], true) . "</p>";
    }
}
?>

<form method="post">
    <input type="text" name="lista_id" placeholder="Nombre playlist" required>
    <button type="submit">Probar Creación</button>
</form>