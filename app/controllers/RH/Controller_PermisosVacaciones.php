<?php

use Controladores\Controller_Base as Base;

class Controller_PermisosVacaciones extends Base {

    private $permisos;
    private $autorizar;
    
    public function __construct() {
        parent::__construct();
        $this->permisos = \Librerias\RH\Permisos_Vacaciones::factory();
        $this->autorizar = \Librerias\RH\Autorizar_permisos::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Permisos':
                $resultado = $this->permisos->generarPDF($this->input->post());
                break;
            case 'VerModalActualizar':
                $resultado = $this->permisos->revisarInformacionAusencia($this->input->post());
                break;
            case 'ActualizarPermiso':
                $resultado = $this->permisos->actualizarPermiso($this->input->post());
                break;
            case 'ActualizarPermisoArchivo':
                $resultado = $this->permisos->actualizarPermisoArchivo($this->input->post());
                break;
            case 'Cancelar':
                $resultado = $this->permisos->cancelarPermiso($this->input->post());
                break;
            case 'Autorizar':
                $resultado = $this->autorizar->revisarPermiso($this->input->post());
                break;
            case 'AutorizarPermiso':
                $resultado = $this->autorizar->autorizarPermiso($this->input->post());
                break;
            case 'CancelarPermisos':
                $resultado = $this->autorizar->cancelarPermiso($this->input->post());
                break;
            case 'ConluirAutorizacion':
                $resultado = $this->autorizar->conluirAutorizacion($this->input->post());
                break;
            case 'exportarExcel':
                $resultado = $this->autorizar->exportExcel();
                break;
            case 'MostarMotivosAucencia':
                $resultado = $this->permisos->obtenerMotivoAusencia($this->input->post());
                break;
            case 'MostarMotivosRechazo':
                $resultado = $this->permisos->obtenerMotivoRechazo();
                break;
        }
        echo json_encode($resultado);
    }

}
