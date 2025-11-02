<?php
require_once __DIR__ . '/../vo/CreadorVO.php';

interface CreadorDAO {
    public function agregarCreador(CreadorVO $creador);
    public function obtenerCreadorPorId($usuario_id);
    public function obtenerTodosLosCreadores();
    public function actualizarCreador(CreadorVO $creador);
    public function eliminarCreador($usuario_id);
}
?>