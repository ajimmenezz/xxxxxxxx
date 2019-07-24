<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_ProyectoGapsi extends Modelo_Base {

    public function getInformacion(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                         
                                                        Descripcion AS Nombre,
                                                        FCreacion As Fecha,
                                                        Tipo As TipoProyecto
                                                    FROM db_Proyectos
                                                    where ID = " . $idProyecto);
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getGasto(string $idProyecto, string $moneda) {
        $gasto = null;
        $consulta = parent::connectDBGapsi()->query("select 
                                                        sum(Importe) as Gasto 
                                                    from dbo.db_Registro 
                                                    where Proyecto = " . $idProyecto . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    and Moneda = '" . $moneda . "'  
                                                    and TipoTrans = 'GASTO'");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $gasto = $value['Gasto'];
            }
        }
        return $gasto;
    }

    public function getCompra(string $idProyecto, string $moneda) {
        $compra = null;
        $consulta = parent::connectDBGapsi()->query("select 
                                                        sum(Importe) as Compra 
                                                    from dbo.db_Registro 
                                                    where Proyecto = " . $idProyecto . " 
                                                    and StatusConciliacion = 'Conciliado' 
                                                    and Moneda = '" . $moneda . "' 
                                                    and TipoTrans = 'COMPRA'");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $compra = $value['Compra'];
            }
        }
        return $compra;
    }

    public function getUltimoMovimiento(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT
                                                            top 1 FCaptura
                                                      FROM db_Registro
                                                      WHERE Proyecto = " . $idProyecto . "
                                                      ORDER BY FCaptura DESC");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $key => $value) {
                $ultimoMovimiento = $value['FCaptura'];
            }
        }
        return $ultimoMovimiento;
    }

    public function getIdSucursales(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                      
                                                        Sucursal
                                                    FROM db_Registro
                                                    where Proyecto = '" . $idProyecto . "'
                                                    and StatusConciliacion = 'Conciliado'
                                                    GROUP BY Sucursal");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

    public function getServicios(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                      
                                                        TipoServicio
                                                    FROM db_Registro
                                                    where Proyecto = '" . $idProyecto . "'
                                                    and StatusConciliacion = 'Conciliado'
                                                    GROUP BY TipoServicio");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }
    
    public function getCategorias(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                      
                                                        ddg.Categoria
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE dr.Proyecto = '" . $idProyecto . "'
                                                    AND dr.StatusConciliacion = 'Conciliado'
                                                    GROUP BY ddg.Categoria");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }
    
    public function getSubcategorias(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                      
                                                        ddg.SubCategoria
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE dr.Proyecto = '" . $idProyecto . "'
                                                    AND dr.StatusConciliacion = 'Conciliado'
                                                    GROUP BY ddg.SubCategoria");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }
    
    public function getConceptos(string $idProyecto) {
        $consulta = parent::connectDBGapsi()->query("SELECT                                                                                                      
                                                        ddg.Concepto
                                                    FROM db_Registro AS dr
                                                    INNER JOIN db_DetalleGasto ddg
                                                    ON ddg.Gasto = dr.ID
                                                    WHERE dr.Proyecto = '" . $idProyecto . "'
                                                    AND dr.StatusConciliacion = 'Conciliado'
                                                    GROUP BY ddg.Concepto");
        if (!empty($consulta)) {
            return $consulta->result_array();
        }
        return array();
    }

}
