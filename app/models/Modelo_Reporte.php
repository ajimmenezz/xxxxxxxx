<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Reporte extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function obtenerFoliosAdist() {
        return $this->consulta("select
                                    CASE 
                                    WHEN MONTH(ts.FechaCreacion) = 1
                                    THEN 'Enero'
                                    WHEN MONTH(ts.FechaCreacion) = 2
                                    THEN 'Febrero'
                                    WHEN MONTH(ts.FechaCreacion) = 3
                                    THEN 'Marzo'
                                    WHEN MONTH(ts.FechaCreacion) = 4
                                    THEN 'Abril'
                                    WHEN MONTH(ts.FechaCreacion) = 5
                                    THEN 'Mayo'
                                    WHEN MONTH(ts.FechaCreacion) = 6
                                    THEN 'Junio'
                                    WHEN MONTH(ts.FechaCreacion) = 7
                                    THEN 'Julio'
                                    WHEN MONTH(ts.FechaCreacion) = 8
                                    THEN 'Agosto'
                                    WHEN MONTH(ts.FechaCreacion) = 9
                                    THEN 'Septiembre'
                                    WHEN MONTH(ts.FechaCreacion) = 10
                                    THEN 'Octubre'
                                    WHEN MONTH(ts.FechaCreacion) = 11
                                    THEN 'Noviembre'
                                    WHEN MONTH(ts.FechaCreacion) = 12
                                    THEN 'Diciembre'
                                    END as Mes,
                                    WEEK(ts.FechaCreacion, 1) AS Semana,
                                    ts.Folio AS TicketServiceDesk,
                                    estatus(ts.IdEstatus) as EstatusTicketAdIST,
                                    tst.Id as ServicioAdIST,
                                    IF(tipoServicio(tst.IdTipoServicio)  =  'Correctivo Adicional', 'Correctivo', tipoServicio(tst.IdTipoServicio)) AS TipoServicio,
                                    estatus(tst.IdEstatus) as EstatusServicio,
                                    departamentoArea(ts.IdDepartamento) as Departamento,
                                    nombreUsuario(tst.Atiende) as TecnicoAsignado,
                                    regionBySucursal(tst.IdSucursal) as Region,
                                    IF(tst.IdSucursal IS NULL, (sucursalCliente(ts.IdSucursal)), (sucursalCliente(tst.IdSucursal))) as Sucursal,
                                    ts.FechaCreacion as FechaSolicitud,
                                    nombreUsuario(ts.Solicita) as Solicitante,
                                    tsi.Asunto,
                                    tsi.Descripcion as DescripcionSolicitud,
                                    tst.FechaCreacion as FechaServicio,
                                    tst.FechaInicio as FechaInicioServicio,
                                    tst.FechaConclusion as FechaConclusionServicio,
                                    areaAtencion(tcg.IdArea) as AreaAtencion,
                                    tcg.Punto,
                                    modelo(tcg.IdModelo) as EquipoDiagnosticado,
                                    (select Nombre from cat_v3_componentes_equipo where Id = tcd.IdComponente) as Componente,
                                    (select Nombre from cat_v3_tipos_diagnostico_correctivo where Id = tcd.IdTipoDiagnostico) as TipoDiagnostico,
                                    (select Nombre from cat_v3_tipos_falla where Id = tcd.IdTipoFalla) as TipoFalla,
                                    if(tcd.IdComponente is null, (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla), (select Nombre from cat_v3_fallas_refaccion where Id = tcd.IdFalla)) as Falla,
                                    tcd.FechaCaptura as FechaDiagnostico,
                                    tcd.Observaciones as ObservacionesDiagnostico,
                                    (select Nombre from cat_v3_correctivos_soluciones where Id = tcs.IdTipoSolucion) as TipoSolucion,
                                    if(tcs.IdTipoSolucion = 1, (select Nombre from cat_v3_soluciones_equipo where Id = (select IdSolucionEquipo from t_correctivos_solucion_sin_equipo where IdSolucionCorrectivo = tcs.Id)),'NA') as SolucionSinEquipo,
                                    if(tcs.IdTipoSolucion = 3, modelo((select IdModelo from t_correctivos_solucion_cambio where IdSolucionCorrectivo = tcs.Id)), 'NA') as CambioEquipo,
                                    if(tcs.IdTipoSolucion = 2, (select group_concat(Nombre) from cat_v3_componentes_equipo where Id in (select IdRefaccion from t_correctivos_solucion_refaccion where IdSolucionCorrectivo = tcs.Id)), 'NA') as CambioRefaccion,
                                    (SELECT Descripcion FROM t_servicios_generales WHERE IdServicio = tst.Id) AS SolucionServicioSinClasificar,
                                        case
                                            when
                                                ts.IdEstatus in (4 , '4')
                                            then
                                                SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                            ts.FechaCreacion,
                                                            ts.FechaConclusion)) * 60)
                                            when ts.IdEstatus in (6 , '6') then ''
                                            else SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                        ts.FechaCreacion,
                                                        now())) * 60)
                                        end as TiempoSolicitud,
                                        case
                                            when
                                                tst.IdEstatus in (4 , '4')
                                            then
                                                SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                            tst.FechaCreacion,
                                                            tst.FechaConclusion)) * 60)
                                            when tst.IdEstatus in (6 , '6') then ''
                                            else SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                        tst.FechaCreacion,
                                                        now())) * 60)
                                        end as TiempoServicio,
                                        SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                    ts.FechaCreacion,
                                                    tst.FechaCreacion)) * 60) AS TiempoTranscurridoEntreSolicitudServicio
                                from t_servicios_ticket tst
                                right join t_solicitudes ts on tst.IdSolicitud = ts.Id
                                inner join t_solicitudes_internas tsi on tsi.IdSolicitud = ts.Id
                                left join t_correctivos_generales tcg on tst.Id = tcg.IdServicio
                                left join t_correctivos_diagnostico tcd on tcd.Id = (select MAX(Id) from t_correctivos_diagnostico where IdServicio = tst.Id)
                                left join t_correctivos_soluciones tcs on tcs.Id = (select MAX(Id) from t_correctivos_soluciones where IdServicio = tst.Id)
                                WHERE MONTH(ts.FechaCreacion) = MONTH(CURRENT_DATE())
                                AND YEAR(ts.FechaCreacion) = YEAR(CURRENT_DATE())
                                AND
                                    ts.Folio IS NOT NULL
                                AND ts.Folio != '0'
                                LIMIT 0,1000000");
    }

    public function obtenerFoliosAnualAdist() {
        return $this->consulta("select 
                                    YEAR(ts.FechaCreacion) AS Anio,
                                    CASE
                                        WHEN MONTH(ts.FechaCreacion) = 1 THEN 'Enero'
                                        WHEN MONTH(ts.FechaCreacion) = 2 THEN 'Febrero'
                                        WHEN MONTH(ts.FechaCreacion) = 3 THEN 'Marzo'
                                        WHEN MONTH(ts.FechaCreacion) = 4 THEN 'Abril'
                                        WHEN MONTH(ts.FechaCreacion) = 5 THEN 'Mayo'
                                        WHEN MONTH(ts.FechaCreacion) = 6 THEN 'Junio'
                                        WHEN MONTH(ts.FechaCreacion) = 7 THEN 'Julio'
                                        WHEN MONTH(ts.FechaCreacion) = 8 THEN 'Agosto'
                                        WHEN MONTH(ts.FechaCreacion) = 9 THEN 'Septiembre'
                                        WHEN MONTH(ts.FechaCreacion) = 10 THEN 'Octubre'
                                        WHEN MONTH(ts.FechaCreacion) = 11 THEN 'Noviembre'
                                        WHEN MONTH(ts.FechaCreacion) = 12 THEN 'Diciembre'
                                    END as Mes,
                                    WEEK(ts.FechaCreacion, 1) AS Semana,
                                    ts.Folio AS TicketServiceDesk,
                                    estatus(ts.IdEstatus) as EstatusTicketAdIST,
                                    tst.Id as ServicioAdIST,
                                    IF(tipoServicio(tst.IdTipoServicio) = 'Correctivo Adicional',
                                        'Correctivo',
                                        tipoServicio(tst.IdTipoServicio)) AS TipoServicio,
                                    estatus(tst.IdEstatus) as EstatusServicio,
                                    departamentoArea(ts.IdDepartamento) as Departamento,
                                    nombreUsuario(tst.Atiende) as TecnicoAsignado,
                                    regionBySucursal(tst.IdSucursal) as Region,
                                    IF(tst.IdSucursal IS NULL,
                                        (sucursalCliente(ts.IdSucursal)),
                                        (sucursalCliente(tst.IdSucursal))) as Sucursal,
                                    ts.FechaCreacion as FechaSolicitud,
                                    nombreUsuario(ts.Solicita) as Solicitante,
                                    tsi.Asunto,
                                    tsi.Descripcion as DescripcionSolicitud,
                                    estatus(tst.IdEstatus) as EstatusServicio,
                                    tst.FechaCreacion as FechaServicio,
                                    tst.FechaInicio as FechaInicioServicio,
                                    tst.FechaConclusion as FechaConclusiónServicio,
                                    areaAtencion(tcg.IdArea) as AreaAtencion,
                                    tcg.Punto,
                                    (SELECT 
                                            Nombre
                                        FROM
                                            cat_v3_modelos_equipo
                                        WHERE
                                            Id = tcg.IdModelo) AS Modelo,
                                    marca(marcaByModelo(tcg.IdModelo)) AS Marca,
                                    linea(lineaByModelo(tcg.IdModelo)) AS Linea,
                                    sublinea(sublineaByModelo(tcg.IdModelo)) as Sublinea,
                                    (select 
                                            Nombre
                                        from
                                            cat_v3_componentes_equipo
                                        where
                                            Id = tcd.IdComponente) as Componente,
                                    (select 
                                            Nombre
                                        from
                                            cat_v3_tipos_diagnostico_correctivo
                                        where
                                            Id = tcd.IdTipoDiagnostico) as TipoDiagnostico,
                                    (select 
                                            Nombre
                                        from
                                            cat_v3_tipos_falla
                                        where
                                            Id = tcd.IdTipoFalla) as TipoFalla,
                                    if(tcd.IdComponente is null,
                                        (select 
                                                Nombre
                                            from
                                                cat_v3_fallas_equipo
                                            where
                                                Id = tcd.IdFalla),
                                        (select 
                                                Nombre
                                            from
                                                cat_v3_fallas_refaccion
                                            where
                                                Id = tcd.IdFalla)) as Falla,
                                    tcd.FechaCaptura as FechaDiagnostico,
                                    tcd.Observaciones as ObservacionesDiagnostico,
                                    (select 
                                            Nombre
                                        from
                                            cat_v3_correctivos_soluciones
                                        where
                                            Id = tcs.IdTipoSolucion) as TipoSolucion,
                                    if(tcs.IdTipoSolucion = 1,
                                        (select 
                                                Nombre
                                            from
                                                cat_v3_soluciones_equipo
                                            where
                                                Id = (select 
                                                        IdSolucionEquipo
                                                    from
                                                        t_correctivos_solucion_sin_equipo
                                                    where
                                                        IdSolucionCorrectivo = tcs.Id)),
                                        'NA') as SolucionSinEquipo,
                                    if(tcs.IdTipoSolucion = 3,
                                        modelo((select 
                                                        IdModelo
                                                    from
                                                        t_correctivos_solucion_cambio
                                                    where
                                                        IdSolucionCorrectivo = tcs.Id)),
                                        'NA') as CambioEquipo,
                                    if(tcs.IdTipoSolucion = 2,
                                        (select 
                                                group_concat(Nombre)
                                            from
                                                cat_v3_componentes_equipo
                                            where
                                                Id in (select 
                                                        IdRefaccion
                                                    from
                                                        t_correctivos_solucion_refaccion
                                                    where
                                                        IdSolucionCorrectivo = tcs.Id)),
                                        'NA') as CambioRefaccion,
                                    (SELECT 
                                            Descripcion
                                        FROM
                                            t_servicios_generales
                                        WHERE
                                            IdServicio = tst.Id) AS SolucionServicioSinClasificar,
                                    case
                                        when
                                            ts.IdEstatus in (4 , '4')
                                        then
                                            SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                        ts.FechaCreacion,
                                                        ts.FechaConclusion)) * 60)
                                        when ts.IdEstatus in (6 , '6') then ''
                                        else SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                    ts.FechaCreacion,
                                                    now())) * 60)
                                    end as TiempoSolicitud,
                                    case
                                        when
                                            tst.IdEstatus in (4 , '4')
                                        then
                                            SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                        tst.FechaCreacion,
                                                        tst.FechaConclusion)) * 60)
                                        when tst.IdEstatus in (6 , '6') then ''
                                        else SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                    tst.FechaCreacion,
                                                    now())) * 60)
                                    end as TiempoServicio,
                                    SEC_TO_TIME((TIMESTAMPDIFF(MINUTE,
                                                ts.FechaCreacion,
                                                tst.FechaCreacion)) * 60) AS TiempoTranscurridoEntreSolicitudServicio
                                from
                                    t_servicios_ticket tst
                                        right join
                                    t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        inner join
                                    t_solicitudes_internas tsi ON tsi.IdSolicitud = ts.Id
                                        left join
                                    t_correctivos_generales tcg ON tst.Id = tcg.IdServicio
                                        left join
                                    t_correctivos_diagnostico tcd ON tcd.Id = (select 
                                            MAX(Id)
                                        from
                                            t_correctivos_diagnostico
                                        where
                                            IdServicio = tst.Id)
                                        left join
                                    t_correctivos_soluciones tcs ON tcs.Id = (select 
                                            MAX(Id)
                                        from
                                            t_correctivos_soluciones
                                        where
                                            IdServicio = tst.Id)
                                WHERE
                                    YEAR(ts.FechaCreacion) IN(2019,2020)
                                        AND tst.IdTipoServicio IN (20 , 27, 50)
                                        AND ts.Folio IS NOT NULL
                                        AND ts.Folio != '0'
                                ORDER BY Anio, Semana ASC");
    }

    public function getEquiposRefaccionesCorrectivo() {
        return $this->consulta("select
                                tst.Id as ServicioAD,
                                ts.Folio as TicketSD,
                                ts.Ticket as TicketAD,
                                DATE_FORMAT(CreatedTime,'%Y-%m-%d') as Fecha,
                                nombreUsuario(tst.Atiende) as Tecnico,
                                tipoServicio(tst.IdTipoServicio) as TipoServicio,
                                regionBySucursal(tst.IdSucursal) as Zona,
                                sucursal(tst.IdSucursal) as Sucursal,
                                ts.`Status` as EstatusSD,
                                estatus(tst.IdEstatus) as EstatusAD,
                                linea(lineaByModelo(tcg.IdModelo)) as Linea,
                                sublinea(sublineaByModelo(tcg.IdModelo)) as Sublinea,
                                marca(cme.Marca) as Marca,
                                cme.Nombre as Modelo,
                                (select Nombre from cat_v3_tipos_falla where Id = tcd.IdTipoFalla) as TipoFalla,
                                case
                                when tcd.IdTipoDiagnostico = 4
                                then (select Nombre from cat_v3_fallas_refaccion where Id = tcd.IdFalla)
                                else (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla)
                                end as Falla,
                                (select Nombre from cat_v3_correctivos_problemas where Id = tcp.IdTipoProblema) as TipoProblema,
                                case
                                when tcp.IdTipoProblema = 1
                                then componente(tcsr.IdRefaccion)
                                when tcp.IdTipoProblema = 2
                                then (select modelo(IdModelo) from t_correctivos_solicitudes_equipo where IdServicioOrigen = tst.Id order by Id desc limit 1)
                                else ''
                                end as `Equipo Requerido`,
                                case
                                when tcp.IdTipoProblema = 1
                                then tcsr.Cantidad
                                when tcp.IdTipoProblema = 2
                                then (select Cantidad from t_correctivos_solicitudes_equipo where IdServicioOrigen = tst.Id order by Id desc limit 1)
                                else ''
                                end as Cantidad,
                                ts.Technician as AsignadoSD
                                from t_solicitudes ts
                                inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud
                                left join t_correctivos_generales tcg on tst.Id = tcg.IdServicio
                                left join cat_v3_modelos_equipo cme on tcg.IdModelo = cme.Id
                                left join t_correctivos_diagnostico tcd on tcd.Id = (select MAX(Id) from t_correctivos_diagnostico where IdServicio = tst.Id)
                                left join t_correctivos_problemas tcp on tcp.Id = (select MAX(Id) from t_correctivos_problemas where IdServicio = tst.Id)
                                left join (select * from t_correctivos_solicitudes_refaccion group by IdServicioOrigen, IdRefaccion, Cantidad) tcsr on tcsr.IdServicioOrigen = tst.Id and tcp.IdTipoProblema = 1
                                where ts.`Status` in ('En Atención', 'Problema')
                                and tst.IdEstatus in (2,3)
                                and tst.IdTipoServicio in (20,27)
                                and tst.IdSucursal is not null
                                order by tst.Id");
    }
    
    public function getEquiposRefaccionesAdicional() {
        return $this->consulta("select
                                ts.Folio as TicketSD,
                                ts.Ticket as TicketAD,
                                tst.Id as ServicioAD,
                                DATE_FORMAT(ts.CreatedTime,'%Y-%m-%d') as Fecha,
                                regionBySucursal(tst.IdSucursal) as Zona,
                                sucursal(tst.IdSucursal) as Sucursal,
                                nombreUsuario(tst.Atiende) as Tecnico,
                                (select Nombre from cat_v3_tipos_diagnostico_correctivo where Id = tsae.IdTipoDiagnostico) as TipoDiagnostico,
                                case
                                when IdItem = 1 then modelo(tsae.TipoItem)
                                when IdItem = 2 then (select Nombre from cat_v3_equipos_sae where Id = tsae.TipoItem)
                                when IdItem = 3 then componente(tsae.TipoItem)
                                end as Producto,
                                tsae.Cantidad,
                                ts.Technician as AsignadoSD
                                from t_solicitudes ts
                                inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud
                                inner join t_servicios_avance tsa on tst.Id = tsa.IdServicio
                                inner join t_servicios_avance_equipo tsae on tsa.Id = tsae.IdAvance
                                where tst.IdTipoServicio in (39,50)
                                and tst.IdEstatus in (2,3)
                                and ts.`Status` in ('En Atencion', 'Problema')
                                and tsa.IdTipo = 2
                                and tsae.Flag = 1");
    }

}
