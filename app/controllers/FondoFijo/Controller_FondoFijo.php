<?php

use Controladores\Controller_Base as Base;

class Controller_FondoFijo extends Base
{

    private $fondo_fijo;

    public function __construct()
    {
        parent::__construct();
        $this->fondo_fijo = new \Librerias\FondoFijo\FondoFijo();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'TiposCuenta':
                $resultado = $this->fondo_fijo->getTiposCuenta($this->input->post());
                break;
            case 'AgregarTipoCuenta':
                $resultado = $this->fondo_fijo->agregarTipoCuenta($this->input->post());
                break;
            case 'FormularioEditarTipo':
                $resultado = $this->fondo_fijo->formularioEditarTipo($this->input->post());
                break;
            case 'EditarTipoCuenta':
                $resultado = $this->fondo_fijo->editarTipoCuenta($this->input->post());
                break;
            case 'FormularioEditarMontosUsuario':
                $resultado = $this->fondo_fijo->formularioEditarMontosUsuario($this->input->post());
                break;
            case 'GuardarMontos':
                $resultado = $this->fondo_fijo->guardarMontos($this->input->post());
                break;
            case 'FormularioAgregarConcepto':
                $resultado = $this->fondo_fijo->formularioAgregarConcepto($this->input->post());
                break;
            case 'AgregarConcepto':
                $resultado = $this->fondo_fijo->agregarConcepto($this->input->post());
                break;
            case 'InhabilitarConcepto':
                $resultado = $this->fondo_fijo->inhabilitarConcepto($this->input->post());
                break;
            case 'HabilitarConcepto':
                $resultado = $this->fondo_fijo->habilitarConcepto($this->input->post());
                break;
            case 'FormularioDepositar':
                $resultado = $this->fondo_fijo->formularioDepositar($this->input->post());
                break;
            case 'MontosDepositar':
                $resultado = $this->fondo_fijo->montosDepositar($this->input->post());
                break;
            case 'RegistrarDeposito':
                $resultado = $this->fondo_fijo->registrarDeposito($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
