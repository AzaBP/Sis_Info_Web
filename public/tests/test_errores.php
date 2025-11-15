<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';

echo "<h2> Test Manejo de Errores</h2>";

$auth = new AuthController();

echo "<h3>1. Test Registro con datos inválidos</h3>";
$result = $auth->registrar("A", "correo-invalido", "123", "abc");
if (!$result['ok']) {
    echo " Correcto - Rechaza datos inválidos: " . json_encode($result['errors']) . "<br>";
} else {
    echo " Error - Debería rechazar datos inválidos<br>";
}

echo "<h3>2. Test Login con credenciales incorrectas</h3>";
$result = $auth->login("noexiste@test.com", "wrongpass");
if (!$result['ok']) {
    echo " Correcto - Rechaza credenciales incorrectas<br>";
} else {
    echo " Error - Debería rechazar login incorrecto<br>";
}

echo "<h3> Tests de errores completados</h3>";
?>