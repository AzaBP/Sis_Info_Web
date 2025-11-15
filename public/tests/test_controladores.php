<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/PlaylistController.php';

echo "<h2> Test Controladores</h2>";

// Test AuthController
$auth = new AuthController();
echo " AuthController instanciado correctamente<br>";

// Test UserController  
$userCtrl = new UserController();
echo " UserController instanciado correctamente<br>";

// Test PlaylistController
$playlistCtrl = new PlaylistController();
echo "PlaylistController instanciado correctamente<br>";

echo " Todos los controladores funcionan<br>";
?>