<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Mantenimiento extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getAntesDespues(int $servicio) {
        $consulta = $this->consulta('SELECT *,
                                            areaAtencion(IdArea) AS Area
                                        FROM t_mantenimientos_antes_despues 
                                        WHERE IdServicio = "' . $servicio . '"
                                        ORDER BY Area, Punto ASC');

        return $consulta;
    }
    
    public function getProblemasEquipo(int $servicio) {
        $consulta = $this->consulta('SELECT 
                                                        *,
                                            areaAtencion(IdArea) AS Area,
                                            (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) AS Equipo
                                        FROM t_mantenimientos_problemas_equipo 
                                        WHERE IdServicio = "' . $servicio . '"
                                        ORDER BY Area, Punto, Equipo, Serie ASC');

        return $consulta;
    }
    
    public function getEquiposFaltante(int $servicio) {
        $consulta = $this->consulta('SELECT 
                                            *,
                                            areaAtencion(IdArea) AS Area,
                                            CASE tmef.TipoItem
                                                    WHEN 1 THEN "Equipo"
                                                    WHEN 2 THEN "Material"
                                                    WHEN 3 THEN "Refacción"
                                                END as NombreItem, 
                                                CASE tmef.TipoItem
                                                    WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = tmef.IdModelo)
                                                    WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = tmef.IdModelo)
                                                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tmef.IdModelo)
                                                END as Equipo
                                        FROM t_mantenimientos_equipo_faltante tmef  
                                        WHERE tmef.IdServicio = "' . $servicio . '"
                                        ORDER BY Area, tmef.Punto ASC');

        return $consulta;
    }
    
    public function getProblemasAdicionales(int $servicio) {
        $consulta = $this->consulta('SELECT 
                                            *,
                                            areaAtencion(IdArea) AS Area
                                        FROM t_mantenimientos_problemas_adicionales
                                        WHERE IdServicio = "' . $servicio . '"
                                        ORDER BY Area, Punto ASC');
        
        return $consulta;
    }

}
