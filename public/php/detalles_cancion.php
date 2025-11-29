<?php
declare(strict_types=1);

// 1. Incluir clases esenciales
require_once __DIR__ . '/../../src/lib/Session.php';
require_once __DIR__ . '/../../src/dao/CancionDAO.php';
require_once __DIR__ . '/../../src/dao/CancionDAOImpl.php';
require_once __DIR__ . '/../../src/vo/CancionVO.php';
require_once __DIR__ . '/../../config/Database.php';

Session::start();

// La canción se identifica por su clave primaria compuesta: nombre y creador
$nombreCancion = $_GET['cancion'] ?? null; 
$nombreCreador = $_GET['creador'] ?? null;

// Si faltan parámetros críticos
if (!$nombreCancion || !$nombreCreador) {
    header('Location: pagina_principal.php');
    exit;
}

// 3. Inicializar DAO y obtener datos de la canción
$database = new Database();
$dbConnection = $database->getConnection();
$cancionDAO = new CancionDAOImpl($dbConnection);

// 3. CARGA DE DATOS PRINCIPALES (Canción)
$cancionVO = $cancionDAO->obtenerCancionPorId($nombreCancion, $nombreCreador);

if (!$cancionVO) {
    // Si la canción no se encuentra
    exit("Error 404: Canción no encontrada.");
}


// Mapeo de imágenes para cada canción
$imagenesCanciones = [
    'Bohemian Rhapsody' => '../imagenes/Estopa.jpg',
    'Blinding Lights' => '../imagenes/ACDC.jpg',
    'Shape of You' => '../imagenes/nectar.jpg',
    'Bad Guy' => '../imagenes/trench.jpg',
    'Dakiti' => '../imagenes/Estopa.jpg',
    'Watermelon Sugar' => '../imagenes/ACDC.jpg',
    'Levitating' => '../imagenes/nectar.jpg',
    'Stay' => '../imagenes/trench.jpg',
    'Good 4 U' => '../imagenes/Estopa.jpg',
    'Save Your Tears' => '../imagenes/ACDC.jpg'
];

// Intentar obtener datos usando métodos comunes
$cancion = [
    'nombre' => method_exists($cancionVO, 'getNombre') ? $cancionVO->getNombre() : $nombreCancion,
    'artista' => method_exists($cancionVO, 'getNombreCreador') ? $cancionVO->getNombreCreador() : $nombreCreador,
    'duracion' => method_exists($cancionVO, 'getDuracion') ? $cancionVO->getDuracion() : '3:00',
    'valoracion' => method_exists($cancionVO, 'getValoracion') ? $cancionVO->getValoracion() : 3,
    'caratula_img' => $imagenesCanciones[$nombreCancion] ?? '../imagenes/caratula.png'
];

// 4. Cargar datos auxiliares (Comentarios y Canciones Similares)
$comentarios = [
    ['usuario' => 'Usuario1', 'valoracion' => '★★★★☆', 'texto' => 'Me encanta esta canción, tiene muy buen ritmo.'],
    ['usuario' => 'Usuario2', 'valoracion' => '★★★☆☆', 'texto' => 'Está bien, pero prefiero otras del mismo artista.'],
    ['usuario' => 'Usuario3', 'valoracion' => '★★★★★', 'texto' => '¡Una obra maestra!']
];

// Obtener algunas canciones similares (excluyendo la actual)
$todasCanciones = $cancionDAO->obtenerTodasLasCanciones();
$similares = [];

foreach ($todasCanciones as $cancionSim) {
    // CORRECCIÓN: Manejar tanto objetos como arrays
    if (is_object($cancionSim)) {
        $nombreSim = $cancionSim->getNombre();
        $creadorSim = $cancionSim->getNombreCreador();
    } else {
        $nombreSim = $cancionSim['nombre'] ?? '';
        $creadorSim = $cancionSim['nombre_creador'] ?? '';
    }
    
    if ($nombreSim !== $cancion['nombre'] && count($similares) < 3) {
        $similares[] = [
            'titulo' => $nombreSim,
            'imagen' => $imagenesCanciones[$nombreSim] ?? '../imagenes/caratula.png',
            'nombre' => $nombreSim,
            'creador' => $creadorSim
        ];
    }
}

// Si no hay suficientes similares, agregar placeholders
while (count($similares) < 3) {
    $similares[] = [
        'titulo' => 'Canción ' . (count($similares) + 1),
        'imagen' => '../imagenes/caratula.png',
        'nombre' => 'cancion_' . (count($similares) + 1),
        'creador' => 'artista'
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de Canción</title>
  <link rel="stylesheet" href="/recomendador_musica/public/css/style.css">
  <style>
    /* Tus estilos CSS aquí (los mismos que antes) */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: white;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
    }
    .icon-btn {
      background: none;
      border: none;
      cursor: pointer;
    }
    .icon-btn svg {
      stroke: white;
    }
    .dropdown-menu {
      position: absolute;
      top: 60px;
      right: 16px;
      background-color: #1e1e1e;
      border: 1px solid #333;
      padding: 10px;
      border-radius: 8px;
    }
    .dropdown-menu.hidden {
      display: none;
    }
    .dropdown-menu a {
      display: block;
      color: white;
      text-decoration: none;
      margin: 8px 0;
    }
    .song-info {
      text-align: center;
      padding: 20px;
    }
    .song-info img {
      width: 200px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    .song-info h2 {
      margin-top: 16px;
      font-size: 24px;
    }
    .song-info p {
      margin: 4px 0;
      color: #ccc;
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
      min-width: 100px;
      text-align: center;
    }
    .card img {
      width: 100px;
      height: 100px;
      border-radius: 8px;
    }
    .card p {
      margin-top: 6px;
      font-size: 14px;
      color: #ccc;
    }
    .comments {
      margin: 20px;
    }
    .comment {
      background-color: #1e1e1e;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 12px;
    }
    .comment h4 {
      margin: 0 0 6px;
    }
    .comment p {
      margin: 0;
      color: #aaa;
    }
  </style>
</head>
<body>
  <header class="header">
    <button class="icon-btn" onclick="location.href='pagina_principal.php'" aria-label="Volver">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </button>
    <button class="icon-btn" onclick="toggleMenu()" aria-label="Menú">
      <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect y="1" width="20" height="2.6" rx="1" fill="white" />
        <rect y="6" width="20" height="2.6" rx="1" fill="white" />
        <rect y="11" width="20" height="2.6" rx="1" fill="white" />
      </svg>
    </button>
    <nav id="dropdownMenu" class="dropdown-menu hidden">
      <img src="/../../imagenes/user_icon.png" alt="Foto de perfil" class="profile-pic" />
      <ul>
        <li><a href="perfil_usuario.php">Perfil</a></li>
        <li><a href="pagina_principal.php">Inicio</a></li>
        <li><a href="ajustes_perfil.php">Ajustes</a></li>
        <li><a href="procesar_login.php?action=logout">Cerrar sesión</a></li>
      </ul>
    </nav>
  </header>

  <section class="song-info">
    <img src="<?= htmlspecialchars($cancion['caratula_img']) ?>" alt="Carátula de <?= htmlspecialchars($cancion['nombre']) ?>">
    <h2><?= htmlspecialchars($cancion['nombre']) ?></h2>
    <p>Artista: <?= htmlspecialchars($cancion['artista']) ?></p>
    <p>Género: Pop</p>
    <p>Duración: <?= htmlspecialchars($cancion['duracion']) ?></p>
    <p>Valoración: 
      <?php 
      $estrellas = str_repeat('★', (int)$cancion['valoracion']) . str_repeat('☆', 5 - (int)$cancion['valoracion']);
      echo $estrellas;
      ?>
    </p>
  </section>

  <section class="section">
    <h3>Canciones similares</h3>
    <div class="card-row">
      <?php foreach ($similares as $similar): ?>
      <div class="card">
        <a href="detalles_cancion.php?cancion=<?= urlencode($similar['nombre']) ?>&creador=<?= urlencode($similar['creador']) ?>">
          <img src="<?= htmlspecialchars($similar['imagen']) ?>" alt="<?= htmlspecialchars($similar['titulo']) ?>">
          <p><?= htmlspecialchars($similar['titulo']) ?></p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section comments">
    <h3>Comentarios</h3>
    <?php foreach ($comentarios as $comentario): ?>
    <div class="comment">
      <h4><?= htmlspecialchars($comentario['usuario']) ?> <?= htmlspecialchars($comentario['valoracion']) ?></h4>
      <p><?= htmlspecialchars($comentario['texto']) ?></p>
    </div>
    <?php endforeach; ?>
  </section>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('dropdownMenu');
      menu.classList.toggle('hidden');
    }
  </script>
</body>
</html>