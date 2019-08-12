<?php

namespace Librerias\V2\PaquetesAlmacen;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_AlmacenVirtual as Modelo;

class AlmacenVirtual {

    private $idUsuario;
    private $DBAlmacenVirtual;

    public function __construct() {
        $this->DBAlmacenVirtual = new Modelo();
        $this->idUsuario = Usuario::getId();
    }
    
    public function getAlmacen() {
        
    }
}
