<?php

namespace Librerias\Compras;

use Controladores\Controller_Base_General as General;

class Compras extends General {

    private $catalogo;
    private $gapsi;
    private $reportes;
    private $DBSAE;
    private $DBG;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->gapsi = \Librerias\Gapsi\Catalogos::factory();
        $this->reportes = \Librerias\SAEReports\Reportes::factory();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        $this->DBG = \Modelos\Modelo_Gapsi::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioOrdenCompra() {
        $data = array();
        $ultimaClaveDocumentacion = $this->DBSAE->consultaUltimaClaveDocumentacion();

        $data = $this->datosFormularioOrdenCompra();
        $data['claveDocumentacion'] = $ultimaClaveDocumentacion[0]['CVE_DOC'];
        $data['claveGAPSI'] = $ultimaClaveDocumentacion[0]['CVE_GAPSI'];
        $data['ultimoDocumento'] = $ultimaClaveDocumentacion[0]['ULT_DOC'];
//        $data['compo'] = NULL;

        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE), 'datos' => $data);
    }

    public function mostrarEditarOrdenCompra(array $datos) {
        $data = array();

        $data = $this->datosFormularioOrdenCompra();
        $data['claveDocumentacion'] = $datos['ordenCompra'];
        $data['claveGAPSI'] = $this->ordenCompraGapsi($datos['ordenCompra']);
        $data['ultimoDocumento'] = $this->quitarCeros($datos['ordenCompra']);

        $tablaCOMPO = $this->DBSAE->consultaCOMPO($datos['ordenCompra']);
        $tablaPARCOMPO = $this->DBSAE->consultaPAR_COMPO($datos['ordenCompra']);
        $tablaCOMPOCLIB = $this->DBSAE->consultaCOMPO_CLIB($datos['ordenCompra']);
        $tablaOBSDOCC = $this->DBSAE->consultaOBS_DOCC($tablaCOMPO[0]['CVE_OBS']);
        $data['compo'] = $tablaCOMPO[0];
        $data['parCompo'] = $tablaPARCOMPO;
        $data['compoClib'] = $tablaCOMPOCLIB[0];
        $data['obsDocc'] = $tablaOBSDOCC[0];

        if ($tablaCOMPO[0]['TIP_DOC_E'] === 'q') {
            $tablaPartidasEditar = $this->DBSAE->consultaPartidasEditar($datos['ordenCompra']);
            $data['partidasEditar'] = $tablaPartidasEditar;
        } 

        $ordenCompra = $this->ordenCompraGapsi($datos['ordenCompra']);
//        $editarOrdenCompraGapsi = $this->DBG->consultaIdOrdenCompra(array(
//            'ordenCompra' => $ordenCompra
//        ));
        $editarOrdenCompraGapsi = $this->DBG->consultaDatosGasto(array(
            'ordenCompra' => $ordenCompra
        ));
//        var_dump($editarOrdenCompraGapsi);

        $data['editarOrdenCompraGapsi'] = $editarOrdenCompraGapsi;
        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE), 'datos' => $data);
    }

    private function datosFormularioOrdenCompra() {
        $data = array();

        $data['proveedores'] = $this->DBSAE->consultaProveedoresSAE();
        $data['almacenes'] = $this->DBSAE->consultaAlmacenesSAE();
        $data['tiposMonedas'] = $this->DBSAE->consultaTipoMoneda();
        $data['productos'] = $this->DBSAE->consultaProductosSAE();
        $data['clientes'] = $this->gapsi->getClientes();
        $data['tiposServicio'] = $this->gapsi->getTiposServicio();
        $data['tiposBeneficiario'] = $this->gapsi->getTiposBeneficiario();
        $data['requisiciones'] = $this->DBSAE->consultaRequisiciones();

        return $data;
    }

    public function mostrarDatosProyectos(array $datos) {
        $data = array();
        $data['sucursales'] = $this->gapsi->sucursalesByProyecto(array('id' => $datos['id']));
        return $data;
    }

    public function mostrarDatosBeneficiarios(array $datos) {
        $data = array();
        $data['beneficiarios'] = $this->gapsi->beneficiarioByTipo($datos);
        return $data;
    }

    public function consultaListaOrdenesCompra(array $datos = null) {
        $fecha = mdate('%Y-%m-%d', now('America/Mexico_City'));
        if (empty($datos)) {
            $whereFecha = "WHERE FECHA_DOC = '" . $fecha . "'";
        } else {
            switch ($datos['fecha']) {
                case 'hoy':
                    $whereFecha = "WHERE FECHA_DOC = '" . $fecha . "'";
                    break;
                case 'esteMes':
                    $whereFecha = "WHERE FECHA_DOC >= DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)";
                    break;
                case 'mesAnterior':
                    $whereFecha = "WHERE FECHA_DOC >= DATEADD(mm,-1,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)) 
				AND FECHA_DOC < DATEADD(ms,-3,DATEADD(mm,0,DATEADD(mm,DATEDIFF(mm,0,GETDATE()),0)))";
                    break;
                case 'todas':
                    $whereFecha = "";
                    break;
                default:
                    $whereFecha = "WHERE FECHA_DOC = '" . $fecha . "'";
            }
        }

        $consulta = $this->DBSAE->consultaListaOrdenesCompra($whereFecha);
        return $consulta;
    }

    public function consultaListaRequisiciones(array $datos) {
        $consulta = $this->DBSAE->consultaListaRequisiciones($datos['claveDocumento']);
        return $consulta;
    }

    public function guardarOrdenCompra(array $datos) {
        $arraySubtotal = $this->subtotalTablaPartidas($datos['datosTabla'], $datos['esquema'], $datos['descuentoFinanciero']);
        $consulta = $this->DBSAE->guardarOrdenCompra($datos, $arraySubtotal);

        if ($consulta) {
            if ($datos['moneda'] === '1') {
                $moneda = 'MN';
            } else {
                $moneda = 'USD';
            }
            $arrayGapsiOrdenCompra = array(
                'Beneficiario' => $datos['textoBeneficiario'],
                'IDBeneficiario' => $datos['beneficiario'],
                'Tipo' => $datos['tipo'],
                'TipoTrans' => 'COMPRA',
                'TipoServicio' => $datos['textoTipoServicio'],
                'Descripcion' => $datos['observaciones'],
                'Importe' => $arraySubtotal['total'],
                'Observaciones' => $datos['observaciones'],
                'Proyecto' => $datos['proyecto'],
                'Sucursal' => $datos['sucursal'],
                'Moneda' => $moneda,
                'OC' => $datos['claveOrdenCompra']
            );

            $idGapsi = $this->DBG->ordenCompra($arrayGapsiOrdenCompra);
            if (!empty($idGapsi)) {
                $carpeta = './storage/Gastos/' . $idGapsi['last'] . '/PRE';

                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $gastoPDF = $this->reportes->generaOC(array(
                    'id' => '1',
                    'documento' => $datos['claveNuevaDocumentacion'],
                    'idGapsi' => $idGapsi['last']));

                return '.' . $gastoPDF;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function subtotalTablaPartidas(array $datos, string $esquema, string $descuentoFinanciero) {
        $subtotal = '0.00';
        $descuento = '0.00';
        foreach ($datos as $key => $value) {
            $subtotal = (double) $subtotal + $value['subtotalPartida'];
            $porcentajeDescuento = $value['subtotalPartida'] * $value['descuento'] / 100;
            $descuento = $descuento + $porcentajeDescuento;
        }

        $subtotal = number_format($subtotal, 2, ".", "");
        $iva = number_format($subtotal * (int) $esquema / 100, 2, ".", "");
        $total = ($subtotal + $iva) - $descuento - $descuentoFinanciero;

        return array('subtotal' => $subtotal, 'iva' => $iva, 'descuento' => $descuento, 'total' => $total);
    }

    public function crearPDFGastoOrdenCompra(array $datos) {
        $ordenCompra = $this->ordenCompraGapsi($datos['ordenCompra']);

        $idGapsi = $this->DBG->consultaIdOrdenCompra(array(
            'ordenCompra' => $ordenCompra
        ));
        if (!empty($idGapsi)) {
            $carpeta = './storage/Gastos/' . $idGapsi[0]['ID'] . '/PRE';

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $gastoPDF = $this->reportes->generaOC(array(
                'id' => '1',
                'documento' => $datos['ordenCompra'],
                'idGapsi' => $idGapsi[0]['ID']));

            return '.' . $gastoPDF;
        } else {
            return FALSE;
        }
    }

    private function ordenCompraGapsi(string $ordenCompra) {
        $ordenCompraResultado = $this->quitarCeros($ordenCompra);
        $ordenCompraResultado = 'OC' . $ordenCompraResultado;

        return $ordenCompraResultado;
    }

    private function quitarCeros(string $ordenCompra) {
        $ordenCompraResultado = str_replace('OC', '', $ordenCompra);
        $ordenCompraResultado = preg_replace('/^0+/', '', $ordenCompraResultado);

        return $ordenCompraResultado;
    }

}
