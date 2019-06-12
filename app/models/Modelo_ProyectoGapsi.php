<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_ProyectoGapsi extends Modelo_Base {

    public function getInformacion(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                         
                                                        Descripcion AS Nombre,
                                                        FCreacion As Fecha,
                                                        Tipo As TipoProyecto
                                                    FROM db_Proyectos
                                                    where ID = ".$idProyecto);
        if(!empty($consulta)){
            return $consulta->result_array();
        }
        
        return array();        
    }

}
