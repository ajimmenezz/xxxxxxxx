<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base {

    private $Servicio;
    private $notas;

    public function __construct() {
        parent::__construct();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Servicio_Datos':
                $resultado = $this->Servicio->actualizarServicio($this->input->post());
                break;
            case 'Servicio_Nuevo_Modal':
                $resultado = $this->Servicio->modalServicioNuevo($this->input->post());
                break;
            case 'Servicio_Nuevo':
                $resultado = $this->Servicio->servicioNuevo($this->input->post());
                break;
            case 'Servicio_Cancelar_Modal':
                $resultado = $this->Servicio->modalServicioCancelar($this->input->post());
                break;
            case 'Servicio_Cancelar':
                $resultado = $this->Servicio->servicioCancelar($this->input->post());
                break;
            case 'Guardar_Nota_Servicio':
                $resultado = $this->notas->setNotaServicio($this->input->post());
                break;
            case 'ActualizaNotas':
                $resultado = $this->notas->actualizaNotas($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
