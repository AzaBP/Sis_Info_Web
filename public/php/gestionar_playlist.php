<?php
declare(strict_types=1);

// 1. Incluir clases esenciales
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';

// 2. Seguridad y preparación
Session::requireLogin(); // Obliga al usuario a estar autenticado
$uid = (string)$_SESSION['uid']; // ID del usuario logueado
$ctl = new PlaylistController(); // Instancia el controlador

$action = $_REQUEST['action'] ?? 'list'; // Determina la acción a realizar

// 3. LÓGICA DE PROCESAMIENTO (POST): creación, adición y eliminación de canciones.

// Crear nueva lista (create)
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); } // Protección CSRF
    
    $listaId = $_POST['lista_id'] ?? '';
    $res = $ctl->crearLista($uid, $listaId);

    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=create':'e=create'));
    exit;
}

// Añadir canción a una lista (add)
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); } // Protección CSRF
    
    $listaId = $_POST['lista_id'] ?? '';
    $cancion = $_POST['nombre_cancion'] ?? '';
    $creador = $_POST['nombre_creador'] ?? '';
    
    $res = $ctl->agregarCancion($listaId, $cancion, $creador);

    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=add':'e=add').'&lista='.$listaId);
    exit;
}

// Eliminar canción de una lista (del)
if ($action === 'del' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); } // Protección CSRF
    
    $listaId = $_POST['lista_id'] ?? '';
    $cancion = $_POST['nombre_cancion'] ?? '';
    $creador = $_POST['nombre_creador'] ?? '';
    $res = $ctl->eliminarCancion($listaId, $cancion, $creador);
    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=del':'e=del').'&lista='.$listaId);
    exit;
}

// 4. Lógica de listado(GET): si no es una petición POST o si $action es 'list'.
$csrf = Session::csrfToken(); // Generar token para los formularios

$listas = $ctl->listasDeUsuario($uid); // Obtiene todas las listas del usuario
$listaSel = $_GET['lista'] ?? ($listas[0]['lista_id'] ?? null); // Selecciona la lista activa (o la primera)
$canciones = $listaSel ? $ctl->cancionesDeLista($listaSel) : []; // Carga las canciones de la lista seleccionada

// Manejo de mensajes de estado (éxito o error)
$mensaje = '';
if (isset($_GET['ok'])) $mensaje = "Operación '{$_GET['ok']}' realizada con éxito.";
if (isset($_GET['e'])) $mensaje = "Error al realizar la operación '{$_GET['e']}'.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Playlists - VMusic</title>
    <!-- Asumimos la inclusión del CSS de VMusic -->
    <!-- ... -->
</head>
<body class="bg-900">
    <div class="main-wrap container">
        <div class="central-section">
            <h1>Mis Playlists</h1>

            <?php if ($mensaje): ?>
                <p style="color: #00d4ff; font-weight: bold;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <!-- 4.1. Formulario: crear nueva lista -->
            <section>
                <h2>Crear nueva lista</h2>
                <form method="post" action="gestionar_playlist.php?action=create">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <label>Identificador/Nombre lista
                        <input name="lista_id" required>
                    </label>
                    <button>Crear</button>
                </form>
            </section>
            
            <hr>

            <!-- 4.2. Listado: listas del usuario -->
            <section>
                <h2>Listas</h2>
                <?php if (empty($listas)): ?>
                    <p>Aún no has creado ninguna lista. Utiliza el formulario superior.</p>
                <?php else: ?>
                    <p>
                        <?php foreach ($listas as $lista): ?>
                            <?php 
                                $id = htmlspecialchars($lista['lista_id']);
                                $active = ($listaSel === $lista['lista_id']) ? 'style="font-weight: bold; color: var(--neon-cyan);"' : '';
                            ?>
                            <a href="?lista=<?= urlencode($lista['lista_id']) ?>" <?= $active ?>><?= $id ?></a> - 
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
            </section>
            
            <?php if ($listaSel): ?>
                <hr>
                
                <h2>Lista: <?= htmlspecialchars($listaSel) ?></h2>

                <!-- 4.3. Formulario: añadir canción -->
                <section>
                    <h3>Añadir canción</h3>
                    <form method="post" action="gestionar_playlist.php?action=add">
                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                        <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
                        <label>Canción
                            <input name="nombre_cancion" required>
                        </label><br>
                        <label>Creador (usuario_id)
                            <input name="nombre_creador" required>
                        </label><br>
                        <button>Añadir</button>
                    </form>
                </section>

                <!-- 4.4. Canciones en la lista y eliminar formulario -->
                <section>
                    <h3>Canciones en la lista</h3>
                    <?php if (empty($canciones)): ?>
                        <p>Esta lista está vacía.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($canciones as $cancion): ?>
                                <li>
                                    <?= htmlspecialchars($cancion['nombre_cancion']) ?> por 
                                    <?= htmlspecialchars($cancion['nombre_creador']) ?> 
                                    <!-- Formulario invisible para eliminar, envía POST con el ID de la canción -->
                                    <form method="post" action="gestionar_playlist.php?action=del" style="display:inline;">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                        <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
                                        <input type="hidden" name="nombre_cancion" value="<?= htmlspecialchars($cancion['nombre_cancion']) ?>">
                                        <input type="hidden" name="nombre_creador" value="<?= htmlspecialchars($cancion['nombre_creador']) ?>">
                                        <button type="submit" style="background: red; padding: 4px 8px; font-size: 0.8rem;">Eliminar</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            
            <hr>
            <p><a href="perfil_usuario.php">Volver al perfil</a></p>
        </div>
    </div>
</body>
</html>
