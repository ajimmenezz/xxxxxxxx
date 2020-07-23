<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Censo extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getCensoComponente(string $idSucursal, string $componente)
    {
        $consulta = $this->ejecutaFuncion('call getInventoryFromSucursalItem(' . $idSucursal . ',"' . $componente . '")');
        return $consulta;
    }

    public function setCensoIdServicio(array $datos) {
        $this->insertar('INSERT INTO t_censos (IdServicio,IdArea,IdModelo,Punto,Serie,Extra)VALUES(
                        "' . $datos['IdServicio'] . '",
                        "' . $datos['IdArea'] . '",
                        "' . $datos['IdModelo'] . '",
                        "' . $datos['Punto'] . '",
                        "' . $datos['Serie'] . '",
                        "' . $datos['Extra'] . '")');
    }

    public function deleteCenso(array $datos) {
        $this->query('DELETE FROM t_censos
                        WHERE 
                        IdServicio = ' . $datos['idServicio'] . '
                        AND IdArea = ' . $datos['idArea'] . '
                        AND IdModelo = ' . $datos['idModelo'] . '
                        AND Punto = ' . $datos['punto'] . '
                        AND Serie = "' . $datos['serie'] . '"');
    }

}
