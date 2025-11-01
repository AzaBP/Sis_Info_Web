<?php

class CancionVO {

    // Constantes
    const VALORACION_MINIMA = 0;
    const VALORACION_MAXIMA = 5;

    private $nombre;
    private $nombre_creador;
    private $duracion;
    private $valoracion;

    // Constructor con validación de valoración
    public function __construct($nombre = null, $nombre_creador = null, $duracion = null, $valoracion = null) {
        $this->nombre = $nombre;
        $this->nombre_creador = $nombre_creador;
        $this->duracion = $duracion;
        $this->setValoracion($valoracion);
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getNombreCreador() { return $this->nombre_creador; }
    public function getDuracion() { return $this->duracion; }
    public function getValoracion() { return $this->valoracion; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setNombreCreador($nombre_creador) { $this->nombre_creador = $nombre_creador; } 
    public function setDuracion($duracion) { $this->duracion = $duracion; }

    public function setValoracion($valoracion) {
        if ($valoracion !== null && 
           ($valoracion < self::VALORACION_MINIMA || $valoracion > self::VALORACION_MAXIMA)) {
            error_log("La valoración debe estar entre " . self::VALORACION_MINIMA . 
            " y " . self::VALORACION_MAXIMA . ". \nValoracion ". $valoracion . " no válida.");
            $this->valoracion = null;
        } else {
            $this->valoracion = $valoracion;
        }
    }

}
?>