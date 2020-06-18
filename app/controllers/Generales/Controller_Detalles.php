<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Detalles
 *
 * @author Alberto Barcenas
 */
class Controller_Detalles extends Base {


    private $Detalles;

    public function __construct() {
        parent::__construct();
        $this->Detalles = \Librerias\Generales\Detalles::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

    public function detallesSolicitud(string $solicitud = null) {
        $this->Detalles->detallesSolicitud($solicitud);
    }
    public function detallesServicio(string $servicio = null) {
        $this->Detalles->detallesServicio($servicio);
    }

}
