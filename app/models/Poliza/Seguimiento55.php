<?php

namespace Modelos\Poliza;

use Librerias\Modelos\Base as Modelo_Base;

class Seguimiento55 extends Modelo_Base
{
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function serviceInfo($serviceId = null)
    {
        if (is_null($serviceId) || $serviceId <= 0) {
            return ['code' => 500, 'message' => 'Service ID is not valid. Please send a valid Service ID (This field must be a number)'];
        } else {
            $request = $this->consulta("
            select
            tst.*
            from t_servicios_ticket tst
            where tst.Id = '" . $serviceId . "'");
            if (!empty($request)) {
                return ['code' => 200, 'message' => 'Sending service Info', 'data' => $request[0]];
            } else {
                return ['code' => 500, 'message' => 'Service ID is not exists'];
            }
        }
    }

    public function updateSOInfo($serviceId, $branchId)
    {
        return $this->consulta("
        select
        tc.Id,
        concat(cs.Dominio,caa.ClaveCorta,LPAD(Punto,2,0)) as Terminal,
        caa.Nombre as Area,
        tc.Punto,
        cle.Nombre as Linea,
        cse.Nombre as Sublinea,
        cmae.Nombre as Marca,
        cme.Nombre as Modelo,
        tc.Serie,
        tas.Id as IdRegistroActualizacion,
        tas.Actualizado
        from t_censos tc
        inner join cat_v3_areas_atencion caa on tc.IdArea = caa.Id
        inner join cat_v3_sucursales cs on cs.Id = '" . $branchId . "'
        inner join cat_v3_modelos_equipo cme on cme.Id = tc.IdModelo
        inner join cat_v3_marcas_equipo cmae on cmae.Id = cme.Marca
        inner join cat_v3_sublineas_equipo cse on cse.Id = cmae.Sublinea
        inner join cat_v3_lineas_equipo cle on cle.Id = cse.Linea
        left join t_actualizacion_so tas on tas.IdServicio = '" . $serviceId . "' and tas.IdCenso = tc.Id
        where tc.IdServicio = (select MAX(Id) from t_servicios_ticket where IdTipoServicio = 11 and IdEstatus = 4 and IdSucursal = '" . $branchId . "')
        and cle.Id = 1
        and areaAtencion(tc.IdArea) not like '%PLASMAS%'
        order by Area, Punto");
    }

    public function getImpediments()
    {
        return $this->consulta("select * from cat_v3_actualizacion_so_impedimentos");
    }

    public function updateSOImpediments($serviceId)
    {
        return $this->consulta("select * from t_actualizacion_so_impedimentos where IdServicio = '" . $serviceId . "'");
    }
}
