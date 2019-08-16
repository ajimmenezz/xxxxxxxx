<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

class InformacionServicios extends General {

    private $DBS;
    private $Phantom;
    private $Correo;
    private $ServiceDesk;
    private $MSP;
    private $MSD;
    private $pdf;
    private $x;
    private $y;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->DBS = \Modelos\Modelo_Loguistica_Seguimiento::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->MSD = \Modelos\Modelo_ServiceDesk::factory();
        $this->pdf = new PDFAux();
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

    public function cambiarEstatusSD(array $datos) {
        $atiende = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                tso.Atiende
                                                                FROM t_servicios_ticket tst
                                                                INNER JOIN t_solicitudes tso
                                                                ON tst.IdSolicitud = tso.Id
                                                                WHERE tst.Id = "' . $datos['Servicio'] . '"');
        $SDkey = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($atiende[0]['Atiende']));

        $servicios = $this->verificarTodosServiciosFolio($datos);

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

            $path = $this->cargarPDF($datos);
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

        $linkPdf = $this->cargarPDF($datos);
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
        $infoServicio = $this->getInformacionServicio($datos['servicio']);

        $datosDescripcionConclusion = $this->DBS->consultaGeneralSeguimiento('SELECT
                                                                                sucursal(tst.IdSucursal) Sucursal
                                                                            FROM t_servicios_ticket tst
                                                                            WHERE tst.Id = "' . $datos['servicio'] . '"');

        $path = $this->cargarPDF($datos);
        $descripcion = $datosDescripcionConclusion[0]['Sucursal'] . ' ' . $infoServicio[0]['TipoServicio'] . ' se concluyo con exito';
        $datosResolucion = '<br>' . $descripcion . "<div><a href='" . $path . "' target='_blank'>Documento PDF</a></div>";

        return $datosResolucion;
    }

    public function avancesProblemasServicio(string $folio) {
        $datosAvancesProblemas = '';
        $datosAvances = '***AVANCES***<br>';
        $datosProblemas = '<br><p style="color:#FF0000";>***PROBLEMAS***</p>';
        $avancesProblemas = '';

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

        if ($datosProblemas == '<br><p style="color:#FF0000";>***PROBLEMAS***</p>') {
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
        $tablaAvancesProblemas = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                                                                    *,
                                                                                CASE IdItem 
                                                                                    WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = TipoItem) 
                                                                                    WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = TipoItem)
                                                                                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = TipoItem) 
                                                                                    WHEN 4 THEN (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = TipoItem) 
                                                                                    WHEN 5 THEN (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = TipoItem) 
                                                                                END as EquipoMaterial 
                                                                            FROM t_servicios_avance_equipo 
                                                                            WHERE IdAvance = "' . $datos['Id'] . '"');

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
                    break;
                case '4':
                    $tipoItem = 'Elemento';
                    break;
                case '5':
                    $tipoItem = 'Sub-Elemento';
                    break;
            }
            if ($valor['IdItem'] === '1') {
                if ($datos['IdTipo'] === '1') {
                    $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Serie: " . $valor['Serie'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                } else {
                    $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
                }
            } else {
                $tabla .= "<div>" . $tipoItem . ": &nbsp " . $valor['EquipoMaterial'] . " &nbsp Cantidad: " . $valor['Cantidad'] . "</div>";
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
        $usuario = $this->Usuario->getDatosUsuario();
        $descripcion = array();
        $informacionSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($usuario['Id']));
        $folio = $this->DBS->consultaGeneralSeguimiento('SELECT 
                                            ts.Folio 
                                        FROM t_servicios_ticket tst
                                        INNER JOIN t_solicitudes ts
                                            ON ts.Id = tst.IdSolicitud
                                        WHERE tst.Id = "' . $informacionSolicitud['servicio'] . '"');

        foreach ($folio as $value) {
            if (isset($value['Folio'])) {
                $this->cambiarEstatusSD(array(
                    'Folio' => $value['Folio'],
                    'Servicio' => $servicio,
                    'ServicioConcluir' => $servicioConcluir
                ));
                $descripcion = $this->MostrarDatosSD($value['Folio'], $informacionSolicitud['servicio'], FALSE, $key);
                $this->ServiceDesk->setResolucionServiceDesk($key, $value['Folio'], $descripcion['html']);
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
        $linkPdf = $this->cargarPDF($datos);
        $descripcion = "<div>Ha concluido el Servicio Checklist</div><br/><a href='" . $linkPdf . "' target='_blank'>DOCUMENTO PDF</a>";

        return $descripcion;
    }

    public function trafficService(array $datos) {
        $linkPdf = $this->cargarPDF($datos);
        $descripcion = "<br/><div>Se ha realizo un servicio de Tráfico</div><a href='" . $linkPdf . "' target='_blank'>DOCUMENTO PDF</a><br/>";
        return $descripcion;
    }

    public function verifyProcess(array $datos) {
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

        if ($datos['servicioConcluir'] === 'true') {
            $servicioConcluir = TRUE;
        } else {
            $servicioConcluir = FALSE;
        }

        $servicios = $this->verificarTodosServiciosFolio(array('Servicio' => $datos['servicio'], 'ServicioConcluir' => $servicioConcluir, 'Folio' => $datosServicios[0]['Folio']));

        if (empty($servicios)) {
            $this->guardarDatosServiceDesk($datos['servicio'], $servicioConcluir);
        } else {
            $key = $this->ServiceDesk->validarAPIKey($this->MSP->getApiKeyByUser($usuario['Id']));
            $htmlServicio = $this->vistaHTMLServicio($datosServicios[0]);
            $this->setNoteAndWorkLog(array('key' => $key, 'folio' => $datosServicios[0]['Folio'], 'html' => $htmlServicio));
        }
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
        $key = $this->MSP->getApiKeyByUser($usuario);
        $result = $this->ServiceDesk->getTecnicosSD($key);

        if ($result->operation->result->status !== 'Success') {
            $key = $this->MSP->getApiKeyByUser('2');
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
        folioByServicio(tst.Id) as SD,
        tst.Ticket,
        nombreUsuario(tst.Atiende) as Atiende,
        (select FechaCreacion from t_solicitudes where Id = tst.IdSolicitud) as FechaSolicitud,
        tst.FechaCreacion,
        tst.FechaInicio,
        tst.Descripcion,
        tst.IdSolicitud,
        nombreUsuario(ts.Solicita) as Solicita,
        ts.FechaCreacion,
        tsi.Asunto,
        tsi.Descripcion as Solicitud,
        tst.IdSucursal,
        tst.IdEstatus,
        estatus(tst.IdEstatus) as Estatus,
        tst.IdTipoServicio,
        tipoServicio(tst.IdTipoServicio) as TipoServicio,
        sucursal(tst.IdSucursal) as Sucursal, 
        (select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal) as IdCliente,
        cliente((select IdCliente from cat_v3_sucursales where Id = tst.IdSucursal)) as Cliente       
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
        tcd.IdTipoDiagnostico,
        (select Nombre from cat_v3_tipos_diagnostico_correctivo where Id = tcd.IdTipoDiagnostico) as TipoDiagnostico,
        (select Nombre from cat_v3_componentes_equipo where Id = tcd.IdComponente) as Componente,
        if(IdTipoDiagnostico = 4, (select Nombre from cat_v3_fallas_refaccion where Id = tcd.IdFalla), (select Nombre from cat_v3_fallas_equipo where Id = tcd.IdFalla)) as Falla,
        tcd.Evidencias,
        tcd.Observaciones
        from t_correctivos_generales tcg
        inner join t_correctivos_diagnostico tcd on tcg.IdServicio = tcd.IdServicio
        where tcg.IdServicio = '" . $id . "'
        order by tcd.Id desc limit 1");
        return $consulta[0];
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
        on tcsr.Id = (select MAX(Id) from t_correctivos_solicitudes_refaccion where IdServicio = tcp.IdServicio)
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
        tcs.Evidencias
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
        Fecha
        from t_servicios_generales 
        where IdServicio = '" . $id . "'");
        return $consulta;
    }

    private function getAvancesProblemasForPDF(int $id) {
        $arrayReturn = [];
        $consulta = $this->DBS->consulta("select
        tsa.Id,
        nombreUsuario(tsa.IdUsuario) as Usuario,
        tsa.IdTipo,
        tsa.Fecha,
        tsa.Descripcion,
        tsa.Archivos as Evidencias
        from t_servicios_avance tsa
        where tsa.IdServicio = '" . $id . "'");
        if (!empty($consulta)) {
            foreach ($consulta as $key => $value) {
                $cmateriales = $this->DBS->consulta("SELECT 
                CASE IdItem 
                    WHEN 1 THEN 'Equipo'
                    WHEN 2 THEN 'Material'
                    WHEN 3 THEN 'Refacción'
                END as Tipo, 
                CASE IdItem 
                    WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = TipoItem) 
                    WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = TipoItem)
                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = TipoItem) 
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

    private function getFirmasServicio(int $servicio) {
        $consulta = $this->DBS->consulta("
        select 
        Firma,
        NombreFirma as Gerente,
        FechaFirma,
        nombreUsuario(tst.IdTecnicoFirma) as Tecnico,
        FirmaTecnico
        from t_servicios_ticket tst where Id = '" . $servicio . "'");
        return $consulta[0];
    }

    public function pdfFromFolio(array $datos) {
        if (!isset($datos['folio'])) {
            return ["code" => 500, "message" => "The parameter 'folio' is mandatory"];
        } else if (!is_numeric($datos['folio'])) {
            return ["code" => 500, "message" => "The parameter 'folio' must be a number"];
        } else {
            $servicios = $this->getServiciosByFolio($datos['folio']);

            if (!empty($servicios)) {
                $this->setHeaderPDF($datos['folio']);

                foreach ($servicios as $k => $v) {
                    $generales = $this->getGeneralesServicio($v['Id']);

                    if (($this->y + 26) > 276) {
                        $this->setHeaderPDF($datos['folio']);
                    }

                    $this->setStyleHeader();
                    $this->setCoordinates();
                    $this->setHeaderValue("#" . ($k + 1) . " Información General");

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Cliente:", 'R', true);
                    $this->setCellValue(25, 5, "Sucursal:", 'R');
                    $this->setCellValue(25, 5, "Tipo Serv:", 'R', true);
                    $this->setCoordinates(100, $this->y - 5);
                    $this->setCellValue(25, 5, "Estatus:", 'R', true);
                    $this->setCoordinates(10);
                    $this->setCellValue(25, 5, "Atiende:", 'R');

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 20);
                    $this->setCellValue(0, 5, $generales['Cliente'], 'L', true);
                    $this->setCellValue(0, 5, $generales['Sucursal'], 'L');
                    $this->setCellValue(75, 5, $generales['TipoServicio'], 'L', true);
                    $this->setCoordinates(125, $this->y - 5);
                    $this->setCellValue(75, 5, $generales['Estatus'], 'L', true);
                    $this->setCoordinates(35);
                    $this->setCellValue(0, 5, $generales['Atiende'], 'L');
                    $this->setCoordinates(10);

                    if ($v['HasSeguimiento'] == 0) {
                        $this->setPDFContentSinSeguimiento($generales['Id'], $datos);
                    } else {

                        switch ($generales['IdTipoServicio']) {
                            case 20:
                            case '20':
                                $this->setPDFContentCorrectivo($generales['Id'], $datos);
                                break;
                        }
                    }

                    $this->setFirmasServicio($generales['Id'], $datos);
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

    private function setPDFContentSinSeguimiento(int $id, array $datos) {
        $this->setAvancesProblemasPDF($id, $datos);

        $resolucion = $this->getResolucionSinClasificarForPDF($id);
        if (isset($resolucion[0])) {
            $resolucion = $resolucion[0];
            if (($this->y + 26) > 276) {
                $this->setHeaderPDF($datos['folio']);
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

            $this->setCoordinates(10, $this->pdf->GetY());

            $this->setEvidenciasPDF($datos, $resolucion['Evidencias'], 'Documentación del Servicio');
        }
    }

    private function setAvancesProblemasPDF(int $id, array $datos) {
        $registros = $this->getAvancesProblemasForPDF($id);
        if (!empty($registros)) {
            if (($this->y + 26) > 276) {
                $this->setHeaderPDF($datos['folio']);
            }
            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Historial de Avances y Problemas");

            foreach ($registros as $key => $value) {
                $this->setStyleTitle();
                $this->setCellValue(25, 5, "Usuario:", 'R', true);
                $this->setCoordinates(100, $this->y - 5);
                $this->setCellValue(25, 5, "Fecha:", 'R', true);

                $this->setStyleSubtitle();
                $this->setCoordinates(35, $this->y - 5);
                $this->setCellValue(75, 5, $value['Usuario'], 'L', true);
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

                $this->setEvidenciasPDF($datos, $value['Evidencias'], 'Historial de Avances y Problemas');
            }
        }
    }

    private function setFirmasServicio(int $id, array $datos) {
        $firmas = $this->getFirmasServicio($id);
        if ((!is_null($firmas['Firma']) && $firmas['Firma'] != '') || (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '')) {
            if (($this->y + 56) > 276) {
                $this->setHeaderPDF($datos['folio']);
            }

            $this->setCoordinates(10);
            $this->setStyleHeader();
            $this->setHeaderValue("Firmas del Servicio");

            $this->setStyleTitle();
            $this->setCellValue(95, 40, "", 'C');
            $this->setCoordinates(10, $this->y - 40);

            $gerente = '';
            if (!is_null($firmas['Firma']) && $firmas['Firma'] != '') {
                $this->pdf->Image('.' . $firmas['Firma'], $this->x + 7.5, $this->y + 2.5, 80, 35, pathinfo($firmas['Firma'], PATHINFO_EXTENSION));
                $gerente = utf8_decode($firmas['Gerente']);
            }

            $this->setCoordinates(105);

            $this->setCellValue(95, 40, "", 'C');
            $this->setCoordinates(105, $this->y - 40);

            $tecnico = '';
            if (!is_null($firmas['FirmaTecnico']) && $firmas['FirmaTecnico'] != '') {
                $this->pdf->Image('.' . $firmas['FirmaTecnico'], $this->x + 7.5, $this->y + 2.5, 80, 35, pathinfo($firmas['FirmaTecnico'], PATHINFO_EXTENSION));
                $tecnico = utf8_decode($firmas['Tecnico']);
            }

            $this->setCoordinates(10, $this->y + 40);
            $this->setCellValue(95, 5, $gerente, 'C', true);
            $this->setCoordinates(105, $this->y - 5);
            $this->setCellValue(95, 5, $tecnico, 'C', true);

            $this->setCoordinates(10);
            $this->setCellValue(95, 5, 'Gerente Cinemex', 'C', true);
            $this->setCoordinates(105, $this->y - 5);
            $this->setCellValue(95, 5, "Técnico Siccob", 'C', true);
        }
    }

    private function setEvidenciasPDF($datos, $evidencias, $header) {
        $evidencias = explode(",", $evidencias);
        $totalEvidencias = count($evidencias);
        if ($totalEvidencias > 0) {

            $filas = ceil($totalEvidencias / 4);

            $indice = 0;
            for ($f = 1; $f <= $filas; $f++) {
                if (($this->y + 45) > 276) {
                    $this->setHeaderPDF($datos['folio']);
                    $this->setStyleHeader();
                    $this->setHeaderValue($header);
                }

                $this->setCoordinates(10);

                for ($i = 1; $i <= 4; $i++) {
                    if (isset($evidencias[$indice]) && $evidencias[$indice] != '') {
                        $url = $evidencias[$indice];
                        $image = $url;
                        if (!in_array(pathinfo($url, PATHINFO_EXTENSION), ['JPG', 'JPEG', 'PNG', 'GIF', 'jpg', 'jpeg', 'png', 'gif'])) {
                            $image = '/assets/img/Iconos/no-thumbnail.jpg';
                        }
                        $this->pdf->Image('.' . $image, $this->x + 2.5, $this->y + 2.5, 42.5, 40, pathinfo($image, PATHINFO_EXTENSION), 'http://siccob.solutions' . $url);
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
        $this->setDiagnosticoCorrectivoPDF($diagnostico, $datos);

        $problema = $this->getProblemaCorrectivoForPDF($id);
        $this->setProblemaCorrectivoPDF($problema, $datos);

        $solucion = $this->getSolucionCorrectivoForPDF($id);
        $this->setSolucionCorrectivoPDF($solucion, $datos);
    }

    private function setDiagnosticoCorrectivoPDF($diagnostico, $datos) {
        if (($this->y + 26) > 276) {
            $this->setHeaderPDF($datos['folio']);
        }

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
        ;

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

        $this->setEvidenciasPDF($datos, $diagnostico['Evidencias'], "Diagnóstico " . $diagnostico['TipoDiagnostico']);
    }

    private function setProblemaCorrectivoPDF($problema, $datos) {
        if (isset($problema[0])) {
            $problema = $problema[0];
            if (($this->y + 26) > 276) {
                $this->setHeaderPDF($datos['folio']);
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
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Equipo:", 'R');

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(0, 5, $problema['Equipo'], 'L');
                    break;
                case 3:
                case '3':
                    $this->setCoordinates(10);
                    $this->setStyleTitle();
                    if ($problema['DejaRespaldo'] == 1 || 1 == 1) {
                        $this->setCellValue(25, 5, "Respaldo:", 'R');
                        $this->setCoordinates(130, $this->y - 5);
                        $this->setCellValue(25, 5, "Serie:", 'R');

                        $this->setCoordinates(10);

                        $this->setStyleSubtitle();
                        $this->setCoordinates(35, $this->y - 5);
                        $this->setCellValue(95, 5, $problema['EquipoRespaldo'], 'L');
                        $this->setCoordinates(155, $this->y - 5);
                        $this->setCellValue(0, 5, $problema['SerieRespaldo'], 'L');
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
            if (($this->y + 16) > 276) {
                $this->setHeaderPDF($datos['folio']);
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
                    $this->setCoordinates(10);

                    $this->setStyleTitle();
                    $this->setCellValue(25, 5, "Solución:", 'R');

                    $this->setStyleSubtitle();
                    $this->setCoordinates(35, $this->y - 5);
                    $this->setCellValue(0, 5, $solucion['SolucionSinEquipo'], 'L');
                    break;
                case 2:
                case '2':
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

            $this->setEvidenciasPDF($datos, $solucion['Evidencias'], "Solución del Servicio");
        }
    }

    private function setHeaderPDF(int $folio) {
        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
        $this->pdf->SetXY(0, 13);
        $this->pdf->SetFont("helvetica", "B", 15);
        $this->pdf->Cell(0, 0, utf8_decode("Resumen de Incidente Service Desk"), 0, 0, 'C');

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

    private function setHeaderValue(string $value) {
        $this->pdf->Cell(0, 6, utf8_decode($value), 1, 0, 'L', true);
        $this->y += 6;
        $this->setCoordinates();
    }

    private function setCellValue($width, $height, string $value, string $align, bool $fill = false, bool $trueFill = true) {
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

}

class PDFAux extends PDF {

    function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(68, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

}
