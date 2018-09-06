<?php

use Controladores\Controller_Base as Base;

class Controller_Editar extends Base {

    private $Editar;

    public function __construct() {
        parent::__construct();
        $this->Editar = \Librerias\Generales\EditarSolicitud::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Carga_Solicitudes':
                $resultado = $this->Editar->cargaSolicitudes($this->input->post());             
                break;
            case 'Carga_DetallesSolicitud':
                $resultado = $this->Editar->cargaDetallesSolicitud($this->input->post());             
                break;        
            case 'GuardaImagenesSolicitud':
                $resultado = $this->Editar->guardaImagenesSolicitud($this->input->post());             
                break;        
            case 'Guarda_DetallesSolicitud':
                $resultado = $this->Editar->guardaDetallesSolicitud($this->input->post());             
                break;        
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
