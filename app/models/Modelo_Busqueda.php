<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Notificacion
 *
 * @author Freddy
 */
class Modelo_Busqueda extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Obteniendo el resultado de la búsqueda.
     * 
     */

    public function busquedaReporte(string $query) {
        return $this->consulta($query);
    }

    public function getGeneralesSolicitud(string $solicitud) {
        return $this->consulta("select "
                        . "ts.Id, "
                        . "nombreUsuario(ts.Solicita) as Solicita, "
                        . "ts.FechaCreacion, "
                        . "estatus(ts.IdEstatus) as Estatus, "
                        . "departamentoArea(ts.IdDepartamento) as Departamento, "
                        . "ts.Ticket, "
                        . "tsi.Asunto, "
                        . "prioridad(ts.IdPrioridad) as Prioridad, "
                        . "tsi.Descripcion, "
                        . "tsi.Evidencias "
                        . "from t_solicitudes ts INNER JOIN t_solicitudes_internas tsi "
                        . "on ts.Id = tsi.IdSolicitud "
                        . "where ts.Id = '" . $solicitud . "';");
    }

    public function getTipoServicio(string $servicio) {
        return $this->consulta("select "
                        . "tst.IdTipoServicio as Tipo,  "
                        . "(select c.Seguimiento from cat_v3_servicios_departamento c where Id = IdTipoServicio) as Seguimiento "
                        . "from t_servicios_ticket tst where tst.Id = '" . $servicio . "'; ");
    }

    public function getGeneralesServicioGeneral(string $servicio) {
        return $this->consulta("select "
                        . "tst.Id, "
                        . "tst.Ticket, "
                        . "tst.IdSolicitud as Solicitud, "
                        . "folioByServicio(tst.Id) as Folio, "
                        . "tipoServicio(tst.IdTipoServicio) as Tipo, "
                        . "estatus(tst.IdEstatus) as Estatus, "
                        . "nombreUsuario(tst.Atiende) as Atiende, "
                        . "sucursalCliente(tst.IdSucursal) as Sucursal, "
                        . "tst.Descripcion as Servicio, "
                        . "tst.FechaCreacion, "
                        . "tst.FechaInicio, "
                        . "tst.FechaConclusion, "
                        . "tsg.Descripcion as Resolucion, "
                        . "tsg.Archivos as Evidencias, "
                        . "tsg.Fecha as FechaResolucion, "
                        . "tst.Firma, "
                        . "tst.NombreFirma, "
                        . "tst.FechaFirma "
                        . "from t_servicios_ticket tst LEFT JOIN t_servicios_generales tsg "
                        . "on tst.Id = tsg.IdServicio "
                        . "where tst.Id = '" . $servicio . "';");
    }

    public function getGeneralesServicioGeneralCompleto(string $servicio) {
        return $this->consulta("select tst.Id, 
                        tipoServicio(tst.IdTipoServicio) as Tipo, 
                        estatus(tst.IdEstatus) as Estatus, 
                        nombreUsuario(tst.Atiende) as Atiende, 
                        tst.Descripcion as Servicio, 
                        tst.FechaCreacion, 
                        tst.FechaInicio,
                        tst.FechaConclusion, 
                        sucursalCliente(tst.IdSucursal) as Sucursal, 
                        tsg.Descripcion as Resolucion, 
                        tsg.Archivos as Evidencias, 
                        tsg.Fecha as FechaResolucion, 
                        tst.Firma as FirmaGerente, 
                        tst.FirmaTecnico as FirmaTecnico, 
                        tst.NombreFirma as NombreGerente,
                        nombreUsuario(tst.IdTecnicoFirma) as NombreTecnico,
                        tst.FechaFirma,
                        (SELECT Nombre FROM cat_v3_cinemex_validadores WHERE Id = tst.IdValidaCinemex) as ValidaCinemex
                        from t_servicios_ticket tst LEFT JOIN t_servicios_generales tsg 
                        on tst.Id = tsg.IdServicio 
                        where tst.Id = '" . $servicio . "'");
    }

    /* Generales de mantenimientos preventivos de póliza */

    public function getGeneralesServicio12(string $servicio) {
        return $this->consulta("select "
                        . "tst.Id, "
                        . "tipoServicio(tst.IdTipoServicio) as Tipo, "
                        . "estatus(tst.IdEstatus) as Estatus, "
                        . "nombreUsuario(tst.Atiende) as Atiende, "
                        . "tst.Descripcion as Servicio, "
                        . "tst.FechaCreacion, "
                        . "tst.FechaInicio, "
                        . "tst.FechaConclusion, "
                        . "sucursalCliente(tmg.IdSucursal) as Sucursal, "
                        . "tmg.Evidencias, "
                        . "tst.Firma, "
                        . "tst.NombreFirma, "
                        . "tst.FechaFirma "
                        . "from t_servicios_ticket tst "
                        . "left join t_mantenimientos_generales tmg on tst.Id = tmg.IdServicio "
                        . "where tst.Id = '" . $servicio . "';");
    }

    /* Obtiene la información del antes y después de los mantenimientos */

    public function getAntesDespues12(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "areaAtencion(tamd.IdArea) as Area, "
                        . "tamd.Punto, "
                        . "tamd.ObservacionesAntes, "
                        . "tamd.ObservacionesDespues, "
                        . "tamd.EvidenciasAntes, "
                        . "tamd.EvidenciasDespues "
                        . "from t_mantenimientos_antes_despues tamd "
                        . "where tamd.IdServicio = '" . $servicio . "' "
                        . "order by Area, Punto;");
    }

    /* Obtiene la información de problemas por equipo de los mantenimientos */

    public function getProblemasEquipo12(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "areaAtencion(IdArea) as Area, "
                        . "Punto, "
                        . "(SELECT Equipo from v_equipos where Id = IdModelo) as Modelo, "
                        . "Serie, "
                        . "Observaciones, "
                        . "Evidencias "
                        . "from t_mantenimientos_problemas_equipo "
                        . "where IdServicio = '" . $servicio . "' "
                        . "order by Area, Punto;");
    }

    /* Obtiene la información del equipo faltante por punto */

    public function getEquipoFaltante12(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "areaAtencion(IdArea) as Area, "
                        . "Punto, "
                        . "(SELECT Equipo from v_equipos where Id = IdModelo) as Modelo "
                        . "from t_mantenimientos_equipo_faltante "
                        . "where IdServicio = '" . $servicio . "' "
                        . "order by Area, Punto;");
    }

    /* Obtiene la información del los problemas adicionales furante el mantenimiento */

    public function getProblemasAdicionales12(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "areaAtencion(IdArea) as Area, "
                        . "if(Punto = 0,'',Punto) as Punto, "
                        . "Descripcion, "
                        . "Evidencias "
                        . "from t_mantenimientos_problemas_adicionales "
                        . "where IdServicio = '" . $servicio . "' "
                        . "order by Area, Punto;");
    }

    /* Generales de censos de póliza */

    public function getGeneralesServicio11(string $servicio) {
        return $this->consulta("select "
                        . "tst.Id, "
                        . "tipoServicio(tst.IdTipoServicio) as Tipo, "
                        . "estatus(tst.IdEstatus) as Estatus, "
                        . "nombreUsuario(tst.Atiende) as Atiende, "
                        . "tst.Descripcion as Servicio, "
                        . "tst.FechaCreacion, "
                        . "tst.FechaInicio, "
                        . "tst.FechaConclusion, "
                        . "sucursalCliente(tcg.IdSucursal) as Sucursal, "
                        . "tcg.Descripcion, "
                        . "tst.Firma, "
                        . "tst.NombreFirma, "
                        . "tst.FechaFirma "
                        . "from t_servicios_ticket tst "
                        . "left join t_censos_generales tcg on tst.Id = tcg.IdServicio "
                        . "where tst.Id = '" . $servicio . "';");
    }

    /* Obtiene la información del los censos */

    public function getDetalllesServicio11(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "areaAtencion(IdArea) as Area, "
                        . "if(Punto = 0,'',Punto) as Punto, "
                        . "(SELECT Equipo from v_equipos where Id = IdModelo) as Modelo, "
                        . "Serie,"
                        . "Extra as Terminal "
                        . "from t_censos "
                        . "where IdServicio = '" . $servicio . "' "
                        . "order by Area, Punto;");
    }

    /* Generales de mantenimientos preventivos de póliza */

    public function getGeneralesServicio5(string $servicio) {
        return $this->consulta("SELECT "
                        . "tst.Id, "
                        . "tipoServicio(tst.IdTipoServicio) as Tipo, "
                        . "estatus(tst.IdEstatus) as Estatus, "
                        . "nombreUsuario(tst.Atiende) as Atiende, "
                        . "tst.Descripcion as Servicio, "
                        . "tst.FechaCreacion, "
                        . "tst.FechaInicio, "
                        . "tst.FechaConclusion, "
                        . "tst.Firma, "
                        . "tst.NombreFirma, "
                        . "tst.FechaFirma, "
                        . "ttg.IdTipoTrafico, "
                        . "tipoTrafico(ttg.IdTipoTrafico) as TipoTrafico, "
                        . "(select nombreUsuario(IdUsuarioAsignado) from t_rutas_logistica where Id = tsxr.IdRuta) as Encargado, "
                        . "CASE ttg.IdTipoOrigen "
                        . "	WHEN 1 THEN concat('(Sucursal) ',sucursalCliente(ttg.IdOrigen)) "
                        . "	WHEN 2 then concat('(Sucursal) ',proveedor(ttg.IdOrigen)) "
                        . "	WHEN 3 THEN ttg.OrigenDireccion "
                        . "end as Origen, "
                        . "CASE ttg.IdTipoDestino "
                        . "	WHEN 1 THEN concat('(Sucursal) ',sucursalCliente(ttg.IdDestino)) "
                        . "	WHEN 2 then concat('(Sucursal) ',proveedor(ttg.IdDestino)) "
                        . "	WHEN 3 THEN ttg.DestinoDireccion "
                        . "end as Destino "
                        . "from t_servicios_ticket tst "
                        . "left join t_traficos_generales ttg on tst.Id = ttg.IdServicio "
                        . "left join t_servicios_x_ruta tsxr on ttg.IdServicio = tsxr.IdServicio "
                        . "where ttg.IdServicio = " . $servicio . ";");
    }

    /* detalle de items de servicios de tráfico */

    public function getItemsServicio5(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "CASE IdTipoEquipo "
                        . " WHEN 4 THEN DescripcionOtros "
                        . " ELSE (select concat(Clave,' - ',Nombre) from cat_v3_equipos_sae where Id = IdModelo) "
                        . "END as Equipo, "
                        . "Serie, "
                        . "Cantidad "
                        . "from t_traficos_equipo "
                        . "where IdServicio = '" . $servicio . "'");
    }

    /* documentación de envío del servicio de tráfico */

    public function getEnvioServicio5(string $servicio) {
        return $this->consulta(""
                        . "select "
                        . "tipoEnvio(IdTipoEnvio) as TipoEnvio, "
                        . "paqueteria(IdPaqueteria) as Paqueteria, "
                        . "FechaEnvio, "
                        . "Guia, "
                        . "ComentariosEnvio as Envio, "
                        . "UrlEnvio, "
                        . "FechaEntrega, "
                        . "NombreRecibe as Recibe, "
                        . "ComentariosEntrega as Entrega, "
                        . "UrlEntrega "
                        . "from t_traficos_envios "
                        . "where IdServicio = '" . $servicio . "'");
    }

    /* Correctivo */

    public function getGeneralesServicio20(string $servicio) {
        $consulta = $this->consulta("SELECT 
                                    *,
                                    areaAtencion(IdArea) Area,
                                    modelo(IdModelo) Modelo
                                FROM t_correctivos_generales
                                WHERE IdServicio = '" . $servicio . "'");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function getDiagnosticoEquipo20(string $servicio) {
        return $this->consulta("SELECT "
                        . "*, "
                        . "(SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = IdTipoDiagnostico) TipoDiagnostico, "
                        . "(SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = IdComponente) Componente, "
                        . "(SELECT Nombre FROM cat_v3_tipos_falla WHERE Id = IdTipoFalla) TipoFalla, "
                        . "CASE IdTipoDiagnostico"
                        . " WHEN 3 THEN (SELECT Nombre FROM cat_v3_fallas_equipo WHERE Id = IdFalla)"
                        . " WHEN 4 THEN (SELECT Nombre FROM cat_v3_fallas_refaccion WHERE Id = IdFalla)"
                        . "END as Falla "
                        . "FROM t_correctivos_diagnostico "
                        . "WHERE Id = (SELECT MAX(Id) FROM t_correctivos_diagnostico WHERE IdServicio = '" . $servicio . "')");
    }

    public function getTipoProblema20(string $servicio) {
        $consulta = $this->consulta("SELECT Id, IdTipoProblema FROM t_correctivos_problemas WHERE IdServicio = '" . $servicio . "' ORDER BY Id DESC LIMIT 1");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function getProblemaServicio20(string $servicio) {
        $array = array();
        $tipoProblema = $this->getTipoProblema20($servicio);
        switch ($tipoProblema[0]['IdTipoProblema']) {
            case '1':
                $array['solicitudesRefaccionServicio'] = $this->consulta('select 
                                                                tcsr.IdServicio as Servicio,
                                                                nombreUsuario(tst.Solicita) as Solicitante,
                                                                tst.FechaCreacion,
                                                                estatus(tst.IdEstatus) as Estatus,
                                                                group_concat(cvce.Nombre," - ",tcsr.Cantidad) as RefaccionCantidad
                                                            from t_correctivos_solicitudes_refaccion tcsr inner join t_servicios_ticket tst
                                                                on tcsr.IdServicio = tst.Id
                                                            inner join cat_v3_componentes_equipo cvce
                                                                on tcsr.IdRefaccion = cvce.Id
                                                            where tcsr.IdServicioOrigen = "' . $servicio . '" 
                                                            group by Servicio');
                break;
            case '2':
                $array['solicitudesEquipoServicio'] = $this->consulta('select 
                                                                tcse.IdServicio as Servicio,
                                                                nombreUsuario(tst.Solicita) as Solicitante,
                                                                tst.FechaCreacion,
                                                                estatus(tst.IdEstatus) as Estatus,
                                                                group_concat(ve.Equipo," _ ",tcse.Cantidad) as EquipoCantidad
                                                            from t_correctivos_solicitudes_equipo tcse inner join t_servicios_ticket tst
                                                                on tcse.IdServicio = tst.Id
                                                            inner join v_equipos ve
                                                                on tcse.IdModelo = ve.Id
                                                            where tcse.IdServicioOrigen = "' . $servicio . '" 
                                                            group by Servicio');
                break;
            case '3':
                $array['garantiaRespaldo'] = $this->consulta('SELECT * FROM t_correctivos_garantia_respaldo WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');
                $returnArrayEquipoGarantia = [
                    'equiposGarantiaRespaldo' => 'Sin Información',
                    'solicitudEquipoRespaldo' => 'Sin Información',
                    'garantiaRespaldo' => 'Sin Información'
                ];
                if ($array['garantiaRespaldo'][0]['EsRespaldo'] === '1' && $array['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                    $returnArrayEquipoGarantia['equiposGarantiaRespaldo'] = $this->consulta('SELECT 
                                                                                                            tegr.*,
                                                                                                            (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRetira) NombreEquipoRetira,
                                                                                                            (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRespaldo) NombreEquipoRespaldo
                                                                                                        FROM t_equipos_garantia_respaldo tegr 
                                                                                                        WHERE tegr.IdGarantia = "' . $array['garantiaRespaldo'][0]['Id'] . '"');
                }
                if ($array['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $array['garantiaRespaldo'][0]['SolicitaEquipo'] === '1') {
                    $returnArrayEquipoGarantia['solicitudEquipoRespaldo'] = $this->consulta('SELECT 
                                                                                                            nombreUsuario(tst.Atiende) Atiende,
                                                                                                            tst.FechaCreacion
                                                                                                        FROM t_servicios_relaciones tsr
                                                                                                        INNER JOIN t_servicios_ticket tst
                                                                                                            ON tsr.IdServicioNuevo = tst.Id
                                                                                                        WHERE tsr.IdServicioOrigen = "' . $servicio . '" 
                                                                                                        AND tst.IdTipoServicio = 21
                                                                                                        ORDER BY tsr.Id DESC LIMIT 1');
                }
                $array['informacionGarantiaRespaldo'] = $returnArrayEquipoGarantia;
                break;
        }
        return $array;
    }

    public function getVerificarEnvioEntrega20(string $servicio) {
        $tipoProblema = $this->getTipoProblema20($servicio);
        $consulta = $this->consulta('SELECT Id, Tipo FROM (
                                SELECT Id, IdProblemaCorrectivo, FechaCapturaEnvio AS Fecha, "Envio" AS Tipo FROM t_correctivos_envios_equipo WHERE IdProblemaCorrectivo = "' . $tipoProblema[0]['Id'] . '"
                                UNION
                                SELECT Id, IdProblemaCorrectivo, Fecha, "Entrega" AS Tipo FROM t_correctivos_entregas_equipo WHERE IdProblemaCorrectivo = "' . $tipoProblema[0]['Id'] . '") AS TablaEnvioEntrega 
                                ORDER BY Fecha DESC LIMIT 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCorrectivoEnviosEquipoProblemas(string $servicio) {
        $consulta = $this->consulta('SELECT 
                                                                tcee.*,
                                                                (SELECT Nombre FROM cat_v3_tipos_envio WHERE Id = IdTipoEnvio) TipoEnvio,
                                                                CASE tcee.IdTipoEnvio
                                                                        WHEN 2 THEN (SELECT Nombre FROM cat_v3_paqueterias WHERE Id = tcee.IdPaqueteriaConsolidado)
                                                                        WHEN 3 THEN (SELECT Nombre FROM cat_v3_consolidados WHERE Id = tcee.IdPaqueteriaConsolidado)
                                                                END as PaqueteriaConsolidado,
                                                                nombreUsuario(tcee.Recibe) NombreRecibe
                                                            FROM t_correctivos_envios_equipo tcee
                                                            INNER JOIN t_correctivos_problemas tcp
                                                                ON tcp.Id = tcee.IdProblemaCorrectivo
                                                            WHERE tcp.IdServicio = "' . $servicio . '"
                                                            ORDER BY IdProblemaCorrectivo DESC LIMIT 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCorrectivoEntregasEquipo(string $servicio) {
        $tipoProblema = $this->getTipoProblema20($servicio);
        $consulta = $this->consulta('SELECT
                                                                Firma,
                                                                Fecha,
                                                                nombreUsuario(IdUsuarioRecibe) Recibe,
                                                                NombreRecibe
                                                            FROM t_correctivos_entregas_equipo
                                                            WHERE IdProblemaCorrectivo = "' . $tipoProblema[0]['Id'] . '"
                                                            ORDER BY Fecha DESC LIMIT 1');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaEntregaEnvio(string $servicio) {
        $verificarEnvioEntrega = $this->getVerificarEnvioEntrega20($servicio);
        if ($verificarEnvioEntrega[0]['Tipo'] === 'Envio') {
            $returnArrayEnvioEntrega['tituloEntregaEnvio'] = 'Envio del Equipo (Foraneo)';
            $returnArrayEnvioEntrega['envioEquipo'] = $this->consultaCorrectivoEnviosEquipoProblemas($servicio);
        } else {
            $returnArrayEnvioEntrega['tituloEntregaEnvio'] = 'Entrega del Equipo (Local - Trigo)';
            $returnArrayEnvioEntrega['entregaEquipo'] = $this->consultaCorrectivoEntregasEquipo($servicio);
        }
        return $returnArrayEnvioEntrega;
    }

    public function getCorrectivosSoluciones(string $servicio) {
        $data['correctivoSoluciones'] = $this->consulta('SELECT * FROM t_correctivos_soluciones WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');
        if (!empty($data['correctivoSoluciones'])) {
            switch ($data['correctivoSoluciones'][0]['IdTipoSolucion']) {
                case '1':
                    $data['returnArraySolicion']['tituloSolucion'] = 'Reparación sin Equipo';
                    $data['returnArraySolicion']['correctivosSolucionSinEquipo'] = $this->consulta('SELECT 
                                                                                                        (SELECT Nombre FROM cat_v3_soluciones_equipo WHERE Id = tcsse.IdSolucionEquipo) Solucion 
                                                                                                    FROM t_correctivos_solucion_sin_equipo tcsse 
                                                                                                    WHERE tcsse.IdSolucionCorrectivo = "' . $data['correctivoSoluciones'][0]['Id'] . '"');
                    break;
                case '2':
                    $data['returnArraySolicion']['tituloSolucion'] = 'Reparación con Refacción';
                    $data['returnArraySolicion']['correctivosSolucionRefaccion'] = $this->consulta('SELECT
                                                                                                        tcsr.Cantidad,
                                                                                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tcsr.IdRefaccion) Refaccion
                                                                                                    FROM t_correctivos_solucion_refaccion tcsr
                                                                                                    WHERE tcsr.IdSolucionCorrectivo = "' . $data['correctivoSoluciones'][0]['Id'] . '"');
                    break;
                case '3':
                    $data['returnArraySolicion']['tituloSolucion'] = 'Cambio de Equipo';
                    $data['returnArraySolicion']['correctivosSolucionCambio'] = $this->consulta('SELECT 
                                                                                                    *,
                                                                                                    (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) Equipo 
                                                                                                FROM t_correctivos_solucion_cambio 
                                                                                                WHERE IdSolucionCorrectivo = "' . $data['correctivoSoluciones'][0]['Id'] . '"');
                    break;
            }
        } else {
            $data = FALSE;
        }
        return $data;
    }

}
