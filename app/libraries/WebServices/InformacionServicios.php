<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

class InformacionServicios extends General {

    private $DBS;
    private $DBB;
    private $Correo;
    private $ServiceDesk;
    private $MSP;
    private $MSD;
    private $DBST;
    private $DBC;
    private $DBM;
    private $pdf;
    private $x;
    private $y;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->MSD = \Modelos\Modelo_ServiceDesk::factory();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBC = \Modelos\Modelo_Censos::factory();
        $this->DBM = \Modelos\Modelo_Mantenimiento::factory();
    }

//    public function MostrarDatosSD(string $folio) {
//        $host = $_SERVER['SERVER_NAME'];
//        $pdf = $this->pdfFromFolio(array('folio' => $folio));
//
//        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
//            $path = 'https://siccob.solutions/' . $pdf['uri'];
//        } else {
//            $path = 'http://' . $host . '/' . $pdf['uri'];
//        }
//
//        $html = "<div>Se resuelve el incidente del folio:" . $folio . "</div>";
//        $html .= "<div><a href='" . $path . "' target='_blank'>Resumen documento PDF</a></div>";
//        return array('html' => $html);
//    }

    public function MostrarDatosSD(string $folio, string $servicio = NULL, bool $servicioConcluir = FALSE, string $key) {
        $html = '';
        $estatus = TRUE;

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
            $generales = $this->getGeneralesServicio($servicio);
            if ($generales['IdEstatus'] == 3) {
                $html .= $this->consultaCorrectivoProblema($servicio, $folio, $key);
            }
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
                                                                        GROUP BY TABLAS.Id
                                                                        ORDER BY FIELD (IdEstatus, 2,4), FechaConclusion DESC');

        if (!empty($serviciosConcluidos)) {
            foreach ($serviciosConcluidos as $key => $value) {
                $html .= $this->vistaHTMLServicio($value);
            }

            $html .= $this->avancesProblemasServicio($folio);
        }

        return array('html' => $html);
    }

    public function vistaHTMLServicio(array $value) {
        if ($value['Seguimiento'] === '1') {
            switch ($value['IdTipoServicio']) {
                case '27':
                    $html = $this->checklist(array(
                        'servicio' => $value['Id'],
                        'ticket' => $value['Ticket']
                    ));
                    break;
                case '20':
                    $html = $this->correctivo(array(
                        'servicio' => $value['Id'],
                        'ticket' => $value['Ticket']
                    ));
                    break;
                case '12':
                case '11':
                    $html = $this->servicioSinDetalles(array(
                        'servicio' => $value['Id'],
                        'ticket' => $value['Ticket']
                    ));
                    break;
                case '5':
                    $html = $this->trafficService(array(
                        'servicio' => $value['Id'],
                        'ticket' => $value['Ticket']
                    ));
                    break;
            }
        } else {
            $html = $this->sinClasificar(array(
                'servicio' => $value['Id'],
                'ticket' => $value['Ticket']
            ));
        }

        return $html;
    }

    public function cambiarEstatusResolucionSD(array $datos, array $servicios) {
        $datosServicios = $this->DBST->consultaServicio($datos['Servicio']);
        if (isset($datos['Servicio'])) {
            $servicioLaboratorio = $this->DBST->consultaServicioLaboratorio($datos['Servicio']);

            if ($servicioLaboratorio[0]['IdDepartamento'] === '10') {
                $htmlServicio = $this->vistaHTMLServicio($datosServicios[0]);
                $this->setNoteAndWorkLog(array('key' => $datos['Key'], 'folio' => $datos['Folio'], 'html' => $htmlServicio));
                $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'En Atención', $datos['Folio']);
            } else {
                if (!empty($servicios)) {
                    foreach ($servicios as $key => $value) {
                        if ($value['IdEstatus'] === '3') {
//                            $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'Problema', $datos['Folio']);
                        } else {
                            $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'En Atención', $datos['Folio']);
                        }
                    }
                    $htmlServicio = $this->vistaHTMLServicio($datosServicios[0]);
                    $this->setNoteAndWorkLog(array('key' => $datos['Key'], 'folio' => $datos['Folio'], 'html' => $htmlServicio));
                } else {
                    $informacionServicios = $this->MostrarDatosSD($datos['Folio'], $datos['Servicio'], $datos['ServicioConcluir'], $datos['Key']);
                    $this->ServiceDesk->setResolucionServiceDesk($datos['Key'], $datos['Folio'], $informacionServicios['html']);
                    $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'Completado', $datos['Folio']);
                }
            }
        } else {
            if (!empty($servicios)) {
                foreach ($servicios as $key => $value) {
                    if ($value['IdEstatus'] === '3') {
//                        $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'Problema', $datos['Folio']);
                    } else {
                        $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'En Atención', $datos['Folio']);
                    }
                }
                $htmlServicio = $this->vistaHTMLServicio($datosServicios[0]);
                $this->setNoteAndWorkLog(array('key' => $datos['Key'], 'folio' => $datos['Folio'], 'html' => $htmlServicio));
            } else {
                $informacionServicios = $this->MostrarDatosSD($datos['Folio'], $datos['Servicio'], $datos['ServicioConcluir'], $datos['Key']);
                $this->ServiceDesk->setResolucionServiceDesk($datos['Key'], $datos['Folio'], $informacionServicios['html']);
                $resultadoSD = $this->ServiceDesk->cambiarEstatusServiceDesk($datos['Key'], 'Completado', $datos['Folio']);
            }
        }

        return ['code' => 200, 'message' => 'correcto'];
    }

    public function verificarTodosServiciosFolio(array $datos) {
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
                'AND tse.IdEstatus in (1,2,3,5,10,12)
                                                                    AND tse.IdTipoServicio not in (21,41)');
        return $servicios;
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
                    $linkImagenesSolucion .= "<a href='http://" . $host . $value . "' target='_blank'>Archivo" . $contSolucion . "</a> &nbsp ";
                }
            }

            $path = $this->definirPDF($datos);

            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $path = 'http://siccob.solutions/' . $path;
            } else {
                $path = 'http://' . $host . '/' . $path;
            }

            $descripcionConclusionSD = '<div>Descripción: ' . $datosDescripcionConclusion[0]['DescripcionServicio'] . '</div>';
            $descripcion = $datosDescripcionConclusion[0]['Sucursal'] . ' ' . $infoServicio[0]['TipoServicio'] . ' se concluyo con exito';
            $datosResolucion = '<br>' . $descripcion . $descripcionConclusionSD . $linkImagenesSolucion . "<div><a href='" . $path . "' target='_blank'>Documento PDF</a></div>";
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

        $linkPdf = $this->definirPDF($datos);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $linkPdf = 'http://siccob.solutions/' . $linkPdf;
        } else {
            $linkPdf = 'http://' . $host . '/' . $linkPdf;
        }

        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($usuario['Id']));


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
                $linkImagenesDiagnostico .= "<a href='http://" . $host . $value . "' target='_blank'>Archivo" . $contDiagnostico . "</a> &nbsp";
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
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "' target='_blank'>Archivo" . $contSolucion . "</a> &nbsp ";
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
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "' target='_blank'>Archivo" . $contSolucion . "</a> &nbsp ";
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
                            $linkImagenesSolucion .= "<a href='http://" . $host . $value . "' target='_blank'>Archivo" . $contSolucion . "</a> &nbsp ";
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
        $host = $_SERVER['SERVER_NAME'];
        $infoServicio = $this->getInformacionServicio($datos['servicio']);

        $datosDescripcionConclusion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                                sucursal(tst.IdSucursal) Sucursal
                                                                            FROM t_servicios_ticket tst
                                                                            WHERE tst.Id = "' . $datos['servicio'] . '"');

        $pdf = $this->definirPDF($datos);
        
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'http://siccob.solutions/' . $pdf;
        } else {
            $path = 'http://' . $host . '/' . $pdf;
        }

        $descripcion = $datosDescripcionConclusion[0]['Sucursal'] . ' ' . $infoServicio[0]['TipoServicio'] . ' se concluyo con exito';
        $datosResolucion = '<br>' . $descripcion . "<div><a href='" . $path . "' target='_blank'>Documento PDF</a></div>";

        return $datosResolucion;
    }

    public function avancesProblemasServicio(string $folio) {
        $datosAvancesProblemas = '';
        $datosAvances = '***AVANCES***<br>';
        $datosProblemas = "<br><p style='color:#FF0000';>***PROBLEMAS***</p>";
        $avancesProblemas = '';

        $serviciosAvancesServicios = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                    tsa.* 
                                                                            FROM t_servicios_avance tsa
                                                                            INNER JOIN t_servicios_ticket tst
                                                                            ON tsa.IdServicio = tst.Id
                                                                            INNER JOIN t_solicitudes ts
                                                                            ON tst.IdSolicitud = ts.Id
                                                                            WHERE ts.Folio = "' . $folio . '"
                                                                            AND tsa.Flag = "1"
                                                                            ORDER BY tsa.Fecha DESC');

        foreach ($serviciosAvancesServicios as $value) {
            $avancesProblemas = $this->crearVistaAvanceProblema($value);
            if ($avancesProblemas['tipo'] === 'Avance') {
                $datosAvances .= $avancesProblemas['datosAvancesProblemas'];
            } else {
                $datosProblemas .= $avancesProblemas['datosAvancesProblemas'];
            }
        }

        if ($datosAvances == '***AVANCES***<br>') {
            $datosAvances = '';
        }

        if ($datosProblemas == "<br><p style='color:#FF0000';>***PROBLEMAS***</p>") {
            $datosProblemas = '';
        }

        $datosAvancesProblemas = $datosProblemas . $datosAvances;

        return $datosAvancesProblemas;
    }

    public function crearVistaAvanceProblema(array $datos) {
        $host = $_SERVER['SERVER_NAME'];
        $contAvanceProblema = 0;
        $linkImagenes = '';
        $tabla = '';
        $datosAvancesProblemas = '';
        $tablaAvancesProblemas = $this->DBST->serviciosAvanceEquipo($datos['Id']);

        if (!empty($tablaAvancesProblemas)) {
            foreach ($tablaAvancesProblemas as $key => $valor) {
                if ($valor['IdItem'] === '1') {
                    if ($datos['IdTipo'] === '1') {
                        $tabla .= "<div>" . $valor['Tipo'] . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Serie: " . $valor['Serie'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                    } else {
                        $tabla .= "<div>" . $valor['Tipo'] . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                    }
                } else {
                    $tabla .= "<div>" . $valor['Tipo'] . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                }
            }
        }

        $archivosAvanceProblema = explode(',', $datos['Archivos']);

        foreach ($archivosAvanceProblema as $v) {
            if ($v != '') {
                $contAvanceProblema++;
                $linkImagenes .= "<a href='http://" . $host . $v . "' target='_blank'>Archivo" . $contAvanceProblema . "</a> &nbsp ";
            }
        }

        if ($datos['IdTipo'] === '1') {
            $tipo = 'Avance';
        } else {
            $tipo = 'Problema';
        }

        $datosAvancesProblemas .= "<div>" . $datos['Descripcion'] . "</div>" . $tabla . "<div>" . $linkImagenes . "</div><br>";

        return array('datosAvancesProblemas' => $datosAvancesProblemas, 'tipo' => $tipo);
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
                        tcd . *,
                        (SELECT 
                                Nombre
                            FROM
                                cat_v3_tipos_diagnostico_correctivo
                            WHERE
                                Id = tcd.IdTipoDiagnostico) AS NombreTipoDiagnostico,
                        (SELECT 
                                Nombre
                            FROM
                                cat_v3_tipos_falla
                            WHERE
                                Id = tcd.IdTipoFalla) AS NombreTipoFalla,
                        IF(tcd.IdComponente IS NULL,
                            (SELECT 
                                    Nombre
                                FROM
                                    cat_v3_fallas_equipo
                                WHERE
                                    Id = IdFalla),
                            (SELECT 
                                    Nombre
                                FROM
                                    cat_v3_fallas_refaccion
                                WHERE
                                    Id = IdFalla)) AS NombreFalla,
                        (SELECT 
                                Nombre
                            FROM
                                cat_v3_componentes_equipo
                            WHERE
                                Id = IdComponente) AS Componente
                    FROM
                        t_correctivos_diagnostico tcd
                    WHERE
                        tcd.Id = (SELECT 
                                MAX(Id)
                            FROM
                                t_correctivos_diagnostico
                            WHERE
                                IdServicio = "' . $servicio . '" )';

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
//            $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'Problema', $folio);
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
                    default:
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

            $path = $this->definirPDF($datos);
            $host = $_SERVER['SERVER_NAME'];
            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $path = 'https://siccob.solutions/' . $path;
            } else {
                $path = 'http://' . $host . '/' . $path;
            }
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
        } elseif ($host === 'pruebas.siccob.solutions' || $host === 'www.pruebas.siccob.solutions') {
            $detallesServicio = 'https://pruebas.siccob.solutions/Detalles/Servicio/' . $servicio;
        } else {
            $detallesServicio = 'http://' . $host . '/Detalles/Servicio/' . $servicio;
        }
        return $detallesServicio;
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function guardarDatosServiceDesk(string $servicio, bool $servicioConcluir = FALSE) {
        $folio = $this->DBST->consultaFolio($servicio);

        if ($folio !== '0') {
            if ($folio !== NULL) {
                $atiende = $this->DBST->atiendeServicio($servicio);
                $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($atiende[0]['Atiende']));
                $datos = array(
                    'Folio' => $folio,
                    'Servicio' => $servicio,
                    'ServicioConcluir' => $servicioConcluir,
                    'Key' => $key
                );
                $servicios = $this->verificarTodosServiciosFolio($datos);

                $this->cambiarEstatusResolucionSD($datos, $servicios);
            }
        }
        return ['code' => 200, 'message' => 'correcto'];
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
                return $resultadoSD;
            } else {
                throw new \Exception('No cuenta con folio.');
            }
        } else {
            throw new \Exception('No existen servicios para esta solicitud.');
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
        $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($usuario['Id']));
        $dataFolio = $this->DBS->consultaGeneralSeguimiento('SELECT Folio FROM t_solicitudes WHERE Id = "' . $solicitud . '"');

        if (!empty($dataFolio[0]['Folio'])) {
            $datosSD = $this->ServiceDesk->getDetallesFolio($key, $dataFolio[0]['Folio']);
            $datosResolucionSD = $this->ServiceDesk->getResolucionFolio($key, $dataFolio[0]['Folio']);
            $datosNotasSD = $this->ServiceDesk->getNotas($key, $dataFolio[0]['Folio']);

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

                        if (isset($datosNotasSD->operation->Details)) {
                            $data['notasSD'] = array();
                            $detallesNotas = $datosNotasSD->operation->Details;
                            foreach ($detallesNotas as $key => $value) {
                                $data['notasSD'][$key]['nombreUsuario'] = $value->USERNAME;
                                $data['notasSD'][$key]['fecha'] = date('Y-m-d H:i:s', $value->NOTESDATE / 1000);
                                $data['notasSD'][$key]['texto'] = $value->NOTESTEXT;
                            }
                        } else {
                            $data['nombreUsuarioResolucionSD'] = 'Sin Resolución';
                            $data['fechaResolucionSD'] = 'Sin Resolución';
                            $data['resolucionSD'] = 'Sin Resolución';
                            $data['notasSD'] = 'Sin notas';
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
        $catalogoUsuariosSD = $this->ServiceDesk->getTecnicosSD($usuario['SDKey']);
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
        $host = $_SERVER['SERVER_NAME'];
        
        $linkPdf = $this->definirPDF($datos);
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'http://siccob.solutions/' . $linkPdf;
        } else {
            $path = 'http://' . $host . '/' . $linkPdf;
        }
        
        $descripcion = "<div>Ha concluido el Correctivo Proactivo</div><br/><a href='" . $path . "' target='_blank'>DOCUMENTO PDF</a>";

        return $descripcion;
    }

    public function trafficService(array $datos) {
        $host = $_SERVER['SERVER_NAME'];
        $linkPdf = $this->definirPDF($datos);
        
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'http://siccob.solutions/' . $linkPdf;
        } else {
            $path = 'http://' . $host . '/' . $linkPdf;
        }
        
        $descripcion = "<br/><div>Se ha realizo un servicio de Tráfico</div><a href='" . $path . "' target='_blank'>DOCUMENTO PDF</a><br/>";
        return $descripcion;
    }

    public function verifyProcess(array $datos) {
        if (isset($datos['servicioConcluir']) && $datos['servicioConcluir'] === 'true') {
            $servicioConcluir = TRUE;
        } else {
            $servicioConcluir = FALSE;
        }

        $this->guardarDatosServiceDesk($datos['servicio'], $servicioConcluir);

        return ['code' => 200, 'message' => 'correcto'];
    }

    public function setHTMLService(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $datosServicios = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                            ts.Folio,
                                            tst.Id,
                                            tst.Ticket,
                                            tst.IdTipoServicio,
                                            (SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = tst.IdTipoServicio) Seguimiento,
                                            tst.IdEstatus,
                                            tst.FechaConclusion,
                                            (SELECT Atiende FROM t_solicitudes WHERE Id = tst.IdSolicitud) Atiende
                                        FROM t_servicios_ticket tst
                                        INNER JOIN t_solicitudes ts
                                            ON ts.Id = tst.IdSolicitud
                                        WHERE tst.Id = "' . $datos['servicio'] . '"');

        $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($usuario['Id']));
        $htmlServicio = $this->vistaHTMLServicio($datosServicios[0]);
        $datosNotasSD = $this->setNoteAndWorkLog(array('key' => $key, 'folio' => $datosServicios[0]['Folio'], 'html' => $htmlServicio));
        return $datosNotasSD;
    }

    public function setNoteAndWorkLog(array $data) {
        try {
            if (!empty($data['folio'])) {
                if ($data['folio'] !== '0') {
                    $datosNotasSD = $this->ServiceDesk->setNoteServiceDesk($data['key'], $data['folio'], $data['html']);

                    if ($datosNotasSD->operation->result->status !== 'Success') {
                        ['code' => 400, 'error' => $datosNotasSD];
                    } else {
                        $datosHistorialTrabajoSD = $this->ServiceDesk->setWorkLogServiceDesk($data['key'], $data['folio'], $data['html']);
                        if ($datosHistorialTrabajoSD->operation->result->status !== 'Success') {
                            ['code' => 400, 'error' => $datosHistorialTrabajoSD];
                        }
                    }
                }
            }

            return TRUE;
        } catch (Exception $err) {
            return $err;
        }
    }

    public function getApiKeyByUser(string $usuario) {
        $keyTecnico = $this->MSP->getApiKeyByUser($usuario);

        if (empty($keyTecnico)) {
            $keyTecnico = $this->MSD->apiKeyJefe($usuario);
        }

        $respuestaKey = $this->ServiceDesk->validarKey($keyTecnico);

        if ($respuestaKey['code'] === 400) {
            $key = $this->MSD->apiKeyJefe($usuario);
        } else {
            $key = $respuestaKey['messege'];
        }

        return $key;
    }

    private function getServiciosByFolio($folio) {
        $consulta = $this->DBS->consulta("select 
        tst.Id,
		tst.IdTipoServicio,
		(select Seguimiento from cat_v3_servicios_departamento where Id = tst.IdTipoServicio) as HasSeguimiento
        from t_servicios_ticket tst
        where IdSolicitud in (
            select 
            Id 
            from t_solicitudes 
            where Folio = '" . $folio . "'
        )");
        return $consulta;
    }

    private function getGeneralesServicio($servicio) {

        $consulta = $this->DBS->consulta("select 
        tst.Id,        
        if(folioByServicio(tst.Id) is null, '', folioByServicio(tst.Id)) as SD,
        tst.Ticket,
        nombreUsuario(tst.Atiende) as Atiende,
        (select cp.Nombre from cat_v3_usuarios cu join cat_perfiles cp on cu.IdPerfil = cp.Id where cu.Id = tst.Atiende) as Perfil,
        (select FechaCreacion from t_solicitudes where Id = tst.IdSolicitud) as FechaSolicitud,
        tst.FechaCreacion,
        tst.FechaInicio,
        tst.Descripcion,
        tst.IdSolicitud,
        tst.FechaConclusion,
        nombreUsuario(ts.Solicita) as Solicita,
        ts.FechaCreacion,
        tsi.Asunto,
        tsi.Descripcion as Solicitud,
        tst.IdSucursal,
        tst.IdEstatus,
        estatus(tst.IdEstatus) as Estatus,
        tst.IdTipoServicio,
        tipoServicio(tst.IdTipoServicio) as TipoServicio,
        (select Seguimiento from cat_v3_servicios_departamento where Id = tst.IdTipoServicio) as HasSeguimiento,
        sucursal(tst.IdSucursal) as Sucursal, 
        (select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal) as IdCliente,
        cliente((select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal)) as Cliente,
        if(
            tst.IdTipoServicio = 20, 
            (select FallaReportada from t_correctivos_generales where IdServicio = tst.Id),
            (select FallaReportada from t_servicios_generales where IdServicio = tst.Id) 
        ) as FallaReportada
        from t_servicios_ticket tst
        inner join t_solicitudes ts on tst.IdSolicitud = ts.Id
        inner join t_solicitudes_internas tsi on tsi.IdSolicitud = ts.Id
        where tst.Id = '" . $servicio . "'");

        return $consulta[0];
    }

    private function getDiagnosticoCorrectivoForPDF(int $id) {
        $consulta = $this->DBS->consulta("select 
        areaAtencion(tcg.IdArea) as Area,
        tcg.Punto,
        modelo(tcg.IdModelo) as Modelo,
        tcg.Serie,
        (select Nombre from cat_v3_tipos_diagnostico_correctivo where Id = tcd.IdTipoDiagnostico) as TipoDiagnostico,
        (select Nombre from cat_v3_componentes_equipo where Id = tcd.IdComponente) as Componente,
        concat(
            if(IdTipoDiagnostico = 4, 
                (select Nombre from cat_v3_fallas_refaccion where Id = tcd.IdFalla), 
                (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla)
            ),' (',
            if(IdTipoDiagnostico = 4, 
                (select Nombre from cat_v3_tipos_falla where Id = (select IdTipoFalla from cat_v3_fallas_refaccion where Id = tcd.IdFalla)), 
                (select Nombre from cat_v3_tipos_falla where Id = (select IdTipoFalla from cat_v3_fallas_equipo where Id = tcd.IdFalla))
            ),')'
        ) as Falla,
        (SELECT FirmaTecnico FROM t_servicios_ticket WHERE Id = tcg.IdServicio) AS FirmaTecnico,
        (SELECT nombreUsuario(IdTecnicoFirma) FROM t_servicios_ticket WHERE Id = tcg.IdServicio) AS Tecnico,
        tcd.*
        from t_correctivos_generales tcg
        inner join t_correctivos_diagnostico tcd on tcg.IdServicio = tcd.IdServicio
        where tcg.IdServicio = '" . $id . "'
        order by tcd.Id desc limit 1");

        if (!empty($consulta)) {
            return $consulta[0];
        } else {
            return $consulta;
        }
    }

    private function getProblemaCorrectivoForPDF(int $id) {
        $consulta = $this->DBS->consulta("select
        tcp.IdTipoProblema,
        (select Nombre from cat_v3_correctivos_problemas where Id = tcp.IdTipoProblema) as TipoProblema,
        (select Nombre from cat_v3_componentes_equipo where Id = tcsr.IdRefaccion) as Refaccion,
        tcsr.Cantidad as CantidadRefaccion,
        modelo(tcse.IdModelo) as Equipo,
        tcgr.EsRespaldo as DejaRespaldo,
        modelo(tcgr.IdModelo) as EquipoRespaldo,
        tcgr.Serie as SerieRespaldo,
        tcgr.Autoriza as AutorizaSinRespaldo,
        tcgr.Evidencia as EvidenciaAutoriza
        from t_correctivos_problemas tcp
        left join t_correctivos_solicitudes_refaccion tcsr
        on tcsr.Id = (select MAX(Id) from t_correctivos_solicitudes_refaccion where IdServicioOrigen = tcp.IdServicio)
        left join t_correctivos_solicitudes_equipo tcse
        on tcse.Id = (select MAX(Id) from t_correctivos_solicitudes_equipo where IdServicioOrigen = tcp.IdServicio)
        left join t_correctivos_garantia_respaldo tcgr
        on tcgr.Id = (select MAX(Id) from t_correctivos_garantia_respaldo where IdServicio = tcp.IdServicio)
        where tcp.IdServicio = '" . $id . "'
        order by tcp.Id desc limit 1");
        return $consulta;
    }

    private function getSolucionCorrectivoForPDF(int $id) {
        $consulta = $this->DBS->consulta("select 
        tcs.IdTipoSolucion,
        (select Nombre from cat_v3_correctivos_soluciones where Id = tcs.IdTipoSolucion) as TipoSolucion,
        (select Nombre from cat_v3_soluciones_equipo where Id = tcsse.IdSolucionEquipo) as SolucionSinEquipo,
        modelo(tcsc.IdModelo) as EquipoCambio,
        tcsc.Serie as SerieCambio,
        (select Nombre from cat_v3_componentes_equipo where Id = tcsr.IdRefaccion) as Refaccion,
        tcsr.Cantidad as CantidadRefaccion,
        tcs.Evidencias,
        tcs.Observaciones
        from t_correctivos_soluciones tcs
        left join t_correctivos_solucion_sin_equipo tcsse on tcs.Id = tcsse.IdSolucionCorrectivo
        left join t_correctivos_solucion_cambio tcsc 
        on tcsc.Id = (select MAX(Id) from t_correctivos_solucion_cambio where IdSolucionCorrectivo = tcs.Id)
        left join t_correctivos_solucion_refaccion tcsr on tcsr.IdSolucionCorrectivo = tcs.Id
        where tcs.IdServicio = '" . $id . "'
        order by tcs.Id desc limit 1");
        return $consulta;
    }

    private function getResolucionSinClasificarForPDF(int $id) {
        $consulta = $this->DBS->consulta("select 
        Descripcion,
        Archivos as Evidencias,
        Fecha,
        modelo(IdModelo) AS Modelo,
        areaAtencion(IdArea) AS Area,
        Punto AS Punto
        from t_servicios_generales 
        where IdServicio = '" . $id . "'");
        return $consulta;
    }

    private function getAvancesProblemasForPDF(int $id) {
        $arrayReturn = [];
        $consulta = $this->DBS->consulta("select
        tsa.Id,
        nombreUsuario(tsa.IdUsuario) as Usuario,
        (select cp.Nombre from cat_v3_usuarios cu join cat_perfiles cp on cu.IdPerfil = cp.Id where cu.Id = tsa.IdUsuario) as Perfil,
        tsa.IdTipo,
        tsa.Fecha,
        tsa.Descripcion,
        tsa.Archivos as Evidencias
        from t_servicios_avance tsa
        where tsa.IdServicio = '" . $id . "'
        and tsa.Flag = '1'");
        if (!empty($consulta)) {
            foreach ($consulta as $key => $value) {
                $cmateriales = $this->DBS->consulta("SELECT 
                CASE IdItem 
                    WHEN 1 THEN 'Equipo'
                    WHEN 2 THEN 'Material'
                    WHEN 3 THEN 'Refacción'
                    WHEN 4 THEN 'Elemento'
                    WHEN 5 THEN 'Sub-Elemento'
                END as Tipo, 
                CASE IdItem 
                    WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = TipoItem) 
                    WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = TipoItem)
                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = TipoItem)
                    WHEN 4 THEN (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = TipoItem) 
                    WHEN 5 THEN (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = TipoItem) 
                END as EquipoMaterial,
                Serie,
                Cantidad
                FROM t_servicios_avance_equipo 
                WHERE IdAvance = '" . $value['Id'] . "'");
                array_push($arrayReturn, array_merge($value, ['items' => $cmateriales]));
            }
        }

        return $arrayReturn;
    }

    public function getHistorialReporteEnFalso(int $id) {
        $consulta = $this->DBS->consulta("select
        tcbrf.Id,
        nombreUsuario(tcbrf.IdUsuario) as Usuario,
        (select cp.Nombre from cat_v3_usuarios cu join cat_perfiles cp on cu.IdPerfil = cp.Id where cu.Id = tcbrf.IdUsuario) as Perfil,
        tcbrf.Fecha,
        tcbrf.Observaciones as Descripcion,
        tcbrf.Evidencias
        from t_correctivos_bitacora_reporte_falso tcbrf
        where tcbrf.IdServicio = '" . $id . "'");
        return $consulta;
    }

    private function getFirmasTecnico(int $servicio) {
        $consulta = $this->DBS->consulta("select 
                                            nombreUsuario(tst.IdTecnicoFirma) as Tecnico,
                                            FirmaTecnico
                                        from t_servicios_ticket tst WHERE Id = '".$servicio."' limit 1");
        if($consulta){
            return $consulta[0];
        }else{
            return null;
        }
    }
    private function getFirmasGerenteDiagnostico(int $servicio) {
        $consulta = $this->DBS->consulta("select 
                                            Gerente,
                                            Firma,
                                            FechaFirma
                                        from t_correctivos_diagnostico 
                                        where IdServicio = '".$servicio."' and Gerente is not null limit 1");
        if($consulta){
            return $consulta[0];
        }else{
            return null;
        }
    }
    
    private function getFirmasGerenteTicket(int $servicio){
        $consulta = $this->DBS->consulta("select 
                                            Firma,
                                            NombreFirma as Gerente,
                                            FechaFirma
                                        from t_servicios_ticket tst 
                                        WHERE  Id = '".$servicio."' 
                                        and tst.NombreFirma is not null limit 1");
        if($consulta){
            return $consulta[0];
        }else{
            return null;
        }
    }

    public function pdfFromFolio(array $datos) {
        $this->pdf = new PDFAux();
        if (!isset($datos['folio'])) {
            return ["code" => 500, "message" => "The parameter 'folio' is mandatory"];
        } else if (!is_numeric($datos['folio'])) {
            return ["code" => 500, "message" => "The parameter 'folio' must be a number"];
        } else {
            $servicios = $this->getServiciosByFolio($datos['folio']);

            if (!empty($servicios)) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);

                foreach ($servicios as $k => $v) {
                    $generales = $this->getGeneralesServicio($v['Id']);

                    if (($this->y + 26) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }

                    $this->setStyleHeader();
                    $this->setCoordinates();
                    $this->setHeaderValue("#" . ($k + 1) . " Información General");

                    $this->setStyleTitle();
                    $this->setCellValue(30, 5, "Cliente:", 'R', true);
                    $this->setCellValue(30, 5, "Sucursal:", 'R');
                    $this->setCellValue(30, 5, "Tipo Serv:", 'R', true);
                    $this->setCoordinates(100, $this->y - 5);
                    $this->setCellValue(30, 5, "Estatus:", 'R', true);
                    $this->setCoordinates(10);
                    $this->setCellValue(30, 5, "Atiende:", 'R');

                    $restarYFallaReportada = 25;
                    $restarY = 20;

                    if ($generales['IdEstatus'] === '4') {
                        $this->setCellValue(30, 5, "Fecha Conclusión:", 'R', true);
                        if ($generales['IdTipoServicio'] === '20') {
                            $restarY = 25;
                        }
                    }

                    $this->setStyleSubtitle();
                    $this->setCoordinates(40, $this->y - $restarY);
                    $this->setCellValue(0, 5, $generales['Cliente'], 'L', true);
                    $this->setCellValue(0, 5, $generales['Sucursal'], 'L');
                    $this->setCellValue(75, 5, $generales['TipoServicio'], 'L', true);
                    $this->setCoordinates(130, $this->y - 5);
                    $this->setCellValue(70, 5, $generales['Estatus'], 'L', true);
                    $this->setCoordinates(40);
                    $this->setCellValue(0, 5, $generales['Atiende']." (".$generales['Perfil'].")", 'L');

                    if ($generales['IdEstatus'] === '4') {
                        $this->setCellValue(0, 5, $generales['FechaConclusion'], 'L', true);
                    }

                    $this->setCoordinates(10);

                    $datos['servicio'] = $generales['Id'];

                    if ($v['HasSeguimiento'] == 0) {
                        $this->setPDFContentSinSeguimiento($generales['Id'], $datos);
                    } else {
                        switch ($generales['IdTipoServicio']) {
                            case 20:
                            case '20':
                                $this->setPDFContentCorrectivo($generales['Id'], $datos);
                                break;
                            case 27:
                            case '27':
                                $datosServicio = $this->DBB->getGeneralesServicioGeneral($datos['servicio']);
                                if (count($datosServicio) > 0) {
                                    $this->setPDFContentSinSeguimiento($generales['Id'], $datos);
                                    $this->obtenerEquipoMaterialServicio($datos['servicio']);
                                } else {
                                    $this->setPDFContentCorrectivo($generales['Id'], $datos);
                                }
                                break;
                        }
                    }

                    $this->setCoordinates(10, $this->y + 10);
                }

                $carpeta = $this->pdf->definirArchivo('SDPDF/' . substr($datos['folio'], 0, 3) . '/', $datos['folio']);
                $this->pdf->Output('F', $carpeta, true);
                $carpeta = substr($carpeta, 1);
                return ["code" => 200, "message" => "Your file was created correctly", 'uri' => $carpeta];
            } else {
                return ["code" => 500, "message" => "The Folio " . $datos['folio'] . " doesn't have any registered service"];
            }
        }
    }

    public function definirPDF(array $datos) {
        $this->pdf = new PDFAux();
        
        $firmas = array();
        $firmaTecnico = $this->getFirmasTecnico($datos['servicio']);
        if (!is_null($firmaTecnico)) {
            $firmas['FirmaTecnico'] = $firmaTecnico['FirmaTecnico'];
            $firmas['Tecnico'] = $firmaTecnico['Tecnico'];
        }
        $firmaGerente = $this->getFirmasGerenteDiagnostico($datos['servicio']);
        if (!is_null($firmaGerente)) {
            $firmas['Firma'] = $firmaGerente['Firma'];
            $firmas['Gerente'] = $firmaGerente['Gerente'];
            $firmas['FechaFirma'] = $firmaGerente['FechaFirma'];
        } else {
            $firmaGerente = $this->getFirmasGerenteTicket($datos['servicio']);
            $firmas['Firma'] = $firmaGerente['Firma'];
            $firmas['Gerente'] = $firmaGerente['Gerente'];
            $firmas['FechaFirma'] = $firmaGerente['FechaFirma'];
        }
        $this->pdf->setDato($firmas);

        $this->pdf->AliasNbPages();
        $nombreExtra = '';

        $generales = $this->getGeneralesServicio($datos['servicio']);
        $datos['folio'] = $generales['SD'];

        if (!empty($datos['nombreExtra'])) {
            $nombreExtra = $datos['nombreExtra'];
        }

        if (isset($datos['archivo'])) {
            $carpeta = $this->pdf->definirArchivo('Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Asociados/', str_replace(' ','_',$datos['archivo'] . $nombreExtra));
        } else {
            $carpeta = $this->pdf->definirArchivo('Servicios/Servicio-' . $datos['servicio'] . '/Pdf/', str_replace(' ','_','Ticket_' . $generales['Ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $generales['TipoServicio'] . $nombreExtra));
        }

        if (file_exists($carpeta)) {
            unlink($carpeta);
        }

        $this->setHeaderPDF("Resumen de Incidente Service Desk", $generales['SD']);

        $this->setCoordinates(10);
        $this->setStyleHeader();
        $this->setHeaderValue("Información General");

        $this->setStyleTitle();
        $this->setCellValue(30, 5, "Cliente:", 'R', true);
        $this->setCellValue(30, 5, "Sucursal:", 'R');
        $this->setCellValue(30, 5, "Tipo Serv:", 'R', true);
        $this->setCoordinates(100, $this->y - 5);
        $this->setCellValue(27, 5, "Estatus:", 'R', true);
        $this->setCoordinates(10);
        $this->setCellValue(30, 5, "Atiende:", 'R');

        $restarYFallaReportada = 25;
        $restarY = 20;

        if ($generales['IdEstatus'] === '4') {
            $this->setCellValue(30, 5, "Fecha Conclusión:", 'R', true);
            $restarY = 25;
        }

        $this->setStyleSubtitle();
        $this->setCoordinates(40, $this->y - $restarY);
        $this->setCellValue(0, 5, $generales['Cliente'], 'L', true);
        $this->setCellValue(0, 5, $generales['Sucursal'], 'L');
        $this->setCellValue(70, 5, $generales['TipoServicio'], 'L', true);
        $this->setCoordinates(127, $this->y - 5);

        if ($generales['IdEstatus'] === '5') {
            $estatus = 'EN ATENCIÓN';
        } else {
            $estatus = $generales['Estatus'];
        }

        $this->setCellValue(73, 5, $estatus, 'L', true);
        $this->setCoordinates(40);
        $this->setCellValue(0, 5, $generales['Atiende']." (".$generales['Perfil'].")", 'L');

        if ($generales['IdEstatus'] === '4') {
            $this->setCellValue(0, 5, $generales['FechaConclusion'], 'L', true);
        }
        
        if($datos['folio'] != '' || $datos['folio'] != null){
            $this->informacionSD($datos['folio']);
        }
        
        if ($generales['HasSeguimiento'] === '0') {
            $this->setPDFContentSinSeguimiento($generales['Id'], $datos);
            $this->obtenerEquipoMaterialServicio($datos['servicio']);
//            $this->setFirmasServicio($generales['Id'], $datos);
        } else {
            switch ($generales['IdTipoServicio']) {
                case 11:
                case '11':
                    $this->setCensoPDF($datos);
//                    $this->setFirmasServicio($generales['Id'], $datos);
                    break;
                case 12:
                case '12':
                    $this->setMantenimientoPDF($datos);
//                    $this->setFirmasServicio($generales['Id'], $datos);
                    break;
                case 20:
                case '20':
                    $this->setPDFContentCorrectivo($generales['Id'], $datos);
                    break;
                case 27:
                case '27':
                    $datosServicio = $this->DBB->getGeneralesServicioGeneral($datos['servicio']);
                    if (count($datosServicio) > 0) {
                        $this->setPDFContentSinSeguimiento($generales['Id'], $datos);
                        $this->obtenerEquipoMaterialServicio($datos['servicio']);
                    } else {
                        $this->setPDFContentCorrectivo($generales['Id'], $datos);
                    }
                    break;
            }
        }

        $this->pdf->Output('F', $carpeta, true);
        $this->pdf->Close();
        $carpeta = substr($carpeta, 1);

        return $carpeta;
    }
    
    private function informacionSD($folio) {
        $usuario = $this->Usuario->getDatosUsuario();
        $resultadoSD = $this->ServiceDesk->getDetallesFolio($usuario["SDKey"],$folio);
        $infoSD = json_decode(json_encode($resultadoSD), True);
        
        $this->setCoordinates(10, $this->y + 5);
        $this->setStyleHeader();
        $this->setHeaderValue("Información SD");

        $this->setStyleTitle();
        $this->setCellValue(30, 5, "Gerente del folio:", 'R', true);
        $this->setCellValue(30, 5, "Asunto:", 'R');
        
        $this->setStyleSubtitle();
        $this->setCoordinates(40, $this->y - 10);
        $this->setCellValue(0, 5, $infoSD["Nombre del Gerente"], 'L', true);
        $this->setMulticellValue(0, 5, $infoSD["SUBJECT"], 'L');
        $this->setCoordinates(40, $this->y + 5);
        $this->setMulticellValue(0, 5, $infoSD["SHORTDESCRIPTION"], 'L', true);
        $heightMulti = $this->pdf->GetY() - $this->y;
        $this->setStyleTitle();
        $this->setCoordinates(10, $this->y);
        $this->setCellValue(30, $heightMulti, "Descripción:", 'R', true);
        
        $this->setCoordinates(10);
    }

    private function setPDFContentSinSeguimiento(int $id, array $datos) {
        $this->setAvancesProblemasPDF($id, $datos);
        $this->setCoordinates(10, $this->y + 5);
        $resolucion = $this->getResolucionSinClasificarForPDF($id);

        if (isset($resolucion[0])) {
            $resolucion = $resolucion[0];

            if (($this->y + 26) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            }

            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Documentación del Servicio");

            $this->setStyleMinisubtitle();
            $this->setCoordinates(35);
            $this->setMulticellValue(0, 4, $resolucion['Descripcion'], 'J', true);

            $heightMulti = $this->pdf->GetY() - $this->y;

            $this->setCoordinates(10);

            $this->setStyleTitle();
            $this->setCellValue(25, $heightMulti, "Resolución:", 'R', true);

            if ($resolucion['Modelo'] !== NULL && $resolucion['Modelo'] !== 0) {
                $this->setStyleTitle();
                $this->setCellValue(25, 5, "Equipo:", 'R');
                $this->setCellValue(25, 5, "Área y Punto:", 'R', true);

                $this->setStyleSubtitle();
                $this->setCoordinates(35, $this->y - 10);
                $this->setCellValue(0, 5, $resolucion['Modelo'], 'L');
                $this->setCellValue(0, 5, $resolucion['Area'] . ' - ' . $resolucion['Punto'], 'L', true);
            }

            $this->setCoordinates(10, $this->pdf->GetY());

            $this->setEvidenciasPDF($datos, $resolucion['Evidencias'], 'Documentación del Servicio');
        }
    }

    private function setAvancesProblemasPDF(int $id, array $datos) {
        $registros = $this->getAvancesProblemasForPDF($id);

        if (!empty($registros)) {
            if (($this->y + 26) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            }
            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Historial de Avances y Problemas");

            foreach ($registros as $key => $value) {
                if (($this->y + 26) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }
                $this->setStyleTitle();
                $this->setCellValue(25, 5, "Usuario:", 'R', true);
                $this->setCoordinates(100, $this->y - 5);
                $this->setCellValue(25, 5, "Fecha:", 'R', true);

                $this->setStyleSubtitle();
                $this->setCoordinates(35, $this->y - 5);
                $this->setCellValue(75, 5, $value['Usuario'] . " (".$value['Perfil'].")", 'L', true);
                $this->setCoordinates(125, $this->y - 5);
                $this->setCellValue(75, 5, $value['Fecha'], 'L', true);

                $termino = 'Avance';
                if ($value['IdTipo'] == 2) {
                    $termino = 'Problema';
                }

                $this->setStyleMinisubtitle();
                $this->setCoordinates(35);
                $this->setMulticellValue(0, 4, $value['Descripcion'], 'J');

                $heightMulti = $this->pdf->GetY() - $this->y;

                $this->setCoordinates(10);

                $this->setStyleTitle();
                $this->setCellValue(25, $heightMulti, $termino . ":", 'R');

                $this->setCoordinates(10, $this->pdf->GetY());
                if (isset($value['Evidencias']) && !empty($value['Evidencias'])) {
                    $this->setEvidenciasPDF($datos, $value['Evidencias'], 'Historial de Avances y Problemas');
                }
            }
        }
    }

    private function setHistorialReporteEnFalso(int $id, array $datos) {
        $registros = $this->getHistorialReporteEnFalso($id);
        if (!empty($registros)) {
            if (($this->y + 26) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            }
            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Bitácora de Revisión Reporte en Falso");

            foreach ($registros as $key => $value) {
                if (($this->y + 16) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }
                $this->setStyleTitle();
                $this->setCellValue(25, 5, "Usuario:", 'R', true);
                $this->setCoordinates(100, $this->y - 5);
                $this->setCellValue(25, 5, "Fecha:", 'R', true);

                $this->setStyleSubtitle();
                $this->setCoordinates(35, $this->y - 5);
                $this->setCellValue(75, 5, $value['Usuario'] . " (".$value['Perfil'].")", 'L', true);
                $this->setCoordinates(125, $this->y - 5);
                $this->setCellValue(75, 5, $value['Fecha'], 'L', true);



                $this->setStyleMinisubtitle();
                $this->setCoordinates(35);
                $this->setMulticellValue(0, 4, $value['Descripcion'], 'J');

                $heightMulti = $this->pdf->GetY() - $this->y;

                $this->setCoordinates(10);

                $this->setStyleTitle();
                $this->setCellValue(25, $heightMulti, 'Observaciones' . ":", 'R');

                $this->setCoordinates(10, $this->pdf->GetY());
                if (isset($value['Evidencias']) && !empty($value['Evidencias'])) {
                    $this->setEvidenciasPDF($datos, $value['Evidencias'], 'Bitácora de Revisión Reporte en Falso');
                }
            }
        }
    }

    private function setFirmasGerenteTecnico(array $datos) {
        $firmas = $this->getFirmasServicio($datos['servicio']);
        if ((!is_null($diagnostico['Firma']) && $diagnostico['Firma'] != '')) {
            if (file_exists('.' . $diagnostico['Firma'])) {
                if (($this->y + 62) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }

                if (!is_null($diagnostico['Firma']) && $diagnostico['Firma'] != '') {
                    if (file_exists('.' . $diagnostico['Firma'])) {
                        $this->pdf->Image('.' . $diagnostico['Firma'], 12, $this->y + 12, 80, 35, pathinfo($diagnostico['Firma'], PATHINFO_EXTENSION));
                        $gerente = utf8_decode($diagnostico['Gerente']);
                        $this->setCoordinates(10, $this->y + 45);
                        $this->pdf->Cell(95, 5, utf8_decode($gerente), 0, 0, 'C');

                        $this->setCoordinates(10, $this->y + 5);
                        $this->pdf->Cell(95, 5, 'Gerente en turno Cinemex', 0, 0, 'C');

                        $this->setCoordinates(10, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode($fechaFirma), 0, 0, 'C');

                        $this->setCoordinates(105, $this->y - 45);
                    }
                }

                $tecnico = '';

                if (!is_null($diagnostico['FirmaTecnico']) && $diagnostico['FirmaTecnico'] != '') {
                    if (file_exists('.' . $diagnostico['FirmaTecnico'])) {
                        $this->pdf->Image('.' . $diagnostico['FirmaTecnico'], $this->x, $this->y + 2.5, 80, 35, pathinfo($diagnostico['FirmaTecnico'], PATHINFO_EXTENSION));
                        $tecnico = utf8_decode($diagnostico['Tecnico']);

                        $this->setCoordinates($this->x, $this->y + 35);
                        $this->pdf->Cell(95, 5, utf8_decode($diagnostico['Tecnico']), 0, 0, 'C');

                        $this->setCoordinates($this->x, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode("Técnico Siccob"), 0, 0, 'C');

                        $this->setCoordinates($this->x, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode($fechaFirma), 0, 0, 'C');
                    }
                }
            }
        }
    }

    private function setFirmasServicio(int $id, array $datos) {
        $firmas = $this->getFirmasServicio($id);
        if (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '') {
            $this->setFirmasGerenteTecnico($datos);
        } else {
            $this->setFirmaGerente($firmas, $datos);
        }
    }

    private function setFirmaGerente(array $firmas, array $datos) {
        if ((!is_null($firmas['Firma']) && $firmas['Firma'] != '')) {
            if (file_exists('.' . $firmas['Firma'])) {
                if (($this->y + 62) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }

                $this->setStyleSubtitle();

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 1, "Firma de Cierre", 0, 0, 'C');

                $gerente = '';
                $this->pdf->Image('.' . $firmas['Firma'], $this->x + 2.5, $this->y + 2.5, 89, 35, pathinfo($firmas['Firma'], PATHINFO_EXTENSION));
                $gerente = utf8_decode($firmas['Gerente']);

                $this->setCoordinates(55, $this->y + 40);
                $this->pdf->Cell(95, 5, $gerente, 0, 0, 'C');

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 5, 'Gerente en turno Cinemex', 0, 0, 'C');

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 5, $firmas['FechaFirma'], 0, 0, 'C');
            }
        } else {
            $servicioDocumentacion = $this->DBST->consultaDocumentacioFirmadaServicio($datos['servicio'], TRUE);

            if (!empty($servicioDocumentacion)) {

                if (($this->y + 62) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }

                $this->setStyleSubtitle();

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 1, "Firma de Cierre", 0, 0, 'C');

                $gerente = '';
                $this->pdf->Image('.' . $servicioDocumentacion[0]['Firma'], $this->x + 2.5, $this->y + 2.5, 89, 35, pathinfo($servicioDocumentacion[0]['Firma'], PATHINFO_EXTENSION));
                $gerente = utf8_decode($servicioDocumentacion[0]['Recibe']);

                $this->setCoordinates(55, $this->y + 40);
                $this->pdf->Cell(95, 5, $gerente, 0, 0, 'C');

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 5, 'Gerente en turno Cinemex', 0, 0, 'C');

                $this->setCoordinates(55, $this->y + 5);
                $this->pdf->Cell(95, 5, $servicioDocumentacion[0]['Fecha'], 0, 0, 'C');
            }
        }
    }

    private function setFirmaGerenteDiagnosticoCorrectivo(int $id, array $datos) {
        $diagnostico = $this->getDiagnosticoCorrectivoForPDF($id);
        $fechaFirma = utf8_decode($diagnostico['FechaFirma']);

        if ((!is_null($diagnostico['Firma']) && $diagnostico['Firma'] != '')) {
            if (file_exists('.' . $diagnostico['Firma'])) {
                if (($this->y + 62) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }

                if (!is_null($diagnostico['Firma']) && $diagnostico['Firma'] != '') {
                    if (file_exists('.' . $diagnostico['Firma'])) {
                        $this->pdf->Image('.' . $diagnostico['Firma'], 12, $this->y + 12, 80, 35, pathinfo($diagnostico['Firma'], PATHINFO_EXTENSION));
                        $gerente = utf8_decode($diagnostico['Gerente']);
                        $this->setCoordinates(10, $this->y + 45);
                        $this->pdf->Cell(95, 5, utf8_decode($gerente), 0, 0, 'C');

                        $this->setCoordinates(10, $this->y + 5);
                        $this->pdf->Cell(95, 5, 'Gerente en turno Cinemex', 0, 0, 'C');

                        $this->setCoordinates(10, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode($fechaFirma), 0, 0, 'C');

                        $this->setCoordinates(105, $this->y - 45);
                    }
                }

                $tecnico = '';

                if (!is_null($diagnostico['FirmaTecnico']) && $diagnostico['FirmaTecnico'] != '') {
                    if (file_exists('.' . $diagnostico['FirmaTecnico'])) {
                        $this->pdf->Image('.' . $diagnostico['FirmaTecnico'], $this->x, $this->y + 2.5, 80, 35, pathinfo($diagnostico['FirmaTecnico'], PATHINFO_EXTENSION));
                        $tecnico = utf8_decode($diagnostico['Tecnico']);

                        $this->setCoordinates($this->x, $this->y + 35);
                        $this->pdf->Cell(95, 5, utf8_decode($diagnostico['Tecnico']), 0, 0, 'C');

                        $this->setCoordinates($this->x, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode("Técnico Siccob"), 0, 0, 'C');

                        $this->setCoordinates($this->x, $this->y + 5);
                        $this->pdf->Cell(95, 5, utf8_decode($fechaFirma), 0, 0, 'C');
                    }
                }
            }
        }
    }

    private function setEvidenciasPDF($datos, $evidencias, $header) {
        $host = $_SERVER['SERVER_NAME'];
        $evidencias = explode(",", $evidencias);
        $totalEvidencias = count($evidencias);
        if ($totalEvidencias > 0) {

            $filas = ceil($totalEvidencias / 4);

            $indice = 0;
            for ($f = 1; $f <= $filas; $f++) {
                if (($this->y + 45) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    $this->setStyleHeader();
                    $this->setHeaderValue($header);
                }

                $this->setCoordinates(10);

                for ($i = 1; $i <= 4; $i++) {
                    if (isset($evidencias[$indice]) && $evidencias[$indice] != '' && file_exists('.' . $evidencias[$indice])) {
                        $image = $evidencias[$indice];
                        if (!in_array(pathinfo($image, PATHINFO_EXTENSION), ['JPG', 'JPEG', 'PNG', 'GIF', 'jpg', 'jpeg', 'png', 'gif'])) {
                            $image = '/assets/img/Iconos/no-thumbnail.jpg';
                        }
                        $this->pdf->Image('.' . $image, $this->x + 2.5, $this->y + 2.5, 42.5, 40, pathinfo($image, PATHINFO_EXTENSION), 'http://siccob.solutions' . $evidencias[$indice]);
                    }

                    $this->setCoordinates($this->x + 47.5);

                    if ($i == 4) {
                        $this->setCoordinates(10, $this->y + 45);
                    }
                    $indice++;
                }
            }
        }
    }

    private function setPDFContentCorrectivo(int $id, array $datos) {
        $diagnostico = $this->getDiagnosticoCorrectivoForPDF($id);

        if (!empty($diagnostico)) {
            $this->setDiagnosticoCorrectivoPDF($diagnostico, $datos);

            if (in_array($diagnostico['IdTipoDiagnostico'], [1, '1',])) {
                $this->setHistorialReporteEnFalso($id, $datos);
            }
        }

        $this->setAvancesProblemasPDF($id, $datos);
        $problema = $this->getProblemaCorrectivoForPDF($id);
        $this->setProblemaCorrectivoPDF($problema, $datos);

        $solucion = $this->getSolucionCorrectivoForPDF($id);
        $this->setSolucionCorrectivoPDF($solucion, $datos);

        $this->setStyleSubtitle();
    }

    private function setDiagnosticoCorrectivoPDF($diagnostico, $datos) {
        if (($this->y + 26) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
        }

        $this->setCoordinates(10, $this->y + 5);
        $this->setStyleHeader();
        $this->setHeaderValue("Diagnóstico " . $diagnostico['TipoDiagnostico']);

        $this->setStyleTitle();
        $this->setCellValue(25, 5, "Equipo:", 'R', true);
        $this->setCellValue(25, 5, "Ubicación:", 'R');
        $this->setCoordinates(100, $this->y - 5);
        $this->setCellValue(25, 5, "Serie:", 'R');
        $this->setStyleSubtitle();
        $this->setCoordinates(35, $this->y - 10);
        $this->setCellValue(0, 5, $diagnostico['Modelo'], 'L', true);
        $this->setCellValue(75, 5, $diagnostico['Area'] . " " . $diagnostico['Punto'], 'L');
        $this->setCoordinates(125, $this->y - 5);
        $this->setCellValue(75, 5, $diagnostico['Serie'], 'L');
        $fill = false;

        if (in_array($diagnostico['IdTipoDiagnostico'], [4, '4'])) {
            $fill = !$fill;
            $this->setCoordinates(10);
            $this->setStyleTitle();
            $this->setCellValue(25, 5, "Componente:", 'R', $fill);
            $this->setStyleSubtitle();
            $this->setCoordinates(35, $this->y - 5);
            $this->setCellValue(0, 5, $diagnostico['Componente'], 'L', $fill);
        }

        if (in_array($diagnostico['IdTipoDiagnostico'], [2, 3, 4, '2', '3', '4'])) {
            $fill = !$fill;
            $this->setCoordinates(10);
            $this->setStyleTitle();
            $this->setCellValue(25, 5, "Falla:", 'R', $fill);
            $this->setStyleSubtitle();
            $this->setCoordinates(35, $this->y - 5);
            $this->setCellValue(0, 5, $diagnostico['Falla'], 'L', $fill);
        }

        if (in_array($diagnostico['IdTipoDiagnostico'], [1, '1',])) {
            $fill = !$fill;
            $this->setCoordinates(10);
            $this->setStyleTitle();
            $this->setCellValue(25, 5, "Falla:", 'R', $fill);
            $this->setStyleSubtitle();
            $this->setCoordinates(35, $this->y - 5);
            $this->setCellValue(0, 5, 'No se encuentra falla en el equipo. Se agregan a continuación detalles de la revisión', 'L', $fill);
        }

        if ($diagnostico['Observaciones'] != '') {
            $fill = !$fill;

            $this->setStyleMinisubtitle();
            $this->setCoordinates(35);
            $this->setMulticellValue(0, 4, $diagnostico['Observaciones'], 'J', $fill);

            $heightMulti = $this->pdf->GetY() - $this->y;

            $this->setCoordinates(10);

            $this->setStyleTitle();
            $this->setCellValue(25, $heightMulti, "Observaciones:", 'R', $fill);

            $this->setCoordinates(10, $this->pdf->GetY());
        }

        $this->setEvidenciasPDF($datos, $diagnostico['Evidencias'], "Diagnóstico " . $diagnostico['TipoDiagnostico']);
    }

    private function setProblemaCorrectivoPDF($problema, $datos) {
        if (isset($problema[0])) {
            $problema = $problema[0];
            if (($this->y + 26) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            }
            $this->setStyleHeader();
            $this->setHeaderValue("Problema del Servicio");

            $this->setCoordinates(10);
            $this->setStyleTitle();
            $this->setCellValue(25, 5, "Tipo:", 'R', true);
            $this->setStyleSubtitle();
            $this->setCoordinates(35, $this->y - 5);
            $this->setCellValue(0, 5, $problema['TipoProblema'], 'L', true);

            switch ($problema['IdTipoProblema']) {
                case 1:
                case '1':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Refacción:", 'R');
                    $this->setCoordinates(130, $this->y - 5);
                    $this->setCellValue(25, 5, "Cantidad:", 'R');

                    $this->setCoordinates(10);

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(95, 5, $problema['Refaccion'], 'L');
                    $this->setCoordinates(155, $this->y - 5);
                    $this->setCellValue(0, 5, $problema['CantidadRefaccion'], 'L');
                    break;
                case 2:
                case '2':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Equipo:", 'R');

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(0, 5, $problema['Equipo'], 'L');
                    break;
                case 3:
                case '3':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);
                    $this->setStyleTitle();
                    if ($problema['DejaRespaldo'] == 1 || 1 == 1) {
                        if (!empty($problema['EquipoRespaldo'])) {
                            $this->setCellValue(25, 5, "Respaldo:", 'R');
                            $this->setCoordinates(130, $this->y - 5);
                            $this->setCellValue(25, 5, "Serie:", 'R');

                            $this->setCoordinates(10);

                            $this->setStyleSubtitle();
                            $this->setCoordinates(35, $this->y - 5);
                            $this->setCellValue(95, 5, $problema['EquipoRespaldo'], 'L');
                            $this->setCoordinates(155, $this->y - 5);
                            $this->setCellValue(0, 5, $problema['SerieRespaldo'], 'L');
                        }
                    } else {
                        $this->setCellValue(45, 5, "Autoriza Sin Respaldo:", 'R');

                        $this->setStyleSubtitle();
                        $this->setCoordinates(55, $this->y - 5);
                        $this->setCellValue(0, 5, $problema['AutorizaSinRespaldo'], 'L');
                    }
                    break;
            }
        }
    }

    private function setSolucionCorrectivoPDF($solucion, $datos) {
        if (isset($solucion[0])) {
            $solucion = $solucion[0];
            if (($this->y + 16) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            }
            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Solución del Servicio");

            $this->setStyleTitle();
            $this->setCellValue(25, 5, "Tipo:", 'R', true);
            $this->setStyleSubtitle();
            $this->setCoordinates(35, $this->y - 5);
            $this->setCellValue(0, 5, $solucion['TipoSolucion'], 'L', true);

            switch ($solucion['IdTipoSolucion']) {
                case 1:
                case '1':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Solución:", 'R');

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(0, 5, $solucion['SolucionSinEquipo'], 'L');
                    break;
                case 2:
                case '2':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Refacción:", 'R');
                    $this->setCoordinates(130, $this->y - 5);
                    $this->setCellValue(25, 5, "Cantidad:", 'R');

                    $this->setCoordinates(10);

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(95, 5, $solucion['Refaccion'], 'L');
                    $this->setCoordinates(155, $this->y - 5);
                    $this->setCellValue(0, 5, $solucion['CantidadRefaccion'], 'L');
                    break;
                case 3:
                case '3':
                    if (($this->y + 16) > 270) {
                        $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                    }
                    $this->setCoordinates(10);
                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Equipo:", 'R');
                    $this->setCoordinates(130, $this->y - 5);
                    $this->setCellValue(25, 5, "Serie:", 'R');

                    $this->setCoordinates(10);

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(95, 5, $solucion['EquipoCambio'], 'L');
                    $this->setCoordinates(155, $this->y - 5);
                    $this->setCellValue(0, 5, $solucion['SerieCambio'], 'L');
                    break;
            }

            if ($solucion['Observaciones'] != '') {
                $fill = true;
                if (($this->y + 16) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }
                $this->setStyleMinisubtitle();
                $this->setCoordinates(35);
                $this->setMulticellValue(0, 4, $solucion['Observaciones'], 'J', $fill);

                $heightMulti = $this->pdf->GetY() - $this->y;

                $this->setCoordinates(10);

                $this->setStyleTitle();
                $this->setCellValue(25, $heightMulti, "Observaciones:", 'R', $fill);

                $this->setCoordinates(10, $this->pdf->GetY());
            }

            $totalEvidencias = $this->totalEvidenciasSolicitud($datos['servicio']);

            $this->setEvidenciasPDF($datos, $totalEvidencias, "Solución del Servicio");
        }
    }

    private function setCensoPDF(array $datos) {
        if (($this->y + 26) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
        }

        $this->setCoordinates(10);
        $this->setCensos($datos);
        $this->setTotalAreasCenso($datos);
        $this->setTotalLineasCenso($datos);
    }

    private function setCensos(array $datos) {
        if (($this->y + 26) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
        }
        $this->setCoordinates(10, $this->y + 5);
        $this->setHeadersCensoData();

        $censo = $this->DBC->getCensos($datos['servicio']);

        foreach ($censo as $key => $value) {
            $this->pdf->SetX('10');
            $this->setStyleMinisubtitle();
            $this->setCellValue(55, 5, $value['Area'], 'L');
            $this->setCoordinates(65, $this->y - 5);
            $this->setCellValue(15, 5, $value['Punto'], 'L');
            $this->setCoordinates(80, $this->y - 5);
            $this->setCellValue(80, 5, $value['Equipo'], 'L');
            $this->setCoordinates(160, $this->y - 5);
            $this->setCellValue(40, 5, $value['Serie'], 'L');

            if (($this->y + 5) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                $this->setHeadersCensoData();
            }

// $height = $this->setHeightMaximo(array(
//     'area' => $value['Area'],
//     'equipo' => $value['Equipo'],
//     'serie' => $value['Serie']                
// ));
// $cellHeight = $height[0]['nuevoHeight'];
// if (in_array(0, $height[2])) {
//     $cellHeight = 5;
// }
// $this->pdf->SetX('10');
// $this->pdf->MultiCell(45, $cellHeight, $value['Area'], 1, 'L');
// $cellHeight = $height[0]['nuevoHeight'];
// $xPos = $this->pdf->GetX();
// $yPos = $this->pdf->GetY();
// $this->pdf->SetXY(55, $yPos - $height[0]['nuevoHeight']);
// $this->pdf->MultiCell(12, $cellHeight, $value['Punto'], 1, 'C');
// $cellHeight = $height[0]['nuevoHeight'];
// if (in_array(1, $height[2])) {
//     $cellHeight = 5;
// }
// $xPos = $this->pdf->GetX();
// $yPos = $this->pdf->GetY();
// $this->pdf->SetXY(67, $yPos - $height[0]['nuevoHeight']);
// $this->pdf->MultiCell(76, $cellHeight, $value['Equipo'], 1, 'L');
// $cellHeight = $height[0]['nuevoHeight'];
// if (in_array(2, $height[2])) {
//     $cellHeight = 5;
// }
// $xPos = $this->pdf->GetX();
// $yPos = $this->pdf->GetY();
// $this->pdf->SetXY(143, $yPos - $height[0]['nuevoHeight']);
// $this->pdf->MultiCell(32, $cellHeight, $value['Serie'], 1, 'L');
// $cellHeight = $height[0]['nuevoHeight'];
// if (in_array(3, $height[2])) {
//     if (sizeof($height[2]) >= 1) {
//         $cellHeight = 5;
//     } else {
//         $cellHeight = 7.5;
//     }
// }
// $xPos = $this->pdf->GetX();
// $yPos = $this->pdf->GetY();
// $this->pdf->SetXY(175, $yPos - $height[0]['nuevoHeight']);
// $this->pdf->MultiCell(25, $cellHeight, $noTerminal, 1, 'L');
        }
    }

    private function setHeadersCensoData() {
        $this->setStyleHeader();
        $this->setHeaderValue("Información del Censo");

        $this->setCoordinates(10);
        $this->setStyleTitle();
        $this->setCellValue(55, 5, "Área", 'L', true);
        $this->setCoordinates(65, $this->y - 5);
        $this->setCellValue(15, 5, 'Punto', 'L', true);
        $this->setCoordinates(80, $this->y - 5);
        $this->setCellValue(80, 5, 'Modelo', 'L', true);
        $this->setCoordinates(160, $this->y - 5);
        $this->setCellValue(40, 5, 'Serie', 'L', true);
    }

    private function setTotalLineasCenso(array $datos) {
        if (($this->y + 21) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            $this->setCoordinates(10);
        } else {
            $this->setCoordinates(10, $this->y + 5);
        }

        $this->setHeadersTotalLineasCenso();

        $totalLineas = $this->DBC->getTotalLineas($datos['servicio']);

        foreach ($totalLineas as $key => $value) {
            $this->setCoordinates(10);
            $this->setStyleSubtitle();
            $this->setCellValue(100, 5, $value['Linea'], 'L');
            $this->setStyleSubtitle();
            $this->setCoordinates(110, $this->y - 5);
            $this->setCellValue(30, 5, $value['Total'], 'C');
            if (($this->y + 5) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                $this->setHeadersTotalLineasCenso();
            }
        }
        $this->setCoordinates(10, $this->y + 5);
    }

    private function setHeadersTotalLineasCenso() {
        $this->setStyleHeader();
        $this->setHeaderValue("Total de Equipos", 130);

        $this->setCoordinates(10);
        $this->setStyleTitle();
        $this->setCellValue(100, 5, "Línea", 'C', true);
        $this->setStyleTitle();
        $this->setCoordinates(110, $this->y - 5);
        $this->setCellValue(30, 5, 'Total', 'C', true);
    }

    private function setTotalAreasCenso(array $datos) {
        if (($this->y + 21) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
            $this->setCoordinates(10);
        } else {
            $this->setCoordinates(10, $this->y + 5);
        }

        $this->setHeadersTotalAreasCenso();

        $totalAreas = $this->DBC->getTotalAreas($datos['servicio']);

        foreach ($totalAreas as $key => $value) {
            $this->setCoordinates(10);
            $this->setStyleSubtitle();
            $this->setCellValue(100, 5, $value['Area'], 'L');
            $this->setCoordinates(110, $this->y - 5);
            $this->setCellValue(30, 5, $value['Total'], 'C');
            if (($this->y + 5) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                $this->setHeadersTotalAreasCenso();
            }
        }
    }

    private function setHeadersTotalAreasCenso() {
        $this->setStyleHeader();
        $this->setHeaderValue("Equipos y Perifericos por Área", 130);

        $this->setCoordinates(10);
        $this->setStyleTitle();
        $this->setCellValue(100, 5, "Área", 'C', true);
        $this->setStyleTitle();
        $this->setCoordinates(110, $this->y - 5);
        $this->setCellValue(30, 5, 'Total', 'C', true);
    }

    private function setHeightMaximo(array $datos) {
        $arrayHeight = array();
        $arrayHeight[0] = $this->setHeight(array('width' => 45, 'height' => 5, 'campo' => $datos['area']));
        $arrayHeight[1] = $this->setHeight(array('width' => 76, 'height' => 5, 'campo' => $datos['equipo']));
        $arrayHeight[2] = $this->setHeight(array('width' => 30, 'height' => 5, 'campo' => $datos['serie']));
        $arrayHeight[3] = $this->setHeight(array('width' => 25, 'height' => 5, 'campo' => $datos['noTerminal']));
        $contador = 0;
        $numeroMasAlto = 0;
        $indiceArray = 0;
        $arrayAltos = array();

        while ($contador < sizeof($arrayHeight)) {
            if ($arrayHeight[$contador]['nuevoHeight'] > 5) {
                $numeroMasAlto = $arrayHeight[$contador]['nuevoHeight'];
                $indiceArray = $contador;
                array_push($arrayAltos, $contador);
            }
            $contador++;
        }

        return array($arrayHeight[$indiceArray], $indiceArray, $arrayAltos);
    }

    private function setHeight(array $datos) {
        $cellWidth = $datos['width'];
        $cellHeight = $datos['height'];

        if ($this->pdf->GetStringWidth($datos['campo']) < $cellWidth) {
            $line = 1;
        } else {
            $textLength = strlen($datos['campo']);
            $errMargin = 10;
            $startChar = 0;
            $maxChar = 0;
            $textArray = array();
            $tmpString = "";

            while ($startChar < $textLength) {
                while (
                $this->pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) && ($startChar + $maxChar) < $textLength
                ) {
                    $maxChar++;
                    $tmpString = substr($datos['campo'], $startChar, $maxChar);
                }

                $startChar = $startChar + $maxChar;
                array_push($textArray, $tmpString);
                $maxChar = 0;
                $tmpString = '';
            }
            $line = count($textArray);
        }

        return array('nuevoHeight' => ($line * $cellHeight), 'cellHeight' => $cellHeight, 'cellWidth' => $cellWidth);
    }

    private function setMantenimientoPDF(array $datos) {
        $this->setAntesDespues($datos);

        $this->setProblemasEquipo($datos);

        $this->setEquiposFaltante($datos);

        $this->setProblemasAdicionales($datos);
    }

    private function setAntesDespues(array $datos) {
        $antesDespues = $this->DBM->getAntesDespues($datos['servicio']);

        if (!empty($antesDespues)) {
            foreach ($antesDespues as $key => $value) {
                if (($this->y + 61) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }

                $this->setCoordinates(10);
                $this->setStyleHeader();
                $this->setHeaderValue($value['Area'] . ' - ' . $value['Punto'] . ' ANTES');
                $this->setStyleTitle();
                $this->setCoordinates(10);
                $this->setCellValue(190, 5, $value['ObservacionesAntes'], 'L');
                $this->setEvidenciasPDF($datos, $value['EvidenciasAntes'], $value['Area'] . ' - ' . $value['Punto'] . ' ANTES');

                if (($this->y + 66) > 270) {
                    $this->setHeaderPDF("Resumen de Incidente Service Desk", $datos['folio']);
                }
                $this->setCoordinates(10);
                $this->setStyleHeader();
                $this->setHeaderValue($value['Area'] . ' - ' . $value['Punto'] . ' DESPUÉS');
                $this->setStyleTitle();
                $this->setCoordinates(10);
                $this->setCellValue(190, 5, $value['ObservacionesDespues'], 'L');
                $this->setEvidenciasPDF($datos, $value['EvidenciasDespues'], $value['Area'] . ' - ' . $value['Punto'] . ' DESPUÉS');
                $this->setCoordinates(10, $this->y + 5);
            }
        }
    }

    private function setProblemasEquipo($datos) {
        $fill = false;
        $fill = !$fill;
        $problemasEquipo = $this->DBM->getProblemasEquipo($datos['servicio']);

        if (!empty($problemasEquipo)) {
            if (isset($datos['folio'])) {
                $this->setHeaderPDF("Problemas por equipo", $datos['folio']);
            } else {
                $this->setHeaderPDF('Problemas por equipo.');
            }

            foreach ($problemasEquipo as $key => $value) {

                if (($this->y + 26) > 270) {
                    if (isset($datos['folio'])) {
                        $this->setHeaderPDF("Problemas por equipo", $datos['folio']);
                    } else {
                        $this->setHeaderPDF('Problemas por equipo');
                    }
                }

                $this->setStyleHeader();
                $this->setHeaderValue($value['Area'] . ' - ' . $value['Punto']);

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Equipo", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['Equipo'], 'L');

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Observaciones", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['Observaciones'], 'L');

                $this->setEvidenciasPDF($datos, $value['Evidencias'], '');
            }
        }
    }

    private function setEquiposFaltante($datos) {
        $fill = false;
        $fill = !$fill;
        $equiposFaltante = $this->DBM->getEquiposFaltante($datos['servicio']);

        if (!empty($equiposFaltante)) {
            if (isset($datos['folio'])) {
                $this->setHeaderPDF("Equipo Faltante", $datos['folio']);
            } else {
                $this->setHeaderPDF('Equipo Faltante');
            }

            foreach ($equiposFaltante as $key => $value) {

                if (($this->y + 26) > 270) {
                    if (isset($datos['folio'])) {
                        $this->setHeaderPDF("Problemas por equipo", $datos['folio']);
                    } else {
                        $this->setHeaderPDF('Problemas por equipo.');
                    }
                }

                $this->setCoordinates(10, $this->y + 5);

                $this->setStyleHeader();
                $this->setHeaderValue($value['Area'] . ' - ' . $value['Punto']);

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Tipo de Artículo", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['NombreItem'], 'L');

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Artículo", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['Equipo'], 'L');

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Cantidad", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['Cantidad'], 'L');
            }
        }
    }

    private function setProblemasAdicionales($datos) {
        $fill = false;
        $fill = !$fill;
        $problemasAdicionales = $this->DBM->getProblemasAdicionales($datos['servicio']);

        if (!empty($problemasAdicionales)) {
            if (isset($datos['folio'])) {
                $this->setHeaderPDF("Problemas Adicionales", $datos['folio']);
            } else {
                $this->setHeaderPDF('Problemas Adicionales');
            }

            foreach ($problemasAdicionales as $key => $value) {

                if (($this->y + 26) > 270) {
                    if (isset($datos['folio'])) {
                        $this->setHeaderPDF("Problemas por equipo", $datos['folio']);
                    } else {
                        $this->setHeaderPDF('Problemas por equipo.');
                    }
                }

                $this->setCoordinates(10, $this->y + 5);

                $this->setStyleHeader();
                $this->setHeaderValue($value['Area'] . ' - ' . $value['Punto']);

                $this->setCoordinates(10);
                $this->setStyleTitle();
                $this->setCellValue(190, 5, "Descripción", 'L', $fill);

                $this->setCoordinates(60, $this->y - 5);
                $this->setCellValue(140, 5, $value['Descripcion'], 'L');

                $this->setEvidenciasPDF($datos, $value['Evidencias'], '');
            }
        }
    }

    private function setHeaderPDF(string $titulo, string $folio = '') {
        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
        $this->pdf->SetXY(0, 13);
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->Cell(0, 0, utf8_decode($titulo), 0, 0, 'C');

        $this->pdf->SetXY(0, 20);
        $this->pdf->SetFont("helvetica", "I", 13);
        $this->pdf->Cell(0, 0, utf8_decode($folio), 0, 0, 'C');
        $this->setCoordinates(10, 36);
    }

    private function setStyleHeader() {
        $this->pdf->SetFillColor(31, 56, 100);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont("helvetica", "BI", 10);
    }

    private function setStyleTitle() {
        $this->pdf->SetTextColor(10, 10, 10);
        $this->pdf->SetFont("helvetica", "BI", 9);
    }

    private function setStyleSubtitle() {
        $this->pdf->SetTextColor(10, 10, 10);
        $this->pdf->SetFont("helvetica", "", 9);
    }

    private function setStyleMinisubtitle() {
        $this->pdf->SetTextColor(10, 10, 10);
        $this->pdf->SetFont("helvetica", "", 7);
    }

    private function setFillGray() {
        $this->pdf->SetFillColor(217, 217, 217);
    }

    private function setFillWhite() {
        $this->pdf->SetFillColor(255, 255, 255);
    }

    private function setCoordinates(int $x = null, int $y = null) {
        if (!is_null($x)) {
            $this->x = $x;
        }

        if (!is_null($y)) {
            $this->y = $y;
        }

        $this->pdf->SetXY($this->x, $this->y);
    }

    private function setHeaderValue(string $value, int $width = 0, int $height = 6) {
        $this->pdf->Cell($width, $height, utf8_decode($value), 1, 0, 'L', true);
        $this->y += 6;
        $this->setCoordinates();
    }

    private function setCellValue($width, $height, $value = '', string $align, bool $fill = false, bool $trueFill = true) {
        if ($fill) {
            $this->setFillGray();
        } else {
            $this->setFillWhite();
        }

        $this->pdf->Cell($width, $height, utf8_decode($value), 1, 0, $align, $trueFill);
        $this->y += $height;
        $this->setCoordinates();
    }

    private function setMulticellValue($width, $height, string $value, string $align, bool $fill = false, bool $trueFill = true) {
        if ($fill) {
            $this->setFillGray();
        } else {
            $this->setFillWhite();
        }

        $this->pdf->MultiCell($width, $height, utf8_decode($value), 1, $align, $trueFill);
    }

    private function obtenerEquipoMaterialServicio(string $servicio) {
        $serviciosAvance = $this->DBST->servicioAvanceProblema($servicio);
        $folio = '';
        $folio .= $this->DBST->consulta('select folioByServicio('.$servicio.') as folio')[0]['folio'];
        $equipoMaterial = array();

        if($serviciosAvance){
            foreach ($serviciosAvance as $avance) {
                $avanceEquipo = $this->DBST->serviciosAvanceEquipo($avance['Id']);
                if($avanceEquipo){
                    foreach ($avanceEquipo as $equipo) {
                        array_push($equipoMaterial, $equipo);
                    }
                }
            }
        }
        if (!empty($equipoMaterial)) {
            $this->agregarPDFEquipoMaterial($equipoMaterial, $folio);
        }
    }

    private function agregarPDFEquipoMaterial(array $materialEquipo, string $folio = '') {
        if (($this->y + 26) > 270) {
            $this->setHeaderPDF("Resumen de Incidente Service Desk", $folio);
        }

        $this->setCoordinates(10);
        $this->setStyleHeader();
        $this->setHeaderValue("Equipo y Material Utilizado");

        $this->setStyleTitle();
        $this->setCellValue(30, 5, "Tipo", 'C', true);
        $this->setCoordinates(40, $this->y - 5);
        $this->setCellValue(100, 5, "Nombre", 'C', true);
        $this->setCoordinates(140, $this->y - 5);
        $this->setCellValue(30, 5, "Serie", 'C', true);
        $this->setCoordinates(170, $this->y - 5);
        $this->setCellValue(30, 5, "Cantidad", 'C', true);

        foreach ($materialEquipo as $value) {
            if (($this->y + 26) > 270) {
                $this->setHeaderPDF("Resumen de Incidente Service Desk", $folio);
            }
            $this->setCoordinates(10);
            $this->setStyleSubtitle();
            $this->setCellValue(30, 5, $value['Tipo'], 'C');
            $this->setCoordinates(40, $this->y - 5);
            $this->setCellValue(100, 5, $value['EquipoMaterial'], 'C');
            $this->setCoordinates(140, $this->y - 5);
            $this->setCellValue(30, 5, $value['Serie'], 'C');
            $this->setCoordinates(170, $this->y - 5);
            $this->setCellValue(30, 5, $value['Cantidad'], 'C');
        }
    }

    function totalEvidenciasSolicitud($servicio) {
        $consulta = $this->DBS->consulta("select Evidencias from t_correctivos_soluciones where IdServicio = '" . $servicio . "'");

        foreach ($consulta as $evidencias) {
            $concatena = $evidencias['Evidencias'] . ',';
        }

        $todaEvidencia = substr($concatena, 0, -1);
        return $todaEvidencia;
    }
}

class PDFAux extends PDF {

    private $dato;

    public function Footer() {
        $fecha = date('d/m/Y');
// Go to 1.5 cm from bottom
        $this->SetY(-15);
// Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
// Print centered page number
// $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(100, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->setFirmas();
    }

    public function setDato(array $dato = null) {
        $this->dato = $dato;
    }

    public function setFirmas() {
        $this->SetFont('Helvetica', 'I', 6);
        $this->SetTextColor(0, 0, 0);
        if (!is_null($this->dato['Firma']) && $this->dato['Firma'] != '') {
            if (file_exists('.' . $this->dato['Firma'])) {
                $this->Image('.' . $this->dato['Firma'], 145, 274, 25, 12, pathinfo($this->dato['Firma'], PATHINFO_EXTENSION));
            } else{
                $this->Image('./assets/img/Iconos/sin_firma.png', 145, 274, 25, 12, 'png');
            }
            $this->Cell(95, 10, utf8_decode($this->dato['Gerente']), 0, 0, 'C');
            $this->SetXY(100, 15);
            $this->Cell(115, 550, utf8_decode('Gerente en turno Cinemex'), 0, 0, 'C');
            $this->SetXY(100, 15);
            $this->Cell(115, 555, utf8_decode($this->dato['FechaFirma']), 0, 0, 'C');
        } else {
            $this->Image('./assets/img/Iconos/sin_firma.png', 145, 274, 25, 12, 'png');
            $this->SetXY(100, 15);
            $this->Cell(115, 548, utf8_decode('Gerente'), 0, 0, 'C');
        }
        
        if (!is_null($this->dato['FirmaTecnico']) && $this->dato['FirmaTecnico'] != '') {
            if (file_exists('.' . $this->dato['FirmaTecnico'])) {
                $this->Image('.' . $this->dato['FirmaTecnico'], 175, 274, 25, 12, pathinfo($this->dato['FirmaTecnico'], PATHINFO_EXTENSION));
            } else{
                $this->Image('./assets/img/Iconos/sin_firma.png', 175, 274, 25, 12, 'png');
            }
            $this->SetXY(100, 15);
            $this->Cell(180, 545, utf8_decode($this->dato['Tecnico']), 0, 0, 'C');
            $this->SetXY(100, 15);
            $this->Cell(180, 550, utf8_decode('Técnico Siccob'), 0, 0, 'C');
            $this->SetXY(100, 15);
            $this->Cell(180, 555, utf8_decode($this->dato['FechaFirma']), 0, 0, 'C');
        } else {
            $this->Image('./assets/img/Iconos/sin_firma.png', 175, 274, 25, 12, 'png');
            $this->SetXY(100, 15);
            $this->Cell(185, 548, utf8_decode('Técnico'), 0, 0, 'C');
        }
    }
}
