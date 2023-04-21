<?php
class ArchivoPDF {
    private $nombre;
    private $tamano;
    private $ruta;

    function __construct($nombre, $tamano, $ruta) {
        $this->nombre = $nombre;
        $this->tamano = $tamano;
        $this->ruta = $ruta;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function getTamano() {
        return $this->tamano;
    }

    function setTamano($tamano) {
        $this->tamano = $tamano;
    }

    function getRuta() {
        return $this->ruta;
    }

    function setRuta($ruta) {
        $this->ruta = $ruta;
    }
}
?>
