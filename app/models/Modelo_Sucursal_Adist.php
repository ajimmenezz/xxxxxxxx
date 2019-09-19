<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Sucursal_Adist extends Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getDatos(string $idSucursal) {
        
    }
    
    public function getAreas(string $idSucursal){
        $consulta = $this->consulta('SELECT 
                                        tc.IdArea,
                                        tc.Punto,
                                        cvaa.Nombre as Area
                                    FROM t_censos tc inner join cat_v3_areas_atencion cvaa
                                    on tc.IdArea = cvaa.Id
                                    WHERE IdServicio = (select MAX(tcg.IdServicio) 
                                    from t_censos_generales tcg 
                                    inner join t_servicios_ticket tst
                                    on tcg.IdServicio = tst.Id
                                    WHERE tcg.IdSucursal = '.$idSucursal.'
                                    and tst.IdEstatus = 4)
                                    group by Area');
        return $consulta;
    }
}
