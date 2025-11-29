<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';

// Iniciar sesión y verificar estado
Session::start();

$uid = Session::check() ? $_SESSION['uid'] : null;
$listaId = $_GET['id'] ?? null;

// Inicializar variables con valores por defecto
$nombreLista = '';
$canciones = [];
$esPublica = false;
$playlistExiste = false;

if (!$listaId) {
    header('Location: pagina_principal.php');
    exit;
}

$ctl = new PlaylistController();

// VERIFICAR SI LA PLAYLIST ES PÚBLICA O PERTENECE AL USUARIO
try {
    $esPublica = $ctl->esPlaylistPublica($listaId);

    if ($esPublica) {
        // Es una playlist pública - cualquier usuario puede verla
        $playlistExiste = true;
        $nombreLista = htmlspecialchars($listaId);
    } elseif ($uid) {
        // Verificar si es una playlist del usuario
        $playlistsUsuario = $ctl->listasDeUsuario($uid);
        foreach ($playlistsUsuario as $playlist) {
            if ($playlist['lista_id'] == $listaId) {
                $playlistExiste = true;
                $nombreLista = htmlspecialchars($listaId);
                break;
            }
        }
    }

    if (!$playlistExiste) {
        header('Location: pagina_principal.php?error=playlist_no_encontrada');
        exit;
    }

    // Obtener las canciones de la playlist
    $canciones = $ctl->cancionesDeLista($listaId);

    // DEBUG: Verificar qué se está obteniendo
    error_log("DEBUG playlist.php - Lista: $listaId");
    error_log("DEBUG playlist.php - Es pública: " . ($esPublica ? 'SÍ' : 'NO'));
    error_log("DEBUG playlist.php - Canciones obtenidas: " . count($canciones));

} catch (Exception $e) {
    error_log("Error en playlist.php: " . $e->getMessage());
    header('Location: pagina_principal.php?error=playlist_error');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de reproducción - <?= $nombreLista ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .playlist-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .playlist-header {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .playlist-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #00d4ff;
            color: #121212;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .playlist-title {
            color: #00d4ff;
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        
        .back-link {
            color: #00d4ff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .songs-table {
            width: 100%;
            border-collapse: collapse;
            background: #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .songs-table th {
            background: #2a2a2a;
            color: #00d4ff;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #00d4ff;
        }
        
        .songs-table td {
            padding: 15px;
            border-bottom: 1px solid #333;
            color: white;
        }
        
        .songs-table tr:hover {
            background: #2a2a2a;
        }
        
        .song-link {
            color: #00d4ff;
            text-decoration: none;
            font-weight: bold;
        }
        
        .song-link:hover {
            text-decoration: underline;
        }
        
        .control-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.1rem;
            margin: 0 5px;
            padding: 5px;
        }
        
        .control-btn:hover {
            color: #00d4ff;
        }
        
        .empty-playlist {
            text-align: center;
            padding: 60px 20px;
            color: #888;
            background: #1e1e1e;
            border-radius: 12px;
        }
        
        .empty-playlist a {
            color: #00d4ff;
            text-decoration: none;
        }
        
        .empty-playlist a:hover {
            text-decoration: underline;
        }
        
        .btn {
            padding: 10px 20px;
            background: #00d4ff;
            color: #121212;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #00b8e0;
        }
        
        .add-to-library {
            margin-top: 10px;
        }
        
        .add-to-library .btn {
            background: #28a745;
        }
        
        .add-to-library .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body class="bg-900">
    <!-- Header y Menú Desplegable -->
    <header class="app-header">
        <nav class="nav-left">
            <a href="pagina_principal.php" class="nav-item">Home</a>
            <a href="perfil_usuario.php" class="nav-item">Your Library</a>
        </nav>
        <div class="header-actions">
            <input type="search" placeholder="Buscar canciones o artistas..." class="search">
            <div class="header-right">
                <button class="icon-btn profile" onclick="toggleMenu()" aria-label="Menú">
                    <img src="../images/user_icon.png" alt="Foto de perfil">
                </button>
            </div>
        </div>
    </header>

    <!-- Menú desplegable -->
    <div id="dropdownMenu" class="dropdown-menu hidden">
        <ul>
            <li><a href="perfil_usuario.php">Perfil</a></li>
            <li><a href="pagina_principal.php">Inicio</a></li>
            <li><a href="ajustes_perfil.php">Ajustes</a></li>
            <li><a href="procesar_login.php?action=logout">Cerrar sesión</a></li>
        </ul>
    </div>

    <div class="playlist-container">
        <div class="playlist-header">
            <a href="pagina_principal.php" class="back-link">← Volver al inicio</a>
            <h1 class="playlist-title"><?= $nombreLista ?></h1>
            <?php if ($playlistExiste): ?>
                <div class="playlist-badge">
                    <?= $esPublica ? 'PÚBLICA' : 'TU PLAYLIST' ?>
                </div>
            <?php endif; ?>
            <p style="color: #ccc; margin: 0;"><?= count($canciones) ?> canciones</p>
            
            <?php if ($esPublica && $uid): ?>
                <div class="add-to-library">
                    <button class="btn" onclick="agregarALibreria('<?= $listaId ?>')">
                        ＋ Agregar a mi librería
                    </button>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (empty($canciones)): ?>
            <div class="empty-playlist">
                <h3>Esta playlist está vacía</h3>
                <p>¡No hay canciones en esta playlist!</p>
                <?php if (!$esPublica && $uid): ?>
                    <a href="gestionar_playlist.php?lista=<?= urlencode($listaId) ?>" class="btn" style="margin-top: 15px;">
                        Añadir canciones
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="songs-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Artista</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($canciones as $index => $cancion): 
                        // Asegurarnos de que las claves existen
                        $nombre = htmlspecialchars($cancion['nombre_cancion'] ?? 'Desconocido');
                        $creador = htmlspecialchars($cancion['nombre_creador'] ?? 'Desconocido');
                    ?>
                    <tr>
                        <td style="color: #888; width: 50px;"><?= $index + 1 ?></td>
                        <td>
                            <a href="detalles_cancion.php?cancion=<?= urlencode($nombre) ?>&creador=<?= urlencode($creador) ?>" class="song-link">
                                <?= $nombre ?>
                            </a>
                        </td>
                        <td><?= $creador ?></td>
                        <td style="text-align: center;">
                            <button class="control-btn" title="Reproducir">▶</button>
                            <button class="control-btn" title="Añadir a favoritos">❤️</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('hidden');
        }
        
        function agregarALibreria(listaId) {
            if (confirm('¿Quieres agregar esta playlist a tu librería?')) {
                // Aquí puedes implementar la lógica para agregar la playlist a la librería del usuario
                window.location.href = 'gestionar_playlist.php?action=copy&lista=' + encodeURIComponent(listaId);
            }
        }
    </script>
</body>
</html>