<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_GapsiGestorProyectos extends Modelo_Base {

    public function getProjects(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    dr.Tipo,
                                                    SUM(dr.Importe) AS Gasto,
                                                    dr.Proyecto AS IdProyecto,
                                                    (SELECT Descripcion FROM db_Proyectos WHERE ID = dr.Proyecto) AS Descripcion,
                                                    (SELECT FCreacion FROM db_Proyectos WHERE ID = dr.Proyecto) AS FCreacion,
                                                    (SELECT
                                                            top 1 FCaptura
                                                      FROM db_Registro
                                                      WHERE Proyecto = dr.Proyecto
                                                      AND Tipo = dr.Tipo
                                                      ORDER BY FCaptura DESC) AS UltimoRegistro
                                                    FROM db_Registro dr
                                                    WHERE 1=1
                                                    AND dr.StatusConciliacion = 'Conciliado'
                                                    " . $parameters . "
                                                    GROUP BY dr.Tipo, dr.Proyecto
                                                    ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT 
                                                    COUNT(*) AS Proyectos,
                                                    Tipo,
                                                    SUM(Importe) AS Importe
                                                    FROM (
                                                    SELECT 
                                                    Tipo,
                                                    SUM(Importe) AS Importe
                                                    FROM db_Registro
                                                    WHERE 1=1
                                                    AND StatusConciliacion = 'Conciliado'
                                                    " . $parameters . "
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
                                                    SUM(dr.Importe) AS Gasto,
                                                    (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        GROUP BY Proyecto
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY Proyecto
                                                ORDER BY Gasto DESC");

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
                                                    SUM(dr.Importe) AS Gasto,
                                                    (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        Group by TipoServicio
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                Group by TipoServicio
                                                ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getBranchOfficesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT dr.Sucursal AS idSucursal,
                                                    CASE	
                                                        WHEN ((SELECT Nombre FROM db_Sucursales WHERE Id = dr.Sucursal) = '') THEN 'SIN NOMBRE DE SUCURSAL'
                                                        WHEN ((SELECT Nombre FROM db_Sucursales WHERE Id = dr.Sucursal) = NULL) THEN 'SIN NOMBRE DE SUCURSAL'
                                                        ELSE (SELECT Nombre FROM db_Sucursales WHERE Id = dr.Sucursal)
                                                    END AS Sucursal,
                                                    SUM(dr.Importe) AS Gasto,
                                                    (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        GROUP BY Sucursal
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY Sucursal
                                                ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getCategoriesByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    ISNULL(ddg.Categoria, 'SIN CATEGORIA') AS Categoria,
                                                    SUM(dr.Importe) AS Gasto,
                                                    (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        GROUP BY ddg.Categoria
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY ddg.Categoria
                                                ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getSubcategoryByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                    ISNULL(ddg.SubCategoria, 'SIN SUBCATEGORIA') AS SubCategoria,
                                                    SUM(dr.Importe) AS Gasto,
                                                    (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        GROUP BY ddg.SubCategoria
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY ddg.SubCategoria
                                                ORDER BY Gasto DESC");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

    public function getConceptByType(string $parameters) {
        $query = parent::connectDBGapsi()->query("SELECT
                                                ISNULL(Concepto, 'SIN CONCEPTO') AS Concepto,
                                                SUM(dr.Importe) AS Gasto,
                                                (SELECT
                                                            TOP 1 min(dr.FCaptura) AS FCaptura
                                                        FROM db_Registro AS dr
                                                        LEFT JOIN db_DetalleGasto AS ddg
                                                        ON ddg.ID = dr.ID
                                                        WHERE 1=1
                                                        AND dr.StatusConciliacion = 'Conciliado'
                                                        " . $parameters . "
                                                        GROUP BY ddg.Concepto
                                                        ORDER BY FCaptura ASC) AS FCaptura
                                                FROM db_Registro AS dr
                                                LEFT JOIN db_DetalleGasto ddg
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY ddg.Concepto
                                                ORDER BY Gasto DESC");

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
                                                ON ddg.Gasto = dr.ID
                                                WHERE 1=1
                                                AND dr.StatusConciliacion = 'Conciliado'
                                                " . $parameters . "
                                                GROUP BY dr.TipoTrans");

        if (!empty($query)) {
            return ['code' => 200, 'query' => $query->result_array()];
        } else {
            return ['code' => 400];
        }
    }

}
