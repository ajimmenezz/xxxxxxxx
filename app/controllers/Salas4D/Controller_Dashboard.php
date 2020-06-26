<?php

use Controladores\Controller_Base as Base;

class Controller_Dashboard extends Base {

    private $dashboard;
    private $dashboardGeneral;

    public function __construct() {
        parent::__construct();
        $this->dashboard = \Librerias\Salas4D\Dashboard::factory();
        $this->dashboardGeneral = \Librerias\Generales\Dashboard::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            //Seccion Regiones Logistica
            case 'InfoInicial':
                $resultado = $this->dashboard->infoInicial($this->input->post());
                break;
            case 'CargaPanelByEstatus':
                $resultado = $this->dashboard->cargaPanelByEstatus($this->input->post());
                break;
            case 'CargaPanelByPrioridad':
                $resultado = $this->dashboard->cargaPanelByPrioridad($this->input->post());
                break;
            case 'CargaPanelByTipo':
                $resultado = $this->dashboard->cargaPanelByTipo($this->input->post());
                break;
            case 'CargaPanelByEstatusPrioridad':
                $resultado = $this->dashboard->cargaPanelByEstatusPrioridad($this->input->post());
                break;
            case 'CargaPanelByPrioridadEstatus':
                $resultado = $this->dashboard->cargaPanelByPrioridadEstatus($this->input->post());
                break;
            case 'CargaPanelByEstatusTipo':
                $resultado = $this->dashboard->cargaPanelByEstatusTipo($this->input->post());
                break;
            case 'CargaPanelByPrioridadTipo':
                $resultado = $this->dashboard->cargaPanelByPrioridadTipo($this->input->post());
                break;
            case 'CargaServiciosBySucursal':
                $resultado = $this->dashboard->cargaServiciosBySucursal($this->input->post());
                break;
            case 'CargaServiciosByAtiende':
                $resultado = $this->dashboard->cargaServiciosByAtiende($this->input->post());
                break;
            case 'CargaServiciosByEstatusS':
                $resultado = $this->dashboard->cargaServiciosByEstatus($this->input->post());
                break;
            case 'CargaUltimofiltro':
                $resultado = $this->dashboard->cargaUltimoFiltro($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
