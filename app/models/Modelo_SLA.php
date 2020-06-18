<?php

namespace Modelos;

use Librerias\Modelos\Base as Base;

/**
 * Description of Modelo_Tareas
 *
 * @author Freddy
 */
class Modelo_SLA extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function consultaGral(string $consulta) {
        $resultado = $this->consulta($consulta);
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function getFoliosCreacionInicio() {
        $consulta = $this->consulta("SELECT * FROM (SELECT 
                                        T.FechaInicio, ts.FechaCreacion, ts.Folio
                                    FROM
                                        (SELECT 
                                            tst.FechaInicio, tst.IdSolicitud
                                        FROM
                                            t_servicios_ticket tst
                                        WHERE
                                            tst.FechaInicio IS NOT NULL
                                        GROUP BY IdSolicitud
                                        ORDER BY tst.IdSolicitud , FechaInicio ASC) AS T
                                            INNER JOIN
                                        t_solicitudes ts ON T.IdSolicitud = ts.Id
                                    WHERE
                                        ts.Folio IS NOT NULL AND ts.Folio <> 0
                                            AND ts.FechaCreacion BETWEEN '2019-01-01 00:00:00' AND NOW()
                                            ORDER BY ts.Folio, FechaCreacion ASC) AS TABLAFINAL
                                            GROUP BY Folio");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function sla(array $datos = NULL) {
        $filtroFecha = " AND ts.FechaCreacion BETWEEN '2019-01-01 00:00:00' AND NOW() ";
        
        if(!empty($datos)){
            $filtroFecha = " AND ts.FechaCreacion BETWEEN '" . $datos['desde'] . " 00:00:00' AND '" . $datos['hasta'] . " 23:59:59' ";
        }
        
        $consulta = $this->consulta("SELECT * FROM(SELECT 
                                        TIME_TO_SEC(TIME(tct.TiempoTranscurrido)) AS SegundosTiempoTranscurrido,
                                        tct.TiempoTranscurrido,
                                        ts.Id AS IdSolicitud,
                                        TServicio.FechaInicio,  ts.Folio, TServicio.Tecnico, ts.IdPrioridad, IF(ts.IdSucursal = 0, sucursal(TServicio.IdSucursalServicio), sucursal(ts.IdSucursal )) AS Sucursal,
                                        IF(ts.IdSucursal = 0, TServicio.IdSucursalServicio, ts.IdSucursal ) AS IdSucursal,
                                        nombreUsuario(ts.Atiende) AS AtiendeSolicitud,
                                        ts.FechaCreacion,
                                        TServicio.FechaCreacion AS FechaCreacionServicio
                                        FROM (SELECT  tst.FechaInicio, tst.IdSolicitud, nombreUsuario(tst.Atiende) AS Tecnico, tst.IdSucursal AS IdSucursalServicio, tst.FechaCreacion 
                                                                        FROM t_servicios_ticket AS tst
                                                                        WHERE
                                                                                tst.FechaInicio IS NOT NULL
                                                                        GROUP BY IdSolicitud
                                                                        ORDER BY tst.IdSolicitud , FechaInicio ASC) 
                                        AS TServicio
                                        INNER JOIN
                                                t_solicitudes ts ON TServicio.IdSolicitud = ts.Id
                                        INNER JOIN t_cheking_ticket tct
                                        ON tct.Folio = ts.Folio
                                        WHERE
                                                ts.Folio IS NOT NULL AND ts.Folio <> 0
                                        " . $filtroFecha . "
                                        ORDER BY ts.Folio, ts.FechaCreacion ASC) AS TABLAFINAL
                                        GROUP BY Folio");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function getCheking_Ticket(string $folio) {
        $consulta = $this->consulta("SELECT * FROM t_cheking_ticket WHERE Folio = " . $folio);

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function setCheking_Ticket(array $datos) {
        $this->insertar("t_cheking_ticket", $datos);
    }

}
