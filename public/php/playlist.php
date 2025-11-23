<?php
declare(strict_types=1);

// 1. Inclusiones necesarias
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';

// 2. Seguridad y carga de datos
Session::requireLogin(); // La gestión de listas requiere autenticación.
$uid = Session::check() ? $_SESSION['uid'] : null;

$listaId = $_GET['id'] ?? null; // Obtener el ID de la lista desde la URL

if (!$listaId) {
    // Si no se especifica una lista, redirigir al perfil o a la gestión de playlists
    header('Location: perfil_usuario.php');
    exit;
}

$ctl = new PlaylistController();

$canciones = $ctl->cancionesDeLista($listaId); 

// Si necesitamos el nombre de la playlist, la lista en sí se obtiene del ID, 
// o se asume que listaId es el nombre visible.
$nombreLista = htmlspecialchars($listaId); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de reproducción - <?= $nombreLista ?></title>
    <!-- Incluir CSS de VMusic para estilos -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-900">
    <!-- Header y Menú Desplegable -->
    <header class="app-header">
        <div class="header-right">
            <button class="icon-btn profile" onclick="toggleMenu()" aria-label="Menú">
                <img src="../images/user_icon.png" alt="Foto de perfil">
            </button>
            <div id="dropdownMenu" class="dropdown-menu hidden">
                <ul>
                    <li><a href="perfil_usuario.php">Perfil</a></li>
                    <li><a href="pagina_principal.php">Inicio</a></li>
                    <li><a href="ajustes_perfil.php">Ajustes</a></li>
                    <li><a href="procesar_login.php?action=logout">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="main-wrap container">
        <div class="central-section">
            <div class="top-link">
                <a href="perfil_usuario.php">← Volver a tu biblioteca</a>
            </div>

            <h1 style="text-align: left;"><?= $nombreLista ?></h1>
            
            <?php if (empty($canciones)): ?>
                <p>Esta lista de reproducción está vacía. <a href="gestionar_playlist.php?lista=<?= urlencode($listaId) ?>">Añadir canciones</a>.</p>
            <?php else: ?>
                <table class="playlist-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <th style="padding: 10px; text-align: left;">Nombre</th>
                            <th style="padding: 10px; text-align: left;">Artista</th>
                            <th style="padding: 10px; text-align: left;">Duración</th>
                            <th style="padding: 10px; text-align: center;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($canciones as $cancion): 
                            $nombre = htmlspecialchars($cancion['nombre_cancion']);
                            $creador = htmlspecialchars($cancion['nombre_creador']);
                            $duracion = htmlspecialchars($cancion['duracion'] ?? 'N/A');
                        ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 10px;">
                                <a href="detalles_cancion.php?cancion=<?= urlencode($nombre) ?>&creador=<?= urlencode($creador) ?>" style="color: var(--neon-cyan); text-decoration: none;">
                                    <?= $nombre ?>
                                </a>
                            </td>
                            <td style="padding: 10px;"><?= $creador ?></td>
                            <td style="padding: 10px;"><?= $duracion ?></td>
                            <td style="padding: 10px; text-align: center;">
                                <!-- Iconos de opciones (Play/Favorito) -->
                                ▶ | ❤️ 
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <section class="player-controls">
        <div class="progress-row">
            <button id="playBtn" class="control-btn">▶</button>
            <div class="time">0:00</div>
            <input id="progress" type="range" min="0" max="100" value="0">
            <div class="time total">3:30</div>
        </div>
    </section>

    <script src="../js/menu_desplegable.js"></script>
</body>
</html>