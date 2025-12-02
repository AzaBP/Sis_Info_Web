<?php
// inicio_vmusic.php
require_once __DIR__ . '/../../src/lib/Session.php';
Session::start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- interpretar los caracteres -->
        <meta charset="utf-8" />

        <!-- para que se vea bien en móviles -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- descripción -->
        <meta name="description" content="Página de inicio de VMusic, donde puedes registrarte o iniciar sesión para acceder a tu música favorita." />

        <!--Palabras clave-->
        <meta name="keywords" content="VMusic, música, inicio, registro, sesión" />

        <!-- título de la pestaña -->
        <title>VMusic - Inicio</title>
        
        <!-- Preconnect y carga de fuentes (Orbitron para título, Poppins para cuerpo) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

        <!-- enlace al archivo de estilos -->
        <link rel="stylesheet" href="../css/style.css">

        <!-- ícono de la pestaña (usa tu icono adjunto si está en la carpeta) -->
        <link rel="icon" type="image/png" href="../images/VMicon.png" />
    </head>
    <body>
        <!-- sección principal de la página -->
        <main class="hero">
            <!-- título principal -->
            <h1 class="title">VMusic</h1>
            <!-- fila de botones -->
            <div class="btn-row" role="navigation" aria-label="Acciones de inicio">
                <!-- botón de registro - ahora apunta a registro.php -->
                <a class="btn btn-register" id="registrarse" href="registro.php">Registrarse</a>
                <!-- botón de inicio de sesión - ahora apunta a login.php -->
                <a class="btn btn-login" id="iniciar-sesion" href="login.php">Iniciar sesión</a>
            </div>
            <!-- Enlace para continuar como invitado -->
            <div class="guest-link">
                <a href="pagina_principal.php" target="_self" role="link">Continuar como invitado</a>
            </div>
        </main>
    </body>
</html>