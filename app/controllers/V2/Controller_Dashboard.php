<?php

use Librerias\V2\PaquetesDashboard\GestorDashboard as GestorDashboard;

class Controller_Dashboard extends CI_Controller {

    private $gestorDashboard;

    public function __construct() {
        parent::__construct();
        $this->gestorDashboard = new GestorDashboard();
    }

    public function getDatosDashboards() {
        try {
            $resultado = $this->gestorDashboard->getDatosDashboards();
            echo json_encode($resultado);
        } catch (\Exception $ex) {
            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }

    public function getDatosActualizados() {
        try {
            $datos = $this->input->post();
            $metodo = 'getDatos' . $datos['clave'];
            $datos['nombreConsulta'] = $metodo;
            $resultado = $this->gestorDashboard->$metodo($datos);
            echo json_encode($resultado);
        } catch (\Exception $ex) {
            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }

}
