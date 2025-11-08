<?php
require_once __DIR__ . '/../src/lib/Session.php';
Session::start(); $csrf = Session::csrfToken();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h1>Iniciar sesión</h1>
  procesar_login.php
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <label>Correo <input type="email" name="correo" required></label><br>
    <label>Contraseña <input type="password" name="password" required></label><br>
    <button>Entrar</button>
  </form>
</body></html>
