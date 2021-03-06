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

    public function getRegistrosComponente(int $componente) {
        $datos = array();
        $consulta = $this->DBCenso->getCensoComponente($componente);

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['IdModelo'],
                'text' => $value['Equipo'],
                'Modelo' => $value['Modelo'],
                'Parte' => $value['Parte'],
                'IdMarca' => $value['IdMarca'],
                'Marca' => $value['Marca'],
                'Flag' => $value['Flag']
            ));
        }
        return $datos;
    }

    public function setCensoIdServicio(array $datos) {
        $this->DBCenso->setCensoIdServicio(array(
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['idArea'],
            'IdModelo' => $datos['idModelo'],
            'Serie' => $datos['serie'],
            'Punto' => $datos['punto'],
            'Extra' => 'SN'));
    }
    
    public function deleteCenso(array $datos){
        $this->DBCenso->deleteCenso($datos);
    }

}
