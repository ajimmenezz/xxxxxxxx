<?php

use Librerias\V2\PaquetesDashboard\GestorDashboard as GestorDashboard;

class Controller_Dashboard extends CI_Controller {

    private $gestorDashboard;

    public function __construct() {
        parent::__construct();
        $this->gestorDashboard = new GestorDashboard();
    }

    public function getDatosDashboards() {
        $datos = $this->input->post();
        $resultado = $this->gestorDashboard->getDatosDashboards('1');
        echo json_encode($resultado);
    }

}
