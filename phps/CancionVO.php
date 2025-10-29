<?php
require_once 'CreadorVO.php';

class CancionVO {
    
    private $nombre;
    private $nombreCreador;
    private $duracion;
    private $valoracion;

    // Constructor
    public function __construct($nombre = null, CreadorVO $nombreCreador = null, $duracion = null, $valoracion = null) {
        $this->nombre = $nombre;
        $this->nombreCreador = $nombreCreador;
        $this->duracion = $duracion;
        $this->setValoracion($valoracion);
    }

    // Getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getNombreCreador(): ?CreadorVO {
        return $this->nombreCreador;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    public function getValoracion() {
        return $this->valoracion;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setNombreCreador(CreadorVO $nombreCreador) {
        $this->nombreCreador = $nombreCreador;
    }

    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    public function setValoracion($valoracion) {
        if ($valoracion !== null && $valoracion >= 0 && $valoracion <= 5) {
            $this->valoracion = $valoracion;
        } else if ($valoracion !== null) {
            throw new InvalidArgumentException("La valoración debe estar entre 0 y 5.");
        } else {
            $this->valoracion = null;
        }
    }

    // toString
    public function toString() {
        return "CancionVO{" .
                "nombre='" . $this->nombre . "'" .
                ", nombre creador='" . ($this->nombreCreador ? $this->nombreCreador->getNombre() : "null") . "'" .
                ", duracion='" . $this->duracion . "'" .
                ", valoracion=" . $this->valoracion .
                '}';
    }
}
?>