<?php

use Controladores\Controller_Base as Base;

class Controller_ReportesPoliza extends Base
{

    private $reportes;
    private $inventarios;

    public function __construct()
    {
        parent::__construct();
        $this->reportes = \Librerias\Poliza\Reportes::factory();
        $this->inventarios = \Librerias\Poliza\Inventario::factory();
    }

    /*
     * Se encarga se recibir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'exportaReporteProblemasFaltantesMantenimientos':
                $resultado = $this->reportes->exportaReporteProblemasFaltantesMantenimientos($this->input->post());
                break;
            case 'mostrarReporteProblemasFaltantesMantenimientos':
                $resultado = $this->reportes->mostrarReporteProblemasFaltantesMantenimientos($this->input->post());
                break;
            case 'consultaSucursalXRegionCliente':
                $resultado = $this->reportes->consultaSucursalXRegionCliente($this->input->post());
                break;
            case 'loadInventoryView':
                $resultado = $this->inventarios->loadFirstView($this->input->post());
                break;
            case 'loadInventoryDetails':
                $resultado = $this->inventarios->loadInventoryDetails($this->input->post());
                break;
        }
        echo \json_encode($resultado);
    }
}
