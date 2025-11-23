<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

// 1. Verificar el método de la petición (Solo POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    exit('Method Not Allowed'); 
}

// 2. Seguridad: Autenticación y CSRF
Session::requireLogin(); // Obliga al inicio de sesión

if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { // Verificación de token CSRF 
    http_response_code(400); 
    exit('CSRF inválido'); 
}

// 3. Capturar datos y UID
$uid = (string)$_SESSION['uid']; // Obtener ID del usuario logueado 
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';

// 4. Llama al controlador
$ctl = new UserController();
$res = $ctl->actualizarPerfil($uid, $nombre, $telefono);

// 5. Redirección basada en el resultado
if ($res['ok'] ?? false) {
    // Éxito: Redirigir al perfil con mensaje de éxito
    header('Location: ajustes_perfil.php?updated=1'); 
} else {
    // Error: Redirigir a la página de edición, pasando los errores
    $q = http_build_query(['e'=>json_encode($res['errors'] ?? ['global'=>'Error'])]);
    header("Location: ajustes_perfil.php?$q");
}
exit;
