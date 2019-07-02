<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_GestorProyectos extends Modelo_Base {

    public function getListaProyectos(array $datosProyectos) {

        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                         
                                                        dr.Proyecto AS IdProyecto                                        
                                                    FROM db_Registro dr                                                    
                                                    where dr.StatusConciliacion = 'Conciliado'
                                                    AND dr.Moneda = '" . $datosProyectos['moneda'] . "'
                                                    GROUP by  dr.Proyecto");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }

        return array();
    }

    public function getListTypeProyects(array $datosProyectos) {
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        Tipo AS Nombre
                                                    FROM db_Registro
                                                    WHERE Moneda = '" . $datosProyectos['moneda'] . "'
                                                    GROUP BY Tipo");

        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

}
