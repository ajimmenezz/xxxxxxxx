<?php

namespace Librerias\Proyectos2;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\PDF as PDF;

class Proyecto extends General {

    private $DB;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Salas4D::factory();
        $this->pdf = new PDF();
    }

    public function mostrarInventarioSucursal(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'ids' => (isset($datos['ids'])) ? $datos['ids'] : ''
        ];

        if (!empty($datos)) {
            $data = [
                'elementos' => $this->DB->getElementosSucursal($datos['id']),
                'subelementos' => $this->DB->getSubelementosSucursal($datos['id'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/InventarioSucursal', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

}
