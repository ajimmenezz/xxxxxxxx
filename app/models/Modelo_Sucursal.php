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
        var_dump("SELECT 
                                                        sum(Importe) as Gasto 
                                                    FROM db_Registro 
                                                    WHERE Sucursal = " . $idSucursal . " 
                                                    AND StatusConciliacion = 'Conciliado' 
                                                    AND Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND TipoTrans = 'GASTO'
                                                    GROUP BY Sucursal");
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(Importe) as Gasto 
                                                    FROM db_Registro 
                                                    WHERE Sucursal = " . $idSucursal . " 
                                                    AND StatusConciliacion = 'Conciliado' 
                                                    AND Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND Moneda = '" . $datosProyecto['moneda'] . "'
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
        var_dump("SELECT 
                                                        sum(Importe) as Compra 
                                                    FROM db_Registro 
                                                    WHERE Sucursal = " . $idSucursal . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    AND Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND TipoTrans = 'COMPRA'
                                                    GROUP BY Sucursal");
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(Importe) as Compra 
                                                    FROM db_Registro 
                                                    WHERE Sucursal = " . $idSucursal . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    AND Tipo = '" . $datosProyecto['tipoProyecto'] . "'
                                                    AND Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND TipoTrans = 'COMPRA'
                                                    GROUP BY Sucursal");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

}
