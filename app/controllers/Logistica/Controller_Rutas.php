<?php

use Controladores\Controller_Base as Base;

class Controller_Rutas extends Base {

    private $rutas;

    public function __construct() {
        parent::__construct();
        $this->rutas = \Librerias\Logistica\Rutas::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarFormularioRutas':
                $resultado = $this->rutas->mostrarFormularioRutas($this->input->post());
                break;
            case 'NuevaRuta':
                $resultado = $this->rutas->nuevaRuta($this->input->post());
                break;
            case 'ActualizarRuta':
                $resultado = $this->rutas->actualizarRuta('1', $this->input->post());
                break;
            case 'BuscarRuta':
                $resultado = $this->rutas->listaRutas($this->input->post());
                break;
            case 'MostrarTodasRutas':
                $resultado = $this->rutas->listaRutas();
                break;
            case 'CancelarRuta':
                $resultado = $this->rutas->actualizarRuta('3', $this->input->post());
                break;
            case 'EmpezarRuta':
                $resultado = $this->rutas->actualizarRuta('2', $this->input->post());
                break;
            case 'ConcluirRuta':
                $resultado = $this->rutas->actualizarRuta('4', $this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
