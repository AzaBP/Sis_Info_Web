<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/PlaylistController.php';

Session::requireLogin();
$uid = (string)$_SESSION['uid'];
$ctl = new PlaylistController();
$action = $_REQUEST['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }
    $listaId = $_POST['lista_id'] ?? '';
    $res = $ctl->crearLista($uid, $listaId);
    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=create':'e=create'));
    exit;
}
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }
    $listaId = $_POST['lista_id'] ?? '';
    $cancion = $_POST['nombre_cancion'] ?? '';
    $creador = $_POST['nombre_creador'] ?? '';
    $res = $ctl->agregarCancion($listaId, $cancion, $creador);
    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=add':'e=add').'&lista='.$listaId);
    exit;
}
if ($action === 'del' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('CSRF inválido'); }
    $listaId = $_POST['lista_id'] ?? '';
    $cancion = $_POST['nombre_cancion'] ?? '';
    $creador = $_POST['nombre_creador'] ?? '';
    $res = $ctl->eliminarCancion($listaId, $cancion, $creador);
    header('Location: gestionar_playlist.php?'.($res['ok']?'ok=del':'e=del').'&lista='.$listaId);
    exit;
}

// list (GET)
$csrf = Session::csrfToken();
$listas = $ctl->listasDeUsuario($uid);
$listaSel = $_GET['lista'] ?? ($listas[0]['lista_id'] ?? null);
$canciones = $listaSel ? $ctl->cancionesDeLista($listaSel) : [];
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Mis Playlists</title></head>
<body>
  <h1>Mis Playlists</h1>

  <h2>Crear nueva lista</h2>
  ?action=create
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>Identificador/Nombre lista <input name="lista_id" required></label>
    <button>Crear</button>
  </form>

  <h2>Listas</h2>
  <ul>
    <?php foreach ($listas as $l): ?>
      <li>
        <a href="?lista=<?= urlencode($l[specialchars($l['lista_id']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if ($listaSel): ?>
    <h2>Lista: <?= htmlspecialchars($listaSel) ?></h2>
    <h3>Añadir canción</h3>
    ?action=add
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
      <label>Canción <input name="nombre_cancion" required></label>
      <label>Creador (usuario_id) <input name="nombre_creador" required></label>
      <button>Añadir</button>
    </form>

    <h3>Canciones en la lista</h3>
    <ul>
      <?php foreach ($canciones as $c): ?>
        <li>
          <?= htmlspecialchars($c['nombre_cancion']) ?> — <?= htmlspecialchars($c['nombre_creador']) ?>
          ?action=del
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="lista_id" value="<?= htmlspecialchars($listaSel) ?>">
            <input type="hidden" name="nombre_cancion" value="<?= htmlspecialchars($c['nombre_cancion']) ?>">
            <input type="hidden" name="nombre_creador" value="<?= htmlspecialchars($c['nombre_creador']) ?>">
            <button onclick="return confirm('¿Eliminar de la lista?')">Eliminar</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <p><a href="perfilr al perfil</a></p>
