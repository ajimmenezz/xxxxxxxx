<?php

use Controladores\Controller_Base as Base;

class Controller_Localizacion extends Base {

    private $ubicaphone;

    public function __construct() {
        parent::__construct();
        $this->ubicaphone = new \Librerias\WebServices\Ubicaphone();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'CargaDispositivosGlobal':
                $resultado = $this->ubicaphone->cargaDispositivosGlobal();
                break;
            case 'DetallesDispositivo':
                $resultado = $this->ubicaphone->detallesDispositivo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
