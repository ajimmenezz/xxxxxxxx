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
            case 'SaveDeviceTransferOrDeviceRequest':
                $result = $this->deviceTransferLibrary->saveDeviceTransferOrDeviceRequest($this->input->post());
                break;
            case 'RequestLogisticGuide':
                $result = $this->deviceTransferLibrary->requestLogisticGuide($this->input->post());
                break;
            case 'CancelRequestLogisticGuide':
                $result = $this->deviceTransferLibrary->cancelRequestLogisticGuide($this->input->post());
                break;
            case 'SaveShipingInfo':
                $result = $this->deviceTransferLibrary->saveShipingInfo($this->input->post());
                break;
            case 'CancelMovementDeviceTransfer':
                $result = $this->deviceTransferLibrary->cancelMovementDeviceTransfer($this->input->post());
                break;
            case 'RequestQuote':
                $result = $this->deviceTransferLibrary->requestQuote($this->input->post());
                break;
            default:
                $result = FALSE;
                break;
        }
        echo json_encode($result);
    }
}
