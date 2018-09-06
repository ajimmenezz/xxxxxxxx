<?php

use Controladores\Controller_Base as Base;

class Controller_Logistica extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            //Seccion Regiones Logistica
            case 'Nueva_Region':
                $resultado = $this->catalogo->catRegionesLogistica('1', $this->input->post());
                break;
            case 'MostrarRegionActualizar':
                $resultado = $this->catalogo->catRegionesLogistica('4', array($this->input->post('Region')));
                break;
            case 'Actualizar_Region':
                $resultado = $this->catalogo->catRegionesLogistica('2', $this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
