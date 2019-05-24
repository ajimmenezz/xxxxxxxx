<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Proyecto_Gapsi extends Modelo_Base {

    public function showAllProjects() {
        $query = parent::connectDBGapsi()->query("SELECT
                                                        dp.ID AS IdProyecto,
                                                        dp.Descripcion,
                                                        (SELECT SUM(Importe) FROM db_Registro WHERE Proyecto = dp.ID) AS Gasto,
                                                        dp.FCreacion
                                                  FROM db_Proyectos dp");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function showProjectTypes() {
        $query = parent::connectDBGapsi()->query("SELECT TOP (1000) 
                                                        dp.Tipo, 
                                                        count(dp.ID) Proyectos
                                                  FROM db_Proyectos AS dp
                                                  INNER JOIN db_Tipo AS dt
                                                  ON dt.Nombre = dp.Tipo
                                                  GROUP BY Tipo");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

}
