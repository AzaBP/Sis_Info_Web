<?php
require_once 'CancionVO.php';

interface CancionDAO {
    public function agregarCancion(CancionVO $cancion);
    public function obtenerCancionPorId($nombre, $nombre_creador);
    public function obtenerTodasLasCanciones();
    public function actualizarCancion(CancionVO $cancion);
    public function eliminarCancion($nombre, $nombre_creador);
}
?>