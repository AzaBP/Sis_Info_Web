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

// Convertir VO a array para facilitar inyección en HTML
$cancion = [
    'nombre' => $cancionVO->getNombre(),
    'artista' => $cancionVO->getNombreCreador(),
    'duracion' => $cancionVO->getDuracion(),
    'valoracion' => $cancionVO->getValoracion(),
    'caratula_img' => '../imagenes/Quevedo_album_20.jpg' 
];

// 4. Cargar datos auxiliares (Comentarios y Canciones Similares)
// Comentarios: placeholder (no hay DAO implementado)
$comentarios = [
    ['usuario' => 'Usuario1', 'valoracion' => '★★★★☆', 'texto' => 'Me encanta esta canción, tiene muy buen ritmo.'],
    ['usuario' => 'Usuario2', 'valoracion' => '★★★☆☆', 'texto' => 'Buena, pero podría ser mejor.']
    // TODO: Implementar ComentarioDAO y cargar desde BD
];

// Canciones Similares (Placeholder)
$similares = [
    ['titulo' => 'Similar 1', 'imagen' => '../imagenes/ballads.jpg', 'nombre' => 'Similar A', 'creador' => 'Art A'],
    ['titulo' => 'Similar 2', 'imagen' => '../imagenes/nectar.jpg', 'nombre' => 'Similar B', 'creador' => 'Art B'],
    ['titulo' => 'Similar 3', 'imagen' => '../imagenes/Robe.jpg', 'nombre' => 'Similar C', 'creador' => 'Art C']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de <?= htmlspecialchars($cancion['nombre']) ?></title>
    <!-- Inclusión de estilos VMusic -->
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body class="bg-900">
    <!-- Menú desplegable y navegación -->
    <header class="app-header">
        <div class="header-right">
            <!-- Icono de perfil y menú -->
            <button class="icon-btn profile" onclick="toggleMenu()" aria-label="Menú">
                <img src="../images/user_icon.png" alt="Foto de perfil">
            </button>
            <div id="dropdownMenu" class="dropdown-menu hidden">
                <ul>
                    <li><a href="perfil_usuario.php">Perfil</a></li>
                    <li><a href="pagina_principal.php">Inicio</a></li>
                    <li><a href="ajustes_perfil.php">Ajustes</a></li>
                    <li><a href="procesar_login.php?action=logout">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="main-wrap container" style="max-width: 800px; margin-top: 40px;">
        <div class="central-section">
            <div class="top-link">
                <a href="pagina_principal.php">← Volver al Catálogo</a>
            </div>

            <!-- Información resumida y características -->
            <section class="song-details" style="display: flex; gap: 30px; align-items: flex-start;">
                <img src="<?= htmlspecialchars($cancion['caratula_img']) ?>" alt="Carátula de la canción" style="width: 200px; height: 200px; border-radius: 8px;">
                
                <div class="meta-info">
                    <h1><?= htmlspecialchars($cancion['nombre']) ?></h1>
                    
                    <p>Artista: <strong><?= htmlspecialchars($cancion['artista']) ?></strong></p>
                    <p>Género: <strong>Rock</strong></p> <!-- Placeholder, ya que no está en VO -->
                    <p>Duración: <strong><?= htmlspecialchars($cancion['duracion']) ?></strong></p>
                    
                    <!-- Valoración media -->
                    <p>Valoración media: 
                        <?php 
                        // Simular estrellas basadas en $cancion['valoracion'] (0 a 5)
                        $estrellas = str_repeat('★', (int)$cancion['valoracion']) . str_repeat('☆', 5 - (int)$cancion['valoracion']);
                        ?>
                        <strong style="color: gold;"><?= $estrellas ?></strong>
                    </p>

                    <!-- Botón de reproducción directa -->
                    <button class="btn btn-login" style="margin-top: 15px;">▶ Reproducir ahora</button>
                </div>
            </section>

            <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0;">

            <!-- SECCIÓN CANCIONES SIMILARES -->
            <section class="similar-songs">
                <h2 class="section-title">Canciones similares</h2>
                <div class="albums-grid" style="grid-template-columns: repeat(3, 1fr);">
                    <?php foreach ($similares as $similar): ?>
                        <a href="detalles_cancion.php?cancion=<?= urlencode($similar['nombre']) ?>&creador=<?= urlencode($similar['creador']) ?>" class="album-card">
                            <img src="<?= htmlspecialchars($similar['imagen']) ?>" alt="<?= htmlspecialchars($similar['titulo']) ?>" class="album-cover">
                            <div class="album-title"><?= htmlspecialchars($similar['titulo']) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0;">

            <!-- SECCIÓN COMENTARIOS / OPINIONES -->
            <section class="comments-section">
                <h2 class="section-title">Comentarios y Opiniones</h2>
                <?php if (empty($comentarios)): ?>
                    <p>Sé el primero en dejar una opinión.</p>
                <?php else: ?>
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="comment-item" style="border-bottom: 1px dashed rgba(255,255,255,0.08); padding: 10px 0;">
                            <p><strong><?= htmlspecialchars($comentario['usuario']) ?></strong> <span style="color: gold;"><?= htmlspecialchars($comentario['valoracion']) ?></span></p>
                            <p style="font-size: 0.9rem; margin-top: 5px;"><?= htmlspecialchars($comentario['texto']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- Formulario para dejar un nuevo comentario (placeholder, requiere procesar_comentario.php) -->
                <form action="procesar_comentario.php" method="post" style="margin-top: 20px;">
                    <!-- Incluir campos ocultos para el ID de la canción -->
                    <input type="hidden" name="nombre_cancion" value="<?= htmlspecialchars($nombreCancion) ?>">
                    <input type="hidden" name="nombre_creador" value="<?= htmlspecialchars($nombreCreador) ?>">
                    <!-- CSRF y otros campos de seguridad -->
                    <textarea name="texto_comentario" placeholder="Escribe tu opinión aquí..." required rows="4" style="width: 100%; padding: 10px;"></textarea>
                    <button type="submit" class="btn">Enviar Comentario</button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>