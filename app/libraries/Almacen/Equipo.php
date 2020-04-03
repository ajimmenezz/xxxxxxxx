<?php

namespace Librerias\Almacen;

use Modelos\Modelo_Equipo as Modelo;

class Equipo {

    private $idEquipo;
    private $DBE;
    
    public function __construct(string $idEquipo) {
        $this->idEquipo = $idEquipo;
        $this->DBE = new Modelo;
    }
    
    public function getRefaccionesEquipo(){
        return $this->DBE->getRefaccionesEquipo($this->idEquipo);
    }

}
