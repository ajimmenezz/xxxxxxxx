<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi_Proyecto extends Modelo_Base {

    public function getProjects() {
        $query = parent::connectDBGapsi()->query("SELECT
                                                        dp.ID AS IdProyecto,
                                                        dp.Descripcion,
                                                        (SELECT SUM(Importe) FROM db_Registro WHERE Proyecto = dp.ID) AS Gasto,
                                                        dp.FCreacion,
                                                        (SELECT ID FROM db_Tipo WHERE Nombre = dp.Tipo) AS IdTipo
                                                  FROM db_Proyectos dp");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes() {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                        dp.Tipo, 
                                                        count(dp.ID) Proyectos,
                                                        (SELECT ID FROM db_Tipo WHERE Nombre = dp.Tipo) AS IdTipo
                                                  FROM db_Proyectos AS dp
                                                  GROUP BY Tipo");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

}
