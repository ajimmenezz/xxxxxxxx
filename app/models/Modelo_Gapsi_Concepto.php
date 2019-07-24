<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi_Concepto extends Modelo_Base {

    public function getInformacionConcepto(string $concepto) {
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

    public function getGastoConcepto(string $concepto, array $datosProyecto) {
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

    public function getCompraConcepto(string $concepto, array $datosProyecto) {
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

    public function getInformacionCategoria(string $categoria) {
        $consulta = parent::connectDBGapsi()->query("SELECT
                                                        ID,
                                                        TipoTrans,
                                                        Nombre
                                                    FROM db_Categorias
                                                    where Nombre = '" . $categoria . "'");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getGastoCategoria(string $categoria, array $datosProyecto) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Gasto 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.Categoria = '" . $categoria . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND TipoTrans = 'GASTO'
                                                    GROUP BY ddg.Categoria");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompraCategoria(string $categoria, array $datosProyecto) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("SELECT 
                                                        sum(ddg.Monto) AS Compra 
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE ddg.Categoria = '" . $categoria . "' 
                                                    and dr.StatusConciliacion = 'Conciliado' 
                                                    AND dr.Moneda = '" . $datosProyecto['moneda'] . "'
                                                    AND dr.Proyecto = '" . $datosProyecto['proyecto'] . "'
                                                    AND dr.TipoTrans = 'COMPRA'
                                                    GROUP BY ddg.Categoria");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

    public function getInformacionSubcategoria(string $subcategoria) {
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

    public function getGastoSubcategoria(string $subcategoria, array $datosProyecto) {
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

    public function getCompraSubcategoria(string $subcategoria, array $datosProyecto) {
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
