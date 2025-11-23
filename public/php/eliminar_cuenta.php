<?php
declare(strict_types=1);

// 1. Inclusiones necesarias
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';

// 2. Seguridad
Session::requireLogin();
$uid = (string)$_SESSION['uid'];
$ctl = new UserController();

// 3. Procesar eliminación si se confirma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('CSRF inválido');
    }
    
    // Eliminar usuario
    if ($ctl->eliminarUsuario($uid)) {
        Session::logout();
        header('Location: login.php?deleted=1');
        exit;
    } else {
        $error = 'Error al eliminar la cuenta.';
    }
}

// 4. Mostrar formulario de confirmación
$csrf = Session::csrfToken();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cuenta - VMusic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-900">
    <div class="main-wrap container">
        <div class="central-section">
            <div class="top-link">
                <a href="ajustes_perfil.php">← Volver a Ajustes</a>
            </div>

            <div class="login-card">
                <h2>Eliminar Cuenta</h2>
                <p>¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible y eliminará todos tus datos.</p>
                
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <button type="submit" style="background: red; color: white;">Sí, eliminar mi cuenta</button>
                </form>
                
                <p><a href="ajustes_perfil.php">Cancelar</a></p>
            </div>
        </div>
    </div>
</body>
</html>