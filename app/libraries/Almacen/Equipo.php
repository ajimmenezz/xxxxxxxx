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
    
    public function getRefaccionesEquipoWhere(string $where = ''){
        return $this->DBE->getRefaccionesEquipo($this->idEquipo, $where);
    }
    
    public function getRefaccionesEquipoRehabilitacion(string $where = ''){
        return $this->DBE->getRefaccionesEquipoRehabilitacion(array('idEquipo' => $this->idEquipo, 'where' => $where));
    }
    
}
