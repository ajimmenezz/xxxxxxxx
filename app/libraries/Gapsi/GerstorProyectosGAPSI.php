<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GerstorProyectosGAPSI extends General {

    private $DBGestorProyectoGAPSI;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DBGestorProyectoGAPSI = \Modelos\Modelo_GapsiGestorProyectos::factory();
        $this->pdf = new \Librerias\Generales\PDFAux();
    }

    public function getListProjects(array $filters = []) {
        $parametersDate = $this->parametersDate($filters);
        $parameters = $this->parametersCurrency($filters);
        $parametersConciliation = $this->parametersConciliation($filters);
        $parameters = $parameters . $parametersDate . $parametersConciliation;
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjects($parameters);

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectTypes(array $filters = []) {
        $parametersDate = $this->parametersDate($filters);
        $parameters = $this->parametersCurrency($filters);
        $parametersConciliation = $this->parametersConciliation($filters);
        $parameters = $parameters . $parametersDate . $parametersConciliation;
        $dataProjects = $this->DBGestorProyectoGAPSI->getProjectTypes($parameters);

        if ($dataProjects['code'] === 200) {
            return $dataProjects['query'];
        } else {
            return ['code' => 400];
        }
    }

    public function getProjectsInfo(array $filters) {
        $dataProjectsInfo = array();
        $parametersDate = $this->parametersDate($filters);
        $parameters = $this->defineParameters($filters);
        $parametersConciliation = $this->parametersConciliation($filters);
        $parameters = $parameters . $parametersDate . $parametersConciliation;
        $proyectos = $this->DBGestorProyectoGAPSI->getProjectsByType($parameters);
        $servicios = $this->DBGestorProyectoGAPSI->getServicesByType($parameters);
        $sucursales = $this->DBGestorProyectoGAPSI->getBranchOfficesByType($parameters);
        $categorias = $this->DBGestorProyectoGAPSI->getCategoriesByType($parameters);
        $subcategorias = $this->DBGestorProyectoGAPSI->getSubcategoryByType($parameters);
        $concepto = $this->DBGestorProyectoGAPSI->getConceptByType($parameters);
        $gastosCompras = $this->DBGestorProyectoGAPSI->getExpensesAndPurchasesProject($parameters);

        $dataProjectsInfo['proyectos'] = $proyectos['query'];
        $dataProjectsInfo['servicios'] = $servicios['query'];
        $dataProjectsInfo['sucursales'] = $sucursales['query'];
        $dataProjectsInfo['categorias'] = $categorias['query'];
        $dataProjectsInfo['subcategorias'] = $subcategorias['query'];
        $dataProjectsInfo['concepto'] = $concepto['query'];
        $dataProjectsInfo['gastosCompras'] = $gastosCompras['query'];

        foreach ($dataProjectsInfo['proyectos'] as $key => $value) {
            array_push($dataProjectsInfo['proyectos'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['servicios'] as $key => $value) {
            array_push($dataProjectsInfo['servicios'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['sucursales'] as $key => $value) {
            array_push($dataProjectsInfo['sucursales'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['categorias'] as $key => $value) {
            array_push($dataProjectsInfo['categorias'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['subcategorias'] as $key => $value) {
            array_push($dataProjectsInfo['subcategorias'][$key], $filters['moneda']);
        }
        foreach ($dataProjectsInfo['gastosCompras'] as $key => $value) {
            array_push($dataProjectsInfo['gastosCompras'][$key], $filters['moneda']);
        }

        return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Filters', $dataProjectsInfo, TRUE), 'consulta' => $dataProjectsInfo);
    }

    private function parametersDate(array $filters) {
        if (!empty($filters['fechaInicio']) && !empty($filters['fechaFinal'])) {
            $parameters = " AND Fecha BETWEEN '" . $filters['fechaInicio'] . "' AND '" . $filters['fechaFinal'] . "'";
        } else {
            $parameters = '';
        }

        return $parameters;
    }

    private function parametersCurrency(array $filters) {
        if (!empty($filters['moneda'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'";
        } else {
            $parameters = "AND Moneda = 'MN'";
        }

        return $parameters;
    }

    private function parametersConciliation(array $filters) {
        if (!empty($filters)) {
            if (!empty($filters['conciliado'] && !empty($filters['sinConciliar']))) {
                $parameters = "";
            } elseif (!empty($filters['conciliado'])) {
                $parameters = "AND dr.StatusConciliacion = 'Conciliado'";
            } elseif (!empty($filters['sinConciliar'])) {
                $parameters = "AND dr.StatusConciliacion != 'Conciliar'";
            } else {
                $parameters = "AND dr.StatusConciliacion = 'Conciliado'";
            }
        } else {
            $parameters = "AND dr.StatusConciliacion = 'Conciliado'";
        }

        return $parameters;
    }

    private function defineParameters(array $filters) {
        if (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['sucursal']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal']) && !empty($filters['concepto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Concepto = '" . $filters['concepto'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['sucursal']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['categoria']) && !empty($filters['subcategoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'
                            AND ddg.SubCategoria = '" . $filters['subcategoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto']) && !empty($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['servicio']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'
                            AND dr.Sucursal= '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['categoria'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Categoria = '" . $filters['categoria'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['sucursal'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.Sucursal = '" . $filters['sucursal'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['servicio'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND dr.TipoServicio = '" . $filters['servicio'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['proyecto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND Proyecto = '" . $filters['proyecto'] . "'";
        } elseif (!empty($filters['tipoProyecto']) && !empty($filters['concepto'])) {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'
                            AND ddg.Concepto = '" . $filters['concepto'] . "'";
        } else {
            $parameters = "AND Moneda = '" . $filters['moneda'] . "'
                            AND dr.Tipo = '" . $filters['tipoProyecto'] . "'";
        }

        return $parameters;
    }

    public function getProjectRecords(array $filters) {
        $dataRecords = array();
        $parametersDate = $this->parametersDate($filters);
        $parameters = $this->defineParameters($filters);
        $parameters = $parameters . $parametersDate;
        $dataRecords = $this->DBGestorProyectoGAPSI->getProjectRecords($parameters);
        return $dataRecords;
    }

    public function htmlProjectRecords(array $filters) {
        $dataRecords = $this->getProjectRecords($filters);
        if ($dataRecords['code'] === 200) {
            return array('formulario' => parent::getCI()->load->view('Generales/Dashboard_Gapsi_Detalles', $dataRecords, TRUE), 'consulta' => $dataRecords);
        } else {
            return ['code' => 400];
        }
    }

    public function getDetailsList(array $filters) {
        $dataRecords = $this->getProjectRecords($filters);

        if ($dataRecords['code'] === 200) {
            $this->pdf->AddPage();
            $this->pdf->subTitulo('Detalles');

            foreach ($dataRecords['query'] as $key => $records) {
                $this->BasicTable(array(
                    'Clave Gasto',
                    'Proyecto',
                    'Tipo Proyecto',
                    'Servicio',
                    'Beneficiario',
                    'Importe',
                    'Moneda',
                    'Tipo',
                    'Fecha'), array(
                    array($records['ID'],
                        $records['Proyecto'],
                        $records['Tipo'],
                        $records['TipoServicio'],
                        $records['Beneficiario'],
                        $records['Importe'],
                        $records['Moneda'],
                        $records['TipoTrans'],
                        $records['Fecha']),
                ));
            }

            $carpeta = $this->pdf->definirArchivo('Gapsi/PDF', 'Lista de Registros');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);

            return $carpeta;
        } else {
            return ['code' => 400];
        }
    }

    public function BasicTable($header, $data) {
        $this->pdf->Ln();
        $ancho = ($this->pdf->GetPageWidth() - 20) / count($header);
        // Cabecera
        foreach ($header as $col) {
            $this->pdf->SetFont("Helvetica", "B", 6);
            $this->pdf->Cell($ancho, 2, utf8_decode($col), 0);
        }
        $this->pdf->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->pdf->SetFont("Helvetica", "", 4);
                $this->pdf->Cell($ancho, 4, utf8_decode($col), 0);
            }
            $this->pdf->Ln();
        }
    }

}
