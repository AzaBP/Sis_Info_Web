<?php
require_once 'UsuarioVO.php';

class OyenteVO extends UsuarioVO {

    // Constructor
    public function __construct($nombreId = null, $nombre = null, $correo = null, 
                               $password = null, $telefono = null, $codigoSuscripcion = null,) {
        // PRIMERO: Llamar al constructor de la clase padre
        parent::__construct($nombreId, $nombre, $correo, $password, $telefono, $codigoSuscripcion);
    }

    // Sobrescribir toString
    public function toString() {
        return "OyenteVO{" .
                "usuarioId='" . $this->getNombreId() . "'" .
                ", nombre='" . $this->getNombre() . "'" .
                ", correo='" . $this->getCorreo() . "'" .
                ", telefono='" . $this->getTelefono() . "'" .
                ", codigoSuscripcion='" . $this->getCodigoSuscripcion() . "'" .
                '}';
    }
}
?>