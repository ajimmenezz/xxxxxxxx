<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_GestorProyectos extends Modelo_Base {

    public function getListaProyectos(string $tipo = null) {

        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                         
                                                        dr.Proyecto AS IdProyecto                                        
                                                    FROM db_Registro dr                                                    
                                                    where dr.StatusConciliacion = 'Conciliado'
                                                    GROUP by  dr.Proyecto");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }

        return array();
    }

    public function getListTypeProyects() {
        
        $consulta = parent::connectDBGapsi()->query("select Nombre from dbo.db_Tipo");
        
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

}
