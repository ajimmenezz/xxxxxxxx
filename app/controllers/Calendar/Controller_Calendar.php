<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Calendar extends Base {

    private $catalogo;
    private $catalogosPoliza;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->catalogosPoliza = \Librerias\Poliza\Catalogos::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Mostrar':
//                $resultado = $this->catalogosPoliza->mostrarFormularioClasificacionFalla($this->input->post());
                $resultado = "nada";
                break;
        }
        echo json_encode($resultado);
    }

}
