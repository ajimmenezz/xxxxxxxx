<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi_Subcategoria extends Modelo_Base {

    public function getInformacion(string $subcategoria) {
        $consulta = parent::connectDBGapsi()->query("SELECT
                                                        ID,
                                                        Categoria,
                                                        Nombre
                                                    FROM db_SubCategorias
                                                    where Nombre = '" . $subcategoria . "'");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getGasto(string $subcategoria, array $datosProyecto) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Gasto 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.SubCategoria = '" . $subcategoria . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND TipoTrans = 'GASTO'
                                                    GROUP BY ddg.SubCategoria");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompra(string $subcategoria, array $datosProyecto) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Compra 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.SubCategoria = '" . $subcategoria . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND dr.TipoTrans = 'COMPRA'
                                                    GROUP BY ddg.SubCategoria");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

}
