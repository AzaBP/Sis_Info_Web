<?php
<?php
declare(strict_types=1);

// 1. Incluir clases esenciales: Sesión y Controlador de Playlists
require_once __DIR__ . '/../src/lib/Session.php';
require_once __DIR__ . '/../src/controllers/PlaylistController.php';

// 2. Iniciar sesión para identificar al usuario
Session::start();

// 3. Obtener el ID del usuario actual. Si no está logueado, $uid será null (Modo Invitado)
$uid = Session::getCurrentUser();

// 4. Inicializar el controlador para acceder a los datos de playlists
$playlistController = new PlaylistController();

$playlistsUsuario = [];
if ($uid) {
    // Si hay un usuario logueado, obtenemos sus listas de la BD
    // El método obtenerListasDeUsuario() está definido en ListaDAOImpl y usado por el Controller
    $playlistsUsuario = $playlistController->listasDeUsuario($uid);
} else {
    // Si no está logueado, para efectos de dinamismo, podríamos cargar listas genéricas.
    // Aquí, se mantendrá vacío o se cargará una lista por defecto/recomendada (a implementar).
    // Por ahora, si $uid es null, $playlistsUsuario queda vacío.
}

// Opcional: Para la sección de "Recommended Albums" (Álbumes Recomendados)
// Podemos simular datos de prueba si no hay un DAO de Álbumes dedicado, o cargar un array de VO.
$albumsRecomendados = [
    ['titulo' => 'Acoustic Breeze', 'imagen' => '../imagenes/ballads.jpg'],
    ['titulo' => 'Evening Sky', 'imagen' => '../imagenes/ACDC.jpg'],
    ['titulo' => 'Midnight Vibes', 'imagen' => '../imagenes/nectar.jpg'],
    ['titulo' => 'Smooth Jazz', 'imagen' => '../imagenes/trench.jpg']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>VMusic - Principal</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header class="app-header">
    <nav class="nav-left">
      <a href="#" class="nav-item active">Home</a>
      <a href="#" class="nav-item">Your Library</a>
    </nav>
    <input class="search" placeholder="Buscar" aria-label="Buscar" />
    <div class="header-actions">
      <!-- botón de menú -->
      <div class="header-right">
        <button class="icon-btn menu" aria-label="Menú" onclick="toggleMenu()">
          <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect y="1" width="20" height="2.6" rx="1" fill="white" />
            <rect y="6" width="20" height="2.6" rx="1" fill="white" />
            <rect y="11" width="20" height="2.6" rx="1" fill="white" />
          </svg>
        </button>
      </div>
    </header>

    <!-- Menú desplegable fuera del botón -->
    <nav id="dropdownMenu" class="dropdown-menu hidden">
      <img src="../images/user_icon.png" alt="Foto de perfil" class="profile-pic" />
      <ul>
        <li><a href="perfil_usuario.html">Perfil</a></li>
        <li><a href="pagina_principal.html">Inicio</a></li>
        <li><a href="ajustes_perfil.html">Ajustes</a></li>
        <li><a href="inicio_vmusic.html">Cerrar sesión</a></li>
      </ul>
    </nav>
      
    </div>
  </header>

  <main class="main-wrap">
    <section class="hero-section">
      <h1 class="page-title">Recommended for you</h1>

      <h3 class="section-title">Playlists</h3>
      <div class="playlists-row">
        <?php if (!empty($playlistsUsuario)): ?>
          <?php foreach ($playlistsUsuario as $playlist): ?>
            <a href="playlist.html?lista=<?= urlencode(htmlspecialchars($playlist['lista_id'])) ?>" title="Ver <?= htmlspecialchars($playlist['lista_id']) ?>" style="text-decoration: none; color: inherit;">
              <article class="pl-card">
                <div class="pl-art">♪</div>
                <div class="pl-caption"><?= htmlspecialchars($playlist['lista_id']) ?></div>
              </article>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <article class="pl-card">
            <div class="pl-art">♪</div>
            <div class="pl-caption">Chill Mix</div>
          </article>
          <article class="pl-card">
            <div class="pl-art">♪</div>
            <div class="pl-caption">Workout</div>
          </article>
          <article class="pl-card">
            <div class="pl-art">♪</div>
            <div class="pl-caption">Relaxing</div>
          </article>
          <article class="pl-card">
            <div class="pl-art">♪</div>
            <div class="pl-caption">Party</div>
          </article>
        <?php endif; ?>
      </div>

      <h3 class="section-title">Recommended Albums</h3>
      <div class="albums-grid">
        <?php foreach ($albumsRecomendados as $album): ?>
          <div class="album-card">
            <div class="album-cover" style="background: url('<?= htmlspecialchars($album['imagen']) ?>') center/cover;"></div>
            <div class="album-title"><?= htmlspecialchars($album['titulo']) ?></div>
          </div>
        <?php endforeach; ?>
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
        <button class="btn-ctrl">⏮</button>
        <button class="btn-ctrl play">▶</button>
        <button class="btn-ctrl">⏭</button>
      </div>
      <div class="player-controls-right">
        <div class="time">0:00</div>
      </div>
    </div>
  </footer>
  <script src="../js/menu_desplegable.js"></script>
</body>
</html>