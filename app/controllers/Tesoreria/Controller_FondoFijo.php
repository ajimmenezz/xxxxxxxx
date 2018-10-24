<?php

use Controladores\Controller_Base as Base;

class Controller_FondoFijo extends Base {

    private $fondo_fijo;

    public function __construct() {
        parent::__construct();
        $this->fondo_fijo = new \Librerias\Tesoreria\FondoFijo();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {            
            case 'DetallesFondoFijoXUsuario':
                $resultado = $this->fondo_fijo->detallesFondoFijoXUsuario($this->input->post());
                break;
            case 'FormularioRegistrarDeposito':
                $resultado = $this->fondo_fijo->formularioRegistrarDeposito($this->input->post());
                break;            
            case 'RegistrarDeposito':
                $resultado = $this->fondo_fijo->registrarDeposito($this->input->post());
                break;
            case 'FormularioRegistrarComprobante':
                $resultado = $this->fondo_fijo->formularioRegistrarComprobante($this->input->post());
                break;
            case 'CargaMontoMaximoConcepto':
                $resultado = $this->fondo_fijo->cargaMontoMaximoConcepto($this->input->post());
                break;
            case 'CargaServiciosTicket':
                $resultado = $this->fondo_fijo->cargaServiciosTicket($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
