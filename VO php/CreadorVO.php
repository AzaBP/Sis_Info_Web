<?php
require_once 'UsuarioVO.php';

class CreadorVO extends UsuarioVO {
    private $biografia;
    private $numero_seguidores;

    // Constructor
    public function __construct($usuario_id = null, $nombre = null, $correo = null, 
                               $password = null, $telefono = null, $codigoSuscripcion = null,
                               $biografia = null, $numero_seguidores = 0) {
        // Llamar al constructor de la clase padre
        parent::__construct($usuario_id, $nombre, $correo, $password, $telefono, $codigoSuscripcion);
        $this->biografia = $biografia;
        $this->numero_seguidores = $numero_seguidores;
    }

    // Getters
    public function getBiografia() { return $this->biografia; }
    public function getNumeroSeguidores() { return $this->numero_seguidores; }

    // Setters
    public function setBiografia($biografia) { $this->biografia = $biografia; }
    public function setNumeroSeguidores($numero_seguidores) { $this->numero_seguidores = $numero_seguidores; }
}
?>