<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of ServiceDesck
 *
 * @author Freddy
 */
class InformacionServicios extends General {

    private $DBS;
    private $Phantom;
    private $Correo;
    private $ServiceDesk;
    private $MSP;
    private $MSD;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->MSD = \Modelos\Modelo_ServiceDesk::factory();
    }

    public function MostrarDatosSD(string $folio, string $servicio = NULL, bool $servicioConcluir = FALSE, string $key) {
        $html = '';
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $estatus = TRUE;

        $html .= '<div>' . $usuario['Nombre'] . '</div>';
        $html .= '<div>' . $fecha . '</div>';

        if ($servicioConcluir) {
            $union = 'SELECT 
                            tse.Id,
                            tse.Ticket,
                            tse.IdTipoServicio,
                            (SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = tse.IdTipoServicio) Seguimiento,
                            tse.IdEstatus,
                            tse.FechaConclusion,
                            tse.Atiende AS Atiende
                    FROM t_servicios_ticket tse 
                    INNER JOIN t_solicitudes tso 
                    ON tse.IdSolicitud = tso.Id 
                    WHERE tse.Id = "' . $servicio . '"
                    UNION ';
        } else {
            $union = '';
            $html .= $this->consultaCorrectivoProblema($servicio, $folio, $key);
        }

        $serviciosConcluidos = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM (' . $union . 'SELECT 
                                                                                tse.Id,
                                                                                tse.Ticket,
                                                                                tse.IdTipoServicio,
                                                                                (SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = tse.IdTipoServicio) Seguimiento,
                                                                                tse.IdEstatus,
                                                                                tse.FechaConclusion,
                                                                                (SELECT Atiende FROM t_solicitudes WHERE Id = tse.IdSolicitud) Atiende
                                                                        FROM t_servicios_ticket tse 
                                                                        INNER JOIN t_solicitudes tso 
                                                                        ON tse.IdSolicitud = tso.Id 
                                                                        WHERE tso.Folio = "' . $folio . '"
                                                                        AND (tse.IdEstatus in (3,4)
                                                                        OR (tse.IdTipoServicio = 20 AND tse.IdEstatus = 2))
                                                                        ) TABLAS
                                                                        ORDER BY FIELD (IdEstatus, 2,4), FechaConclusion DESC');

        if (!empty($serviciosConcluidos)) {
            foreach ($serviciosConcluidos as $key => $value) {
                $datos = array(
                    'servicio' => $value['Id'],
                    'ticket' => $value['Ticket']
                );

                if ($value['Seguimiento'] === '1') {
                    switch ($value['IdTipoServicio']) {
                        case '27':
                            $html .= $this->checklist($datos);
                            break;
                        case '20':
                            $html .= $this->correctivo($datos);
                            break;
                        case '12':
                        case '11':
                            $html .= $this->servicioSinDetalles($datos);
                            break;
                    }
                } else {
                    $html .= $this->sinClasificar($datos);
                }
            }

            $html .= $this->avancesProblemasServicio($folio);

            $atiende = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                tso.Atiende
                                                                FROM t_servicios_ticket tst
                                                                INNER JOIN t_solicitudes tso
                                                                ON tst.IdSolicitud = tso.Id
                                                                WHERE tst.Id = "' . $servicio . '"');

            $resultadoSD = $this->cambiarEstatusSD(array(
                'Folio' => $folio,
                'Atiende' => $atiende[0]['Atiende'],
                'Servicio' => $servicio,
                'ServicioConcluir' => $servicioConcluir));

            if ($resultadoSD->operation->result->status !== 'Success') {
                $estatus = $resultadoSD->operation->result->message;
            }
        }

        return array('html' => $html, 'estatus' => $estatus);
    }

    public function cambiarEstatusSD(array $datos) {
        $SDkey = $this->MSP->getApiKeyByUser($datos['Atiende']);

        if ($datos['ServicioConcluir']) {
            $datosExtraServicio = 'AND	tse.Id <> "' . $datos['Servicio'] . '"';
        } else {
            $datosExtraServicio = ' ';
        }

        $servicios = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                    tse.IdEstatus
                                                            FROM t_servicios_ticket tse 
                                                            INNER JOIN t_solicitudes tso 
                                                            ON tse.IdSolicitud = tso.Id 
                                                            WHERE tso.Folio = "' . $datos['Folio'] . '"'
                . $datosExtraServicio .
                'AND (tse.IdEstatus in (1,2,3,10,12) 
                                                            OR(tse.IdTipoServicio = 20 
                                                                    AND tse.IdEstatus = 4
                                                                    AND(tse.Firma IS NULL OR tse.Firma = "")))
                                                                    AND tse.IdTipoServicio not in (21,41)');

        if (isset($datos['Servicio'])) {
            $servicioLaboratorio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                    (SELECT IdDepartamento FROM cat_perfiles WHERE Id = cvu.IdPerfil) IdDepartamento
                                                                FROM t_servicios_ticket tst
                                                                INNER JOIN cat_v3_usuarios cvu
                                                                ON tst.Atiende = cvu.Id
                                                                WHERE tst.Id = "' . $datos['Servicio'] . '"');


            if ($servicioLaboratorio[0]['IdDepartamento'] === '10') {
                $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'En Atención', $datos['Folio']);
            } else {
                if (!empty($servicios)) {
                    foreach ($servicios as $key => $value) {
                        if ($value['IdEstatus'] === '3') {
                            $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'Problema', $datos['Folio']);
                        } else {
                            $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'En Atención', $datos['Folio']);
                        }
                    }
                } else {
                    $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'Completado', $datos['Folio']);
                }
            }
        } else {
            if (!empty($servicios)) {
                foreach ($servicios as $key => $value) {
                    if ($value['IdEstatus'] === '3') {
                        $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'Problema', $datos['Folio']);
                    } else {
                        $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'En Atención', $datos['Folio']);
                    }
                }
            } else {
                $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($SDkey, 'Completado', $datos['Folio']);
            }
        }

        $this->guardarLogSD($resultadoSD, $datos['Folio']);

        return $resultadoSD;
    }

    public function guardarLogSD($resultadoSD, string $folio) {
        if ($resultadoSD->operation->result->status === 'Failed') {
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

            $datosEstatusServiceDesk = array(
                'Folio' => $folio,
                'MensajeSD' => $resultadoSD->operation->result->message,
                'Fecha' => $fecha,
                'Flag' => '0'
            );
            $this->MSD->guardarLogSD($datosEstatusServiceDesk);
        }
    }

    public function sinClasificar($datos) {
        $host = $_SERVER['SERVER_NAME'];
        $contSolucion = 0;
        $linkImagenesSolucion = '';
        $datosResolucion = '';
        $infoServicio = $this->getInformacionServicio($datos['servicio']);

        $datosDescripcionConclusion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                                sucursal(tst.IdSucursal) Sucursal,
                                                                                tsg.Descripcion AS DescripcionServicio,
                                                                                tsg.Archivos
                                                                            FROM t_servicios_ticket tst
                                                                            INNER JOIN t_servicios_generales tsg
                                                                            ON tsg.IdServicio = tst.Id
                                                                            WHERE tst.Id = "' . $datos['servicio'] . '"');

        if (!empty($datosDescripcionConclusion)) {
            $archivosSolucion = explode(',', $datosDescripcionConclusion[0]['Archivos']);
            foreach ($archivosSolucion as $value) {
                if ($value != '') {
                    $contSolucion++;
                    $linkImagenesSolucion .= "<a href='http://" . $host . $value . "'>Archivo" . $contSolucion . "</a> &nbsp ";
                }
            }

            $path = $this->cargarPDF($datos);
            $descripcionConclusionSD = '<div>Descripción: ' . $datosDescripcionConclusion[0]['DescripcionServicio'] . '</div>';
            $descripcion = $datosDescripcionConclusion[0]['Sucursal'] . ' ' . $infoServicio[0]['TipoServicio'] . ' se concluyo con exito';
            $datosResolucion = '<br>' . $descripcion . $descripcionConclusionSD . $linkImagenesSolucion . "<div><a href='" . $path . "'>Documento PDF</a></div>";
        }
        return $datosResolucion;
    }

    public function correctivo(array $datos) {
        $informacionSolicitud = $this->getGeneralesSolicitudServicio($datos['servicio']);
        $informacionCorrectivo = $this->consultaInformacionCorrectivo($datos['servicio']);
        $informacionDiagnostico = $this->consultaCorrectivosDiagnostico($datos['servicio']);
        $host = $_SERVER['SERVER_NAME'];
        $linkImagenesDiagnostico = '';
        $linkImagenesSolucion = '';
        $refaccion = '';
        $descripcion = '';
        $solucionDiv = '';

        $linkPdf = $this->cargarPDF($datos);
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->MSP->getApiKeyByUser($usuario['Id']);

        if ($informacionDiagnostico !== FALSE) {
            if ($informacionDiagnostico[0]['IdTipoDiagnostico'] === '4') {
                $componente = "<div> Componente: " . $informacionDiagnostico[0]['Componente'] . "</div>";
            } else {
                $componente = '';
            }

            if ($informacionDiagnostico[0]['IdTipoDiagnostico'] === '4' || $informacionDiagnostico[0]['IdTipoDiagnostico'] === '3' || $informacionDiagnostico[0]['IdTipoDiagnostico'] === '2') {
                $datosFalla = "<div>Tipo de Falla: " . $informacionDiagnostico[0]['NombreTipoFalla'] . "&nbsp Falla: " . $informacionDiagnostico[0]['NombreFalla'] . "</div>";
            } else {
                $datosFalla = "";
            }

            $contDiagnostico = 0;
            $archivosDiagnostico = explode(',', $informacionDiagnostico[0]['Evidencias']);
            foreach ($archivosDiagnostico as $value) {
                $contDiagnostico++;
                $linkImagenesDiagnostico .= "<a href='http://" . $host . $value . "'>Archivo" . $contDiagnostico . "</a> &nbsp";
            }

            $correctivoSoluciones = $this->DBS->consultaGeneralSeguimiento('SELECT * FROM t_correctivos_soluciones WHERE IdServicio = "' . $datos['servicio'] . '" ORDER BY Id DESC LIMIT 1');
            $informacionProblema = $this->consultaCorrectivoProblema($datos['servicio'], $informacionSolicitud['folio'], $key);

            if (!empty($correctivoSoluciones)) {
                switch ($correctivoSoluciones[0]['IdTipoSolucion']) {
                    case '1':
                        $tituloSolucion = 'Reparación sin Equipo';
                        $solucion = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                            (SELECT Nombre FROM cat_v3_soluciones_equipo WHERE Id = tcsse.IdSolucionEquipo) Solucion 
                                                                        FROM t_correctivos_solucion_sin_equipo tcsse 
                                                                        WHERE tcsse.IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');
                        $contSolucion = 0;
                        $archivosSolucion = explode(',', $correctivoSoluciones[0]['Evidencias']);
                        foreach ($archivosSolucion as $value) {
                            $contSolucion++;
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "'>Archivo" . $contSolucion . "</a> &nbsp ";
                        }
                        $solucionDiv = "<div>***SOLUCIÓN***</div><div>" . $tituloSolucion . " &nbsp Tipo de Solución: " . $solucion[0]['Solucion'] . "</div><div>Observaciones: " . $correctivoSoluciones[0]['Observaciones'] . "</div>" . "<div>" . $linkImagenesSolucion . "</div>";
                        break;
                    case '2':
                        $tituloSolucion = 'Reparación con Refacción';
                        $solucion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                            tcsr.Cantidad,
                                                                            (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tcsr.IdRefaccion) Refaccion
                                                                        FROM t_correctivos_solucion_refaccion tcsr
                                                                        WHERE tcsr.IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');

                        foreach ($solucion as $clave => $value) {
                            $refaccion .= "<div>Refacción: " . $value['Refaccion'] . " &nbsp Cantidad: " . $value['Cantidad'] . "</div>";
                        }
                        $contSolucion = 0;
                        $archivosSolucion = explode(',', $correctivoSoluciones[0]['Evidencias']);
                        foreach ($archivosSolucion as $value) {
                            $contSolucion++;
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "'>Archivo" . $contSolucion . "</a> &nbsp ";
                        }
                        $solucionDiv = "<div>***SOLUCIÓN***</div><div>" . $tituloSolucion . "</div>" . $refaccion . "<div>Observaciones: " . $correctivoSoluciones[0]['Observaciones'] . "</div>" . "<div>" . $linkImagenesSolucion . "</div>";
                        break;
                    case '3':
                        $tituloSolucion = 'Cambio de Equipo';
                        $solucion = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                            *,
                                                                            (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) Equipo 
                                                                        FROM t_correctivos_solucion_cambio 
                                                                        WHERE IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');
                        $contSolucion = 0;
                        $archivosSolucion = explode(',', $correctivoSoluciones[0]['Evidencias']);
                        foreach ($archivosSolucion as $value) {
                            $contSolucion++;
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "'>Archivo" . $contSolucion . "</a> &nbsp ";
                        }
                        $solucionDiv = "<div>***SOLUCIÓN***</div><div>" . $tituloSolucion . " &nbsp Equipo: " . $solucion[0]['Equipo'] . "</div><div>Serie: " . $solucion[0]['Serie'] . "<div>Observaciones: " . $correctivoSoluciones[0]['Observaciones'] . "</div>" . "<div>" . $linkImagenesSolucion . "</div>";
                        break;
                }
            } else {
                if ($informacionDiagnostico[0]['IdTipoDiagnostico'] === '1' || $informacionDiagnostico[0]['IdTipoDiagnostico'] === '5') {
                    if ($informacionDiagnostico[0]['IdTipoDiagnostico'] === '5') {
                        $this->asignarMultimedia($linkPdf, $informacionSolicitud['folio'], $key, $datos['servicio']);
                    }
                }
            }

            $descripcion = "<br>"
                    . "<div>***DIAGNÓSTICO DEL EQUIPO***</div>"
                    . "<div>" . $informacionSolicitud['sucursal'] . " &nbsp " . $informacionCorrectivo[0]['NombreArea'] . " " . $informacionCorrectivo[0]['Punto'] . " &nbsp " . $informacionCorrectivo[0]['Equipo'] . "&nbsp Serie: " . $informacionCorrectivo[0]['Serie'] . "&nbsp Terminal: " . $informacionCorrectivo[0]['Serie'] . "</div>"
                    . "<div>" . $informacionDiagnostico[0]['NombreTipoDiagnostico'] . " &nbsp " . $componente . "</div>"
                    . $datosFalla
                    . "<div>Observaciones: " . $informacionDiagnostico[0]['Observaciones'] . "</div>"
                    . $linkImagenesDiagnostico
                    . $informacionProblema
                    . $solucionDiv
                    . "<div><a href='" . $linkPdf . "' target='_blank'>DOCUMENTO PDF</a></div>";

            return $descripcion;
        }
    }

    public function asignarMultimedia(string $linkPdf, string $folio, string $key, string $servicio = null) {
        $usuario = $this->Usuario->getDatosUsuario();
        $linkPDF = '<br>Ver PDF Resumen General <a href="' . $linkPdf . '" target="_blank">Aquí</a>';
        $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'En Atención', $folio);
        $textoMultimedia = '<p><strong>Multimedia,</strong> el técnico <strong>' . $usuario['Nombre'] . '</strong> le ha reasignado la solicitud <strong>' . $folio . '</strong>.</p>' . $linkPDF;
        $sucursal = $this->sucursalServicio($servicio);
        $this->enviarCorreoConcluido(array('ajimenez@siccob.com.mx'), 'Reasignación de Solicitud' . $sucursal, $textoMultimedia);

        $this->ServiceDesk->reasignarFolioSD($folio, '9304', $key);
    }

    public function servicioSinDetalles($datos) {
        $infoServicio = $this->getInformacionServicio($datos['servicio']);

        $datosDescripcionConclusion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                                sucursal(tst.IdSucursal) Sucursal
                                                                            FROM t_servicios_ticket tst
                                                                            WHERE tst.Id = "' . $datos['servicio'] . '"');

        $path = $this->cargarPDF($datos);
        $descripcion = $datosDescripcionConclusion[0]['Sucursal'] . ' ' . $infoServicio[0]['TipoServicio'] . ' se concluyo con exito';
        $datosResolucion = '<br>' . $descripcion . "<div><a href='" . $path . "'>Documento PDF</a></div>";

        return $datosResolucion;
    }

    public function avancesProblemasServicio(string $folio) {
        $host = $_SERVER['SERVER_NAME'];
        $datosAvancesProblemas = '';
        $datosAvances = '';
        $datosProblemas = '';
        $contAvanceProblema = 0;
        $serviciosAvancesServicios = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                    tsa.* 
                                                                            FROM t_servicios_avance tsa
                                                                            INNER JOIN t_servicios_ticket tst
                                                                            ON tsa.IdServicio = tst.Id
                                                                            INNER JOIN t_solicitudes ts
                                                                            ON tst.IdSolicitud = ts.Id
                                                                            WHERE ts.Folio = "' . $folio . '"
                                                                            ORDER BY tsa.Fecha DESC');

        foreach ($serviciosAvancesServicios as $value) {
            $linkImagenes = '';
            $tabla = '';
            $tablaAvancesProblemas = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                    *,
                                                                                CASE IdItem 
                                                                                    WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = TipoItem) 
                                                                                    WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = TipoItem)
                                                                                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = TipoItem) 
                                                                                END as EquipoMaterial 
                                                                            FROM t_servicios_avance_equipo 
                                                                            WHERE IdAvance = "' . $value['Id'] . '"');

            foreach ($tablaAvancesProblemas as $key => $valor) {
                switch ($valor['IdItem']) {
                    case '1':
                        $tipoItem = 'Equipo';
                        break;
                    case '2':
                        $tipoItem = 'Material';
                        break;
                    case '3':
                        $tipoItem = 'Refacción';
                }
                if ($valor['IdItem'] === '1') {
                    if ($value['IdTipo'] === '1') {
                        $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Serie: " . $valor['Serie'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                    } else {
                        $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                    }
                } else {
                    $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                }
            }


            $archivosAvanceProblema = explode(',', $value['Archivos']);
            foreach ($archivosAvanceProblema as $v) {
                if ($v != '') {
                    $contAvanceProblema++;
                    $linkImagenes .= "<a href='http://" . $host . $v . "'>Archivo" . $contAvanceProblema . "</a> &nbsp ";
                }
            }

            if ($value['IdTipo'] === '1') {
                $datosAvances .= "<br><div>" . $value['Descripcion'] . "</div>" . $tabla . "<div>" . $linkImagenes . "</div>";
            } else {
                $datosProblemas .= "<div>" . $value['Descripcion'] . "</div>" . $tabla . "<div>" . $linkImagenes . "</div><br>";
            }
        }

        if ($datosAvances !== '') {
            $datosAvancesTitulo = "***AVANCES***<br>";
        } else {
            $datosAvancesTitulo = "";
        }

        if ($datosProblemas !== '') {
            $datosProblemasTitulo = '<p style="color:#FF0000";>***PROBLEMAS***</p>';
        } else {
            $datosProblemasTitulo = "";
        }

        $datosAvancesProblemas = $datosProblemasTitulo . $datosProblemas . $datosAvancesTitulo . $datosAvances;
        return $datosAvancesProblemas;
    }

    public function getGeneralesSolicitudServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "ts.Folio, "
                . "tst.Id as Servicio, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "(select Nombre from cat_v3_departamentos_siccob where Id = ts.IdDepartamento) as DepartamentoSolicitud, "
                . "(select cvas.Nombre from cat_v3_departamentos_siccob cvs INNER JOIN cat_v3_areas_siccob cvas ON cvas.Id = cvs.IdArea where cvs.Id = ts.IdDepartamento) as AreaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Asunto from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as AsuntoSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "(select Nombre from cat_v3_prioridades where Id = ts.IdPrioridad) as Prioridad, "
                . "tst.Ticket, "
                . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
                . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
                . "if(
                            tst.IdSucursal is not null and tst.IdSucursal > 0, 
                        sucursal(tst.IdSucursal), 
                            case tst.IdTipoServicio
                                    when 11 then sucursal((select IdSucursal from t_censos_generales where IdServicio = tst.Id order by Id desc limit 1))
                            when 12 then sucursal((select IdSucursal from t_mantenimientos_generales where IdServicio = tst.Id order by Id desc limit 1))
                            end
                    ) as Sucursal, "
                . "tst.FechaCreacion as FechaServicio, "
                . "tst.FechaInicio, "
                . "if(tst.FechaFirma is not null and tst.FechaFirma <> '', tst.FechaFirma, tst.FechaConclusion) as FechaConclusion, "
                . "estatus(tst.IdEstatus) as EstatusServicio, "
                . "tst.Descripcion as DescripcionServicio, "
                . "tst.Firma, "
                . "tst.NombreFirma, "
                . "tst.CorreoCopiaFirma, "
                . "tst.FechaFirma, "
                . "nombreUsuario(tst.Atiende) as AtiendeServicio, "
                . "tst.Atiende, "
                . "case "
                . " when ts.IdEstatus in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
                . " when ts.IdEstatus in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
                . "end as TiempoSolicitud, "
                . ""
                . "case "
                . " when tst.IdEstatus  in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
                . " when tst.IdEstatus  in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
                . "end as TiempoServicio "
                . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
                . "on tst.IdSolicitud = ts.Id "
                . "where tst.Id = '" . $servicio . "';";
        $detallesSolicitud = $this->DBS->consultaGeneralSeguimiento($sentencia);

        $arrayReturn = array();
        if (array_key_exists(0, $detallesSolicitud)) {
            $arrayReturn['solicitud'] = ($detallesSolicitud[0]['Solicitud'] !== '') ? $detallesSolicitud[0]['Solicitud'] : 'Sin Información';
            $arrayReturn['servicio'] = ($detallesSolicitud[0]['Servicio'] !== '') ? $detallesSolicitud[0]['Servicio'] : 'Sin Información';
            $arrayReturn['folio'] = ($detallesSolicitud[0]['Folio'] !== '') ? $detallesSolicitud[0]['Folio'] : 'Sin Información';
            $arrayReturn['solicitante'] = ($detallesSolicitud[0]['Solicitante'] !== '') ? $detallesSolicitud[0]['Solicitante'] : 'Sin Información';
            $arrayReturn['fechaSolicitud'] = ($detallesSolicitud[0]['FechaSolicitud'] !== '') ? $detallesSolicitud[0]['FechaSolicitud'] : 'Sin Información';
            $arrayReturn['departamentoSolcitud'] = ($detallesSolicitud[0]['DepartamentoSolicitud'] !== '') ? $detallesSolicitud[0]['DepartamentoSolicitud'] : 'Sin Información';
            $arrayReturn['areaSolicitud'] = ($detallesSolicitud[0]['AreaSolicitud'] !== '') ? $detallesSolicitud[0]['AreaSolicitud'] : 'Sin Información';
            $arrayReturn['estatusSolicitud'] = ($detallesSolicitud[0]['EstatusSolicitud'] !== '') ? $detallesSolicitud[0]['EstatusSolicitud'] : 'Sin Información';
            $arrayReturn['asuntoSolicitud'] = ($detallesSolicitud[0]['AsuntoSolicitud'] !== '') ? $detallesSolicitud[0]['AsuntoSolicitud'] : 'Sin Información';
            $arrayReturn['descripcionSolicitud'] = ($detallesSolicitud[0]['DescripcionSolicitud'] !== '') ? $detallesSolicitud[0]['DescripcionSolicitud'] : 'Sin Información';
            $arrayReturn['prioridad'] = ($detallesSolicitud[0]['Prioridad'] !== '') ? $detallesSolicitud[0]['Prioridad'] : 'Sin Información';
            $arrayReturn['ticket'] = ($detallesSolicitud[0]['Ticket'] !== '') ? $detallesSolicitud[0]['Ticket'] : 'Sin Información';
            $arrayReturn['sucursal'] = ($detallesSolicitud[0]['Sucursal'] !== '') ? $detallesSolicitud[0]['Sucursal'] : 'Sin Información';
            $arrayReturn['tipoServicio'] = ($detallesSolicitud[0]['TipoServicio'] !== '') ? $detallesSolicitud[0]['TipoServicio'] : 'Sin Información';
            $arrayReturn['fechaServicio'] = ($detallesSolicitud[0]['FechaServicio'] !== '') ? $detallesSolicitud[0]['FechaServicio'] : 'Sin Información';
            $arrayReturn['fechaInicio'] = ($detallesSolicitud[0]['FechaInicio'] !== '') ? $detallesSolicitud[0]['FechaInicio'] : 'Sin Información';
            $arrayReturn['fechaConclusion'] = ($detallesSolicitud[0]['FechaConclusion'] !== '') ? $detallesSolicitud[0]['FechaConclusion'] : 'Sin Información';
            $arrayReturn['estatusServicio'] = ($detallesSolicitud[0]['EstatusServicio'] !== '') ? $detallesSolicitud[0]['EstatusServicio'] : 'Sin Información';
            $arrayReturn['descripcionServicio'] = ($detallesSolicitud[0]['DescripcionServicio'] !== '') ? $detallesSolicitud[0]['DescripcionServicio'] : 'Sin Información';
            $arrayReturn['firma'] = ($detallesSolicitud[0]['Firma'] !== NULL) ? $detallesSolicitud[0]['Firma'] : 'Sin Información';
            $arrayReturn['nombreFirma'] = ($detallesSolicitud[0]['NombreFirma'] !== NULL) ? $detallesSolicitud[0]['NombreFirma'] : 'Sin Información';
            $arrayReturn['correoCopiaFirma'] = ($detallesSolicitud[0]['CorreoCopiaFirma'] !== NULL) ? $detallesSolicitud[0]['CorreoCopiaFirma'] : 'Sin Información';
            $arrayReturn['fechaFirma'] = ($detallesSolicitud[0]['FechaFirma'] !== NULL) ? $detallesSolicitud[0]['FechaFirma'] : 'Sin Información';
            $arrayReturn['atiendeServicio'] = ($detallesSolicitud[0]['AtiendeServicio'] !== NULL) ? $detallesSolicitud[0]['AtiendeServicio'] : 'Sin Información';
            $arrayReturn['atiende'] = ($detallesSolicitud[0]['Atiende'] !== NULL) ? $detallesSolicitud[0]['Atiende'] : 'Sin Información';
            $arrayReturn['tiempoSolicitud'] = ($detallesSolicitud[0]['TiempoSolicitud'] !== '') ? $detallesSolicitud[0]['TiempoSolicitud'] : 'Sin Información';
            $arrayReturn['tiempoServicio'] = ($detallesSolicitud[0]['TiempoServicio'] !== '') ? $detallesSolicitud[0]['TiempoServicio'] : 'Sin Información';
        }
        return $arrayReturn;
    }

    public function consultaInformacionCorrectivo(string $servicio) {
        $sentencia = 'SELECT 
                        tcg.*,
                        areaAtencion(IdArea) AS NombreArea,
                        (SELECT Equipo FROM v_equipos WHERE Id = tcg.IdModelo) AS Equipo
                    FROM t_correctivos_generales tcg
                    WHERE tcg.IdServicio = "' . $servicio . '"';
        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function consultaCorrectivosDiagnostico(string $servicio) {
        $sentencia = 'SELECT 
                        tcd.*,
                        (SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = tcd.IdTipoDiagnostico) AS NombreTipoDiagnostico,
                        (SELECT Nombre FROM cat_v3_tipos_falla WHERE Id = tcd.IdTipoFalla) AS NombreTipoFalla,
                        (SELECT Nombre FROM cat_v3_fallas_equipo WHERE Id = IdFalla) AS NombreFalla,
                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = IdComponente) AS Componente
                        FROM t_correctivos_diagnostico tcd
                        WHERE tcd.Id = (SELECT MAX(Id) FROM t_correctivos_diagnostico WHERE IdServicio = "' . $servicio . '" )';

        $consulta = $this->DBS->consultaGeneralSeguimiento($sentencia);

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaCorrectivoProblema(string $servicio, string $folio, string $key) {
        $informacionSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $tabla = '';
        $descripcionProblema = '';
        $datosProblema = '';

        $data['idTipoProblema'] = $this->DBS->consultaGeneralSeguimiento('SELECT Id, IdTipoProblema, RecibeSolicitud FROM t_correctivos_problemas WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');


        if (!empty($data['idTipoProblema'])) {
            $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'Problema', $folio);
            switch ($data['idTipoProblema'][0]['IdTipoProblema']) {
                case '1':
                    $solicitudRefaccion = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = IdRefaccion) Refaccion,
                                                                                Cantidad
                                                                        FROM t_correctivos_solicitudes_refaccion 
                                                                        WHERE IdServicioOrigen = "' . $servicio . '"');
                    $descripcionProblema .= 'Se ha solicitado la refacción(es) a ';
                    foreach ($solicitudRefaccion as $key => $value) {
                        $tabla .= "<div>Refacción: " . $value['Refaccion'] . " &nbsp Cantidad: " . $value['Cantidad'] . "</div>";
                    }
                    break;
                case '2':
                    $solicitudEquipo = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                modelo(IdModelo) Equipo,
                                                                                Cantidad
                                                                        FROM t_correctivos_solicitudes_equipo 
                                                                        WHERE IdServicioOrigen = "' . $servicio . '"');
                    $descripcionProblema .= 'Se ha solicitado el equipo(s) a ';
                    foreach ($solicitudEquipo as $key => $value) {
                        $tabla .= "<div>Equipo: " . $value['Equipo'] . " &nbsp Cantidad: " . $value['Cantidad'] . "</div>";
                    }
                    break;
                case '3';
                    $equipoGarantia = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                    EsRespaldo,
                                                                                    SolicitaEquipo,
                                                                                    modelo(IdModelo) Equipo,
                                                                                    Serie
                                                                                FROM t_correctivos_garantia_respaldo
                                                                                WHERE IdServicio = "' . $servicio . '"
                                                                                ORDER BY Id DESC LIMIT 1');
                    if ($equipoGarantia[0]['EsRespaldo'] === '1' && $equipoGarantia[0]['SolicitaEquipo'] === '0') {
                        $descripcionProblema .= 'Se ha dejado equipo de respaldo';
                        $tabla .= "<div>Equipo Respaldo: " . $equipoGarantia[0]['Equipo'] . " &nbsp Serie: " . $equipoGarantia[0]['Serie'] . "</div>";
                    } else if ($equipoGarantia[0]['EsRespaldo'] === '0' && $equipoGarantia[0]['SolicitaEquipo'] === '0') {
                        $descripcionProblema .= 'No se encuentra equipo de respaldo';
                    }

                    break;
            }

            if ($data['idTipoProblema'][0]['IdTipoProblema'] === '1' || $data['idTipoProblema'][0]['IdTipoProblema'] === '2') {
                switch ($data['idTipoProblema'][0]['RecibeSolicitud']) {
                    case '1':
                        $recibeSolicitud = 'Almacén';
                        break;
                    case '2':
                        $recibeSolicitud = 'TI';
                        break;
                    case '3':
                        $recibeSolicitud = 'Multimedia';
                        break;
                    default :
                        $recibeSolicitud = '';
                        break;
                }
            } else {
                $recibeSolicitud = '';
            }

            $datos = array(
                'servicio' => $servicio,
                'ticket' => $informacionSolicitud['ticket']
            );

            $path = $this->cargarPDF($datos);
            $link = "<div><a href='" . $path . "' target='_blank'>DOCUMENTO PDF</a></div>";
            $datosProblema .= '<br><div style="color:#FF0000";>***PROBLEMA***</div><div>' . $descripcionProblema . $recibeSolicitud . '</div>' . $tabla . $link;

            return $datosProblema;
        }
    }

    public function getInformacionServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "tst.Ticket, "
                . "if(tst.IdSucursal is not null and tst.IdSucursal > 0, sucursal(tst.IdSucursal),'') as Sucursal, "
                . "tst.IdTipoServicio, "
                . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
                . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
                . "tst.FechaCreacion as FechaServicio, "
                . "estatus(tst.IdEstatus) as EstatusServicio, "
                . "tst.Descripcion as DescripcionServicio, "
                . "case "
                . " when ts.IdEstatus in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
                . " when ts.IdEstatus in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
                . "end as TiempoSolicitud, "
                . ""
                . "case "
                . " when tst.IdEstatus  in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
                . " when tst.IdEstatus  in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
                . "end as TiempoServicio "
                . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
                . "on tst.IdSolicitud = ts.Id "
                . "where tst.Id = '" . $servicio . "';";
        return $this->DBS->consultaGeneralSeguimiento($sentencia);
    }

    public function linkDetallesServicio(string $servicio) {
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $detallesServicio = 'https://siccob.solutions/Detalles/Servicio/' . $servicio;
        } else {
            $detallesServicio = 'http://' . $host . '/Detalles/Servicio/' . $servicio;
        }
        return $detallesServicio;
    }

    public function cargarPDF(array $datos) {
        $host = $_SERVER['SERVER_NAME'];
        $linkPdf = $this->getServicioToPdf($datos);
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        return $path;
    }

    public function getServicioToPdf(array $servicio, string $nombreExtra = NULL) {
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'] . '/' . $nombreExtra;
        $datosServicio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                sucursal(IdSucursal) Sucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $servicio['servicio'] . '"');
        $link = $this->Phantom->htmlToPdf($archivo, $ruta, $datosServicio[0]);
        return ['link' => $link];
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function guardarDatosServiceDesk(string $servicio, bool $servicioConcluir = FALSE) {
        $informacionSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->MSP->getApiKeyByUser($informacionSolicitud['atiende']);
        $folio = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                            ts.Folio 
                                        FROM t_servicios_ticket tst
                                        INNER JOIN t_solicitudes ts
                                            ON ts.Id = tst.IdSolicitud
                                        WHERE tst.Id = "' . $servicio . '"');

        if (!empty($folio[0]['Folio'])) {
            if ($folio[0]['Folio'] !== NULL) {
                if ($folio[0]['Folio'] !== '') {
                    if ($folio[0]['Folio'] !== '0') {
                        $descripcion = $this->MostrarDatosSD($folio[0]['Folio'], $servicio, $servicioConcluir, $key);

                        if ($descripcion['estatus']) {
                            $ServiceDesck = $this->ServiceDesk->setResolucionServiceDesk($key, $folio[0]['Folio'], $descripcion['html']);
                            if ($ServiceDesck->operation->result->status !== 'Success') {
                                return $ServiceDesck->operation->result->message;
                            } else {
                                return TRUE;
                            }
                        } else {
                            return $descripcion['estatus'];
                        }
                    }
                }
            }
        }
    }

    public function validarServicio(array $datos) {
        $dataServicio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                            Id,
                                                            (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                                        FROM t_servicios_ticket
                                                        WHERE Id = "' . $datos['servicio'] . '"');

        if (!empty($dataServicio)) {
            if (!empty($dataServicio[0]['Folio']) || $dataServicio[0]['Folio'] != '0') {
                $resultadoSD = $this->guardarDatosServiceDesk($datos['servicio']);

                if ($resultadoSD !== TRUE) {
                    return $resultadoSD;
                } else {
                    return $resultadoSD;
                }
            } else {
                return 'noTieneFolio';
            }
        } else {
            return 'noExisteServicio';
        }
    }

    public function validarFolioServicio(array $datos) {
        $dataServicio = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                            Id,
                                                            (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                                        FROM t_servicios_ticket
                                                        WHERE Id = "' . $datos['servicio'] . '"');
        if (!empty($dataServicio)) {
            if ($dataServicio[0]['Folio'] && $dataServicio[0]['Folio'] !== '0') {
                return TRUE;
            } else {
                return 'noTieneFolio';
            }
        } else {
            return 'noExisteServicio';
        }
    }

    /*
     * Encargado de crear un arreglo con los datos de Service Desk
     * 
     */

    public function datosSD(string $solicitud) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->MSP->getApiKeyByUser($usuario['Id']);
        $dataFolio = $this->DBS->consultaGeneralSeguimiento('SELECT Folio FROM t_solicitudes WHERE Id = "' . $solicitud . '"');

        if (!empty($dataFolio[0]['Folio'])) {
            $datosSD = $this->ServiceDesk->getDetallesFolio($key, $dataFolio[0]['Folio']);
            $datosResolucionSD = json_decode($this->ServiceDesk->getResolucionFolio($key, $dataFolio[0]['Folio']));

            if (!empty($datosResolucionSD)) {
                if ($datosResolucionSD->operation->result->status === 'Success') {
                    if (isset($datosSD->WORKORDERID)) {
                        $data['creadoSD'] = $datosSD->CREATEDBY;
                        $data['fechaSolicitudSD'] = date('Y-m-d H:i:s', $datosSD->CREATEDTIME / 1000);
                        (!isset($datosSD->PRIORITY)) ? $prioridad = 'Baja' : $prioridad = $datosSD->PRIORITY;
                        $data['prioridadSD'] = $prioridad;
                        $data['solicitaSD'] = $datosSD->REQUESTER;
                        $data['asignadoSD'] = $datosSD->TECHNICIAN;
                        $data['estatusSD'] = $datosSD->STATUS;
                        $data['asuntoSD'] = $datosSD->SUBJECT;
                        $data['descripcionSD'] = $datosSD->DESCRIPTION;
                        if (isset($datosResolucionSD->operation->Details)) {
                            $nombreUsuarioSD = $this->ServiceDesk->nombreUsuarioServiceDesk($key, $datosResolucionSD->operation->Details->RESOLVER);
                            $data['nombreUsuarioResolucionSD'] = $nombreUsuarioSD;
                            $data['fechaResolucionSD'] = date('Y-m-d H:i:s', $datosResolucionSD->operation->Details->LASTUPDATEDTIME / 1000);
                            $data['resolucionSD'] = $datosResolucionSD->operation->Details->RESOLUTION;
                        } else {
                            $data['nombreUsuarioResolucionSD'] = 'Sin Resolución';
                            $data['fechaResolucionSD'] = 'Sin Resolución';
                            $data['resolucionSD'] = 'Sin Resolución';
                        }
                    } else {
                        $data = NULL;
                    }
                } else {
                    $data = NULL;
                }
            } else {
                $data = 'El sistema de ServiceDesk no se encuentra disponible por el momento.';
            }
        } else {
            $data = NULL;
        }

        return $data;
    }

    public function catalogoSD() {
        $usuario = $this->Usuario->getDatosUsuario();
        $catalogoUsuariosSD = json_decode($this->ServiceDesk->getTecnicosSD($usuario['SDKey']));
        return $catalogoUsuariosSD->operation->details;
    }

    public function sucursalServicio(string $servicio = null) {
        $sucursal = '';

        if ($servicio !== null) {
            $dataSucursal = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                            sucursal(IdSucursal) Sucursal
                                                        FROM t_servicios_ticket
                                                        WHERE Id = "' . $servicio . '"');
            if ($dataSucursal !== null) {
                $sucursal = ' - ' . $dataSucursal[0]['Sucursal'];
            }
        }

        return $sucursal;
    }
    
    public function checklist(array $datos) {
        $linkPdf = $this->rutaPDF($datos);
        $descripcion = "<div>Ha concluido el Servicio Checklist</div><br/>
                        <a href='" . $linkPdf . "' target='_blank'>DOCUMENTO PDF</a>";
        
        return $descripcion;
    }
    
    public function rutaPDF(array $datos) {
        $host = $_SERVER['SERVER_NAME'];
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $ruta = '/storage/Archivos//Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $ruta;
        }

        return $path;
    }

}
