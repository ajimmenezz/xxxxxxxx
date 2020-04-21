<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Solicitud
 *
 * @author Alonso
 */
class Controller_Phantom extends Base {

    private $Servicio;
    private $TicketOld;
    private $Servicio4D;

    public function __construct() {
        parent::__construct();
        $this->Servicio = \Librerias\Generales\Servicio::factory();
        $this->TicketOld = \Librerias\Generales\TicketsOld::factory();
        $this->Servicio4D = \Librerias\Salas4D\Seguimiento::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

    public function servicioPhantom(string $servicio = null, string $datosExtra = NULL, string $idUsuario = NULL) {
        $tipo = $this->Servicio->getTipoByServicio($servicio);
        $verificarSeguimiento = $this->Servicio->verificarServiciosDepartamento($tipo[0]['IdTipoServicio']);
        $contenido = '';
        $titulo = '';
        $tipoServicio = $this->Servicio->getTipoByServicio($servicio)[0]['IdTipoServicio'];
        if ($verificarSeguimiento[0]['Seguimiento'] === '0') {
            $titulo = 'Resumen de Servicio';
            $contenido = $this->Servicio->getDetallesSinClasificar($servicio, true, null, $tipoServicio);
        } else {
            switch ($tipo[0]['IdTipoServicio']) {
                case '5': case 5:
                    $contenido = $this->Servicio->getTraficoHtmlToPdf($servicio);
                    $titulo = 'Resumen de Servicio - Tráfico';
                    break;
                case '6': case 6:
                    $nombreServicio = $this->Servicio4D->getNombreServicio($servicio);
                    $titulo = 'Resumen de Servicio - ' . $nombreServicio;
                    $contenido = $this->Servicio4D->getDetallesServicio4D($servicio, true);
                    break;
                case '7': case 7:
                    $nombreServicio = $this->Servicio4D->getNombreServicio($servicio);
                    $titulo = 'Resumen de Servicio - ' . $nombreServicio;
                    $contenido = $this->Servicio4D->getDetallesServicioCorrectivo4D($servicio, true);
                    break;
                case '10': case 10:
                    $contenido = $this->Servicio->getDetallesUber($servicio, true);
                    $titulo = 'Resumen de Servicio - Uber';
                    break;
                case '9': case 9:
                    $titulo = 'Resumen de Servicio - Sin Clasificar';
                    $contenido = $this->Servicio->getDetallesSinClasificar($servicio, true);
                    break;
                case '11': case 11:
                    $titulo = 'Resumen de Servicio - Censo';
                    $contenido = $this->Servicio->getDetallesCenso($servicio);
                    $data = ['html' => $contenido, 'titulo' => $titulo];
                    $this->load->view('Phantom/ServicioV2', $data);
                    return false;
                    break;
                case '12': case 12:
                    $titulo = 'Resumen de Servicio - Mantenimiento General';
                    if (is_null($datosExtra)) {
                        $contenido = $this->Servicio->getDetallesMantenimientoPoliza($servicio);
                        $data = ['html' => $contenido, 'titulo' => $titulo];
                        $this->load->view('Phantom/ServicioV2', $data);
                        return false;
                    } else {
                        $contenido = $this->Servicio->getDetallesMantenimientoPoliza($servicio, $datosExtra);
                    }
                    break;
                case '20': case 20:
                case '27': case 27:
                    if (is_null($datosExtra)) {
                        $titulo = 'Resumen de Servicio - Correctivo';
                        $contenido = $this->Servicio->getDetallesCorrectivo($servicio);
                    } else {
                        if ($datosExtra === 'Impericia') {
                            $titulo = 'Reporte Firmado - Correctivo';
                            $contenido = $this->Servicio->getDetallesImpericiaCorrectivo($servicio);
                        } elseif ($datosExtra === 'RetiroGarantiaRespaldo') {
                            $titulo = 'Retiro a Garantía con Respaldo - Correctivo';
                            $contenido = $this->Servicio->getDetallesRetiroGarantiaRespaldoCorrectivoPdf($servicio);
                        } else {
                            $titulo = 'Acuse de Entrega - Correctivo';
                            $contenido = $this->Servicio->getDetallesEntregaEquipoPdf($servicio);
                        }
                    }
                    break;
            }
        }

        $data = ['html' => $contenido, 'titulo' => $titulo];
        $this->load->view('Phantom/ServicioV2', $data);
    }

    public function ticketOldPhantom(string $ticket = null) {
        $tipo = $this->TicketOld->getTipoByTicket($ticket);
        $contenido = '';
        $titulo = '';
        switch ($tipo[0]['Tipo']) {
            case '1': case 1:
                $contenido = $this->TicketOld->getContenidoCorrectivo($ticket, true);
                $titulo = 'Resumen de Ticket - Correctivo';
                break;
        }
        $data = ['html' => $contenido, 'titulo' => $titulo];
        $this->load->view('Phantom/TicketOld', $data);
    }

    public function mostrarServiciosFolio(string $folio = null) {
        $titulo = 'Resumen de Vuelta';
        $contenido = $this->Servicio->informacionFolioPDF($folio, true);
        $data = ['html' => $contenido, 'titulo' => $titulo];
        $this->load->view('Phantom/ServicioV2', $data);
    }
}
