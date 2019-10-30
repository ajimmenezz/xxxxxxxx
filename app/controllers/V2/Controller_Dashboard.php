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
//        $resultado = $this->gestorDashboard->getDatosDashboards();
        $resultado = array('VGT' =>
            [
                ['Work', 0],
                ['Eat', 2],
                ['Commute', 2],
                ['Watch TV', 2],
                ['Sleep', 7]
            ]
        );
        echo json_encode($resultado);
    }

}
