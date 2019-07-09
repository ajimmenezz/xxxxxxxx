<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi_Concepto extends Modelo_Base {

    public function getInformacion(string $concepto) {
        $consulta = parent::connectDBGapsi()->query("SELECT
                                                        ID,
                                                        SubCategoria,
                                                        Nombre
                                                    FROM db_SubSubCategorias
                                                    where Nombre = '" . $concepto . "'");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getGasto(string $concepto, array $datosProyecto) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Gasto 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.Concepto = '" . $concepto . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND TipoTrans = 'GASTO'
                                                    GROUP BY ddg.Concepto");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompra(string $concepto, array $datosProyecto) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Compra 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.Concepto = '" . $concepto . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND dr.TipoTrans = 'COMPRA'
                                                    GROUP BY ddg.Concepto");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

}
