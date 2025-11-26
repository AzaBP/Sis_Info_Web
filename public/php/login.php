<?php
require_once __DIR__ . '/../../src/lib/Session.php';
Session::start(); 
$csrf = Session::csrfToken();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - VMusic</title>
    
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body class="bg-900">
    <div class="login-wrap">
        <h1 class="page-title-above">VMusic</h1>
        <div class="login-card">
            <h2>Iniciar sesión</h2>
            <form method="post" action="procesar_login.php">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                
                <div class="login-field">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="tu@email.com" required>
                </div>
                
                <div class="login-field">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                
                <div class="login-actions">
                    <button type="submit" class="btn btn-login">Entrar</button>
                </div>
            </form>
            
            <div class="guest-link">
                <a href="pagina_principal.php">Continuar como invitado</a>
            </div>
        </div>
    </div>
</body>
</html>