<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Censos extends Modelo_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAreasPuntosCensos(int $servicio)
    {
        $consulta = $this->consulta("select 
                tcp.Id,
                tcp.IdArea,
                areaAtencion(tcp.IdArea) as Area,
                tcp.Puntos
                from
                t_censos_puntos tcp
                where tcp.IdServicio = '" . $servicio . "'
                order by Area");

        if (empty($consulta)) {
            $this->queryBolean("insert into t_censos_puntos
                                    select
                                    null,
                                    IdServicio,
                                    IdArea,
                                    MAX(Punto) as Puntos
                                    from t_censos where IdServicio = '" . $servicio . "'
                                    group by IdArea");

            $consulta = $this->consulta("select 
                tcp.Id,
                tcp.IdArea,
                areaAtencion(tcp.IdArea) as Area,
                tcp.Puntos
                from
                t_censos_puntos tcp
                where tcp.IdServicio = '" . $servicio . "'
                order by Area");
        }

        return $consulta;
    }

    public function getAreasClienteFaltantesCenso(int $servicio)
    {
        $consulta = $this->consulta("select 
                                        Id,
                                        Nombre
                                        from cat_v3_areas_atencion
                                        where IdCliente = (select IdCliente 
                                                            from cat_v3_sucursales 
                                                            where Id = (select IdSucursal 
                                                                        from t_servicios_ticket 
                                                                        where Id = '" . $servicio . "')
                                                            )
                                        and Id not in (select IdArea 
                                                        from t_censos_puntos 
                                                        where IdServicio = '" . $servicio . "')
                                        and Flag = 1
                                        order by Nombre;");
        return $consulta;
    }

    public function agregaAreaPuntosCenso(array $datos)
    {
        $this->iniciaTransaccion();

        $this->queryBolean("insert "
            . "into t_censos_puntos "
            . "set IdServicio = '" . $datos['servicio'] . "', "
            . "IdArea = '" . $datos['area'] . "', "
            . "Puntos = '" . $datos['puntos'] . "'");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function guardaCambiosAreasPuntos(array $datos)
    {
        $this->iniciaTransaccion();

        foreach ($datos['areasPuntos'] as $key => $value) {
            if ($value['Cantidad'] <= 0) {
                $this->queryBolean("
                delete from t_censos where Id in (
                    select tf.Id from (
                        select 
                        tc.Id
                        from t_censos tc
                        inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea = tcp.IdArea
                        where tcp.Id = '" . $value['Id'] . "'
                    ) as tf
                )");
                $this->queryBolean("delete from t_censos_puntos where Id = '" . $value['Id'] . "'");
            } else {
                $this->queryBolean("
                delete from t_censos where Id in (
                    select tf.Id from (
                        select  
                        tc.Id
                        from t_censos tc
                        inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea = tcp.IdArea
                        where tcp.Id = '" . $value['Id'] . "' 
                        and tc.Punto > '" . $value['Cantidad'] . "'
                    ) as tf
                )");
                $this->queryBolean("update t_censos_puntos set Puntos = '" . $value['Cantidad'] . "' where Id = '" . $value['Id'] . "'");
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getKitStandarArea(int $area)
    {
        $consulta = $this->consulta("select 
                                    csxa.IdSublinea,
                                    linea(cse.Linea) as Linea,
                                    cse.Nombre as Sublinea,
                                    csxa.Cantidad
                                    from cat_v3_sublineas_x_area csxa 
                                    inner join cat_v3_sublineas_equipo cse on csxa.IdSublinea = cse.Id
                                    where csxa.IdArea = '" . $area . "'
                                    and csxa.Flag = 1
                                    group by csxa.IdSublinea
                                    order by Linea, Sublinea;");
        return $consulta;
    }

    public function getModelosStandarByArea(int $area)
    {
        $consulta = $this->consulta("select 
                                    modelos.Id,
                                    marcas.Nombre as Marca,
                                    modelos.Nombre as Modelo,
                                    marcas.Sublinea
                                    from cat_v3_marcas_equipo marcas
                                    inner join cat_v3_modelos_equipo modelos on marcas.Id = modelos.Marca
                                    where marcas.Sublinea in (select 
                                                                IdSublinea 
                                                                from cat_v3_sublineas_x_area 
                                                                where IdArea = '" . $area . "')
                                    and modelos.Flag = 1;");
        return $consulta;
    }

    public function getEquiposCensoByAreaPunto(array $datos)
    {
        $consulta = $this->consulta("select
                                    Id,
                                    IdModelo,
                                    modelo(IdModelo) as Modelo,
                                    Serie,
                                    Extra,
                                    Existe,
                                    Danado,
                                    Extra as Etiqueta,
                                    IdEstatus,                                    
                                    MAC,
                                    IdSistemaOperativo as IdSO,
                                    NombreRed,
                                    IdEstatusSoftwareRQ
                                    from 
                                    t_censos tc
                                    where IdServicio = '" . $datos['servicio'] . "'
                                    and IdArea = '" . $datos['area'] . "'
                                    and Punto = '" . $datos['punto'] . "'");
        return $consulta;
    }

    public function getNombreAreaById(int $area)
    {
        $consulta = $this->consulta("select Nombre from cat_v3_areas_atencion where Id = '" . $area . "'");
        return $consulta[0]['Nombre'];
    }

    public function getClienteByIdArea(int $area)
    {
        $consulta = $this->consulta("select IdCliente from cat_v3_areas_atencion where Id = '" . $area . "'");
        return $consulta[0]['IdCliente'];
    }

    public function getSistemasOperativos()
    {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_sistemas_operativos where Flag = 1");
        return $consulta;
    }

    public function getEstatusEquipoPrimeMX()
    {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_estatus where Id in (42,43,44,45)");
        return $consulta;
    }

    public function getModelosGenerales()
    {
        $consulta = $this->consulta("select 
                                    Id,
                                    modelo(Id) as Modelo,
                                    lineaByModelo(Id) as Linea,
                                    sublineaByModelo(Id) as Sublinea
                                    from cat_v3_modelos_equipo 
                                    where Flag = 1
                                    order by Modelo");
        return $consulta;
    }

    public function guardaEquiposPuntoCenso(array $datos)
    {
        $this->iniciaTransaccion();

        if (isset($datos['activosEstandar']) && count($datos['activosEstandar']) > 0) {
            foreach ($datos['activosEstandar'] as $key => $value) {
                if ($value['existe'] == 1) {
                    $this->actualizar("t_censos", [
                        'IdModelo' => $value['modelo'],
                        'Serie' => $value['serie'],
                        'Existe' => $value['existe'],
                        'Danado' => $value['danado']
                    ], ['Id' => $value['id']]);
                } else {
                    $this->eliminar("t_censos", ['Id' => $value['id']]);
                }
            }
        }

        if (isset($datos['nuevosEstandar']) && count($datos['nuevosEstandar']) > 0) {
            foreach ($datos['nuevosEstandar'] as $key => $value) {
                $this->insertar("t_censos", [
                    'IdServicio' => $datos['servicio'],
                    'IdArea' => $datos['area'],
                    'Punto' => $datos['punto'],
                    'IdModelo' => $value['modelo'],
                    'Serie' => $value['serie'],
                    'Existe' => 1,
                    'Danado' => $value['danado']
                ]);
            }
        }

        $this->insertar("t_censos_areas_puntos_revisados", [
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto']
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getPuntosCensoRevisados(int $servicio)
    {
        $consulta = $this->consulta("select 
                                    * 
                                    from t_censos_areas_puntos_revisados 
                                    where IdServicio = '" . $servicio . "'");

        return $consulta;
    }

    public function guardarEquipoAdicionalCenso(array $datos)
    {
        $this->iniciaTransaccion();

        $seriesExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.Serie = '" . $datos['serie'] . "' and tc.Serie != 'ILEGIBLE');");
        if ($seriesExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la serie en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $etiquetasExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.Extra = '" . $datos['etiqueta'] . "' and tc.Extra != '')");
        if ($etiquetasExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la etiqueta en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $macAddressExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.MAC = '" . $datos['mac'] . "' and tc.MAC != '')");
        if ($macAddressExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la MAC Address en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $this->insertar("t_censos", [
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'IdModelo' => $datos['modelo'],
            'Punto' => $datos['punto'],
            'Serie' => $datos['serie'],
            'Extra' => $datos['etiqueta'],
            'IdEstatus' => $datos['estado'],
            'MAC' => $datos['mac'],
            'IdSistemaOperativo' => $datos['so'],
            'NombreRed' => $datos['nombreRed'],
            'IdEstatusSoftwareRQ' => $datos['rq'],
            'Existe' => 1,
            'Danado' => $datos['danado']
        ]);

        $this->insertar("t_censos_areas_puntos_revisados", [
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto']
        ]);


        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'id' => $this->ultimoId()];
        }
    }

    public function eliminarEquiposAdicionalesCenso(array $datos)
    {
        $this->iniciaTransaccion();

        $this->eliminar("t_censos", [
            "Id" => $datos['id']
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function guardaCambiosEquiposAdicionalesCenso(array $datos)
    {
        $this->iniciaTransaccion();

        $seriesExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.Serie = '" . $datos['serie'] . "' and tc.Serie != 'ILEGIBLE') 
                                            and tc.Id <> '" . $datos['id'] . "'");
        if ($seriesExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la serie en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $etiquetasExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.Extra = '" . $datos['etiqueta'] . "' and tc.Extra != '')
                                            and tc.Id <> '" . $datos['id'] . "'");
        if ($etiquetasExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la etiqueta en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $macAddressExistentes = $this->consulta("select
                                            count(*) as Total
                                            from t_censos tc where IdServicio in (
                                                    select
                                                    MAX(tst.Id) as Id
                                                    from
                                                    t_servicios_ticket tst
                                                    where IdSucursal in (
                                                                        select 
                                                                        Id 
                                                                        from cat_v3_sucursales 
                                                                        where IdCliente = (
                                                                                            select 
                                                                                            IdCliente 
                                                                                            from cat_v3_sucursales 
                                                                                            where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $datos['servicio'] . "'))
                                                                                            )
                                                    and IdTipoServicio = 11
                                                    and IdEstatus in (4,2)
                                                    group by IdSucursal
                                            ) and (tc.MAC = '" . $datos['mac'] . "' and tc.MAC != '') 
                                            and tc.Id <> '" . $datos['id'] . "'");
        if ($macAddressExistentes[0]['Total'] > 0) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => 'Ya existe la MAC Address en el registro de esta u otra sucursal. Verifique la información'
            ];
        }

        $this->queryBolean("
            insert into  t_censos_areas_puntos_revisados
            select 
            null,
            IdServicio,
            IdArea,
            Punto 
            from t_censos where Id = '" . $datos['id'] . "'");

        $this->actualizar("t_censos", [
            'IdModelo' => $datos['modelo'],
            'Serie' => $datos['serie'],
            'Extra' => $datos['etiqueta'],
            'IdEstatus' => $datos['estado'],
            'MAC' => $datos['mac'],
            'IdSistemaOperativo' => $datos['so'],
            'NombreRed' => $datos['nombreRed'],
            'IdEstatusSoftwareRQ' => $datos['rq'],
            'Existe' => $datos['existe'],
            'Danado' => $datos['danado']
        ], ['Id' => $datos['id']]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getNomenclaturaInicial($idServicio)
    {
        $nomenclatura = $this->consulta("select
                                concat(
                                (select SUBSTRING(Nombre,-3) from cat_v3_regiones_cliente where Id = cs.IdRegionCliente),
                                '-',
                                upper(replace(NombreCinemex,'Prmdts',''))) as Clave
                                from 
                                cat_v3_sucursales cs
                                where cs.Id = (select IdSucursal from t_servicios_ticket where Id = '" . $idServicio . "')");
        return $nomenclatura[0]['Clave'];
    }

    public function getTotalAreas(string $idServicio)
    {
        $consulta = $this->consulta('select 
                                                    areaAtencion(IdArea) as Area,
                                                    count(*) as Total
                                                from t_censos  
                                                WHERE IdServicio = "' . $idServicio . '"
                                                group by Area order by Area');
        return $consulta;
    }

    public function getTotalLineas(string $idServicio)
    {
        $consulta = $this->consulta('select
                                                    cap_first(strSplit(modelo(IdModelo)," - ",1)) as Linea,
                                                    count(*) as Total
                                                from t_censos  
                                                WHERE IdServicio = "' . $idServicio . '" 
                                                group by Linea');
        return $consulta;
    }

    public function getCensos(string $idServicio)
    {
        $consulta = $this->consulta('SELECT 
                                                areaAtencion(tc.IdArea) AS Area,
                                                tc.Punto,
                                                (SELECT Equipo FROM v_equipos WHERE Id = tc.IdModelo) AS Equipo, 
                                                tc.Serie,
                                                tc.Extra
                                            FROM t_censos tc 
                                            WHERE tc.IdServicio = "' . $idServicio . '"
                                            ORDER BY Area, Punto ASC');
        return $consulta;
    }

    public function getInforomacionUltimoCenso($sucursal)
    {
        $consulta = $this->consulta("
        select
        tst.Id,
        nombreUsuario(tst.Atiende) as Usuario,
        tst.IdSucursal,
        tst.FechaCreacion
        from t_servicios_ticket tst
        where IdTipoServicio = 11
        and IdEstatus = 4
        and IdSucursal = 17
        order by Id desc limit 1");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return [];
        }
    }

    public function restaurarCenso($sucursal, $servicio)
    {
        $this->iniciaTransaccion();
        $this->queryBolean("delete from t_censos_areas_puntos_revisados where IdServicio = '" . $servicio . "'");
        $this->queryBolean("delete from t_censos where IdServicio = '" . $servicio . "'");
        $this->queryBolean("delete from t_censos_puntos where IdServicio = '" . $servicio . "'");
        $this->queryBolean("
        insert into t_censos_puntos (IdServicio, IdArea, Puntos)
        select
        " . $servicio . ",
        IdArea,
        Puntos
        from t_censos_puntos
        where IdServicio = (
            select MAX(Id) from t_servicios_ticket where IdSucursal = '" . $sucursal . "' and IdTipoServicio = 11 and IdEstatus = 4
        )");
        $this->queryBolean("
        insert into t_censos (IdServicio,IdArea,IdModelo,Punto,Serie,Extra,Existe,Danado,IdEstatus,IdSistemaOperativo,MAC,NombreRed,IdEstatusSoftwareRQ)
        select 
            " . $servicio . ",
            tc.IdArea,
            tc.IdModelo,
            tc.Punto,
            tc.Serie,
            tc.Extra,
            tc.Existe,
            tc.Danado,
            tc.IdEstatus,
            tc.IdSistemaOperativo,
            tc.MAC,
            tc.NombreRed,
            tc.IdEstatusSoftwareRQ
            from t_censos tc
            inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea  = tcp.IdArea and tc.Punto <= tcp.Puntos
            where tc.IdServicio = (
                select MAX(Id) from t_servicios_ticket where IdSucursal = '" . $sucursal . "' and IdTipoServicio = 11 and IdEstatus = 4
            )");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getAreasForCensoTemplate()
    {
        return $this->consulta("
        select 
        Nombre, ClaveCorta
        from cat_v3_areas_atencion
        where Flag = 1
        and ClaveCorta <> ''
        order by Nombre");
    }

    public function getDevicesForCensoTemplate()
    {
        return $this->consulta("
        select 
        marca(Marca) as MarcaS,
        Nombre
        from cat_v3_modelos_equipo
        where Flag = 1
        order by MarcaS, Nombre");
    }

    public function getCensoForTemplate($servicio)
    {
        return $this->consulta("
        select 
        (select ClaveCorta from cat_v3_areas_atencion where Id = tc.IdArea) as Area,
        tc.Punto,
        (select Nombre from cat_v3_modelos_equipo where Id = tc.IdModelo) as Modelo,
        tc.Serie,
        tc.Forced
        from t_censos tc
        inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea = tcp.IdArea and tc.Punto <= tcp.Puntos
        where tc.IdServicio = '" . $servicio . "'
        order by Area, Punto");
    }

    public function getAreasForCensoCompare()
    {
        $consulta = $this->consulta("
        select 
        Id,
        ClaveCorta
        from cat_v3_areas_atencion
        where Flag = 1
        and ClaveCorta <> ''");

        $array = [];
        foreach ($consulta as $k => $v) {
            $array[$v['ClaveCorta']] = $v['Id'];
        }

        return $array;
    }

    public function getDevicesForCensoCompare()
    {
        $consulta = $this->consulta("
        select 
        Id,
        Nombre
        from cat_v3_modelos_equipo
        where Flag = 1");

        $array = [];
        foreach ($consulta as $k => $v) {
            $array[$v['Nombre']] = $v['Id'];
        }

        return $array;
    }

    public function getDuplicitySeries($servicio, $series)
    {
        $consulta = $this->consulta("
        select
        ticketByServicio(tc.IdServicio) as Ticket,
        sucursalByServicio(tc.IdServicio) as Sucursal,
        tc.*
        from t_censos tc
        inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea = tcp.IdArea and tc.Punto <= tcp.Puntos
        where tc.IdServicio in (
            select 
            tst.Id
            from cat_v3_sucursales cs
            inner join t_servicios_ticket tst on tst.Id = (select MAX(Id) from t_servicios_ticket where IdSucursal = cs.Id and IdTipoServicio = 11 and IdEstatus in(4,2,5))
            where cs.Flag = 1
            and cs.IdCliente = (select IdCliente  from cat_v3_sucursales where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $servicio . "'))
            and cs.Id <> (select IdSucursal from t_servicios_ticket where Id = '" . $servicio . "')
        ) and tc.Serie in (\"" . $series . "\")
        and tc.IdServicio <> '" . $servicio . "'");

        $array = [];
        foreach ($consulta as $k => $v) {
            $array[$v['Serie']] = $v;
        }

        return $array;
    }

    public function getDomainBranchByService($servicio)
    {
        return $this->consulta("
        select 
        Dominio
        from cat_v3_sucursales
        where Id = (select IdSucursal from t_servicios_ticket where Id = '" . $servicio . "')")[0]['Dominio'];
    }

    public function updateCensoFromTemplate($servicio, $data)
    {
        $this->iniciaTransaccion();
        $this->queryBolean("delete from t_censos_areas_puntos_revisados where IdServicio = '" . $servicio . "'");
        $this->queryBolean("delete from t_censos where IdServicio = '" . $servicio . "'");
        $this->queryBolean("delete from t_censos_puntos where IdServicio = '" . $servicio . "'");
        $this->insertarBatch('t_censos', $data);
        $this->queryBolean("
        insert into t_censos_puntos (IdServicio, IdArea, Puntos)
        select
        '" . $servicio . "',
        IdArea,
        MAX(Punto)
        from t_censos
        where IdServicio = '" . $servicio . "'
        group by IdArea");

        $this->queryBolean("
        update        
        t_censos tc
        inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea = tcp.IdArea and tc.Punto <= tcp.Puntos
        set tc.Forced = 2
        where tc.IdServicio in (
            select 
            tst.Id
            from cat_v3_sucursales cs
            inner join t_servicios_ticket tst on tst.Id = (select MAX(Id) from t_servicios_ticket where IdSucursal = cs.Id and IdTipoServicio = 11 and IdEstatus in(4,2,5))
            where cs.Flag = 1
            and cs.IdCliente = 1
        ) and tc.Serie in (select * from (select Serie from t_censos where IdServicio = '" . $servicio . "' and Forced = 1)as tf)
        and tc.IdServicio <> '" . $servicio . "'");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }
}
