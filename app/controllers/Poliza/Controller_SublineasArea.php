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
        switch ($evento) {
            case 'MostrarTablaSublienasArea':
                $resultado = $this->catologosPoliza->mostrarTablaSublienasArea($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
