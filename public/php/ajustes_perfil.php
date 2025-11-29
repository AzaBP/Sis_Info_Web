<?php
declare(strict_types=1);

// 1. Inclusiones necesarias
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';

// 2. Seguridad y carga de datos
Session::requireLogin();
$uid = (string)$_SESSION['uid'];

$ctl = new UserController();
$usuarioVO = $ctl->obtenerUsuarioPorId($uid);

if (!$usuarioVO) {
    Session::logout();
    header('Location: login.php?e=user_not_found');
    exit;
}

// Extraer datos del usuario
$nombreActual = $usuarioVO->getNombre() ?? '';
$correoActual = $usuarioVO->getCorreo() ?? '';

// Intentar obtener otros campos si existen
$telefonoActual = '';
if (method_exists($usuarioVO, 'getTelefono')) {
    $telefonoActual = $usuarioVO->getTelefono() ?? '';
}

$apellidosActual = '';
if (method_exists($usuarioVO, 'getApellidos')) {
    $apellidosActual = $usuarioVO->getApellidos() ?? '';
}

$fechaNacimientoActual = '';
if (method_exists($usuarioVO, 'getFechaNacimiento')) {
    $fechaNacimientoActual = $usuarioVO->getFechaNacimiento() ?? '';
}

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Manejo de errores - MEJORADO
$errors = [];
$fieldValues = []; // Para mantener los valores enviados

if (isset($_GET['e'])) {
    $errors = json_decode(base64_decode($_GET['e']), true) ?? [];
}

if (isset($_GET['v'])) {
    $fieldValues = json_decode(base64_decode($_GET['v']), true) ?? [];
}

// Manejo de mensaje de éxito
$updated = isset($_GET['updated']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar perfil de usuario</title>
  <link rel="stylesheet" href="/recomendador_musica/public/css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: white;
      margin: 0;
      padding: 0;
    }
    .header {
      display: flex;
      align-items: center;
      padding: 16px;
    }
    .back-btn {
      background: none;
      border: none;
      cursor: pointer;
    }
    .back-btn svg {
      stroke: white;
    }
    .container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 12px;
      margin-bottom: 4px;
    }
    input {
      width: 100%;
      padding: 8px;
      border-radius: 4px;
      border: none;
      background-color: #1e1e1e;
      color: white;
    }
    .input-error {
      border: 1px solid #e53935;
    }
    .error-message {
      color: #e53935;
      font-size: 12px;
      margin-top: 4px;
    }
    .btn {
      margin-top: 20px;
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 4px;
      background-color: #00bcd4;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }
    .btn-danger {
      background-color: #e53935;
      margin-top: 10px;
    }
    .success-message {
      color: #00d4ff;
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      padding: 10px;
      background-color: rgba(0, 212, 255, 0.1);
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <header class="header">
    <button class="back-btn" onclick="location.href='perfil_usuario.php'" aria-label="Volver">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </button>
  </header>

  <div class="container">
    <h2>Editar perfil</h2>
    
    <?php if ($updated): ?>
      <div class="success-message">
        Perfil actualizado correctamente.
      </div>
    <?php endif; ?>
    
    <?php if (!empty($errors['general'])): ?>
      <div style="color: red; margin-bottom: 20px; padding: 10px; background-color: rgba(229, 57, 53, 0.1); border-radius: 4px;">
        <p><strong>Error:</strong> <?= htmlspecialchars($errors['general']) ?></p>
      </div>
    <?php endif; ?>

    <form action="actualizar_perfil.php" method="post">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      
      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" placeholder="Nombre" 
             value="<?= htmlspecialchars($fieldValues['nombre'] ?? $nombreActual) ?>"
             class="<?= isset($errors['nombre']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['nombre'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['nombre']) ?></div>
      <?php endif; ?>

      <label for="apellidos">Apellidos</label>
      <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" 
             value="<?= htmlspecialchars($fieldValues['apellidos'] ?? $apellidosActual) ?>"
             class="<?= isset($errors['apellidos']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['apellidos'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['apellidos']) ?></div>
      <?php endif; ?>

      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" placeholder="Correo electrónico" 
             value="<?= htmlspecialchars($fieldValues['email'] ?? $correoActual) ?>"
             class="<?= isset($errors['email']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['email'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['email']) ?></div>
      <?php endif; ?>

      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" name="telefono" placeholder="Teléfono" 
             value="<?= htmlspecialchars($fieldValues['telefono'] ?? $telefonoActual) ?>"
             class="<?= isset($errors['telefono']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['telefono'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['telefono']) ?></div>
      <?php endif; ?>

      <label for="fecha_nacimiento">Fecha de nacimiento</label>
      <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
             value="<?= htmlspecialchars($fieldValues['fecha_nacimiento'] ?? $fechaNacimientoActual) ?>"
             class="<?= isset($errors['fecha_nacimiento']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['fecha_nacimiento'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['fecha_nacimiento']) ?></div>
      <?php endif; ?>

      <hr style="margin: 20px 0; border-color: #333;">

      <h3 style="margin-bottom: 15px;">Cambiar contraseña</h3>
      <small style="color: #888;">Solo completa estos campos si deseas cambiar tu contraseña</small>

      <label for="password_actual">Contraseña actual</label>
      <input type="password" id="password_actual" name="password_actual" placeholder="Contraseña actual"
             class="<?= isset($errors['password_actual']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['password_actual'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['password_actual']) ?></div>
      <?php endif; ?>

      <label for="nuevo_password">Nueva contraseña</label>
      <input type="password" id="nuevo_password" name="nuevo_password" placeholder="Nueva contraseña"
             class="<?= isset($errors['nuevo_password']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['nuevo_password'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['nuevo_password']) ?></div>
      <?php endif; ?>

      <label for="confirmar_password">Confirmar nueva contraseña</label>
      <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Confirmar nueva contraseña"
             class="<?= isset($errors['confirmar_password']) ? 'input-error' : '' ?>">
      <?php if (isset($errors['confirmar_password'])): ?>
        <div class="error-message"><?= htmlspecialchars($errors['confirmar_password']) ?></div>
      <?php endif; ?>

      <button type="submit" class="btn">Guardar cambios</button>
    </form>

    <form action="eliminar_cuenta.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
    </form>
  </div>
</body>
</html>