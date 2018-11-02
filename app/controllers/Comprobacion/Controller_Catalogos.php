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
            case 'FormularioAgregarConcepto':
                $resultado = $this->catalogo->formularioAgregarConcepto($this->input->post());
                break;
            case 'AgregarConcepto':
                $resultado = $this->catalogo->agregarConcepto($this->input->post());
                break;                                  
            case 'FormularioAgregarFondoFijo':
                $resultado = $this->catalogo->formularioAgregarFondoFijo($this->input->post());
                break;
            case 'AgregarFondoFijo':
                $resultado = $this->catalogo->agregarFondoFijo($this->input->post());
                break;  
            case 'InhabilitarFF':
                $resultado = $this->catalogo->inhabilitarFF($this->input->post());
                break;    
            case 'HabilitarFF':
                $resultado = $this->catalogo->habilitarFF($this->input->post());
                break;
            case 'InhabilitarConcepto':
                $resultado = $this->catalogo->inhabilitarConcepto($this->input->post());
                break;  
            case 'HabilitarConcepto':
                $resultado = $this->catalogo->habilitarConcepto($this->input->post());
                break;  
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
