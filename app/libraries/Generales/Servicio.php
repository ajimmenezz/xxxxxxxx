<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Solicitud
 *
 * @author Alonso
 */
class Servicio extends General {

    private $Notificacion;
    private $Servicio;
    private $DBMAS;
    private $DBS;
    private $DBB;
    private $DBP;
    private $DBT;
    private $Notas;
    private $Phantom;
    private $TicketOld;
    private $Correo;
    private $Catalogo;
    private $ServiceDesk;
    private $SeguimientoPoliza;
    private $InformacionServicios;
    private $MSP;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBMAS = \Modelos\Modelo_MesaDeAyuda_Seguimiento::factory();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->DBT = \Modelos\Modelo_Tesoreria::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->Notas = \Librerias\Generales\Notas::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->TicketOld = \Librerias\Generales\TicketsOld::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->SeguimientoPoliza = \Librerias\Poliza\Seguimientos::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();

        parent::getCI()->load->helper(array('FileUpload', 'date', 'conversionpalabra'));
    }

    public function getDetallesByServicio(array $datos) {
        $tipo = $this->getTipoByServicio($datos['servicio']);
        $html = '';
        if ($tipo[0]['IdTipoServicio'] === '5') {
            $html = $this->getDetallesTrafico($datos['servicio'], FALSE, $datos['solicitud']);
        } else if ($tipo[0]['IdTipoServicio'] === '10') {
            $html = $this->getDetallesUber($datos['servicio'], FALSE, $datos['solicitud']);
        } else {
            $html = $this->getDetallesSinClasificar($datos['servicio'], FALSE, $datos['solicitud']);
        }

        $ids = $this->DBS->consultaGeneral('SELECT IdEstatus, IdSolicitud, Atiende FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');

        return ['html' => $html, 'ids' => $ids[0]];
    }

    public function getDetallesTrafico(string $servicio) {
        $detallesSolicitud = $this->getInformacionServicio($servicio);
        $detalles = $this->getGeneralesTrafico($servicio);
        $optionEquipos = $this->getHtmlEquiposTrafico($servicio);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $notas = $this->Notas->getNotasByServicio($servicio, $idSolicitud[0]['IdSolicitud']);

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
        $htmlDocumentacion = '';

        switch ($detalles[0]['IdTipo']) {
            case 1:
                $documentacion = $this->getDocumentacionEnvio($servicio);
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
            case 2:
                $documentacion = $this->getDocumentacionRecoleccionTrafico($servicio);
                $htmlArchivos = '';
                $fecha = $entrega = $comentariosRecoleccion = 'Sin Información';
                if (array_key_exists(0, $documentacion)) {
                    $fecha = ($documentacion[0]['Fecha'] !== '') ? strftime('%A %e de %B, %G ', strtotime($documentacion[0]['Fecha'])) . date("h:ma", strtotime($documentacion[0]['Fecha'])) : 'Sin información';
                    $entrega = ($documentacion[0]['Entrega'] !== '') ? $documentacion[0]['Entrega'] : 'Sin Información';
                    $comentariosRecoleccion = ($documentacion[0]['ComentariosRecoleccion'] !== '') ? $documentacion[0]['ComentariosRecoleccion'] : 'Sin Información';

                    if ($documentacion[0]['Recoleccion'] !== '' && $documentacion[0]['Recoleccion'] !== NULL) {
                        $htmlArchivos .= '';
                        $archivos = explode(",", $documentacion[0]['Recoleccion']);
                        foreach ($archivos as $k => $v) {
                            $pathInfo = pathinfo($v);
                            $src = $this->getSrcByPath($pathInfo, $v);
                            $htmlArchivos .= ''
                                    . '<div class="evidencia">'
                                    . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-envio-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                                    . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                    . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                    . ' </a>'
                                    . '</div>';
                        }
                    } else {
                        $htmlArchivos .= ''
                                . '<h5>Sin Información</h5>';
                    }
                }
                $htmlDocumentacion .= ''
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>Fecha y Hora de Entrega</h6>'
                        . '     <h5>' . $fecha . '</h5>'
                        . ' </div>'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>¿Quién Entrega?</h6>'
                        . '     <h5>' . $entrega . '</h5>'
                        . ' </div>'
                        . '</div>'
                        . '<div class="row">'
                        . ' <div class="col-md-6 col-xs-12">'
                        . '     <h6>Comentarios de Entrega</h6>'
                        . '     <h5>' . $comentariosRecoleccion . '</h5>'
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
                . '     <div class="col-md-12 col-xs-12">'
                . '         <ul class="nav nav-pills">'
                . '             <li class="active"><a href="#nav-pills-resumen" data-toggle="tab" aria-expanded="false">Resumen</a></li>'
                . '             <li class=""><a href="#nav-pills-notas" data-toggle="tab" aria-expanded="true">Conversación del Servicio</a></li>'
                . '         </ul>'
                . '         <div class="tab-content">'
                . '             <div class="tab-pane fade active in" id="nav-pills-resumen">'
                . '                 <div class="row">'
                . '                     <div class="col-md-9 col-xs-9">'
                . '                         <h3>Información General del tráfico</h3>'
                . '                     </div>'
                . '                     <div class="col-md-3 col-xs-3">'
                . '                         <div class="form-group text-right">'
                . '                             <label for="detallesInformacionServicio"><strong id="detallesInformacionServicio"><h4><a>+ Información del Servicio</a></h4></strong></label>'
                . '                         </div>'
                . '                     </div>'
                . '                 </div>'
                . '                 <div class="underline"></div>'
                . '                 <div class="row">'
                . '                     <div class="col-md-6 col-xs-12">'
                . '                         <h5 class="f-w-700">Tipo de Tráfico</h5>'
                . '                         <h4>' . $tipo . '</h4>'
                . '                     </div>'
                . '                     <div class="col-md-6 col-xs-12">'
                . '                         <h5 class="f-w-700">Encargado de Ruta</h5>'
                . '                         <h4>' . $encargado . '</h4>'
                . '                     </div>'
                . '                 </div>'
                . '                 <div class="row">'
                . '                     <div class="col-md-6 col-xs-12">'
                . '                         <h5 class="f-w-700">Origen</h5>'
                . '                         <h4>' . $origen . '</h4>'
                . '                     </div>'
                . '                     <div class="col-md-6 col-xs-12">'
                . '                         <h5 class="f-w-700">Destino</h5>'
                . '                         <h4>' . $destino . '</h4>'
                . '                     </div>'
                . '                 </div>'
                . '                 <div id="detallesServicio" class="hidden">'
                . '                     <div class="row">'
                . '                         <div class="col-md-12 col-xs-12">'
                . '                             <h3>Información del Servicio</h3>'
                . '                         </div>'
                . '                     </div>'
                . '                     <div class="underline"></div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Número de Solicitud</h5>
                                                <h4>' . $DS_solicitud . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Solicitante</h5>
                                                <h4>' . $DS_solicitante . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Fecha de Solicitud</h5>
                                                <h4>' . $DS_fechaSolicitud . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Estatus de Solicitud</h5>
                                                <h4>' . $DS_estatusSolicitud . '</h4>
                                            </div>
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-12 col-xs-12">                        
                                                <div class="underline"></div>                    
                                            </div>
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-12 col-xs-12">
                                                <h5 class="f-w-700">Descripción de la Solicitud</h5>
                                                <h4>' . $DS_descripcionSolicitud . '</h4>
                                                <div class="underline"></div>
                                            </div>       
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Número de Ticket</h5>
                                                <h4>' . $DS_ticket . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Tipo de Servicio</h5>
                                                <h4>' . $DS_tipoServicio . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Fecha de Servicio</h5>
                                                <h4>' . $DS_fechaServicio . '</h4>
                                            </div>                    
                                            <div class="col-md-3 col-xs-12">
                                                <h5 class="f-w-700">Estatus de Servicio</h5>
                                                <h4>' . $DS_estatusServicio . '</h4>
                                            </div>                          
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-12 col-xs-12">                        
                                                <div class="underline"></div>                    
                                            </div>
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-12 col-xs-12">
                                                <h5 class="f-w-700">Descripción del Servicio</h5>
                                                <h4>' . $DS_descripcionServicio . '</h4>
                                                <div class="underline"></div>
                                            </div>                   
                                        </div>'
                . '                     <div class="row m-t-10">
                                            <div class="col-md-6 col-xs-12">
                                                <h5 class="f-w-700">Tiempo de la Solicitud</h5>
                                                <h4>' . $DS_tiempoSolicitud . '</h4>
                                            </div>                   
                                            <div class="col-md-6 col-xs-12">
                                                <h5 class="f-w-700">Tiempo del Servicio</h5>
                                                <h4>' . $DS_tiempoServicio . '</h4>
                                            </div>                   
                                        </div>'
                . '                 </div>'
                . '                 <div class="row m-t-10">'
                . '                     <div class="col-md-12 col-xs-12">'
                . '                         <h3>Detalle de Items</h3>'
                . '                         <div class="underline"></div>'
                . '                     </div>'
                . '                 </div>'
                . '                 <div class="row m-t-10">'
                . '                     <div class="col-md-12 col-xs-12">'
                . '                         <table id="data-table-detalle-items" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">'
                . '                             <thead>'
                . '                                 <tr>'
                . '                                     <th class="all">Item</th>'
                . '                                     <th class="all">Serie</th>'
                . '                                     <th class="all">Cantidad</th>'
                . '                                 </tr>'
                . '                             </thead>'
                . '                             <tbody>'
                . '                                 ' . $optionEquipos
                . '                             </tbody>'
                . '                         </table>'
                . '                     </div>'
                . '                 </div>'
                . '                 <div class="row">'
                . '                     <div class="col-md-12 col-xs-12">'
                . '                         <h3>Documentación del servicio.</h3>'
                . '                         <div class="underline"></div>'
                . '                     </div>'
                . '                 </div>'
                . '                 ' . $htmlDocumentacion
                . '             </div>'
                . '             <div class="tab-pane fade" id="nav-pills-notas">'
                . '                 ' . $notas
                . '             </div>'
                . '         </div>'
                . '     </div>'
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

    public function getDocumentacionEnvio(string $servicio) {
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

    public function getDocumentacionRecoleccionTrafico(string $servicio) {
        $sentencia = ""
                . "SELECT "
                . "tte.Fecha, "
                . "tte.ComentariosRecoleccion, "
                . "tte.UrlRecoleccion as Recoleccion, "
                . "tte.NombreEntrega as Entrega "
                . "from t_traficos_recolecciones tte  "
                . "where IdServicio = '" . $servicio . "';";
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getTraficoHtmlToPdf(string $servicio) {
        $detallesSolicitud = $this->getInformacionServicio($servicio);
        $detalles = $this->getGeneralesTrafico($servicio);
        $optionEquipos = $this->getHtmlEquiposTrafico($servicio);
        $notasPdf = $this->getNotasByServicio($servicio);

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

        $documentacion = $this->getDocumentacionEnvio($servicio);
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
        $html .= $htmlArchivosE . $htmlArchivos;
        if (count($notasPdf) > 0) {
            $html .= '<div style="page-break-after:always;">'
                    . '<div class="row">'
                    . '    <div class="col-md-12">'
                    . '        <fieldset>'
                    . '            <legend class="pull-left width-full f-s-17">Conversación del Servicio.</legend>'
                    . '        </fieldset>'
                    . '    </div>'
                    . '</div>';
            foreach ($notasPdf as $key => $value) {
                $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
                $html .= '<div class="row m-t-25">'
                        . '         <div class="col-md-6 col-sm-6 col-xs-12">'
                        . '             <p class="f-w-600 pull-left">' . $value['Nombre'] . '</p>'
                        . '         </div>'
                        . '         <div class="col-md-6 col-sm-6 col-xs-12">'
                        . '             <p class="f-w-600 pull-right"><?php echo $fecha; ?></p>'
                        . '         </div>'
                        . '      </div>';
                if ($value['Nota'] != '') {
                    $html .= '<pre>' . $value['Nota'] . '</pre>';
                }
                if ($value['Archivos'] != '') {
                    $archivos = explode(",", $value['Archivos']);
                    foreach ($archivos as $k => $v) {
                        $html .= '        <div style="display:inline-block; max-width: 180px; max-height: 250px; font-size:10px;" >'
                                . '             <a href="<?php echo $v; ?>" target="_blank" >'
                                . '                 <img class="img-thumbnail img-responsive" src="' . $v . '" />'
                                . '             </a>'
                                . '         </div>';
                    }
                }
            }
            $html .= ' </div>';
        }

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

    public function getServicioToPdf(array $servicio, string $nombreExtra = NULL) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'] . '/' . $nombreExtra;

        $datosServicio = $this->DBS->getServicios('SELECT
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
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getDetallesUber(string $servicio, bool $esPdf = false) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->getGeneralesUber($servicio);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $notas = $this->Notas->getNotasByServicio($servicio, $idSolicitud[0]['IdSolicitud']);
        $notasPdf = $this->getNotasByServicio($servicio);

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales,
            'notas' => $notas,
            'notasPdf' => $notasPdf
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
                . "tst.FirmaTecnico, "
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
        $detallesSolicitud = $this->DBS->consultaGeneral($sentencia);

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
            $arrayReturn['firmaTecnico'] = ($detallesSolicitud[0]['FirmaTecnico'] !== NULL) ? $detallesSolicitud[0]['FirmaTecnico'] : 'Sin Información';
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

    public function getGeneralesSolicitudFolio(string $folio) {
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
                . "tst.FirmaTecnico, "
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
                . "where ts.Folio = '" . $folio . "';";
        $detallesSolicitud = $this->DBS->consultaGeneral($sentencia);

        return $detallesSolicitud;
    }

    public function getDetallesSinClasificar(string $servicio, bool $esPdf = false, string $idSolicitud = null, $_tipoServicio = '') {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->getGeneralesSinClasificar($servicio, $esPdf);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $notas = $this->Notas->getNotasByServicio($servicio, $idSolicitud[0]['IdSolicitud']);
        $notasPdf = $this->getNotasByServicio($servicio);
        $listaNotas = $this->Notas->setNotaServicioSolicitud($servicio, $idSolicitud[0]['IdSolicitud']);
        $avanceServicio = $this->Servicio->consultaAvanceServicio($servicio);
        $sumaTipoDiagnostico = $this->getSumaTipoDiagnostico($servicio);

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales,
            'notas' => $notas,
            'notasPdf' => $notasPdf,
            'listaNotas' => $listaNotas,
            'avanceServicio' => $avanceServicio,
            'sumaTipoDiagnostico' => $sumaTipoDiagnostico,
            'tipoServicio' => $_tipoServicio
        ];

        if (!$esPdf) {
            return parent::getCI()->load->view('Generales/Detalles/sinClasificar', $data, TRUE);
        } else {
            return parent::getCI()->load->view('Generales/Detalles/sinClasificarPdf', $data, TRUE);
        }
    }

    public function getSumaTipoDiagnostico(string $servicio) {
        $consulta = $this->DBS->consultaGeneral('SELECT 
                                                        SUM(tsae.Cantidad) Cantidad,
                                                        CASE tsae.IdItem 
                                                            WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = tsae.TipoItem) 
                                                            WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = tsae.TipoItem)
                                                            WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tsae.TipoItem) 
                                                        END as EquipoMaterial,
                                                        CASE tsae.IdItem 
                                                            WHEN 1 THEN "Equipo"
                                                            WHEN 2 THEN "Material"
                                                            WHEN 3 THEN "Refacción"
                                                        END as Tipo,
                                                        tsae.IdItem,
                                                          (SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = tsae.IdTipoDiagnostico) TipoDiagnostico
                                                  FROM t_servicios_avance_equipo tsae
                                                  INNER JOIN t_servicios_avance tsa
                                                  ON tsa.Id = tsae.IdAvance
                                                  WHERE tsa.IdTipo = 2
                                                  AND tsa.IdServicio = "' . $servicio . '"
                                                  GROUP BY tsae.TipoItem, tsae.IdItem, tsae.IdTipoDiagnostico
                                                  ORDER BY Tipo, EquipoMaterial ASC');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
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

    public function getDetallesCenso(string $servicio) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $sucursal = $this->DBS->getServicios('SELECT sucursal(IdSucursal) AS Sucursal FROM t_censos_generales WHERE IdServicio ="' . $servicio . '"');
        $equiposCensados = $this->getEquiposCensados($servicio);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $notasPdf = $this->getNotasByServicio($servicio);
        $documentacionFirmada = $this->Servicio->consultaDocumentacioFirmadaServicio($servicio, '1');

        $data = [
            'solicitud' => $generalesSolicitud,
            'sucursal' => $sucursal[0]['Sucursal'],
            'equiposCensados' => $equiposCensados,
            'documentacionFirmada' => $documentacionFirmada,
            'notasPdf' => $notasPdf
        ];

        return parent::getCI()->load->view('Poliza/Detalles/censoPdf', $data, TRUE);
    }

    public function getEquiposCensados(string $servicio) {
        $generales = $this->DBS->getServicios('SELECT 
                                                areaAtencion(tc.IdArea) AS Area,
                                                tc.Punto,
                                                (SELECT Equipo FROM v_equipos WHERE Id = tc.IdModelo) AS Equipo, 
                                                tc.Serie,
                                                tc.Extra
                                            FROM t_censos tc 
                                            WHERE tc.IdServicio = "' . $servicio . '"
                                            ORDER BY Area, Punto ASC');
        $totalAreas = $this->DBS->getServicios('select 
                                                    areaAtencion(IdArea) as Area,
                                                    count(*) as Total
                                                from t_censos  
                                                WHERE IdServicio = "' . $servicio . '"
                                                group by Area order by Area');
        $totalLineas = $this->DBS->getServicios('select
                                                    cap_first(strSplit(modelo(IdModelo)," - ",1)) as Linea,
                                                    count(*) as Total
                                                from t_censos  
                                                WHERE IdServicio = "' . $servicio . '" 
                                                group by Linea');
        $returnArray = [
            'equiposCensados' => 'Sin Información',
            'totalAreas' => 'Sin Información',
            'totalLineas' => 'Sin Información'
        ];

        if (!empty($generales)) {
            $returnArray['equiposCensados'] = ($generales !== '') ? $generales : 'Sin Información';
        }
        if (!empty($totalAreas)) {
            $returnArray['totalAreas'] = ($totalAreas !== '') ? $totalAreas : 'Sin Información';
        }
        if (!empty($totalLineas)) {
            $returnArray['totalLineas'] = ($totalLineas !== '') ? $totalLineas : 'Sin Información';
        }
        return $returnArray;
    }

    public function getDetallesMantenimientoPoliza(string $servicio, string $restringirDatos = NULL) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $sucursal = $this->DBS->getServicios('SELECT sucursal(IdSucursal) AS Sucursal FROM t_mantenimientos_generales WHERE IdServicio ="' . $servicio . '"');
        $generalesMantenimiento = $this->getGeneralesMantenimiento($servicio, $restringirDatos);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $notasPdf = $this->getNotasByServicio($servicio);
        $documentacionFirmada = $this->Servicio->consultaDocumentacioFirmadaServicio($servicio, '1');

        $data = [
            'solicitud' => $generalesSolicitud,
            'sucursal' => $sucursal[0]['Sucursal'],
            'generalesMantenimiento' => $generalesMantenimiento,
            'documentacionFirmada' => $documentacionFirmada,
            'notasPdf' => $notasPdf
        ];

        return parent::getCI()->load->view('Poliza/Detalles/mantenimientoPdf', $data, TRUE);
    }

    public function getGeneralesMantenimiento(string $servicio, string $restringirDatos = NULL) {
        $antesDespues = $this->DBS->getServicios('SELECT *,
                                                areaAtencion(IdArea) AS Area
                                            FROM t_mantenimientos_antes_despues 
                                            WHERE IdServicio = "' . $servicio . '"
                                            ORDER BY Area, Punto ASC');
        $problemasEquipo = $this->DBS->getServicios('SELECT 
                                                        *,
                                                        areaAtencion(IdArea) AS Area,
                                                        (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) AS Equipo
                                                    FROM t_mantenimientos_problemas_equipo 
                                                    WHERE IdServicio = "' . $servicio . '"
                                                    ORDER BY Area, Punto, Equipo, Serie ASC');
        $equiposFaltante = $this->DBS->getServicios('SELECT 
                                                        *,
                                                        areaAtencion(IdArea) AS Area,
                                                        CASE tmef.TipoItem
                                                                WHEN 1 THEN "Equipo"
                                                                WHEN 2 THEN "Material"
                                                                WHEN 3 THEN "Refacción"
                                                            END as NombreItem, 
                                                            CASE tmef.TipoItem
                                                                WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = tmef.IdModelo)
                                                                WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = tmef.IdModelo)
                                                                WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tmef.IdModelo)
                                                            END as Equipo
                                                    FROM t_mantenimientos_equipo_faltante tmef  
                                                    WHERE tmef.IdServicio = "' . $servicio . '"
                                                    ORDER BY Area, tmef.Punto ASC');
        $problemasAdicionales = $this->DBS->getServicios('SELECT 
                                                        *,
                                                        areaAtencion(IdArea) AS Area
                                                    FROM t_mantenimientos_problemas_adicionales
                                                    WHERE IdServicio = "' . $servicio . '"
                                                    ORDER BY Area, Punto ASC');
        $returnArray = [
            'antesDespues' => 'Sin Información',
            'problemasEquipo' => 'Sin Información',
            'equiposFaltante' => 'Sin Información',
            'problemasAdicionales' => 'Sin Información'
        ];

        if (!empty($antesDespues)) {
            if (is_null($restringirDatos)) {
                $returnArray['antesDespues'] = ($antesDespues !== '') ? $antesDespues : 'Sin Información';
            } else {
                $returnArray['antesDespues'] = 'Sin Información';
            }
        }
        if (!empty($problemasEquipo)) {
            if (is_null($restringirDatos)) {
                $returnArray['problemasEquipo'] = ($problemasEquipo !== '') ? $problemasEquipo : 'Sin Información';
            } else {
                if ($restringirDatos !== 'ProblemasEquipo') {
                    $returnArray['problemasEquipo'] = 'Sin Información';
                } else {
                    $returnArray['problemasEquipo'] = ($problemasEquipo !== '') ? $problemasEquipo : 'Sin Información';
                }
            }
        }
        if (!empty($equiposFaltante)) {
            if (is_null($restringirDatos)) {
                $returnArray['equiposFaltante'] = ($equiposFaltante !== '') ? $equiposFaltante : 'Sin Información';
            } else {
                if ($restringirDatos !== 'EquipoFaltante') {
                    $returnArray['equiposFaltante'] = 'Sin Información';
                } else {
                    $returnArray['equiposFaltante'] = ($equiposFaltante !== '') ? $equiposFaltante : 'Sin Información';
                }
            }
        }
        if (!empty($problemasAdicionales)) {
            if (is_null($restringirDatos)) {
                $returnArray['problemasAdicionales'] = ($problemasAdicionales !== '') ? $problemasAdicionales : 'Sin Información';
            } else {
                if ($restringirDatos !== 'OtrosProblemas') {
                    $returnArray['problemasAdicionales'] = 'Sin Información';
                } else {
                    $returnArray['problemasAdicionales'] = ($problemasAdicionales !== '') ? $problemasAdicionales : 'Sin Información';
                }
            }
        }

        return $returnArray;
    }

    public function getDetallesCorrectivo(string $servicio) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $sucursal = $this->DBS->getServicios('SELECT sucursal(IdSucursal) AS Sucursal FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $generales = $this->InformacionServicios->consultaInformacionCorrectivo($servicio);
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        if (empty($generales)) {
            $generales[0] = 'Sin Información';
        }

        $correctivosDiagnostico = $this->InformacionServicios->consultaCorrectivosDiagnostico($servicio);

        if (empty($correctivosDiagnostico)) {
            $correctivosDiagnostico[0] = 'Sin Información';
        } else {
            $correctivosDiagnostico[0]['Evidencias'] = ($correctivosDiagnostico[0]['Evidencias'] !== '') ? $this->getHtmlArchivosPdf($correctivosDiagnostico[0]['Evidencias'], $servicio) : '';
        }

        $tipoProblema = $this->DBS->consultaGeneral('SELECT Id, IdTipoProblema FROM t_correctivos_problemas WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');
        $notas = $this->Notas->getNotasByServicio($servicio, $idSolicitud[0]['IdSolicitud']);
        $notasPdf = $this->getNotasByServicio($servicio);
        $returnArrayProblemasServicio = [
            'solicitudesRefaccionServicio' => 'Sin Información',
            'solicitudesEquipoServicio' => 'Sin Información',
            'garantiaRespaldo' => 'Sin Información',
            'informacionGarantiaRespaldo' => 'Sin Información'
        ];
        $returnArrayEnvioEntrega = [
            'envioEquipo' => 'Sin Información',
            'entregaEquipo' => 'Sin Información',
            'tituloEntregaEnvio' => ''
        ];
        $returnArraySolicion = [
            'correctivosSolucionSinEquipo' => 'Sin Información',
            'correctivosSolucionRefaccion' => 'Sin Información',
            'correctivosSolucionCambio' => 'Sin Información',
            'tituloSolucion' => 'Sin Información'
        ];
        $tituloProblemasServicio = 'Sin Información';
        if (!empty($tipoProblema)) {
            switch ($tipoProblema[0]['IdTipoProblema']) {
                case '1':
                    $tituloProblemasServicio = 'Solicitud de Refacción';
                    $returnArrayProblemasServicio['solicitudesRefaccionServicio'] = $this->DBS->consultaGeneral('select 
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
                    $tituloProblemasServicio = 'Solicitud de Equipo';
                    $returnArrayProblemasServicio['solicitudesEquipoServicio'] = $this->DBS->consultaGeneral('select 
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
                    $tituloProblemasServicio = 'Equipo a Garantía';
                    $returnArrayProblemasServicio['garantiaRespaldo'] = $this->DBS->consultaGeneral('SELECT * FROM t_correctivos_garantia_respaldo WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');
                    $returnArrayEquipoGarantia = [
                        'equiposGarantiaRespaldo' => 'Sin Información',
                        'solicitudEquipoRespaldo' => 'Sin Información',
                        'garantiaRespaldo' => 'Sin Información'
                    ];
                    if ($returnArrayProblemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '1' && $returnArrayProblemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '0') {
                        $returnArrayEquipoGarantia['equiposGarantiaRespaldo'] = $this->DBS->consultaGeneral('SELECT 
                                                                                                            tegr.*,
                                                                                                            (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRetira) NombreEquipoRetira,
                                                                                                            (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRespaldo) NombreEquipoRespaldo
                                                                                                        FROM t_equipos_garantia_respaldo tegr 
                                                                                                        WHERE tegr.IdGarantia = "' . $returnArrayProblemasServicio['garantiaRespaldo'][0]['Id'] . '"');
                    }
                    if ($returnArrayProblemasServicio['garantiaRespaldo'][0]['EsRespaldo'] === '0' && $returnArrayProblemasServicio['garantiaRespaldo'][0]['SolicitaEquipo'] === '1') {
                        $returnArrayEquipoGarantia['solicitudEquipoRespaldo'] = $this->DBS->consultaGeneral('SELECT 
                                                                                                            nombreUsuario(tst.Atiende) Atiende,
                                                                                                            tst.FechaCreacion
                                                                                                        FROM t_servicios_relaciones tsr
                                                                                                        INNER JOIN t_servicios_ticket tst
                                                                                                            ON tsr.IdServicioNuevo = tst.Id
                                                                                                        WHERE tsr.IdServicioOrigen = "' . $servicio . '" 
                                                                                                        AND tst.IdTipoServicio = 21
                                                                                                        ORDER BY tsr.Id DESC LIMIT 1');
                    }
                    $returnArrayProblemasServicio['informacionGarantiaRespaldo'] = $returnArrayEquipoGarantia;
                    break;
            }

            $envioEntrega = $this->DBS->consultaGeneral('SELECT Id, Tipo FROM (
                                                            SELECT Id, IdProblemaCorrectivo, FechaCapturaEnvio AS Fecha, "Envio" AS Tipo FROM t_correctivos_envios_equipo WHERE IdProblemaCorrectivo = "' . $tipoProblema[0]['Id'] . '"
                                                            UNION
                                                            SELECT Id, IdProblemaCorrectivo, Fecha, "Entrega" AS Tipo FROM t_correctivos_entregas_equipo WHERE IdProblemaCorrectivo = "' . $tipoProblema[0]['Id'] . '") AS TablaEnvioEntrega 
                                                            ORDER BY Fecha DESC LIMIT 1');
            if (!empty($envioEntrega)) {
                if ($envioEntrega[0]['Tipo'] === 'Envio') {
                    $returnArrayEnvioEntrega['tituloEntregaEnvio'] = 'Envio del Equipo (Foraneo)';
                    $returnArrayEnvioEntrega['envioEquipo'] = $this->DBB->consultaCorrectivoEnviosEquipoProblemas($servicio);
                } else {
                    $returnArrayEnvioEntrega['tituloEntregaEnvio'] = 'Entrega del Equipo (Local - Trigo)';
                    $returnArrayEnvioEntrega['entregaEquipo'] = $this->DBB->consultaCorrectivoEntregasEquipo($servicio);
                }
            } else {
                $envioEntrega[0] = 'Sin Información';
            }
        } else {
            $tipoProblema[0]['IdTipoProblema'] = 'Sin Información';
            $envioEntrega[0] = 'Sin Información';
        }

        $correctivoSoluciones = $this->DBP->consultaCorrectivosSolucionesServicio($servicio);

        if (!empty($correctivoSoluciones)) {
            switch ($correctivoSoluciones[0]['IdTipoSolucion']) {
                case '1':
                    $returnArraySolicion['tituloSolucion'] = 'Reparación sin Equipo';
                    $returnArraySolicion['correctivosSolucionSinEquipo'] = $this->DBS->consultaGeneral('SELECT 
                                                                                                        (SELECT Nombre FROM cat_v3_soluciones_equipo WHERE Id = tcsse.IdSolucionEquipo) Solucion 
                                                                                                    FROM t_correctivos_solucion_sin_equipo tcsse 
                                                                                                    WHERE tcsse.IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');
                    break;
                case '2':
                    $returnArraySolicion['tituloSolucion'] = 'Reparación con Refacción';
                    $returnArraySolicion['correctivosSolucionRefaccion'] = $this->DBS->consultaGeneral('SELECT
                                                                                                        tcsr.Cantidad,
                                                                                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tcsr.IdRefaccion) Refaccion
                                                                                                    FROM t_correctivos_solucion_refaccion tcsr
                                                                                                    WHERE tcsr.IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');
                    break;
                case '3':
                    $returnArraySolicion['tituloSolucion'] = 'Cambio de Equipo';
                    $returnArraySolicion['correctivosSolucionCambio'] = $this->DBS->consultaGeneral('SELECT 
                                                                                                    *,
                                                                                                    (SELECT Equipo FROM v_equipos WHERE Id = IdModelo) Equipo 
                                                                                                FROM t_correctivos_solucion_cambio 
                                                                                                WHERE IdSolucionCorrectivo = "' . $correctivoSoluciones[0]['Id'] . '"');
                    break;
            }
            $correctivoSoluciones[0]['Evidencias'] = ($correctivoSoluciones[0]['Evidencias'] !== '') ? $this->getHtmlArchivosPdf($correctivoSoluciones[0]['Evidencias'], $servicio) : '';
        } else {
            $correctivoSoluciones[0] = 'Sin Información';
        }
        $detallesServicio = $this->linkDetallesServicio($servicio);

        if (!empty($generalesSolicitud['folio'])) {
            if ($generalesSolicitud['folio'] !== '' || $generalesSolicitud['folio'] !== '0') {
                $key = $this->MSP->getApiKeyByUser($generalesSolicitud['atiende']);
                $informacionSD = $this->ServiceDesk->getDetallesFolio($key, $generalesSolicitud['folio']);
                if (isset($informacionSD->SHORTDESCRIPTION)) {
                    $detallesSD = $informacionSD->SHORTDESCRIPTION;
                } else {
                    $detallesSD = 'Sin Información';
                }
            } else {
                $detallesSD = 'Sin Información';
            }
        } else {
            $detallesSD = 'Sin Información';
        }

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales[0],
            'sucursal' => $sucursal[0]['Sucursal'],
            'correctivosDiagnostico' => $correctivosDiagnostico[0],
            'tipoProblema' => $tipoProblema[0]['IdTipoProblema'],
            'tituloProblemasServicio' => $tituloProblemasServicio,
            'returnArrayProblemasServicio' => $returnArrayProblemasServicio,
            'correctivoSoluciones' => $correctivoSoluciones[0],
            'returnArraySolicion' => $returnArraySolicion,
            'envioEntrega' => $envioEntrega[0],
            'returnArrayEnvioEntrega' => $returnArrayEnvioEntrega,
            'notas' => $notas,
            'notasPdf' => $notasPdf,
            'detallesServicio' => $detallesServicio,
            'detallesSD' => $detallesSD
        ];

        return parent::getCI()->load->view('Poliza/Detalles/correctivoPdf', $data, TRUE);
    }

    public function getDetallesImpericiaCorrectivo(string $servicio) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->InformacionServicios->consultaInformacionCorrectivo($servicio);
        $correctivosDiagnostico = $this->DBS->consultaGeneral('SELECT 
                                                                tcd.*,
                                                                (SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = tcd.IdTipoDiagnostico) AS NombreTipoDiagnostico,
                                                                (SELECT Nombre FROM cat_v3_tipos_falla WHERE Id = tcd.IdTipoFalla) AS NombreTipoFalla,
                                                                (SELECT Nombre FROM cat_v3_fallas_equipo WHERE Id = IdFalla) AS NombreFalla
                                                                FROM t_correctivos_diagnostico tcd
                                                                WHERE Id = (SELECT MAX(Id) FROM t_correctivos_diagnostico WHERE IdServicio = "' . $servicio . '" )');
        $detallesServicio = $this->linkDetallesServicio($servicio);
        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales[0],
            'correctivosDiagnostico' => $correctivosDiagnostico[0],
            'detallesServicio' => $detallesServicio
        ];

        return parent::getCI()->load->view('Poliza/Detalles/impericiaCorrectivoPdf', $data, TRUE);
    }

    public function getDetallesRetiroGarantiaRespaldoCorrectivoPdf(string $servicio) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->InformacionServicios->consultaInformacionCorrectivo($servicio);
        $equipos = $this->DBS->consultaGeneral('SELECT 
                                                    (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRetira) EquipoRetira,
                                                    (SELECT Equipo FROM v_equipos WHERE Id = tegr.IdModeloRespaldo) EquipoRespaldo,
                                                    tegr.SerieRetira,
                                                    tegr.SerieRespaldo,
                                                    tegr.NombreFirma,
                                                    tcgr.Fecha
                                                FROM t_correctivos_garantia_respaldo tcgr
                                                INNER JOIN t_equipos_garantia_respaldo tegr
                                                    ON tegr.IdGarantia = tcgr.Id
                                                WHERE tcgr.IdServicio = "' . $servicio . '" ORDER BY tcgr.Id DESC LIMIT 1');

        $detallesServicio = $this->linkDetallesServicio($servicio);

        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales[0],
            'equipos' => $equipos[0],
            'detallesServicio' => $detallesServicio
        ];

        return parent::getCI()->load->view('Poliza/Detalles/retiroGarantiaRespaldoCorrectivoPdf', $data, TRUE);
    }

    public function getDetallesEntregaEquipoPdf(string $servicio) {
        $generalesSolicitud = $this->getGeneralesSolicitudServicio($servicio);
        $generales = $this->InformacionServicios->consultaInformacionCorrectivo($servicio);
        $entregaEquipo = $this->DBS->consultaGeneral('SELECT 
                                                    nombreUsuario(tcee.IdUsuarioRecibe) NombreFirma,
                                                    tcee.CorreoCopia,
                                                    tcee.Firma,
                                                    tcee.Fecha,
                                                    (SELECT Equipo FROM v_equipos WHERE Id = tcg.IdModelo) Equipo,
                                                    tcg.Serie,
                                                    tcee.NombreRecibe
                                                FROM t_correctivos_entregas_equipo tcee
                                                INNER JOIN t_correctivos_problemas tcp
                                                    ON tcp.Id = tcee.IdProblemaCorrectivo
                                                INNER JOIN t_correctivos_generales tcg
                                                    ON tcg.IdServicio = tcp.IdServicio
                                                WHERE tcp.IdServicio = "' . $servicio . '" 
                                                ORDER BY tcee.Fecha DESC LIMIT 1');

        $detallesServicio = $this->linkDetallesServicio($servicio);
        $data = [
            'solicitud' => $generalesSolicitud,
            'generales' => $generales[0],
            'entregaEquipo' => $entregaEquipo[0],
            'detallesServicio' => $detallesServicio
        ];

        return parent::getCI()->load->view('Poliza/Detalles/acuseEntregaEquipoCorrectivoPdf', $data, TRUE);
    }

    public function getHtmlArchivosWeb(string $archivos = '', string $servicio) {
        $archivos = explode(",", $archivos);
        $htmlArchivos = '';
        foreach ($archivos as $k => $v) {
            $pathInfo = pathinfo($v);
            $src = $this->getSrcByPath($pathInfo, $v);
            if (in_array($pathInfo['extension'], ['jpg', 'jpeg', 'bmp', 'gif', 'png'])) {
                $htmlArchivos .= ''
                        . '<div class="evidencia">'
                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-entrega-' . $servicio . '" data-title="' . $pathInfo['basename'] . '">'
                        . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                        . '     <p class="f-s-9 m-t-0">' . $pathInfo['basename'] . '</p>'
                        . ' </a>'
                        . '</div>';
            } else {
                $htmlArchivos .= ''
                        . '<div class="evidencia">'
                        . ' <a class="m-l-5 m-r-5" href="' . $v . '" target="_blank" data-title="' . $pathInfo['basename'] . '">'
                        . '     <img src="' . $src . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                        . '     <p class="f-s-9 m-t-0">' . $pathInfo['basename'] . '</p>'
                        . ' </a>'
                        . '</div>';
            }
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
                    . ' <h6 style="font-size:15px !important;" >Archivo Entrega ' . $cont . ': ' . $pathInfo['basename'] . '</h6>" '
                    . '</a>';
            $arrayReturn['htmlArchivos'] .= ''
                    . ' <div style="display:inline-block; max-width: 180px; max-height: 240px !important;" >'
                    . '     <a href="http://' . $_SERVER['HTTP_HOST'] . $src . '" target="_blank" style="font-size:0px;" >'
                    . '         <img class = "img-thumbnail img-responsive" style="max-height: 240px !important;" src="http://' . $_SERVER['HTTP_HOST'] . $src . '">'
                    . '     </a>'
                    . '</div>';
        }

        return $arrayReturn;
    }

    public function Guarda_SinClasificar(array $datos = null) {
        $usuario = $this->Usuario->getDatosUsuario();
        $resultado = ($this->DBS->Guarda_SinClasificar(array_merge($datos, $usuario)) == 1) ? ['result' => 200] : ['result' => 0];
        return $resultado;
    }

    public function Concluir_SinClasificar(array $datos = null) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $evidenciasAnteriores = '';
        $consulta = $this->DBS->consultaGeneral('SELECT Id, Archivos FROM t_servicios_generales WHERE IdServicio =' . $datos['servicio']);

        $verificarServicioSinClaficar = $this->DBS->consultaGeneral('SELECT 
                                                                        (SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = tst.IdTipoServicio) AS Seguimiento
                                                                    FROM t_servicios_ticket tst WHERE tst.Id = "' . $datos['servicio'] . '"');

        if (!empty($datos['sucursal'])) {
            $this->DBS->actualizarServicio('t_servicios_ticket', array('IdSucursal' => $datos['sucursal']), array('Id' => $datos['servicio']));
        }

        if (!empty($_FILES)) {
            $descripcion = $datos['datosConcluir'];
            if ($descripcion === '[object Object]') {
                $descripcion = $datos['descripcion'];
            }
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasServicioGeneral/';
            $archivos = setMultiplesArchivos($CI, 'evidenciasSinClasificar', $carpeta);
            $archivos = implode(',', $archivos);

            if (!empty($archivos) && $archivos != '') {
                $resultado = '';
                if (!empty($consulta)) {
                    if ($archivos !== NULL) {
                        if ($archivos !== '') {
                            $evidenciasAnteriores = $archivos . ',';
                        }
                    }
                    $resultado = $this->DBS->actualizarServicio('t_servicios_generales', array(
                        'IdUsuario' => $usuario['Id'],
                        'IdServicio' => $datos['servicio'],
                        'Descripcion' => $descripcion,
                        'Archivos' => $evidenciasAnteriores . $consulta[0]['Archivos'],
                        'Fecha' => $fecha
                            ), array('IdServicio' => $datos['servicio'])
                    );
                } else {
                    $resultado = $this->DBS->setNuevoElemento('t_servicios_generales', array(
                        'IdUsuario' => $usuario['Id'],
                        'IdServicio' => $datos['servicio'],
                        'Descripcion' => $descripcion,
                        'Archivos' => $archivos,
                        'Fecha' => $fecha
                            )
                    );
                }
            }
            $consulta = '';
        } else {
            if ($verificarServicioSinClaficar[0]['Seguimiento'] === '0') {
                if (is_array($datos['datosConcluir'])) {
                    $descripcion = $datos['datosConcluir']['descripcion'];
                } else {
                    $descripcion = $datos['datosConcluir'];
                }
                $datosServicio = array(
                    'IdUsuario' => $usuario['Id'],
                    'IdServicio' => $datos['servicio'],
                    'Descripcion' => $descripcion,
                    'Fecha' => $fecha
                );
                if (!empty($consulta)) {
                    $resultado = $this->DBS->actualizarServicio('t_servicios_generales', $datosServicio, array('IdServicio' => $datos['servicio']));
                } else {
                    $resultado = $this->DBS->setNuevoElemento('t_servicios_generales', $datosServicio);
                }
            }
        }

        if (isset($datos['soloGuardar'])) {
            return true;
        } else {
            if ($verificarServicioSinClaficar[0]['Seguimiento'] === '0') {
                if (isset($datos['img'])) {
                    $this->crearImangenFirma($datos);
                }
                if (is_array($datos['datosConcluir'])) {
                    $datosConcluir = $datos['datosConcluir'];
                } else {
                    $datosConcluir = array($datos['datosConcluir']);
                }
                $cambiarEstatus = $this->cambiarEstatus($fecha, $datos, $datosConcluir, '5');
            } else {
                $this->crearImangenFirma($datos, $datos['datosConcluir']);
                if (isset($datos['datosConcluir']['estatus'])) {
                    $cambiarEstatus = $this->cambiarEstatus($fecha, $datos, NULL, '4');
                } else {
                    $cambiarEstatus = $this->cambiarEstatus($fecha, $datos, NULL, '5');
                }
            }
            if ($cambiarEstatus === TRUE) {
                return TRUE;
            } else {
                return $cambiarEstatus;
            }
        }
    }

    public function enviar_Reporte_PDF(array $datos) {
        $titulo = 'Se concluyo Solicitud';
        $usuario = $this->Usuario->getDatosUsuario();
        $host = $_SERVER['SERVER_NAME'];
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $imgFirma = $datos['img'];
        $imgFirma = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirma));
        $dataFirma = base64_decode($imgFirma);
        $imgFirmaTecnico = $datos['imgFirmaTecnico'];
        $imgFirmaTecnico = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirmaTecnico));
        $dataFirmaTecnico = base64_decode($imgFirmaTecnico);
        $verificarServicioCorrectivo = $this->DBS->getServicios('SELECT IdTipoServicio FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        $folio = $this->DBS->consultaFolio($datos['servicio']);

        if ($verificarServicioCorrectivo[0]['IdTipoServicio'] === '20') {
            $linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']));
            $infoServicio = $this->getInformacionServicio($datos['servicio']);
            $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
            } else {
                $path = 'http://' . $host . '/' . $linkPdf['link'];
            }

            if ($datos['estatus'] === '4') {
                if ($datos['concluirServicio']) {
                    $this->concluirServicioSolicitudTicket($fecha, $datos, $path);
                }
            } else {
                $this->DBS->actualizarServicio('t_servicios_ticket', array(
                    'IdEstatus' => $datos['status'],
                    'FechaConclusion' => $fecha
                        ), array('Id' => $datos['servicio'])
                );
            }

            $dataPDF = $this->enviarReportePDFCorrectivo($datos, $dataFirma, $dataFirmaTecnico);
            $linkPDF = $dataPDF['linkPDF'];
            $linkExtraEquiposFaltante = $dataPDF['linkExtraEquiposFaltante'];
        } else {
            $direccionFirma = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
            $direccionFirmaTecnico = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'FirmaTecnico_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $dataFirma);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirmaTecnico, $dataFirmaTecnico);
            if ($datos['encargadoTI'] !== NULL) {
                $encargadoTI = $datos['encargadoTI'];
            } else {
                $encargadoTI = NULL;
            }

            if (isset($datos['correo'])) {
                $correo = implode(",", $datos['correo']);
            } else {
                $correo = '';
            }

            if ($datos['imgFirmaTecnico'] !== NULL) {
                $imgFirmaTecnico = $direccionFirmaTecnico;
                $idTecnico = $usuario['Id'];
            } else {
                $imgFirmaTecnico = NULL;
                $idTecnico = NULL;
            }

            $this->DBS->actualizarServicio('t_servicios_ticket', array(
                'Firma' => $direccionFirma,
                'NombreFirma' => $datos['recibe'],
                'CorreoCopiaFirma' => $correo,
                'FechaFirma' => $fecha,
                'IdTecnicoFirma' => $idTecnico,
                'FirmaTecnico' => $imgFirmaTecnico,
                'IdValidaCinemex' => $encargadoTI
                    ), array('Id' => $datos['servicio']));

            $linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']));
            $infoServicio = $this->getInformacionServicio($datos['servicio']);
            $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
            } else {
                $path = 'http://' . $host . '/' . $linkPdf['link'];
            }
            $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
            $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
            $linkPDF = '<br>Ver PDF Resumen General <a href="' . $path . '" target="_blank">Aquí</a>';

            if ($datos['estatus'] === '4') {
                if ($datos['concluirServicio']) {
                    $this->concluirServicioSolicitudTicket($fecha, $datos, $path);
                }
            } else {
                $this->DBS->actualizarServicio('t_servicios_ticket', array(
                    'IdEstatus' => $datos['status'],
                    'FechaConclusion' => $fecha
                        ), array('Id' => $datos['servicio'])
                );
            }

            $datosDescripcionConclusion = $this->DBS->getServicios('SELECT
                                            tst.Descripcion AS DescripcionServicio,
                                            tst.IdSolicitud,
                                            tsi.Asunto AS AsuntoSolicitud,
                                            tsi.Descripcion AS DescripcionSolicitud
                                           FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes_internas tsi
                                           ON tsi.IdSolicitud = tst.IdSolicitud
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');

            if ($folio !== FALSE) {
                $textoFolio = '<br>Folio: <strong>' . $folio . '</strong>';
            } else {
                $textoFolio = '';
            }

            $descripcionConclusion = '<br><br>Solicitud: <strong>' . $datosDescripcionConclusion[0]['IdSolicitud'] . '</strong>
                <br>Asunto de la Solicitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br>Descripcion de la Solcitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br><br>Ticket: <strong>' . $datos['ticket'] . '</strong>
                ' . $textoFolio . '
                <br><br>Servicio: <strong>' . $datos['servicio'] . '</strong>
                <br>Descripcion del Servicio: <strong>' . $datosDescripcionConclusion[0]['DescripcionServicio'] . '</strong>';

            $contadorEquiposFaltantes = $this->SeguimientoPoliza->contadorEquiposFaltantes($datos['servicio']);

            if ($contadorEquiposFaltantes[0]['Contador'] > 0) {
                $linkPdfEquipoFaltante = $this->getServicioToPdf(array('servicio' => $datos['servicio']), '/EquipoFaltante');
                if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                    $pathEquipoFaltante = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
                } else {
                    $pathEquipoFaltante = 'http://' . $host . '/' . $linkPdfEquipoFaltante['link'];
                }
                $linkExtraEquiposFaltante = '<br>Ver PDF Equipo Faltante <a href="' . $pathEquipoFaltante . '" target="_blank">Aquí</a>';
            } else {
                $linkExtraEquiposFaltante = '';
            }

            $textoUsuario = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la conclusión del servicio que realizo.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
            $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), $titulo, $textoUsuario);

            $datosSolicita = $this->DBS->getServicios('SELECT
                                            (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = tst.Solicita) AS CorreoSolicita,
                                            nombreUsuario(tst.Solicita) NombreSolicita
                                            FROM t_servicios_ticket tst
                                            WHERE tst.Id = "' . $datos['servicio'] . '"');
            $textoSolicita = '<p>Estimado(a) <strong>' . $datosSolicita[0]['NombreSolicita'] . ',</strong> se le ha mandado el documento de la conclusión del servicio que ha solicitado en el ticket: </p><strong>' . $datos['ticket'] . '</strong>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
            $this->enviarCorreoConcluido(array($datosSolicita[0]['CorreoSolicita']), $titulo, $textoSolicita);

            $idArea = $this->DBS->getServicios('SELECT
                                            cvds.IdArea
                                            FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes ts
                                            ON tst.IdSolicitud = ts.Id
                                           INNER JOIN cat_v3_departamentos_siccob cvds
                                            ON ts.IdDepartamento = cvds.Id
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');
            if ($idArea[0]['IdArea'] === '8') {
                $correoCordinadorPoliza = $this->DBS->getServicios('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
                $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento de la conclusión del servicio que realizo el personal ' . $usuario['Nombre'] . '.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
                foreach ($correoCordinadorPoliza as $key => $value) {
                    $this->enviarCorreoConcluido(array($value['EmailCorporativo']), $titulo, $textoCoordinadorPoliza);
                }
            }

            if (isset($datos['correo'])) {
                $textoCorreo = '<p>Estimado(a) <strong>' . $datos['recibe'] . ',</strong> se le he mandado el documento que ha firmado de la conclusión del servicio(s) a solicitado.</p>' . $linkPDF . $linkDetallesServicio . $linkExtraEquiposFaltante;
                $this->enviarCorreoConcluido($datos['correo'], $titulo, $textoCorreo);
            }
        }

        if ($folio !== FALSE && $usuario['IdPerfil'] == '83') {
            $this->agregarVueltaAsociado($folio, $datos);
        }

        return TRUE;
    }

    public function agregarVueltaAsociado(string $folio, array $datos) {
        $dataServicios = $this->DBS->getServicios('SELECT
                                                        IdSucursal,
                                                        (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio,
                                                        IdEstatus,
                                                        sucursal(IdSucursal) Sucursal
                                                    FROM t_servicios_ticket
                                                    WHERE Id = "' . $datos['servicio'] . '"');

        $nombreSucursal = str_replace(" PLATINO", "", $dataServicios[0]['Sucursal']);
        $vueltasAnteriores = $this->DBT->vueltasAnteriores(array('folio' => $dataServicios[0]['Folio']));
        $sucursalVuelta = str_replace(" PLATINO", "", $vueltasAnteriores[0]['Nombre']);
        if ($sucursalVuelta !== $nombreSucursal) {
            $this->guardarVueltaAsociados(array(
                'servicio' => $datos['servicio'],
                'img' => $datos['img'],
                'imgFirmaTecnico' => $datos['imgFirmaTecnico'],
                'recibe' => $datos['recibe'],
                'vueltaAutomatica' => [TRUE]
            ));
        }
    }

    public function enviarReportePDFCorrectivo(array $datos, string $dataFirma, string $dataFirmaTecnico) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $host = $_SERVER['SERVER_NAME'];
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $correctivos = $this->DBS->getServicios('SELECT Id, Ticket FROM t_servicios_ticket WHERE Ticket = ' . $datos['ticket'] . ' AND IdTipoServicio = 20 AND IdEstatus = 4 AND FIRMA IS NULL');

        if (isset($datos['correo'])) {
            $correo = implode(",", $datos['correo']);
        } else {
            $correo = null;
        }

        $encargadoTI = $datos['encargadoTI'];
        $idTecnico = $usuario['Id'];
        $linkPDF = '';
        $linkExtraEquiposFaltante = '';
        $contador = 0;

        if (!empty($correctivos)) {
            foreach ($correctivos as $key => $value) {
                $contador++;
                $direccionFirma = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'Firma_' . $value['Ticket'] . '_' . $value['Id']) . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $dataFirma);
                $direccionFirmaTecnico = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'FirmaTecnico_' . $value['Ticket'] . '_' . $value['Id']) . '.png';
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirmaTecnico, $dataFirmaTecnico);

                $this->DBS->actualizarServicio('t_servicios_ticket', array(
                    'Firma' => $direccionFirma,
                    'NombreFirma' => $datos['recibe'],
                    'CorreoCopiaFirma' => $correo,
                    'FechaFirma' => $fecha,
                    'IdTecnicoFirma' => $idTecnico,
                    'FirmaTecnico' => $direccionFirmaTecnico,
                    'IdValidaCinemex' => $encargadoTI
                        ), array('Id' => $value['Id']));
                $linkPdf = $this->getServicioToPdf(array('servicio' => $value['Id']));
                $infoServicio = $this->getInformacionServicio($value['Id']);
                $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

                if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                    $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $value['Id'] . '/Pdf/Ticket_' . $value['Ticket'] . '_Servicio_' . $value['Id'] . '_' . $tipoServicio . '.pdf';
                } else {
                    $path = 'http://' . $host . '/' . $linkPdf['link'];
                }

                $linkPDF .= '<br>Ver Servicio PDF-' . $contador . '<a href="' . $path . '" target="_blank"> Aquí</a>';

                $contadorEquiposFaltantes = $this->SeguimientoPoliza->contadorEquiposFaltantes($value['Id']);

                if ($contadorEquiposFaltantes[0]['Contador'] > 0) {
                    $linkPdfEquipoFaltante = $this->getServicioToPdf(array('servicio' => $value['Id']), '/EquipoFaltante');
                    if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                        $pathEquipoFaltante = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $value['Id'] . '/Pdf/Ticket_' . $value['Ticket'] . '_Servicio_' . $value['Id'] . '_' . $tipoServicio . '.pdf';
                    } else {
                        $pathEquipoFaltante = 'http://' . $host . '/' . $linkPdfEquipoFaltante['link'];
                    }
                    $linkExtraEquiposFaltante .= '<br>Ver PDF Equipo Faltante <a href="' . $pathEquipoFaltante . '" target="_blank">Aquí</a>';
                }
            }
        }

        $data['linkPDF'] = $linkPDF;
        $data['linkExtraEquiposFaltante'] = $linkExtraEquiposFaltante;
        return $data;
    }

    public function concluirServicioSolicitudTicket(string $fecha, array $datos, string $path = NULL) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $host = $_SERVER['SERVER_NAME'];
        $linkPDF = '';
        $contador = 0;
        $correctivos = $this->DBS->getServicios('SELECT Id, Ticket FROM t_servicios_ticket WHERE Ticket = ' . $datos['ticket'] . ' AND IdTipoServicio = 20');
        $this->DBS->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => '4',
            'FechaConclusion' => $fecha
                ), array('Id' => $datos['servicio']));
        $verificarEstatusTicket = $this->DBS->getServicios('SELECT 
                                                                IdEstatus,
                                                                IdSolicitud
                                                            FROM t_servicios_ticket tst
                                                            WHERE ticket = ' . $datos['ticket'] . '
                                                            AND IdEstatus IN(10,5,2,1,3)');
        $verificarSolicitud = $this->DBS->getServicios('SELECT
                                                            Id,
                                                            Folio,
                                                            (SELECT EmailCorporativo From cat_v3_usuarios WHERE ID = Atiende) CorreoAtiende,
                                                            nombreUsuario(Atiende) Atiende
                                                        FROM t_solicitudes
                                                        WHERE Ticket =  "' . $datos['ticket'] . '"');

        if (!$verificarEstatusTicket) {
            foreach ($verificarSolicitud as $key => $value) {
                $this->DBS->actualizarServicio('t_solicitudes', array(
                    'IdEstatus' => '4',
                    'FechaConclusion' => $fecha
                        ), array('Id' => $value['Id']));
            }

            $this->DBS->concluirTicketAdist2(array(
                'Estatus' => 'CONCLUIDO',
                'Flag' => '1',
                'F_Cierre' => '0',
                'Id_Orden' => $datos['ticket']
            ));

            foreach ($correctivos as $key => $value) {
                $contador++;
                $linkPdf = $this->getServicioToPdf(array('servicio' => $value['Id']));
                $infoServicio = $this->getInformacionServicio($value['Id']);
                $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);

                if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                    $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $value['Id'] . '/Pdf/Ticket_' . $value['Ticket'] . '_Servicio_' . $value['Id'] . '_' . $tipoServicio . '.pdf';
                    $linkDetallesSolicitud = 'http://siccob.solutions/Detalles/Solicitud/' . $verificarSolicitud[0]['Id'];
                } else {
                    $path = 'http://' . $host . '/' . $linkPdf['link'];
                    $linkDetallesSolicitud = 'http://' . $host . '/Detalles/Solicitud/' . $verificarSolicitud[0]['Id'];
                }

                $linkPDF .= '<br>Ver Servicio PDF-' . $contador . ' <a href="' . $path . '" target="_blank">Aquí</a>';
            }

            $titulo = 'Solicitud Concluida';
            $linkSolicitud = 'Ver detalles de la Solicitud <a href="' . $linkDetallesSolicitud . '" target="_blank">Aquí</a>';
            $textoCorreo = '<p>Estimado(a) <strong>' . $verificarSolicitud[0]['Atiende'] . ',</strong> se ha concluido la Solicitud.</p><br>Ticket: <strong>' . $value['Ticket'] . '</strong><br> Número Solicitud: <strong>' . $verificarSolicitud[0]['Id'] . '</strong><br><br>' . $linkSolicitud . '<br>' . $linkPDF;
            $this->enviarCorreoConcluido(array($verificarSolicitud[0]['CorreoAtiende']), $titulo, $textoCorreo);

            return 'serviciosConcluidos';
        }
    }

    public function crearImangenFirma(array $datos, array $datosExtra = NULL) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $img = $datos['img'];
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $data = base64_decode($img);
        $direccion = '/storage/Archivos/imagenesFirmas/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccion, $data);
        if ($datosExtra !== NULL) {
            if (isset($datos['correo'])) {
                $correo = implode(",", $datos['correo']);
            } else {
                $correo = NULL;
            }
        } else {
            if (isset($datos['correo'])) {
                $correo = $datos['correo'];
            } else {
                $correo = '';
            }
        }
        if (is_array($correo)) {
            $correo = implode(",", $correo);
        }

        $this->DBS->actualizarServicio('t_servicios_ticket', array(
            'Firma' => $direccion,
            'NombreFirma' => $datos['recibe'],
            'CorreoCopiaFirma' => $correo,
            'FechaFirma' => $fecha
                ), array('Id' => $datos['servicio']));

        return TRUE;
    }

    public function cambiarEstatus(string $fecha, array $datos, array $datosExtra = NULL, string $status) {
        $cambiarEstatus = $this->DBS->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => $status,
            'FechaConclusion' => $fecha
                ), array('Id' => $datos['servicio'])
        );
        if ($datosExtra !== NULL) {
            if (is_array($datos['datosConcluir'])) {
                $datosConcluir = $datos['datosConcluir'];
            } else {
                $datosConcluir = array($datos['datosConcluir']);
            }
            $resultadoEnviarConclusion = $this->enviarCorreoConlusionPDF($datos, $datosConcluir);
        } else {
            $resultadoEnviarConclusion = $this->enviarCorreoConlusionPDF($datos);
        }
        if ($resultadoEnviarConclusion === TRUE) {
            if (!empty($cambiarEstatus)) {
                return TRUE;
            } else {
                return 'errorConcluir';
            }
        } else {
            return $resultadoEnviarConclusion;
        }
    }

    public function enviarCorreoConlusionPDF(array $datos, array $datosExtra = NULL) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $titulo = 'Se concluyo el servicio';
        $linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']));
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }
        if ($datosExtra !== NULL) {
            if (isset($datos['correo'])) {
                $correo = $datos['correo'];
                if (is_array($datos['correo'])) {
                    $correo = $datos['correo'];
                } else {
                    $correo = explode(",", $datos['correo']);
                }
            } else {
                $correo = array('');
            }
        } else {
            if (isset($datos['correo'])) {
                if (is_array($datos['correo'])) {
                    $correo = $datos['correo'];
                } else {
                    $correo = explode(",", $datos['correo']);
                }
            } else {
                $correo = array('');
            }
        }
        $detallesServicio = $this->linkDetallesServicio($datos['servicio']);
        $linkDetallesServicio = '<br>Ver Detalles del Servicio <a href="' . $detallesServicio . '" target="_blank">Aquí</a>';
        $linkPDF = '<br>Ver PDF Resumen General <a href="' . $path . '" target="_blank">Aquí</a>';
        $datosDescripcionConclusion = $this->DBS->getServicios('SELECT
                                            tst.Descripcion AS DescripcionServicio,
                                            tst.IdSolicitud,
                                            tsi.Asunto AS AsuntoSolicitud,
                                            tsi.Descripcion AS DescripcionSolicitud
                                           FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes_internas tsi
                                           ON tsi.IdSolicitud = tst.IdSolicitud
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');
        $descripcionConclusion = '<br><br>Solicitud: <strong>' . $datosDescripcionConclusion[0]['IdSolicitud'] . '</strong>
                <br>Asunto de la Solicitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br>Descripcion de la Solcitud: <strong>' . $datosDescripcionConclusion[0]['AsuntoSolicitud'] . '</strong>
                <br><br>Ticket: <strong>' . $datos['ticket'] . '</strong>
                <br><br>Servicio: <strong>' . $datos['servicio'] . '</strong>
                <br>Descripcion del Servicio: <strong>' . $datosDescripcionConclusion[0]['DescripcionServicio'] . '</strong>';

        if ($correo !== '') {
            if (isset($datos['recibe'])) {
                $textoCorreo = '<p>Estimado(a) <strong>' . $datos['recibe'] . ',</strong> se le he mandado el documento que ha firmado en la conclusión del servicio solicitado.</p>' . $linkPDF . $linkDetallesServicio;
                $this->enviarCorreoConcluido($correo, $titulo, $textoCorreo);
            }
        }

        $textoUsuario = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la conclusión del servicio que realizo.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
        $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), $titulo, $textoUsuario);

        $datosSolicita = $this->DBS->getServicios('SELECT
                                            (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = tst.Solicita) AS CorreoSolicita,
                                            nombreUsuario(tst.Solicita) NombreSolicita
                                            FROM t_servicios_ticket tst
                                            WHERE tst.Id = "' . $datos['servicio'] . '"');

        $idArea = $this->DBS->getServicios('SELECT
                                            cvds.IdArea
                                            FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes ts
                                            ON tst.IdSolicitud = ts.Id
                                           INNER JOIN cat_v3_departamentos_siccob cvds
                                            ON ts.IdDepartamento = cvds.Id
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');
        if ($idArea[0]['IdArea'] === '8') {
            $correoCordinadorPoliza = $this->DBS->getServicios('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
            $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento de la conclusión del servicio que realizo el personal ' . $usuario['Nombre'] . '.</p>' . $linkPDF . $linkDetallesServicio . $descripcionConclusion;
            foreach ($correoCordinadorPoliza as $key => $value) {
                $this->enviarCorreoConcluido(array($value['EmailCorporativo']), $titulo, $textoCoordinadorPoliza);
            }
        }

        return TRUE;
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function verificarServiciosDepartamento(string $tipoServicio) {
        return $this->DBS->getServicios('SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = "' . $tipoServicio . '"');
    }

    public function mostrarFormularioAvenceServicio(array $datos) {
        $data = array();
        $tipoServicio = $this->DBS->consultaGeneral('SELECT IdTipoServicio, IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');

        if ($datos['tipoAvanceProblema'] === '2' && $tipoServicio[0]['IdTipoServicio'] === '27' && !empty($tipoServicio[0]['IdSucursal'])) {
            $equipos = $this->DBS->consultaGeneral('SELECT 
                                                        IdModelo AS Id,
                                                        Serie AS Parte,
                                                        modelo(IdModelo) Equipo 
                                                    FROM t_censos
                                                    WHERE IdServicio = (SELECT 
                                                                                IdServicio 
                                                                        FROM t_censos_generales 
                                                                        WHERE IdSucursal = ' . $tipoServicio[0]['IdSucursal'] . '
                                                                        GROUP BY Id DESC LIMIT 1)
                                                    GROUP BY Id
                                                    ORDER BY Equipo ASC');
        } else {
            $equipos = $this->DBS->consultaGeneral('SELECT * FROM v_equipos');
        }

        $equiposSAE = $this->Catalogo->catEquiposSAE('3', array('Flag' => '1'));
        $componentesEquipo = $this->Catalogo->catComponentesEquipo('3');
        $tiposDiagnostico = $this->DBS->consultaGeneral('SELECT * FROM cat_v3_tipos_diagnostico_correctivo WHERE Flag = 1 AND Id > 1');
        $CI = parent::getCI();

        $data = [
            'equiposSAE' => $equiposSAE,
            'equipos' => $equipos,
            'componentesEquipo' => $componentesEquipo,
            'tiposDiagnostico' => $tiposDiagnostico
        ];

        return array('formulario' => parent::getCI()->load->view('Generales/Modal/formularioAvanceServicio', $data, TRUE), 'datos' => $data);
    }

    public function guardarAvenceServicio(array $datos) {
        $archivos = null;
        $CI = parent::getCI();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $verificar = TRUE;

        $idConsulta = $this->DBS->setServicioId('t_servicios_avance', array(
            'IdServicio' => $datos['servicio'],
            'IdUsuario' => $usuario['Id'],
            'IdTipo' => $datos['tipoAvanceProblema'],
            'Fecha' => $fecha,
            'Descripcion' => $datos['descripcion']
        ));

        if ((is_string($datos['datosTabla']))) {
            $datos['datosTabla'] = explode(",", $datos['datosTabla']);
            $datos['datosTabla'] = array_chunk($datos['datosTabla'], 8);
        }

        if ($datos['verificarArchivos'] === 'false') {
            if ($datos['datosTabla'][0] === 'sinDatos') {
                $verificar = FALSE;
            }
        } else {
            if ($datos['datosTabla'][0][0] === 'sinDatos') {
                $verificar = FALSE;
            }
        }

        if ($verificar === TRUE) {
            foreach ($datos['datosTabla'] as $value) {
                $this->DBS->setNuevoElemento('t_servicios_avance_equipo', array(
                    'IdAvance' => $idConsulta,
                    'TipoItem' => $value[4],
                    'IdItem' => $value[5],
                    'Serie' => $value[2],
                    'Cantidad' => $value[3],
                    'IdTipoDiagnostico' => $value[7]
                ));
            }
        }

        if ($datos['tipoAvanceProblema'] === '2') {
            $this->DBS->actualizarServicio('t_servicios_ticket', array(
                'IdEstatus' => '3'
                    ), array('Id' => $datos['servicio'])
            );
        }

        if ($datos['verificarArchivos'] !== 'false') {
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/ArchivosAvance/';
            $archivos = setMultiplesArchivos($CI, 'archivosAvanceServicio', $carpeta);
            $host = $_SERVER['SERVER_NAME'];

            if ($archivos) {
                $archivos = implode(',', $archivos);
                $this->DBS->actualizarServicio('t_servicios_avance', array(
                    'Archivos' => $archivos
                        ), array('Id' => $idConsulta)
                );
            } else {
                return FALSE;
            }
        }

        $datosSD = $this->InformacionServicios->guardarDatosServiceDesk($datos['servicio']);
        if (!empty($datosSD)) {
            if ($datosSD) {
                return array('avances' => $this->Servicio->consultaAvanceServicio($datos['servicio']), 'SD' => '');
            } else {
                return array('avances' => $this->Servicio->consultaAvanceServicio($datos['servicio']), 'SD' => $datosSD);
            }
        } else {
            return array('avances' => $this->Servicio->consultaAvanceServicio($datos['servicio']), 'SD' => '');
        }
    }

    public function mostrarFormularioReasignarServicio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();
        $data['atiende'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => $usuario['IdDepartamento']));

        return array('formulario' => parent::getCI()->load->view('Generales/Modal/formularioReasignarServicio', $data, TRUE), 'datos' => $data);
    }

    public function cambiarAtiendeServicio(array $datos) {
        $atiendeAnterior = $this->DBS->getServicios('SELECT Atiende FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        if ($atiendeAnterior[0]['Atiende'] !== $datos['atiende']) {
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $usuario = $this->Usuario->getDatosUsuario();
            $atiende = $this->DBS->getDatosAtiende($datos['atiende']);

            $this->DBS->setNuevoElemento('t_servicios_reasignaciones', array(
                'IdUsuario' => $usuario['Id'],
                'IdServicio' => $datos['servicio'],
                'Atendia' => $atiendeAnterior[0]['Atiende'],
                'Atiende' => $datos['atiende'],
                'Descripcion' => $datos['descripcion'],
                'Fecha' => $fecha
                    )
            );

            $this->DBS->actualizarServicio('t_servicios_ticket', array(
                'Atiende' => $datos['atiende']
                    ), array('Id' => $datos['servicio'])
            );

            $data['departamento'] = $usuario['IdDepartamento'];
            $data['remitente'] = $usuario['Id'];
            $data['tipo'] = '19';
            $data['descripcion'] = 'Se reasigno el servicio <b class="f-s-16">' . $datos['servicio'] . '</b> del ticket ' . $datos['ticket'];

            $this->Notificacion->setNuevaNotificacion(
                    $data, 'Reasignación de Servicio', 'El usuario <b>' . $usuario['Nombre'] . '</b> te ha reasignado el servicio "<strong>' . $datos['servicio'] . '</strong>" del ticket ' . $datos['ticket'] . '.<br>
                        <br>Por lo que se solicita que se atienda lo mas pronto posible el servicio.', $atiende);
            return $this->DBS->getServicios('SELECT NombreUsuario(Id) AS Nombre FROM cat_v3_usuarios WHERE Id = "' . $datos['atiende'] . '"');
        } else {
            return FALSE;
        }
    }

    public function getNotasByServicio(int $servicio) {
        $resultado = $this->DBS->consultaGeneral('SELECT 
                                                    IdSolicitud 
                                                FROM t_servicios_ticket 
                                                WHERE Id = "' . $servicio . '"');
        $idSolicitud = $resultado[0]['IdSolicitud'];
        $sentencia = "SELECT * FROM (SELECT 
                            tns.Id,
                            nombreUsuario(tns.IdUsuario) as Nombre, 
                        (select Archivos from t_notas_servicio where Id = tns.Id)  as Archivos,
                            tns.Nota,
                            tns.Fecha 
                    FROM t_notas_servicio tns where tns.IdEstatus in(10,13,6,2)
                    and tns.IdServicio = '" . $servicio . "'
                    UNION
                    SELECT 
                            tnso.Id,
                            nombreUsuario(tnso.IdUsuario) as Nombre, 
                        (select if(Archivos is not null, '', Archivos) from t_notas_servicio where Id = tnso.Id)  as Archivos,
                            tnso.Nota,
                        tnso.Fecha
                    FROM t_notas_solicitudes tnso 
                    WHERE tnso.IdSolicitud = '" . $idSolicitud . "') AS TABLAS ORDER BY TABLAS.Fecha DESC";
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function getNotasBySolicitud(int $solicitud) {
        $sentencia = "SELECT 
                            tnso.Id,
                            nombreUsuario(tnso.IdUsuario) as Nombre, 
                        (select if(Archivos is not null, '', Archivos) from t_notas_servicio where Id = tnso.Id)  as Archivos,
                            tnso.Nota,
                        tnso.Fecha
                    FROM t_notas_solicitudes tnso 
                    WHERE tnso.IdSolicitud = '" . $solicitud . "'";
        return $this->DBS->consultaGeneral($sentencia);
    }

    public function eliminarEvidenciaServicio(array $datos) {
        $posicionInicial = strpos($datos['key'], 'Servicio-') + 9;
        $posicionFinal = strpos($datos['key'], '/', $posicionInicial);
        $diferencia = $posicionFinal - $posicionInicial;
        $servicio = substr($datos['key'], $posicionInicial, $diferencia);

        $evidenciasAnteriores = $this->DBS->consultaGeneral('select Id, Archivos
                        from t_servicios_generales 
                        where IdServicio = "' . $servicio . '"
                        and Archivos like "%' . $datos['key'] . '%"');

        $evidencias = explode(',', $evidenciasAnteriores[0]['Archivos']);

        if (in_array($datos['key'], $evidencias)) {
            foreach ($evidencias as $key => $value) {
                if ($value === $datos['key']) {
                    unset($evidencias[$key]);
                }
            }

            $archivos = implode(',', $evidencias);
            $consulta = $this->DBS->actualizarServicio('t_servicios_generales', array('Archivos' => $archivos), array('Id' => $evidenciasAnteriores[0]['Id']));

            if (!empty($consulta)) {
                eliminarArchivo($datos['key']);
            } else {
                return FALSE;
            }
        }
    }

    public function getHistorialServicio(string $servicio) {
        $query = ""
                . "select "
                . "tsa.Id, "
                . "(select UrlFoto from t_rh_personal where IdUsuario = tsa.IdUsuario) as Foto, "
                . "nombreUsuario(tsa.IdUsuario) as Usuario, "
                . "tsa.IdTipo, "
                . "if(tsa.IdTipo = 1, 'Avance', 'Problema') as TipoAvance, "
                . "tsa.Fecha, "
                . "tsa.Descripcion, "
                . "tsa.Archivos "
                . "from t_servicios_avance tsa "
                . "where tsa.IdServicio = '" . $servicio . "'";
        $resultado = $this->DBS->consultaGeneral($query);

        $arrayReturn = [];
        foreach ($resultado as $key => $value) {
            $query = "select 
                        tsae.IdAvance,
                        CASE tsae.IdItem
                                when 1 then 'Equipo'
                                when 2 then 'Material'
                                when 3 then 'Refacción'
                        end as Tipo,
                        CASE tsae.IdItem
                                when 1 then modelo(tsae.TipoItem)
                                when 2 then (select Nombre from cat_v3_equipos_sae where Id = tsae.TipoItem)
                                when 3 then (select Nombre from cat_v3_componentes_equipo where Id = tsae.TipoItem)
                        end as Equipo,
                        tsae.Serie,
                        tsae.Cantidad
                        from t_servicios_avance_equipo tsae
                        where IdAvance = '" . $value['Id'] . "';";
            array_push($arrayReturn, [
                'usuario' => $value['Usuario'],
                'foto' => $value['Foto'],
                'IdTipo' => $value['IdTipo'],
                'TipoAvance' => $value['TipoAvance'],
                'fecha' => $value['Fecha'],
                'descripcion' => $value['Descripcion'],
                'archivos' => explode(",", $value['Archivos']),
                'items' => $this->DBS->consultaGeneral($query)
            ]);
        }
        return $arrayReturn;
    }

    public function consultaIdClienteSucursal(array $datos) {
        $consulta = $this->DBS->getServicios('SELECT 
                                                (SELECT IdCliente FROM cat_v3_sucursales WHERE Id = IdSucursal) IdCliente
                                            FROM t_servicios_ticket 
                                            WHERE Id = "' . $datos['servicio'] . '"');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
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

    public function guardarVueltaAsociados(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        if (!isset($datos['vueltaAutomatica'])) {
            $this->enviarCorreoConcluido(array('abarcenas@siccob.com.mx'), 'Vuelta-' . $datos['servicio'], 'Se creo la vuelta del servicio:' . $datos['servicio']);
        }
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $fechaAsociado = mdate('%Y-%m-%d_%H-%i-%s', now('America/Mexico_City'));
        $folio = $this->DBS->getServicios('SELECT
                                                IdSucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $datos['servicio'] . '"');

        if (isset($datos['correo'])) {
            $correo = implode(",", $datos['correo']);
        } else {
            $correo = '';
        }

        $img = $datos['img'];
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $imagenFirmaGerente = base64_decode($img);
        $direccionFirma = '/storage/Archivos/imagenesFirmas/Asociados/' . str_replace(' ', '_', 'Firma_' . $folio[0]['Folio']) . $fechaAsociado . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $imagenFirmaGerente);

        $imgFirmaTecnico = $datos['imgFirmaTecnico'];
        $imgFirmaTecnico = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirmaTecnico));
        $imagenFirmaTecnico = base64_decode($imgFirmaTecnico);
        $direccionFirmaTecnico = '/storage/Archivos/imagenesFirmas/Asociados/' . str_replace(' ', '_', 'FirmaTecnico_' . $folio[0]['Folio']) . $fechaAsociado . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirmaTecnico, $imagenFirmaTecnico);

        $vueltasFacturasOutsourcing = $this->DBT->vueltasFacturasOutsourcing($folio[0]['Folio']);

        if (empty($vueltasFacturasOutsourcing)) {
            $vuelta = '1';
        } else {
            $numeroVuelta = (int) $vueltasFacturasOutsourcing[0]['Vuelta'];
            $vuelta = $numeroVuelta + 1;
        }

        $idFacturacionOutSourcing = $this->DBS->setServicioId('t_facturacion_outsourcing', array(
            'IdServicio' => $datos['servicio'],
            'Vuelta' => $vuelta,
            'Folio' => $folio[0]['Folio'],
            'Fecha' => $fecha,
            'IdUsuario' => $usuario['Id'],
            'FirmaUsuario' => $direccionFirmaTecnico,
            'Gerente' => $datos['recibe'],
            'FirmaGerente' => $direccionFirma,
            'Correos' => $correo,
            'IdEstatus' => '8',
            'FechaEstatus' => $fecha
                )
        );

        $linkPdf = $this->pdfAsociadoVueltas(array('servicio' => $datos['servicio'], 'folio' => $folio[0]['Folio']), $fechaAsociado);
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $infoServicio = $this->getInformacionServicio($datos['servicio']);
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Asociados/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $fechaAsociado . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        $consulta = $this->DBS->actualizarServicio('t_facturacion_outsourcing', array(
            'Archivo' => $path,
                ), array('Id' => $idFacturacionOutSourcing)
        );

        if ($consulta) {
            $key = $this->MSP->getApiKeyByUser($usuario['Id']);
            $informacionSD = $this->ServiceDesk->getDetallesFolio($key, $folio[0]['Folio']);

            if (isset($informacionSD->SHORTDESCRIPTION)) {
                $detallesSD = $informacionSD->SHORTDESCRIPTION;
            } else {
                $detallesSD = '';
            }

            $titulo = 'Documentación de Vuelta';
            $linkPDF = '<br>Ver PDF Resumen Vuelta <a href="' . $path . '" target="_blank">Aquí</a>';

            $descripcionVuelta = '<br><br>Folio: <strong>' . $folio[0]['Folio'] . '</strong>
                <br>Descripción de Service Desk: <strong>' . $detallesSD . '</strong>';

            if (isset($datos['correo'])) {
                $textoCorreo = '<p>Estimado(a) <strong>' . $datos['recibe'] . ',</strong> se le he mandado el documento que ha firmado de la vuelta que realizo del técnico <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $linkPDF;
                $this->enviarCorreoConcluido($datos['correo'], $titulo, $textoCorreo);
            }

            $textoUsuario = '<p>Estimado(a) <strong>' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento de la vuelta que realizo.</p>' . $linkPDF . $descripcionVuelta;
            $this->enviarCorreoConcluido(array($usuario['EmailCorporativo']), $titulo, $textoUsuario);

            $correoSupervisorZona = $this->DBS->getServicios('SELECT 
                                                        (SELECT IdResponsableInterno FROM cat_v3_regiones_cliente WHERE Id = IdRegionCliente)SupervisorZona,
                                                        (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = SupervisorZona)CorreoSupervisorZona
                                                    FROM cat_v3_sucursales
                                                    WHERE Id = "' . $folio[0]['IdSucursal'] . '"');

            $textoSupervisorZona = '<p><strong>Supervisor,</strong> se le ha mandado el documento de la vuelta que realizo el técnico <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $linkPDF . $descripcionVuelta;
            $this->enviarCorreoConcluido(array($correoSupervisorZona[0]['CorreoSupervisorZona']), $titulo, $textoSupervisorZona);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function pdfAsociadoVueltas(array $servicio, string $nombreExtra = NULL) {
        $usuario = $this->Usuario->getDatosUsuario();
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Asociados/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Folio/' . $servicio['folio'];
        $datosServicio = $this->DBS->getServicios('SELECT
                                                sucursal(IdSucursal) Sucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $servicio['servicio'] . '"');
        $link = $this->Phantom->htmlToPdf($archivo, $ruta, $datosServicio[0]);
        return ['link' => $link];
    }

    public function informacionFolioPDF(string $folio) {
        $contenido = '';
        $dataServicios = $this->DBS->getServicios('SELECT
                                                        tst.Id 
                                                    FROM t_solicitudes ts
                                                    INNER JOIN t_servicios_ticket tst
                                                    ON tst.IdSolicitud = ts.Id
                                                    WHERE Folio = "' . $folio . '"');

        $contenido .= $this->portadaFolioPDF($folio);

        foreach ($dataServicios as $key => $value) {
            $tipo = $this->getTipoByServicio($value['Id']);
            $verificarSeguimiento = $this->verificarServiciosDepartamento($tipo[0]['IdTipoServicio']);

            switch ($tipo[0]['IdTipoServicio']) {
                case '20': case 20:
                    $titulo = 'Resumen de Servicio - Correctivo';
                    $contenido .= $this->getDetallesCorrectivo($value['Id']);
                    break;
            }

            if ($verificarSeguimiento[0]['Seguimiento'] === '0') {
                $titulo = 'Resumen de Servicio';
                $contenido .= $this->getDetallesSinClasificar($value['Id'], true);
            }
        }

        return $contenido;
    }

    public function portadaFolioPDF(string $folio) {
        $data = array();
        $generalesSolicitud = $this->getGeneralesSolicitudFolio($folio);

        $tablaServicios = '';

        $tablaServicios .= '<div class="row">
                <div class="col-md-12">
                    <table class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%" height="3px">
                        <thead>
                            <tr>
                                <th>Atiende</th>
                                <th>Folio</th>
                                <th>Ticket</th>
                                <th>Servicio</th>
                                <th>Tipo Servicio</th>
                                <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($generalesSolicitud as $key => $valor) {
            $tablaServicios .= '<tr>
            <td>' . $valor['Solicitante'] . '</td>
            <td>' . $valor['Folio'] . '</td>
            <td>' . $valor['Ticket'] . '</td>
            <td>' . $valor['Servicio'] . '</td>
            <td>' . $valor['NTipoServicio'] . '</td>
            <td>' . $valor['EstatusServicio'] . '</td>
            </tr>';
        }

        $tablaServicios .= '</tbody>
            </table>
            </div>
            </div>';

        $key = $this->MSP->getApiKeyByUser($generalesSolicitud[0]['Atiende']);
        $informacionSD = $this->ServiceDesk->getDetallesFolio($key, $folio);
        if (isset($informacionSD->SHORTDESCRIPTION)) {
            $detallesSD = $informacionSD->SHORTDESCRIPTION;
        } else {
            $detallesSD = '';
        }

        $datosFacturacionOutsourcig = $this->DBS->getServicios('SELECT 
                                                                    *,
                                                                    nombreUsuario(IdUsuario)NombreTecnico
                                                                FROM t_facturacion_outsourcing
                                                                WHERE Folio = "' . $folio . '"
                                                                ORDER BY Id DESC LIMIT 1');

        $numeroVueltas = $this->DBS->getServicios('SELECT 
                                                        COUNT(Id) Vueltas
                                                    FROM t_facturacion_outsourcing
                                                    WHERE Folio = "' . $folio . '"');

        $data = [
            'tablaServicios' => $tablaServicios,
            'solicitud' => $generalesSolicitud[0],
            'datosFacturacionOutsourcig' => $datosFacturacionOutsourcig[0],
            'numeroVueltas' => $numeroVueltas[0],
            'detallesSD' => $detallesSD
        ];

        return parent::getCI()->load->view('Generales/Detalles/portadaFolioPdf', $data, TRUE);
    }

    public function varificarVueltaAsociado(array $datos) {
        $dataServicios = $this->DBS->getServicios('SELECT
                                                        IdSucursal,
                                                        (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio,
                                                        IdEstatus,
                                                        sucursal(IdSucursal) Sucursal
                                                    FROM t_servicios_ticket
                                                    WHERE Id = "' . $datos['servicio'] . '"');

        if ($dataServicios[0]['Folio'] && $dataServicios[0]['Folio'] !== '0') {
            if (!empty($dataServicios[0]['IdSucursal'])) {
                if ($dataServicios[0]['IdEstatus'] === '3') {
                    $nombreSucursal = str_replace(" PLATINO", "", $dataServicios[0]['Sucursal']);
                    $vueltasAnteriores = $this->DBT->vueltasAnteriores(array('folio' => $dataServicios[0]['Folio']));
                    
                    if (empty($vueltasAnteriores)) {
                        $sucursalVuelta = '';
                    } else {
                        $sucursalVuelta = str_replace(" PLATINO", "", $vueltasAnteriores[0]['Nombre']);
                    }

                    if ($sucursalVuelta === $nombreSucursal) {
                        if (empty($vueltasAnteriores)) {
                            return TRUE;
                        } else {
                            return 'yaTieneVueltas';
                        }
                    } else {
                        return TRUE;
                    }
                } else {
                    return 'noEstaProblema';
                }
            } else {
                return 'sinSucural';
            }
        } else {
            return 'noTieneFolio';
        }
    }

    public function varificarTecnicoPoliza() {
        $usuario = $this->Usuario->getDatosUsuario();

        $tecnicoPoliza = $this->DBS->getServicios('SELECT * FROM cat_v3_usuarios WHERE Id = "' . $usuario['IdPerfil'] . '" AND IdPerfil in(57,64)');

        if ($tecnicoPoliza === FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
