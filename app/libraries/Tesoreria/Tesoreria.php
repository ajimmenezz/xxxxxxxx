<?php

namespace Librerias\Tesoreria;

use Controladores\Controller_Datos_Usuario as General;

class Tesoreria extends General {

    private $DBST;

    public function __construct() {
        parent::__construct();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        parent::getCI()->load->helper('date');
    }

    public function validarPuesto() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $usuario['IdPerfil'];
    }

    public function mostrarFormularioDocumentacionFacturacion(array $datos) {
        $data = array();
        $facturacionAsociados = $this->facturasAsociados($datos['ticket']);
        $contadorFacturacionAsociados = count($facturacionAsociados);

        if ($contadorFacturacionAsociados > 0) {
            $idCoordinador = $facturacionAsociados[0]['IdCoordinador'];
            $idSupervisor = $facturacionAsociados[0]['IdSupervisor'];
            $idTesoreria = $facturacionAsociados[0]['IdTesoreria'];
            $idRechaza = $facturacionAsociados[0]['IdUsuarioRechaza'];
            $data['datosFacturacionAsociados'] = $facturacionAsociados[0];
            $data['DocumentoPDF'] = explode(',', $facturacionAsociados[0]['Pdf']);
            $data['DocumentoXML'] = explode(',', $facturacionAsociados[0]['Xml']);
            $data['NombreCoordinador'] = ($idCoordinador !== NULL) ? $this->nombreUsuario($idCoordinador) : NULL;
            $data['NombreSupervisor'] = ($idSupervisor !== NULL) ? $this->nombreUsuario($idSupervisor) : NULL;
            $data['NombreTesoreria'] = ($idTesoreria !== NULL) ? $this->nombreUsuario($idTesoreria) : NULL;
            $data['NombreRechaza'] = ($idRechaza !== NULL) ? $this->nombreUsuario($idRechaza) : NULL;
        } else {
            $data['DocumentoPDF'] = NULL;
            $data['DocumentoXML'] = NULL;
            $data['NombreCoordinador'] = NULL;
            $data['NombreSupervisor'] = NULL;
        }
        return array('formulario' => parent::getCI()->load->view('Tesoreria/Formularios/FormularioDocumentacionFacturacion', $data, TRUE), 'datos' => $data);
    }

    public function consultaIdOrdenIngeniero(string $usuario) {
        $perfil = $this->validarPuesto();
        $data = [];
        if ($perfil !== '36') {
            $nombreUsuario = $this->nombreUsuario($usuario);
            $buscarPorNombre = ($perfil !== '83') ? '' : ' AND Nombre LIKE "' . $nombreUsuario[0]['NombreUsuario'] . '"';
            $buscarEstatus = ($perfil !== '36') ? '' : '' . $nombreUsuario[0]['NombreUsuario'] . '"';

            $consultaAdist2TicketIngeniero = $this->DBST->consultaQueryAdist2('SELECT 
                                                        ts.Id_Orden,
                                                        ts.Ingeniero,
                                                        ts.Estatus,
                                                        ct.Nombre AS NombreIngeniero 
                                                    FROM t_servicios ts
                                                    INNER JOIN cat_tecnicos ct
                                                    ON ct.Id = ts.Ingeniero
                                                    WHERE ts.F_Start >= "20170714"
                                                    AND ts.Estatus = "Concluido"'
                    . $buscarPorNombre);

            foreach ($consultaAdist2TicketIngeniero as $key => $value) {
                $facturacionAsociados = $this->facturasAsociados($value['Id_Orden']);
                $data[$key]['Ticket'] = $value['Id_Orden'];
                $data[$key]['Ingeniero'] = $value['NombreIngeniero'];
                $contadorFacturacionAsociados = count($facturacionAsociados);
                if ($contadorFacturacionAsociados > 0) {
                    $data[$key]['FechaDocumentacion'] = ($facturacionAsociados[0]['FechaDocumentacion'] !== NULL) ? $facturacionAsociados[0]['FechaDocumentacion'] : '-';
                    $data[$key]['FechaValidacionSup'] = ($facturacionAsociados[0]['FechaValidacionSup'] !== NULL) ? $facturacionAsociados[0]['FechaValidacionSup'] : '-';
                    $data[$key]['FechaValidacionCoord'] = ($facturacionAsociados[0]['FechaValidacionCoord'] !== NULL) ? $facturacionAsociados[0]['FechaValidacionCoord'] : '-';
                    $data[$key]['FechaPago'] = ($facturacionAsociados[0]['FechaPago'] !== NULL) ? $facturacionAsociados[0]['FechaPago'] : '-';
                    $data[$key]['Estatus'] = ($facturacionAsociados[0]['Pdf'] !== NULL && $facturacionAsociados[0]['Xml'] !== NULL) ? $facturacionAsociados[0]['Estatus'] : 'FALTA DOCUMENTACIÓN';
                } else {
                    $data[$key]['FechaDocumentacion'] = '-';
                    $data[$key]['FechaValidacionSup'] = '-';
                    $data[$key]['FechaValidacionCoord'] = '-';
                    $data[$key]['FechaPago'] = '-';
                    $data[$key]['Estatus'] = 'FALTA DOCUMENTACIÓN';
                }
            }
            return $data;
        } else {
            return $this->faltaPagoAsociados();
        }
    }

    public function facturasAsociados(string $ticket) {
        $consulta = $this->DBST->consultaGeneral('SELECT 
                                                        *,
                                                        estatus(IdEstatus) AS Estatus
                                                    FROM t_facturacion_asociados
                                                    WHERE Ticket = "' . $ticket . '"');
        return $consulta;
    }

    public function nombreUsuario(string $usuario) {
        $consulta = $this->DBST->consultaGeneral('SELECT 
                                        nombreUsuario(Id) AS NombreUsuario 
                                    FROM cat_v3_usuarios
                                    WHERE Id = "' . $usuario . '"');
        return $consulta;
    }

    public function guardarDocumentosFacturaAsociados(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'documentosAsociados/Usuario-' . $usuario['Id'] . '/Ticket-' . $datos['ticket'] . '/';
        $archivos = setMultiplesArchivos($CI, $datos['input'], $carpeta);
        $campoDocumento = ($datos['input'] === 'PDFFacturacion') ? 'Pdf' : 'Xml';

        if ($archivos) {
            $archivos = implode(',', $archivos);
            $verificarFacturacionAsociados = $this->facturasAsociados($datos['ticket']);
            if (empty($verificarFacturacionAsociados)) {
                $this->DBST->setNuevoElemento('t_facturacion_asociados', array(
                    'IdEstatus' => '5',
                    'Ticket' => $datos['ticket'],
                    'IdIngeniero' => $usuario['Id'],
                    'FechaDocumentacion' => $fecha,
                    $campoDocumento => $archivos
                ));
            } else {
                $this->DBST->actualizarServicio('t_facturacion_asociados', array(
                    'FechaDocumentacion' => $fecha,
                    $campoDocumento => $archivos
                        ), array('Ticket' => $datos['ticket'])
                );
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function colocarFechaValidacion(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $perfil = $this->validarPuesto();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $campoFechaValidacion = ($perfil === '46') ? 'FechaValidacionCoord' : 'FechaValidacionSup';
        $campoIdUsuario = ($perfil === '46') ? 'IdCoordinador' : 'IdSupervisor';
        $this->DBST->actualizarServicio('t_facturacion_asociados', array(
            $campoFechaValidacion => $fecha,
            $campoIdUsuario => $usuario['Id'],
                ), array('Ticket' => $datos['ticket'])
        );

        $facturacionAsociados = $this->facturasAsociados($datos['ticket']);
        if ($facturacionAsociados[0]['FechaValidacionCoord'] !== NULL) {
            $this->DBST->actualizarServicio('t_facturacion_asociados', array(
                'IdEstatus' => '14',
                    ), array('Ticket' => $datos['ticket'])
            );
        }

        return $this->consultaIdOrdenIngeniero($usuario['Id']);
    }

    public function colocarReferenciaPago(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $IdIngeniero = $this->facturasAsociados($datos['ticket']);
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'documentosAsociados/Usuario-' . $IdIngeniero[0]['IdIngeniero'] . '/Ticket-' . $datos['ticket'] . '/';
        $archivos = setMultiplesArchivos($CI, 'evidenciaFacturacion', $carpeta);

        if ($archivos) {
            $archivos = implode(',', $archivos);

            $this->DBST->actualizarServicio('t_facturacion_asociados', array(
                'FechaPago' => $fecha,
                'Referencia' => $datos['referenciaPago'],
                'EvidenciaPago' => $archivos,
                'IdEstatus' => '15',
                'IdTesoreria' => $usuario['Id']
                    ), array('Ticket' => $datos['ticket'])
            );
            return $this->faltaPagoAsociados();
        } else {
            return FALSE;
        }
    }

    public function faltaPagoAsociados() {
        $faltaPagoAsociados = $this->DBST->consultaGeneral('SELECT *,
                                                    estatus(IdEstatus) AS Estatus,
                                                    nombreUsuario(IdIngeniero) AS Ingeniero 
                                                FROM t_facturacion_asociados
                                                WHERE IdEstatus = "14"');
        return $faltaPagoAsociados;
    }

    public function rechazarFacturaAsociado(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();

        $this->DBST->actualizarServicio('t_facturacion_asociados', array(
            'IdEstatus' => '10',
            'Rechazo' => $datos['descripcionRechazo'],
            'IdUsuarioRechaza' => $usuario['Id'],
                ), array('Ticket' => $datos['ticket'])
        );

        return $this->consultaIdOrdenIngeniero($usuario['Id']);
    }
}