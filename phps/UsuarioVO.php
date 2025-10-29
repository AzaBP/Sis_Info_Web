<?php

class UsuarioVO {
    private $nombreId;
    private $nombre;
    private $correo;
    private $password;
    private $telefono;
    private $codigoSuscripcion;

    // Constructor vacío
    public function __construct($nombreId = null, $nombre = null, $correo = null, 
                               $password = null, $telefono = null, $codigoSuscripcion = null) {
        $this->nombreId = $nombreId;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = $password;
        $this->telefono = $telefono;
        $this->codigoSuscripcion = $codigoSuscripcion;
    }

    // Getters
    public function getNombreId() {
        return $this->nombreId;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getCodigoSuscripcion() {
        return $this->codigoSuscripcion;
    }

    // Setters
    public function setNombreId($nombreId) {
        $this->nombreId = $nombreId;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setCodigoSuscripcion($codigoSuscripcion) {
        $this->codigoSuscripcion = $codigoSuscripcion;
    }

    // Métodos auxiliares ---> ESTO DEBERÍA IR EN LA CLASES VAO
    public function toString() {
        return "UsuarioVO{" .
                "nombreId='" . $this->nombreId . "'" .
                ", nombre='" . $this->nombre . "'" .
                ", telefono=" . $this->telefono .
                ", correo='" . $this->correo . "'" .
                ", codigoSuscripcion='" . $this->codigoSuscripcion . "'" .
                '}';
    }
}
?>