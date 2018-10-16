<?php

use Controladores\Controller_Base as Base;

class Controller_Catalogos extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = new \Librerias\Comprobacion\Catalogos();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'AgregarConcepto':
                $resultado = $this->catalogo->agregarConcepto($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
