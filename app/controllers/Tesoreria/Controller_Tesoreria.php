<?php

use Controladores\Controller_Base as Base;

class Controller_Tesoreria extends Base {

    private $tesoreria;

    public function __construct() {
        parent::__construct();
        $this->tesoreria = \Librerias\Tesoreria\Tesoreria::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {

            case 'validarPuesto':
                $resultado = $this->tesoreria->validarPuesto($this->input->post());
                break;
            case 'mostrarFormularioDocumentacionFacturacion':
                $resultado = $this->tesoreria->mostrarFormularioDocumentacionFacturacion($this->input->post());
                break;
            case 'guardarDocumentosFacturaAsociados':
                $resultado = $this->tesoreria->guardarDocumentosFacturaAsociados($this->input->post());
                break;
            case 'colocarFechaValidacion':
                $resultado = $this->tesoreria->colocarFechaValidacion($this->input->post());
                break;
            case 'colocarReferenciaPago':
                $resultado = $this->tesoreria->colocarReferenciaPago($this->input->post());
                break;
            case 'rechazarFacturaAsociado':
                $resultado = $this->tesoreria->rechazarFacturaAsociado($this->input->post());
                break;

            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}