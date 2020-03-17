<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class DeviceTransfer extends General {

    private $db;

    public function __construct() {
        parent::__construct();        
        $this->db = \Modelos\Modelo_DeviceTransfer::factory();
    }

    public function deviceTransferAndDeviceRequestForm(array $data){
        return $this->db->getUsers();
    }

}
