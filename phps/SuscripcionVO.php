<?php
class SuscripcionVO {
    
    private $precio;
    private $tipo;
    private $codigo;

    // Constructor
    public function __construct($precio = null, $tipo = null, $codigo = null) {
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->codigo = $codigo;
    }

    // Getters
    public function getPrecio() {
        return $this->precio;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    // Setters
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    // Métodos auxiliares
    public function toString() {
        return "SuscripcionVO{" .
                "codigo='" . $this->codigo . "'" .
                ", suscripcionTipo='" . $this->tipo . "'" .
                ", precio=" . $this->precio .
                '}';
    }
}
?>