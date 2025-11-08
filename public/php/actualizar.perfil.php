<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
Session::requireLogin();
if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }

$uid      = (string)$_SESSION['uid'];
$nombre   = $_POST['nombre']   ?? '';
$telefono = $_POST['telefono'] ?? '';

$ctl = new UserController();
$res = $ctl->actualizarPerfil($uid, $nombre, $telefono);

if ($res['ok'] ?? false) {
    header('Location: perfil.php?updated=1');
} else {
    $q = http_build_query(['e'=>json_encode($res['errors'] ?? ['global'=>'Error'])]);
    header("Location: perfil.php?$q");
}
exit;
