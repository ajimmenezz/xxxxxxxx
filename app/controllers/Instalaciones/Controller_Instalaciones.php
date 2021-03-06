<?php

use Controladores\Controller_Base as Base;

class Controller_Instalaciones extends Base
{

    private $instalaciones;

    public function __construct()
    {
        parent::__construct();
        $this->instalaciones = new \Librerias\Instalaciones\Instalaciones();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'SeguimientoInstalacion':
                $resultado = $this->instalaciones->formularioSeguimientoInstalacion($this->input->post());
                break;
            case 'IniciarInstalacion':
                $resultado = $this->instalaciones->iniciarInstalacion($this->input->post());
                break;
            case 'SucursalesXCliente':
                $resultado = $this->instalaciones->getSucursalesXCliente($this->input->post());
                break;
            case 'GuardarSucursalServicio':
                $resultado = $this->instalaciones->guardarSucursalServicio($this->input->post());
                break;
            case 'InstaladosLexmark':
                $resultado = $this->instalaciones->instaladosLexmark($this->input->post());
                break;
            case 'GuardarInstaladosLexmark':
                $resultado = $this->instalaciones->guardarInstaladosLexmark($this->input->post());
                break;
            case 'RetiradosLexmark':
                $resultado = $this->instalaciones->retiradosLexmark($this->input->post());
                break;
            case 'GuardarRetiradosLexmark':
                $resultado = $this->instalaciones->guardarRetiradosLexmark($this->input->post());
                break;
            case 'SubirArchivoInstalacion':
                $resultado = $this->instalaciones->subirArchivoInstalacion($this->input->post());
                break;
            case 'SubirArchivoRetiro':
                $resultado = $this->instalaciones->subirArchivoRetiro($this->input->post());
                break;
            case 'EvidenciasInstalacion':
                $resultado = $this->instalaciones->evidenciasInstalacion($this->input->post());
                break;
            case 'EvidenciasRetiro':
                $resultado = $this->instalaciones->evidenciasRetiro($this->input->post());
                break;
            case 'EliminarEvidenciaInstalacion':
                $resultado = $this->instalaciones->eliminarEvidenciaInstalacion($this->input->post());
                break;
            case 'EliminarEvidenciaRetiro':
                $resultado = $this->instalaciones->eliminarEvidenciaRetiro($this->input->post());
                break;
            case 'GuardarMaterial':
                $resultado = $this->instalaciones->guardarMaterial($this->input->post());
                break;
            case 'CargaMateriales':
                $resultado = $this->instalaciones->cargaMateriales($this->input->post());
                break;
            case 'EliminarMaterial':
                $resultado = $this->instalaciones->eliminarMaterial($this->input->post());
                break;
            case 'ExportarInstalacion':
                $resultado = $this->instalaciones->exportarInstalacion($this->input->post());
                break;
            case 'CargaFirmas':
                $resultado = $this->instalaciones->cargaFirmas($this->input->post());
                break;
            case 'GuardaFirma':
                $resultado = $this->instalaciones->guardaFirma($this->input->post());
                break;
            case 'ConcluirServicio':
                $resultado = $this->instalaciones->concluirServicio($this->input->post());
                break;
            case 'InstaladosAntenas':
                $resultado = $this->instalaciones->instaladosAntenas($this->input->post());
                break;
            case 'GuardarAntena':
                $resultado = $this->instalaciones->guardarAntena($this->input->post());
                break;
            case 'EliminarAntena':
                $resultado = $this->instalaciones->eliminarAntena($this->input->post());
                break;
            case 'EvidenciasXEquipo':
                $resultado = $this->instalaciones->evidenciasXEquipo($this->input->post());
                break;
            case 'SubirArchivoInstalacionEquipo':
                $resultado = $this->instalaciones->subirArchivoInstalacionEquipo($this->input->post());
                break;
            case 'EliminarEvidenciaInstalacionEquipo':
                $resultado = $this->instalaciones->eliminarEvidenciaInstalacionEquipo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
