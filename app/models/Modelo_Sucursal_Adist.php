<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Sucursal_Adist extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatos(string $idSucursal) {
        
    }

    public function getAreas() {
        $consulta = $this->consulta('SELECT 
                                        Id, 
                                        Nombre 
                                    FROM cat_v3_areas_atencion 
                                    WHERE IdCliente = 1 
                                    AND Flag = 1;');
        return $consulta;
    }

    public function getAreasSucursal(string $sucursal) {
        $consulta = $this->consulta('SELECT 
                                        tcapr.*,
                                            areaAtencion(tcapr.IdArea) AS AreaAtencion
                                    FROM
                                        t_censos_areas_puntos_revisados tcapr
                                            INNER JOIN
                                        t_servicios_ticket tst ON tst.Id = tcapr.IdServicio
                                    WHERE
                                        IdServicio = (select 
                                                MAX(tcg.IdServicio)
                                            from
                                                t_censos_generales tcg
                                                    inner join
                                                t_servicios_ticket tst ON tcg.IdServicio = tst.Id
                                            WHERE
                                                tcg.IdSucursal = ' . $sucursal . '
                                                    and tst.IdEstatus = 4)
                                    GROUP BY tcapr.IdArea
                                    ORDER BY AreaAtencion');
        return $consulta;
    }

    public function getAreasPuntoSucursal(string $sucursal) {
        $consulta = $this->consulta('SELECT 
                                        tcapr.*,
                                            areaAtencion(tcapr.IdArea) AS AreaAtencion
                                    FROM
                                        t_censos_areas_puntos_revisados tcapr
                                            INNER JOIN
                                        t_servicios_ticket tst ON tst.Id = tcapr.IdServicio
                                    WHERE
                                        IdServicio = (select 
                                                MAX(tcg.IdServicio)
                                            from
                                                t_censos_generales tcg
                                                    inner join
                                                t_servicios_ticket tst ON tcg.IdServicio = tst.Id
                                            WHERE
                                                tcg.IdSucursal = ' . $sucursal . '
                                                    and tst.IdEstatus = 4)
                                    GROUP BY IdArea, Punto
                                    ORDER BY AreaAtencion, Punto');
        return $consulta;
    }

    public function getServicioUltimoCensoSucursal(string $sucursal) {
        $consulta = $this->consulta('SELECT 
                                        tcapr.IdServicio
                                    FROM
                                        t_censos_areas_puntos_revisados tcapr
                                    WHERE
                                        IdServicio = (SELECT 
                                                MAX(tcg.IdServicio)
                                            FROM
                                                t_censos_generales tcg
                                                    INNER JOIN
                                                t_servicios_ticket tst ON tcg.IdServicio = tst.Id
                                            WHERE
                                                tcg.IdSucursal = ' . $sucursal . ' AND tst.IdEstatus = 4)
                                    LIMIT 1');
        return $consulta;
    }

}
