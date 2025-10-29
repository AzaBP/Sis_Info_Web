<?php
require_once 'UsuarioVO.php';

class CreadorVO extends UsuarioVO {

    // Constructor
    public function __construct($nombreId = null, $nombre = null, $correo = null, 
                               $password = null, $telefono = null, $codigoSuscripcion = null) {
        // Llamar al constructor de la clase padre
        parent::__construct($nombreId, $nombre, $correo, $password, $telefono, $codigoSuscripcion);
    }

    // Sobrescribir toString ----> ESTO DEBERÍA IR EN LA CLASES VAO
    public function toString() {
        return "CreadorVO{" .
                "usuarioId='" . $this->getNombreId() . "'" .
                ", nombre='" . $this->getNombre() . "'" .
                ", correo='" . $this->getCorreo() . "'" .
                ", telefono='" . $this->getTelefono() . "'" .
                ", codigoSuscripcion='" . $this->getCodigoSuscripcion() . "'" .
                '}';
    }
}
?>