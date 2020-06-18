<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Minuta
 *
 * @author Alberto
 */
class Controller_Minuta extends Base {

    private $Minuta;

    public function __construct() {
        parent::__construct();
        $this->Minuta = \Librerias\Generales\Minuta::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nueva_minuta':
                $resultado = $this->Minuta->minutaNueva($this->input->post());
                break;
            case 'MostrarActualizarMinuta':
                $resultado = $this->Minuta->modalActualizarMinuta($this->input->post());
                break;
            case 'Nuevo_Archivo':
                $resultado = $this->Minuta->archivoNuevo($this->input->post());
                break;
            case 'CambiarEstatusMinuta':
                $resultado = $this->Minuta->cambiarEstatusMinuta($this->input->post());
                break;
            case 'ActualizarMinuta':
                $resultado = $this->Minuta->actualizarMinuta($this->input->post());
                break;
            case 'ActualizarEvidencia':
                $resultado = $this->Minuta->actualizarEvidencia($this->input->post());
                break;
            case 'EliminarMinuta':
                $resultado = $this->Minuta->eliminarMinuta($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
