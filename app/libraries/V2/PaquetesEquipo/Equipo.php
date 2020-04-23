<?php

namespace Librerias\V2\PaquetesEquipo;

use Modelos\Modelo_Equipo as Modelo;

class Equipo {

    private $DBEquipo;

    public function __construct() {
        $this->DBEquipo = new Modelo();
    }

    public function getEquipo() {
        $datos = array();
        $consulta = $this->DBEquipo->getEquipos();

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Equipo']
            ));
        }

        return $datos;
    }

    public function getEquipoCensadosAreaPunto(array $datos) {
        $datosEquipo = array();
        $consulta = $this->DBEquipo->getEquipoCensadosAreaPuntoInstalaciones($datos);

        foreach ($consulta as $value) {
            array_push($datosEquipo, array(
                'id' => $value['IdModelo'],
                'text' => $value['Equipo'],
                'serie' => $value['Serie']
            ));
        }

        return $datosEquipo;
    }

}
