<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class DeviceTransfer extends General
{

    private $db;
    private $sd;
    private $serviceInfo;
    private $mail;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Modelos\Modelo_DeviceTransfer::factory();
        $this->sd = \Librerias\WebServices\ServiceDesk::factory();
        $this->mail = \Librerias\Generales\Correo::factory();
        $this->serviceInfo = \Librerias\WebServices\InformacionServicios::factory();
    }

    public function deviceTransferAndDeviceRequestForm(array $data)
    {
        $dataForm = [
            'validators' => $this->db->getTransferOrRequestDeviceValidators(),
            'posibleBackupDevices' => $this->db->getAvailableDeviceForServiceSolution($data['serviceId']),
            'generalsService' => $this->db->getGeneralsService($data['serviceId']),
            'diagnosticService' => $this->db->getDiagnosticService($data['serviceId']),
            'deviceMovementData' => $this->db->getDeviceMovementData($data['serviceId'])
        ];

        $init = 'transferRequestForm';

        $view = 'Poliza/Formularios/deviceTransferAndDeviceRequestForm';
        if (!empty($dataForm['deviceMovementData'])) {
            switch ($dataForm['deviceMovementData'][0]['IdTipoMovimiento']) {
                case 1:
                    $dataForm['logisticCompanies'] = $this->db->getLogisticCompanies();

                    $technicianLogisticInfo = $this->db->getTechnicianLogicticInfo($dataForm['deviceMovementData'][0]['Id']);
                    if (!empty($technicianLogisticInfo)) {
                        $dataForm['technicianLogisticInfo'] = $technicianLogisticInfo[0];
                    } else {
                        $dataForm['customerValidators'] = $this->sd->consultarValidadoresTI();
                    }
                    break;
            }
            $view = 'Poliza/Formularios/deviceMovementInformation';
            $init = 'movementInformation';
        }
        return [
            'code' => 200,
            'init' => $init,
            'form' => parent::getCI()->load->view($view, $dataForm, TRUE)
        ];
    }

    public function saveDeviceTransferOrDeviceRequest(array $data)
    {
        switch ($data['movement']) {
            case 1:
            case 2:
                $result = $this->db->saveDeviceTransfer($data);
                break;
            default:
                $result = ['code' => 400, 'error' => 'No se ha recibido la información necesaria para documentar el movimiento. Intente de nuevo o contácte al administrador.'];
                break;
        }

        return $result;
    }

    public function cancelMovementDeviceTransfer(array $data)
    {
        return $this->db->cancelMovementDeviceTransfer($data);
    }

    public function requestLogisticGuide(array $data)
    {
        $movementInfo = $this->db->getDeviceMovementData(null, $data['movementId'])[0];
        $bodyText = $this->getBodyTextForLogisticGuide($data, $movementInfo);

        $result = $this->db->requestLogisticGuide($data, $bodyText);
        if ($result['code'] == 200) {
            $bodyMail = $this->mail->mensajeCorreo('Solicitud de Guía', $bodyText);
            $this->mail->enviarCorreo('notificaciones@siccob.solutions', ['ajimenez@siccob.com.mx', 'g.gonzalez@siccob.com.mx', 'erodriguez@siccob.com.mx', 'oflores@siccob.com.mx'], 'Solicitud de Guía', $bodyMail);
        }
        return $result;
    }

    public function cancelRequestLogisticGuide(array $data)
    {
        $result = $this->db->cancelRequestLogisticGuide($data['logisticGuideRequestId']);
        if ($result['code'] == 200) {
            $bodyText = '
                <p style="font-size:15px; font-weight:600;">
                    Se ha solicitado la cancelación de la solicitud de guía con la siguiente información
                </p>' . $result['bodyText'];
            $bodyMail = $this->mail->mensajeCorreo('Cancelación de Solicitud de Guía', $bodyText);
            $this->mail->enviarCorreo('notificaciones@siccob.solutions', ['ajimenez@siccob.com.mx', 'g.gonzalez@siccob.com.mx', 'erodriguez@siccob.com.mx', 'oflores@siccob.com.mx'], 'Cancelación de Solicitud de Guía', $bodyMail);
        }
        return $result;
    }

    public function saveShipingInfo(array $data)
    {
        return $this->db->saveShipingInfo($data);
    }

    private function getBodyTextForLogisticGuide($dataFormRequest, $movementInfo)
    {
        $bodyText = '
            No. Incidente: Ticket ' . $movementInfo['Ticket'] . ', Folio: ' . $movementInfo['Folio'] . '<br />
            Persona que solicita: ' . $movementInfo['UsuarioActual'] . '<br />
            Sucursal de Origen: ' . $movementInfo['Sucursal'] . '<br />
            Destino: ' . $dataFormRequest['to'] . '<br />
            Personal de TI que autoriza: ' . $dataFormRequest['customerValidator'] . '<br /><br />
            <table border="1">
                <thead>
                    <tr>
                        <th style="text-align:center; padding:7px; font-weight:600;"># Caja</th>
                        <th style="text-align:center; padding:7px; font-weight:600;">Peso (kg)</th>
                        <th style="text-align:center; padding:7px; font-weight:600;">Largo (cm)</th>
                        <th style="text-align:center; padding:7px; font-weight:600;">Ancho (cm)</th>
                        <th style="text-align:center; padding:7px; font-weight:600;">Alto (cm)</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($dataFormRequest['boxes'] as $k => $v) {
            $bodyText .= '
                    <tr>
                        <td style="text-align:center; padding:7px; font-weight:500;">' . ($k + 1) . '</td>
                        <td style="text-align:center; padding:7px; font-weight:500;">' . $v['weight'] . '</td>
                        <td style="text-align:center; padding:7px; font-weight:500;">' . $v['length'] . '</td>
                        <td style="text-align:center; padding:7px; font-weight:500;">' . $v['width'] . '</td>
                        <td style="text-align:center; padding:7px; font-weight:500;">' . $v['eight'] . '</td>
                    </tr>
            ';
        }
        $bodyText .= '
                </tbody>
            </table>
            <p>Para consultar sus pendientes de asignación de guía, puede seguir el siguiente enlace:<br /><a target="_blank" href="http://siccob.solutions/Poliza/Seguimiento_Equipos">Seguimiento de Guías</a></p>';


        return $bodyText;
    }

    public function requestQuote(array $dataForm)
    {
        $files = '';
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $folder = 'Servicios/Servicio-' . $dataForm['idServicio'] . '/SolicitudCotizacion/';
            $files = implode(',', setMultiplesArchivos($CI, 'archivosSolicitudCotizacion', $folder));
        }

        $data = [
            'movementId' => $dataForm['id'],
            'assignTo' => $dataForm['reasignar'],
            'annotations' => $dataForm['comentarios'],
            'files' => $files,
            'serviceId' => $dataForm['idServicio']
        ];

        $result = $this->db->requestQuote($data);

        if ($result['code'] == 200) {
            $pdf = $this->serviceInfo->definirPDFTraslado(['servicio' => $result['serviceInfo']['IdServicio'], 'folio' => $result['serviceInfo']['Folio']]);
            return ['code' => 200, 'file' => $pdf, 'data' => $data];
        } else {
            return $result;
        }
    }
}
