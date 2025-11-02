<?php
class PlaylistVO {
    
    private $listaId;
    private $nombreCancion;
    private $nombreCreador;

    // Constructor
    public function __construct($listaId = null, $nombreCancion = null, $nombreCreador = null) {
        $this->listaId = $listaId;
        $this->nombreCancion = $nombreCancion;
        $this->nombreCreador = $nombreCreador;
    }

    // Getters
    public function getListaId() {
        return $this->listaId;
    }

    public function getNombreCancion() {
        return $this->nombreCancion;
    }

    public function getNombreCreador() {
        return $this->nombreCreador;
    }

    // Setters
    public function setListaId($listaId) {
        $this->listaId = $listaId;
    }

    public function setNombreCancion($nombreCancion) {
        $this->nombreCancion = $nombreCancion;
    }

    public function setNombreCreador($nombreCreador) {
        $this->nombreCreador = $nombreCreador;
    }

    // toString
    public function toString() {
        return "PlaylistVO{" .
                "listaId='" . $this->listaId . "'" .
                ", nombreCancion='" . $this->nombreCancion . "'" .
                ", nombreCreador='" . $this->nombreCreador . "'" .
                '}';
    }

    // Método para comprobar igualdad basado en todos los atributos
    public function equals($obj) {
        if ($this === $obj) return true;
        if ($obj === null || get_class($this) !== get_class($obj)) return false;
        
        return $this->listaId === $obj->getListaId() &&
               $this->nombreCancion === $obj->getNombreCancion() &&
               $this->nombreCreador === $obj->getNombreCreador();
    }
}
?>