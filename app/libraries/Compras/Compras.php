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

        $data['claveNuevaDocumentacion'] = $ultimaClaveDocumentacion[0]['CVE_DOC'];
        $data['claveGAPSI'] = $ultimaClaveDocumentacion[0]['CVE_GAPSI'];
        $data['ultimoDocumento'] = $ultimaClaveDocumentacion[0]['ULT_DOC'];
        $data['proveedores'] = $this->DBSAE->consultaProveedoresSAE();
        $data['almacenes'] = $this->DBSAE->consultaAlmacenesSAE();
        $data['tiposMonedas'] = $this->DBSAE->consultaTipoMoneda();
        $data['productos'] = $this->DBSAE->consultaProductosSAE();
        $data['clientes'] = $this->gapsi->getClientes();
        $data['tiposServicio'] = $this->gapsi->getTiposServicio();
        $data['requisiciones'] = $this->DBSAE->consultaRequisiciones();

        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE), 'datos' => $data);
    }

    public function mostrarDatosProyectosBeneficiarios(array $datos) {
        $data = array();
        $data['sucursales'] = $this->gapsi->sucursalesByProyecto(array('id' => $datos['id']));
        $data['beneficiarios'] = $this->gapsi->beneficiarioByTipo(array('id' => '2', 'proyecto' => $datos['id']));
        return $data;
    }

    public function consultaListaOrdenesCompra() {
        $consulta = $this->DBSAE->consultaListaOrdenesCompra();
        return $consulta;
    }

    public function consultaListaRequisiciones(array $datos) {
        $consulta = $this->DBSAE->consultaListaRequisiciones($datos['claveDocumento']);
        return $consulta;
    }

    public function ceros($cadena) {
        if (preg_match("/0([^0])/", $cadena, $match)) {
            return strpos($cadena, $match[1]);
        } else {
            return 0;
        }
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
        $ordenCompra = str_replace(0, '', $datos['ordenCompra']);

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

}
