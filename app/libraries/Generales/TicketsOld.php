<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Solicitud
 *
 * @author Alonso
 */
class TicketsOld extends General {

    private $DBTO;
    private $Phantom;

    public function __construct() {
        parent::__construct();
        $this->DBTO = \Modelos\Modelo_TicketsOld::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date', 'conversionpalabra'));
    }

    public function getTipoByTicket(string $ticket = null) {
        return $this->DBTO->getTipoByTicket($ticket);
    }

    public function getContenidoCorrectivo(string $ticket, bool $esPdf = false) {
        $generales = $this->getGeneralesTicket($ticket);
        $detalles = $this->getDetallesCorrectivo($ticket);
        $notas = array();
        $html = '';

        $data = [
            'generales' => $generales,
            'detalles' => $detalles,
            'notas' => $notas
        ];

        if ($esPdf) {
            return parent::getCI()->load->view('TicketsOld/Correctivo', $data, TRUE);
        }
    }

    public function getDetallesCorrectivo(string $ticket = null) {
        $sentencia = ""
                . "select "
                . "Servicio, "
                . "estatusserv(Estatus_SAD) as Estatus, "
                . "substatusserv(Substatus_SAD) as Substatus, "
                . "FechaEstatus_SAD as FechaEstatus, "
                . "DescEstatus_SAD as DescripcionEstatus, "
                . "Area_SAD as Area, "
                . "concat(lineaC1(Linea_SAD),'/',Marca_SAD,'/',Modelo_SAD) as Equipo, "
                . "Serie_SAD as Serie, "
                . "categoriaFalla(Categoria_SAD) as TipoFalla, "
                . "if(Clasificacion_SAD is null or Clasificacion_SAD = '','SIN CLASIF',clasifFalla(Clasificacion_SAD)) as Clasificacion, "
                . "refaccion(TipoEquipo_SAD) as Componente, "
                . "falla(Falla_SAD) as Falla, "
                . "solucion(Solucion_SAD) as Solucion, "
                . "Equipo_Utilizado "
                . "from t_info_reporte_general where Id_Orden = '" . $ticket . "';";
        return $this->DBTO->consultaAD2($sentencia);
    }

    public function getGeneralesTicket(string $ticket) {
        $sentencia = "SELECT "
                . "ts.Id_Orden as Ticket, "
                . "ts.Folio_Cliente as Folio, "
                . "tipo(ts.Tipo) as Tipo, "
                . "ts.Estatus, "
                . "sucursalFullCliente(ts.Sucursal) as Sucursal, "
                . "tecnico(ts.Ingeniero) as Ingeniero,"
                . "ts.Observaciones, "
                . "now() as Fecha "
                . "from t_servicios ts where Id_Orden = '" . $ticket . "'";
        $consulta = $this->DBTO->consultaAD2($sentencia);

        $arrayReturn = [
            'ticket' => 'Sin Información',
            'folio' => 'Sin Información',
            'tipo' => 'Sin Información',
            'estatus' => 'Sin Información',
            'sucursal' => 'Sin Información',
            'ingeniero' => 'Sin Información',
            'observaciones' => 'Sin Información',
            'fecha' => 'Sin Información'
        ];

        if ($consulta) {
            if (array_key_exists(0, $consulta)) {
                $observaciones = str_replace("ASUNTO: ", "<br />ASUNTO: ", $consulta[0]['Observaciones']);
                $observaciones = str_replace("DESCRIPCIÓN: ", "<br />DESCRIPCIÓN: ", $observaciones);
                $observaciones = str_replace("RESOLUCIÓN: ", "<br />RESOLUCIÓN: ", $observaciones);
                $arrayReturn['ticket'] = ($consulta[0]['Ticket'] !== '') ? $consulta[0]['Ticket'] : 'Sin Información';
                $arrayReturn['folio'] = $consulta[0]['Folio'];
                $arrayReturn['tipo'] = ($consulta[0]['Tipo'] !== '') ? $consulta[0]['Tipo'] : 'Sin Información';
                $arrayReturn['estatus'] = ($consulta[0]['Estatus'] !== '') ? $consulta[0]['Estatus'] : 'Sin Información';
                $arrayReturn['sucursal'] = ($consulta[0]['Sucursal'] !== '') ? $consulta[0]['Sucursal'] : 'Sin Información';
                $arrayReturn['ingeniero'] = ($consulta[0]['Ingeniero'] !== '') ? $consulta[0]['Ingeniero'] : 'Sin Información';
                $arrayReturn['fecha'] = ($consulta[0]['Fecha'] !== '') ? $consulta[0]['Fecha'] : 'Sin Información';
                $arrayReturn['observaciones'] = ($observaciones !== '') ? $observaciones : 'Sin Información';
            }
        }
        return $arrayReturn;
    }

    public function getDetallesByServicio(array $servicio) {
        $tipo = $this->getTipoByServicio($servicio['servicio']);
        $html = '';
        switch ($tipo[0]['IdTipoServicio']) {
            case '5': case 5:
                $html = $this->getDetallesTrafico($servicio['servicio']);
                break;
            case '10': case 10:
                $html = $this->getDetallesUber($servicio['servicio']);
                break;
            case '9': case 9:
                $html = $this->getDetallesSinClasificar($servicio['servicio']);
                break;
        }

        return ['html' => $html];
    }

    public function getDetallesTrafico(string $servicio) {
        $detalles = $this->getGeneralesTrafico($servicio);
        $optionEquipos = $this->getHtmlEquiposTrafico($servicio);
        $notas = $this->Notas->getNotasByServicio($servicio);

        $tipo = ($detalles[0]['Tipo'] !== '') ? $detalles[0]['Tipo'] : 'Sin Información';
        $encargado = ($detalles[0]['Encargado'] !== '') ? $detalles[0]['Encargado'] : 'Sin Información';
        $origen = ($detalles[0]['Origen'] !== '') ? $detalles[0]['Origen'] : 'Sin Información';
        $destino = ($detalles[0]['Destino'] !== '') ? $detalles[0]['Destino'] : 'Sin Información';

        $documentacion = $this->getDocumentacionEnvioRecoleccionTrafico($servicio);
        $htmlDocumentacion = '';

        switch ($detalles[0]['IdTipo']) {
            case 1:
                $htmlArchivos = '';
                $fechaEnvio = $fechaEntrega = $recibe = $comentariosEntrega = 'Sin Información';
                if (array_key_exists(0, $documentacion)) {
                    $fechaEnvio = ($documentacion[0]['FechaEnvio'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEnvio'])) . date("h:ma", strtotime($documentacion[0]['FechaEnvio'])) : 'Sin información';
                    $fechaEntrega = ($documentacion[0]['FechaEntrega'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEntrega'])) . date("h:ma", strtotime($documentacion[0]['FechaEntrega'])) : 'Sin información';
                    $recibe = ($documentacion[0]['Recibe'] !== '') ? $documentacion[0]['Recibe'] : 'Sin Información';
                    $comentariosEntrega = ($documentacion[0]['ComentariosEntrega'] !== '') ? $documentacion[0]['ComentariosEntrega'] : 'Sin Información';

                    if ($documentacion[0]['EvidenciaEntrega'] !== '' && $documentacion[0]['EvidenciaEntrega'] !== NULL) {
                        $htmlArchivos .= '';
                        $archivos = explode(",", $documentacion[0]['EvidenciaEntrega']);
                        foreach ($archivos as $k => $v) {
                            $pathInfo = pathinfo($v);
                            $src = $this->getSrcByPath($pathInfo, $v);
                            $htmlArchivos .= ''
                                    . '<div class="evidencia">'
                                    . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-entrega-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                    . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                    . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                    . ' </a>'
                                    . '</div>';
                        }
                    }



                    $htmlDocumentacion .= ''
                            . '<div class="row">'
                            . ' <div class="col-md-6 col-xs-12">'
                            . '     <h6>Fecha y Hora de Envío</h6>'
                            . '     <h5>' . $fechaEnvio . '</h5>'
                            . ' </div>'
                            . '</div>';
                    if (in_array($documentacion[0]['IdTipoEnvio'], [2, 3, '2', '3'])) {
                        $paqueteria = ($documentacion[0]['Paqueteria'] !== '') ? $documentacion[0]['Paqueteria'] : 'Sin Información';
                        $guia = ($documentacion[0]['Guia'] !== '') ? $documentacion[0]['Guia'] : 'Sin Información';
                        $comentariosEnvio = ($documentacion[0]['ComentariosEnvio'] !== '') ? $documentacion[0]['ComentariosEnvio'] : 'Sin Información';
                        $htmlArchivosE = '';
                        if ($documentacion[0]['EvidenciaEnvio'] !== '' && $documentacion[0]['EvidenciaEnvio'] !== NULL) {
                            $htmlArchivos .= '';
                            $archivos = explode(",", $documentacion[0]['EvidenciaEnvio']);
                            foreach ($archivos as $k => $v) {
                                $pathInfo = pathinfo($v);
                                $src = $this->getSrcByPath($pathInfo, $v);
                                $htmlArchivosE .= ''
                                        . '<div class="evidencia">'
                                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-envio-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                        . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                        . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                        . ' </a>'
                                        . '</div>';
                            }
                        } else {
                            $htmlArchivosE .= ''
                                    . '<h5>Sin Información</h5>';
                        }
                        $htmlDocumentacion .= ''
                                . '<div class="row">'
                                . ' <div class="col-md-12 col-xs-12">'
                                . '     <h4>Información de Paqueteria y Consolidado</h4>'
                                . '     <div class="underline"></div>'
                                . ' </div>'
                                . '</div>'
                                . '<div class="row">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h6>Paquetería</h6>'
                                . '     <h5>' . $paqueteria . '</h5>'
                                . ' </div>'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h6>Guía o Referencia</h6>'
                                . '     <h5>' . $guia . '</h5>'
                                . ' </div>'
                                . '</div>'
                                . '<div class="row">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h6>Comentarios de Envío</h6>'
                                . '     <h5>' . $comentariosEnvio . '</h5>'
                                . ' </div>'
                                . '</div>'
                                . '<div class="row">'
                                . ' <div class="col-md-6 col-xs-12">'
                                . '     <h6>Evidencia de Envío</h6>'
                                . '     ' . $htmlArchivosE
                                . ' </div>'
                                . '</div>';
                    }
                }
                $htmlDocumentacion .= ''
                        . '<div class="row">'
                        . ' <div class="col-md-12 col-xs-12">'
                        . '     <h4>Información de Entrega</h4>'
                        . '     <div class="underline"></div>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>Fecha y Hora de Entrega</h6>'
                        . '     <h5>' . $fechaEntrega . '</h5>'
                        . ' </div>'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>¿Quién Recibe?</h6>'
                        . '     <h5>' . $recibe . '</h5>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>Comentarios de Entrega</h6>'
                        . '     <h5>' . $comentariosEntrega . '</h5>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>Evidencia de Entrega</h6>'
                        . '     ' . $htmlArchivos
                        . ' </div>'
                        . '</div>';
                break;
        }

        $html = ''
                . '<div class="row">'
                . ' <div class="col-md-12 col-xs-12">'
                . '     <ul class="nav nav-pills">'
                . '         <li class="active"><a href="#nav-pills-resumen" data-toggle="tab" aria-expanded="false">Resumen</a></li>'
                . '         <li class=""><a href="#nav-pills-notas" data-toggle="tab" aria-expanded="true">Conversación del Servicio</a></li>'
                . '     </ul>'
                . '     <div class="tab-content">'
                . '         <div class="tab-pane fade active in" id="nav-pills-resumen">'
                . '             <div class="row">'
                . '                 <div class="col-md-12 col-xs-12 pull-right">'
                . '                     <a id="btnGeneraPdfServicio" class="btn btn-danger btn-sm pull-right"><i class="fa fa-file-pdf-o"></i> Generar Pdf</a>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row">'
                . '                 <div class="col-md-12 col-xs-12">'
                . '                     <h3>Información General del tráfico</h3>'
                . '                     <div class="underline"></div>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row">'
                . '                 <div class="col-md-6 col-xs-12">'
                . '                     <h5>Tipo de Tráfico</h5>'
                . '                     <h4>' . $tipo . '</h4>'
                . '                 </div>'
                . '                 <div class="col-md-6 col-xs-12">'
                . '                     <h5>Encargado de Ruta</h5>'
                . '                     <h4>' . $encargado . '</h4>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row">'
                . '                 <div class="col-md-6 col-xs-12">'
                . '                     <h5>Origen</h5>'
                . '                     <h4>' . $origen . '</h4>'
                . '                 </div>'
                . '                 <div class="col-md-6 col-xs-12">'
                . '                     <h5>Destino</h5>'
                . '                     <h4>' . $destino . '</h4>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row m-t-10">'
                . '                 <div class="col-md-12 col-xs-12">'
                . '                     <h3>Detalle de Items</h3>'
                . '                     <div class="underline"></div>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row m-t-10">'
                . '                 <div class="col-md-12 col-xs-12">'
                . '                     <table id="data-table-detalle-items" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">'
                . '                         <thead>'
                . '                             <tr>'
                . '                                 <th class="all">Item</th>'
                . '                                 <th class="all">Serie</th>'
                . '                                 <th class="all">Cantidad</th>'
                . '                             </tr>'
                . '                         </thead>'
                . '                         <tbody>'
                . '                         ' . $optionEquipos
                . '                         </tbody>'
                . '                     </table>'
                . '                 </div>'
                . '             </div>'
                . '             <div class="row">'
                . '                 <div class="col-md-12 col-xs-12">'
                . '                     <h3>Documentación del servicio.</h3>'
                . '                     <div class="underline"></div>'
                . '                 </div>'
                . '             </div>'
                . '             ' . $htmlDocumentacion
                . '         </div>'
                . '         <div class="tab-pane fade" id="nav-pills-notas">'
                . '         ' . $notas
                . '         </div>'
                . '     </div>'
                . ' </div>'
                . '</div>';

        return $html;
    }

    public function getGeneralesTrafico(string $servicio) {
        $sentencia = "SELECT "
                . "ttg.IdTipoTrafico as IdTipo, "
                . "(select Nombre from cat_v3_tipos_trafico where Id = ttg.IdTipoTrafico) as Tipo, "
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
                . "from t_traficos_generales ttg left join t_servicios_x_ruta tsxr "
                . "on ttg.IdServicio = tsxr.IdServicio "
                . "where ttg.IdServicio = " . $servicio . ";";
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getHtmlEquiposTrafico(string $servicio) {
        $sentencia = ""
                . "select "
                . "CASE IdTipoEquipo "
                . " WHEN 4 THEN DescripcionOtros "
                . " ELSE (select concat(Clave,' - ',Nombre) from cat_v3_equipos_sae where Id = IdModelo) "
                . "END as Equipo, "
                . "Serie, "
                . "Cantidad "
                . "from t_traficos_equipo "
                . "where IdServicio = '" . $servicio . "'";
        $equipos = $this->DBS->consultaGeneral($sentencia);
        $optionEquipos = '';
        foreach ($equipos as $key => $value) {
            $optionEquipos .= ''
                    . '<tr>'
                    . ' <td>' . $value['Equipo'] . '</td>'
                    . ' <td>' . $value['Serie'] . '</td>'
                    . ' <td>' . $value['Cantidad'] . '</td>'
                    . '</tr>';
        }

        return $optionEquipos;
    }

    public function getDocumentacionEnvioRecoleccionTrafico(string $servicio) {
        $sentencia = ""
                . "SELECT "
                . "tte.FechaEnvio, "
                . "tte.IdTipoEnvio as IdTipoEnvio, "
                . "(select Nombre from cat_v3_tipos_envio where Id = tte.IdTipoEnvio) as TipoEnvio, "
                . "paqueteria(tte.IdPaqueteria) as Paqueteria, "
                . "tte.Guia, "
                . "tte.ComentariosEnvio, "
                . "tte.UrlEnvio as EvidenciaEnvio, "
                . "tte.FechaEntrega, "
                . "tte.NombreRecibe as Recibe, "
                . "tte.ComentariosEntrega, "
                . "tte.UrlEntrega as EvidenciaEntrega "
                . "from t_traficos_envios tte "
                . "where IdServicio = '" . $servicio . "';";
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getTraficoHtmlToPdf(string $servicio) {
        $detallesSolicitud = $this->getInformacionServicio($servicio);
        $detalles = $this->getGeneralesTrafico($servicio);
        $optionEquipos = $this->getHtmlEquiposTrafico($servicio);
        $notas = $this->Notas->getNotasByServicio($servicio);

        $DS_solicitud = ($detallesSolicitud[0]['Solicitud'] !== '') ? $detallesSolicitud[0]['Solicitud'] : 'Sin Información';
        $DS_solicitante = ($detallesSolicitud[0]['Solicitante'] !== '') ? $detallesSolicitud[0]['Solicitante'] : 'Sin Información';
        $DS_fechaSolicitud = ($detallesSolicitud[0]['FechaSolicitud'] !== '') ? $detallesSolicitud[0]['FechaSolicitud'] : 'Sin Información';
        $DS_estatusSolicitud = ($detallesSolicitud[0]['EstatusSolicitud'] !== '') ? $detallesSolicitud[0]['EstatusSolicitud'] : 'Sin Información';
        $DS_descripcionSolicitud = ($detallesSolicitud[0]['DescripcionSolicitud'] !== '') ? $detallesSolicitud[0]['DescripcionSolicitud'] : 'Sin Información';
        $DS_ticket = ($detallesSolicitud[0]['Ticket'] !== '') ? $detallesSolicitud[0]['Ticket'] : 'Sin Información';
        $DS_tipoServicio = ($detallesSolicitud[0]['TipoServicio'] !== '') ? $detallesSolicitud[0]['TipoServicio'] : 'Sin Información';
        $DS_fechaServicio = ($detallesSolicitud[0]['FechaServicio'] !== '') ? $detallesSolicitud[0]['FechaServicio'] : 'Sin Información';
        $DS_estatusServicio = ($detallesSolicitud[0]['EstatusServicio'] !== '') ? $detallesSolicitud[0]['EstatusServicio'] : 'Sin Información';
        $DS_descripcionServicio = ($detallesSolicitud[0]['DescripcionServicio'] !== '') ? $detallesSolicitud[0]['DescripcionServicio'] : 'Sin Información';
        $DS_tiempoSolicitud = ($detallesSolicitud[0]['TiempoSolicitud'] !== '') ? $detallesSolicitud[0]['TiempoSolicitud'] : 'Sin Información';
        $DS_tiempoServicio = ($detallesSolicitud[0]['TiempoServicio'] !== '') ? $detallesSolicitud[0]['TiempoServicio'] : 'Sin Información';


        $tipo = ($detalles[0]['Tipo'] !== '') ? $detalles[0]['Tipo'] : 'Sin Información';
        $encargado = ($detalles[0]['Encargado'] !== '') ? $detalles[0]['Encargado'] : 'Sin Información';
        $origen = ($detalles[0]['Origen'] !== '') ? $detalles[0]['Origen'] : 'Sin Información';
        $destino = ($detalles[0]['Destino'] !== '') ? $detalles[0]['Destino'] : 'Sin Información';

        $documentacion = $this->getDocumentacionEnvioRecoleccionTrafico($servicio);
        $htmlDocumentacion = '';

        $style = [
            'th-50' => 'width:50%; padding-top:0px !important; padding-bottom:0px;',
            'td-50' => 'width:50%;',
            'th-25' => 'width:25%; padding-top:0px !important; padding-bottom:0px;',
            'td-25' => 'width:25%;',
            'th' => 'padding-top:0px !important; padding-bottom:0px;'
        ];

        $htmlArchivos = $htmlArchivosTexto = $htmlArchivosE = $htmlArchivosETexto = '';
        switch ($detalles[0]['IdTipo']) {
            case 1:
                $fechaEnvio = $fechaEntrega = $recibe = $comentariosEntrega = 'Sin Información';
                if (array_key_exists(0, $documentacion)) {
                    $fechaEnvio = ($documentacion[0]['FechaEnvio'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEnvio'])) . date("h:ma", strtotime($documentacion[0]['FechaEnvio'])) : 'Sin información';
                    $fechaEntrega = ($documentacion[0]['FechaEntrega'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['FechaEntrega'])) . date("h:ma", strtotime($documentacion[0]['FechaEntrega'])) : 'Sin información';
                    $recibe = ($documentacion[0]['Recibe'] !== '') ? $documentacion[0]['Recibe'] : 'Sin Información';
                    $comentariosEntrega = ($documentacion[0]['ComentariosEntrega'] !== '') ? $documentacion[0]['ComentariosEntrega'] : 'Sin Información';

                    if ($documentacion[0]['EvidenciaEntrega'] !== '' && $documentacion[0]['EvidenciaEntrega'] !== NULL) {
                        $archivos = explode(",", $documentacion[0]['EvidenciaEntrega']);
                        $cont = 0;
                        foreach ($archivos as $k => $v) {
                            $cont ++;
                            $pathInfo = pathinfo($v);
                            $src = $this->getSrcByPath($pathInfo, $v);
                            $htmlArchivosTexto .= ''
                                    . '<a style="font-size:0px; display:block" '
                                    . ' href="http://' . $_SERVER['HTTP_HOST'] . $v . '">'
                                    . ' <h5 style="font-size:12px !important;" >Archivo Entrega ' . $cont . ': ' . $pathInfo['basename'] . '</h5>" '
                                    . '</a>';
                            $htmlArchivos .= ''
                                    . '<div style="page-break-before: always;"></div>'
                                    . ' <div class="text-center">'
                                    . ' <h4>Archivo Entrega ' . $cont . ': ' . $pathInfo['basename'] . '</h4>'
                                    . ' <img class="img-rounded img-thumbnail" src="http://' . $_SERVER['HTTP_HOST'] . $src . '" style="max-height:800px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                    . '</div>';
                        }
                    }

                    if (in_array($documentacion[0]['IdTipoEnvio'], [2, 3, '2', '3'])) {
                        $paqueteria = ($documentacion[0]['Paqueteria'] !== '') ? $documentacion[0]['Paqueteria'] : 'Sin Información';
                        $guia = ($documentacion[0]['Guia'] !== '') ? $documentacion[0]['Guia'] : 'Sin Información';
                        $comentariosEnvio = ($documentacion[0]['ComentariosEnvio'] !== '') ? $documentacion[0]['ComentariosEnvio'] : 'Sin Información';
                        if ($documentacion[0]['EvidenciaEnvio'] !== '' && $documentacion[0]['EvidenciaEnvio'] !== NULL) {
                            $archivos = explode(",", $documentacion[0]['EvidenciaEnvio']);
                            $cont = 0;
                            foreach ($archivos as $k => $v) {
                                $cont++;
                                $pathInfo = pathinfo($v);
                                $src = $this->getSrcByPath($pathInfo, $v);
                                $htmlArchivosETexto .= ''
                                        . '<a style="font-size:0px; display:block" '
                                        . 'href="http://' . $_SERVER['HTTP_HOST'] . $v . '">'
                                        . '<h5 style="font-size:12px !important;" >Archivo Envío ' . $cont . ': ' . $pathInfo['basename'] . '</h5>" '
                                        . '</a>';
                                $htmlArchivosE .= ''
                                        . '<div style="page-break-before: always;"></div>'
                                        . ' <div class="text-center">'
                                        . '     <h4>Archivo Envío ' . $cont . ': ' . $pathInfo['basename'] . '</h4>'
                                        . '     <img class="img-rounded img-thumbnail" src="http://' . $_SERVER['HTTP_HOST'] . $src . '" style="margin-left:auto; margin-right:auto; max-height:850px !important; !important;" alt="http://' . $_SERVER['HTTP_HOST'] . $src . '"  />'
                                        . ' </div>';
                            }
                        }
                        $htmlDocumentacion .= '<br />'
                                . '     <h4>Información de Paqueteria y Consolidado</h4>'
                                . '     <div class="underline"></div>'
                                . '         <table class="table table-condensed" style="width:100% !important;">'
                                . '             <tr>'
                                . '                 <th style="' . $style['th-50'] . '"><h6><strong>Paquetería</strong></h6></th>'
                                . '                 <th style="' . $style['th-50'] . '"><h6><strong>Guía O Referencia</strong></h6></th>'
                                . '             </tr>'
                                . '             <tr>'
                                . '                 <td style="' . $style['td-50'] . '"><h5>' . $paqueteria . '</h5></td>'
                                . '                 <td style="' . $style['td-50'] . '"><h5>' . $guia . '</h5></td>'
                                . '             </tr>'
                                . '             <tr>'
                                . '                 <th style="' . $style['th'] . '" colspan="2"><h6><strong>Comentarios de Envío</strong></h6></th>'
                                . '             </tr>'
                                . '             <tr>'
                                . '                 <td colspan="2"><h5>' . $comentariosEnvio . '</h5></td>'
                                . '             </tr>'
                                . '             <tr>'
                                . '                 <th style="' . $style['th'] . '" colspan="2"><h6><strong>Evidencia de Envío</strong></h6></th>'
                                . '             </tr>'
                                . '             <tr>'
                                . '                 <td colspan="2">' . $htmlArchivosETexto . '</td>'
                                . '             </tr>'
                                . '         </table>';
                    }
                }

                $htmlDocumentacion .= ''
                        . '     <h6><strong>Fecha y Hora de Envío</strong></h6>'
                        . '     <h5>' . $fechaEnvio . '</h5>'
                        . '     <h4>Información de Entrega</h4>'
                        . '     <div class="underline"></div>'
                        . '     <table class="table table-condensed">'
                        . '         <tr>'
                        . '             <th style="' . $style['th-50'] . '"><h6><strong>Fecha y Hora de Entrega</strong></h6></th>'
                        . '             <th style="' . $style['th-50'] . '"><h6><strong>¿Quién Recibe?</strong></h6></th>'
                        . '         </tr>'
                        . '         <tr>'
                        . '             <td style="' . $style['td-50'] . '"><h5>' . $fechaEntrega . '</h5></td>'
                        . '             <td style="' . $style['td-50'] . '"><h5>' . $recibe . '</h5></td>'
                        . '         </tr>'
                        . '         <tr>'
                        . '             <th style="' . $style['th'] . '" colspan="2"><h6><strong>Comentarios de Entrega</strong></h6></th>'
                        . '         </tr>'
                        . '         <tr>'
                        . '             <td colspan="2"><h5>' . $comentariosEntrega . '</h5></td>'
                        . '         </tr>'
                        . '         <tr>'
                        . '             <th style="' . $style['th'] . '" colspan="2"><h6><strong>Evidencia de Entrega</strong></h6></th>'
                        . '         </tr>'
                        . '         <tr>'
                        . '             <td colspan="2">' . $htmlArchivosTexto . '</td>'
                        . '         </tr>'
                        . '     </table>';



                break;
        }

        $html = ''
                . '<div class="divTablas">'
                . ' <h4>Información del Servicio</h4>'
                . ' <div class="underline" style="width:132%"></div>'
                . '     <table class="table table-condensed">'
                . '         <tr>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong># Solicitud</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Solicitante</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Fecha de Solicitud</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Estatus de Solicitud</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_solicitud . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_solicitante . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_fechaSolicitud . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_estatusSolicitud . '</h5></td>'
                . '         </tr>'
                . '         <tr>'
                . '             <th colspan="4">'
                . '                 <h6><strong>Descripción de Solicitud</strong></h6>'
                . '             </th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td colspan="4"><h5>' . $DS_descripcionSolicitud . '</h5></td>'
                . '         </tr>'
                . '         <tr>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong># Ticket</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Tipo de Servicio</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Fecha de Servicio</strong></h6></th>'
                . '             <th style="' . $style['th-25'] . '"><h6><strong>Estatus de Servicio</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_ticket . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_tipoServicio . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_fechaServicio . '</h5></td>'
                . '             <td style="' . $style['td-25'] . '"><h5>' . $DS_estatusServicio . '</h5></td>'
                . '         </tr>'
                . '         <tr>'
                . '             <th colspan="4"><h6><strong>Descripción del Servicio</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td colspan="4"><h5>' . $DS_descripcionServicio . '</h5></td>'
                . '         </tr>'
                . '         <tr>'
                . '             <th colspan="2"><h6><strong>Tiempo de Solicitud</strong></h6></th>'
                . '             <th colspan="2"><h6><strong>Tipo de Servicio</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td colspan="2"><h5>' . $DS_tiempoSolicitud . ' hrs</h5></td>'
                . '             <td colspan="2"><h5>' . $DS_tiempoServicio . ' hrs</h5></td>'
                . '         </tr>'
                . '     </table>'
                . ' <h4>Información General del tráfico</h4>'
                . ' <div class="underline"></div>'
                . '     <table class="table table-condensed">'
                . '         <tr>'
                . '             <th style="' . $style['th-50'] . '"><h6><strong>Tipo de Tráfico</strong></h6></th>'
                . '             <th style="' . $style['th-50'] . '"><h6><strong>Encargado de Ruta</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td style="' . $style['td-50'] . '"><h5>' . $tipo . '</h5></td>'
                . '             <td style="' . $style['td-50'] . '"><h5>' . $encargado . '</h5></td>'
                . '         </tr>'
                . '         <tr>'
                . '             <th style="' . $style['th-50'] . '"><h6><strong>Origen</strong></h6></th>'
                . '             <th style="' . $style['th-50'] . '"><h6><strong>Destino</strong></h6></th>'
                . '         </tr>'
                . '         <tr>'
                . '             <td style="' . $style['td-50'] . '"><h5>' . $origen . '</h5></td>'
                . '             <td style="' . $style['td-50'] . '"><h5>' . $destino . '</h5></td>'
                . '         </tr>'
                . '     </table>'
                . ' <h4>Documentación del servicio.</h4>'
                . ' <div class="underline"></div>'
                . $htmlDocumentacion
                . '</div>';

//        if ($optionEquipos !== '') {
        $html .= ''
                . '<div style="page-break-before: always;"></div>'
                . '<div class="divTablas">'
                . ' <h4>Detalle de Items</h4>'
                . ' <div class="underline"></div>'
                . '     <table id="data-table-detalle-items" class="table table-hover table-striped table-bordered no-wrap ">'
                . '         <thead>'
                . '             <tr>'
                . '                 <th class="' . $style['th'] . '">Item</th>'
                . '                 <th class="' . $style['th'] . '">Serie</th>'
                . '                 <th class="' . $style['th'] . '">Cantidad</th>'
                . '             </tr>'
                . '         </thead>'
                . '         <tbody>'
                . '         ' . $optionEquipos
                . '         </tbody>'
                . '     </table>'
                . '</div>';
//        }
        $html .= $htmlArchivosE . $htmlArchivos;

        return $html;
    }

    public function getSrcByPath(array $pathInfo, string $url) {
        if (array_key_exists("extension", $pathInfo)) {
            switch (strtolower($pathInfo['extension'])) {
                case 'doc': case 'docx':
                    $src = '/assets/img/Iconos/word_icon.png';
                    break;
                case 'xls': case 'xlsx':
                    $src = '/assets/img/Iconos/excel_icon.png';
                    break;
                case 'pdf':
                    $src = '/assets/img/Iconos/pdf_icon.png';
                    break;
                case 'jpg': case 'jpeg': case 'bmp': case 'gif': case 'png':
                    $src = $url;
                    break;
                default :
                    $src = '/assets/img/Iconos/file_icon.png';
                    break;
            }
        } else {
            $src = '/assets/img/Iconos/file_icon.png';
        }

        return $src;
    }

    public function getServicioToPdf(array $servicio) {
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'];
        $datosServicio = $this->DBS->consultaGeneral('SELECT
                                                sucursal(IdSucursal) Sucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $servicio['servicio'] . '"');
        $link = $this->Phantom->htmlToPdf($archivo, $ruta, $datosServicio[0]);

        return ['link' => $link];
    }

    public function getTipoByServicio(string $servicio) {
        return $this->DBS->consultaGeneral("select IdTipoServicio from t_servicios_ticket where Id = '" . $servicio . "';");
    }

    public function getInformacionServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "tst.Ticket, "
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
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getDetallesUber(string $servicio, bool $esPdf = false) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->getGeneralesUber($servicio);
        $notas = $this->Notas->getNotasByServicio($servicio);

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales,
            'notas' => $notas
        ];

        if (!$esPdf) {
            return parent::getCI()->load->view('MesaDeAyuda/Detalles/Uber', $data, TRUE);
        } else {
            return parent::getCI()->load->view('MesaDeAyuda/Detalles/UberPdf', $data, TRUE);
        }
    }

    public function getGeneralesUber(string $servicio) {
        $generales = $this->DBMAS->getGeneralesUber($servicio);
        $returnArray = [
            'ticket' => 'Sin Información',
            'personas' => 'Sin Información',
            'fecha' => 'Sin Información',
            'origen' => 'Sin Información',
            'destino' => 'Sin Información',
            'motivo' => 'Sin Información',
        ];
        if (array_key_exists(0, $generales)) {
            $returnArray['ticket'] = ($generales[0]['Ticket'] !== '') ? $generales[0]['Ticket'] : 'Sin Información';
            $returnArray['personas'] = ($generales[0]['Personas'] !== '') ? $generales[0]['Personas'] : 'Sin Información';
            $returnArray['fecha'] = ($generales[0]['Fecha'] !== '') ? $generales[0]['Fecha'] : 'Sin Información';
            $returnArray['origen'] = ($generales[0]['Origen'] !== '') ? $generales[0]['Origen'] : 'Sin Información';
            $returnArray['destino'] = ($generales[0]['Destino'] !== '') ? $generales[0]['Destino'] : 'Sin Información';
            $returnArray['motivo'] = ($generales[0]['Proyecto'] !== '') ? $generales[0]['Proyecto'] : 'Sin Información';
        }
        return $returnArray;
    }

    public function getGeneralesSolicitudServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "tst.Ticket, "
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
        $detallesSolicitud = $this->DBS->consultaGeneral($sentencia);

        $arrayReturn = array();
        if (array_key_exists(0, $detallesSolicitud)) {
            $arrayReturn['solicitud'] = ($detallesSolicitud[0]['Solicitud'] !== '') ? $detallesSolicitud[0]['Solicitud'] : 'Sin Información';
            $arrayReturn['solicitante'] = ($detallesSolicitud[0]['Solicitante'] !== '') ? $detallesSolicitud[0]['Solicitante'] : 'Sin Información';
            $arrayReturn['fechaSolicitud'] = ($detallesSolicitud[0]['FechaSolicitud'] !== '') ? $detallesSolicitud[0]['FechaSolicitud'] : 'Sin Información';
            $arrayReturn['estatusSolicitud'] = ($detallesSolicitud[0]['EstatusSolicitud'] !== '') ? $detallesSolicitud[0]['EstatusSolicitud'] : 'Sin Información';
            $arrayReturn['descripcionSolicitud'] = ($detallesSolicitud[0]['DescripcionSolicitud'] !== '') ? $detallesSolicitud[0]['DescripcionSolicitud'] : 'Sin Información';
            $arrayReturn['ticket'] = ($detallesSolicitud[0]['Ticket'] !== '') ? $detallesSolicitud[0]['Ticket'] : 'Sin Información';
            $arrayReturn['tipoServicio'] = ($detallesSolicitud[0]['TipoServicio'] !== '') ? $detallesSolicitud[0]['TipoServicio'] : 'Sin Información';
            $arrayReturn['fechaServicio'] = ($detallesSolicitud[0]['FechaServicio'] !== '') ? $detallesSolicitud[0]['FechaServicio'] : 'Sin Información';
            $arrayReturn['estatusServicio'] = ($detallesSolicitud[0]['EstatusServicio'] !== '') ? $detallesSolicitud[0]['EstatusServicio'] : 'Sin Información';
            $arrayReturn['descripcionServicio'] = ($detallesSolicitud[0]['DescripcionServicio'] !== '') ? $detallesSolicitud[0]['DescripcionServicio'] : 'Sin Información';
            $arrayReturn['tiempoSolicitud'] = ($detallesSolicitud[0]['TiempoSolicitud'] !== '') ? $detallesSolicitud[0]['TiempoSolicitud'] : 'Sin Información';
            $arrayReturn['tiempoServicio'] = ($detallesSolicitud[0]['TiempoServicio'] !== '') ? $detallesSolicitud[0]['TiempoServicio'] : 'Sin Información';
        }
        return $arrayReturn;
    }

    public function getDetallesSinClasificar(string $servicio, bool $esPdf = false) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->getGeneralesSinClasificar($servicio, $esPdf);
        $notas = $this->Notas->getNotasByServicio($servicio);

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales,
            'notas' => $notas
        ];

        if (!$esPdf) {
            return parent::getCI()->load->view('Generales/Detalles/sinClasificar', $data, TRUE);
        } else {
            return parent::getCI()->load->view('Generales/Detalles/sinClasificarPdf', $data, TRUE);
        }
    }

    public function getGeneralesSinClasificar(string $servicio, bool $esPdf = false) {
        $generales = $this->DBS->getGeneralesSinClasificar($servicio);
        $returnArray = [
            'descripcion' => 'Sin Información',
            'archivos' => '',
            'fecha' => 'Sin Información'
        ];
        if (array_key_exists(0, $generales)) {
            $returnArray['descripcion'] = ($generales[0]['Descripcion'] !== '') ? $generales[0]['Descripcion'] : 'Sin Información';
            if ($esPdf) {
                $returnArray['archivos'] = ($generales[0]['Archivos'] !== '') ? $this->getHtmlArchivosPdf($generales[0]['Archivos'], $servicio) : '';
            } else {
                $returnArray['archivos'] = ($generales[0]['Archivos'] !== '') ? $this->getHtmlArchivosWeb($generales[0]['Archivos'], $servicio) : '';
            }
            $returnArray['fecha'] = ($generales[0]['Fecha'] !== '') ? $generales[0]['Fecha'] : 'Sin Información';
        }
        return $returnArray;
    }

    public function getHtmlArchivosWeb(string $archivos = '', string $servicio) {
        $archivos = explode(",", $archivos);
        $htmlArchivos = '';
        foreach ($archivos as $k => $v) {
            $pathInfo = pathinfo($v);
            $src = $this->getSrcByPath($pathInfo, $v);
            $htmlArchivos .= ''
                    . '<div class="evidencia">'
                    . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-entrega-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                    . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                    . '     <p class="f-s-9 m-t-0">' . $pathInfo['basename'] . '</p>'
                    . ' </a>'
                    . '</div>';
        }
        return $htmlArchivos;
    }

    public function getHtmlArchivosPdf(string $archivos = '', string $servicio) {
        $archivos = explode(",", $archivos);
        $cont = 0;
        $arrayReturn = [
            'htmlArchivosTexto' => '',
            'htmlArchivos' => ''
        ];
        foreach ($archivos as $k => $v) {
            $cont ++;
            $pathInfo = pathinfo($v);
            $src = $this->getSrcByPath($pathInfo, $v);
            $arrayReturn['htmlArchivosTexto'] .= ''
                    . '<a style="font-size:0px; display:block" '
                    . ' href="http://' . $_SERVER['HTTP_HOST'] . $v . '">'
                    . ' <h5 style="font-size:15px !important;" >Archivo Entrega ' . $cont . ': ' . $pathInfo['basename'] . '</h5>" '
                    . '</a>';
            $arrayReturn['htmlArchivos'] .= ''
                    . '<div style="page-break-before: always;"></div>'
                    . ' <div class="text-center">'
                    . ' <h4>Archivo Entrega ' . $cont . ': ' . $pathInfo['basename'] . '</h4>'
                    . ' <img class="img-rounded img-thumbnail" src="http://' . $_SERVER['HTTP_HOST'] . $src . '" style="max-height:800px !important;" alt="' . $pathInfo['basename'] . '"  />'
                    . '</div>';
        }

        return $arrayReturn;
    }

}
