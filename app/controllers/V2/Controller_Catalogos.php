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
        $respuesta = array();
        switch ($catalogo) {
            case 'Motivos':
                $this->catalogoMotivosPermisos = $this->factory->getCatalogo('');
                break;
            case 'Rechazos':
                $this->catalogoMotivosPermisos = $this->factory->getCatalogo('');
                break;

            default:
                break;
        }
    }

}
