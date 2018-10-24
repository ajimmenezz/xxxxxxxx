<?php

namespace Librerias\Tesoreria;

use Controladores\Controller_Base_General as General;

class FondoFijo extends General {

    private $DB;
    private $DBC;
    private $Correo;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Tesoreria::factory();
        $this->DBC = \Modelos\Modelo_Comprobacion::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getFondosFijos() {
        $fondos = $this->DB->getFondosFijos();
        return $fondos;
    }

    public function detallesFondoFijoXUsuario(array $datos) {

        $datos = [
            'listaComprobaciones' => $this->DB->getDetallesFondoFijoXUsuario($datos['id']),
            'usuario' => $this->DB->getNombreUsuarioById($datos['id']),
            'saldo' => $this->DB->getSaldoByUsuario($datos['id']),
            'xautorizar' => $this->DB->getSaldoXAutorizarByUsuario($datos['id'])
        ];

        return [
            'html' => parent::getCI()->load->view('Tesoreria/Formularios/DetallesFondoFijoXUsuario', $datos, TRUE)
        ];
    }

    public function formularioRegistrarDeposito(array $datos) {

        $generalesMontoFijoUsuario = $this->DB->getFondosFijos($datos['id'])[0];

        $datos = [
            'listaComprobaciones' => $this->DB->getDetallesFondoFijoXUsuario($datos['id']),
            'usuario' => $this->DB->getNombreUsuarioById($datos['id']),
            'saldo' => $this->DB->getSaldoByUsuario($datos['id']),
            'monto' => $generalesMontoFijoUsuario['MontoUsuario'],
            'estatus' => $generalesMontoFijoUsuario['Flag']
        ];

        return [
            'html' => parent::getCI()->load->view('Tesoreria/Formularios/FormularioRegistrarDeposito', $datos, TRUE)
        ];
    }

    public function registrarDeposito(array $datos) {
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
                    . ' Se ha registrado un nuevo depósito por la cantidad de <strong>$' . number_format($datos['monto'], 2, '.', ',') . '</strong> correspondiente al fondo fijo. Por favor verifica la información mencionada ingresando al sistema'
                    . '</p>';
            $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
//            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array($generalesUsuario['Email'], $generalesUsuario['EmailCorporativo']), $titulo, $mensaje);
            $this->Correo->enviarCorreo('fondofijo@siccob.solutions', array('ajimenez@siccob.com.mx'), $titulo, $mensaje);
        }

        return $registrar;
    }

    public function formularioRegistrarComprobante(array $datos) {
        $datos = [
            'conceptos' => $this->DBC->getConceptos(),
            'tickets' => $this->DB->getTicketsByUsuario($this->usuario['Id']),
            'sucursales' => $this->DB->getSucursales()
        ];

        return [
            'html' => parent::getCI()->load->view('Comprobacion/Formularios/FormularioRegistrarComprobante', $datos, TRUE)
        ];
    }

    public function cargaMontoMaximoConcepto(array $datos) {
        $monto = $this->DB->cargaMontoMaximoConcepto(array_merge($datos, ['usuario' => $this->usuario['Id']]));
        return $monto;
    }
    
    public function cargaServiciosTicket(array $datos) {
        $servicios = $this->DB->cargaServiciosTicket(array_merge($datos, ['usuario' => $this->usuario['Id']]));
        return $servicios;
    }

}
