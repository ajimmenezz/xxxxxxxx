<?php

namespace Librerias\FondoFijo;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\LeerCFDI as CFDI;

class FondoFijo extends General
{

    private $DB;
    private $Correo;
    private $usuario;
    private $cfdi;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_FondoFijo::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getTiposCuenta()
    {
        $tipos = $this->DB->getTiposCuenta();
        return $tipos;
    }

    public function getUsuarios()
    {
        $usuarios = $this->DB->getUsuarios();
        return $usuarios;
    }

    public function getConceptos()
    {
        $conceptos = $this->DB->getConceptos();
        return $conceptos;
    }

    public function agregarTipoCuenta(array $datos)
    {
        if (!isset($datos['tipo'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del Tipo de Cuenta. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarTipoCuenta(mb_strtoupper($datos['tipo']));
            if (!is_null($insert['id'])) {
                return [
                    'code' => 200,
                    'id' => $insert['id'],
                    'tipo' => mb_strtoupper($datos['tipo'])
                ];
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function formularioEditarTipo(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del tipo de cuenta. Intente de nuevo'
            ];
        } else {
            $sistema = $this->DB->getTiposCuenta($datos['id']);
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('FondoFijo/Formularios/EditarTipoCuenta', ['data' => $sistema[0]], TRUE)
            ];
        }
    }

    public function editarTipoCuenta(array $datos)
    {
        if (!isset($datos['tipo']) || !isset($datos['id']) || !isset($datos['estatus'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarTipoCuenta($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function formularioEditarMontosUsuario(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del usuario. Intente de nuevo'
            ];
        } else {
            $data = [
                'tiposCuenta' => $this->getTiposCuenta(),
                'montos' => $this->getMontosUsuario($datos['id']),
                'usuario' => $datos,
            ];

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('FondoFijo/Formularios/EditarMontosUsuario', $data, TRUE)
            ];
        }
    }

    public function getMontosUsuario(int $id)
    {
        $montos = $this->DB->getMontosUsuario($id);
        return $montos;
    }

    public function guardarMontos(array $datos)
    {
        if (!isset($datos['id']) || !isset($datos['montos'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->guardarMontos($datos);
            return $result;
        }
    }

    public function formularioAgregarConcepto(array $datos)
    {

        $datos = [
            'tiposCuenta' => $this->getTiposCuenta(),
            'tiposComprobante' => $this->getTiposComprobante(),
            'usuarios' => $this->getUsuarios(),
            'sucursales' => $this->getSucursales(),
            'generales' => ($datos['id'] > 0) ? $this->DB->getConceptos($datos['id'])[0] : [],
            'alternativas' => ($datos['id'] > 0) ? $this->DB->getAlternativasByConcepto($datos['id']) : [],
        ];

        return [
            'html' => parent::getCI()->load->view('FondoFijo/Formularios/AgregarEditarConcepto', $datos, TRUE)
        ];
    }

    public function agregarConcepto(array $datos)
    {
        return $this->DB->guardarConcepto($datos);
    }

    public function getTiposComprobante()
    {
        return $this->DB->getTiposComprobante();
    }

    public function getSucursales()
    {
        return $this->DB->getSucursales();
    }

    public function inhabilitarConcepto(array $datos)
    {
        return $this->DB->habInhabConcepto($datos, 0);
    }

    public function habilitarConcepto(array $datos)
    {
        return $this->DB->habInhabConcepto($datos, 1);
    }

    public function getUsuariosConFondoFijo()
    {
        $usuarios = $this->DB->getUsuariosConFondoFijo();
        return $usuarios;
    }

    public function formularioDepositar(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del usuario. Intente de nuevo'
            ];
        } else {
            $data = [
                'tiposCuenta' => $this->getTiposCuentaXUsuario($datos['id']),
                'montos' => $this->getMontosUsuario($datos['id']),
                'usuario' => $datos,
                'depositos' => $this->DB->getDepositos($datos['id'])
            ];

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('FondoFijo/Formularios/RegistrarDeposito', $data, TRUE)
            ];
        }
    }

    public function getTiposCuentaXUsuario(int $id)
    {
        $tipos = $this->DB->getTiposCuentaXUsuario($id);
        return $tipos;
    }

    public function montosDepositar(array $datos)
    {
        $montoMaximo = $this->DB->getMaximoMontoAutorizado($datos['id'], $datos['tipoCuenta']);
        $saldo = $this->DB->getSaldo($datos['id'], $datos['tipoCuenta']);
        $sugerido = (double)$montoMaximo - (double)$saldo;
        if ($sugerido < 0) {
            $sugerido = 0;
        }
        return [
            'code' => 200,
            'montos' => [
                'montoMaximo' => number_format($montoMaximo, 2),
                'saldo' => number_format($saldo, 2),
                'sugerido' => number_format($sugerido, 2)
            ]
        ];
    }


    public function registrarDeposito(array $datos)
    {
        $date = date('Ymd');
        $returnArray = [
            'code' => 400,
            'error' => ""
        ];

        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'comprobaciones/fondo_fijo/' . $date . '/';

        $archivos = '';
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosDeposito', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos = array_merge($datos, ['archivos' => $archivos]);

        $registrar = $this->DB->registrarDeposito($datos);

        if ($registrar['code'] == 200) {
            $generalesUsuario = $this->DB->getGeneralInfoByUserID($datos['id']);
            $titulo = 'Depósito Fondo Fijo';
            $texto = '<h4>Hola ' . $generalesUsuario['Nombre'] . '</h4>'
                . '<p>'
                . ' Se ha registrado un nuevo depósito por la cantidad de <strong>$' . number_format($datos['depositar'], 2, '.', ',') . '</strong> correspondiente al fondo fijo. Por favor verifica la información mencionada ingresando al sistema'
                . '</p>';
            //            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            //            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array($generalesUsuario['EmailCorporativo']), $titulo, $mensaje);
        }

        return $registrar;
    }

    public function getSaldosCuentasXUsuario(int $id)
    {
        $resultado = $this->DB->getSaldosCuentasXUsuario($id);
        return $resultado;
    }

    public function detalleCuenta(array $datos)
    {
        if (!isset($datos['tipoCuenta']) || !isset($datos['usuario'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información de la cuenta o el usuario. Intente de nuevo'
            ];
        } else {
            $data = [
                'tipoCuenta' => $this->DB->getTiposCuenta($datos['tipoCuenta'])[0]['Nombre'],
                'movimientos' => $this->DB->getMovimientos($datos['usuario'], $datos['tipoCuenta']),
                'conceptos' => $this->DB->getConceptosXTipoCuenta($datos['tipoCuenta']),
                'tickets' => $this->DB->getTicketsByUsuario($this->usuario['Id']),
                'sucursales' => $this->DB->getSucursales()
            ];

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('FondoFijo/Formularios/DetallesCuenta', $data, TRUE)
            ];
        }
    }

    public function cargaMontoMaximoConcepto(array $datos)
    {
        $monto = $this->DB->cargaMontoMaximoConcepto(array_merge($datos, ['usuario' => $this->usuario['Id']]));
        return $monto;
    }

    public function cargaServiciosTicket(array $datos)
    {
        $servicios = $this->DB->cargaServiciosTicket(array_merge($datos, ['usuario' => $this->usuario['Id']]));
        return $servicios;
    }

    public function registrarComprobante(array $datos)
    {
        $date = date('Ymd');
        $datos['tipoComprobante'] = 3;
        $datos['xml'] = '';
        $datos['pdf'] = '';

        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'comprobaciones/fondo_fijo/' . $date . '/';

        $archivos = '';
        $archivosAux = [];

        $datosXML = [
            'serie' => '',
            'folio' => '',
            'receptor' => '',
            'fecha' => '',
            'total' => '',
            'uuid' => '',
            'version' => '1'
        ];


        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosDeposito', $carpeta);
            $archivosAux = $archivos;
            $archivosFact = $archivos;

            $countXML = 0;
            $countPdf = 0;
            foreach ($archivos as $key => $value) {
                $extension = pathinfo($value, PATHINFO_EXTENSION);
                if ($extension === 'pdf') {
                    $datos['pdf'] = $value;
                    unset($archivosFact[$key]);
                    $countPdf++;
                    if ($countPdf > 1) {
                        $this->eliminaArchivos($archivos);
                        return ['code' => 500, 'errorBack' => 'Se seleccionaron más de 1 PDF. Por favor verifique sus archivos.'];
                    }
                }

                if ($extension === 'xml') {
                    $datos['xml'] = $value;
                    unset($archivosFact[$key]);
                    $countXML++;
                    if ($countXML > 1) {
                        $this->eliminaArchivos($archivos);
                        return ['code' => 500, 'errorBack' => 'Se seleccionaron más de 1 XML. Por favor verifique sus archivos.'];
                    }

                    $this->cfdi = new CFDI();
                    $this->cfdi->cargaXml(getcwd() . $value);
                    $resultado = $this->cfdi->validar($datos['monto']);

                    if ($resultado['code'] != 200) {
                        $this->eliminaArchivos($archivos);
                        return [
                            'code' => $resultado['code'],
                            'errorBack' => $resultado['error']
                        ];
                    } else {
                        $datosXML = $resultado['data'];
                    }
                }
            }

            $tiposComprobante = explode(",", $datos['tiposComprobante']);

            if ($tiposComprobante[0] == 1 && count($tiposComprobante) == 1) {
                if ($countXML <= 0) {
                    $this->eliminaArchivos($archivos);
                    return ['code' => 500, 'errorBack' => 'Es necesario el archivo XML para comprobar este tipo de gastos.'];
                } else if ($countPdf <= 0) {
                    $this->eliminaArchivos($archivos);
                    return ['code' => 500, 'errorBack' => 'Es necesario el archivo PDF para comprobar este tipo de gastos.'];
                }
            }

            if ($countXML > 0 && $countPdf <= 0) {
                $this->eliminaArchivos($archivos);
                return ['code' => 500, 'errorBack' => 'Falta el archivo PDF de la factura.'];
            }

            if ($countXML == 1 && $countPdf == 1) {
                $datos['tipoComprobante'] = 1;
                $archivos = implode(',', $archivosFact);
            } else {
                $datos['tipoComprobante'] = 2;
                $archivos = implode(',', $archivos);
            }
        }

        $datos = array_merge($datos, ['archivos' => $archivos], ['cfdi' => $datosXML]);

        $registrar = $this->DB->registrarComprobante($datos);
        if ($registrar['code'] != 200) {
            $this->eliminaArchivos($archivosAux);
        }
        return $registrar;
    }

    public function formularioDetallesMovimiento(array $datos)
    {
        $rolAutoriza = 0;
        if (isset($datos['autorizaciones']) && $datos['autorizaciones'] == 1) {
            $rolAutoriza = 1;
        }


        $datos = [
            'generales' => $this->DB->getDetallesFondoFijoXId($datos['id'])[0],
            'rolAutoriza' => $rolAutoriza
        ];

        return [
            'html' => parent::getCI()->load->view('FondoFijo/Formularios/DetallesMovimiento', $datos, TRUE)
        ];
    }

    public function cancelarMovimiento(array $datos)
    {
        $cancelar = $this->DB->cancelarMovimiento($datos);
        return $cancelar;
    }

    public function pendientesXAutorizar(int $idJefe)
    {
        $pendientes = $this->DB->pendientesXAutorizar($idJefe);
        return $pendientes;
    }

    public function rechazarMovimiento(array $datos)
    {
        $rechazar = $this->DB->rechazarMovimiento($datos);

        if ($rechazar['code'] == 200) {
            $generalesUsuario = $this->DB->getGeneralInfoByUserID($rechazar['id']);
            $generalesUsuarioRechaza = $this->DB->getGeneralInfoByUserID($this->usuario['Id']);
            $generalesMovimiento = $this->DB->getDetallesFondoFijoXId($datos['id'])[0];

            $titulo = 'Comprobante Rechazado - Fondo Fijo';
            $texto = '<h4>Hola ' . $generalesUsuario['Nombre'] . '</h4>'
                . '<p>'
                . ' El usuario ' . $generalesUsuarioRechaza['Nombre'] . ' '
                . ' ha  rechazado su comprobante por concepto de "' . $generalesMovimiento['Nombre'] . '" '
                . ' por el total de <strong>$' . number_format(abs($generalesMovimiento['Monto']), 2, '.', ',') . '</strong> '
                . ' correspondiente al fondo fijo. <br />'
                . ' Observaciones: <strong>' . $datos['observaciones'] . '</strong><br />'
                . ' Por favor verifique la información mencionada ingresando al sistema '
                . 'o comuniquese con el usuario que rechazó el comprobante.'
                . '</p>';
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array($generalesUsuario['EmailCorporativo']), $titulo, $mensaje);
        }

        return $rechazar;
    }

    public function autorizarMovimiento(array $datos)
    {
        $autorizar = $this->DB->autorizarMovimiento($datos);

        if ($autorizar['code'] == 200) {
            $generalesUsuario = $this->DB->getGeneralInfoByUserID($autorizar['id']);
            $generalesUsuarioRechaza = $this->DB->getGeneralInfoByUserID($this->usuario['Id']);
            $generalesMovimiento = $this->DB->getDetallesFondoFijoXId($datos['id'])[0];

            $titulo = 'Comprobante Autorizado - Fondo Fijo';
            $texto = '<h4>Hola ' . $generalesUsuario['Nombre'] . '</h4>'
                . '<p>'
                . ' El usuario ' . $generalesUsuarioRechaza['Nombre'] . ' ha autorizado su comprobante por concepto de "' . $generalesMovimiento['Nombre'] . '" por el total de $' . number_format(abs($generalesMovimiento['Monto']), 2, '.', ',') . '</strong> correspondiente al fondo fijo. Por favor verifique la información mencionada ingresando al sistema.'
                . '</p>';
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array($generalesUsuario['EmailCorporativo']), $titulo, $mensaje);
        }

        return $autorizar;
    }

    /************************************************************************/
    
    public function rechazarMovimientoCobrable(array $datos)
    {
        $rechazar = $this->DB->rechazarMovimientoCobrable($datos);

        if ($rechazar['code'] == 200) {
            $generalesUsuario = $this->DB->getGeneralInfoByUserID($rechazar['id']);
            $generalesUsuarioRechaza = $this->DB->getGeneralInfoByUserID($this->usuario['Id']);
            $generalesMovimiento = $this->DB->getDetallesFondoFijoXId($datos['id'])[0];

            $titulo = 'Comprobante Rechazado - Fondo Fijo';
            $texto = '<h4>Hola ' . $generalesUsuario['Nombre'] . '</h4>'
                . '<p>'
                . ' El usuario ' . $generalesUsuarioRechaza['Nombre'] . ' '
                . ' ha  rechazado su comprobante por concepto de "' . $generalesMovimiento['Nombre'] . '" '
                . ' por el total de <strong>$' . number_format(abs($generalesMovimiento['Monto']), 2, '.', ',') . '</strong> '
                . ' correspondiente al fondo fijo. <br />'
                . ' Observaciones: <strong>' . $datos['observaciones'] . '</strong><br />'
                . ' Por favor verifique la información mencionada ingresando al sistema '
                . 'o comuniquese con el usuario que rechazó el comprobante.'
                . '</p>';
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array($generalesUsuario['EmailCorporativo']), $titulo, $mensaje);
        }

        return $rechazar;
    }
}
