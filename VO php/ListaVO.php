<?php
class ListaVO {
    
    private $listaId;

    // Constructor
    public function __construct($listaId = null) {
        $this->listaId = $listaId;
    }

    // Getter
    public function getListaId() {
        return $this->listaId;
    }

    // Setter
    public function setListaId($listaId) {
        $this->listaId = $listaId;
    }

    // toString -->  ESTO DEBERÍA IR EN LA CLASES VAO
    public function toString() {
        return "ListaVO{" .
                "ListaId='" . $this->listaId . "'" .
                '}';
    }

    // Método para comprobar igualdad basado en listaId -->  ESTO DEBERÍA IR EN LA CLASES VAO
    public function equals($obj) {
        if ($this === $obj) return true;
        if ($obj === null || get_class($this) !== get_class($obj)) return false;
        return $this->listaId !== null && $this->listaId === $obj->getListaId();
    }
}
?>