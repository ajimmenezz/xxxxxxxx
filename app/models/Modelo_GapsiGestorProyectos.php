<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_GapsiGestorProyectos extends Modelo_Base {

    public function getProjects() {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    dr.Tipo,
                                                    SUM(dr.Importe) AS Gasto,
                                                    dr.Proyecto AS IdProyecto,
                                                    (SELECT Descripcion FROM db_Proyectos WHERE ID = dr.Proyecto) AS Descripcion,
                                                    (SELECT FCreacion FROM db_Proyectos WHERE ID = dr.Proyecto) AS FCreacion
                                                    FROM db_Registro dr
                                                    WHERE dr.Moneda = 'MN'
                                                    GROUP BY dr.Tipo, dr.Proyecto
                                                    ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes() {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    COUNT(*) AS Proyectos,
                                                    Tipo,
                                                    SUM(Importe) AS Importe
                                                    FROM (
                                                    SELECT 
                                                    Tipo,
                                                    SUM(Importe) AS Importe
                                                    FROM db_Registro
                                                    WHERE Moneda = 'MN'
                                                    GROUP BY Tipo, Proyecto) AS T
                                                    GROUP BY T.Tipo
                                                    ORDER BY Importe DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectsByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    Proyecto AS IdProyecto,
                                                    (SELECT Descripcion FROM db_Proyectos WHERE ID = dr.Proyecto) AS Proyecto,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1
                                                " . $parameters . "
                                                Group by Proyecto");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getServicesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    (SELECT ID FROM db_TipoServicio WHERE Nombre = dr.TipoServicio) AS IdServicio,
                                                    ISNULL(dr.TipoServicio, 'SIN SERVICIO') AS TipoServicio,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1
                                                " . $parameters . "
                                                Group by TipoServicio
                                                ORDER BY TipoServicio ASC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getBranchOfficesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    (SELECT ID FROM db_Sucursales WHERE Id = dr.Sucursal) AS IdSucursal,
                                                    ISNULL((SELECT Nombre FROM db_Sucursales WHERE Id = dr.Sucursal), 'SIN SUCURSAL') AS Sucursal,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1
                                                " . $parameters . "
                                                GROUP BY Sucursal
                                                ORDER BY Sucursal");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getCategoriesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    (SELECT ID FROM db_Categorias WHERE Nombre = ddg.Categoria) AS IdCategoria,
                                                    ISNULL(ddg.Categoria, 'SIN CATEGORIA') AS Categoria,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1
                                                " . $parameters . "
                                                GROUP BY ddg.Categoria
                                                ORDER BY ddg.Categoria");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getSubcategoryByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    ISNULL(ddg.SubCategoria, 'SIN SUBCATEGORIA') AS SubCategoria,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1
                                                " . $parameters . "
                                                GROUP BY ddg.SubCategoria
                                                ORDER BY ddg.SubCategoria");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getConceptByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                ISNULL(Concepto, 'SIN CONCEPTO') AS Concepto,
                                                SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1 
                                                " . $parameters . "
                                                GROUP BY ddg.Concepto
                                                ORDER BY ddg.Concepto");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getExpensesAndPurchasesProject(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    dr.TipoTrans,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                WHERE 1=1 
                                                " . $parameters . "
                                                GROUP BY dr.TipoTrans");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

}
