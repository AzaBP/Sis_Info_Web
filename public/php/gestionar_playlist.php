<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';
require_once __DIR__ . '/../../src/dao/CancionDAO.php';
require_once __DIR__ . '/../../src/dao/CancionDAOImpl.php';
require_once __DIR__ . '/../../config/Database.php';

Session::requireLogin();
$uid = (string)$_SESSION['uid'];
$ctl = new PlaylistController();

$database = new Database();
$cancionDAO = new CancionDAOImpl($database->getConnection());
$todasLasCanciones = $cancionDAO->obtenerTodasLasCanciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' && isset($_POST['lista_id'], $_POST['nombre_cancion'], $_POST['nombre_creador'])) {
        if (!Session::verifyCsrf($_POST['csrf'])) {
            header('Location: gestionar_playlist.php?e=csrf');
            exit;
        }
        
        $result = $ctl->agregarCancion(
            $_POST['lista_id'],
            $_POST['nombre_cancion'], 
            $_POST['nombre_creador'],
            $uid
        );
        
        if ($result['ok']) {
            header('Location: gestionar_playlist.php?ok=add&lista=' . urlencode($_POST['lista_id']));
        } else {
            header('Location: gestionar_playlist.php?e=add&lista=' . urlencode($_POST['lista_id']));
        }
        exit;
    }
    
    // procesar la creación de playlists
    if ($action === 'create' && isset($_POST['lista_id'])) {
        if (!Session::verifyCsrf($_POST['csrf'])) {
            header('Location: gestionar_playlist.php?e=csrf');
            exit;
        }
        
        $result = $ctl->crearLista($uid, $_POST['lista_id']);
        
        if ($result['ok']) {
            header('Location: gestionar_playlist.php?ok=create&lista=' . urlencode($_POST['lista_id']));
        } else {
            header('Location: gestionar_playlist.php?e=create');
        }
        exit;
    }
    
    // Procesar eliminación de canciones
    if ($action === 'del' && isset($_POST['lista_id'], $_POST['nombre_cancion'], $_POST['nombre_creador'])) {
        if (!Session::verifyCsrf($_POST['csrf'])) {
            header('Location: gestionar_playlist.php?e=csrf');
            exit;
        }
        
        $result = $ctl->eliminarCancion(
            $_POST['lista_id'],
            $_POST['nombre_cancion'],
            $_POST['nombre_creador']
        );
        
        if ($result['ok']) {
            header('Location: gestionar_playlist.php?ok=del&lista=' . urlencode($_POST['lista_id']));
        } else {
            header('Location: gestionar_playlist.php?e=del&lista=' . urlencode($_POST['lista_id']));
        }
        exit;
    }
    
    // Procesar eliminación de playlist completa
    if ($action === 'delete_playlist' && isset($_POST['lista_id'])) {
        if (!Session::verifyCsrf($_POST['csrf'])) {
            header('Location: gestionar_playlist.php?e=csrf');
            exit;
        }
        
        $result = $ctl->eliminarPlaylist($_POST['lista_id'], $uid);
        
        if ($result['ok']) {
            header('Location: gestionar_playlist.php?ok=delete_playlist');
        } else {
            header('Location: gestionar_playlist.php?e=delete_playlist&lista=' . urlencode($_POST['lista_id']));
        }
        exit;
    }
}

// CARGAR DATOS PARA MOSTRAR (solo para GET)
$csrf = Session::csrfToken();
$listas = $ctl->listasDeUsuario($uid);

// Determinar qué playlist mostrar
// 1. Si viene de playlist.php con parámetro 'lista', usar esa
// 2. Si viene con parámetro 'add', también usar esa
// 3. Si no, usar la primera de la lista o ninguna
$listaSel = null;

// Primero verificar si viene de playlist.php
if (isset($_GET['lista'])) {
    $listaParam = $_GET['lista'];
    // Verificar que la playlist pertenece al usuario
    foreach ($listas as $lista) {
        if ($lista['lista_id'] == $listaParam) {
            $listaSel = $listaParam;
            break;
        }
    }
}

// Si no se encontró una lista válida del usuario, usar la primera de la lista
if (!$listaSel && !empty($listas)) {
    $listaSel = $listas[0]['lista_id'];
}

$canciones = $listaSel ? $ctl->cancionesDeLista($listaSel) : [];

// Verificar si viene para añadir canciones (para scroll automático)
$addMode = isset($_GET['add']) && $_GET['add'] === 'true';

// Mensajes
$mensaje = '';
$tipoMensaje = '';
if (isset($_GET['ok'])) {
    $mensajes = [
        'create' => 'Playlist creada correctamente',
        'add' => 'Canción añadida correctamente',
        'del' => 'Canción eliminada correctamente',
        'delete_playlist' => 'Playlist eliminada correctamente'
    ];
    $mensaje = $mensajes[$_GET['ok']] ?? 'Operación realizada con éxito';
    $tipoMensaje = 'success';
}
if (isset($_GET['e'])) {
    $errores = [
        'create' => 'Error: No se pudo crear la playlist. Puede que ya exista o el nombre no sea válido.',
        'add' => 'Error: No se pudo añadir la canción. Verifica que la canción y artista existan.',
        'del' => 'Error: No se pudo eliminar la canción.',
        'delete_playlist' => 'Error: No se pudo eliminar la playlist.',
        'csrf' => 'Error de seguridad: Token CSRF inválido.'
    ];
    $mensaje = $errores[$_GET['e']] ?? 'Error en la operación';
    $tipoMensaje = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Playlists - VMusic</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .main-wrap { max-width: 900px; margin: 0 auto; padding: 20px; }
        .central-section { background: #1e1e1e; padding: 30px; border-radius: 12px; }
        .section { margin: 25px 0; padding: 20px; background: #2a2a2a; border-radius: 8px; }
        .section h2 { color: #00d4ff; margin-top: 0; }
        .section h3 { color: #ffffff; margin-top: 0; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 8px; color: #ccc; font-weight: bold; }
        input, select { 
            padding: 10px; 
            margin: 5px 0; 
            width: 100%; 
            max-width: 300px;
            background: #333;
            border: 1px solid #444;
            border-radius: 6px;
            color: white;
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
        .btn:hover { background: #00b8e0; }
        .btn-danger { 
            background: #ff4444; 
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        .btn-danger:hover { background: #cc3333; }
        .playlist-nav { margin: 20px 0; }
        .playlist-nav a { 
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px 5px 0;
            background: #333;
            color: #00d4ff;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .playlist-nav a:hover {
            background: #3a3a3a;
            text-decoration: none;
        }
        .playlist-nav a.active { 
            background: #00d4ff;
            color: #121212;
            font-weight: bold;
        }
        .song-list { list-style: none; padding: 0; }
        .song-item { 
            padding: 12px; 
            margin: 8px 0; 
            background: #333; 
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .message { 
            padding: 12px; 
            margin: 15px 0; 
            border-radius: 6px;
            font-weight: bold;
        }
        .message.success { background: #00d4ff; color: #121212; }
        .message.error { background: #ff4444; color: white; }
        .back-link { 
            display: inline-block;
            margin-bottom: 20px;
            color: #00d4ff;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .debug-info {
            background: #333;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #ccc;
        }
        
        .playlist-item {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        
        .delete-playlist-btn {
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
            position: absolute;
            top: -5px;
            right: -5px;
            display: none;
        }
        
        .playlist-item:hover .delete-playlist-btn {
            display: block;
        }
        
        .confirm-delete {
            background: #ff4444;
            padding: 10px 20px;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            border: none;
        }
        
        .confirm-delete:hover {
            background: #cc3333;
        }
        
        .small-x {
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1;
        }
        
        .playlist-with-delete {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 0 5px 5px 0;
        }
        
        .back-to-playlist {
            display: inline-block;
            margin-left: 10px;
            color: #00d4ff;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .back-to-playlist:hover {
            background: rgba(0, 212, 255, 0.2);
            text-decoration: none;
            color: #00d4ff;
        }
        
        .highlight-section {
            border: 2px solid #00d4ff;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { border-color: #00d4ff; }
            50% { border-color: #00b8e0; }
            100% { border-color: #00d4ff; }
        }
    </style>
</head>
<body class="bg-900">
    <div class="main-wrap container">
        <div class="central-section">
            <a href="perfil_usuario.php" class="back-link">← Volver al perfil</a>
            <?php if ($listaSel): ?>
                <a href="playlist.php?id=<?= urlencode($listaSel) ?>" class="back-to-playlist">
                    ← Volver a la playlist
                </a>
            <?php endif; ?>
            <h1>Gestión de Playlists</h1>

            <?php if ($mensaje): ?>
                <div class="message <?= $tipoMensaje === 'success' ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <!-- FORMULARIO CREAR PLAYLIST -->
            <section class="section">
                <h2>Crear Nueva Playlist</h2>
                <form method="post" action="gestionar_playlist.php">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="lista_id">Nombre de la playlist:</label>
                        <input type="text" id="lista_id" name="lista_id" placeholder="Ej: Mis Favoritas" required>
                    </div>
                    <button type="submit" class="btn">Crear Playlist</button>
                </form>
            </section>

            <!-- LISTA DE PLAYLISTS -->
            <section class="section">
                <h2>Tus Playlists</h2>
                <?php if (empty($listas)): ?>
                    <p>Aún no has creado ninguna playlist.</p>
                <?php else: ?>
                    <div class="playlist-nav">
                        <?php foreach ($listas as $lista): ?>
                            <?php 
                                $id = htmlspecialchars($lista['lista_id']);
                                $active = ($listaSel === $lista['lista_id']) ? 'active' : '';
                            ?>
                            <div class="playlist-with-delete">
                                <a href="?lista=<?= urlencode($lista['lista_id']) ?>" class="<?= $active ?>">
                                    <?= $id ?>
                                </a>
                                <?php if (!$active): ?>
                                    <form method="post" action="gestionar_playlist.php" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('¿Eliminar playlist \'<?= htmlspecialchars($lista['lista_id']) ?>\'?');">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                        <input type="hidden" name="action" value="delete_playlist">
                                        <input type="hidden" name="lista_id" value="<?= htmlspecialchars($lista['lista_id']) ?>">
                                        <button type="submit" class="small-x" title="Eliminar playlist">×</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Botón para eliminar la playlist actual (si hay una seleccionada) -->
                    <?php if ($listaSel): ?>
                        <div style="margin-top: 20px;">
                            <form method="post" action="gestionar_playlist.php" 
                                  onsubmit="return confirm('¿Estás SEGURO de que quieres eliminar la playlist \'<?= htmlspecialchars($listaSel) ?>\'?\n\nEsta acción no se puede deshacer.');">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="hidden" name="action" value="delete_playlist">
                                <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
                                <button type="submit" class="confirm-delete">
                                    🗑️ Eliminar Playlist "<?= htmlspecialchars($listaSel) ?>"
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
            
            <!-- GESTIÓN DE CANCIONES EN PLAYLIST SELECCIONADA -->
            <?php if ($listaSel): ?>
                <section id="gestion-canciones" class="section <?= $addMode ? 'highlight-section' : '' ?>">
                    <h2>Gestionar: <?= htmlspecialchars($listaSel) ?></h2>

                    <!-- AÑADIR CANCIÓN -->
                    <div class="form-group">
                        <h3>Añadir Canción</h3>
                        <form method="post" action="gestionar_playlist.php">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
                            
                            <label for="nombre_cancion">Seleccionar Canción:</label>
                            <select id="nombre_cancion" name="nombre_cancion" required>
                                <option value="">-- Selecciona una canción --</option>
                                <?php foreach ($todasLasCanciones as $cancion): ?>
                                    <?php
                                    // Manejar tanto objetos como arrays
                                    $nombreCancion = is_object($cancion) ? $cancion->getNombre() : $cancion['nombre'];
                                    $nombreCreador = is_object($cancion) ? $cancion->getNombreCreador() : $cancion['nombre_creador'];
                                    ?>
                                    <option value="<?= htmlspecialchars($nombreCancion) ?>" 
                                            data-creador="<?= htmlspecialchars($nombreCreador) ?>">
                                        <?= htmlspecialchars($nombreCancion) ?> - <?= htmlspecialchars($nombreCreador) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <input type="hidden" name="nombre_creador" id="nombre_creador">
                            
                            <button type="submit" class="btn" style="margin-top: 10px;">Añadir a Playlist</button>
                        </form>
                    </div>

                    <!-- LISTA DE CANCIONES -->
                    <div class="form-group">
                        <h3>Canciones en la Playlist (<?= count($canciones) ?>)</h3>
                        <?php if (empty($canciones)): ?>
                            <p>Esta playlist está vacía. Añade algunas canciones para comenzar.</p>
                        <?php else: ?>
                            <ul class="song-list">
                                <?php foreach ($canciones as $cancion): ?>
                                    <li class="song-item">
                                        <div>
                                            <strong><?= htmlspecialchars($cancion['nombre_cancion']) ?></strong> 
                                            por <?= htmlspecialchars($cancion['nombre_creador']) ?>
                                        </div>
                                        <form method="post" action="gestionar_playlist.php" style="display:inline;">
                                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                            <input type="hidden" name="action" value="del">
                                            <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
                                            <input type="hidden" name="nombre_cancion" value="<?= htmlspecialchars($cancion['nombre_cancion']) ?>">
                                            <input type="hidden" name="nombre_creador" value="<?= htmlspecialchars($cancion['nombre_creador']) ?>">
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('¿Eliminar \'<?= htmlspecialchars($cancion['nombre_cancion']) ?>\' de la playlist?');">
                                                Eliminar
                                            </button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-completar el creador cuando se selecciona una canción
        document.getElementById('nombre_cancion').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value && selectedOption.dataset.creador) {
                document.getElementById('nombre_creador').value = selectedOption.dataset.creador;
            }
        });
        
        // Si estamos en modo "añadir canciones", hacer scroll a la sección de gestión
        <?php if ($addMode && $listaSel): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const section = document.getElementById('gestion-canciones');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Quitar el highlight después de 4 segundos
                    setTimeout(function() {
                        section.classList.remove('highlight-section');
                    }, 4000);
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>