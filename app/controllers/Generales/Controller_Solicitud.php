<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Solicitud
 *
 * @author Freddy
 */
class Controller_Solicitud extends Base {

    private $Solicitud;

    public function __construct() {
        parent::__construct();
        $this->Solicitud = \Librerias\Generales\Solicitud::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Solicitud_CatDepartamentos':
                $resultado = $this->Solicitud->getDepartamentos();
                break;
            case 'Nueva_solicitud':
                $resultado = $this->Solicitud->solicitudNueva($this->input->post());
                break;
            case 'Solicitud_Datos':
                $resultado = $this->Solicitud->getDatosSolicitud($this->input->post());
                break;
            case 'Solicitud_EliminarEvidencia':
                $resultado = $this->Solicitud->eliminarEvidencia($this->input->post());
                break;
            case 'Solicitud_Actualizar':
                $resultado = $this->Solicitud->actualizarDatosSolicitud($this->input->post());
                break;
            case 'Generar_Ticket':
                $resultado = $this->Solicitud->generarTicket($this->input->post());
                break;
            case 'Datos_SistemaExterno':
                $resultado = $this->Solicitud->getDatosSolicitud($this->input->post());
                break;
            case 'Formulario_Nueva_Solicitud':
                $resultado = $this->Solicitud->getFormularioSolicitud($this->input->post());
                break;
            case 'BuscarAreaDepartamento':
                $resultado = $this->Solicitud->buscarAreaDepartamento($this->input->post());
                break;
            case 'Usuario':
                $resultado = $this->Solicitud->getUsuario($this->input->post());
                break;
            case 'GuardarNotaSolicitud':
                $resultado = $this->Solicitud->guardarNotaSolicitud($this->input->post());
                break;
            case 'editarFolio':
                $resultado = $this->Solicitud->editarFolio($this->input->post());
                break;
            case 'Atencion_Solcitud':
                $resultado = $this->Solicitud->atencioSolicitudInterna($this->input->post());
                break;
            case 'ReasignarFolioSD':
                $resultado = $this->Solicitud->reasignarFolioSD($this->input->post());
                break;
            case 'MostrarSucursalesCliente':
                $resultado = $this->Solicitud->sucursalesCliente($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
