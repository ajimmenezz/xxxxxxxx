<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class DeviceTransfer extends General
{

    private $db;
    private $sd;
    private $mail;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Modelos\Modelo_DeviceTransfer::factory();
        $this->sd = \Librerias\WebServices\ServiceDesk::factory();
        $this->mail = \Librerias\Generales\Correo::factory();
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
            $dataForm['logisticCompanies'] = $this->db->getLogisticCompanies();
            $dataForm['customerValidators'] = $this->sd->consultarValidadoresTI();
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

    public function requestLogisticGuide(array $data)
    {
        $movementInfo = $this->db->getDeviceMovementData(null, $data['movementId'])[0];
        $bodyText = $this->getBodyTextForLogisticGuide($data, $movementInfo);

        $result = $this->db->requestLogisticGuide($data, $bodyText);
        if ($result['code'] == 200) {
            $bodyMail = $this->mail->mensajeCorreo('Solicitud de Guía', $bodyText);
            $this->mail->enviarCorreo('notificaciones@siccob.solutions', ['ajimenez@siccob.com.mx'], 'Solicitud de Guía', $bodyMail);
        }
        return $result;
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
            </table>';


        return $bodyText;
    }
}
