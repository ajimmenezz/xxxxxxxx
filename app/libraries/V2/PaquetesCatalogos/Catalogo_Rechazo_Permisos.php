<?php

namespace Librerias\V2\PaquetesCatalogos;

use Librerias\V2\PaquetesCatalogos\Interfaces\Catalogo as Catalogo;
use Modelos\Modelo_Catalogos_Permisos as Modelo;

class Catalogo_Rechazo_Permisos implements Catalogo{
    
    private $DBCatalogoPermisos;
    private $registros = array('rechazos');

    function __construct() {
        $this->registros = array();
        $this->DBCatalogoPermisos = new Modelo();
        $this->setDatos();
    }
    
    private function setDatos() {
        $this->registros = $this->DBCatalogoPermisos->getRegistros('cat_v3_tipos_rechazos_ausencia_personal');        
    }

    public function getDatos() {
        return $this->registros;
    }

    public function setRegistro(array $datos) {
        return 'Nuevo registro rechazo';
    }

}