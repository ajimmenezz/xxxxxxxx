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
        $datos = array();
        $consulta = $this->DBAlmacenVirtual->getMaterial($this->idUsuario);
        
        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'], 
                'text' => $value['Producto'],
                'cantidad' => $value['Cantidad']));
            
        }
        return $datos;
    }
    
    public function updateAlmacen(array $material) {
        
    }
}
