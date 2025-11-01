<?php
require_once __DIR__ . '/../VO php/SuscripcionVO.php';

interface SuscripcionDAO {
    public function agregarSuscripcion(SuscripcionVO $suscripcion);
    public function obtenerSuscripcionPorCodigo($codigo_suscripcion);
    public function obtenerTodasLasSuscripciones();
    public function actualizarSuscripcion(SuscripcionVO $suscripcion);
    public function eliminarSuscripcion($codigo_suscripcion);
}
?>