<?php

/**
 * Description of Controller_SegundoPlano
 *
 * @author Freddy
 */
class Controller_SegundoPlano extends \CI_Controller {

    private $DB;
    private $DBS;
    private $SD;
    private $mail;
    private $ubicaphone;
    private $informacionServicios;
    private $solicitud;
    private $sae;
    private $pruebas;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->DB = \Modelos\Modelo_SegundoPlano::factory();
        $this->DBS = \Modelos\Modelo_ServiceDesk::factory();
        $this->SD = \Librerias\WebServices\ServiceDesk::factory();
        $this->mail = \Librerias\Generales\Correo::factory();
        $this->ubicaphone = \Librerias\WebServices\Ubicaphone::factory();
        $this->informacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->solicitud = \Librerias\Generales\Solicitud::factory();
        $this->sae = \Librerias\SAEReports\Reportes::factory();
        $this->pruebas = \Librerias\Pruebas\Pruebas::factory();
    }

    public function actulizarTablaEquiposSae() {
        $materiales = $this->DB->obtenerMaterialSae();
        $this->DB->truncar('truncate tmp_cat_v3_equipos_sae');
        foreach ($materiales as $material) {
            $this->DB->insertar('tmp_cat_v3_equipos_sae', $material);
        }
        $this->DB->agregarMaterialFaltanteEquiposSae();
        echo 'Termino de actualizar material SAE en las base de datos adist3';
    }

    private function getViewFilterId($apiKey = '') {
        $filterId = $this->SD->getViewId('Todos Ingenieros', $apiKey);
        return $filterId;
    }

    public function getAllRequests() {
        $apiKey = $this->DB->getApiKeyByUser(2);
        $filterId = $this->getViewFilterId($apiKey);

        $requests = $this->SD->getRequestsByFilter($filterId, $apiKey);

        foreach ($requests as $key => $value) {
            $infoDatabase = $this->DB->getDatabaseInfoSD($value['folio']);
            if (!$infoDatabase) {
                $this->DB->insertInfoCambiosSD($value);
            } else {
                $infoDatabase = $infoDatabase[0];
                $destinatarios = ['ajimenez@siccob.com.mx'];
                $correos = $this->DB->buscarMailsBySDName($value['tecnico']);
                if ($correos) {
                    $correos = $correos[0];
                    if ($correos['Tecnico'] != '') {
                        array_push($destinatarios, $correos['Tecnico']);
                    }

                    if ($correos['Jefe'] != '') {
                        array_push($destinatarios, $correos['Jefe']);
                    }
                }

                $cambios = [];
                if ($infoDatabase['Creador'] != $value['creador']) {
                    $cambios['Creador'] = [
                        'antes' => $infoDatabase['Creador'],
                        'despues' => $value['creador']
                    ];
                }

                if ($infoDatabase['Solicitante'] != $value['solicita']) {
                    $cambios['Solicitante'] = [
                        'antes' => $infoDatabase['Solicitante'],
                        'despues' => $value['solicita']
                    ];
                }

                if ($infoDatabase['Tecnico'] != $value['tecnico']) {
                    $cambios['Tecnico'] = [
                        'antes' => $infoDatabase['Tecnico'],
                        'despues' => $value['tecnico']
                    ];

                    $correos = $this->DB->buscarMailsBySDName($infoDatabase['Tecnico']);
                    if ($correos) {
                        $correos = $correos[0];
                        if ($correos['Tecnico'] != '') {
                            array_push($destinatarios, $correos['Tecnico']);
                        }

                        if ($correos['Jefe'] != '') {
                            array_push($destinatarios, $correos['Jefe']);
                        }
                    }
                }

                if ($infoDatabase['Estatus'] != $value['estatus']) {
                    $cambios['Estatus'] = [
                        'antes' => $infoDatabase['Estatus'],
                        'despues' => $value['estatus']
                    ];
                }

                if ($infoDatabase['Prioridad'] != $value['prioridad']) {
                    $cambios['Prioridad'] = [
                        'antes' => $infoDatabase['Prioridad'],
                        'despues' => $value['prioridad']
                    ];
                }


                if ($infoDatabase['Asunto'] != $value['asunto']) {
                    $cambios['Asunto'] = [
                        'antes' => $infoDatabase['Asunto'],
                        'despues' => $value['asunto']
                    ];
                }

                $infoDatabase['FechaResolucion'] = ($infoDatabase['FechaResolucion'] == '0000-00-00 00:00:00') ? '' : $infoDatabase['FechaResolucion'];
                if ($infoDatabase['FechaResolucion'] != $value['fechaResolucion']) {

                    $cambios['FechaResolucion'] = [
                        'antes' => $infoDatabase['FechaResolucion'],
                        'despues' => $value['fechaResolucion']
                    ];

                    if ($infoDatabase['Resolucion'] != $value['resolucion']) {
                        $cambios['Resolucion'] = [
                            'antes' => $infoDatabase['Resolucion'],
                            'despues' => $value['resolucion']
                        ];
                    }
                }

                if ($infoDatabase['Solucionador'] != $value['resolver']) {
                    $cambios['Solucionador'] = [
                        'antes' => $infoDatabase['Solucionador'],
                        'despues' => $value['resolver']
                    ];
                }

                if (!empty($cambios)) {
                    $datos = $value;
                    $datos['fechaLectura'] = $infoDatabase['FechaLectura'];
                    $mensaje = ''
                            . '<p>Se han detectado algunos cambios en el Folio ' . $datos['folio'] . ' y es posible que pueda ser de su interés.</p>';
                    foreach ($cambios as $kc => $vc) {
                        switch ($kc) {
                            case 'Creador':
                                $mensaje .= '<p>Se cambio el campo "Creador" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Solicitante':
                                $mensaje .= '<p>Se cambio el campo "Solicitante" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Tecnico':
                                $mensaje .= '<p>Se cambio el campo "Técnico" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Estatus':
                                $mensaje .= '<p>Se cambio el campo "Estatus" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Prioridad':
                                $mensaje .= '<p>Se cambio el campo "Prioridad" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Asunto':
                                $mensaje .= '<p>Se cambio el campo "Asunto" de "' . $vc['antes'] . '" a "' . $vc['despues'] . '"</p>';
                                break;
                            case 'Resolucion':
                                $mensaje .= '<p>Se cambio el campo "Resolución". Por favor válide en ServiceDesk.</p>';
                                break;
                            case 'FechaResolucion':
                                $mensaje .= '<p>Se cambio el campo "Fecha de Resolucion". Por favor válide en ServiceDesk.</p>';
                                break;
                            case 'Solucionador':
                                $mensaje .= '<p>Se cambio el campo "Solucionador". Por favor válide en ServiceDesk.</p>';
                                break;
                        }
                    }

                    $mensaje .= '<p>Gracias por no responder este mensaje, es solo un bot que envía correos automáticos.</p>';
                    $mensaje .= '<p>Si desea alguna aclaración por favor contácte al área correspondiente.</p>';

                    $this->mail->enviarCorreo('notificaciones@siccob.solutions', $destinatarios, "Cambios en el Folio " . $datos['folio'] . " de SD", $mensaje);

                    $this->DB->insertInfoCambiosSD($datos);

                    //                    echo "***********************************************************************<br />"
                    //                        . "<pre style='text-color:red !important;'>", var_dump($mensaje), "</pre>"
                    //                        . "<br /<<br />";
                }
            }
        }
    }

    public function getAsignacionesSD() {
        date_default_timezone_set("America/Mexico_City");
        $apiKey = $this->DB->getApiKeyByUser('2');
        //var_dump($apiKey);
        $filterId = $this->getViewFilterId($apiKey);

        $requests = $this->SD->getRequestsByFilter($filterId, $apiKey, 0);

        $folios = '';
        $foliosSD = [];
        $foliosAdIST = [];
        $foliosSolicitudes = [];
        $foliosV2 = [];

        foreach ($requests as $key => $value) {
            $folios .= "," . $value->WORKORDERID;
            array_push($foliosSD, $value->WORKORDERID);
        }


        $foliosExistentes = $this->DB->getFoliosExistentesAsignacionesSD($folios);
        foreach ($foliosExistentes as $key => $value) {
            array_push($foliosAdIST, $value['Folio']);
        }


        $foliosEnSolitudes = $this->DB->getFoliosExistentesEnSolicitudes($folios);
        foreach ($foliosEnSolitudes as $key => $value) {
            array_push($foliosSolicitudes, $value['Folio']);
        }

        $foliosEnTicketsV2 = $this->DB->getFoliosExistentesEnV2($folios);
        foreach ($foliosEnTicketsV2 as $key => $value) {
            array_push($foliosV2, $value['Folio']);
        }

        $foliosParaSolicitudes = array_diff($foliosSD, $foliosSolicitudes);
        $foliosParaSolicitudes = array_diff($foliosParaSolicitudes, $foliosV2);

        $foliosParaRevisar = array_diff($foliosSD, $foliosAdIST);

        foreach ($foliosParaRevisar as $key => $value) {
            $details = $this->SD->getRequestDetails($value, $apiKey);
            $arrayInsert = [
                'Folio' => $details->WORKORDERID,
                'Creacion' => date('Y-m-d H:i:s', $details->CREATEDTIME / 1000),
                'Creador' => $details->CREATEDBY,
                'Solicitante' => $details->REQUESTER,
                'Prioridad' => $details->PRIORITY,
                'Asunto' => $details->SUBJECT,
                'Descripcion' => $details->SHORTDESCRIPTION,
                'Tecnico' => $details->TECHNICIAN,
                'Estatus' => $details->STATUS
            ];

            $this->DB->insertaAsignacionesSD($arrayInsert);

            //            echo "<pre>";
            //            var_dump($arrayInsert);
            //            var_dump($details->CREATEDTIME);
            //            echo "</pre>";
        }

        $cont = 0;
        echo "<pre>";
        var_dump($foliosParaSolicitudes);
        echo "</pre>";
        foreach ($foliosParaSolicitudes as $key => $value) {
            $cont++;
            if ($cont <= 1) {
                $correos = [];

                $details = $this->SD->getRequestDetails($value, $apiKey);
                $dataUsuario = $this->DB->consulta("select EmailCorporativo, (select IdDepartamento from cat_perfiles where Id = cu.IdPerfil) as IdDepartamento from cat_v3_usuarios cu where SDName = '" . $details->TECHNICIAN . "'");
                if (!empty($dataUsuario)) {
                    array_push($correos, $dataUsuario[0]['EmailCorporativo']);
                    $departamento = $dataUsuario[0]['IdDepartamento'];
                } else {
                    $departamento = 0;
                }

                $sucursal = $this->DB->consulta("select "
                        . "cs.Id, "
                        . "(select EmailCorporativo from cat_v3_usuarios where Id = cs.IdResponsable) as Email, "
                        . "(select EmailCorporativo from cat_v3_usuarios where Id = (select IdResponsableInterno from cat_v3_regiones_cliente where Id = cs.IdRegionCliente)) as EmailSupervisor "
                        . "from cat_v3_sucursales cs "
                        . "where NombreCinemex = '" . $details->CREATEDBY . "' "
                        . "or NombreCinemex = '" . $details->REQUESTER . "' limit 1");
                if (!empty($sucursal)) {
                    if (!in_array($sucursal[0]['Email'], ['', 'NULL'])) {
                        array_push($correos, $sucursal[0]['Email']);
                    }
                    if (!in_array($sucursal[0]['EmailSupervisor'], ['', 'NULL'])) {
                        array_push($correos, $sucursal[0]['EmailSupervisor']);
                    }
                    $sucursal = $sucursal[0]['Id'];
                } else {
                    $sucursal = 0;
                }
                $prioridad = '3';
                switch ($details->PRIORITY) {
                    case 'Alta':
                        $prioridad = 1;
                        break;
                    case 'Media':
                        $prioridad = 2;
                        break;
                    default:
                        $prioridad = 3;
                        break;
                }
                $arrayInsert = [
                    'IdTipoSolicitud' => '5',
                    'IdEstatus' => '1',
                    'IdDepartamento' => (!in_array($departamento, ['', 'NULL'])) ? $departamento : '11',
                    'IdSucursal' => $sucursal,
                    'IdPrioridad' => $prioridad,
                    'Folio' => $details->WORKORDERID,
                    'CreatedTime' => date('Y-m-d H:i:s', $details->CREATEDTIME / 1000),
                    'FechaCreacion' => date('Y-m-d H:i:s'),
                    'Solicita' => '1'
                ];

                $requesterText = 'Solicita: ' . $details->REQUESTER;

                $arrayInsertAsunto = [
                    'Asunto' => $details->WORKORDERID . ' - ' . $details->SUBJECT,
                    'Descripcion' => $requesterText . "  --  " . $details->SHORTDESCRIPTION
                ];

                $insertaSolicitud = $this->DB->insertaSolicitudesAdISTV3($arrayInsert, $arrayInsertAsunto);

                if ($insertaSolicitud) {
                    if (empty($correos)) {
                        $correosDB = $this->DB->consulta("select 
                                                        EmailCorporativo as Email
                                                        from cat_v3_usuarios 
                                                        where IdPerfil in (
                                                                select 
                                                                Id
                                                                from cat_perfiles
                                                                where IdDepartamento = 11
                                                        ) and Flag = 1 
                                                        and EmailCorporativo <> ''");
                        if (!empty($correosDB)) {
                            foreach ($correosDB as $key => $value) {
                                array_push($correos, $value['Email']);
                            }
                        }
                    }

                    //                    $correos = ['ajimenez@siccob.com.mx'];
                    $texto = '<p>Se ha generado una solicitud automática ligada al Folio: <strong>' . $arrayInsert['Folio'] . '</strong>.</p>'
                            . '<p><strong>Solicitante:</strong> ' . $details->REQUESTER . ' </p>'
                            . '<p><strong>Asunto:</strong> ' . $arrayInsertAsunto['Asunto'] . ' </p>'
                            . '<p><strong>Descripción:</strong> ' . $arrayInsertAsunto['Descripcion'] . ' </p>'
                            . '<br><br>';
                    $mensaje = $this->mail->mensajeCorreo('Nueva Solicitud por Folio ' . $arrayInsert['Folio'], $texto);
                    $this->mail->enviarCorreo('notificaciones@siccob.solutions', $correos, 'Nueva Solicitud por Folio ' . $arrayInsert['Folio'], $mensaje);
                }

                //                echo "<pre>";
                //                var_dump($details);
                //                echo "</pre>";
                //                echo "<pre>";
                //                var_dump($arrayInsert);
                //                echo "</pre>";
            }
        }

        //        echo $cont;
        //Se coloca en Completado los SD que anteriormente no cambio su estatus 
        $logSDCierres = $this->DBS->consultarFlagLogSDCierres();

        foreach ($logSDCierres as $key => $value) {
            $resultadoSD = $this->SD->cambiarEstatusServiceDesk($apiKey, 'Completado', $value['Folio']);

            if ($resultadoSD->operation->result->status === 'Success') {
                $this->DBS->actualizarFlagLogSDCierres($value['Folio']);
            } else {
                $this->informacionServicios->guardarLogSD($resultadoSD, $value['Folio']);
            }
        }
    }

    public function checkUbicaphoneEstatus() {
        //        $result = $this->ubicaphone->getAllDevices();
        //        $array = [];
        //        date_default_timezone_set("America/Mexico_City");
        //        foreach ($result as $key => $value) {
        //            $infoUser = $this->DB->getInfoUserByIMEI($value['imei']);
        //            $mail = '';
        //            $nombre = '';
        //            if (!empty($infoUser)) {
        //                $mail = $infoUser[0]['Email'];
        //                $nombre = $infoUser[0]['Usuario'];
        //            }
        //
        //            $t1 = strtotime(date('Y-m-d H:i:s', $value['timestamp']));
        //            $t2 = strtotime(date('Y-m-d H:i:s'));
        //
        //            $diff = $t2 - $t1;
        //            $hours = $diff / 3600;
        //
        //            if ($hours > 3 && $mail != '') {
        //                array_push($array, [
        //                    'nombre' => $nombre,
        //                    'mail' => $mail,
        //                    'hours' => $hours,
        //                    'imei' => $value['imei'],
        //                    'fecha' => date('Y-m-d H:i:s', $value['timestamp']),
        //                    'timestamp' => $value['timestamp'],
        //                    'usuario' => $value['alias'],
        //                    'lat' => $value['lat'],
        //                    'lng' => $value['lng'],
        //                    'direccion' => $value['street']
        //                ]);
        //            }
        //        }
        //
        //
        //
        //        echo "<pre>";
        //        var_dump($array);
        //        echo "</pre>";
    }

    public function getUbicaphoneGeofenceActivations() {
        //        $from = strtotime("2018-10-30 00:00:00");
        //        $to = strtotime("2018-10-30 23:59:59");
        //        $data = [
        //            'imei' => '351515080890249',
        //            'from' => $from,
        //            'to' => $to
        //        ];
        //        $result = $this->ubicaphone->getGeofenceActivations($data);
        //
        ////        echo "<pre>";
        ////        var_dump($result);
        ////        echo "</pre>";
        //
        //        $array = [];
        //        $origins = '19.3625308,-99.1851497';
        //        foreach ($result as $key => $value) {
        //            if ($value['eventType'] == 'GEO_IN_START') {
        //                $agregar = false;
        //
        //
        //                if (empty($array)) {
        //                    $agregar = true;
        //                } else if (end($array)['idGeocerca'] != $value['geofenceID'] && end($array)['timestamp'] != $value['timestamp'] && end($array)['lat'] != $value['lat'] && end($array)['lng'] != $value['lng']) {
        //                    $agregar = true;
        //                    $origins = end($array)['lat'] . ',' . end($array)['lng'];
        //                }
        //
        //
        //                if ($agregar) {
        //
        //                    $resultado = $this->Url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origins . "&destinations=" . $value['lat'] . "," . $value['lng'] . "&key=AIzaSyD3ELeFOp0xTOMrj2GDa9xNyzRuSbI-C3s";
        //                    $json = json_decode(@file_get_contents($resultado));
        //                    var_dump($json);
        //
        //                    array_push($array, [
        //                        'imei' => $value['deviceImei'],
        //                        'usuario' => $value['deviceName'],
        //                        'idGeocerca' => $value['geofenceID'],
        //                        'geocerca' => $value['geofenceName'],
        //                        'fecha' => date('Y-m-d H:i:s', $value['timestamp']),
        //                        'timestamp' => $value['timestamp'],
        //                        'lat' => $value['lat'],
        //                        'lng' => $value['lng'],
        //                        'direccion' => $value['street'],
        //                        'distance' => $json->rows[0]->elements[0]->distance->value,
        //                        'duration' => $json->rows[0]->elements[0]->duration->value
        //                    ]);
        //                }
        //            }
        //        }
        //
        //        echo "<pre>";
        //        var_dump($array);
        //        echo "</pre>";
    }

    public function cancelarPermisos() {
        $permisosPendientes = $this->DB->getPermisosSNArchivo();
    }

    public function enviarReportes() {
        $correosPoliza = $this->DB->getCorreosPoliza();

        $reporteFolios = $this->solicitud->getFolios();
        $reporteSemanal = $this->solicitud->getFoliosSemanal();


        $texto = '<p>Se han generado los reportes de la semana. </p>
                    <p><strong>Comparación de Folios Adist/SD: </strong> <a href="' . $reporteFolios["ruta"] . '">Reporte_Comparacion_Folios</a></p>
                    <p><strong>Reporte semanal de Folios: </strong> <a href="' . $reporteSemanal["ruta"] . '">Lista_Folios</a></p>
                    <br><br>';
        $mensaje = $this->mail->mensajeCorreo(' Reportes de la Semana ', $texto);

        $this->mail->enviarCorreo('notificaciones@siccob.solutions', $correosPoliza[0], 'Reportes de Folios', $mensaje);
    }

    public function updateRequestWithSDInfo() {
        $this->solicitud->updateRequestWithSDInfo();
    }

    public function getComprobantesPagoSAE7() {
        $this->sae->getComprobantesPagoSAE7();
    }

    public function getPersonalSiccob() {
        $this->pruebas->getActivePersonal();
    }

    public function setNotificacionesSLA() {
        $datosTicket = $this->DB->getTicketValidacion();

        foreach ($datosTicket as $key => $value) {
            if ($value['LocalForaneo'] === 'SLALocal') {
                $localForaneo = 'Local';
            } else {
                $localForaneo = 'Foraneo';
            }

            $datosPrioridades = $this->DB->catalogo_Prioridades(array(
                'LocalForaneo' => $value['LocalForaneo'],
                'IdPrioridad' => $value['IdPrioridad'],
                'TextoLocalForaneo' => $localForaneo));
            $tiempo = $datosPrioridades[0]['tiempo'];
            $tiempoSegundaNotificacion = $tiempo - $datosPrioridades[0]['segundosSegundaNotificacion'];
            $tiempoTerceraNotificacion = $tiempo - $datosPrioridades[0]['segundosTerceraNotificacion'];
            $correoTecnico = $this->DB->consulta("SELECT EmailCorporativo FROM cat_v3_usuarios where ID = '" . $value['Atiende'] . "'");
            $datosSupervisor = $this->DB->consulta('SELECT 
                                                (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = cvrc.IdResponsableInterno) AS CorreoSupervisor,
                                                usuario(cvrc.IdResponsableInterno) NombreSupervisor
                                            FROM 
                                                cat_v3_sucursales cvs
                                            INNER JOIN cat_v3_regiones_cliente cvrc
                                                ON cvrc.Id = cvs.IdRegionCliente
                                            WHERE cvs.Id = "' . $value['IdSucursal'] . '"');

            if ($value['TiempoTranscurrido'] >= $datosPrioridades[0]['segundosPrimeraNotificacion'] && empty($value['NumeroNotificacion'])) {
                $numeroNotificacion = 'Notificación num. 1';
                $this->DB->setTChekingTicket(array('Folio' => $value['Folio'], 'NumeroNotificacion' => 2));
            } elseif ($value['TiempoTranscurrido'] >= $tiempoSegundaNotificacion && $value['NumeroNotificacion'] === '2') {
                $numeroNotificacion = 'Notificación num. 2';
                $textoSupervisor = '<p>Se ha generado una solicitud automática ligada al Folio: <strong>' . $value['Folio'] . '</strong>.</p>
                    <p><strong>Asunto:</strong> ' . $numeroNotificacion . '  </p>
                    <p><strong>Descripción:</strong>Se le informa qu el Folio: ' . $value['Folio'] . ' todavía no se ha tendido. </p>
                    <br><br>';
                    $mensaje = $this->mail->mensajeCorreo('Seguimiento Folio ' . $value['Folio'], $textoSupervisor);
                    $this->mail->enviarCorreo('notificaciones@siccob.solutions', [$datosSupervisor[0]['CorreoSupervisor']], 'Seguimiento Folio ' . $value['Folio'], $mensaje);
                $this->DB->updateTCkekingTicket(array('Folio' => $value['Folio'], 'NumeroNotificacion' => 3));
            } elseif ($value['TiempoTranscurrido'] >= $tiempoTerceraNotificacion && $value['NumeroNotificacion'] === '3') {
                $arrayCorreos = array();
                $numeroNotificacion = 'Notificación num. 3';
                $correosCoordinadores = $this->DB->consulta("SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46");
                
                if (!empty($correosCoordinadores)) {
                    foreach ($correosCoordinadores as $k => $v) {
                        array_push($arrayCorreos, $v['EmailCorporativo']);
                    }
                }
                
                array_push($arrayCorreos, $datosSupervisor[0]['CorreoSupervisor']);
                
                $textoCoordinador = '<p>Se ha generado una solicitud automática ligada al Folio: <strong>' . $value['Folio'] . '</strong>.</p>
                    <p><strong>Asunto:</strong> ' . $numeroNotificacion . '  </p>
                    <p><strong>Descripción:</strong>Se le informa que el Folio: ' . $value['Folio'] . ' todavía no se ha tendido. </p>
                    <br><br>';
                $mensaje = $this->mail->mensajeCorreo('Seguimiento Folio ' . $value['Folio'], $textoCoordinador);
                $this->mail->enviarCorreo('notificaciones@siccob.solutions', $arrayCorreos, 'Seguimiento Folio ' . $value['Folio'], $mensaje);
            }

            $textoTecnico = '<p>Se ha generado una solicitud automática ligada al Folio: <strong>' . $value['Folio'] . '</strong>.</p>
                    <p><strong>Asunto:</strong> ' . $numeroNotificacion . '  </p>
                    <p><strong>Descripción:</strong> Favor te de atender el Folio ' . $value['Folio'] . '. </p>
                    <br><br>';
            $mensaje = $this->mail->mensajeCorreo('Seguimiento Folio ' . $value['Folio'], $textoTecnico);
            $this->mail->enviarCorreo('notificaciones@siccob.solutions', [$correoTecnico[0]['EmailCorporativo']], 'Seguimiento Folio ' . $value['Folio'], $mensaje);
        }
    }

    private function conversorSegundosHoras($tiempo_en_segundos) {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

        if ($minutos == (float) 0) {
            $minutos = '00';
        } else {
            $minutos = (string) $minutos;
        }

        if ($segundos == (float) 0) {
            $segundos = '00';
        } else {
            $segundos = (string) $segundos;
        }

        return $horas . ':' . $minutos . ":" . $segundos;
    }

}
