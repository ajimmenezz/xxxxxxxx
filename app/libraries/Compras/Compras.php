<?php

namespace Librerias\Compras;

use Controladores\Controller_Base_General as General;

class Compras extends General {

    private $catalogo;
    private $gapsi;
    private $reportes;
    private $DBSAE;
    private $DBG;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->gapsi = \Librerias\Gapsi\Catalogos::factory();
        $this->reportes = \Librerias\SAEReports\Reportes::factory();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        $this->DBG = \Modelos\Modelo_Gapsi::factory();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioOrdenCompra() {
        $data = array();
        $ultimaClaveDocumentacion = $this->DBSAE->consultaUltimaClaveDocumentacion();

        $data = $this->datosFormularioOrdenCompra();
        $data['claveDocumentacion'] = $ultimaClaveDocumentacion[0]['CVE_DOC'];
        $data['claveGAPSI'] = $ultimaClaveDocumentacion[0]['CVE_GAPSI'];
        $data['ultimoDocumento'] = $ultimaClaveDocumentacion[0]['ULT_DOC'];

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

        $editarOrdenCompraGapsi = $this->DBG->consultaDatosGasto(array(
            'ordenCompra' => $ordenCompra
        ));

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
            $whereFecha = "WHERE FECHA_DOC between '" . $fecha . " 00:00:00' and '" . $fecha . " 23:59:59'";
        } else {
            switch ($datos['fecha']) {
                case 'hoy':
                    $whereFecha = "WHERE FECHA_DOC between '" . $fecha . " 00:00:00' and '" . $fecha . " 23:59:59'";
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

    public function consultaPartidasOrdenCompraAnteriores(array $datos) {
        $consulta = $this->DBSAE->consultaPartidasOrdenCompraAnteriores($datos['ordenCompra']);
        return $consulta;
    }

    public function consultaRequisicionesOrdenCompra(array $datos) {
        $consulta = $this->DBSAE->consultaRequisicionesOrdenCompra($datos['ordenCompra']);
        return $consulta;
    }

    public function guardarOrdenCompra(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datosExtra = array(
            'esquema' => $datos['esquema'],
            'tipoMoneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'descuento' => $datos['descuento']);
        $arraySubtotal = $this->subtotalTablaPartidas($datos['datosTabla'], $datosExtra);

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
                'Descripcion' => $arraySubtotal['descripcionGapsi'],
                'Importe' => $arraySubtotal['totalGapsi'],
                'Observaciones' => $arraySubtotal['observaciones'],
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

                if (!empty($gastoPDF)) {
                    $registroGapsi = $this->DBG->insertarArchivosGastosGapsi(array(
                        'idGapsi' => $idGapsi['last'],
                        'archivos' => $gastoPDF,
                        'idUsuario' => $usuario['Id'],
                        'email' => $usuario['EmailCorporativo']
                    ));

                    if (!empty($registroGapsi)) {
                        return '.' . $gastoPDF;
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function actualizarOrdenCompra(array $datos) {
        $datosExtra = array(
            'esquema' => $datos['esquema'],
            'tipoMoneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'descuento' => $datos['descuento']);
        $arraySubtotal = $this->subtotalTablaPartidas($datos['datosTabla'], $datosExtra);

        $consulta = $this->DBSAE->actualizarOrdenCompra($datos, $arraySubtotal);

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
                'Descripcion' => $arraySubtotal['descripcionGapsi'],
                'Importe' => $arraySubtotal['totalGapsi'],
                'Observaciones' => $datos['observaciones'],
                'Proyecto' => $datos['proyecto'],
                'Sucursal' => $datos['sucursal'],
                'Moneda' => $moneda,
                'OC' => $datos['claveOrdenCompra']
            );

            $resultadoOrdenCompra = $this->DBG->actualizarOrdenCompra($arrayGapsiOrdenCompra);

            if ($resultadoOrdenCompra) {
                $gastoPDF = $this->crearPDFGastoOrdenCompra(array('ordenCompra' => $datos['claveNuevaDocumentacion']));
                return $gastoPDF;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function subtotalTablaPartidas(array $datos, array $datosExtra) {
        $subtotal = '0.00';
        $descuentoPartida = '0.00';
        $descripcionGapsi = '';
        foreach ($datos as $key => $value) {
            $subtotal = (double) $subtotal + $value['subtotalPartida'];
            $porcentajeDescuento = $value['subtotalPartida'] * $value['descuento'] / 100;
            $descuentoPartida = $descuentoPartida + $porcentajeDescuento;
            $descripcionGapsi = $descripcionGapsi . $value['nombreProducto'] . ', ';
        }
        
        $ivaGapsi = number_format($subtotal * (int) $datosExtra['esquema'] / 100, 2, ".", "");
        $totalGapsi = ((($subtotal - $descuentoPartida) - ($subtotal * $datosExtra['descuento'] / 100)) - ($subtotal * $datosExtra['descuentoFinanciero'] / 100)) + $ivaGapsi;
        $subtotal = $subtotal * $datosExtra['tipoCambio'];
        $iva = number_format($subtotal * (int) $datosExtra['esquema'] / 100, 2, ".", "");
        $descuentoPartida = $descuentoPartida * $datosExtra['tipoCambio'];
        $descuento = ($subtotal * $datosExtra['descuento'] / 100) * $datosExtra['tipoCambio'];
        $descuentoFinanciero = ($subtotal * $datosExtra['descuentoFinanciero'] / 100) * $datosExtra['tipoCambio'];
        $total = ((($subtotal - $descuento) - $descuentoFinanciero) - $descuentoPartida) + $iva;

        return array(
            'subtotal' => $subtotal, 
            'iva' => $iva, 
            'descuento' => $descuento + $descuentoPartida, 
            'total' => $total, 
            'descuentoFinanciero' => $descuentoFinanciero, 
            'totalGapsi' => $totalGapsi,
            'descripcionGapsi' => $descripcionGapsi);
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

    public function verificarExisteOrdenCompra(array $datos) {
        $consulta = $this->DBSAE->consultaCOMPO($datos['ordenCompra']);
        return $consulta;
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
