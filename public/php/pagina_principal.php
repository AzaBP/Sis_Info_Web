<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php'; 
require_once __DIR__ . '/../../src/controllers/PlaylistController.php'; 

// 1. Iniciar sesión y obtener estado del usuario
Session::start();
// Obtener UID (será null si el usuario no está logueado, habilitando el Modo Invitado)
$uid = Session::check() ? $_SESSION['uid'] : null;

// Obtener email si el usuario está logueado
$userEmail = '';
if ($uid) {
    $userController = new UserController();
    $usuarioVO = $userController->obtenerUsuarioPorId($uid);
    $userEmail = $usuarioVO ? $usuarioVO->getCorreo() : '';
}

// 2. Cargar datos dinámicos
$playlistController = new PlaylistController();

$playlistsUsuario = [];
if ($uid) {
    // Si hay un usuario logueado, obtenemos sus listas para la sección "Your Library"
    $playlistsUsuario = $playlistController->listasDeUsuario($uid);
}

// 3. Contenido del catálogo (Datos simulados) - RUTAS CORREGIDAS
$albumsRecomendados = [
    ['titulo' => 'Acoustic Breeze', 'imagen' => '/imagenes/ballads.jpg'],
    ['titulo' => 'Evening Sky', 'imagen' => '/imagenes/ACDC.jpg'],
    ['titulo' => 'Midnight Vibes', 'imagen' => '/imagenes/nectar.jpg'],
    ['titulo' => 'Smooth Jazz', 'imagen' => '/imagenes/trench.jpg']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>VMusic - Principal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-900">
    <header class="app-header">
        <nav class="nav-left">
            <a href="#" class="nav-item active">Home</a>
            <a href="perfil_usuario.php" class="nav-item">Your Library</a>
        </nav>
        
        <div class="header-actions">
            <input type="search" placeholder="Buscar canciones o artistas..." class="search">
            <?php if ($uid): ?>
                <a href="perfil_usuario.php" class="icon small">Perfil</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="main-wrap">
        <h1 class="page-title">Bienvenido, <?= $uid ? htmlspecialchars($userEmail) : 'Invitado' ?></h1>

        <!-- Sección 1: Recomendaciones -->
        <section>
            <h2 class="section-title">Recommended Albums</h2>
            <div class="albums-grid">
                <?php foreach ($albumsRecomendados as $album): ?>
                    <a href="detalles_cancion.php?cancion=<?= urlencode($album['titulo']) ?>&creador=Artista" class="album-card">
                        <!-- RUTA ABSOLUTA CORREGIDA -->
                        <img src="<?= htmlspecialchars($album['imagen']) ?>" alt="<?= htmlspecialchars($album['titulo']) ?>" class="album-cover">
                        <div class="album-title"><?= htmlspecialchars($album['titulo']) ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if ($uid): ?>
        <!-- Sección 2: Your library -->
        <section>
            <h2 class="section-title">Your Library (Playlists)</h2>
            <div class="playlists-row">
                <?php if (empty($playlistsUsuario)): ?>
                    <p class="pl-caption" style="color: grey;">No hay playlists disponibles. <a href="gestionar_playlist.php">Crear una.</a></p>
                <?php else: ?>
                    <?php foreach ($playlistsUsuario as $lista): ?>
                        <a href="playlist.php?id=<?= urlencode($lista['lista_id']) ?>" class="pl-card">
                            <span class="pl-art">♪</span>
                            <span class="pl-caption"><?= htmlspecialchars($lista['lista_id']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</body>
</html>