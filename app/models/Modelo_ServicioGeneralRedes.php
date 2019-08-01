<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioGeneralRedes extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatosServicio(string $idServicio) {
        $consulta = array();
        try{            
        $consulta = $this->consulta('SELECT 
                                        * 
                                    FROM t_servicios_ticket 
                                    WHERE Id =' . $idServicio);
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function setFolioServiceDesk(string $idServicio) {
        
    }

}
