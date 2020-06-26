<?php

use Controladores\Controller_Base as Base;

class Controller_Dashboard extends Base {
    
    private $dashboard;
    private $dashboardGeneral;


    public function __construct() {
        parent::__construct();        
        $this->dashboard = \Librerias\Logistica\Dashboard::factory(); 
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
            case 'Solicitudes_Generadas':
                $resultado = $this->dashboardGeneral->getSolicitudesGeneradas($this->input->post());
                break;             
            case 'Filtro_Rapido_Fecha':
                $resultado = $this->dashboardGeneral->getFiltrosFecha($this->input->post())[0];                
                break;
            case 'Servicios_Logistica':
                $resultado = $this->dashboardGeneral->getServiciosAreaLogistica($this->input->post());
                break;
            case 'Exporta_Servicios_Logistica':
                $resultado = $this->dashboard->exportarServiciosLogistica($this->input->post());
                break;
        }        
        echo json_encode($resultado);
    }

}
