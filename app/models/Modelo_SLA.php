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

    public function sla(array $datos = NULL) {
        $filtroFecha = " where ts.FechaCreacion >= '2019-01-01 00:00:00' ";

        if (!empty($datos)) {
            if ($datos['hasta'] !== '') {
                $filtroFecha = " WHERE ts.FechaCreacion BETWEEN '" . $datos['desde'] . " 00:00:00' AND '" . $datos['hasta'] . " 23:59:59' ";
            } else {
                $filtroFecha = " where ts.FechaCreacion >= '" . $datos['desde'] . " 00:00:00' ";
            }
        }

        $consulta = $this->consulta("select
                                    workingHours(ts.FechaCreacion, tst.FechaInicio) as SegundosTiempoTranscurrido,
                                    sec_to_time(workingHours(ts.FechaCreacion, tst.FechaInicio)) as TiempoTranscurrido,
                                    sec_to_time(TIMESTAMPDIFF(SECOND,ts.FechaCreacion,tst.FechaCreacion)) as IntervaloSolicitudServicioCreacion,
                                    ts.Id as IdSolicitud,
                                    tst.FechaInicio,
                                    ts.Folio,
                                    nombreUsuario(tst.Atiende) as Tecnico,
                                    ts.IdPrioridad,
                                    if(ts.IdSucursal = 0, sucursal(tst.IdSucursal), sucursal(ts.IdSucursal)) as Sucursal,
                                    if(ts.IdSucursal = 0, tst.IdSucursal, ts.IdSucursal) as IdSucursal,
                                    if(ts.IdSucursal = 0, (SELECT zona(IdRegionCliente) FROM cat_v3_sucursales WHERE Id = tst.IdSucursal), (SELECT zona(IdRegionCliente) FROM cat_v3_sucursales WHERE Id = ts.IdSucursal)) as Zona,
                                    nombreUsuario(ts.Atiende) as AtiendeSolicitud,
                                    ts.FechaCreacion,
                                    tst.FechaCreacion as FechaCreacionServicio
                                    from t_solicitudes ts
                                    inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud and tst.FechaInicio = (select MIN(FechaInicio) from t_servicios_ticket where IdSolicitud = ts.Id and FechaInicio is not null)
                                    " . $filtroFecha . "
                                    and ts.Folio is not null and ts.Folio <> 0
                                    group by Folio");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

}
