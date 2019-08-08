<?php

use Librerias\V2\Factorys\FactoryCatalogos as Factory;

class Controller_Catalogos extends CI_Controller {

    private $factory;
    private $catalogoMotivosPermisos;
    private $catalogoRechazosPermisos;

    function __construct() {
        parent::__construct();
        $this->factory = new Factory();
    }

    public function nuevoRegistro(string $catalogo) {
        $datos = $this->input->post();
        try {
            switch ($catalogo) {
                case 'Motivo':
                    $this->catalogoMotivosPermisos = $this->factory->getCatalogo('CatalogoMotivoPermisos');
                    $this->catalogoMotivosPermisos->setRegistro($datos);
                    $respuesta = $this->catalogoMotivosPermisos->getDatos();
                    break;
                case 'Rechazo':
                    $this->catalogoRechazosPermisos = $this->factory->getCatalogo('CatalogoRechazoPermisos');
                    $this->catalogoRechazosPermisos->setRegistro($datos);
                    $respuesta = $this->catalogoRechazosPermisos->getDatos();
                    break;

                default:
                    $respuesta = array();
                    break;
            }
        } catch (Exception $ex) {
            $respuesta = $ex->getMessage();
        }


        echo json_encode($respuesta);
    }
    
    public function actualizarRegistro(string $catalogo) {
        $datos = $this->input->post();
        try {
            switch ($catalogo) {
                case 'Motivo':
                    $this->catalogoMotivosPermisos = $this->factory->getCatalogo('CatalogoMotivoPermisos');
                    $this->catalogoMotivosPermisos->actualizarRegistro($datos);
                    $respuesta = $this->catalogoMotivosPermisos->getDatos();
                    break;
                case 'Rechazo':
                    $this->catalogoRechazosPermisos = $this->factory->getCatalogo('CatalogoRechazoPermisos');
                    $this->catalogoRechazosPermisos->actualizarRegistro($datos);
                    $respuesta = $this->catalogoRechazosPermisos->getDatos();
                    break;

                default:
                    $respuesta = array();
                    break;
            }
        } catch (Exception $ex) {
            $respuesta = $ex->getMessage();
        }


        echo json_encode($respuesta);
    }

}
