<?php

/**
 * Description of Controller_SegundoPlano
 *
 * @author Freddy
 */
class Controller_SegundoPlano extends \CI_Controller {

    private $DB;
    private $SD;
    private $mail;
    private $ubicaphone;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->DB = \Modelos\Modelo_SegundoPlano::factory();
        $this->SD = \Librerias\WebServices\ServiceDesk::factory();
        $this->mail = \Librerias\Generales\Correo::factory();
        $this->ubicaphone = \Librerias\WebServices\Ubicaphone::factory();
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
        $apiKey = $this->DB->getApiKeyByUser();
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

                    echo "***********************************************************************<br />"
                    . "<pre style='text-color:red !important;'>", var_dump($mensaje), "</pre>"
                    . "<br /<<br />";
                }
            }
        }
    }

    public function getAsignacionesSD() {
        date_default_timezone_set("America/Mexico_City");
        $apiKey = $this->DB->getApiKeyByUser();
        var_dump($apiKey);
        $filterId = $this->getViewFilterId($apiKey);

        $requests = $this->SD->getRequestsByFilter($filterId, $apiKey, 0);

        $folios = '';
        $foliosSD = [];
        $foliosAdIST = [];

        foreach ($requests as $key => $value) {
            $folios .= "," . $value->WORKORDERID;
            array_push($foliosSD, $value->WORKORDERID);
        }


        $foliosExistentes = $this->DB->getFoliosExistentesAsignacionesSD($folios);
        foreach ($foliosExistentes as $key => $value) {
            array_push($foliosAdIST, $value['Folio']);
        }

        $foliosParaRevisar = array_diff($foliosSD, $foliosAdIST);               

//        $cont = 0;
        foreach ($foliosParaRevisar as $key => $value) {
//            $cont++;
//            if ($cont <= 10) {
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

            echo "<pre>";
            var_dump($arrayInsert);
            var_dump($details->CREATEDTIME);
            echo "</pre>";
//            }
        }
    }

    public function checkUbicaphoneEstatus() {
        $result = $this->ubicaphone->getAllDevices();
        $array = [];
        date_default_timezone_set("America/Mexico_City");
        foreach ($result as $key => $value) {
            $infoUser = $this->DB->getInfoUserByIMEI($value['imei']);
            $mail = '';
            $nombre = '';
            if (!empty($infoUser)) {
                $mail = $infoUser[0]['Email'];
                $nombre = $infoUser[0]['Usuario'];
            }

            $t1 = strtotime(date('Y-m-d H:i:s', $value['timestamp']));
            $t2 = strtotime(date('Y-m-d H:i:s'));

            $diff = $t2 - $t1;
            $hours = $diff / 3600;

            if ($hours > 3 && $mail != '') {
                array_push($array, [
                    'nombre' => $nombre,
                    'mail' => $mail,
                    'hours' => $hours,
                    'imei' => $value['imei'],
                    'fecha' => date('Y-m-d H:i:s', $value['timestamp']),
                    'timestamp' => $value['timestamp'],
                    'usuario' => $value['alias'],
                    'lat' => $value['lat'],
                    'lng' => $value['lng'],
                    'direccion' => $value['street']
                ]);
            }
        }



        echo "<pre>";
        var_dump($array);
        echo "</pre>";
    }

}
