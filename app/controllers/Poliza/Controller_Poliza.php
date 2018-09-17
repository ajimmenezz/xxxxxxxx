<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Poliza extends Base {

    private $poliza;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->poliza = \Librerias\Poliza\Poliza::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
    }

    /*
     * Se encarga de mostrar la pagina login cuando accesa por primera vez
     * al sistema el usuario.
     */

    public function index() {
        
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Ingresar':
                $resultado = $this->usuario->validarUsuario(trim($this->input->post('usuario')), trim($this->input->post('password')), $this->input->ip_address());
                break;
            case 'MostrarFormularioSolicitudMultimedia':
                $resultado = $this->poliza->formularioSolicitudMultimedia($this->input->post());
                break;
            case 'insertarSolicitudMultimedia':
                $resultado = $this->poliza->insertarSolicitudMultimedia($this->input->post());
                break;
            case 'NuevaEvidencia':
                $resultado = $this->poliza->nuevaEvidenciaSM($this->input->post());
                break;
            case 'EliminarEvidencia':
                $resultado = $this->poliza->eliminarEvidenciaSM($this->input->post());
                break;
            case 'MostrarFormularioRegionesCliente':
                $resultado = $this->poliza->mostrarFormularioRegionesCliente($this->input->post());
                break;
            case 'GuardarRegionCliente':
                $resultado = $this->catalogo->catRegionesCliente($this->input->post('operacion'), $this->input->post());
                break;
            case 'GuardarRegionCliente':
                $resultado = $this->catalogo->catRegionesCliente($this->input->post('operacion'), $this->input->post());
                break;
            case 'DatosServicioSinFirma':
                $resultado = $this->poliza->datosServicioSinFirma($this->input->post());
                break;
            case 'AgregarCategoria':
                $resultado = $this->poliza->agregarCategoria($this->input->post());
                break;
            case 'ActulizarCategoria':
                $resultado = $this->poliza->actulizarCategoria($this->input->post());
                break;
            case 'EditarCategoria':
                $resultado = $this->poliza->editarCategoria($this->input->post());
                break;
            case 'ModalPregunta':
                $resultado = $this->poliza->modalPregunta($this->input->post());
                break;
            case 'GuardarPregunta':
                $resultado = $this->poliza->guardarPregunta($this->input->post());
                break;
            case 'EditarPregunta':
                $resultado = $this->poliza->editarPregunta($this->input->post());
                break;
            case 'MostrarPregunta':
                $resultado = $this->poliza->mostrarPregunta($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}