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
            case 'mostrarFormularioSubirFactura':
                $resultado = $this->tesoreria->formularioSubirFactura($this->input->post());
                break;
            case 'mostrarFormularioValidarVuelta':
                $resultado = $this->tesoreria->formularioValidarVuelta($this->input->post());
                break;
            case 'mostrarFormularioPago':
                $resultado = $this->tesoreria->formularioPago($this->input->post());
                break;
            case 'mostrarEvidenciaPagoFactura':
                $resultado = $this->tesoreria->evidenciaPagoFactura($this->input->post());
                break;
            case 'mostrarDetallesFactura':
                $resultado = $this->tesoreria->detallesFactura($this->input->post());
                break;
            case 'mostrarObservacionesFactura':
                $resultado = $this->tesoreria->observacionesFactura($this->input->post());
                break;
            case 'guardarDocumentosFacturaAsociados':
                $resultado = $this->tesoreria->guardarDocumentosFacturaAsociados($this->input->post());
                break;
            case 'guardarValidacionVuelta':
                $resultado = $this->tesoreria->guardarValidacionVuelta($this->input->post());
                break;
            case 'guardarFacturaAsociado':
                $resultado = $this->tesoreria->guardarFacturaAsociado($this->input->post());
                break;
            case 'guardarEvidenciaPagoFactura':
                $resultado = $this->tesoreria->guardarEvidenciaPagoFactura($this->input->post());
                break;
            case 'colocarFechaValidacion':
                $resultado = $this->tesoreria->colocarFechaValidacion($this->input->post());
                break;
            case 'colocarReferenciaPago':
                $resultado = $this->tesoreria->colocarReferenciaPago($this->input->post());
                break;
            case 'rechazarVuelta':
                $resultado = $this->tesoreria->rechazarVuelta($this->input->post());
                break;
            case 'verificarReabrirVuelta':
                $resultado = $this->tesoreria->verificarReabrirVuelta($this->input->post());
                break;
            case 'reabrirVuelta':
                $resultado = $this->tesoreria->reabrirVuelta($this->input->post());
                break;
            case 'CombinarFacturasActivas':
                $resultado = $this->tesoreria->combinarFacturasActivas();
                break;
            case 'MostrarFacturasSemanaAnterior':
                $resultado = $this->tesoreria->mostrarFacturasSemanaAnterior($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
