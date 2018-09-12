<?php

namespace Librerias\Tesoreria;

use Controladores\Controller_Datos_Usuario as General;

class Tesoreria extends General {

    private $DBST;
    private $DBT;
    private $DBP;
    private $poliza;
    private $correo;

    public function __construct() {
        parent::__construct();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBT = \Modelos\Modelo_Tesoreria::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->poliza = \Librerias\Poliza\Poliza::factory();
        $this->correo = \Librerias\Generales\Correo::factory();
        parent::getCI()->load->helper('date');
    }

    public function validarPuesto() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $usuario['IdPerfil'];
    }

    public function mostrarTablaDependiendoUsuario() {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['tablaVueltas'] = '';
        $data['tablaTesoreria'] = '';
        $data['vueltas'] = $this->poliza->resumenVueltasAsociadosFolio();
        $htmlTitulo = '';

        if (in_array('275', $usuario['PermisosAdicionales']) || in_array('275', $usuario['Permisos'])) {
            $htmlTitulo = '<div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Resumen de Vueltas</h3>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>';
            $data['tablaVueltas'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaSupervisoresPoliza", $data, TRUE);
            $data['tablaTesoreria'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaTesoreriaPagos", $data, TRUE);
        } else if (in_array('229', $usuario['PermisosAdicionales']) || in_array('229', $usuario['Permisos'])) {
            $htmlTitulo = '<div class="row">
                                <div class="col-md-6">
                                    <h3 class="m-t-10">Resumen de Vueltas</h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <a href="javascript:;" class="btn btn-success btn-lg " id="btnSubirFactura"><i class="fa fa-cloud-upload"></i> Subir Factura</a>
                                    </div>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>';
            $data['tablaVueltas'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaSupervisoresPoliza", $data, TRUE);
        } else if (in_array('228', $usuario['PermisosAdicionales']) || in_array('228', $usuario['Permisos'])) {
            $htmlTitulo = '<div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Vueltas Pendientes por Autorizar</h3>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>';
            $data['tablaVueltas'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaSupervisoresPoliza", $data, TRUE);
        } else if (in_array('227', $usuario['PermisosAdicionales']) || in_array('227', $usuario['Permisos'])) {
            $htmlTitulo = '<div class="row">
                                <div class="col-md-12">
                                    <h3 class="m-t-10">Resumen de Vueltas</h3>
                                </div>
                                <!--Empezando Separador-->
                                <div class="col-md-12">
                                    <div class="underline m-b-15 m-t-15"></div>
                                </div>
                            </div>';
            $data['tablaVueltas'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaSupervisoresPoliza", $data, TRUE);
        }

        if (in_array('281', $usuario['PermisosAdicionales']) || in_array('281', $usuario['Permisos'])) {
            $data['facturasTesoreriaPago'] = $this->DBT->facturasTesoreriaPago();
            $data['tablaTesoreria'] = parent::getCI()->load->view("Tesoreria/Formularios/TablaTesoreriaPagos", $data, TRUE);
        }

        $data['titulo'] = $htmlTitulo;

        return array('formulario' => parent::getCI()->load->view('/Tesoreria/Facturacion', $data, TRUE), 'datos' => $data);
    }

    public function formularioSubirFactura() {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['tablaFacturacionOutsourcingAutorizado'] = $this->DBT->tablaFacturacionOutsourcingAutorizado(array('usuario' => $usuario['Id']));
        return array('formulario' => parent::getCI()->load->view('/Tesoreria/Formularios/FormularioSubirFactura', $data, TRUE), 'datos' => $data);
    }

    public function detallesFactura(array $datos) {
        $data = array();
        $data['detallesFactura'] = $this->DBT->consultaDetallesFactura($datos['xml']);
        return array('formulario' => parent::getCI()->load->view('/Tesoreria/Formularios/TablaDetallesFactura', $data, TRUE), 'datos' => $data);
    }

    public function observacionesFactura(array $datos) {
        $consulta = $this->DBT->consultaObservacionesFactura($datos['id']);
        return $consulta;
    }

    public function formularioValidarVuelta(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['datosTablaVueltas'] = $datos['datosTabla'];
        $montosVueltasOutsourcing = $this->DBST->consultaGeneral('SELECT * FROM t_montos_x_vuelta_outsourcing');
        $viaticoSucursalOutsourcing = $this->DBST->consultaGeneral('SELECT
                                                                    (SELECT Monto FROM cat_v3_viaticos_outsourcing WHERE IdSucursal = tst.IdSucursal) Monto
                                                                    FROM t_servicios_ticket tst
                                                                    WHERE Id = "' . $datos['datosTabla'][1] . '"');
        $archivoVueltaOutsourcing = $this->DBST->consultaGeneral('SELECT Archivo FROM t_facturacion_outsourcing WHERE Id = "' . $datos['datosTabla'][0] . '"');

        if ($datos['datosTabla'][4] === '1') {
            $monto = $montosVueltasOutsourcing[0]['Monto'];
        } else {
            $monto = $montosVueltasOutsourcing[1]['Monto'];
        }

        $data['monto'] = $monto;
        $data['viatico'] = $viaticoSucursalOutsourcing[0]['Monto'];
        $data['archivo'] = $archivoVueltaOutsourcing[0]['Archivo'];
        $data['arregloUsuario'] = $usuario;
        return array('formulario' => parent::getCI()->load->view('/Tesoreria/Formularios/FormularioValidarVuelta', $data, TRUE), 'datos' => $data);
    }

    public function formularioPago(array $datos) {
        $rutaActual = getcwd();
        $data = array();
        $data['datosFactura'] = $this->DBT->consultaFacturaOutsourcingDocumantacion($datos['id']);
        $xml = simplexml_load_file($rutaActual . $data['datosFactura'][0]['XML']);
        $arrayEmisor = (array) $xml->xpath('//cfdi:Emisor');
        $arrayEmisor = (array) $arrayEmisor[0];
        $data['emisor'] = $arrayEmisor["@attributes"]['Nombre'];
        $arrayComprobante = (array) $xml->xpath('//cfdi:Comprobante');
        $arrayComprobante = (array) $arrayComprobante[0];
        $data['totalPago'] = $arrayComprobante["@attributes"]['Total'];

        return array('formulario' => parent::getCI()->load->view('/Tesoreria/Formularios/FormularioPago', $data, TRUE), 'datos' => $data);
    }

    public function guardarValidacionVuelta(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaEstatus = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $consulta = $this->DBST->actualizarServicio('t_facturacion_outsourcing', array(
            'FechaEstatus' => $fechaEstatus,
            'Monto' => $datos['monto'],
            'Viatico' => $datos['viatico'],
            'IdSupervisor' => $usuario['Id'],
            'Observaciones' => $datos['observaciones'],
            'IdEstatus' => '7'
                ), array('Id' => $datos['id'])
        );

        $factura = $this->DBT->consultaFactura($datos['id']);
        $vueltaFactura = (int) $factura['Vuelta'];

        if ($vueltaFactura > 1) {
            $correoCoordinadorPoliza = $this->DBP->consultaCorreoCoordinadorPoliza();
            $correoCoordinadorPoliza = (array) $correoCoordinadorPoliza[0]['EmailCorporativo'];

            $texto = '<p><strong>' . $factura['Supervisor'] . '</strong> autorizo la vuelta del Folio: <strong>' . $factura['Folio'] . '</strong>.
                    <br><br>Ver PDF Resumen Vuelta <a href="' . $factura['Archivo'] . '" target="_blank">Aquí</a>
                    <br><br>Ticket: <strong>' . $factura['Ticket'] . '</strong>
                    <br><br>Solicitud: <strong>' . $factura['IdSolicitud'] . '</strong>
                    <br>Asunto de la Solicitud: <strong>' . $factura['Asunto'] . '</strong>.
                    <br>Descripción de la Solicitud: <strong>' . $factura['DescripcionSolicitud'] . '</strong>.
                    <br><br>Servicio: <strong>' . $factura['IdServicio'] . '</strong>
                    <br>Descripción del Servicio: <strong>' . $factura['Descripcion'] . '</strong>.';
            $mensaje = $this->correo->mensajeCorreo('Autorización de Vuelta ' . $vueltaFactura . ' - ' . $factura['Folio'], $texto);
            $this->correo->enviarCorreo('notificaciones@siccob.solutions', $correoCoordinadorPoliza, 'Autorización de Vuelta ' . $vueltaFactura . ' - ' . $factura['Folio'], $mensaje);
        }

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function guardarEvidenciaPagoFactura(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'documentosAsociados/Tickets-' . $datos['tickets'] . '/EviendenciaPago/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasPago', $carpeta);
        $evidencias = implode(',', $archivos);
        $arrayEvidenciaPagoFactura = array(
            'evidencias' => $evidencias,
            'xml' => $datos['xml'],
            'fecha' => $fecha,
            'usuarioPaga' => $usuario['Id']);

        $consulta = $this->DBT->guardarEvidenciaPagoFactura($arrayEvidenciaPagoFactura);

        if (!empty($consulta)) {
            $correoTecnico = $this->DBT->consultaCorreoUsuario($consulta);
            $texto = '<p>Tesorería le ha realizado el pago de los tickest: <strong>' . $datos['tickets'] . '</strong>.';
            $mensaje = $this->correo->mensajeCorreo('Pago de Factura', $texto);
            $this->correo->enviarCorreo('notificaciones@siccob.solutions', array($correoTecnico), 'Pago de Factura', $mensaje);
            return $this->DBT->facturasTesoreriaPago();
        } else {
            return FALSE;
        }
    }

    public function rechazarVuelta(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fechaEstatus = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $constulta = $this->DBST->actualizarServicio('t_facturacion_outsourcing', array(
            'FechaEstatus' => $fechaEstatus,
            'IdSupervisor' => $usuario['Id'],
            'Observaciones' => $datos['observaciones'],
            'IdEstatus' => '10'
                ), array('Id' => $datos['id'])
        );
        if (!empty($constulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function guardarFacturaAsociado(array $datos) {
        $rutaActual = getcwd();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'documentosAsociados/Tickets-' . $datos['tickets'] . '/ArchivosFactura/';
        $archivos = setMultiplesArchivos($CI, 'evidenciasFacturaTesoreria', $carpeta);
        $resultadoComprobante = TRUE;
        $resultadoCFDI = TRUE;
        $resultadoConcepto = TRUE;
        $datosFacturasOutsourcing = $this->DBT->facturasOutsourcing($datos['listaIds']);
        $totalFactura = $this->totalVueltasFactura($datosFacturasOutsourcing);
        $nombreExtencion = '';

        foreach ($archivos as $key => $value) {
            $extencion = pathinfo($value, PATHINFO_EXTENSION);
            if ($nombreExtencion !== $extencion) {
                if ($extencion === 'xml') {
                    $xml = simplexml_load_file($rutaActual . $value);

                    $arrayComprobante = (array) $xml->xpath('//cfdi:Comprobante');
                    $arrayComprobante = (array) $arrayComprobante[0];

                    $resultadoComprobante = $this->validarXMLComprobante($arrayComprobante, $totalFactura);

                    $arrayReceptor = (array) $xml->xpath('//cfdi:Receptor');
                    $arrayReceptor = (array) $arrayReceptor[0];
                    $resultadoReceptor = $this->validarXMLReceptor($arrayReceptor);

                    $arrayConcepto = (array) $xml->xpath('//cfdi:Concepto');
                    $resultadoConcepto = $this->validarXMLConcepto($arrayConcepto, $datos['tickets']);
                }
            } else {
                $this->elimarArchivoFactura($archivos);
                return 'El tipo de archivo son iguales.';
            }
            $nombreExtencion = $extencion;
        }

        if ($resultadoComprobante === TRUE && $resultadoReceptor === TRUE && $resultadoConcepto === TRUE) {
            $arrayDocumentacion = array(
                'datosFacturasOutsourcing' => $datosFacturasOutsourcing,
                'tickets' => $datos['tickets'],
                'archivos' => $archivos,
                'carpeta' => $carpeta,
                'usuario' => $usuario['Id'],
                'fecha' => $fecha,
                'total' => $totalFactura);

            $facturasGuardadas = $this->DBT->guardarFacturaOutsourcingDocumentacion($arrayDocumentacion);

            if ($facturasGuardadas) {
                $this->elimarArchivoFactura($archivos);
                $proximoPago = date("d-m-Y", strtotime("next Friday"));
                $texto = '<p>El pago de la factura de los tickets <strong>' . $datos['tickets'] . '</strong> que ha realizado, se hará el viernes  <strong>' . $proximoPago . '</strong>.';
                $mensaje = $this->correo->mensajeCorreo('Fecha de Pago', $texto);
                $this->correo->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), 'Fecha de Pago', $mensaje);
                return $this->poliza->resumenVueltasAsociadosFolio();
            } else {
                $this->elimarArchivoFactura($archivos);
                return $facturasGuardadas;
            }
        } else {
            $this->elimarArchivoFactura($archivos);

            if ($resultadoComprobante !== TRUE) {
                return $resultadoComprobante;
            } else if ($resultadoReceptor !== TRUE) {
                return $resultadoReceptor;
            } else if ($resultadoConcepto !== TRUE) {
                return $resultadoConcepto;
            }
        }
    }

    public function elimarArchivoFactura(array $archivos) {
        foreach ($archivos as $key => $value) {
            eliminarArchivo($value);
        }
    }

    public function validarXMLComprobante(array $datos, float $total) {
        $resultadoComprobante = TRUE;

        foreach ($datos as $k => $nodoComprobante) {
            if (isset($nodoComprobante['MetodoPago'])) {
                if ($nodoComprobante['MetodoPago'] === 'PUE' || $nodoComprobante['MetodoPago'] === 'PUE - Pago en una Exhibición' || $nodoComprobante['MetodoPago'] === 'Pago en una Exhibición') {
                    $resultadoComprobante = TRUE;
                } else {
                    return 'El Metodo de Pago es incorrecto';
                }
            } else {
                return 'La etiqueta Metodo de pago no existe';
            }

            if (isset($nodoComprobante['Version'])) {
                if ($nodoComprobante['Version'] === '3.3' || $nodoComprobante['Version'] === 'V3.3' || $nodoComprobante['Version'] === 'V 3.3') {
                    $resultadoComprobante = TRUE;
                } else {
                    return 'La Version de XML es incorrecta';
                }
            } else {
                return 'La etiqueta Versión no existe';
            }

            if (isset($nodoComprobante['FormaPago'])) {
                if ($nodoComprobante['FormaPago'] === '03' || $nodoComprobante['FormaPago'] === 'Transferencia electrónica de fondos' || $nodoComprobante['FormaPago'] === '03 - Transferencia electrónica de fondos') {
                    $resultadoComprobante = TRUE;
                } else {
                    return 'La Forma de Pago es incorrecta';
                }
            } else {
                return 'La etiqueta Forma de Pago no existe';
            }

            if (isset($nodoComprobante['Total'])) {
                $totalFloat = (float) $nodoComprobante['Total'];

                if (round($totalFloat) === $total || round($totalFloat) === $total + 1) {
                    $resultadoComprobante = TRUE;
                } else {
                    return 'El Total de la factura es incorrecto';
                }
            } else {
                return 'La etiqueta Total no existe';
            }
        }
        return $resultadoComprobante;
    }

    public function validarXMLReceptor(array $datos) {
        $resultadoReceptor = TRUE;
        foreach ($datos as $k => $nodoReceptor) {
            if (isset($nodoReceptor['UsoCFDI'])) {
                if ($nodoReceptor['UsoCFDI'] === 'P01' || $nodoReceptor['UsoCFDI'] === 'Por definir') {
                    $resultadoReceptor = TRUE;
                } else {
                    return 'El Uso CFDI es incorrecto';
                }
            } else {
                return 'La etiqueta Uso CFDI no existe';
            }
            
            if (isset($nodoReceptor['Rfc'])) {
                if ($nodoReceptor['Rfc'] === 'SSO0101179Z7') {
                    $resultadoReceptor = TRUE;
                } else {
                    return 'El Rfc del Receptor es incorrecto';
                }
            } else {
                return 'La etiqueta Rfc del Receptor no existe';
            }
        }
        return $resultadoReceptor;
    }

    public function validarXMLConcepto(array $datos, string $tickets) {
        $arrayTickets = explode(',', $tickets);
        $resultadoConcepto = TRUE;
        $arrayCatalogoServicio = array('81111800', '81111812', '81112300', '81112308', '81112309', '81111803', '81111804', '72151605', '01010101');

        foreach ($datos as $k => $nodoConcepto) {
            $nodoConcepto = (array) $nodoConcepto;
            if (isset($nodoConcepto["@attributes"]['ClaveUnidad'])) {
                if ($nodoConcepto["@attributes"]['ClaveUnidad'] === 'E48' || $nodoConcepto["@attributes"]['ClaveUnidad'] === 'E048') {
                    $resultadoConcepto = TRUE;
                } else {
                    return 'La Clave de Unidad es incorrecta';
                }
            } else {
                return 'La etiqueta Clave de Unidad no existe';
            }

            if (isset($nodoConcepto["@attributes"]['ClaveProdServ'])) {
                if (in_array($nodoConcepto["@attributes"]['ClaveProdServ'], $arrayCatalogoServicio)) {
                    $resultadoConcepto = TRUE;
                } else {
                    return 'La Clave(s) del producto y/o servicio es incorrecto';
                }
            } else {
                return 'La etiqueta La Clave(s) del producto y/o servicio no existe';
            }

            if (isset($nodoConcepto["@attributes"]['Descripcion'])) {
                $stringTicket = strpos($nodoConcepto["@attributes"]['Descripcion'], 'TICKET');
                if ($stringTicket) {
                    $ticketEncontrado = $this->validarTicketDescripcion($arrayTickets, $nodoConcepto["@attributes"]['Descripcion']);
                    if ($ticketEncontrado) {
                        $resultadoConcepto = TRUE;
                    } else {
                        return 'Uno de los tickets es incorrecto';
                    }
                }
            } else {
                return 'La etiqueta Descripcion no existe';
            }
        }
        return $resultadoConcepto;
    }

    public function validarTicketDescripcion(array $arrayTickets, string $descripcion) {
        foreach ($arrayTickets as $key => $value) {
            $ticketEncontrado = strpos($descripcion, $value);
            if ($ticketEncontrado) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function totalVueltasFactura(array $datos) {
        $totalFactura = 0;

        foreach ($datos as $k => $v) {
            $montoIvaVuelta = number_format($v['Monto'] * 16 / 100, 2);
            $totalIvaMontoVuelta = $v['Monto'] + $montoIvaVuelta;
            $viaticoIvaVuelta = number_format($v['Viatico'] * 16 / 100, 2);
            $totalIvaViaticoVuelta = $v['Viatico'] + $viaticoIvaVuelta;
            $sumaMontoViatico = $totalIvaMontoVuelta + $totalIvaViaticoVuelta;
            $totalFactura = $totalFactura + $sumaMontoViatico;
        }

        return (float) $totalFactura;
    }

    public function evidenciaPagoFactura(array $datos) {
        $evidenciaPago = $this->DBT->evidenciaPagoFactura($datos['idVuelta']);

        return $evidenciaPago;
    }

}
