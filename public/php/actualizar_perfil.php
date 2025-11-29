<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';

Session::requireLogin();
$uid = (string)$_SESSION['uid'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajustes_perfil.php');
    exit;
}

// VALIDACIÓN CSRF
if (!isset($_POST['csrf']) || !isset($_SESSION['csrf_token']) || 
    empty($_SESSION['csrf_token']) || $_POST['csrf'] !== $_SESSION['csrf_token']) {
    
    $errors = ['general' => 'Token de seguridad inválido. Por favor, recarga la página e intenta nuevamente.'];
    $errorQuery = base64_encode(json_encode($errors));
    header('Location: ajustes_perfil.php?e=' . $errorQuery);
    exit;
}

$ctl = new UserController();
$errors = [];
$fieldValues = [];

// Recoger y limpiar los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$password_actual = $_POST['password_actual'] ?? '';
$nuevo_password = $_POST['nuevo_password'] ?? '';
$confirmar_password = $_POST['confirmar_password'] ?? '';

// Guardar valores para repoblar el formulario
$fieldValues = [
    'nombre' => $nombre,
    'apellidos' => $apellidos,
    'email' => $email,
    'telefono' => $telefono,
    'fecha_nacimiento' => $fecha_nacimiento
];

// VALIDACIONES BÁSICAS
if (empty($nombre)) {
    $errors['nombre'] = 'El nombre es obligatorio';
} elseif (strlen($nombre) < 2) {
    $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres';
}

if (empty($email)) {
    $errors['email'] = 'El correo electrónico es obligatorio';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'El formato del correo electrónico no es válido';
}

// Validar teléfono si se proporciona
if (!empty($telefono) && !preg_match('/^[0-9\s\-\+\(\)]{7,15}$/', $telefono)) {
    $errors['telefono'] = 'El teléfono debe tener entre 7 y 15 dígitos';
}

// Validar contraseñas si se proporcionaron
$cambiarPassword = !empty($nuevo_password) || !empty($confirmar_password);

if ($cambiarPassword) {
    if (empty($password_actual)) {
        $errors['password_actual'] = 'Debes ingresar tu contraseña actual para cambiarla';
    }
    
    if (empty($nuevo_password)) {
        $errors['nuevo_password'] = 'La nueva contraseña es obligatoria';
    } elseif (strlen($nuevo_password) < 6) {
        $errors['nuevo_password'] = 'La contraseña debe tener al menos 6 caracteres';
    } elseif ($nuevo_password !== $confirmar_password) {
        $errors['confirmar_password'] = 'Las contraseñas no coinciden';
    }
}

// Si hay errores, redirigir mostrándolos
if (!empty($errors)) {
    $errorQuery = base64_encode(json_encode($errors));
    $valuesQuery = base64_encode(json_encode($fieldValues));
    header('Location: ajustes_perfil.php?e=' . $errorQuery . '&v=' . $valuesQuery);
    exit;
}

try {
    // ACTUALIZAR PERFIL usando el método existente de UserController
    // Tu método actualizarPerfil solo acepta nombre y teléfono
    $resultado = $ctl->actualizarPerfil($uid, $nombre, $telefono);
    
    if ($resultado['ok']) {
        header('Location: ajustes_perfil.php?updated=1');
        exit;
    } else {
        // Si el controlador devuelve errores, mostrarlos
        if (isset($resultado['errors'])) {
            $errors = array_merge($errors, $resultado['errors']);
        } else {
            $errors['general'] = 'Error al actualizar el perfil';
        }
        
        $errorQuery = base64_encode(json_encode($errors));
        $valuesQuery = base64_encode(json_encode($fieldValues));
        header('Location: ajustes_perfil.php?e=' . $errorQuery . '&v=' . $valuesQuery);
        exit;
    }
    
} catch (Exception $e) {
    $errors['general'] = 'Error del sistema: ' . $e->getMessage();
    $errorQuery = base64_encode(json_encode($errors));
    $valuesQuery = base64_encode(json_encode($fieldValues));
    header('Location: ajustes_perfil.php?e=' . $errorQuery . '&v=' . $valuesQuery);
    exit;
}