<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/controllers/UserController.php'; 
require_once __DIR__ . '/../../src/controllers/PlaylistController.php'; 
require_once __DIR__ . '/../../src/dao/CancionDAO.php';
require_once __DIR__ . '/../../src/dao/CancionDAOImpl.php';
require_once __DIR__ . '/../../config/Database.php';

// 1. Iniciar sesión y obtener estado del usuario
Session::start();
$uid = Session::check() ? $_SESSION['uid'] : null;

// 2. Cargar datos dinámicos
$database = new Database();
$cancionDAO = new CancionDAOImpl($database->getConnection());

// Obtener canciones reales de la base de datos
$cancionesRecomendadas = $cancionDAO->obtenerCancionesConImagenes();

// Si no hay método para todas las canciones, usar algunas de ejemplo
if (empty($cancionesRecomendadas)) {
    $cancionesRecomendadas = [
        ['nombre' => 'Bohemian Rhapsody', 'creador' => 'Queen', 'imagen' => '../imagenes/Estopa.jpg'],
        ['nombre' => 'Blinding Lights', 'creador' => 'The Weeknd', 'imagen' => '../imagenes/ACDC.jpg'],
        ['nombre' => 'Shape of You', 'creador' => 'Ed Sheeran', 'imagen' => '../imagenes/nectar.jpg'],
        ['nombre' => 'Bad Guy', 'creador' => 'Billie Eilish', 'imagen' => '../imagenes/trench.jpg']
    ];
}

$playlistController = new PlaylistController();

// CORREGIDO: Obtener playlists públicas
$playlistsPublicas = $playlistController->obtenerPlaylistsPublicas();

// CORREGIDO: Playlists del usuario (solo si está logueado)
$playlistsUsuario = [];
if ($uid) {
    $playlistsUsuario = $playlistController->listasDeUsuario($uid);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>VMusic - Principal</title>
  <link rel="stylesheet" href="/recomendador_musica/public/css/style.css">
  <style>
    .main-content {
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .section-container {
      margin: 40px 0;
    }
    
    .section-title {
      color: #00d4ff;
      margin-bottom: 20px;
      font-size: 1.5rem;
      border-bottom: 2px solid #00d4ff;
      padding-bottom: 8px;
    }
    
    .playlists-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .playlist-card {
      background: #2a2a2a;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: transform 0.3s ease, background 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      display: block;
    }
    
    .playlist-card:hover {
      transform: translateY(-5px);
      background: #333;
      text-decoration: none;
      color: inherit;
    }
    
    .playlist-icon {
      font-size: 3rem;
      margin-bottom: 15px;
      color: #00d4ff;
    }
    
    .playlist-name {
      font-weight: bold;
      color: white;
      margin-bottom: 5px;
    }
    
    .playlist-type {
      font-size: 0.8rem;
      color: #ccc;
    }
    
    .empty-message {
      text-align: center;
      padding: 40px;
      color: #888;
      background: #2a2a2a;
      border-radius: 12px;
      margin: 20px 0;
    }
    
    .empty-message a {
      color: #00d4ff;
      text-decoration: none;
    }
    
    .empty-message a:hover {
      text-decoration: underline;
    }
    
    .albums-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 25px;
      margin-top: 20px;
    }
    
    .album-card {
      background: #2a2a2a;
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s ease;
      text-decoration: none;
      color: inherit;
    }
    
    .album-card:hover {
      transform: translateY(-5px);
      text-decoration: none;
      color: inherit;
    }
    
    .album-cover {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    
    .album-info {
      padding: 15px;
    }
    
    .album-title {
      color: white;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .album-artist {
      color: #ccc;
      font-size: 0.9rem;
    }
  </style>
</head>
<body class="bg-900">
  <header class="app-header">
    <nav class="nav-left">
      <a href="#" class="nav-item active">Home</a>
      <a href="perfil_usuario.php" class="nav-item">Your Library</a>
    </nav>
    <input class="search" placeholder="Buscar" aria-label="Buscar" />
    <div class="header-actions">
      <div class="header-right">
        <button class="icon-btn menu" aria-label="Menú" onclick="toggleMenu()">
          <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect y="1" width="20" height="2.6" rx="1" fill="white" />
            <rect y="6" width="20" height="2.6" rx="1" fill="white" />
            <rect y="11" width="20" height="2.6" rx="1" fill="white" />
          </svg>
        </button>
      </div>
    </div>
  </header>

  <!-- Menú desplegable -->
  <nav id="dropdownMenu" class="dropdown-menu hidden">
    <img src="../imagenes/user_icon.png" alt="Foto de perfil" class="profile-pic" />
    <ul>
      <li><a href="perfil_usuario.php">Perfil</a></li>
      <li><a href="pagina_principal.php">Inicio</a></li>
      <li><a href="ajustes_perfil.php">Ajustes</a></li>
      <li><a href="procesar_login.php?action=logout">Cerrar sesión</a></li>
    </ul>
  </nav>

  <main class="main-content">
    <section class="hero-section">
      <h1 class="page-title" style="color: #00d4ff; margin-bottom: 30px;">Recommended for you</h1>

      <!-- Playlists Públicas -->
      <div class="section-container">
        <h3 class="section-title">Playlists Públicas</h3>
        <div class="playlists-grid">
          <?php if (!empty($playlistsPublicas)): ?>
            <?php foreach ($playlistsPublicas as $playlist): ?>
              <a href="playlist.php?id=<?= urlencode($playlist['lista_id']) ?>" class="playlist-card">
                <div class="playlist-icon">♪</div>
                <div class="playlist-name"><?= htmlspecialchars($playlist['lista_id']) ?></div>
                <div class="playlist-type">Pública</div>
              </a>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-message">
              <p>No hay playlists públicas disponibles</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Playlists del Usuario -->
      <?php if ($uid): ?>
        <div class="section-container">
          <h3 class="section-title">Tus Playlists</h3>
          <div class="playlists-grid">
            <?php if (!empty($playlistsUsuario)): ?>
              <?php foreach ($playlistsUsuario as $playlist): ?>
                <a href="playlist.php?id=<?= urlencode($playlist['lista_id']) ?>" class="playlist-card">
                  <div class="playlist-icon">⭐</div>
                  <div class="playlist-name"><?= htmlspecialchars($playlist['lista_id']) ?></div>
                  <div class="playlist-type">Tu playlist</div>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="empty-message">
                <p>Aún no has creado playlists.</p>
                <p><a href="gestionar_playlist.php">Crea tu primera playlist</a></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="section-container">
          <h3 class="section-title">Playlists Personales</h3>
          <div class="empty-message">
            <p><a href="login.php">Inicia sesión</a> para crear y ver tus playlists personales</p>
          </div>
        </div>
      <?php endif; ?>

      <!-- Álbumes Recomendados -->
      <div class="section-container">
        <h3 class="section-title">Álbumes Recomendados</h3>
        <div class="albums-grid">
          <?php foreach ($cancionesRecomendadas as $cancion): ?>
            <a href="detalles_cancion.php?cancion=<?= urlencode($cancion['nombre']) ?>&creador=<?= urlencode($cancion['creador']) ?>" class="album-card">
              <?php if (isset($cancion['imagen'])): ?>
                <img src="<?= htmlspecialchars($cancion['imagen']) ?>" alt="<?= htmlspecialchars($cancion['nombre']) ?>" class="album-cover">
              <?php else: ?>
                <div class="album-cover" style="background: linear-gradient(135deg, #ff9a3c, #ff5e5e); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                  ♪
                </div>
              <?php endif; ?>
              <div class="album-info">
                <div class="album-title"><?= htmlspecialchars($cancion['nombre']) ?></div>
                <div class="album-artist"><?= htmlspecialchars($cancion['creador']) ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="mini-player">
    <div class="player-inner">
      <div class="player-controls-left">
        <div class="track-info">
          <div class="track-title">Now playing</div>
          <div class="track-sub">Artist • Album</div>
        </div>
      </div>
      <div class="player-controls-center">
        <button class="btn-ctrl">◀◀</button>
        <button class="btn-ctrl play">▶</button>
        <button class="btn-ctrl">▶▶</button>
      </div>
      <div class="player-controls-right">
        <div class="time">0:00</div>
      </div>
    </div>
  </footer>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('dropdownMenu');
      menu.classList.toggle('hidden');
    }
    
    // Cerrar menú al hacer clic fuera de él
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('dropdownMenu');
      const menuButton = document.querySelector('.icon-btn.menu');
      
      if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
        menu.classList.add('hidden');
      }
    });
  </script>
</body>
</html>