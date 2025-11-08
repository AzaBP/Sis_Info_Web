<?php
require_once __DIR__ . '/../src/lib/Session.php';
Session::start(); $csrf = Session::csrfToken();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Registro</title></head>
<body>
  <h1>Crear cuenta</h1>
  <form method="post"hp
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>Nombre <input name="nombre" required></label><br>
    <label>Correo <input type="email" name="correo" required></label><br>
    <label>Contraseña <input type="password" name="password" required></label><br>
    <label>Teléfono <input name="telefono" required></label><br>
    <button>Registrarme</button>
  </form>
  <p>login.php¿Ya tienes cuenta?</a></p>
</body></html>