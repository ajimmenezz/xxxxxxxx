<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Solicitud
 *
 * @author Alonso
 */
class Controller_Servicios extends Base {

    private $Servicio;
    private $ServicioTicket;
    private $notas;
    private $informacionServicios;

    public function __construct() {
        parent::__construct();
        $this->Servicio = \Librerias\Generales\Servicio::factory();
        $this->ServicioTicket = \Librerias\Generales\ServiciosTicket::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
        $this->informacionServicios = \Librerias\WebServices\InformacionServicios::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Servicio_Detalles':
                $resultado = $this->Servicio->getDetallesByServicio($this->input->post());
                break;
            case 'Servicio_ToPdf':
                $resultado = $this->Servicio->getServicioToPdf($this->input->post());
                break;
            case 'Verificar_Servicio':
                $resultado = $this->ServicioTicket->verificarServicio($this->input->post());
                break;
            case 'Rechazar_Servicio':
                $resultado = $this->ServicioTicket->rechazarServicio($this->input->post());
                break;
            case 'Reabrir_Servicio':
                $resultado = $this->ServicioTicket->reabrirServicio($this->input->post());
                break;
            case 'Guarda_SinClasificar':
                $resultado = $this->Servicio->Guarda_SinClasificar($this->input->post());
                break;
            case 'Concluir_SinClasificar':
                $resultado = $this->Servicio->Concluir_SinClasificar($this->input->post());
                break;
            case 'Enviar_Reporte_PDF':
                $resultado = $this->Servicio->enviar_Reporte_PDF($this->input->post());
                break;
            case 'MostrarFormularioAvanceServicio':
                $resultado = $this->Servicio->mostrarFormularioAvenceServicio($this->input->post());
                break;
            case 'GuardarAvenceServicio':
                $resultado = $this->Servicio->guardarAvenceServicio($this->input->post());
                break;
            case 'GuardarDocumentacionFirma':
                $resultado = $this->ServicioTicket->guardarDocumentacionFirma($this->input->post());
                break;
            case 'MostrarFormularioReasignarServicio':
                $resultado = $this->Servicio->mostrarFormularioReasignarServicio($this->input->post());
                break;
            case 'cambiarAtiendeServicio':
                $resultado = $this->Servicio->cambiarAtiendeServicio($this->input->post());
                break;
            case 'Guardar_Nota_Servicio':
                $resultado = $this->notas->setNotaServicio($this->input->post());
                break;
            case 'GuardarVueltaAsociado':
                $resultado = $this->Servicio->guardarVueltaAsociados($this->input->post());
                break;
            case 'GuardarVueltaAsociadoSinFirma':
                $resultado = $this->Servicio->guardarVueltaAsociadosSinFirma($this->input->post());
                break;
            case 'ActualizaNotas':
                $resultado = $this->notas->actualizaNotas($this->input->post());
                break;
            case 'EliminarEvidenciaServicio':
                $resultado = $this->Servicio->eliminarEvidenciaServicio($this->input->post());
                break;
            case 'Servicio_ToPdf_ProblemasEquipo':
                $resultado = $this->Servicio->getServicioToPdf($this->input->post(), '/ProblemasEquipo');
                break;
            case 'Servicio_ToPdf_EquipoFaltante':
                $resultado = $this->Servicio->getServicioToPdf($this->input->post(), '/EquipoFaltante');
                break;
            case 'Servicio_ToPdf_OtrosProblemas':
                $resultado = $this->Servicio->getServicioToPdf($this->input->post(), '/OtrosProblemas');
                break;
            case 'ConsultaIdClienteSucursal':
                $resultado = $this->Servicio->consultaIdClienteSucursal($this->input->post());
                break;
            case 'VerificarFolioServicio':
                $resultado = $this->informacionServicios->validarFolioServicio($this->input->post());
                break;
            case 'VerificarVueltaAsociado':
                $resultado = $this->Servicio->varificarVueltaAsociado($this->input->post());
                break;
            case 'VerificarTecnicoPoliza':
                $resultado = $this->Servicio->varificarTecnicoPoliza();
                break;
            case 'AgregarVueltaAsociadoMantenimiento':
                $resultado = $this->ServicioTicket->agregarVueltaAsociadoMantenimiento($this->input->post());
                break;
            case 'CrearPDFVueltaAsociadoMantenimiento':
                $resultado = $this->ServicioTicket->pfdAsociadoVueltaServicioMantenimiento($this->input->post());
                break;
            case 'Servicio_Cancelar_Modal':
                $resultado = $this->ServicioTicket->modalServicioCancelar($this->input->post());
                break;
            case 'Servicio_Cancelar':
                $resultado = $this->ServicioTicket->servicioCancelar($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
