<?php

class UsuarioVO {
    private $usuario_id;
    private $nombre;
    private $correo;
    private $password;
    private $telefono;
    private $codigo_suscripcion;

    public function __construct($usuario_id = null, $nombre = null, $correo = null, 
                                $password = null, $telefono = null, $codigo_suscripcion = null) {
        $this->usuario_id = $usuario_id;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = $password;
        $this->telefono = $telefono;
        $this->codigo_suscripcion = $codigo_suscripcion;
    }

    // Getters
    public function getUsuarioId() { return $this->usuario_id; }
    public function getNombre() { return $this->nombre; }
    public function getCorreo() { return $this->correo; }
    public function getPassword() { return $this->password; }
    public function getTelefono() { return $this->telefono; }
    public function getCodigoSuscripcion() { return $this->codigo_suscripcion; }

    // Setters
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }  
    public function setNombre($nombre) { $this->nombre = $nombre;}
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setPassword($password) { $this->password = $password; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setCodigoSuscripcion($codigo_suscripcion) { $this->codigo_suscripcion = $codigo_suscripcion; }
}

?>