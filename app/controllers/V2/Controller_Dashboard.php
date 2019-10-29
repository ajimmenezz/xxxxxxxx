<?php

use Librerias\V2\PaquetesDashboard\GestorDashboard as GestorDashboard;

class Controller_Dashboard extends CI_Controller {
    
    private $gestorDashboard;

    public function __construct() {
        parent::__construct();
    }

    public function getDatosDashboards() {
        $this->gestorDashboard = new GestorDashboard;
        $resultado = $this->input->post();
        $this->gestorDashboard->getDatosDashboards();
        echo json_encode($resultado);
    }
    
}
