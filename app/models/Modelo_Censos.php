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

    public function getKitStandarArea(int $area, int $unidadNegocio = null)
    {
        $condicion = $unidadNegocio !== null ? " and csxa.IdUnidadNegocio = '" . $unidadNegocio . "' " : "";
        $consulta = $this->consulta("select 
                                    csxa.IdSublinea,
                                    linea(cse.Linea) as Linea,
                                    cse.Nombre as Sublinea,
                                    csxa.Cantidad
                                    from cat_v3_sublineas_x_area csxa 
                                    inner join cat_v3_sublineas_equipo cse on csxa.IdSublinea = cse.Id
                                    where csxa.IdArea = '" . $area . "'
                                    " . $condicion . "
                                    and csxa.Flag = 1
                                    group by csxa.IdSublinea
                                    order by Linea, Sublinea;");
        return $consulta;
    }

    public function getModelosStandarByArea(int $area, int $unidadNegocio = null)
    {
        $condicion = $unidadNegocio !== null ? " and csxa.IdUnidadNegocio = '" . $unidadNegocio . "' " : "";
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
                                                                where IdArea = '" . $area . "' " . $condicion . ")
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
                        'Serie' => str_replace(" ", "", strtoupper($value['serie'])),
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
                    'Serie' => str_replace(" ", "", strtoupper($value['serie'])),
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
        and IdSucursal = '" . $sucursal . "'
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
        // $this->queryBolean("
        // insert into t_censos (IdServicio,IdArea,IdModelo,Punto,Serie,Extra,Existe,Danado,IdEstatus,IdSistemaOperativo,MAC,NombreRed,IdEstatusSoftwareRQ)
        // select 
        //     " . $servicio . ",
        //     tc.IdArea,
        //     tc.IdModelo,
        //     tc.Punto,
        //     tc.Serie,
        //     tc.Extra,
        //     tc.Existe,
        //     tc.Danado,
        //     tc.IdEstatus,
        //     tc.IdSistemaOperativo,
        //     tc.MAC,
        //     tc.NombreRed,
        //     tc.IdEstatusSoftwareRQ
        //     from t_censos tc
        //     inner join t_censos_puntos tcp on tc.IdServicio = tcp.IdServicio and tc.IdArea  = tcp.IdArea and tc.Punto <= tcp.Puntos
        //     where tc.IdServicio = (
        //         select MAX(Id) from t_servicios_ticket where IdSucursal = '" . $sucursal . "' and IdTipoServicio = 11 and IdEstatus = 4
        //     )");

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

    public function getCensoForCompare($idServicio)
    {
        return $this->consulta("
        select 
        IdArea,
        Punto,
        lineaByModelo(IdModelo) as IdLinea,
        sublineaByModelo(IdModelo) as IdSublinea,
        cme.Marca as IdMarca,
        IdModelo,
        concat(cs.Dominio,caa.ClaveCorta,LPAD(tc.Punto,2,'0')) as Dominio,
        unidadNegocioByServicio(tc.IdServicio) as UnidadNegocio,
        estadoBySucursal(cs.Id) as Estado,
        cs.Nombre as Sucursal,
        regionBySucursal(cs.Id) as Zona,
        caa.Nombre as Area,
        linea(lineaByModelo(IdModelo)) as Linea,
        sublinea(sublineaByModelo(IdModelo)) as Sublinea,
        marca(cme.Marca) as Marca,
        cme.Nombre as Modelo,
        tc.Serie,
        date_format((select FechaInicio from t_servicios_ticket where Id = tc.IdServicio),'%d-%m-%Y') as Fecha
        from t_censos tc
        inner join cat_v3_modelos_equipo cme on tc.IdModelo = cme.Id
        inner join cat_v3_areas_atencion caa on tc.IdArea = caa.Id
        inner join cat_v3_sucursales cs on cs.Id = (select IdSucursal from t_servicios_ticket where Id = tc.IdServicio)
        where tc.IdServicio = '" . $idServicio . "'
        and tc.Existe = 1
        and tc.IdEstatus in (0,17)
        and (tc.Forced in (0,1) or (tc.Forced = 2 && Serie = 'ILEGIBLE'))");
    }

    public function getLastCensoForCompare($idServicio)
    {
        $lastInventoryService = $this->consulta(
            "select 
            MAX(Id) as IdServicio
            from t_servicios_ticket tst
            where tst.IdTipoServicio = 11
            and tst.IdEstatus = 4
            and tst.IdSucursal = (select IdSucursal from t_servicios_ticket where Id = '" . $idServicio . "')"
        );
        if (!empty($lastInventoryService)) {
            return $this->getCensoForCompare($lastInventoryService[0]['IdServicio']);
        } else {
            return [];
        }
    }

    public function getGeneralesForCompare($idServicio)
    {
        $this->queryBolean("SET lc_time_names = 'es_ES'");
        return $this->consulta("
            select 
            DATE_FORMAT(tst.FechaCreacion,'%M %d, %Y') as Fecha,
            sucursal(tst.IdSucursal) as Sucursal,
            (select DATE_FORMAT(FechaCreacion,'%M %d, %Y') from t_servicios_ticket where IdSucursal = tst.IdSucursal and IdTipoServicio = 11 and IdEstatus = 4 and Id < tst.Id order by Id desc limit 1) as FechaUltimo
            from t_servicios_ticket tst
            where tst.Id = '" . $idServicio . "'")[0];
    }

    public function getKitSublineasXArea($unidadNegocio)
    {
        return $this->consulta("
            select 
            csa.Id,
            csa.IdArea,
            csa.IdSublinea,
            csa.Cantidad,
            areaAtencion(csa.IdArea) as Area,
            linea((select Linea from cat_v3_sublineas_equipo where Id = csa.IdSublinea)) as Linea,
            sublinea(csa.IdSublinea) as Sublinea
            from cat_v3_sublineas_x_area csa
            where Flag = 1
            and IdUnidadNegocio = '" . $unidadNegocio . "'");
    }

    public function getFullDataAreas()
    {
        $areas = $this->consulta("select * from cat_v3_areas_atencion where Flag <>2");
        $arrayReturn = [];
        foreach ($areas as $k => $v) {
            $arrayReturn[$v['Nombre']] = $v;
        }
        return $arrayReturn;
    }

    public function getFullDataLineas()
    {
        $lineas = $this->consulta("select * from cat_v3_lineas_equipo where Flag <> 2");
        $arrayReturn = [];
        foreach ($lineas as $k => $v) {
            $arrayReturn[$v['Nombre']] = $v;
        }
        return $arrayReturn;
    }

    public function getFullDataSublineas()
    {
        $lineas = $this->consulta("select
        Id,
        linea(Linea) as Linea,
        Nombre,
        Descripcion,
        Flag
        from cat_v3_sublineas_equipo
        where Flag <> 2");
        $arrayReturn = [];
        foreach ($lineas as $k => $v) {
            $arrayReturn[$v['Nombre']] = $v;
        }
        return $arrayReturn;
    }

    public function getFullDataModelos()
    {
        $modelos = $this->consulta("select
        Id,
        linea(lineaByModelo(Id)) as Linea,
        sublinea(sublineaByModelo(Id)) as Sublinea,
        marca(Marca) as Marca,
        Nombre,
        Descripcion
        from cat_v3_modelos_equipo
        where Flag <> 2");
        $arrayReturn = [];
        foreach ($modelos as $k => $v) {
            $arrayReturn[$v['Nombre']] = $v;
        }
        return $arrayReturn;
    }

    public function getUnidadNegocioByServicio($servicio)
    {
        return $this->consulta("
        select 
        IdUnidadNegocio 
        from cat_v3_sucursales where Id = (
            select
            IdSucursal
            from t_servicios_ticket 
            where Id = '" . $servicio . "'
        )")[0]['IdUnidadNegocio'];
    }

    public function getKitAreas($unidadNegocio)
    {
        $consulta = $this->consulta("
        select
        csa.Id,
        csa.IdArea,
        csa.IdSublinea,
        areaAtencion(csa.IdArea) as Area,
        sublinea(csa.IdSublinea) as Sublinea,
        csa.Cantidad
        from cat_v3_sublineas_x_area csa
        where csa.IdUnidadNegocio = '" . $unidadNegocio . "'
        and Flag = 1");

        $arrayReturn = [];
        if (!empty($consulta)) {
            foreach ($consulta as $k => $v) {
                if (!array_key_exists($v['Area'], $arrayReturn)) {
                    $arrayReturn[$v['Area']] = [
                        'total' => 0,
                        'texto' => ''
                    ];
                }
                $arrayReturn[$v['Area']]['total'] += $v['Cantidad'];
                $arrayReturn[$v['Area']]['texto'] .= '<br />' . $v['Cantidad'] . ' ' . $v['Sublinea'];
            }
        }

        return $arrayReturn;
    }

    public function getCensosServicesId(array $data = [])
    {
        return $this->consulta("
        select 
        MAX(tst.Id) as Id,
        sucursal(tst.IdSucursal) as Sucursal,
        regionBySucursal(tst.IdSucursal) as Zona
        from t_servicios_ticket tst
        inner join cat_v3_sucursales cs on tst.IdSucursal = cs.Id
        where tst.IdTipoServicio = 11 
        and tst.IdEstatus = 4
        and cs.Flag = 1
        and cs.IdCliente = 1
        group by cs.Id
        order by cs.Nombre");
    }
}
