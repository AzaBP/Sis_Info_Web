<?php

class CancionVO {

    // Constantes
    const valoración_MINIMA = 0;
    const valoración_MAXIMA = 5;

    private $nombre;
    private $nombre_creador;
    private $duración;
    private $valoración;

    // Constructor con validación de valoración
    public function __construct($nombre = null, $nombre_creador = null, $duración = null, $valoración = null) {
        $this->nombre = $nombre;
        $this->nombre_creador = $nombre_creador;
        $this->duración = $duración;
        $this->setvaloración($valoración);
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getNombreCreador() { return $this->nombre_creador; }
    public function getduración() { return $this->duración; }
    public function getvaloración() { return $this->valoración; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setNombreCreador($nombre_creador) { $this->nombre_creador = $nombre_creador; } 
    public function setduración($duración) { $this->duración = $duración; }

    public function setvaloración($valoración) {
        if ($valoración !== null && 
           ($valoración < self::valoración_MINIMA || $valoración > self::valoración_MAXIMA)) {
            error_log("La valoración debe estar entre " . self::valoración_MINIMA . 
            " y " . self::valoración_MAXIMA . ". \nvaloración ". $valoración . " no válida.");
            $this->valoración = null;
        } else {
            $this->valoración = $valoración;
        }
    }

}
?>