<?php

namespace Librerias\Instalaciones;

use Controladores\Controller_Base_General as General;

class Instalaciones extends General
{

    private $DB;
    private $Correo;
    private $usuario;
    private $cliente;
    private $sucursal;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Instalaciones::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->cliente = \Librerias\Catalogos\Cliente::factory();
        $this->sucursal = \Librerias\Catalogos\Sucursal::factory();
    }

    public function getInstalacionesPendientes(int $idUsuario = null)
    {
        $pendientes = $this->DB->getInstalacionesPendientes($idUsuario);
        return $pendientes;
    }

    public function formularioSeguimientoInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $cliente = [
                'IdCliente' => null
            ];
            if ($generales['IdSucursal'] != '') {
                $sucursal = $this->sucursal->get($generales['IdSucursal'])['result'][0];
                $cliente = $this->cliente->get($sucursal['IdCliente'])['result'][0];
            }

            $generales['IdCliente'] = $cliente['Id'];

            $data = [
                'generales' => $generales,
                'clientes' => $this->cliente->get()['result'],
                'sucursales' => $this->sucursal->get(null, 1, $cliente['Id'])['result']
            ];

            switch ($data['generales']['IdTipoServicio']) {
                case 45:
                case '45':

                    break;
            }

            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Instalaciones/Formularios/Seguimiento-' . $data['generales']['IdTipoServicio'], $data, TRUE),
                'tipoInstalacion' => $data['generales']['IdTipoServicio']
            ];
        }
    }

    public function iniciarInstalacion(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $iniciar = $this->DB->iniciarInstalacion($datos['id']);
            if ($iniciar['code'] != 200) {
                return $iniciar;
            } else {
                return $this->formularioSeguimientoInstalacion($datos);
            }
        }
    }

    public function getSucursalesXCliente(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del cliente. Intente de nuevo'
            ];
        } else {
            $sucursales = $this->sucursal->get(null, 1, $datos['id']);
            return $sucursales;
        }
    }

    public function guardarSucursalServicio(array $datos)
    {
        if (!isset($datos['id']) || !isset($datos['sucursal'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio o la sucursal. Intente de nuevo'
            ];
        } else {
            $result = $this->DB->guardarSucursalServicio($datos['id'], $datos['sucursal']);
            return $result;
        }
    }

    public function instaladosLexmark(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];
            $ubicaciones = [];
            if ($generales['IdSucursal'] != '') {
                $ubicaciones = $this->sucursal->ubicacionesCenso($generales['IdSucursal']);
            }

            $instalados = $this->DB->getEquiposInstaladosLexmark($datos['id']);

            if ($instalados['code'] == 200) {
                return [
                    'code' => 200,
                    'message' => 'Success',
                    'ubicaciones' => $ubicaciones,
                    'instalados' => $instalados['result']
                ];
            } else {
                return $instalados;
            }
        }
    }

    public function guardarInstaladosLexmark(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['instalados'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio y los equipos instalados. Intente de nuevo'
            ];
        } else {
            return $this->DB->guardarInstaladosLexmark($datos);
        }
    }

    public function retiradosLexmark(array $datos)
    {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesServicio($datos['id'])[0];

            $censadas = [];
            if ($generales['IdSucursal'] != '') {
                $censadas = $this->DB->getKyocerasCensadas($generales['IdSucursal']);
            }

            $retirada = $this->DB->getImpresoraRetirada($datos['id']);

            if ($retirada['code'] == 200) {
                return [
                    'code' => 200,
                    'message' => 'Success',
                    'censadas' => $censadas,
                    'modelos' => $this->DB->getModelosKyocera(),
                    'estatus' => $this->DB->getEstatusRetiro(),
                    'retirada' => $retirada['result']
                ];
            } else {
                return $retirada;
            }
        }
    }

    public function guardarRetiradosLexmark(array $datos)
    {
        if (!isset($datos['servicio']) || !isset($datos['retirados'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del servicio y el equipo retirado. Intente de nuevo'
            ];
        } else {
            return $this->DB->guardarRetiradosLexmark($datos);
        }
    }
}
