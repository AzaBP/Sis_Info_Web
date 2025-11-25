<?php
require_once __DIR__ . '/../../src/lib/Session.php';Session::requireLogin(); $csrf = Session::csrfToken();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Perfil</title></head>
<body>
  <h1>Mi perfil</h1>
  <form method="post" action="actualizar_perfil.php"> 
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>Nombre <input name="nombre" required></label><br>
    <label>Teléfono <input name="telefono" required></label><br>
    <button>Actualizar</button>
  </form>
  <p><a gestionar_playlist.phpGestionar playlists</a></p>
  <p><a href="procesphp?action=logout">Cerrar sesión</a></p>
</body></html>