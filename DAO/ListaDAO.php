<?php
require_once 'ListaVO.php';

interface ListaDAO {
    public function agregarLista(ListaVO $lista);
    public function obtenerListaPorId($lista_id);
    public function obtenerTodasLasListas();
    public function actualizarLista(ListaVO $lista);
    public function eliminarLista($lista_id);
}
?>