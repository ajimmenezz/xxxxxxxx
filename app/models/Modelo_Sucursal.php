<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Sucursal extends Modelo_Base {

    public function getInformacion(string $idSucursal) {
        $consulta = parent::connectDBGapsi()->query("SELECT
                                                        ID,
                                                        Nombre
                                                    FROM db_Sucursales
                                                    where ID = " . $idSucursal);
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getGasto(string $idSucursal, array $datosProyecto) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Gasto 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE dr.Sucursal = " . $idSucursal . " 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['idProyecto'] . "'
                                                    AND TipoTrans = 'GASTO'
                                                    GROUP BY Sucursal");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompra(string $idSucursal, array $datosProyecto) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Compra 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE dr.Sucursal = " . $idSucursal . " 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['idProyecto'] . "'
                                                    AND dr.TipoTrans = 'COMPRA'
                                                    GROUP BY Sucursal");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

}
