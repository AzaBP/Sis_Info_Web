<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
Session::start();
if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }

$nombre   = $_POST['nombre']   ?? '';
$correo   = $_POST['correo']   ?? '';
$password = $_POST['password'] ?? '';
$telefono = $_POST['telefono'] ?? '';

$ctl = new AuthController();
$res = $ctl->registrar($nombre, $correo, $password, $telefono, 'FREE');

if ($res['ok'] ?? false) {
    header('Location: perfil.php?registered=1');
} else {
    $q = http_build_query(['e'=>json_encode($res['errors'] ?? ['global'=>'Error'])]);
    header("Location: registro.php?$q");
}
exit;