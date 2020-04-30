<?php

namespace Modelos;

use \Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Equipo extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getEquipos() {
        $consulta = $this->consulta('SELECT * FROM v_equipos');
        return $consulta;
    }

    public function getEquipoCensadosAreaPunto(array $datos) {
        $consulta = $this->consulta('SELECT 
                                          tc.IdModelo,
                                          tc.Serie,
                                          tc.Extra,
                                          (SELECT Equipo FROM v_equipos WHERE Id = tc.IdModelo) AS Equipo
                                          FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                          on tc.IdArea = cvaa.Id
                                        WHERE tc.IdServicio = (select MAX(tcg.IdServicio) 
                                        from t_censos_generales tcg 
                                        inner join t_servicios_ticket tst
                                        on tcg.IdServicio = tst.Id
                                        WHERE tcg.IdSucursal = "' . $datos['sucursal'] . '"
                                        AND tc.IdArea = "' . $datos['area'] . '"
                                        AND tc.Punto = "' . $datos['punto'] . '"
                                        and tst.IdEstatus = 4)
                                        ORDER BY Equipo ASC');
        return $consulta;
    }
    
    public function getEquipoCensadosAreaPuntoInstalaciones(array $datos) {
        $consulta = $this->consulta('SELECT 
                                          tc.IdModelo,
                                          tc.Serie,
                                          tc.Extra,
                                          (SELECT Equipo FROM v_equipos WHERE Id = tc.IdModelo) AS Equipo
                                          FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                          on tc.IdArea = cvaa.Id
                                        WHERE tc.IdServicio = (select MAX(tcg.IdServicio) 
                                        from t_censos_generales tcg 
                                        inner join t_servicios_ticket tst
                                        on tcg.IdServicio = tst.Id
                                        WHERE tcg.IdSucursal = "' . $datos['sucursal'] . '"
                                        AND tc.IdArea = "' . $datos['area'] . '"
                                        AND tc.Punto = "' . $datos['punto'] . '"
                                        and tst.IdEstatus = 4)
                                        AND tc.IdModelo  NOT IN(SELECT IdModelo FROM t_instalaciones_equipos_poliza WHERE IdOperacion IN(2,3) AND IdServicio = "' . $datos['servicio'] . '")
                                        ORDER BY Equipo ASC');
        return $consulta;
    }

}
