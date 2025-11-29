<?php
declare(strict_types=1);

// 1. Incluir clases esenciales
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/SessionController.php';
require_once __DIR__ . '/../../src/controllers/UserController.php';
require_once __DIR__ . '/../../src/controllers/PlaylistController.php';
require_once __DIR__ . '/../../config/Database.php'; // Necesario para instanciar DAOs

// 2. Requerir autenticación
// Si el usuario no está logueado, lo redirige automáticamente a login.php
Session::requireLogin(); 
$uid = (string)$_SESSION['uid']; // Obtenemos el ID del usuario logueado

// 3. Inicializar controladores
$database = new Database();
$dbConnection = $database->getConnection();

$userController = new UserController(); 
$playlistController = new PlaylistController(); 

// 4. Carga de datos del usuario
$usuarioVO = $userController->obtenerUsuarioPorId($uid); 

// Manejo de sesión corrupta: si el UID existe pero el usuario no se encuentra en BD 
if (!$usuarioVO) {
    Session::logout();
    header('Location: login.php?e=user_not_found');
    exit;
}

// Convertir el VO en un array para facilitar la inyección en HTML
$usuario = [
    'nombre' => $usuarioVO->getNombre(),
    'correo' => $usuarioVO->getCorreo(),
    // otros campos relevantes para la vista...
];

// 5. Carga de listas y biblioteca
$playlistsUsuario = $playlistController->listasDeUsuario($uid);

// 6. OBTENER PLAYLISTS RECOMENDADAS (playlists públicas de otros usuarios)
$playlistsRecomendadas = $playlistController->obtenerPlaylistsRecomendadas($uid);

// Si se recibe un mensaje de actualización (ej. después de actualizar_perfil.php)
$updateMessage = $_GET['updated'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Usuario - VMusic</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: white;
    }
    .header {
      display: flex;
      align-items: center;
      padding: 16px;
    }
    .back-btn {
      background: none;
      border: none;
      cursor: pointer;
    }
    .back-btn svg {
      stroke: white;
    }
    .profile-section {
      text-align: center;
      margin-top: 20px;
    }
    .profile-section img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    .profile-section h2 {
      margin-top: 12px;
      font-size: 24px;
    }
    .edit-profile-btn {
      display: inline-block;
      margin-top: 8px;
      padding: 6px 16px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      color: white;
      text-decoration: none;
      font-size: 14px;
      transition: background-color 0.2s ease;
    }

    .edit-profile-btn:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .section {
      margin: 30px 20px;
    }
    .section h3 {
      margin-bottom: 12px;
      font-size: 20px;
    }
    .card-row {
      display: flex;
      gap: 12px;
      overflow-x: auto;
    }
    .card {
      min-width: 120px;
      height: 120px;
      background-color: #1e1e1e;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #ccc;
    }
    
    /* Estilos para mensajes de actualización */
    .update-message {
      color: #00d4ff;
      text-align: center;
      font-weight: bold;
      margin: 10px 0;
    }
    
    /* Estilos para las playlists */
    .pl-card {
      min-width: 120px;
      height: 120px;
      background-color: #1e1e1e;
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: #ccc;
      text-decoration: none;
      transition: background-color 0.2s ease;
    }
    
    .pl-card:hover {
      background-color: #2a2a2a;
    }
    
    .pl-art {
      font-size: 24px;
      margin-bottom: 8px;
    }
    
    .pl-caption {
      text-align: center;
      padding: 0 8px;
    }
    
    /* Estilo para cuando no hay playlists */
    .no-playlists {
      text-align: center;
      color: #888;
      font-style: italic;
      margin: 20px 0;
    }
    
    /* Estilo para el enlace de cerrar sesión */
    .logout-link {
      text-align: center;
      margin: 30px 0;
    }
    
    .logout-link a {
      color: #ff4444;
      text-decoration: none;
      padding: 8px 16px;
      border: 1px solid #ff4444;
      border-radius: 20px;
      transition: all 0.2s ease;
    }
    
    .logout-link a:hover {
      background-color: #ff4444;
      color: white;
    }
    
    /* Estilos para playlists recomendadas */
    .recommended-card {
      min-width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: white;
      text-decoration: none;
      transition: transform 0.2s ease;
    }
    
    .recommended-card:hover {
      transform: translateY(-5px);
      text-decoration: none;
      color: white;
    }
    
    .recommended-art {
      font-size: 24px;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
  <header class="header">
    <button class="back-btn" onclick="location.href='pagina_principal.php'" aria-label="Volver">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </button>
  </header>

  <section class="profile-section">
    <img src="../images/user_icon.png" alt="Foto de perfil del usuario">
    <h2><?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?></h2>
    
    <?php if ($updateMessage): ?>
      <div class="update-message">Perfil actualizado correctamente.</div>
    <?php endif; ?>
    
    <a href="ajustes_perfil.php" class="edit-profile-btn">Editar perfil</a>
  </section>

  <section class="section">
    <h3>Tu biblioteca</h3>
    
    <!-- BOTÓN PARA CREAR NUEVA PLAYLIST -->
    <div style="margin-bottom: 20px;">
        <a href="gestionar_playlist.php" class="edit-profile-btn" style="display: inline-block;">
            ＋ Crear Nueva Playlist
        </a>
    </div>
    
        <div class="card-row">
            <?php if (empty($playlistsUsuario)): ?>
                <div class="no-playlists">
                    Aún no has creado ninguna lista.<br>
                    <a href="gestionar_playlist.php" style="color: #00d4ff; margin-top: 10px; display: inline-block;">
                        Crear tu primera playlist
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($playlistsUsuario as $lista): ?>
                    <a href="playlist.php?id=<?= urlencode($lista['lista_id']) ?>" class="pl-card">
                        <span class="pl-art">♪</span>
                        <span class="pl-caption"><?= htmlspecialchars($lista['lista_id']) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

  <section class="section">
    <h3>Últimas escuchas</h3>
    <div class="card-row">
      <div class="card">Artista 1</div>
      <div class="card">Canción 2</div>
      <div class="card">Lista 3</div>
    </div>
  </section>

  <section class="section">
    <h3>Listas recomendadas</h3>
    <div class="card-row">
      <?php if (!empty($playlistsRecomendadas)): ?>
        <?php foreach ($playlistsRecomendadas as $playlist): ?>
          <a href="playlist.php?id=<?= urlencode($playlist['lista_id']) ?>" class="recommended-card">
            <span class="recommended-art">⭐</span>
            <span class="pl-caption"><?= htmlspecialchars($playlist['lista_id']) ?></span>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-playlists">
          No hay playlists recomendadas disponibles.
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="section">
    <h3>Artistas que sigues</h3>
    <div class="card-row">
      <div class="card">Artista 1</div>
      <div class="card">Artista 2</div>
      <div class="card">Artista 3</div>
    </div>
  </section>

  <section class="logout-link">
    <a href="procesar_login.php?action=logout">Cerrar Sesión</a>
  </section>
</body>
</html>