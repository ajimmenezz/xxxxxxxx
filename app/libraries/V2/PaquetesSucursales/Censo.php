<?php

namespace Librerias\V2\PaquetesSucursales;

use Librerias\V2\PaquetesSucursales\interfaces\Sucursal as Sucursal;
use Modelos\Modelo_Censo as Modelo;

class Censo {
    
    private $idSucursal;
    private $DBCenso;

    public function __construct(Sucursal $sucursal) {
        $this->idSucursal = $sucursal->getId();
        $this->DBCenso = new Modelo();
    }
    
    public function getRegistrosComponente(string $componente){
        $datos = array();
        $consulta = $this->DBCenso->getCensoComponente($this->idSucursal, $componente);
        
        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Equipo'],
                'idArea' => $value['IdArea'],
                'area' => $value['Area'],
                'modelo' => $value['IdModelo'],
                'serie' => $value['Serie']                    
            ));
        }
        return $datos;       
    }

}
