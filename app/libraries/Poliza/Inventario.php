<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class Inventario extends General
{

    private $Excel;
    private $DB;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Inventario::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
    }

    public function getInventoryFiltersData()
    {
        $arrayReturn = [
            'status' => $this->DB->statusFilters(),
            'technician' => $this->DB->technicianFilters(),
            'region' => $this->DB->regionFilters(),
            'branches' => $this->DB->branchFilters(),
            'areas' => $this->DB->areasFilters(),
            'devices' => $this->DB->devicesFilters()
        ];
        return $arrayReturn;
    }

    public function loadFirstView($formFieldsValues)
    {
        $v = $formFieldsValues;
        $init = '';
        $data = [];
        if (isset($v['devices']) && $v['devices'] !== "" && count($v['devices']) > 0) {
            $view = 'devicesFirstView';
        } else if (isset($v['areas']) && $v['areas'] !== "" && count($v['areas']) > 0) {
            $view = 'areasFirstView';
            $data = [
                'pointsArea' => $this->DB->totalPointsByArea('', $v),
                'devicesArea' => $this->DB->totalDevicesByArea('', $v),
                'devicesLine' => $this->DB->totalDevicesByLine('', $v),
                'devicesSubline' => $this->DB->totalDevicesBySubline('', $v),
                'devicesModel' => $this->DB->totalDevicesByModel('', $v),
                'branchList' => $this->DB->branchListAreasAnDevices($v)
            ];
            $init = 'areas';
        } else {
            $view = 'branchListFirstView';
            $data['branchList'] = $this->DB->branchListInventories($v);
            $init = 'branchList';
        }


        return [
            'code' => 200,
            'init' => $init,
            'form' => parent::getCI()->load->view('Poliza/Inventarios/' . $view, $data, TRUE)
        ];
    }

    public function loadInventoryDetails($postData)
    {
        $servicio = $postData['servicio'];
        $data = [
            'branch' => $postData['sucursal'],
            'pointsArea' => $this->DB->totalPointsByArea($servicio),
            'devicesArea' => $this->DB->totalDevicesByArea($servicio),
            'devicesLine' => $this->DB->totalDevicesByLine($servicio),
            'devicesSubline' => $this->DB->totalDevicesBySubline($servicio),
            'devicesModel' => $this->DB->totalDevicesByModel($servicio),
            'details' => $this->DB->detailsInventory($servicio)
        ];

        return [
            'code' => 200,
            'form' => parent::getCI()->load->view('Poliza/Inventarios/inventoryDetails', $data, TRUE)
        ];
    }
}
