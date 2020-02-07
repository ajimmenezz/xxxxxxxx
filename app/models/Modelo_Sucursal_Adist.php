<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Sucursal_Adist extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getDatos(string $idSucursal) {
        
    }
    
    public function getAreas(){
        $consulta = $this->consulta('SELECT 
                                        Id, 
                                        Nombre 
                                    FROM cat_v3_areas_atencion 
                                    WHERE IdCliente = 1 
                                    AND Flag = 1;');
        return $consulta;
    }
}
