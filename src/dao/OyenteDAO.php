<?php
require_once __DIR__ . '/../vo/OyenteVO.php';

interface OyenteDAO {
    public function agregarOyente(OyenteVO $oyente);
    public function obtenerOyentePorId($usuario_id);
    public function obtenerTodosLosOyentes();
    public function actualizarOyente(OyenteVO $oyente);
    public function eliminarOyente($usuario_id);
}
?>