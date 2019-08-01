<?php

namespace Librerias\V2\PaquetesCatalogos;

use Librerias\V2\PaquetesCatalogos\Interfaces\Catalogo as Catalogo;
use Modelos\Modelo_Catalogos_Permisos as Modelo;

class Catalogo_Motivos_Permisos implements Catalogo{
    
    private $DBCatalogoPermisos;
    private $registros = array('motivos');

    function __construct() {
        $this->registros = array();
        $this->DBCatalogoPermisos = new Modelo();
        $this->setDatos();
    }
    
    private function setDatos() {
        $this->registros = $this->DBCatalogoPermisos->getRegistros('cat_v3_motivos_ausencia_personal');        
    }

    public function getDatos() {
        return $this->registros;
    }

}