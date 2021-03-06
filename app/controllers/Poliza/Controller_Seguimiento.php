<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base
{
    private $Seguimientos;
    private $Servicio;
    private $notas;
    private $ServiciosGeneral;
    private $Catalogo;
    private $ServiciosTicket;
    private $seguimiento55;

    public function __construct()
    {
        parent::__construct();
        $this->Seguimientos = \Librerias\Poliza\Seguimientos::factory();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
        $this->ServiciosGeneral = \Librerias\Generales\Servicio::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Poliza = \Librerias\Poliza\Poliza::factory();
        $this->ServiciosTicket = \Librerias\Generales\ServiciosTicket::factory();
        $this->seguimiento55 = \Librerias\Poliza\Seguimientos\Seguimiento55::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null)
    {
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
                $resultado = $this->Seguimientos->mostrarVistaPorUsuario($this->input->post());
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
            case 'GuardarValidacionTecnico':
                $resultado = $this->Seguimientos->guardarValidacionTecnico($this->input->post());
                break;
            case 'VistaEnvioAlmacen':
                $resultado = $this->Seguimientos->vistaEnvioAlmacen($this->input->post());
                break;
            case 'GuardarEnvioAlmacen':
                $resultado = $this->Seguimientos->guardarEnvioAlmacen($this->input->post());
                break;
            case 'SolicitarGuia':
                $resultado = $this->Seguimientos->solicitarGuia($this->input->post());
                break;
            case 'AgregarComentarioSeguimientosEquipos':
                $resultado = $this->Seguimientos->agregarComentarioSeguimientosEquipos($this->input->post());
                break;
            case 'AgregarRecepcionesProblemasSeguimientosEquipos':
                $resultado = $this->Seguimientos->agregarRecepcionesProblemasSeguimientosEquipos($this->input->post());
                break;
            case 'CargaComentariosAdjuntos':
                $resultado = $this->Seguimientos->cargaComentariosAdjuntos($this->input->post());
                break;
            case 'CargaRecepcionesProblemas':
                $resultado = $this->Seguimientos->cargaRecepcionesProblemas($this->input->post());
                break;
            case 'GuardarRecepcionTecnico':
                $resultado = $this->Seguimientos->guardarRecepcionTecnico($this->input->post());
                break;
            case 'GuardarRecepcionLogistica':
                $resultado = $this->Seguimientos->guardarRecepcionLogistica($this->input->post());
                break;
            case 'GuardarRecepcionAlmacen':
                $resultado = $this->Seguimientos->guardarRecepcionAlmacen($this->input->post());
                break;
            case 'GuardarRecepcionLaboratorio':
                $resultado = $this->Seguimientos->guardarRecepcionLaboratorio($this->input->post());
                break;
            case 'GuardarRefacionUtilizada':
                $resultado = $this->Seguimientos->guardarRefacionUtilizada($this->input->post());
                break;
            case 'GuardarEnvioLogistica':
                $resultado = $this->Seguimientos->guardarEnvioLogistica($this->input->post());
                break;
            case 'GuardarEntregaLogistica':
                $resultado = $this->Seguimientos->guardarEntregaLogistica($this->input->post());
                break;
            case 'GuardarProblemaGuiaLogistica':
                $resultado = $this->Seguimientos->guardarProblemaGuiaLogistica($this->input->post());
                break;
            case 'EliminarRefacionUtilizada':
                $resultado = $this->Seguimientos->eliminarRefacionUtilizada($this->input->post());
                break;
            case 'ConsultaServiciosTecnico':
                $resultado = $this->Seguimientos->consultaServiciosTecnico($this->input->post());
                break;
            case 'ConcluirRevicionLaboratorio':
                $resultado = $this->Seguimientos->concluirRevicionLaboratorio($this->input->post());
                break;
            case 'SolicitarGuia':
                $resultado = $this->Seguimientos->solicitarGuia($this->input->post());
                break;
            case 'ValidarSolicitudEquipo':
                $resultado = $this->Seguimientos->validarSolicitudEquipo($this->input->post());
                break;
            case 'GuardarSolicitudProducto':
                $resultado = $this->Seguimientos->guardarSolicitudProducto($this->input->post());
                break;
            case 'CargaAreasPuntosCenso':
                $resultado = $this->Seguimientos->cargaAreasPuntosCenso($this->input->post());
                break;
            case 'AgregaAreaPuntosCenso':
                $resultado = $this->Seguimientos->agregaAreaPuntosCenso($this->input->post());
                break;
            case 'GuardaCambiosAreasPuntos':
                $resultado = $this->Seguimientos->guardaCambiosAreasPuntos($this->input->post());
                break;
            case 'CargaEquiposPuntoCenso':
                $resultado = $this->Seguimientos->cargaEquiposPuntoCenso($this->input->post());
                break;
            case 'CargaDiferenciasCenso':
                $resultado = $this->Seguimientos->cargaDiferenciasCenso($this->input->post());
                break;
            case 'CargaFormularioCapturaCenso':
                $resultado = $this->Seguimientos->cargaFormularioCapturaCenso($this->input->post());
                break;
            case 'GuardaEquiposPuntoCenso':
                $resultado = $this->Seguimientos->guardaEquiposPuntoCenso($this->input->post());
                break;
            case 'GuardarEquipoAdicionalCenso':
                $resultado = $this->Seguimientos->guardarEquipoAdicionalCenso($this->input->post());
                break;
            case 'EliminarEquiposAdicionalesCenso':
                $resultado = $this->Seguimientos->eliminarEquiposAdicionalesCenso($this->input->post());
                break;
            case 'GuardaCambiosEquiposAdicionalesCenso':
                $resultado = $this->Seguimientos->guardaCambiosEquiposAdicionalesCenso($this->input->post());
                break;
            case 'CargaFormularioCapturaAdicionalesCenso':
                $resultado = $this->Seguimientos->cargaFormularioCapturaAdicionalesCenso($this->input->post());
                break;
            case 'MostrarFormularioInformacionGeneracionGuia':
                $resultado = $this->Seguimientos->showFormInformationGenerationGuide($this->input->post());
                break;
            case 'SolicitarRefaccionLaboratorio':
                $resultado = $this->Seguimientos->requestLaboratoryReplacement($this->input->post());
                break;
            case 'AsignarRefaccionAlmacen':
                $resultado = $this->Seguimientos->assignSparePartToStore($this->input->post());
                break;
            case 'crearDatosCotizarOpcionRevision':
                $resultado = $this->Seguimientos->createDataQuoteFromRevisionOption($this->input->post());
                break;
            case 'enviarDatosCotizarOpcionRevision':
                $resultado = $this->Seguimientos->checkInsertSicsa($this->input->post());
                break;
            case 'guardarObservacionesBitacora':
                $resultado = $this->Seguimientos->guardarObservacionesBitacora($this->input->post());
                break;
            case 'varifiarBitacora':
                $resultado = $this->Seguimientos->verificarBitacoraReporteFalso($this->input->post());
                break;
            case 'MostrarServicios':
                $servicios = array('serviciosAsignados' => $this->ServiciosTicket->getServiciosAsignados($this->input->post('departamento'), $this->input->post('folio')));
                $resultado = $servicios;
                break;
            case 'InfomacionRestaurarCenso':
                $resultado = $this->Seguimientos->InformacionRestaurarCenso($this->input->post());
                break;
            case 'RestaurarCenso':
                $resultado = $this->Seguimientos->RestaurarCenso($this->input->post());
                break;
            case 'DownloadCensoTemplate':
                $resultado = $this->Seguimientos->DownloadCensoTemplate($this->input->post());
                break;
            case 'UploadCensoTemplate':
                $resultado = $this->Seguimientos->UploadCensoTemplate($this->input->post());
                break;
            case 'VerificarDuplicidadCenso':
                $resultado = $this->Seguimientos->verificarDuplicidadCenso($this->input->post());
                break;
            case 'ShowSOUpdateForm':
                $resultado = $this->seguimiento55->getSOUpdateForm($this->input->post());
                break;
            case 'SaveSOUpdateInfo':
                $resultado = $this->seguimiento55->saveSOUpdateInfo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }

        echo json_encode($resultado);
    }
}
