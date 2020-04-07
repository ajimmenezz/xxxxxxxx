<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Equipo extends Modelo_Base {
    
    public function __construct() {
        parent::__construct();
    }

    public function getRefaccionesEquipo(string $idModelo, string $where = '') {        
        $consulta = $this->consulta("SELECT 
                                        *
                                        FROM cat_v3_componentes_equipo
                                        WHERE IdModelo = '" . $idModelo . "'
                                        AND Flag = 1
                                        " . $where);
        return $consulta;
    }
    
    public function getRefaccionesEquipoRehabilitacion(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        cvce.Id, cvce.Nombre, ti.Serie, if(tirr.Bloqueado IS NULL, 0, 1) AS Bloqueado, ti.Id AS IdInventario
                                    FROM
                                        cat_v3_componentes_equipo cvce
                                            INNER JOIN
                                        cat_v3_modelos_equipo cvme ON cvme.Id = cvce.IdModelo
                                            INNER JOIN
                                        cat_v3_marcas_equipo cvm ON cvm.Id = cvme.Marca
                                            INNER JOIN
                                        t_inventario ti ON ti.IdProducto = cvce.Id
                                            LEFT JOIN
                                        t_inventario_rehabilitacion_refaccion AS tirr ON tirr.IdRefaccion = cvce.Id AND tirr.IdInventario = ti.Id
                                        INNER JOIN cat_v3_almacenes_virtuales cvav
                                        ON cvav.Id = ti.IdAlmacen
                                    WHERE
                                        cvm.Sublinea = (SELECT 
                                                SUBLINEABYMODELO(Id)
                                            FROM
                                                cat_v3_modelos_equipo
                                            WHERE
                                                Id = '" .  $datos['idEquipo'] . "')
                                            AND cvce.Flag = 1
                                            AND ti.IdTipoProducto = 2 "
                                            . $datos['where'] . " GROUP BY cvce.Id, ti.Serie");
        return $consulta;
    }

}
