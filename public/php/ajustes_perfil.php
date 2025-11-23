<?php
declare(strict_types=1);

// 1. Inclusiones necesarias
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';

// 2. Seguridad y carga de datos
Session::requireLogin(); // Obliga al usuario a estar autenticado
$uid = (string)$_SESSION['uid']; // ID del usuario logueado

$ctl = new UserController(); // Instancia el controlador

// Obtener el objeto VO del usuario para precargar los datos
$usuarioVO = $ctl->obtenerUsuarioPorId($uid); 

// Si el usuario no existe
if (!$usuarioVO) {
    Session::logout();
    header('Location: login.php?e=user_not_found');
    exit;
}

// Extraer datos actuales
$nombreActual = $usuarioVO->getNombre() ?? '';
$telefonoActual = $usuarioVO->getTelefono() ?? '';
$correoActual = $usuarioVO->getCorreo() ?? '';

// Generar token CSRF para el formulario
$csrf = Session::csrfToken(); 

// Manejo de errores de validación de la sesión anterior (si actualizar_perfil.php falló)
$errors = [];
if (isset($_GET['e'])) {
    // Asume que los errores vienen codificados en JSON (como en procesar_login.php)
    $errors = json_decode($_GET['e'], true) ?? [];
}

// Manejo de mensaje de éxito
$updated = isset($_GET['updated']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - VMusic</title>
    <!-- Se asume que se incluyen aquí los archivos CSS necesarios -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-900">
    <div class="main-wrap container">
        <div class="central-section">
            <div class="top-link">
                <!-- Enlace para volver al perfil principal -->
                <a href="perfil_usuario.php">← Volver al Perfil</a>
            </div>

            <!-- Estructura del formulario de edición -->
            <div class="login-card"> 
                <h2>Editar perfil</h2>
                
                <?php if ($updated): ?>
                    <p style="color: #00d4ff; text-align: center; font-weight: bold;">Perfil actualizado correctamente.</p>
                <?php endif; ?>
                
                <?php if ($errors): ?>
                    <!-- Mostrar errores de validación si existen -->
                    <div style="color: red; margin-bottom: 1rem;">
                        <p>Por favor, corrige los siguientes errores:</p>
                        <ul>
                            <?php foreach ($errors as $field => $msg): ?>
                                <li><?= htmlspecialchars($field) ?>: <?= htmlspecialchars($msg) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- El formulario envía los datos a actualizar_perfil.php para su procesamiento -->
                <form method="post" action="actualizar_perfil.php"> 
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    
                    <!-- Campo de Correo (solo lectura, es el ID de sesión) -->
                    <div class="login-field">
                        <label>Correo electrónico (No modificable)</label>
                        <input name="correo" type="email" value="<?= htmlspecialchars($correoActual) ?>" readonly> 
                    </div>

                    <!-- Campo Nombre (precargado con el valor actual) -->
                    <div class="login-field">
                        <label>Nombre</label>
                        <input name="nombre" type="text" value="<?= htmlspecialchars($nombreActual) ?>" required>
                    </div>
                    
                    <!-- Campo Teléfono (precargado con el valor actual) -->
                    <div class="login-field">
                        <label>Teléfono</label>
                        <input name="telefono" type="text" value="<?= htmlspecialchars($telefonoActual) ?>" required>
                    </div>
                    
                    <div class="login-actions">
                        <button class="btn btn-login">Guardar cambios</button>
                    </div>
                </form>

                <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">

                <!-- Opción de eliminar cuenta -->
                <p style="text-align: center;">
                    <a href="eliminar_cuenta.php" style="color: red;">Eliminar cuenta</a>
                </p>

            </div>
        </div>
    </div>
</body>
</html>