<?php

namespace Librerias\V2\PaquetesTicket;

use Modelos\Modelo_NodoRedes as Modelo;

class Nodo {

    private $DBNodo;    
    private $idServicio;

    public function __construct(string $idServicio) {
        $this->DBNodo = new Modelo();                           
        $this->idServicio = $idServicio;
    }
    
    public function setNodo(array $datos) {
        $idNodo = $this->DBNodo->setNodo($this->idServicio, $datos);
        $this->DBNodo->setMaterialNodo($idNodo,$datos['material']);
    }
    
    public function updateNodo(array $datos) {
        
    }
    
    public function delateNodo(array $datos) {
        
    }
    
    public function getNodos() {
        
    }

}
