<?php
require_once __DIR__ . '/../../src/lib/Session.php';
Session::start(); 
$csrf = Session::csrfToken();

// Manejo de errores
$errors = [];
if (isset($_GET['e'])) {
    $errors = json_decode($_GET['e'], true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>VMusic - Registrarse</title>
		<link rel="stylesheet" href="../css/style.css">
	</head>
	<body class="bg-900">
		<div class="login-wrap">
			<div>
				<div class="page-title-above">VMusic</div>
				<section class="login-card" aria-labelledby="reg-title">
					<h2 id="reg-title">Registrarse</h2>

					<?php if ($errors): ?>
					<div style="color: #ff6b6b; background: rgba(255,107,107,0.1); padding: 12px; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(255,107,107,0.3);">
						<p style="margin: 0 0 8px 0; font-weight: bold;">Por favor, corrige los siguientes errores:</p>
						<ul style="margin: 0; padding-left: 20px;">
							<?php foreach ($errors as $field => $msg): ?>
								<li><?= htmlspecialchars($msg) ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<div class="decorative-frame">
					<form class="login-form" action="procesar_registro.php" method="post">
						<!-- CSRF Token para seguridad -->
						<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
						
						<div class="login-field">
							<label for="nombre">Nombre completo</label>
							<input id="nombre" name="nombre" type="text" placeholder="Tu nombre" required 
								   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
						</div>

						<div class="login-field">
							<label for="correo">Correo electrónico</label>
							<input id="correo" name="correo" type="email" placeholder="tucorreo@ejemplo.com" required
								   value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
						</div>

						<div class="login-field">
							<label for="password">Contraseña</label>
							<input id="password" name="password" type="password" placeholder="********" required>
							<small style="color: rgba(230,249,255,0.6); font-size: 0.8rem; display: block; margin-top: 5px;">
								Mínimo 8 caracteres, incluir mayúsculas, minúsculas y números
							</small>
						</div>

						<div class="login-field">
							<label for="password2">Confirmar contraseña</label>
							<input id="password2" name="password2" type="password" placeholder="********" required>
						</div>

						<div class="login-field">
							<label for="telefono">Teléfono</label>
							<input id="telefono" name="telefono" type="tel" placeholder="123456789" required
								   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
						</div>

						<div class="login-actions">
							<button class="btn btn-login" type="submit">Registrarse</button>
						</div>
						<div style="text-align:center; margin-top:0.6rem;">
							<p style="font-size:0.9rem; color:rgba(230,249,255,0.8);">¿Ya tienes cuenta? <a href="login.php" style="color:var(--neon-cyan); text-decoration:underline;">Inicia sesión</a></p>
						</div>
					</div>
					</form>
				</section>
			</div>
		</div>
	</body>
</html>