<?php
require_once 'PlaylistVO.php';

interface PlaylistDAO {
    public function agregarPlaylist(PlaylistVO $playlist);
    public function obtenerPlaylistPorId($lista_id, $nombre_cancion, $nombre_creador);
    public function obtenerTodasLasPlaylists();
    public function obtenerPlaylistsPorLista($lista_id);
    public function eliminarPlaylist($lista_id, $nombre_cancion, $nombre_creador);
    public function obtenerCancionesPorLista($lista_id);
}
?>