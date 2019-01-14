<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base {

    private $Seguimientos;
    private $Servicio;
    private $notas;
    private $ServiciosGeneral;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->Seguimientos = \Librerias\Poliza\Seguimientos::factory();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
        $this->ServiciosGeneral = \Librerias\Generales\Servicio::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Poliza = \Librerias\Poliza\Poliza::factory();
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
            case 'GuardarDatosGeneralesCenso':
                $resultado = $this->Seguimientos->guardarDatosGeneralesCenso($this->input->post());
                break;
            case 'GuardarDatosCenso':
                $resultado = $this->Seguimientos->guardarDatosCenso($this->input->post());
                break;
            case 'EliminarCenso':
                $resultado = $this->Seguimientos->eliminarCenso($this->input->post());
                break;
            case 'guardarDatosMantenimiento':
                $resultado = $this->Seguimientos->guardarDatosMantenimiento($this->input->post());
                break;
            case 'guardarEquiposFaltantes':
                $resultado = $this->Seguimientos->guardarEquiposFaltantes($this->input->post());
                break;
            case 'guardarProblemasAdicionales':
                $resultado = $this->Seguimientos->guardarProblemasAdicionales($this->input->post());
                break;
            case 'guardarAntesYDespues':
                $resultado = $this->Seguimientos->guardarAntesYDespues($this->input->post());
                break;
            case 'guardarProblemasEquipo':
                $resultado = $this->Seguimientos->guardarProblemasEquipo($this->input->post());
                break;
            case 'guardarEvidenciasAntesYDespues':
                $resultado = $this->Seguimientos->guardarEvidenciasAntesYDespues($this->input->post());
                break;
            case 'guardarEvidenciasProblemasEquipo':
                $resultado = $this->Seguimientos->guardarEvidenciasProblemasEquipo($this->input->post());
                break;
            case 'guardarDiagnosticoEquipo':
                $resultado = $this->Seguimientos->guardarDiagnosticoEquipo($this->input->post());
                break;
            case 'guardarRefaccionesSolicitud':
                $resultado = $this->Seguimientos->guardarRefaccionesSolicitud($this->input->post());
                break;
            case 'guardarEquiposSolicitud':
                $resultado = $this->Seguimientos->guardarEquiposSolicitud($this->input->post());
                break;
            case 'guardarInformacionEquipoRespaldo':
                $resultado = $this->Seguimientos->guardarInformacionEquipoRespaldo($this->input->post());
                break;
            case 'guardarCrearSolicitarEquipoRespaldo':
                $resultado = $this->Seguimientos->guardarCrearSolicitarEquipoRespaldo($this->input->post());
                break;
            case 'guardarEnvioGarantia':
                $resultado = $this->Seguimientos->guardarEnvioGarantia($this->input->post());
                break;
            case 'guardarEntregaGarantia':
                $resultado = $this->Seguimientos->guardarEntregaGarantia($this->input->post());
                break;
            case 'guardarReparacionSinEquipo':
                $resultado = $this->Seguimientos->guardarReparacionSinEquipo($this->input->post());
                break;
            case 'guardarReparacionConRefaccion':
                $resultado = $this->Seguimientos->guardarReparacionConRefaccion($this->input->post());
                break;
            case 'guardarCambioEquipo':
                $resultado = $this->Seguimientos->guardarCambioEquipo($this->input->post());
                break;
            case 'enviarEntregaEquipoGarantia':
                $resultado = $this->Seguimientos->enviarEntregaEquipoGarantia($this->input->post());
                break;
            case 'enviarReporteImpericia':
                $resultado = $this->Seguimientos->enviarReporteImpericia($this->input->post());
                break;
            case 'enviarSolucionCorrectivoSD':
                $resultado = $this->Seguimientos->enviarSolucionCorrectivoSD($this->input->post());
                break;
            case 'Guardar_Reporte_Firmado':
                $resultado = $this->Seguimientos->guardarReporteFirmadoServicioMantenimiento($this->input->post());
                break;
            case 'GuardarDatosGeneralesCorrectivo':
                $resultado = $this->Seguimientos->guardarDatosGeneralesCorrectivo($this->input->post());
                break;
            case 'EliminarEquipoFaltante':
                $resultado = $this->Seguimientos->eliminarEquipoFaltante($this->input->post());
                break;
            case 'Eliminar_Evidencia':
                $resultado = $this->Seguimientos->eliminarEvidencia($this->input->post());
                break;
            case 'Eliminar_EvidenciaDiagnostico':
                $resultado = $this->Seguimientos->eliminarEvidenciaDiagnostico($this->input->post());
                break;
            case 'Eliminar_EvidenciaSolucion':
                $resultado = $this->Seguimientos->eliminarEvidenciaSolucion($this->input->post());
                break;
            case 'Eliminar_EvidenciaEnviosEquipo':
                $resultado = $this->Seguimientos->eliminarEvidenciaEnviosEquipo($this->input->post());
                break;
            case 'Eliminar_ProblemaEquipo':
                $resultado = $this->Seguimientos->eliminarProblemaEquipo($this->input->post());
                break;
            case 'EliminarDetallesSolicitud':
                $resultado = $this->Seguimientos->eliminarDetallesSolicitud($this->input->post());
                break;
            case 'Eliminar_ProblemaAdicional':
                $resultado = $this->Seguimientos->eliminarProblemaAdicional($this->input->post());
                break;
            case 'mostrarFormularioAntesYDespues':
                $resultado = $this->Seguimientos->mostrarFormularioAntesYDespues($this->input->post());
                break;
            case 'Servicio_ToPdf':
                $resultado = $this->ServiciosGeneral->getServicioToPdf($this->input->post());
                break;
            case 'verificarDocumentacion':
                $resultado = $this->Seguimientos->consultaDocumentacionMantenimientoAntesDespues($this->input->post());
                break;
            case 'verificarDiagnostico':
                $resultado = $this->Seguimientos->verificarDiagnostico($this->input->post());
                break;
            case 'ConsultaAreaPuntoXSucursal':
                $resultado = $this->Seguimientos->consultaAreaPuntoXSucursal($this->input->post('sucursal'), 'Area, Punto');
                break;
            case 'ConsultaEquipoXAreaPuntoUltimoCenso':
                $resultado = $this->Seguimientos->consultaEquipoXAreaPuntoUltimoCenso($this->input->post());
                break;
            case 'ConsultaModelosEquipos':
                $resultado = $this->Catalogo->catModelosEquipo('3', array('Flag' => '1'));
                break;
            case 'ConsultaTiposFallasEquiposImpericia':
                $resultado = $this->Seguimientos->consultaTiposFallasEquiposImpericia($this->input->post());
                break;
            case 'ConsultaTiposFallasEquipos':
                $resultado = $this->Seguimientos->consultaTiposFallasEquipos($this->input->post());
                break;
            case 'ConsultaFallasEquiposXTipoFallaYEquipo':
                $resultado = $this->Seguimientos->consultaFallasEquiposXTipoFallaYEquipo($this->input->post());
                break;
            case 'ConsultaTipoFallaXRefaccion':
                $resultado = $this->Seguimientos->consultaTipoFallaXRefaccion($this->input->post());
                break;
            case 'ConsultaFallasRefacionXTipoFalla':
                $resultado = $this->Seguimientos->consultaFallasRefacionXTipoFalla($this->input->post());
                break;
            case 'ConsultaRefacionXEquipo':
                $resultado = $this->Seguimientos->consultaRefacionXEquipo($this->input->post());
                break;
            case 'ConsultaEquiposXLinea':
                $resultado = $this->Seguimientos->ConsultaEquiposXLinea($this->input->post());
                break;
            case 'ConsultaAtiendeAlmacen':
                $resultado = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => '16'));
                break;
            case 'ConsultaCatalogoSolucionesEquipoXEquipo':
                $resultado = $this->Seguimientos->consultaCatalogoSolucionesEquipoXEquipo($this->input->post());
                break;
            case 'ConsultaCorrectivosSolucionesServicio':
                $resultado = $this->Seguimientos->consultaCorrectivosSolucionesServicio($this->input->post());
                break;
            case 'ConsultaCorrectivoTI':
                $resultado = $this->Seguimientos->consultaCorrectivoTI($this->input->post());
                break;
            case 'SolicitarMultimedia':
                $resultado = $this->Seguimientos->solicitarMultimedia($this->input->post());
                break;
            case 'CambiarEstatus':
                $resultado = $this->Seguimientos->cambiarEstatus($this->input->post());
                break;
            case 'GuardarInformacionChecklist':
                $resultado = $this->Poliza->guardarInformacionGeneral($this->input->post());
                break;
            case 'MostrarPuntoRevision':
                $resultado = $this->Poliza->mostrarPuntoRevision($this->input->post());
                break;
            case 'MostrarPreguntas':
                $resultado = $this->Poliza->obtenerPreguntaPorCategoria($this->input->post());
                break;
            case 'GuardarRevisionPunto':
                $resultado = $this->Poliza->guardarPuntoRevision($this->input->post());
                break;
            case 'EliminarEvidenciaChecklist':
                $resultado = $this->Poliza->eliminarEvidenciaChecklist($this->input->post());
                break;
            case 'ConsultarRevisionPunto':
                $resultado = $this->Poliza->consultarRevisionPunto($this->input->post());
                break;
            case 'ActualizarRevisionPunto':
                $resultado = $this->Poliza->actualizarRevisionPunto($this->input->post());
                break;
            case 'EliminarEvidenciaRevicion':
                $resultado = $this->Poliza->eliminarEvidenciaRevisionPunto($this->input->post());
                break;
            case 'RevisionTecnica':
                $resultado = $this->Poliza->revisionTecnica($this->input->post());
                break;
            case 'ConsultaFallasRefacionXTipoFallaChecklist':
                $resultado = $this->Seguimientos->consultaFallasRefacionXTipoFallaChecklist($this->input->post());
                break;
            case 'GuardarRevisionTecnicaChecklist':
                $resultado = $this->Poliza->guardarRevisionTecnicaChecklist($this->input->post());
                break;
            case 'MostrarFallasTecnicasChecklist':
                $resultado = $this->Poliza->mostrarFallasTecnicasCheclist($this->input->post());
                break;
            case 'ActualizarRevisionTecnica':
                $resultado = $this->Poliza->actualizarRevisionTecnica($this->input->post());
                break;
            case 'EditarRevisionTecnicaChecklist':
                $resultado = $this->Poliza->editarRevisionTecnicaChecklist($this->input->post());
                break;
            case 'MostrarDatosServicio':
                $resultado = $this->Poliza->mostrarDatosServicio($this->input->post());
                break;
            case 'GuardarConclusionChecklist':
                $resultado = $this->Poliza->guardarConclusionChecklist($this->input->post());
                break;
            case 'GenerarPDF':
                $resultado = $this->Poliza->pdfServicioChecklist($this->input->post());
                break;
            case 'ConsultarRevisonArea':
                $resultado = $this->Poliza->consultarRevisonArea($this->input->post());
                break;
            case 'nuevosServiciosDesdeChecklist':
                $resultado = $this->Poliza->nuevosServiciosDesdeChecklist($this->input->post());
                break;
            case 'VistaPorPerfil':
                $resultado = $this->Seguimientos->vistaPorPerfil();
                break;
            case 'MostrarTicketsUsuario':
                $resultado = $this->Seguimientos->mostrarTicketsUsuario();
                break;
            case 'MostrarServiciosUsuario':
                $resultado = $this->Seguimientos->mostrarServiciosUsuario($this->input->post());
                break;
            case 'MostrarEquipoDanado':
                $resultado = $this->Seguimientos->mostrarEquipoDanado($this->input->post());
                break;
            case 'MostrarNombrePersonalValida':
                $resultado = $this->Seguimientos->mostrarNombrePersonalValida($this->input->post());
                break;
            case 'MostrarRefaccionXEquipo':
                $resultado = $this->Seguimientos->mostrarRefaccionXEquipo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }

        echo json_encode($resultado);
    }

}
