<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Inventario extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function statusFilters()
    {
        return $this->consulta("
        select
        tst.IdEstatus as Id,
        estatus(tst.IdEstatus) as Nombre
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        where tst.IdTipoServicio = 11
        and cs.IdCliente = 1
        and tst.IdEstatus <> 6
        group by tst.IdEstatus
        order by Nombre");
    }

    public function technicianFilters()
    {
        return $this->consulta("
        select
        tst.Atiende as Id,
        nombreUsuario(tst.Atiende) as Nombre
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        where tst.IdTipoServicio = 11
        and cs.IdCliente = 1
        group by tst.Atiende
        order by Nombre");
    }

    public function regionFilters()
    {
        return $this->consulta("
        select 
        crc.Id,
        crc.Nombre
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        left join cat_v3_regiones_cliente crc on cs.IdRegionCliente = crc.Id
        where tst.IdTipoServicio = 11
        and cs.IdCliente = 1
        group by crc.Id
        order by crc.Nombre");
    }

    public function branchFilters()
    {
        return $this->consulta("
        select 
        cs.Id,
        cs.Nombre
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        where tst.IdTipoServicio = 11
        and cs.IdCliente = 1
        group by cs.Id
        order by cs.Nombre");
    }

    public function areasFilters()
    {
        return $this->consulta("
        select
        caa.Id,
        caa.Nombre
        from t_censos tc
        inner join cat_v3_areas_atencion caa on tc.IdArea = caa.Id
        where caa.IdCliente = 1
        group by caa.Id
        order by caa.Nombre");
    }

    public function devicesFilters()
    {
        return $this->consulta("
        select
        tc.IdModelo as Id,
        concat(cmae.Nombre,' ',cme.Nombre) as Nombre
        from t_censos tc
        inner join cat_v3_modelos_equipo cme on tc.IdModelo = cme.Id
        inner join cat_v3_marcas_equipo cmae on cme.Marca = cmae.Id
        group by tc.IdModelo
        order by Nombre");
    }

    public function branchListInventories($params)
    {
        $conditions = '';
        $dateCondition = $this->convertDateParamsToConditionSQL($params['iniDate'], $params['endDate']);
        if ($dateCondition !== '') {
            $conditions .= ' and tst.FechaCreacion ' . $dateCondition;
        }

        if (isset($params['estatus']) && $params['estatus'] !== '') {
            $conditions .= " and tst.IdEstatus = '" . $params['estatus'] . "'";
        }

        if (isset($params['technician']) && $params['technician'] !== '') {
            $conditions .= " and tst.Atiende = '" . $params['technician'] . "'";
        }

        if (isset($params['region']) && $params['region'] !== '' && count($params['region']) > 0) {
            $conditions .= " and cs.IdRegionCliente in (" . implode(",", $params['region']) . ")";
        }

        if (isset($params['branch']) && $params['branch'] !== '' && count($params['branch']) > 0) {
            $conditions .= " and tst.IdSucursal in (" . implode(",", $params['branch']) . ")";
        }

        return $this->consulta("
        select
        ticketByServicio(MAX(tst.Id)) as Ticket,
        folioByServicio(MAX(tst.Id)) as SD,
        MAX(tst.Id) as Servicio,
        nombreUsuario(tst.Atiende) as Usuario,
        cs.Nombre as Sucursal,
        regionBySucursal(cs.Id) as Region,
        (select estatus(IdEstatus) from t_servicios_ticket where Id = MAX(tst.Id)) as Estatus,
        (select MAX(FechaConclusion) from t_servicios_ticket where IdTipoServicio = 11 and IdSucursal = tst.IdSucursal and IdEstatus = 4) as UltimaActualizacion,
        (select count(*) from t_servicios_ticket where IdTipoServicio = 11 and IdSucursal = tst.IdSucursal) as TotalCensos
        from t_servicios_ticket tst
        left join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        where IdTipoServicio = 11                                
        " . $conditions . "
        and tst.IdSucursal is not null
        and tst.IdSucursal > 0  
        and cs.IdCliente = 1
        and cs.Flag = 1
        and tst.IdEstatus <> 6
        group by tst.IdSucursal
        order by Sucursal");
    }

    private function convertDateParamsToConditionSQL($iniDate, $endDate)
    {
        $condition = "";
        $idt = '';
        $edt = '';
        if ($iniDate !== null && $iniDate !== '') {
            $idt = date_format(date_create($iniDate), "Y-m-d");
        }

        if ($endDate !== null && $endDate !== '') {
            $edt = date_format(date_create($endDate), "Y-m-d");
        }

        if ($idt !== '' && $edt === '') {
            $condition = " >= '" . $idt . " 00:00:00' ";
        } else if ($idt === '' && $edt !== '') {
            $condition = " <= '" . $edt . " 23:59:59' ";
        } else if ($idt !== '' && $edt !== '') {
            $condition = " between '" . $idt . " 00:00:00' and '" . $edt . " 23:59:59' ";
        }

        return $condition;
    }

    public function totalPointsByArea($servicio)
    {
        return $this->consulta("
        select
        tcp.IdArea as Id,
        areaAtencion(tcp.IdArea) as Nombre,
        tcp.Puntos as Total
        from t_censos_puntos tcp
        where IdServicio = '" . $servicio . "'
        order by Nombre");
    }

    public function totalDevicesByArea($servicio)
    {
        return $this->consulta("
        select
        tcp.IdArea as Id,
        areaAtencion(tcp.IdArea) as Nombre,
        count(*) as Total
        from t_censos tc
        inner join t_censos_puntos tcp 
        on tc.IdArea = tcp.IdArea and tc.IdServicio = tcp.IdServicio and tc.Punto <= tcp.Puntos
        where tc.IdServicio = '" . $servicio . "'
        group by tc.IdArea
        order by Nombre");
    }

    public function totalDevicesByLine($servicio)
    {
        return $this->consulta("
        select
        lineaByModelo(IdModelo) as Id2,
        linea(lineaByModelo(IdModelo)) as Nombre,
        count(*) as Total
        from t_censos tc
        inner join t_censos_puntos tcp 
        on tc.IdArea = tcp.IdArea and tc.IdServicio = tcp.IdServicio and tc.Punto <= tcp.Puntos
        where tc.IdServicio = '" . $servicio . "'
        group by Id2
        order by Nombre");
    }

    public function totalDevicesBySubline($servicio)
    {
        return $this->consulta("
        select
        sublineaByModelo(IdModelo) as Id2,
        sublinea(sublineaByModelo(IdModelo)) as Nombre,
        count(*) as Total
        from t_censos tc
        inner join t_censos_puntos tcp 
        on tc.IdArea = tcp.IdArea and tc.IdServicio = tcp.IdServicio and tc.Punto <= tcp.Puntos
        where tc.IdServicio = '" . $servicio . "'
        group by Id2
        order by Nombre");
    }

    public function totalDevicesByModel($servicio)
    {
        return $this->consulta("
        select
        tc.IdModelo as Id2,
        concat(marca(cme.Marca),' ',cme.Nombre) as Nombre,
        count(*) as Total
        from t_censos tc
        inner join t_censos_puntos tcp 
        on tc.IdArea = tcp.IdArea and tc.IdServicio = tcp.IdServicio and tc.Punto <= tcp.Puntos
        inner join cat_v3_modelos_equipo cme on tc.IdModelo = cme.Id
        where tc.IdServicio = '" . $servicio . "'
        group by Id2
        order by Nombre");
    }

    public function detailsInventory($servicio)
    {
        return $this->consulta("
        select
        tc.Id,
        areaAtencion(tc.IdArea) as Area,
        tc.Punto,
        linea(lineaByModelo(tc.IdModelo)) as Linea,
        sublinea(sublineaByModelo(tc.IdModelo)) as Sublinea,
        marca(cme.Marca) as Marca,
        cme.Nombre as Modelo,
        tc.Serie
        from t_censos tc
        inner join t_censos_puntos tcp on tc.IdArea = tcp.IdArea and tc.IdServicio = tcp.IdServicio and tc.Punto <= tcp.Puntos
        inner join cat_v3_modelos_equipo cme on tc.IdModelo = cme.Id
        where tc.IdServicio = '" . $servicio . "'
        order by Area, Punto, Linea, Sublinea, Marca, Modelo");
    }
}
