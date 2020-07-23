<?php

namespace Librerias\Poliza\Seguimientos;

use Controladores\Controller_Datos_Usuario as General;

class Seguimiento55 extends General
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Modelos\Poliza\Seguimiento55::factory();
    }

    public function getSOUpdateForm(array $postData)
    {
        $serviceInfo = $this->db->serviceInfo($postData['serviceId']);
        if ($serviceInfo['code'] == 200) {
            if (in_array($serviceInfo['data']['IdSucursal'], [null, 0, ''])) {
                return ['code' => 400, 'message' => 'Debe seleccionar y guardar la sucursal en la sección "Información General"'];
            } else {

                $dataForView = [
                    'updateSOInfo' => $this->db->updateSOInfo($serviceInfo['data']['Id'], $serviceInfo['data']['IdSucursal']),
                    'impediments' => $this->db->getImpediments(),
                    'updateSOImpediments' => $this->db->updateSOImpediments($serviceInfo['data']['Id'])

                ];
                $view = parent::getCI()->load->view('Poliza/Formularios/soUpdateForm', $dataForView, TRUE);
                return ['code' => 200, 'html' => $view];
            }
        } else {
            return $serviceInfo;
        }
    }

    public function saveSOUpdateInfo(array $postData)
    {
        $result = $this->db->saveSOUpdateInfo($postData);
        return $result;
    }
}
