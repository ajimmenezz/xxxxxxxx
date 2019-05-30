<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_GapsiGestorProyectos extends Modelo_Base {

    public function getProjects() {
        $query = parent::connectDBGapsi()->query("SELECT
                                                        dp.Tipo,
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
                                                    ID AS IdTipo,
                                                    Nombre AS Tipo,
                                                    (SELECT 
                                                            COUNT(DISTINCT Proyecto)
                                                        FROM  db_Registro
                                                        WHERE Tipo = dp.Nombre) AS Proyectos
                                                FROM db_Tipo dp
                                                ORDER BY dp.Nombre");

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
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
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
                                                    dr.TipoServicio,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
                                                WHERE 1=1
                                                " . $parameters . "
                                                Group by TipoServicio");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getBranchOfficesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    (SELECT ID FROM db_Sucursales WHERE Id = dr.Sucursal) AS IdSucursal,
                                                    (SELECT Nombre FROM db_Sucursales WHERE Id = dr.Sucursal) AS Sucursal,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
                                                WHERE 1=1
                                                " . $parameters . "
                                                Group by Sucursal");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getCategoriesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    (SELECT ID FROM db_Categorias WHERE Nombre = ddg.Categoria) AS IdCategoria,
                                                     ddg.Categoria,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
                                                WHERE 1=1
                                                " . $parameters . "
                                                Group by ddg.Categoria");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getSubcategoryByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    ddg.SubCategoria,
                                                    SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
                                                WHERE 1=1
                                                " . $parameters . "
                                                GROUP BY ddg.SubCategoria");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getConceptByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                Concepto,
                                                SUM(dr.Importe) AS Gasto
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.ID = dr.ID
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
                                                WHERE 1=1 
                                                " . $parameters . "
                                                GROUP BY ddg.Concepto");

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
                                                INNER JOIN db_TipoServicio dt
                                                ON dt.Nombre = dr.TipoServicio
                                                LEFT JOIN db_Categorias dc
                                                ON dc.Nombre = ddg.Categoria
                                                LEFT JOIN db_SubCategorias dsc
                                                ON dsc.Nombre = ddg.SubCategoria
                                                LEFT JOIN db_SubSubCategorias dssc
                                                ON dssc.Nombre = ddg.Concepto
                                                INNER JOIN db_Tipo dti
                                                ON dti.Nombre = dr.Tipo
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
