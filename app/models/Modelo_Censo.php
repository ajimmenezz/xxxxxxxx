<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Censo extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getCensoComponente(int $componente) {
        $consulta = $this->consulta('select 
                cvmoe.Id as IdModelo,
                cvmoe.Nombre as Modelo,
                cvmoe.NoParte as Parte,
                cvme.Id as IdMarca,
                cvme.Nombre as Marca,
                cvmoe.Flag,
                concat(
                    marca(marcaByModelo(cvmoe.Id)),
                    " ",
                    cvmoe.Nombre
                ) as Equipo
                from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                on cvle.Id = cvse.Linea
                inner join cat_v3_marcas_equipo cvme
                on cvse.Id = cvme.Sublinea
                inner join cat_v3_modelos_equipo cvmoe
                on cvme.Id = cvmoe.Marca WHERE cvse.Id = ' . $componente . '
                order by cvmoe.Id desc');
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
