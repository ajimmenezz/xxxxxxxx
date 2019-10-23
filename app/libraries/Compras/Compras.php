<?php

namespace Librerias\Compras;

use Controladores\Controller_Base_General as General;

class Compras extends General
{

    private $catalogo;
    private $gapsi;
    private $reportes;
    private $DBSAE;
    private $DBG;
    private $usuario;
    private $DBCompras;
    private $Correo;

    public function __construct()
    {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->gapsi = \Librerias\Gapsi\Catalogos::factory();
        $this->reportes = \Librerias\SAEReports\Reportes::factory();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        $this->DBG = \Modelos\Modelo_Gapsi::factory();
        $this->DBCompras = \Modelos\Modelo_Compras::factory();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioOrdenCompra()
    {
        $data = array();
        $ultimaClaveDocumentacion = $this->DBSAE->consultaUltimaClaveDocumentacion();

        $data = $this->datosFormularioOrdenCompra();
        $data['claveDocumentacion'] = $ultimaClaveDocumentacion[0]['CVE_DOC'];
        $data['claveGAPSI'] = $ultimaClaveDocumentacion[0]['CVE_GAPSI'];
        $data['ultimoDocumento'] = $ultimaClaveDocumentacion[0]['ULT_DOC'];

        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE), 'datos' => $data);
    }

    public function mostrarEditarOrdenCompra(array $datos)
    {
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

    private function datosFormularioOrdenCompra()
    {
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

    public function mostrarDatosProyectos(array $datos)
    {
        $data = array();
        $data['sucursales'] = $this->gapsi->sucursalesByProyecto(array('id' => $datos['id']));
        return $data;
    }

    public function mostrarDatosBeneficiarios(array $datos)
    {
        $data = array();
        $data['beneficiarios'] = $this->gapsi->beneficiarioByTipo($datos);
        return $data;
    }

    public function consultaListaOrdenesCompra(array $datos = null)
    {
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

    public function consultaListaRequisiciones(array $datos)
    {
        $consulta = $this->DBSAE->consultaListaRequisiciones($datos['claveDocumento']);
        return $consulta;
    }

    public function consultaPartidasOrdenCompraAnteriores(array $datos)
    {
        $consulta = $this->DBSAE->consultaPartidasOrdenCompraAnteriores($datos['ordenCompra']);
        return $consulta;
    }

    public function consultaRequisicionesOrdenCompra(array $datos)
    {
        $consulta = $this->DBSAE->consultaRequisicionesOrdenCompra($datos['ordenCompra']);
        return $consulta;
    }

    public function guardarOrdenCompra(array $datos)
    {
        $usuario = $this->usuario->getDatosUsuario();
        $datosExtra = array(
            'esquema' => $datos['esquema'],
            'tipoMoneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'descuento' => $datos['descuento']
        );
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
                    mkdir($carpeta, 0775, true);
                }

                $gastoPDF = $this->reportes->generaOC(array(
                    'id' => '1',
                    'documento' => $datos['claveNuevaDocumentacion'],
                    'idGapsi' => $idGapsi['last']
                ));

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

    public function actualizarOrdenCompra(array $datos)
    {
        $datosExtra = array(
            'esquema' => $datos['esquema'],
            'tipoMoneda' => $datos['moneda'],
            'tipoCambio' => $datos['tipoCambio'],
            'descuentoFinanciero' => $datos['descuentoFinanciero'],
            'descuento' => $datos['descuento']
        );
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

    public function subtotalTablaPartidas(array $datos, array $datosExtra)
    {
        $subtotal = '0.00';
        $descuentoPartida = '0.00';
        $descripcionGapsi = '';

        foreach ($datos as $key => $value) {
            $subtotal = (double)$subtotal + $value['subtotalPartida'];
            $porcentajeDescuento = $value['subtotalPartida'] * $value['descuento'] / 100;
            $descuentoPartida = $descuentoPartida + $porcentajeDescuento;
            $descripcionGapsi = $descripcionGapsi . $value['nombreProducto'] . ', ';
        }

        $ivaGapsi = number_format($subtotal * (int)$datosExtra['esquema'] / 100, 2, ".", "");
        $subtotalGapsi = ((($subtotal - $descuentoPartida) - ($subtotal * $datosExtra['descuento'] / 100)) - ($subtotal * $datosExtra['descuentoFinanciero'] / 100));
        $totalGapsi = $subtotalGapsi + $ivaGapsi;
        $subtotal = $subtotal * $datosExtra['tipoCambio'];
        $descuentoPartida = $descuentoPartida * $datosExtra['tipoCambio'];
        $descuento = ($subtotal * $datosExtra['descuento'] / 100) * $datosExtra['tipoCambio'];
        $descuentoFinanciero = ($subtotal * $datosExtra['descuentoFinanciero'] / 100) * $datosExtra['tipoCambio'];
        $subtotal = ((($subtotal - $descuento) - $descuentoFinanciero) - $descuentoPartida);
        $iva = number_format($subtotal * (int)$datosExtra['esquema'] / 100, 2, ".", "");
        $total = $subtotal + $iva;

        return array(
            'subtotal' => $subtotal,
            'iva' => $iva,
            'descuento' => $descuento + $descuentoPartida,
            'total' => $total,
            'descuentoFinanciero' => $descuentoFinanciero,
            'totalGapsi' => $totalGapsi,
            'descripcionGapsi' => $descripcionGapsi
        );
    }

    public function crearPDFGastoOrdenCompra(array $datos)
    {
        $ordenCompra = $this->ordenCompraGapsi($datos['ordenCompra']);
        $idGapsi = $this->DBG->consultaIdOrdenCompra(array(
            'ordenCompra' => $ordenCompra
        ));

        if (!empty($idGapsi)) {
            $carpeta = './storage/Gastos/' . $idGapsi[0]['ID'] . '/PRE';

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0775, true);
            }

            $gastoPDF = $this->reportes->generaOC(array(
                'id' => '1',
                'documento' => $datos['ordenCompra'],
                'idGapsi' => $idGapsi[0]['ID']
            ));

            return '.' . $gastoPDF;
        } else {
            return FALSE;
        }
    }

    public function verificarExisteOrdenCompra(array $datos)
    {
        $consulta = $this->DBSAE->consultaCOMPO($datos['ordenCompra']);
        return $consulta;
    }

    private function ordenCompraGapsi(string $ordenCompra)
    {
        $ordenCompraResultado = $this->quitarCeros($ordenCompra);
        $ordenCompraResultado = 'OC' . $ordenCompraResultado;

        return $ordenCompraResultado;
    }

    private function quitarCeros(string $ordenCompra)
    {
        $ordenCompraResultado = str_replace('OC', '', $ordenCompra);
        $ordenCompraResultado = preg_replace('/^0+/', '', $ordenCompraResultado);

        return $ordenCompraResultado;
    }


    public function getSAEProducts()
    {
        $products = $this->DBCompras->getSAEProductos();
        return $products;
    }

    public function getListaMisSolicitudes(int $id = null, bool $autorizarSolicitud = false)
    {
        $solicitudes = $this->DBCompras->getListaMisSolicitudes($id, $autorizarSolicitud);
        return $solicitudes;
    }

    public function getPartidasSolicitudCompra(int $id = null)
    {
        $partidas = $this->DBCompras->getPartidasSolicitudCompra($id);
        return $partidas;
    }

    public function getListaSolicitudesPorAutorizar()
    {
        $solicitudes = $this->DBCompras->getListaSolicitudesPorAutorizar();
        return $solicitudes;
    }

    public function solicitarCompra(array $datos)
    {
        $resultado = $this->DBCompras->insertarSolicitudCompra($datos);
        if ($resultado['code'] == 500) {
            return $resultado;
        }

        $idSolicitud = $resultado['id'];

        $CI = parent::getCI();
        $carpeta = './storage/Gastos/SolicitudesCompra/' . $resultado['id'] . '/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosSolicitud', $carpeta, 'gapsi');
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $resultado = $this->DBCompras->actualizarArchivosSolicitud($resultado['id'], $archivos);

        if ($resultado['code'] == 200) {
            $solicitud = $this->getListaMisSolicitudes($idSolicitud)[0];
            $partidas = $this->getPartidasSolicitudCompra($idSolicitud);
            $jefes = $this->DBCompras->getJefesByEmpleado();
            $titulo = 'Solicitud de Compra ' . $idSolicitud;
            $textoInicial = '<p>El usuario ' . $jefes['nombre'] . ' ha  generado una solicitud de compra con la siguiente información:</p>'
                . '<p><strong> Cliente: </strong> ' . $solicitud['Cliente'] . '</p>'
                . '<p><strong> Proyecto: </strong> ' . $solicitud['Proyecto'] . '</p>'
                . '<p><strong> Sucursal: </strong> ' . $solicitud['Sucursal'] . '</p>'
                . '<p><strong> Descripción: </strong> ' . $solicitud['Descripcion'] . '</p>'
                . '<table style="width:90%; border-collapse: collapse;">'
                . ' <thead>'
                . '     <tr>'
                . '         <th style="border: 1px solid black;">Clave</th>'
                . '         <th style="border: 1px solid black;">Producto</th>'
                . '         <th style="border: 1px solid black;">Cantidad</th>'
                . '     </tr>'
                . ' </thead>'
                . ' <tbody>';
            foreach ($partidas as $kp => $vp) {
                $textoInicial .= ''
                    . '<tr>'
                    . ' <td style="border: 1px solid black;">' . $vp['ClaveSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['DescripcionSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['Cantidad'] . '</td>'
                    . '</tr>';
            }

            $textoInicial .= ''
                . ' </tbody>'
                . '</table>'
                . '';

            if (!empty($jefes['jefes'])) {
                foreach ($jefes['jefes'] as $key => $value) {
                    $datosJefe = $this->DBCompras->getGeneralInfoByUserID($value);
                    $texto = '<h4>Hola ' . $datosJefe['Nombre'] . '</h4>' . $textoInicial;
                    $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
                    $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datosJefe['EmailCorporativo']), $titulo, $mensaje);
                }
            } else {
                $texto = '<h4>Hola ' . $this->usuario['Nombre'] . '</h4>' . $textoInicial;
                $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($this->usuario['EmailCorporativo']), $titulo, $mensaje);
            }
        }

        return $resultado;
    }

    public function formularioEditarSolicitudCompra(array $datos)
    {
        $solicitud = $this->getListaMisSolicitudes($datos['id'])[0];
        $productosSolicitados = $this->getPartidasSolicitudCompra($datos['id']);
        $productosSAE = $this->getSAEProducts();

        foreach ($productosSolicitados as $key => $value) {
            foreach ($productosSAE as $kps => $vps) {
                if ($vps['Clave'] == $value['ClaveSAE']) {
                    unset($productosSAE[$kps]);
                    break;
                }
            }
        }

        if (in_array($solicitud['IdEstatus'], ["9", 9, "10", 10])) {
            $datos = [
                'solicitud' => $solicitud,
                'clientes' => $this->gapsi->getClientes(),
                'proyectos' => $this->gapsi->proyectosByCliente(['id' => $solicitud['IdCliente']])['proyectos'],
                'sucursales' => $this->gapsi->sucursalesByProyecto(['id' => $solicitud['IdProyecto']])['sucursales'],
                'productosSolicitados' => $productosSolicitados,
                'productosDisponibles' => $productosSAE,
            ];

            return [
                'html' => parent::getCI()->load->view('Compras/Formularios/editar_solicitud_compra', $datos, TRUE),
                'imginiciales' => explode(",", $solicitud['Archivos'])
            ];
        } else {
            $datos = [
                'solicitud' => $solicitud,
                'productosSolicitados' => $productosSolicitados
            ];

            return [
                'html' => parent::getCI()->load->view('Compras/Formularios/seguimiento_solicitud_compra', $datos, TRUE)
            ];
        }
    }

    public function formularioAutorizarSolicitudCompra(array $datos)
    {
        $solicitud = $this->getListaMisSolicitudes($datos['id'], true)[0];
        $productosSolicitados = $this->getPartidasSolicitudCompra($datos['id']);

        if ($solicitud['IdEstatus'] == "9") {
            $datos = [
                'solicitud' => $solicitud,
                'productosSolicitados' => $productosSolicitados,
                'autorizar' => true
            ];

            return [
                'html' => parent::getCI()->load->view('Compras/Formularios/seguimiento_solicitud_compra', $datos, TRUE)
            ];
        }
    }

    public function eliminarArchivosSolicitud(array $datos)
    {
        $archivos = $this->getListaMisSolicitudes($datos['extra']['idSolicitud'])[0]['Archivos'];
        $arrayArchivos = explode(",", $archivos);
        $nuevoArrayArchivos = [];

        foreach ($arrayArchivos as $key => $value) {
            if ($datos['key'] != $value) {
                array_push($nuevoArrayArchivos, $value);
            }
        }

        $nuevosArchivos = implode(",", $nuevoArrayArchivos);

        $resultado = $this->DBCompras->actualizarArchivosSolicitud($datos['extra']['idSolicitud'], $nuevosArchivos);
        if ($resultado['code'] == 200) {
            unlink("." . $datos['key']);
        }

        return $resultado;
    }

    public function guardarCambiosSolicitudCompra(array $datos)
    {
        $resultado = $this->DBCompras->guardarCambiosSolicitudCompra($datos);
        if ($resultado['code'] == 500) {
            return $resultado;
        }

        $archivosRegistrados = explode(",", $this->getListaMisSolicitudes($datos['idSolicitud'])[0]['Archivos']);

        $CI = parent::getCI();
        $carpeta = './storage/Gastos/SolicitudesCompra/' . $resultado['id'] . '/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'archivosSolicitud', $carpeta, 'gapsi');
            if ($archivos) {
                if (count($archivosRegistrados) > 0 && $archivosRegistrados[0] != '') {
                    $archivos = array_merge($archivosRegistrados, $archivos);
                }
                $archivos = implode(',', $archivos);
            }
        } else {
            $archivos = implode(',', $archivosRegistrados);
        }

        $resultado = $this->DBCompras->actualizarArchivosSolicitud($resultado['id'], $archivos);
        if ($resultado['code'] == 200) {
            $solicitud = $this->getListaMisSolicitudes($resultado['id'])[0];
            $partidas = $this->getPartidasSolicitudCompra($resultado['id']);
            $jefes = $this->DBCompras->getJefesByEmpleado();
            $titulo = 'Cambios en Solicitud de Compra ' . $resultado['id'];
            $textoInicial = '<p>El usuario ' . $jefes['nombre'] . ' ha modificado la información de una solicitud de compra por lo siguiente:</p>'
                . '<p><strong> Cliente: </strong> ' . $solicitud['Cliente'] . '</p>'
                . '<p><strong> Proyecto: </strong> ' . $solicitud['Proyecto'] . '</p>'
                . '<p><strong> Sucursal: </strong> ' . $solicitud['Sucursal'] . '</p>'
                . '<p><strong> Descripción: </strong> ' . $solicitud['Descripcion'] . '</p>'
                . '<table style="width:90%; border-collapse: collapse;">'
                . ' <thead>'
                . '     <tr>'
                . '         <th style="border: 1px solid black;">Clave</th>'
                . '         <th style="border: 1px solid black;">Producto</th>'
                . '         <th style="border: 1px solid black;">Cantidad</th>'
                . '     </tr>'
                . ' </thead>'
                . ' <tbody>';
            foreach ($partidas as $kp => $vp) {
                $textoInicial .= ''
                    . '<tr>'
                    . ' <td style="border: 1px solid black;">' . $vp['ClaveSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['DescripcionSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['Cantidad'] . '</td>'
                    . '</tr>';
            }

            $textoInicial .= ''
                . ' </tbody>'
                . '</table>'
                . '';

            if (!empty($jefes['jefes'])) {
                foreach ($jefes['jefes'] as $key => $value) {
                    $datosJefe = $this->DBCompras->getGeneralInfoByUserID($value);
                    $texto = '<h4>Hola ' . $datosJefe['Nombre'] . '</h4>' . $textoInicial;
                    $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
                    $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datosJefe['EmailCorporativo']), $titulo, $mensaje);
                }
            } else {
                $texto = '<h4>Hola ' . $this->usuario['Nombre'] . '</h4>' . $textoInicial;
                $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($this->usuario['EmailCorporativo']), $titulo, $mensaje);
            }
        }


        return $resultado;
    }

    public function autorizarSolicitudCompra(array $datos)
    {
        $resultado = $this->DBCompras->autorizarSolicitudCompra($datos);
        if ($resultado['code'] == 200) {
            $solicitud = $this->getListaMisSolicitudes($datos['id'])[0];
            $datosSolicitante = $this->DBCompras->getGeneralInfoByUserID($solicitud['IdUsuario']);
            $partidas = $this->getPartidasSolicitudCompra($datos['id']);
            $jefes = $this->DBCompras->getJefesByEmpleado();
            $titulo = 'Solicitud de Compra ' . $datos['id'] . ' AUTORIZADA';
            $textoInicial = '<p>El usuario ' . $jefes['nombre'] . ' ha autorizado la solicitud de compra ' . $datos['id'] . '</p>'
                . '<p><strong> Cliente: </strong> ' . $solicitud['Cliente'] . '</p>'
                . '<p><strong> Proyecto: </strong> ' . $solicitud['Proyecto'] . '</p>'
                . '<p><strong> Sucursal: </strong> ' . $solicitud['Sucursal'] . '</p>'
                . '<p><strong> Descripción: </strong> ' . $solicitud['Descripcion'] . '</p>'
                . '<table style="width:90%; border-collapse: collapse;">'
                . ' <thead>'
                . '     <tr>'
                . '         <th style="border: 1px solid black;">Clave</th>'
                . '         <th style="border: 1px solid black;">Producto</th>'
                . '         <th style="border: 1px solid black;">Cantidad</th>'
                . '     </tr>'
                . ' </thead>'
                . ' <tbody>';
            foreach ($partidas as $kp => $vp) {
                $textoInicial .= ''
                    . '<tr>'
                    . ' <td style="border: 1px solid black;">' . $vp['ClaveSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['DescripcionSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['Cantidad'] . '</td>'
                    . '</tr>';
            }

            $textoInicial .= ''
                . ' </tbody>'
                . '</table>'
                . '';

            if (!empty($jefes['jefes'])) {
                foreach ($jefes['jefes'] as $key => $value) {
                    $datosJefe = $this->DBCompras->getGeneralInfoByUserID($value);
                    $texto = '<h4>Hola ' . $datosJefe['Nombre'] . '</h4>' . $textoInicial;
                    $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
                    $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datosJefe['EmailCorporativo']), $titulo, $mensaje);
                }
            }        

            $texto = '<h4>Hola ' . $datosSolicitante['Nombre'] . '</h4>' . $textoInicial;
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datosSolicitante['EmailCorporativo']), $titulo, $mensaje);
        }
        return $resultado;
    }

    public function rechazarSolicitudCompra(array $datos)
    {
        $resultado = $this->DBCompras->rechazarSolicitudCompra($datos);
        if ($resultado['code'] == 200) {
            $solicitud = $this->getListaMisSolicitudes($datos['id'])[0];
            $datosSolicitante = $this->DBCompras->getGeneralInfoByUserID($solicitud['IdUsuario']);
            $partidas = $this->getPartidasSolicitudCompra($datos['id']);
            $jefes = $this->DBCompras->getJefesByEmpleado();
            $titulo = 'Solicitud de Compra ' . $datos['id'] . ' RECHAZADA';
            $textoInicial = '<p>El usuario ' . $jefes['nombre'] . ' ha RECHAZADO la solicitud de compra ' . $datos['id'] . '</p>'
                . '<p><strong> Rechazo: </strong> ' . $datos['motivos'] . '</p>'
                . '<p><strong> Cliente: </strong> ' . $solicitud['Cliente'] . '</p>'
                . '<p><strong> Proyecto: </strong> ' . $solicitud['Proyecto'] . '</p>'
                . '<p><strong> Sucursal: </strong> ' . $solicitud['Sucursal'] . '</p>'
                . '<p><strong> Descripción: </strong> ' . $solicitud['Descripcion'] . '</p>'
                . '<table style="width:90%; border-collapse: collapse;">'
                . ' <thead>'
                . '     <tr>'
                . '         <th style="border: 1px solid black;">Clave</th>'
                . '         <th style="border: 1px solid black;">Producto</th>'
                . '         <th style="border: 1px solid black;">Cantidad</th>'
                . '     </tr>'
                . ' </thead>'
                . ' <tbody>';
            foreach ($partidas as $kp => $vp) {
                $textoInicial .= ''
                    . '<tr>'
                    . ' <td style="border: 1px solid black;">' . $vp['ClaveSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['DescripcionSAE'] . '</td>'
                    . ' <td style="border: 1px solid black;">' . $vp['Cantidad'] . '</td>'
                    . '</tr>';
            }

            $textoInicial .= ''
                . ' </tbody>'
                . '</table>'
                . '';

            $texto = '<h4>Hola ' . $datosSolicitante['Nombre'] . '</h4>' . $textoInicial;
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($datosSolicitante['EmailCorporativo']), $titulo, $mensaje);
        }
        return $resultado;
    }
}
