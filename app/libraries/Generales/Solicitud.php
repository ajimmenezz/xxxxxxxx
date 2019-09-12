<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Solicitud
 *
 * @author Freddy
 */
class Solicitud extends General {

    private $DBS;
    private $Notificacion;
    private $Catalogo;
    private $Ticket;
    private $Servicio;
    private $ServiceDesk;
    private $Correo;
    private $SegundoPlano;
    private $InformacionServicios;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Ticket = \Librerias\Generales\Ticket::factory();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->SegundoPlano = \Modelos\Modelo_SegundoPlano::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /*
     * Encargada de obtener los departamentos del las areas
     * 
     * @return array Regresa la lista de todos los departamentos
     */

    public function getDepartamentos() {
        return $this->Catalogo->catDepartamentos('3', array('Flag' => '1'));
    }

    /*
     * Encargada de obtener las solicitudes que genero el usuario.
     * 
     * @return array Regresa la lista de solicitus que ha generado el usuario las cuales esten abiertas o rechazadas
     */

    public function getSolicitudesGeneradas() {
        $usuario = $this->Usuario->getDatosUsuario();
        $usuarioId = ' AND ts.Solicita = "' . $usuario['Id'] . '"';

        if (in_array('77', $usuario['PermisosAdicionales'])) {
            $usuarioId = '';
        } else if (in_array('77', $usuario['Permisos'])) {
            $usuarioId = '';
        }
        return $this->DBS->getSolicitudes('
            select 
                ts.Id as Numero, 
                ts.Ticket,
                ts.FechaCreacion as Fecha, 
                estatus(ts.IdEstatus) as Estatus,
                (select Nombre from cat_v3_departamentos_siccob where Id = ts.IdDepartamento) as Departamento,
                ts.IdPrioridad,	
                tsi.Asunto
            from t_solicitudes ts left join t_solicitudes_internas tsi
            on ts.Id = tsi.IdSolicitud
            where ts.IdEstatus in (1,2,10) and ts.IdTipoSolicitud not in (4) ' . $usuarioId . ' order by ts.Id desc
        ');
    }

    /*
     * Encargada de obtenere las solicitudes que se levantaron para el area.
     * 
     * @return array Regresa las solicitudes que este asignado al departamento y tengan un estatus abierto
     */

    public function getSolicitudesAsignadas() {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['SolicitudesSD'] = array();
        //No eliminar se ocupara despues
//        if (!empty($usuario['SDKey'])) {
//            $data['SolicitudesSD'] = $this->setSolicitudesSD($usuario['SDKey'], $usuario);
//        }

        $data['solicitudes'] = $this->DBS->getSolicitudes('
            select 
                ts.Id as Numero,
                tipoSolicitud(ts.IdTipoSolicitud) as Tipo,
                ts.Ticket,
                ts.Folio,
                ts.FechaCreacion as Fecha, 
                estatus(ts.IdEstatus) as Estatus,
                nombreUsuario(ts.Solicita) as Solicita, 
                tsi.Asunto,
                sucursal(ts.IdSucursal) Sucursal
            from t_solicitudes ts 
            left join t_solicitudes_internas tsi
            on ts.Id = tsi.IdSolicitud
            where ts.IdDepartamento = ' . $usuario['IdDepartamento'] . ' and ts.IdEstatus = 1');
        return $data;
    }

    /*
     * Encargada de obtener las solicitudes que requeiren autorizacion. Donde carga la información siempre y cuando el usuario
     * cuente con los permisos para obtener la información.
     * 
     * @return array Regresa las solicitudes que tengan un estatus 'Sin autorizar' y sean de tipo Personal o Material.
     */

    public function getSolicitudesAurtorizacion() {
        /*
         * En arreglo Permisos se define los permisos que van a tener acceso para las solicitudes  donde la key es el tipo de solicitud y el valor
         * el permiso asociado
         */
        $permisos = array('1' => '33', '2' => '34');
        $tiposSolicitud = array();
        $usuario = $this->Usuario->getDatosUsuario();
        foreach ($permisos as $key => $value) {
            if (in_array($value, $usuario['Permisos'])) {
                array_push($tiposSolicitud, $key);
            } else if (in_array($value, $usuario['PermisosAdicionales'])) {
                array_push($tiposSolicitud, $key);
            }
        }

        if (!empty($tiposSolicitud)) {
            $tipo = implode(',', $tiposSolicitud);
            $consulta = $this->DBS->getSolicitudes('
                select 
                    ts.Id as Numero, 
                    ts.FechaCreacion as Fecha,
                    departamento(ts.IdDepartamento) as Departamento,
                    usuario(ts.Solicita) as Solicita,
                    estatus(ts.IdEstatus) as Estatus,
                    tipoSolicitud(ts.IdTipoSolicitud) as Tipo,
                    tsi.Asunto
                from t_solicitudes ts 	
                inner join t_solicitudes_internas tsi
                on tsi.IdSolicitud = ts.Id
                where ts.IdTipoSolicitud in (' . $tipo . ') and ts.IdEstatus = 9 
                or (select count(*) from t_material_proyecto where IdSolicitud = ts.Id and IdEstatus = 9)>0    
                     ');
            if (!empty($consulta)) {
                return $consulta;
            } else {
                return 'Sin Solicitudes';
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de generar una nueva solicitud interna o por sistema
     * 
     * @param array $datos Recibe los valores de tipo, departamento y descripción
     * 
     * @return string Regresa el numero de solicitud que se genero en caso de no se ejecute regresa un false.
     */

    public function solicitudNueva(array $datos, string $sistemaExterno = null, string $folioSD = null) {
        $archivos = null;
        $data = array();
        $CI = parent::getCI();
        $usuario = $this->Usuario->getDatosUsuario();

        if ($datos['departamento'] === 'sinDepartamento') {
            $datos['departamento'] = '7';
        } else if ($datos['departamento'] === '30') {
            $datos['departamento'] = $usuario['IdDepartamento'];
        }

        $ticket = 'null';

        if (isset($datos['ticket']) && $ticket != "") {
            if (!empty($_FILES)) {
                $ticket = '(select Ticket from t_servicios_ticket where Id = "' . $datos['servicio'] . '")';
            } else {
                $ticket = $datos['ticket'];
            }
        } else {
            if (isset($datos['servicio'])) {
                $ticket = '(select Ticket from t_servicios_ticket where Id = "' . $datos['servicio'] . '")';
            }
        }

        if (empty($datos['fechaProgramada'])) {
            $fechaProgramada = NULL;
            $textoNoficacionFechaProgramada = '';
        } else {
            $fechaProgramada = $datos['fechaProgramada'];
            $textoNoficacionFechaProgramada = ' Atender después del día <b>' . $datos['fechaProgramada'] . '</b>.';
        }

        if (empty($datos['fechaLimiteAtencion'])) {
            $fechaLimiteAtencion = NULL;
            $textoNotificacionFechaLimite = '';
        } else {
            $fechaLimiteAtencion = $datos['fechaLimiteAtencion'];
            $textoNotificacionFechaLimite = ' La fecha límite para atender es <b>' . $datos['fechaLimiteAtencion'] . '</b>.';
        }

        //Se genera la solicitud donde se define si es por SD o por un usuario
        if (!empty($sistemaExterno)) {
            $solicitudNueva = 'insert t_solicitudes set
                Ticket = ' . $ticket . ',
                IdTipoSolicitud = ' . $datos['tipo'] . ',
                IdEstatus = 1,
                IdDepartamento = ' . $datos['departamento'] . ',
                IdPrioridad = ' . $datos['prioridad'] . ',
                Folio = ' . $folioSD . ',
                FechaCreacion = now(),
                Solicita = ' . $sistemaExterno;
        } else {
            if (isset($datos['servicio'])) {
                $servicio = $datos['servicio'];
                if ($datos['folio'] !== '') {
                    $folio = ',Folio = ' . $datos['folio'];
                } else {
                    $folio = ',Folio = folioByServicio("' . $servicio . '")';
                }
            } else {
                if (isset($datos['folio'])) {
                    if ($datos['folio'] !== '') {
                        $folio = ',Folio = ' . $datos['folio'];
                    } else {
                        $folio = '';
                    }
                } else {
                    $folio = '';
                }
                $servicio = '';
            }

            if (isset($datos['sucursal'])) {
                $sucursal = $datos['sucursal'];
            } else {
                $sucursal = NULL;
            }

            $solicitudNueva = 'insert t_solicitudes set 
                Ticket = ' . $ticket . ',
                IdTipoSolicitud = ' . $datos['tipo'] . ',
                IdEstatus = 1,
                IdDepartamento = ' . $datos['departamento'] . ',
                IdPrioridad = ' . $datos['prioridad'] . ',
                FechaCreacion = now(),
                Solicita = ' . $usuario['Id'] . ', 
                IdServicioOrigen = "' . $servicio . '", 
                IdSucursal = "' . $sucursal . '",
                FechaTentativa = "' . $fechaProgramada . '",
                FechaLimite = "' . $fechaLimiteAtencion . '"'
                    . $folio;
        }

        $this->eliminarSolicitudSinDatos();
        //Genera Solicitud  
        $numeroSolicitud = $this->DBS->setSolicitud($solicitudNueva);
        $carpeta = 'solicitudes/' . $numeroSolicitud . '/';

        //Valida si existen archivos
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciasSolicitud', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            } else {
                return 'otraImagen';
            }

            if (isset($datos['correo'])) {
                $correoCopia = array($datos['correo']);
            }
        } else {
            if (isset($datos['correo'])) {
                $correoCopia = $datos['correo'];
            }
        }

        //Guarda los detalles de la solicitud segun el tipo de solicitud
        if ($datos['tipo'] === '3' || $datos['tipo'] === '4') {
            if ($this->setSolicitudInterna($numeroSolicitud, $datos['descripcion'], $datos['asunto'], $archivos)) {
                if (isset($datos['folio'])) {
                    if ($datos['folio'] !== '') {
                        $stringFolio = '<br>Folio: <b class="f-s-16">' . $datos['folio'] . '</b>';
                    } else {
                        $stringFolio = '';
                    }
                } else {
                    $stringFolio = '';
                }

                if (isset($datos['sucursal'])) {
                    if ($datos['sucursal'] !== '') {
                        $consultaSucursal = $this->DBS->getSolicitudes('SELECT Nombre FROM cat_v3_sucursales WHERE Id = "' . $datos['sucursal'] . '"');
                        $stringSucursal = '<br>Sucursal: <b class="f-s-16">' . $consultaSucursal[0]['Nombre'] . '</b>';
                    } else {
                        $stringSucursal = '';
                    }
                } else {
                    $stringSucursal = '';
                }

                $solicitante = $this->DBS->getDatosSolicitante($usuario['Id']);

                $detallesSolicitud = $this->linkDetallesSolicitud($numeroSolicitud);
                $linkDetallesSolicitud = '<br>Ver Detalles de la Solicitud <a href="' . $detallesSolicitud . '" target="_blank">Aquí</a>';

                $this->enviarNotificacion(array(
                    'Departamento' => $datos['departamento'],
                    'remitente' => $usuario['Id'],
                    'tipo' => '3',
                    'descripcion' => 'Se ha generado la solicitud <b class="f-s-16">' . $numeroSolicitud . '</b> la cual requiere de su pronta atención.',
                    'titulo' => 'Nueva Solicitud',
                    'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> levantó la solicitud <b class="f-s-16">' . $numeroSolicitud . '</b>.' . $stringFolio . $stringSucursal . $textoNoficacionFechaProgramada . $textoNotificacionFechaLimite . '
                                    <br>' . $linkDetallesSolicitud . '<br><br>
                                    Asunto: <p><b>' . $datos['asunto'] . '</b> </p><br>
                                    Con la siguiente descripción:<br> <p><b>' . $datos['descripcion'] . '</b> </p><br>
                                    Favor de atender en breve.',
                    'idSolicitud' => $numeroSolicitud
                ));
                $texto = '<p>Estimado(a) <strong>' . $solicitante['Nombre'] . ',</strong> se le ha mandado la información de la solicitud que ha creado.<br>Número de solicitud: <strong>' . $numeroSolicitud . '</strong>.' . $stringFolio . $stringSucursal . '</p></b>' . $linkDetallesSolicitud . '<br><br>
                            Asunto: <p><b>' . $datos['asunto'] . '</b> </p><br><br>
                            Con la siguiente descripción: <p><b>' . $datos['descripcion'] . '</b> </p>';
                $mensaje = $this->Correo->mensajeCorreo('Solicitud Creada', $texto);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($solicitante['EmailCorporativo']), 'Solicitud Creada', $mensaje);

                if ($datos['departamento'] === '15' || $datos['departamento'] === '17' || $datos['departamento'] === '43') {
                    $this->enviarNotificacion(array(
                        'Departamento' => '16',
                        'remitente' => $usuario['Id'],
                        'tipo' => '3',
                        'descripcion' => 'Se ha generado la solicitud <b class="f-s-16">' . $numeroSolicitud . '</b> al Área de compras.',
                        'titulo' => 'Nueva Solicitud para Compras',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> levantó la solicitud <b class="f-s-16">' . $numeroSolicitud . '</b>.' . $stringFolio . $stringSucursal . $textoNoficacionFechaProgramada . $textoNotificacionFechaLimite . '<br>
                         Con la siguiente descripción:<br> <p><b>' . $datos['descripcion'] . '</b> </p><br>
                         Favor de anticiparse.'
                    ));
                }

                if (isset($datos['correo'])) {
                    $textoCopia = '<p>Se le ha mando una copia de la información de la Solicitud creada por  <strong>' . $usuario['Nombre'] . '</strong>.<br>Número de solicitud: <strong>' . $numeroSolicitud . '</strong>.</p></b>' . $linkDetallesSolicitud . '<br><br>
                            Asunto: <p><b>' . $datos['asunto'] . '</b> </p><br><br>
                            Con la siguiente descripción: <p><b>' . $datos['descripcion'] . '</b> </p>';
                    $mensajeCopia = $this->Correo->mensajeCorreo('Copia Solicitud Creada', $textoCopia);
                    $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correoCopia, 'Copia Solicitud Creada', $mensajeCopia);
                }

                return $numeroSolicitud;
            } else {
                return FALSE;
            }
        } else if ($datos['tipo'] === '5') {
            $this->enviarNotificacion(array(
                'Departamento' => $datos['departamento'],
                'remitente' => '40',
                'tipo' => '13',
                'descripcion' => 'Se ha generado la solicitud <b class="f-s-16">' . $numeroSolicitud . '</b> por parte del sistema de ServiceDesk del folio ' . $folioSD . '. Se requiere de su pronta atención.',
                'titulo' => 'Nueva Solicitud',
                'mensaje' => 'El sistema Service Desk levantó el folio <b class="f-s-16">' . $folioSD . '</b>.<br>
                         Por lo que se genero la solicitud <b>' . $numeroSolicitud . '</b><br>
                         Favor de atender en breve.'
            ));
            return $numeroSolicitud;
        }
    }

    private function eliminarSolicitudSinDatos() {
        $IdSolicitud = $this->DBS->consultaGral('SELECT MAX(Id) AS Id FROM t_solicitudes');
        $IdSolicitudInterna = $this->DBS->consultaGral('SELECT IdSolicitud FROM t_solicitudes_internas WHERE IdSolicitud = "' . $IdSolicitud[0]['Id'] . '"');
        if (empty($IdSolicitudInterna)) {
            $this->DBS->eliminarSolicitud('t_solicitudes', array('Id' => $IdSolicitud[0]['Id']));
        }
    }

    /*
     * Encargada de generar la solicitud interna
     * 
     * @param string $IdSolicitud Recibe el numero de solictud que se genero
     * @param string $asuntp Recibe la Asunto de la solicitud.
     * @param string $descripcion Recibe la descricpion de la solicitud.
     * @param string $evidencias Recibe las rutas de la evidencias que se agregaron a la solicitud. Esta puede ser definida o no.
     * 
     * @return boolean regresa true si se inserto con exito o false de caso contrario
     */

    private function setSolicitudInterna(string $IdSolicitud, string $descripcion, string $asunto, string $evidencias = null) {
        if (!empty($evidencias)) {
            $consulta = $this->DBS->setDatosSolicitudInternas('t_solicitudes_internas', array('IdSolicitud' => $IdSolicitud, 'Descripcion' => $descripcion, 'Asunto' => $asunto, 'Evidencias' => $evidencias));
        } else {
            $consulta = $this->DBS->setDatosSolicitudInternas('t_solicitudes_internas', array('IdSolicitud' => $IdSolicitud, 'Descripcion' => $descripcion, 'Asunto' => $asunto));
        }
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de eliminar el evidencias de una solicitud. Esto se realiza atravez del plugin fileupload.
     * Donde se actualiza la solicitud una vez eliminada la evidencia.
     * 
     * @param array $evidencias Recibe el id de la solicitud y key del nombre del archivo
     * @return boolean Regresa true si se elimino y false en caso contrario.
     */

    public function eliminarEvidencia(array $evidencias) {
        $datosSolicitud = $this->DBS->getDatosSolicitud($evidencias['id']);
        $archivos = explode(',', $datosSolicitud['detalles'][0]['Evidencias']);

        foreach ($archivos as $key => $value) {
            if ($evidencias['key'] === $value) {
                unset($archivos[$key]);
            }
        }
        if (eliminarArchivo($evidencias['key'])) {
            $consulta = $this->DBS->actualizarSolicitud(
                    't_solicitudes_internas', array('Evidencias' => implode(',', $archivos)), array('IdSolicitud' => $evidencias['id'])
            );
            if (!empty($consulta)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /*
     * Encargada de obtener los datos de la solicitud
     * 
     * @param array $datos Recibe los siguientes valores el numero de la solicitud y la operacion que requiere
     * 
     * @return array Regresa la información de la solicitud y con los datos que requiera
     */

    public function getDatosSolicitud(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $datosSolicitud = $this->DBS->getDatosSolicitud($datos['solicitud']);
        $data['datos'] = $datosSolicitud;
        $data['servicios'] = $this->Catalogo->catServiciosDepartamento('3', array('departamento' => $usuario['IdDepartamento']));
        $data['atiende'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => $datosSolicitud['IdDepartamento']));
        $data['cliente'] = $this->clientesActivos();

        if (in_array('187', $usuario['PermisosAdicionales'])) {
            $data['autorizacionAtenderServicio'] = TRUE;
        } elseif (in_array('187', $usuario['Permisos'])) {
            $data['autorizacionAtenderServicio'] = TRUE;
        } else {
            $data['autorizacionAtenderServicio'] = FALSE;
        }
        if ($datosSolicitud['TipoSolicitud'] === '1') {
            //Datos solicitud Personal
            if ($datos['operacion'] === '1') {
                //Solo muestra la informacion de la solicitud para la seccion Solicitud asignada
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAsignadaSolicitudPersonal', $data, TRUE);
            } else if ($datos['operacion'] === '2') {
                //Regresa la formulario para editar solicitud en la seccion autorizacion                
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAutorizacionSolicitudPersonalProyecto', $data, TRUE);
            }
        } else if ($datosSolicitud['TipoSolicitud'] === '2') {
            //Datos solicitud material
            if ($datos['operacion'] === '1') {
                //Solo muestra la informacion de la solicitud para la seccion Solicitud asignada                                       
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAsignadaSolicitudMaterial', $data, TRUE);
            } else if ($datos['operacion'] === '2') {
                //Regresa la formulario para editar solicitud en la seccion autorizacion
                $listaMaterial = $data['datos']['detalles'];
                $data['datos']['detalles'] = array();
                foreach ($listaMaterial as $value) {
                    if ($value['IdEstatus'] === '9') {
                        array_push($data['datos']['detalles'], $value);
                    }
                }
                $data['Linea'] = $this->Catalogo->catLineaMaterial('3');
                $data['Material'] = $this->Catalogo->catMaterial('3');
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAutorizacionSolicitudMaterialProyecto', $data, TRUE);
            }
        } else if ($datosSolicitud['TipoSolicitud'] === '3' || $datosSolicitud['TipoSolicitud'] === '4') {
            //Datos solicitud Internas
            $data['datos']['detalles'][0]['Descripcion'] = strip_tags($datosSolicitud['detalles'][0]['Descripcion']);
            $data['usuarios'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'));
            $data['areas'] = $this->Catalogo->catAreas('3', array('Flag' => '1'));
            $prioridades = $this->Catalogo->catPrioridades('3');
            $data['prioridades'] = [];
            foreach ($prioridades as $key => $value) {
                array_push($data['prioridades'], ['id' => $value['Id'], 'text' => $value['Nombre']]);
            }
            $data['departamentos'] = $this->getDepartamentos();
            $data['sucursales'] = $this->Catalogo->catSucursales('3', array('Flag' => '1'));
            if ($datos['operacion'] === '1') {
                //Solo muestra la informacion de la solicitud para la seccion Solicitud asignada 
                $data['evidenciasUrl'] = explode(',', $datosSolicitud['detalles'][0]['Evidencias']);
                $data['notas'] = $this->DBS->getNotasSolicitud($datos['solicitud']);
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAsignadaSolicitudInterna', $data, true);
            } else if ($datos['operacion'] === '2') {
                //Regresa la formulario para editar solicitud utilizado para la seccion Autorizacxion
            }
        } else if ($datosSolicitud['TipoSolicitud'] === '5' || $datosSolicitud['TipoSolicitud'] === '6') {
            //Datos solicitud Service Desk
            if ($datos['operacion'] === '1') {
                //Solo muestra la informacion de la solicitud para la seccion Solicitud asignada                                
                if (!empty($datosSolicitud['Folio'])) {
                    if (!empty($usuario['SDKey'])) {
                        $apiKey = $usuario['SDKey'];
                    } else {
                        $apiKey = $this->DBS->getApiKeyMesaAyuda();
                    }

                    $data['datosSD'] = $this->ServiceDesk->getDetallesFolio($apiKey, $datosSolicitud['Folio']);
                    $data['datosResolucionSD'] = $this->ServiceDesk->getResolucionFolio($apiKey, $datosSolicitud['Folio']);

                    if (isset($data['datosSD']->WORKORDERID)) {
                        $data['sucursales'] = $this->DBS->consulta("select Id, IdCliente, Nombre, NombreCinemex from cat_v3_sucursales where IdCliente = 1 and Flag = 1 order by Nombre");
                    } else {
                        $data['sucursales'] = $this->DBS->consulta("select Id, IdCliente, Nombre, NombreCinemex from cat_v3_sucursales where Flag = 1 order by Nombre");
                    }
                }
                $data['usuarioApiKey'] = $usuario['SDKey'];
                $data['formularioSolicitud'] = parent::getCI()->load->view('Generales/Modal/formularioAsignadaSolicitudSistemasExternos', $data, TRUE);
            } else if ($datos['operacion'] === '2') {
                //Regresa la formulario para editar solicitud en la seccion autorizacion                
            } else if ($datos['operacion'] === '3') {
                //Obtiene tecnicos del sistmea de service desk
                $data['tecnicosSD'] = $this->getTecnicosSistemaSD($usuario);
            }
        }

        $arrayServicios = $this->Servicio->getServiciosBySolicitud($datos['solicitud'], TRUE);

        $htmlSeguimiento = ''
                . '<div class="col-md-12 col-xs-12" >'
                . ' <div class="row">'
                . '     <div class="col-md-12 col-xs-12">'
                . '         <h3>Servicios relacionados</h3>'
                . '         <div class="underline"></div>'
                . '     </div>'
                . ' </div>'
                . ' <div class="table-responsive">'
                . '         <table id="data-table-servicios-relacionados" class="table table-hover table-striped table-bordered no-wrap " style="cursor:pointer" width="100%">'
                . '             <thead>'
                . '                 <tr>'
                . '                     <th class="never">IDServicio</th>'
                . '                     <th class="all">Ticket</th>'
                . '                     <th class="all">Servicio</th>'
                . '                     <th class="all">Fecha</th>'
                . '                     <th class="all">Descripción</th>'
                . '                     <th class="all">Estatus</th>'
                . '                     <th class="all">Solicita</th>'
                . '                     <th class="all">Atiende</th>'
                . '                 </tr>'
                . '             </thead>'
                . '             <tbody>';
        if ($arrayServicios !== FALSE) {
            foreach ($arrayServicios as $key => $value) {
                $htmlSeguimiento .= ''
                        . '<tr>'
                        . ' <td>' . $value['Id'] . '</td>'
                        . ' <td>' . $value['Ticket'] . '</td>'
                        . ' <td>' . $value['Servicio'] . '</td>'
                        . ' <td>' . $value['FechaCreacion'] . '</td>'
                        . ' <td>' . $value['Descripcion'] . '</td>'
                        . ' <td>' . $value['NombreEstatus'] . '</td>'
                        . ' <td>' . $value['Solicita'] . '</td>'
                        . ' <td>' . $value['Atiende'] . '</td>'
                        . '</tr>';
            }
        }

        $htmlSeguimiento .= ''
                . '             </tbody>'
                . '         </table>'
                . '     </div>'
                . '</div>';

        $data['htmlSeguimiento'] = $htmlSeguimiento;

        return $data;
    }

    /*
     * Encargada de generar un ticket, servicios y solicitud
     * 
     * @param array $datos Recibe la solicitud, ticket y los servicios que se generaron para la solictud.
     * 
     * @return array Regresa el ticket, los numeros de servicio y la lista de solicitudes asiganadas al departamento.
     * 
     */

    public function generarTicket(array $datos) {
        $foliosServicios = array();
        $ticket = null;
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosSolicitud = $this->DBS->getDatosSolicitud($datos['solicitud']);
        $usuario = $this->Usuario->getDatosUsuario();
        $solicitante = $this->DBS->getDatosSolicitante($datosSolicitud['Solicita']);

        if (empty($datos['ticket']) || is_null($datos['ticket'])) {
            $ticket = $this->Ticket->setTicket($datosSolicitud, $datos);

            if ($ticket <= 0 || $ticket > 400000) {
                return false;
            } else {
                $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
                    'IdEstatus' => '2',
                    'Ticket' => $ticket,
                    'FechaRevision' => $fecha,
                    'Atiende' => $usuario['Id']
                        ), array('Id' => $datos['solicitud']));
            }
        } else {
            $ticket = $datos['ticket'];

            if ($ticket <= 0 || $ticket > 400000) {
                return false;
            } else {
                $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
                    'IdEstatus' => '2',
                    'Ticket' => $ticket,
                    'FechaRevision' => $fecha,
                    'Atiende' => $usuario['Id']
                        ), array('Id' => $datos['solicitud']));
            }
        }

        if (!empty($consulta)) {
            $historico = $this->DBS->setHistoricoSolicitud(
                    array(
                        'IdSolicitud' => $datos['solicitud'],
                        'IdDepartamento' => $datosSolicitud['IdDepartamento'],
                        'IdEstatus' => '2',
                        'IdUsuarioModifica' => $usuario['Id'],
                        'FechaModifica' => $fecha
            ));

            if (!empty($historico)) {
                foreach ($datos['servicios'] as $value) {
                    array_push($foliosServicios, $this->Servicio->setServicio(array(
                                'Ticket' => $ticket,
                                'IdSolicitud' => $datos['solicitud'],
                                'IdTipoServicio' => $value['servicio'],
                                'IdSucursal' => $value['sucursal'],
                                'IdEstatus' => '1',
                                'Solicita' => $usuario['Id'],
                                'Atiende' => $value['atiende'],
                                'FechaCreacion' => $fecha,
                                'Descripcion' => $value['descripcion']
                                    ), $value['nombreServicio']));
                }

                $detallesSolicitud = $this->linkDetallesSolicitud($datos['solicitud']);
                $linkDetallesSolicitud = '<br>Ver Detalles de la Solicitud <a href="' . $detallesSolicitud . '" target="_blank">Aquí</a>';

                $this->enviarNotificacion(array(
                    'Departamento' => $solicitante['IdDepartamento'],
                    'remitente' => $usuario['Id'],
                    'tipo' => '3',
                    'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ya es atendida por ' . $usuario['Nombre'] . ' del ticket ' . $ticket,
                    'titulo' => 'Seguimiento de Solicitud',
                    'mensaje' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> del ticket ' . $ticket . ' ya esta siendo atendida por el usuario <b>' . $usuario['Nombre'] . '</b>.'
                    . '             <br>' . $linkDetallesSolicitud . '<br><br>
                                    Asunto: <p><b>' . $datosSolicitud['detalles'][0]['Asunto'] . '</b> </p><br>
                                    Descripción:<br> <p><b>' . $datosSolicitud['detalles'][0]['Descripcion'] . '</b> </p>'
                    , $solicitante));
                return array('ticket' => $ticket, 'folios' => $foliosServicios, 'solicitudes' => $this->getSolicitudesAsignadas());
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de generar la notificacion
     * 
     * @param array $datos Recibe los valores de el departamento, remitente, tipo y descripcion para la notificación
     * @param array $atiende Recibe los datos del usuario al que sera enviada la notificación.
     * 
     */

    private function enviarNotificacion(array $datos, array $atiende = null) {
        $usuario = $this->Usuario->getDatosUsuario();
        $data['departamento'] = $datos['Departamento'];
        $data['remitente'] = $datos['remitente'];
        $data['tipo'] = $datos['tipo'];
        $data['descripcion'] = $datos['descripcion'];

        if (isset($datos['idSolicitud'])) {
            $data['idSolicitud'] = $datos['idSolicitud'];
        }

        $this->Notificacion->setNuevaNotificacion($data, $datos['titulo'], $datos['mensaje'], $atiende);

//        if($usuario['Id'] === '92'){
//            $data['destinatario'] = '9';
//            $this->Notificacion->enviarNotificacionEspecifica($data, $datos['titulo'], $datos['mensaje']);
//            $data['destinatario'] = '8';
//            $this->Notificacion->enviarNotificacionEspecifica($data, $datos['titulo'], $datos['mensaje']);
//        }

        if ($data['departamento'] === '7') {
            $data['destinatario'] = '9';
            $this->Notificacion->enviarNotificacionEspecifica($data, $datos['titulo'], $datos['mensaje']);
            $data['destinatario'] = '8';
            $this->Notificacion->enviarNotificacionEspecifica($data, $datos['titulo'], $datos['mensaje']);
        }
    }

    /*
     * Encargada de actualizar la solicitud y definir sus detalles
     * 
     * @param array $datos Recibe los datos que se van actualizar y la operacion que se efectua.
     * @return array Regresa un arreglo con la informacion de la operacion realizada.
     */

    public function actualizarDatosSolicitud(array $datos) {
        $data = array();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosSolicitud = $this->DBS->getDatosSolicitud($datos['solicitud']);
        $usuario = $this->Usuario->getDatosUsuario();
        $solicitante = $this->DBS->getDatosSolicitante($datosSolicitud['Solicita']);
        switch ($datosSolicitud['TipoSolicitud']) {
            //Solicitud Personal Proyecto
            case '1':
                if ($datos['operacion'] === '1') {
                    //Actualiza información
                    $data['respuesta'] = $this->actualizarSolicitudPersonal($datos, $usuario, $solicitante);
                } else if ($datos['operacion'] === '2') {
                    //Autoriza solicitud
                    $data['solicitudes'] = $this->autorizarSolicitud($datos, $usuario, $fecha, $datosSolicitud, $solicitante, '1');
                } else if ($datos['operacion'] === '3') {
                    //No autoriza la solicitud
                    $data['solicitudes'] = $this->noAutorizarSolicitud($datos, $usuario, $fecha, $datosSolicitud, $solicitante, '1');
                }
                break;
            //Solicitud Material Proyecto
            case '2':
                if ($datos['operacion'] === '1') {
                    //Actualiza información
                    $data['respuesta'] = $this->actualizarSolicitudMaterial($datos, $usuario, $datosSolicitud, $solicitante, $fecha);
                } else if ($datos['operacion'] === '2') {
                    //Autoriza solicitud
                    $data['solicitudes'] = $this->autorizarSolicitud($datos, $usuario, $fecha, $datosSolicitud, $solicitante, '2');
                } else if ($datos['operacion'] === '3') {
                    //No autoriza la solicitud
                    $data['solicitudes'] = $this->noAutorizarSolicitud($datos, $usuario, $fecha, $datosSolicitud, $solicitante, '2');
                }
                break;
            //Solicitud Nueva Interna
            case '3':
                if ($datos['operacion'] === '1') {
                    //Actualiza información                    
                    $evidencias = explode(',', $datosSolicitud['detalles'][0]['Evidencias']);
                    $data['solicitudes'] = $this->actualizarSolicitudInterna($datos, $evidencias, $usuario, $datosSolicitud, $fecha);
                } else if ($datos['operacion'] === '2') {
                    //Autoriza solicitud
                } else if ($datos['operacion'] === '3') {
                    //No autoriza la solicitud
                } else if ($datos['operacion'] === '4') {
                    //Rechazar solicitud
                    $data['solicitudes'] = $this->rechazarSolictud($datos, $usuario, $datosSolicitud, $solicitante, $fecha);
                } else if ($datos['operacion'] === '5') {
                    //Reasignar solicitud
                    $data['solicitudes'] = $this->reasignarSolicitud($datos, $usuario, $datosSolicitud, $fecha);
                } else if ($datos['operacion'] === '6') {
                    $data['solicitudes'] = $this->cancelarSolicitudInterna($datos, $datosSolicitud, $usuario, $fecha);
                }
                break;
            case '5':
                if ($datos['operacion'] === '4') {
                    //Reasigna folio en sistema externo SD                                        
                    $data['solicitudes'] = $this->rechazarFolioSistemaSD($datos, $usuario, $datosSolicitud, $fecha);
                }
                break;
        }

        return $data;
    }

    /*
     * Encargada de actualizar la informacion de los detalles de una solicitud de personal
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param array $solicitante Recibe los datos del usuario que genero la solicitud.
     * @return boolean Regresa true si se realizo con exito la actualización de lo contrario un false.
     */

    private function actualizarSolicitudPersonal(array $datos, array $usuario, array $solicitante) {
        foreach ($datos['datos'] as $value) {
            if ($value['name'] === 'perfilPersonal') {
                $actualizar = $this->DBS->actualizarSolicitud('t_personal_proyecto', array(
                    'DescripcionPerfil' => $value['valor'],
                    'IdUsuarioModifica' => $usuario['Id']
                        ), array('IdSolicitud' => $datos['solicitud']));

                if (!empty($actualizar)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datosSolicitud['IdDepartamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '4',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ha sido actualiza por el usuario ' . $usuario['Nombre'],
                        'titulo' => 'Solicitud Actualizada',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a actualizado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.<br>                        
                                Favor de validar nueva informción de la solicitud.'
                            ), $solicitante);
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
    }

    /*
     * Encargada de actualizar el material de una solicitud de material.
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param array $solicitante Recibe los datos del usuario que genero la solicitud.
     * @param string $fecha Recibe la fecha actual del sistema.
     * @return boolean Regresa true si se realizo con exito la actualización de lo contrario un false.
     */

    private function actualizarSolicitudMaterial(array $datos, array $usuario, array $datosSolicitud, array $solicitante, string $fecha) {
        $consulta = $this->DBS->eliminarMaterialSolicitud(array('IdSolicitud' => $datos['solicitud']));
        if ($consulta) {
            foreach ($datos['datos'] as $value) {
                foreach ($value as $key => $valor) {
                    if ($key === 'datosTabla') {
                        foreach ($valor as $item) {
                            $actualizacion = $this->DBS->actualizarSolicitudMaterial(array(
                                'IdSolicitud' => $datos['solicitud'],
                                'IdProyecto' => $datos['proyecto'],
                                'IdMaterial' => $item[0],
                                'Cantidad' => $item[3],
                                'IdRecibe' => '0',
                                'IdUsuarioModifica' => $usuario['Id'],
                                'IdEstatus' => '9',
                                    ), array(
                                'IdVersion' => $datos['version'] + 1,
                                'FechaModificacion' => $fecha,
                                'IdSolicitud' => $datos['solicitud'],
                                'IdProyecto' => $datos['proyecto'],
                                'IdMaterial' => $item[0],
                                'Cantidad' => $item[3],
                                'IdUsuarioModifica' => $usuario['Id'],
                                'IdEstatus' => '9'
                            ));
                        }
                    }
                }
            }
            $this->enviarNotificacion(array(
                'Departamento' => $datosSolicitud['IdDepartamento'],
                'remitente' => $usuario['Id'],
                'tipo' => '4',
                'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ha sido actualiza por el usuario ' . $usuario['Nombre'],
                'titulo' => 'Solicitud Actualizada',
                'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a actualizado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.<br>                        
                        Favor de validar nueva informción de la solicitud.'
                    ), $solicitante);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de actualizar la solicitud interna
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $evidencias  recibe una arreglo con los evidencias de la solicitud.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param string $fecha Recibe la fecha actual del sistema.
     * @return array Regresa la lista de las solicitudes generadas.
     */

    private function actualizarSolicitudInterna(array $datos, array $evidencias, array $usuario, array $datosSolicitud, string $fecha) {
        $actualizacion = FALSE;
        $CI = parent::getCI();
        $carpeta = 'solicitudes/' . $datos['solicitud'] . '/';
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if ($datos['departamento'] === 'sinDepartamento') {
            $datos['departamento'] = '7';
        }

        if ($datosSolicitud['IdEstatus'] === '10') {
            $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array('IdEstatus' => '1'), array('Id' => $datos['solicitud']));
        }


        if ($datosSolicitud['IdDepartamento'] !== $datos['departamento'] || $datosSolicitud['IdPrioridad'] !== $datos['prioridad']) {
            $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
                'IdDepartamento' => $datos['departamento'],
                'IdPrioridad' => $datos['prioridad'],
                'FechaCreacion' => $fecha), array('Id' => $datos['solicitud']));

            if (!empty($consulta)) {
                $consulta = $this->DBS->setHistoricoSolicitud(
                        array(
                            'IdSolicitud' => $datos['solicitud'],
                            'IdDepartamento' => $datos['departamento'],
                            'IdEstatus' => $datosSolicitud['IdEstatus'],
                            'IdUsuarioModifica' => $usuario['Id'],
                            'FechaModifica' => $fecha
                ));
                if (!empty($consulta)) {
                    $actualizacion = TRUE;
                }
            }
        }

        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciasSolicitud', $carpeta);

            foreach ($archivos as $key => $value) {
                array_push($evidencias, $value);
            }

            $archivos = implode(',', $evidencias);

            $comaIncial = strpos($archivos, ',');

            if ($comaIncial === 0) {
                $archivos = substr($archivos, 1);
            }

            $consulta = $this->DBS->actualizarSolicitud(
                    't_solicitudes_internas', array(
                'Descripcion' => $datos['descripcion'],
                'Asunto' => $datos['asunto'],
                'Evidencias' => $archivos), array('IdSolicitud' => $datos['solicitud']));

            if (!empty($consulta)) {
                $actualizacion = TRUE;
            }
        } else {
            $consulta = $this->DBS->actualizarSolicitud(
                    't_solicitudes_internas', array(
                'Descripcion' => $datos['descripcion'],
                'Asunto' => $datos['asunto']), array('IdSolicitud' => $datos['solicitud']));

            if (!empty($consulta)) {
                $actualizacion = TRUE;
            }
        }

        $consultaSolicitud = $this->DBS->actualizarSolicitud(
                't_solicitudes', array(
            'Folio' => $datos['folio'],
            'IdSucursal' => $datos['sucursal'],
            'FechaCreacion' => $fecha,
            'FechaTentativa' => $datos['fechaProgramada'],
            'FechaLimite' => $datos['fechaLimiteAtencion']), array('Id' => $datos['solicitud']));

        if (!empty($consultaSolicitud)) {
            $actualizacion = TRUE;
        }

        if ($actualizacion) {

            if (empty($datos['fechaProgramada'])) {
                $textoNoficacionFechaProgramada = '';
            } else {
                $textoNoficacionFechaProgramada = ' Atender después del día <b>' . $datos['fechaProgramada'] . '</b>.';
            }

            if (empty($datos['fechaLimiteAtencion'])) {
                $textoNotificacionFechaLimite = '';
            } else {
                $textoNotificacionFechaLimite = ' La fecha límite para atender es <b>' . $datos['fechaLimiteAtencion'] . '</b>.';
            }

            $this->enviarNotificacion(array(
                'Departamento' => $datos['departamento'],
                'remitente' => $usuario['Id'],
                'tipo' => '4',
                'descripcion' => 'Se ha actualizado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> la cual requiere de su pronta atención.',
                'titulo' => 'Solicitud Actualizada',
                'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> actualizo la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.' . $textoNoficacionFechaProgramada . $textoNotificacionFechaLimite . '<br>
                        El cual puedo haber reasignado el area o modificado la información de la solicitud.<br>
                        Favor de validar la nueva actualización.'
            ));

            return $this->getSolicitudesGeneradas();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de acutalizar el estatus de la solicitud como autorizada
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param string $fecha Recibe la fecha actual del sistema.
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param array $solicitante Recibe los datos del usuario que genero la solicitud.
     * @param string $tipo Recibe el tipo de solicitud.
     * @return array Regresa la lista de las solicitudes que requieren autorización..
     */

    private function autorizarSolicitud(array $datos, array $usuario, string $fecha, array $datosSolicitud, array $solicitante, string $tipo) {
        if ($tipo === '1') {
            $departamento = '3';
        } else if ($tipo === '2') {
            $departamento = '12';
        }

        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
            'IdEstatus' => '1',
            'IdDepartamento' => $departamento,
            'FechaAutorizacion' => $fecha,
            'Autoriza' => $usuario['Id']
                ), array('Id' => $datos['solicitud']));
        if (!empty($consulta)) {
            $proyecto = $datosSolicitud['detalles'][0]['IdProyecto'];
            if ($tipo === '2') {
                $material = $this->DBS->eliminarMaterialSolicitud(array('IdSolicitud' => $datos['solicitud']));
                if (!empty($material)) {
                    foreach ($datosSolicitud['detalles'] as $value) {
                        $actualizacion = $this->DBS->actualizarSolicitudMaterial(array(
                            'IdSolicitud' => $value['IdSolicitud'],
                            'IdProyecto' => $value['IdProyecto'],
                            'IdMaterial' => $value['IdMaterial'],
                            'Cantidad' => $value['Cantidad'],
                            'IdRecibe' => '0',
                            'IdUsuarioModifica' => $usuario['Id'],
                            'IdEstatus' => '7',
                            'FechaEstatus' => $fecha
                                ), array(
                            'IdVersion' => $value['Version'] + 1,
                            'FechaModificacion' => $fecha,
                            'IdSolicitud' => $value['IdSolicitud'],
                            'IdProyecto' => $value['IdProyecto'],
                            'IdMaterial' => $value['IdMaterial'],
                            'Cantidad' => $value['Cantidad'],
                            'IdUsuarioModifica' => $usuario['Id'],
                            'IdEstatus' => '7',
                            'FechaEstatus' => $fecha
                        ));
                    }
                }
            }
            $historico = $this->DBS->setHistoricoSolicitud(array(
                'IdSolicitud' => $datos['solicitud'],
                'IdDepartamento' => $departamento,
                'IdEstatus' => '1',
                'IdUsuarioModifica' => $usuario['Id'],
                'FechaModifica' => $fecha
            ));
            $this->enviarNotificacion(array(
                'Departamento' => $departamento,
                'remitente' => $usuario['Id'],
                'tipo' => '3',
                'descripcion' => 'Se ha generado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> donde ya fue autorizada por ' . $usuario['Nombre'] . '.Se requiere de su pronta atención.',
                'titulo' => 'Nueva Solicitud',
                'mensaje' => 'Se levantó la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>. La cual fue autorizada por <b>' . $usuario['Nombre'] . '</b>.<br>
                        La solicitud corresponde al proyecto ' . $proyecto . ' por lo que se requiere su pronta atención.'
            ));
            $this->enviarNotificacion(array(
                'Departamento' => $datosSolicitud['IdDepartamento'],
                'remitente' => $usuario['Id'],
                'tipo' => '11',
                'descripcion' => 'Se ha autorizado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>  por ' . $usuario['Nombre'] . ' donde ya fue notificada al área correspondiente.',
                'titulo' => 'Solicitud Autorizada',
                'mensaje' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ya fue autorizada por <b>' . $usuario['Nombre'] . '</b>.<br>
                        Por lo que ya se notifico al área de correspondiente para su seguimiento.'
                    ), $solicitante);
            return $this->getSolicitudesAurtorizacion();
        }
    }

    /*
     * Encargada de acutalizar el estatus de la solicitud como no autorizada
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param string $fecha Recibe la fecha actual del sistema.
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param array $solicitante Recibe los datos del usuario que genero la solicitud.
     * @param string $tipo Recibe el tipo de solicitud.
     * @return array Regresa la lista de las solicitudes que requieren autorización.
     */

    private function noAutorizarSolicitud(array $datos, array $usuario, string $fecha, array $datosSolicitud, array $solicitante, string $tipo) {
        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
            'IdEstatus' => '8',
            'FechaAutorizacion' => $fecha,
            'Autoriza' => $usuario['Id']
                ), array('Id' => $datos['solicitud']));
        if (!empty($consulta)) {
            if ($tipo === '2') {
                $material = $this->DBS->eliminarMaterialSolicitud(array('IdSolicitud' => $datos['solicitud']));
                foreach ($datosSolicitud['detalles'] as $value) {
                    $proyecto = $value['IdProyecto'];
                    $actualizacion = $this->DBS->actualizarSolicitudMaterial(array(
                        'IdSolicitud' => $value['IdSolicitud'],
                        'IdProyecto' => $value['IdProyecto'],
                        'IdMaterial' => $value['IdMaterial'],
                        'Cantidad' => $value['Cantidad'],
                        'IdRecibe' => '0',
                        'IdUsuarioModifica' => $usuario['Id'],
                        'IdEstatus' => '8',
                        'FechaEstatus' => $fecha
                            ), array(
                        'IdVersion' => $value['Version'] + 1,
                        'FechaModificacion' => $fecha,
                        'IdSolicitud' => $value['IdSolicitud'],
                        'IdProyecto' => $value['IdProyecto'],
                        'IdMaterial' => $value['IdMaterial'],
                        'Cantidad' => $value['Cantidad'],
                        'IdUsuarioModifica' => $usuario['Id'],
                        'IdEstatus' => '8',
                        'FechaEstatus' => $fecha
                    ));
                }
            }

            $historico = $this->DBS->setHistoricoSolicitud(array(
                'IdSolicitud' => $datos['solicitud'],
                'IdDepartamento' => $datosSolicitud['IdDepartamento'],
                'IdEstatus' => '8',
                'IdUsuarioModifica' => $usuario['Id'],
                'FechaModifica' => $fecha
            ));

            if (!empty($historico)) {
                $nota = $this->DBS->setNotasSolicitud(array(
                    'IdSolicitud' => $datos['solicitud'],
                    'IdEstatus' => '8',
                    'IdUsuario' => $usuario['Id'],
                    'Nota' => $datos['descripcion'],
                    'Fecha' => $fecha
                ));
                if (!empty($nota)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datosSolicitud['IdDepartamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '12',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> no fue autorizada  por  el usuario ' . $usuario['Nombre'] . ' .',
                        'titulo' => 'Solicitud No Autorizada',
                        'mensaje' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> no fue autorizada por <b>' . $usuario['Nombre'] . '</b>.<br>
                        Por la siguiente causa: ' . $datos['descripcion'] . '<br>
                        Favor de validar la solicitud rechazada.'
                            ), $solicitante);
                    return $this->getSolicitudesAurtorizacion();
                }
            }
        }
    }

    /*
     * Encargada de rechazar una solicitud
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param array $solicitante Recibe los datos del usuario que genero la solicitud.
     * @param string $fecha Recibe la fecha actual del sistema.
     * @return array Regresa la lista de las solicitudes asignadas.
     */

    private function rechazarSolictud(array $datos, array $usuario, array $datosSolicitud, array $solicitante, string $fecha) {
        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
            'IdEstatus' => '10'
                ), array('Id' => $datos['solicitud']));
        if (!empty($consulta)) {
            $historico = $this->DBS->setHistoricoSolicitud(
                    array(
                        'IdSolicitud' => $datos['solicitud'],
                        'IdDepartamento' => $datosSolicitud['IdDepartamento'],
                        'IdEstatus' => '10',
                        'IdUsuarioModifica' => $usuario['Id'],
                        'FechaModifica' => $fecha
            ));

            if (!empty($historico)) {

                $notas = $this->DBS->setNotasSolicitud(array(
                    'IdSolicitud' => $datos['solicitud'],
                    'IdEstatus' => '10',
                    'IdUsuario' => $usuario['Id'],
                    'Nota' => $datos['descripcion'],
                    'Fecha' => $fecha
                ));

                if (!empty($notas)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datosSolicitud['IdDepartamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '8',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ha sido rechazada por el usuario ' . $usuario['Nombre'],
                        'titulo' => 'Solicitud Rechazada',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a rechazado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.<br>
                        Por el siguiente motivo: <br><strong>' . $datos['descripcion'] . '</strong><br>
                        Favor de validar la solicitud y brindarle seguimiento.'
                            ), $solicitante);
                    return $this->getSolicitudesAsignadas();
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de rechazar un folio del sistema externo SD
     * 
     */

    private function rechazarFolioSistemaSD(array $datos, array $usuario, array $datosSolicitud, string $fecha) {
        $resolucionVieja = $this->ServiceDesk->getResolucionFolio($usuario['SDKey'], $datosSolicitud['Folio']);
        $datos['descripcion'] = $datos['descripcion'] . '<br><br>' . $resolucionVieja->operation->Details->RESOLUTION;
        $resultadoResolucion = $this->ServiceDesk->resolucionFolioSD($datosSolicitud['Folio'], $datos['tecnicoSD'], $usuario['SDKey'], $datos['descripcion']);
        if ($resultadoResolucion->operation->result->status === 'Success') {
            $reasignacion = $this->ServiceDesk->reasignarFolioSD($datosSolicitud['Folio'], $datos['tecnicoSD'], $usuario['SDKey']);
            if ($reasignacion->operation->result->status === 'Success') {
                $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
                    'IdEstatus' => '10'
                        ), array('Id' => $datos['solicitud']));
                if (!empty($consulta)) {
                    $historico = $this->DBS->setHistoricoSolicitud(
                            array(
                                'IdSolicitud' => $datos['solicitud'],
                                'IdDepartamento' => $datosSolicitud['IdDepartamento'],
                                'IdEstatus' => '10',
                                'IdUsuarioModifica' => $usuario['Id'],
                                'FechaModifica' => $fecha
                    ));
                    if (!empty($historico)) {
                        $notas = $this->DBS->setNotasSolicitud(array(
                            'IdSolicitud' => $datos['solicitud'],
                            'IdEstatus' => '10',
                            'IdUsuario' => $usuario['Id'],
                            'Nota' => $datos['descripcion'],
                            'Fecha' => $fecha
                        ));
                        return $this->getSolicitudesAsignadas();
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            }
        }
    }

    /*
     * Encargada de actualizar la solicitud para reasignacion de departamento
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param string $fecha Recibe la fecha actual del sistema.
     * @return array Regresa la lista de las solicitudes asignadas.
     */

    private function reasignarSolicitud(array $datos, array $usuario, array $datosSolicitud, string $fecha) {
        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
            'IdDepartamento' => $datos['departamento']
                ), array('Id' => $datos['solicitud']));

        if (!empty($consulta)) {
            $historico = $this->DBS->setHistoricoSolicitud(
                    array(
                        'IdSolicitud' => $datos['solicitud'],
                        'IdDepartamento' => $datos['departamento'],
                        'IdEstatus' => $datosSolicitud['IdEstatus'],
                        'IdUsuarioModifica' => $usuario['Id'],
                        'FechaModifica' => $fecha
            ));
            if (!empty($historico)) {

                $notas = $this->DBS->setNotasSolicitud(array(
                    'IdSolicitud' => $datos['solicitud'],
                    'IdEstatus' => $datosSolicitud['IdEstatus'],
                    'IdUsuario' => $usuario['Id'],
                    'Nota' => $datos['descripcion'],
                    'Fecha' => $fecha
                ));

                if (!empty($notas)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datos['departamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '9',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ha sido reasignada por el usuario ' . $usuario['Nombre'],
                        'titulo' => 'Solicitud Reasignada',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a reasignado  la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.<br>
                        Por el siguiente motivo: <br><strong>' . $datos['descripcion'] . '</strong><br>
                        Favor de validar la solicitud y brindarle seguimiento.'
                    ));
                    return $this->getSolicitudesAsignadas();
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de Cancelar una Solicitud Interna
     * 
     * @param array $datos  recibe los datos que se van a actualizar.
     * @param array $datosSolicitud Recibe los datos de la solictitud que se va actualizar
     * @param array $usuario Recibe los datos del usuario que realiza que esta registrado en el sistema
     * @param string $fecha Recibe la fecha actual del sistema.
     * @return array Regresa la lista de las solicitudes generadas.
     */

    private function cancelarSolicitudInterna(array $datos, array $datosSolicitud, array $usuario, string $fecha) {

        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array('IdEstatus' => '6'), array('Id' => $datos['solicitud']));
        if (!empty($consulta)) {
            $historico = $this->DBS->setHistoricoSolicitud(
                    array(
                        'IdSolicitud' => $datos['solicitud'],
                        'IdDepartamento' => $datosSolicitud['IdDepartamento'],
                        'IdEstatus' => '6',
                        'IdUsuarioModifica' => $usuario['Id'],
                        'FechaModifica' => $fecha
            ));
            if (!empty($historico)) {
                $notas = $this->DBS->setNotasSolicitud(array(
                    'IdSolicitud' => $datos['solicitud'],
                    'IdEstatus' => '6',
                    'IdUsuario' => $usuario['Id'],
                    'Nota' => $datos['descripcion'],
                    'Fecha' => $fecha
                ));
                if (!empty($notas)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datosSolicitud['IdDepartamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '5',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> se ha cancelado por el usuario ' . $usuario['Nombre'],
                        'titulo' => 'Solicitud Cancelada',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a cancelado la solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b>.<br>
                        Favor de no dar seguimiento a la solicitud.'
                    ));
                    return $this->getSolicitudesGeneradas();
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar las solicitudes para el sistema de SD y obtener sus datos
     * 
     */

    private function setSolicitudesSD(string $SDKey, array $usuario) {
        $folios = array();
        $datosFolios = array();
        $solicitudesGeneradas = array();
        $foliosSD = $this->ServiceDesk->getFoliosTecnico($SDKey);

        if (isset($foliosSD->operation->details)) {
            foreach ($foliosSD->operation->details as $value) {
                array_push($datosFolios, array(
                    'folio' => $value->WORKORDERID,
                    'Asunto' => $value->SUBJECT,
                    'Solicitante' => $value->CREATEDBY
                ));
                array_push($folios, $value->WORKORDERID);
            }
            $foliosAdist2 = $this->DBS->getFoliosAdist2(implode(',', $folios));
            $foliosNuevos = array_diff($folios, $foliosAdist2);
            if (!empty($foliosNuevos)) {
                $foliosActualizados = $this->DBS->getSoliditudesRechadasSD('1', implode(',', $foliosNuevos));
                $foliosAbiertosSD = $this->DBS->getSoliditudesRechadasSD('4', implode(',', $foliosNuevos));
                if (count($foliosActualizados) > 0) {
                    $this->DBS->getSoliditudesRechadasSD('2', implode(',', $foliosActualizados));

                    foreach ($foliosActualizados as $value) {
                        $this->enviarNotificacion(array(
                            'Departamento' => $usuario['IdDepartamento'],
                            'remitente' => '40',
                            'tipo' => '14',
                            'descripcion' => 'Se le ha reasignado el folio <b class="f-s-16">' . $value . '</b> de ServiceDesk. Se requiere de su pronta atención.',
                            'titulo' => 'Nueva Solicitud',
                            'mensaje' => 'Se le ha reasignado el folio <b class="f-s-16">' . $value . '</b> de ServiceDesk, verifique las solicitudes asignadas.<br>
                         Favor de atender en breve.'
                        ));
                    }
                }

                $solicitudesSinFolio = $this->DBS->getSoliditudesRechadasSD('3', implode(',', $foliosNuevos));
                $solicitudesNuevas = array_diff($foliosNuevos, $solicitudesSinFolio);
                if (!empty($solicitudesNuevas)) {
                    foreach ($solicitudesNuevas as $value) {
                        $sistemaExterno = '40';
                        $solicitud = $this->solicitudNueva(array('tipo' => '5', 'departamento' => '30'), $sistemaExterno, $value);
                        foreach ($datosFolios as $folio) {
                            if ($value === $folio['folio']) {
                                array_push($solicitudesGeneradas, array('solicitud' => $solicitud, 'datos' => $folio));
                            }
                        }
                    }
                }
                if (!empty($foliosAbiertosSD)) {
                    foreach ($foliosAbiertosSD as $value) {
                        foreach ($datosFolios as $folio) {
                            if ($value['folio'] === ((string) $folio['folio'])) {
                                array_push($solicitudesGeneradas, array('solicitud' => $value['solicitud'], 'datos' => $folio));
                            }
                        }
                    }
                }
            }
        }
        return $solicitudesGeneradas;
    }

    /*
     * Encargado de rechazar un folio del sistema externo SD
     * 
     */

    private function getTecnicosSistemaSD(array $usuario) {
        return $this->ServiceDesk->getTecnicosSD($usuario['SDKey']);
    }

    /*
     * Encargado de obtener todos los folios de service desk
     * 
     */

    public function getFoliosServiceDesk() {
        return $this->ServiceDesk->getFolios('A8D6001B-EB63-4996-A158-1B968E19AB84');
    }

    public function getFormularioSolicitud(array $datos = []) {
        $usuario = $this->Usuario->getDatosUsuario();
        $formulario = [];

        $dataFolio = $this->DBS->consultaGral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datos['ticket'] . '"');

        $data = ['datos' => [
                'CatalogoUsuarios' => $this->Catalogo->catUsuarios('3', array('Flag' => '1')),
                'CatalogoAreas' => $this->Catalogo->catAreas('3', array('Flag' => '1')),
                'CatalogoPrioridades' => $this->Catalogo->catPrioridades('3'),
                'Apoyo' => (!empty($datos)) ? $datos['apoyo'] : false,
                'UsuariosSD' => $this->getTecnicosSistemaSD($usuario),
                'CatalogoClientesActivos' => $this->clientesActivos(),
                'Folio' => $dataFolio[0]['Folio']
            ]
        ];
        $formulario['html'] = parent::getCI()->load->view('Generales/Solicitud_Nueva', $data, TRUE);
        return $formulario;
    }

    public function buscarAreaDepartamento($datos) {
        return $this->DBS->consultaGral('SELECT
                                            cvas.Id AS Area, 
                                            cvds.Id AS Departamento 
                                         FROM cat_v3_areas_siccob cvas 
                                         INNER JOIN cat_v3_departamentos_siccob cvds 
                                            ON cvds.IdArea = cvas.Id 
                                         INNER JOIN cat_perfiles cp 
                                            ON cp.IdDepartamento = cvds.Id 
                                         WHERE cp.Id = "' . $datos['perfil'] . '"');
    }

    public function getUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $usuario['Id'];
    }

    public function guardarNotaSolicitud(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $arrayNotasSolicitud = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $arrayNotasSolicitud = array(
            'IdSolicitud' => $datos['solicitud'],
            'IdEstatus' => '13',
            'IdUsuario' => $usuario['Id'],
            'Nota' => $datos['observaciones'],
            'Fecha' => $fecha
        );
        $consulta = $this->DBS->setNotasSolicitud($arrayNotasSolicitud);
        if (!empty($consulta)) {
            return $this->DBS->getNotasSolicitud($datos['solicitud']);
        } else {
            return FALSE;
        }
    }

    public function editarFolio(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->InformacionServicios->getApiKeyByUser($usuario['Id']);
        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array('Folio' => $datos['folio']), array('Id' => $datos['solicitud']));
        if (!empty($consulta)) {
            if ($datos['folio'] !== '') {
                $datosSD = $this->ServiceDesk->getDetallesFolio($key, $datos['folio']);

                if (!isset($datosSD->operation->result->status)) {
                    if ($datosSD->STATUS !== 'Completado') {
                        $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'En Atención', $datos['folio']);
                    }
                }

                $datosSD = $this->InformacionServicios->datosSD($datos['solicitud']);
                return $datosSD;
            }
            return NULL;
        } else {
            return NULL;
        }
    }

    public function atencioSolicitudInterna(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosSolicitud = $this->DBS->getDatosSolicitud($datos['solicitud']);
        $usuario = $this->Usuario->getDatosUsuario();
        $solicitante = $this->DBS->getDatosSolicitante($datosSolicitud['Solicita']);
        $data = array('cliente' => '4', 'descripcion' => 'Se le da atención a la solicitud: ' . $datos['solicitud']);
        $ticket = $this->Ticket->setTicket($datosSolicitud, $data);

        $consulta = $this->DBS->actualizarSolicitud('t_solicitudes', array(
            'IdEstatus' => '2',
            'Ticket' => $ticket,
            'FechaRevision' => $fecha,
            'Atiende' => $usuario['Id']
                ), array('Id' => $datos['solicitud']));

        if (!empty($consulta)) {
            $this->DBS->setDatosSolicitudInternas('t_servicios_ticket', array(
                'Ticket' => $ticket,
                'IdSolicitud' => $datos['solicitud'],
                'IdTipoServicio' => '9',
                'IdEstatus' => '5',
                'Solicita' => $usuario['Id'],
                'Atiende' => $usuario['Id'],
                'FechaCreacion' => $fecha,
                'FechaInicio' => $fecha,
                'FechaConclusion' => $fecha,
                'Descripcion' => 'Servicio para la Solicitud: ' . $datos['solicitud']
            ));

            $this->enviarNotificacion(array(
                'Departamento' => $solicitante['IdDepartamento'],
                'remitente' => $usuario['Id'],
                'tipo' => '4',
                'descripcion' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> ya fue atendida por ' . $usuario['Nombre'] . ' del ticket ' . $ticket,
                'titulo' => 'Seguimiento de Solicitud',
                'mensaje' => 'La solicitud <b class="f-s-16">' . $datos['solicitud'] . '</b> del ticket ' . $ticket . ' ya fue atendida por el usuario <b>' . $usuario['Nombre'] . '</b>.'
                , $solicitante));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function linkDetallesSolicitud(string $solicitud) {
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $detallesSolicitud = 'https://siccob.solutions/Detalles/Solicitud/' . $solicitud;
        } else {
            $detallesSolicitud = 'http://' . $host . '/Detalles/Solicitud/' . $solicitud;
        }
        return $detallesSolicitud;
    }

    public function reasignarFolioSD(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->InformacionServicios->getApiKeyByUser($usuario['Id']);
        $folio = $this->DBS->consultaGral("SELECT folioByServicio('" . $datos['servicio'] . "') as Folio ");
        if ($folio[0]['Folio'] != '') {
            $this->ServiceDesk->reasignarFolioSD($folio[0]['Folio'], $datos['personalSD'], $key);
            $datosSD = $this->InformacionServicios->datosSD($datos['solicitud']);
            return ['code' => 200, 'message' => $datosSD];
        } else {
            throw new \Exception('No existe información para ese folio.');
        }
    }

    public function clientesActivos() {
        return $this->DBS->consultaGral('SELECT
                                            Id,
                                            Nombre
                                        FROM cat_v3_clientes
                                        WHERE Id IN(1,4,12,18,20)');
    }

    public function sucursalesCliente(array $datos) {
        return $this->DBS->consultaGral('SELECT * FROM cat_v3_sucursales WHERE IdCliente = "' . $datos['cliente'] . '" ORDER BY Nombre ASC');
    }

    public function concluirSolicitudesAbiertas() {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $solicitudes = $this->DBS->getFolioSolicitudesAbiertas();

        foreach ($solicitudes as $key => $value) {
            try {
                $detallesSD = $this->ServiceDesk->getDetallesFolio($usuario['SDKey'], $value['Folio']);
                if ($detallesSD->STATUS === 'Cerrado' || $detallesSD->STATUS === 'Completado') {
                    $resolucion = $this->ServiceDesk->getResolucionFolio($usuario['SDKey'], $value['Folio']);
                    
                    if (!empty($resolucion->operation->Details->RESOLUTION)) {
                        $textoResolucion = $resolucion->operation->Details->RESOLUTION;
                    } else {
                        $textoResolucion = 'Se concluye solicitud ya que esta concluido en SD';
                    }

                    $this->DBS->setNotasSolicitud(array(
                        'IdSolicitud' => $value['Id'],
                        'IdEstatus' => '4',
                        'IdUsuario' => $usuario['Id'],
                        'Nota' => $textoResolucion,
                        'Fecha' => $fecha));
                    
                    $textoReporte = 'Folio: ' . $value['Folio'] . ' Solicitud: ' . $value['Id'] . ' Fecha: ' . $fecha . ' Resolución: ' . $textoResolucion;
                    
                    $this->crearReporteSolicitudesConcluidas($textoReporte);
                    
                    $this->DBS->cambiarEstatusSolicitud(array('IdEstatus' => '4'), array('Id' => $value['Id']));
                }
            } catch (\Exception $ex) {
                
            }
        }
    }

    private function crearReporteSolicitudesConcluidas(string $contenido) {
        if (file_exists("./storage/Archivos/ReporteSolicitudesConcluidos/SolicitudesConcluidas.txt")) {
            $archivo = fopen("./storage/Archivos/ReporteSolicitudesConcluidos/SolicitudesConcluidas.txt", "a");
            fputs($archivo,chr(13).chr(10));
            fwrite($archivo, PHP_EOL . "$contenido");
            fclose($archivo);
        } else {
            $archivo = fopen("./storage/Archivos/ReporteSolicitudesConcluidos/SolicitudesConcluidas.txt", "w");
            fwrite($archivo, PHP_EOL . "$contenido");
            fclose($archivo);
        }
    }

}
