<?php

namespace Librerias\V2\PaquetesCatalogos;

use Librerias\V2\PaquetesCatalogos\Interfaces\Catalogo as Catalogo;
use Modelos\Modelo_Catalogos_Permisos as Modelo;

class Catalogo_Rechazo_Permisos implements Catalogo{
    
    private $DBCatalogoPermisos;
    private $registros = array('rechazos');
    private $tabla;

    function __construct() {
        $this->registros = array();
        $this->tabla = 'cat_v3_tipos_rechazos_ausencia_personal';
        $this->DBCatalogoPermisos = new Modelo();
        $this->setDatos();
    }
    
    private function setDatos() {
        $this->registros = $this->DBCatalogoPermisos->getRegistros($this->tabla);
    }

    public function getDatos() {
        return $this->registros;
    }

    public function setRegistro(array $datos) {
        $this->DBCatalogoPermisos->setRegistro($this->tabla,$datos);
        $this->setDatos();
    }

    public function actualizarRegistro(array $datos) {
        $this->DBCatalogoPermisos->actualizarRegistro($this->tabla,$datos);
        $this->setDatos();
    }

}