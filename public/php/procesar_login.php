<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

Session::start();

if (($_GET['action'] ?? '') === 'logout') {
    Session::logout();
    header('Location: login.php?bye=1'); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }

$correo = $_POST['correo'] ?? '';
$pass   = $_POST['password'] ?? '';

$ctl = new AuthController();
$res = $ctl->login($correo, $pass);

if ($res['ok'] ?? false) {
    header('Location: perfil.php');
} else {
    $q = http_build_query(['e'=>json_encode($res['errors'] ?? ['global'=>'Error'])]);
    header("Location: login.php?$q");
}
exit;
