<?php

// Configuración
require_once 'Database.php';

// VOs
require_once 'model/UsuarioVO.php';
require_once 'model/CreadorVO.php';
require_once 'model/OyenteVO.php';
require_once 'model/CancionVO.php';
require_once 'model/SuscripcionVO.php';
require_once 'model/ListaVO.php';
require_once 'model/PlaylistVO.php';

// DAOs
require_once 'dao/UsuarioDAO.php';
require_once 'dao/UsuarioDAOImpl.php';
require_once 'dao/CreadorDAO.php';
require_once 'dao/CreadorDAOImpl.php';
require_once 'dao/OyenteDAO.php';
require_once 'dao/OyenteDAOImpl.php';
require_once 'dao/CancionDAO.php';
require_once 'dao/CancionDAOImpl.php';
require_once 'dao/SuscripcionDAO.php';
require_once 'dao/SuscripcionDAOImpl.php';
require_once 'dao/ListaDAO.php';
require_once 'dao/ListaDAOImpl.php';
require_once 'dao/PlaylistDAO.php';
require_once 'dao/PlaylistDAOImpl.php';

// CREAR CONEXIÓN
echo "<h2> PRUEBA COMPLETA SISTEMA VMUSIC </h2>";
$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("No se pudo conectar a la base de datos.");
}

echo "Conexión establecida correctamente. <br><br>";


// 1. PRUEBA SUSCRIPCIONES
echo "<h3>1. Prueba Suscripciones </h3>";
$suscripcionDAO = new SuscripcionDAOImpl($conn);

// Suscripciones de prueba
$suscripcionBasica = new SuscripcionVO(0.0, "Básica", "basica_001");
$suscripcionPremium = new SuscripcionVO(9.99, "Premium", "premium_001");

if ($suscripcionDAO->agregarSuscripcion($suscripcionBasica)) {
    echo "Suscripción Básica insertada. <br>";
} else {
    echo "Error insertando Suscripción Básica. <br>";
}

if ($suscripcionDAO->agregarSuscripcion($suscripcionPremium)) {
    echo "Suscripción Premium insertada. <br>";
} else {
    echo "Error insertando Suscripción Premium. <br>";
}


// 2. PRUEBA USUARIOS
echo "<h3>2. Prueba Usuarios </h3>";
$usuarioDAO = new UsuarioDAOImpl($conn);

// Usuarios de prueba
$usuario1 = new UsuarioVO("user001", "Juan Pérez", "juan@email.com", "pass123", 123456789, "basica_001");
$usuario2 = new UsuarioVO("user002", "María García", "maria@email.com", "pass456", 987654321, "premium_001");

if ($usuarioDAO->agregarUsuario($usuario1)) {
    echo "Usuario Juan insertado. <br>";
} else {
    echo "Error insertando usuario Juan. <br>";
}

if ($usuarioDAO->agregarUsuario($usuario2)) {
    echo "Usuario María insertado. <br>";
} else {
    echo "Error insertando usuario María. <br>";
}


// 3. PRUEBA CREADORES
echo "<h3>3. Prueba Creadores </h3>";
$creadorDAO = new CreadorDAOImpl($conn);

// Creador de prueba
$creador1 = new CreadorVO("creador001", "Artista Uno", "artista1@email.com", "pass123", 111111111, "premium_001", "Biografía del artista", 1500);

if ($creadorDAO->agregarCreador($creador1)) {
    echo "Creador 'Artista Uno' insertado. <br>";
} else {
    echo "Error insertando creador. <br>";
}


// 4. PRUEBA CANCIONES
echo "<h3>4. Prueba Canciones </h3>";
$cancionDAO = new CancionDAOImpl($conn);

// Canciones de prueba
$cancion1 = new CancionVO("Canción Demo 1", "creador001", "03:45", 4);
$cancion2 = new CancionVO("Canción Demo 2", "creador001", "04:20", 5);

if ($cancionDAO->agregarCancion($cancion1)) {
    echo "Cancion 1 insertada. <br>";
} else {
    echo "Error insertando cancion 1. <br>";
}

if ($cancionDAO->agregarCancion($cancion2)) {
    echo "Cancion 2 insertada. <br>";
} else {
    echo "Error insertando cancion 2. <br>";
}


// 5. PRUEBA LISTAS Y PLAYLISTS
echo "<h3>5. Prueba Listas y Playlists </h3>";

$listaDAO = new ListaDAOImpl($conn);
$playlistDAO = new PlaylistDAOImpl($conn);

// Crear lista
$lista1 = new ListaVO("lista_001");
if ($listaDAO->agregarLista($lista1)) {
    echo "Lista creada. <br>";
} else {
    echo "Error creando lista. <br>";
}

// Agregar canciones a playlist
$playlist1 = new PlaylistVO("lista_001", "Canción Demo 1", "creador001");
$playlist2 = new PlaylistVO("lista_001", "Canción Demo 2", "creador001");

if ($playlistDAO->agregarPlaylist($playlist1)) {
    echo "Canción 1 agregada a playlist. <br>";
} else {
    echo "Error agregando canción 1 a playlist. <br>";
}

if ($playlistDAO->agregarPlaylist($playlist2)) {
    echo "Canción 2 agregada a playlist. <br>";
} else {
    echo "Error agregando canción 2 a playlist.<br>";
}


// 6. PRUEBAS DE CONSULTA
echo "<h3>6. Pruebas de Consulta </h3>";

// Listar todas las canciones
$canciones = $cancionDAO->obtenerTodasLasCanciones();
echo "Total canciones en sistema: " . count($canciones) . "<br>";

// Listar canciones por creador
$cancionesCreador = $cancionDAO->obtenerCancionesPorCreador("creador001");
echo "Canciones del creador001: " . count($cancionesCreador) . "<br>";

// Obtener canciones de una playlist
$cancionesPlaylist = $playlistDAO->obtenerCancionesPorLista("lista_001");
echo "Canciones en playlist lista_001: " . count($cancionesPlaylist) . "<br>";


// RESULTADO FINAL
echo "<h3>PRUEBA COMPLETADA </h3>";
echo "(Revisar la base de datos para verificar que los datos se insertaron correctamente.)<br>";

?>