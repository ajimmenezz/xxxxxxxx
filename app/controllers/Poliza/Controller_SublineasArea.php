<?php

use Controladores\Controller_Base as Base;

class Controller_SublineasArea extends Base {

    private $catalogo;
    private $catologosPoliza;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->catologosPoliza = \Librerias\Poliza\Catalogos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        try {
            switch ($evento) {
                case 'GetSublienasArea':
                    $resultado = $this->catologosPoliza->getSublienasArea($this->input->post());
                    break;
                case 'GetSublineas':
                    $resultado = $this->catologosPoliza->getSublineas($this->input->post());
                    break;
                case 'SetSublineas':
                    $resultado = $this->catologosPoliza->setSublineas($this->input->post());
                    break;
                case 'GetAreasSublineas':
                    $resultado = $this->catologosPoliza->GetAreasSublineas($this->input->post());
                    break;
                case 'FlagSublineaArea':
                    $resultado = $this->catologosPoliza->flagSublineaArea($this->input->post());
                    break;
            }
            echo json_encode($resultado);
        } catch (\Exception $exc) {
            echo json_encode(array('code' => 400, 'message' => $exc->getMessage()));
        }
    }

}
