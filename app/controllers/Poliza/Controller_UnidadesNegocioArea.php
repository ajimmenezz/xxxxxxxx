<?php

use Controladores\Controller_Base as Base;

class Controller_UnidadesNegocioArea extends Base {

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
                case 'GetUnidadesArea':
                    $resultado = $this->catologosPoliza->getUnidadesArea($this->input->post());
                    break;
                case 'SetUnidadesArea':
                    $resultado = $this->catologosPoliza->setUnidadesArea($this->input->post());
                    break;
                case 'GetUnidadesAreasSelectEliminar':
                    $resultado = $this->catologosPoliza->getUnidadesAreasSelectEliminar($this->input->post());
                    break;
                case 'FlagUnidadArea':
                    $resultado = $this->catologosPoliza->flagUnidadArea($this->input->post());
                    break;
            }
            echo json_encode($resultado);
        } catch (\Exception $exc) {
            echo json_encode(array('code' => 400, 'message' => $exc->getMessage()));
        }
    }

}
