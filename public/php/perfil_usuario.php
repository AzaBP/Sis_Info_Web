<?php
declare(strict_types=1);

// 1. Incluir clases esenciales
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/SessionController.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';
require_once __DIR__ . '/../../config/Database.php'; // Necesario para instanciar DAOs

// 2. Requerir autenticación
// Si el usuario no está logueado, lo redirige automáticamente a login.php
Session::requireLogin(); 
$uid = (string)$_SESSION['uid']; // Obtenemos el ID del usuario logueado

// 3. Inicializar controladores
$database = new Database();
$dbConnection = $database->getConnection();

$userController = new UserController(); 
$playlistController = new PlaylistController(); 

// 4. Carga de datos del usuario
$usuarioVO = $userController->obtenerUsuarioPorId($uid); 

// Manejo de sesión corrupta: si el UID existe pero el usuario no se encuentra en BD 
if (!$usuarioVO) {
    Session::logout();
    header('Location: login.php?e=user_not_found');
    exit;
}

// Convertir el VO en un array para facilitar la inyección en HTML
$usuario = [
    'nombre' => $usuarioVO->getNombre(),
    'correo' => $usuarioVO->getCorreo(),
    // otros campos relevantes para la vista...
];

// 5. Carga de listas y biblioteca

// ListaDAOImpl devuelve un array asociativo con 'lista_id'
$playlistsUsuario = $playlistController->listasDeUsuario($uid);

// Si se recibe un mensaje de actualización (ej. después de actualizar_perfil.php)
$updateMessage = $_GET['updated'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario - VMusic</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-900">
    <div class="main-wrap container">
        <div class="central-section">
            <div class="top-link">
                <a href="pagina_principal.php">← Volver a Inicio</a>
            </div>

            <!-- Estructura del Perfil -->
            <div class="profile-card">
                <img src="../images/user_icon.png" alt="Foto de perfil del usuario" class="profile-pic">
                
                <h1><?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?></h1>
                
                <?php if ($updateMessage): ?>
                    <p style="color: #00d4ff; text-align: center; font-weight: bold;">Perfil actualizado correctamente.</p>
                <?php endif; ?>

                <!-- Botón para modificar el perfil, apunta a la vista de edición -->
                <a href="ajustes_perfil.php" class="btn btn-login">
                    Editar perfil
                </a>
                
                <!-- Sección Tu biblioteca -->
                <section class="section-title">Tu biblioteca</section>
                <div class="playlists-row">
                    <?php if (empty($playlistsUsuario)): ?>
                        <!-- Si no hay listas creadas -->
                        <p class="pl-caption">Aún no has creado ninguna lista.</p>
                    <?php else: ?>
                        <?php foreach ($playlistsUsuario as $lista): ?>
                            <!-- Iterar sobre las listas del usuario -->
                            <a href="playlist.php?id=<?= urlencode($lista['lista_id']) ?>" class="pl-card">
                                <span class="pl-art">♪</span>
                                <span class="pl-caption"><?= htmlspecialchars($lista['lista_id']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">

                <!-- Opción de Cerrar Sesión -->
                <p class="guest-link" style="text-align: center;">
                    <a href="procesar_login.php?action=logout">Cerrar Sesión</a> 
                </p>

            </div>
        </div>
    </div>
</body>
</html>