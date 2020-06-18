<?php

namespace Librerias\V2\PaquetesCatalogos;

use Librerias\V2\PaquetesCatalogos\Interfaces\Catalogo as Catalogo;
use Modelos\Modelo_Catalogos_Permisos as Modelo;

class Catalogo_Cancelar_Permisos implements Catalogo {

    private $DBCatalogoPermisos;
    private $registros = array('cancelaciones');
    private $tabla;

    function __construct() {
        $this->DBCatalogoPermisos = new Modelo();
        $this->registros = array();
        $this->tabla = 'cat_v3_tipos_cancelacion_ausencia_personal';
        $this->setDatos();
    }

    private function setDatos() {
        $this->registros = $this->DBCatalogoPermisos->getRegistros($this->tabla);
    }

    public function getDatos() {
        return $this->registros;
    }

    public function setRegistro(array $datos) {
        $this->DBCatalogoPermisos->setRegistro($this->tabla, $datos);
        $this->setDatos();
    }

    public function actualizarRegistro(array $datos) {
        $this->DBCatalogoPermisos->actualizarRegistro($this->tabla, $datos);
        $this->setDatos();
    }

}
