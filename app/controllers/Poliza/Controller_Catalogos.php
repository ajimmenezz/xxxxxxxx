<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Catalogos extends Base {

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
            case 'MostrarFormularioClasificacionFalla':
                $resultado = $this->catalogosPoliza->mostrarFormularioClasificacionFalla($this->input->post());
                break;
            case 'MostrarFormularioTipoFalla':
                $resultado = $this->catalogosPoliza->mostrarFormularioTipoFalla($this->input->post());
                break;
            case 'MostrarFormularioFallaEquipo':
                $resultado = $this->catalogosPoliza->mostrarFormularioFallaEquipo($this->input->post());
                break;
            case 'MostrarFormularioFallaRefaccion':
                $resultado = $this->catalogosPoliza->mostrarFormularioFallaRefaccion($this->input->post());
                break;
            case 'MostrarFormularioSolucionesEquipo':
                $resultado = $this->catalogosPoliza->mostrarFormularioSolucionesEquipo($this->input->post());
                break;
            case 'MostrarFormularioCinemexValidacion':
                $resultado = $this->catalogosPoliza->mostrarFormularioCinemexValidacion($this->input->post());
                break;
            case 'GuardarClasificacionFalla':
                $resultado = $this->catalogo->catClasificacionFallas($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarTipoFalla':
                $resultado = $this->catalogo->catTiposFallas($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarFallaEquipo':
                $resultado = $this->catalogo->catFallasEquipo($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarFallaRefaccion':
                $resultado = $this->catalogo->catFallasRefaccion($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarSolucionEquipo':
                $resultado = $this->catalogo->catSolucionesEquipo($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarCinemexValidacion':
                $resultado = $this->catalogo->catCinemexValidaciones($this->input->post('operacion'), $this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
