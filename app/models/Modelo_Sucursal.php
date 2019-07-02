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

    public function getGasto(string $idSucursal, string $moneda) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("select 
                                                        sum(Importe) as Gasto 
                                                    from db_Registro 
                                                    where Sucursal = " . $idSucursal . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    and Moneda = '" . $moneda . "'");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompra(string $idSucursal, string $moneda) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("select 
                                                        sum(Importe) as Compra 
                                                    from db_Registro 
                                                    where Proyecto = " . $idSucursal . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    and Moneda = '" . $moneda . "'");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

}
