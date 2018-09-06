<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Solicitud
 *
 * @author Freddy
 */
class Notas extends General {

    private $Notificacion;
    private $DBS;
    private $ServiceDesk;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_ServicioTicket::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    public function getListaNotas(string $servicio, string $idSolicitud = NULL) {
        setlocale(LC_TIME, 'es_ES.UTF-8');
        date_default_timezone_set('America/Mexico_City');

        if ($idSolicitud !== NULL) {
            $resultado = $this->DBS->consultaGeneral('SELECT 
                                                            IdSolicitud 
                                                        FROM t_servicios_ticket 
                                                        WHERE Id = "' . $servicio . '"');
            $idSolicitud = $resultado[0]['IdSolicitud'];
        }
        $sentencia = "SELECT * FROM (SELECT 
                            tns.Id,
                            nombreUsuario(tns.IdUsuario) as Usuario, 
                            (select if(UrlFoto is null, '/assets/img/user-5.jpg', UrlFoto) from t_rh_personal where IdUsuario = tns.IdUsuario)  as Foto,
                        (select Archivos from t_notas_servicio where Id = tns.Id)  as Archivos,
                            tns.Nota,
                            tns.Fecha 
                    FROM t_notas_servicio tns where tns.IdEstatus in(10,13,6)
                    and tns.IdServicio = '" . $servicio . "'
                    UNION
                    SELECT 
                            tnso.Id,
                            nombreUsuario(tnso.IdUsuario) as Usuario, 
                            (select if(UrlFoto is null, '/assets/img/user-5.jpg', UrlFoto) from t_rh_personal where IdUsuario = tnso.IdUsuario)  as Foto,
                        (select if(Archivos is not null, '', Archivos) from t_notas_servicio where Id = tnso.Id)  as Archivos,
                            tnso.Nota,
                        tnso.Fecha
                    FROM t_notas_solicitudes tnso 
                    WHERE tnso.IdSolicitud = '" . $idSolicitud . "') AS TABLAS ORDER BY TABLAS.Fecha DESC";
        $notas = $this->DBS->consultaGeneral($sentencia);

        $htmlNotas = '';
        if (empty($notas)) {
            $htmlNotas = 'No existe ninguna conversación.';
        } else {
            foreach ($notas as $key => $value) {
                $htmlArchivos = '';
                if ($value['Archivos'] !== '' && $value['Archivos'] !== NULL) {
                    $htmlArchivos .= '';
                    $archivos = explode(",", $value['Archivos']);
                    foreach ($archivos as $k => $v) {
                        $pathInfo = pathinfo($v);
                        if (array_key_exists("extension", $pathInfo)) {
                            switch (strtolower($pathInfo['extension'])) {
                                case 'doc': case 'docx':
                                    $scr = '/assets/img/Iconos/word_icon.png';
                                    break;
                                case 'xls': case 'xlsx':
                                    $scr = '/assets/img/Iconos/excel_icon.png';
                                    break;
                                case 'pdf':
                                    $scr = '/assets/img/Iconos/pdf_icon.png';
                                    break;
                                case 'jpg': case 'jpeg': case 'bmp': case 'gif': case 'png':
                                    $scr = $v;
                                    break;
                                default :
                                    $scr = '/assets/img/Iconos/file_icon.png';
                                    break;
                            }
                        } else {
                            $scr = '/assets/img/Iconos/file_icon.png';
                        }
                        $htmlArchivos .= ''
                                . '<div class="evidencia">'
                                . ' <a class="m-l-5 m-r-5" href="' . $v . '" data-lightbox="image-' . $value['Id'] . '" data-title="' . $pathInfo['basename'] . '">'
                                . '     <img src="' . $scr . '" style="max-height:115px !important;" alt="' . $pathInfo['basename'] . '"  />'
                                . '     <p class="m-t-0">' . $pathInfo['basename'] . '</p>'
                                . ' </a>'
                                . '</div>';
                    }
                }

                $fecha = strftime('%A %e de %B, %G ', strtotime($value['Fecha'])) . date("h:ma", strtotime($value['Fecha']));
                $htmlNotas .= ''
                        . '<li class="media media-sm">'
                        . ' <a href="javascript:;" class="pull-left">'
                        . '     <img src="' . $value['Foto'] . '" alt="" class="media-object rounded-corner">'
                        . ' </a>'
                        . ' <div class="media-body">'
                        . '     <h5 class="media-heading">' . $value['Usuario'] . '</h5>'
                        . '     <h6 class="f-w-600">' . $fecha . '</h6>'
                        . '     <p class="f-w-600">' . $value['Nota'] . '</p>'
                        . '     ' . $htmlArchivos
                        . ' </div>'
                        . '</li>';
            }
        }

        return $htmlNotas;
    }

    public function getNotasByServicio(string $servicio, string $idSolicitud = NULL) {
        $htmlNotas = $this->getListaNotas($servicio, $idSolicitud);
        $htmlNotas = ($htmlNotas !== '') ? $htmlNotas : '<h5>No hay notas para mostrar</h5>';
        $html = ''
                . '<div class="row>">'
                . ' <div class="col-md-12 col-xs-12">'
                . '     <a href="javascript:;" id="btnAgregarNota" class="btn bg-green btn-success pull-right">'
                . '         <i class="fa fa-plus pull-left"></i>'
                . '         Agregar nota'
                . '	</a>'
                . ' </div>'
                . '</div>'
                . '<div class="row hidden" id="divFormAgregarNota">'
                . ' <div class="col-md-12 col-xs-12">'
                . '     <div class="row">'
                . '         <div class="col-md-12 col-xs-12">'
                . '             <h3>Agregar nota</h3>'
                . '             <div class="underline"></div>'
                . '         </div>'
                . '     </div>'
                . '     <form id="formAgregarNotas">'
                . '     <div class="row m-t-20">'
                . '         <div class="col-md-12 col-xs-12">'
                . '             <div class="form-group">'
                . '                 <label>Nota *</label>'
                . '                 <textarea id="txtAgregarNotas" class="form-control" rows="3" placeholder="Ingresa la nota ....."></textarea>'
                . '             </div>'
                . '         </div>'
                . '     </div>'
                . '     <div class="row">'
                . '         <div class="col-md-12">'
                . '             <div class="form-group">'
                . '                 <label>Agregar Archivos o Imagenes</label>'
                . '                 <input id="archivosAgregarNotas"  name="archivosAgregarNotas[]" type="file" multiple/>'
                . '             </div>'
                . '         </div>'
                . '     </div>'
                . '     <div class="row">'
                . '         <div class="col-md-12">'
                . '             <div id="errorAgregarNotaServicio"></div>'
                . '         </div>'
                . '     </div>'
                . '     <div class="row">'
                . '         <div class="col-md-12 col-xs-12 text-center">'
                . '             <a id="btnConfirmarAgregarNota" class="btn btn-success" >'
                . '                 <i class="fa fa-floppy-o"></i> Guardar Nota'
                . '             </a>'
                . '             <a id="btnCancelarAgregarNota" class="btn btn-danger">'
                . '                 <i class="fa fa-ban"></i> Cancelar'
                . '             </a>'
                . '         </div>'
                . '     </div>'
                . '     </form>'
                . ' </div>'
                . '</div>'
                . '<div class="row">'
                . ' <div class="col-md-12 col-xs-12">'
                . '     <h3>Conversaciones del servicio</h3>'
                . '     <div class="underline"></div>'
                . ' </div>'
                . '</div>'
                . '<div class="row">'
                . ' <div class="col-md-12">'
                . '     <div id="errorAgregarCorrectoNota"></div>'
                . ' </div>'
                . '</div>'
                . '<div class="row m-t-20">'
                . ' <div class="col-md-12 col-xs-12">'
                . '     <div id="divNotasServicio">'
                . '         <div class="height-sm">'
                . '             <ul id="ulListaNotas" class="media-list media-list-with-divider media-messaging">'
                . '                 ' . $htmlNotas
                . '             </ul>'
                . '         </div>'
                . '     </div>'
                . ' </div>'
                . '</div>';

        return $html;
    }

    public function actualizaNotas(array $datos) {
        $idSolicitud = $this->DBS->consultaGeneral('SELECT IdSolicitud FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        return ['html' => $this->getListaNotas($datos['servicio'], $idSolicitud[0]['IdSolicitud'])];
    }

    /*
     * Encargado de generar notas del servicio.
     * 
     */

    public function setNotaServicio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasNota/';
            $archivos = setMultiplesArchivos($CI, 'archivosAgregarNotas', $carpeta);
            if (!empty($archivos)) {
                $cont = 0;
                $host = $_SERVER['SERVER_NAME'];
                $linkImagenes = 'IMAGENES';
                foreach ($archivos as $value) {
                    $cont++;
                    $linkImagenes .= "<div><a href='http://" . $host . $value . "'>Archivo" . $cont . "</a></div>";
                }
                $archivos = implode(',', $archivos);
                $nuevo = $this->DBS->setNuevoElemento('t_notas_servicio', array(
                    'IdUsuario' => $usuario['Id'],
                    'IdServicio' => $datos['servicio'],
                    'Nota' => $datos['observaciones'],
                    'Archivos' => $archivos,
                    'Fecha' => $fechaCaptura
                        )
                );
                $datosResolucion = "<div>" . $fechaCaptura . "</div><div>NOTA</div><div>" . $datos['observaciones'] . "</div>" . $linkImagenes;
            }
        } else {
            $nuevo = $this->DBS->setNuevoElemento('t_notas_servicio', array(
                'IdUsuario' => $usuario['Id'],
                'IdServicio' => $datos['servicio'],
                'Nota' => $datos['observaciones'],
                'Fecha' => $fechaCaptura
                    )
            );
            $datosResolucion = "<div>" . $fechaCaptura . "</div><div>NOTA</div><div>" . $datos['observaciones'] . "</div>";
        }
        if (!empty($nuevo)) {
            return $nuevo;
        } else {
            return FALSE;
        }
    }

    public function setNotaServicioSolicitud(string $servicio, string $idSolicitud) {
        return $this->DBS->consultaGeneral('SELECT * FROM (SELECT 
                                                            tns.Id,
                                                            nombreUsuario(tns.IdUsuario) as Usuario,
                                                            (select Archivos from t_notas_servicio where Id = tns.Id)  as Archivos,
                                                            tns.Nota,
                                                            tns.Fecha 
                                                    FROM t_notas_servicio tns where tns.IdEstatus in(10,13,6)
                                                    and tns.IdServicio = "' . $servicio . '"
                                                    UNION
                                                    SELECT 
                                                            tnso.Id,
                                                            nombreUsuario(tnso.IdUsuario) as Usuario,
                                                            (select if(Archivos is not null, "", Archivos) from t_notas_servicio where Id = tnso.Id)  as Archivos,                                                            
                                                            tnso.Nota,
                                                            tnso.Fecha
                                                    FROM t_notas_solicitudes tnso 
                                                    WHERE tnso.IdSolicitud = "' . $idSolicitud . '")
                                                    AS TABLAS ORDER BY TABLAS.Fecha DESC');
    }

}