<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Archivos
 *
 * @author Alberto
 */
class Controller_ServiceDesk extends Base {

    private $InformacionServicios;

    public function __construct() {
        parent::__construct();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
    }

    public function manejarEvento(string $evento = null) {
        try {
            switch ($evento) {
                case 'ValidarServicio':
                    $resultado = $this->InformacionServicios->validarServicio($this->input->post());
                    break;
                case 'GuardarInformacionSD':
                    $resultado = $this->InformacionServicios->verifyProcess($this->input->post());
                    break;
                case 'DatosSD':
                    $resultado = $this->InformacionServicios->datosSD($this->input->post('solicitud'));
                    break;
                case 'CatalogoUsuariosSD':
                    $resultado = $this->InformacionServicios->catalogoSD();
                    break;
                case 'PdfFromFolio':
                    $resultado = $this->InformacionServicios->pdfFromFolio($this->input->post());
                    break;
                default:
                    $resultado = FALSE;
                    break;
            }
            echo json_encode($resultado);
        } catch (\Exception $ex) {
            echo json_encode(array('code' => 400, 'message' => $ex->getMessage()));
        }
    }

}
