<?php

use Librerias\V2\Factorys\FactoryCatalogos as Factory;


class Controller_Catalogos extends CI_Controller{

    private $factory;
    private $catalogoMotivosPermisos;
    private $catalogoRechazosPermisos;
    
    function __construct() {
        parent::__construct();
        $this->factory = new Factory();
    }
    
    public function nuevoRegistro(string $catalogo) {        
        $datos = $this->input->post();
        $respuesta = array();
        switch ($catalogo) {
            case 'Motivos':
                $this->catalogoMotivosPermisos = $this->factory->getCatalogo('CatalogoMotivoPermisos');
                $respuesta = $this->catalogoMotivosPermisos->setNuevoRegistro($datos);
                break;
            case 'Rechazos':
                $this->catalogoRechazosPermisos = $this->factory->getCatalogo('CatalogoRechazoPermisos');
                $respuesta = $this->catalogoRechazosPermisos->setNuevoRegistro($datos);
                break;

            default:
                break;
        }
        
        echo json_encode($respuesta);
    }

}
