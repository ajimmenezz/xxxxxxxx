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
    
    public function getRegistrosComponente(int $componente){
        $datos = array();
        $consulta = $this->DBCenso->getCensoComponente($componente);
        
        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['IdModelo'],
                'text' => $value['Equipo'],
                'Modelo' => $value['Modelo'],
                'Parte' => $value['Parte'],
                'Marca' => $value['Marca']                   
            ));
        }
        return $datos;       
    }

}
