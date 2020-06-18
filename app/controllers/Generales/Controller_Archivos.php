<?php

use Controladores\Controller_Base as Base;

/**
 * Description of Controller_Archivos
 *
 * @author Alberto
 */
class Controller_Archivos extends Base {

    private $Archivos;

    public function __construct() {
        parent::__construct();
        $this->Archivos = \Librerias\Generales\Archivos::factory();
    }

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Archivo':
                $resultado = $this->Archivos->archivoNuevoFormato($this->input->post());
                break;
            case 'MostrarTabla':
                $resultado = $this->Archivos->mostrarArchivosTabla($this->input->post());
                break;
            case 'MostrarActualizarArchivo':
                $resultado = $this->Archivos->modalActualizarArchivo($this->input->post());
                break;
            case 'ActualizarArchivo':
                $resultado = $this->Archivos->actualizarArchivo($this->input->post());
                break;
            case 'VerficarUsuario':
                $resultado = $this->Archivos->verificarUsuario($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
