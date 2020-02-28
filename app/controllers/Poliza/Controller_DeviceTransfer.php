<?php

use Controladores\Controller_Base as Base;

class Controller_DeviceTransfer extends Base
{

    private $deviceTransferLibrary;

    public function __construct()
    {
        parent::__construct();
        $this->deviceTransferLibrary = \Librerias\Poliza\DeviceTransfer::factory();
    }
    
    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'DeviceTransferAndDeviceRequestForm':
                $result = $this->deviceTransferLibrary->deviceTransferAndDeviceRequestForm($this->input->post());
                break;
            default:
                $result = FALSE;
                break;
        }
        echo json_encode($result);
    }
}
