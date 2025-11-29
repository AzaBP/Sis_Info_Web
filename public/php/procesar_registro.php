<?php
declare(strict_types=1);

// RUTAS CORREGIDAS
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';

// 1. Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    exit('Method Not Allowed'); 
}

// 2. Iniciar sesión y verificar CSRF
Session::start();
if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { 
    http_response_code(400); 
    exit('CSRF inválido'); 
}

// 3. Capturar datos del formulario
$nombre   = $_POST['nombre']   ?? '';
$correo   = $_POST['correo']   ?? '';
$password = $_POST['password'] ?? '';
$telefono = $_POST['telefono'] ?? '';

// 4. Procesar registro
$ctl = new AuthController();
$res = $ctl->registrar($nombre, $correo, $password, $telefono, 'FREE');

// 5. Redirección basada en resultado
if ($res['ok'] ?? false) {
    //Éxito: Redirigir a perfil_usuario.php (NO perfil.php)
    header('Location: perfil_usuario.php?registered=1');
} else {
    // Error: Redirigir a registro.php con errores
    $q = http_build_query(['e' => json_encode($res['errors'] ?? ['global' => 'Error desconocido'])]);
    header("Location: registro.php?$q");
}
exit;