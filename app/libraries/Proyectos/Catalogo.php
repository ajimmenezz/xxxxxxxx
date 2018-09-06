<?php

namespace Librerias\Proyectos;

use Controladores\Controller_Base_General as General;

class Catalogo extends General {

    private $usuario;
    private $DBC;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->DBC = \Modelos\Modelo_Catalogo_Proyectos::factory();
        parent::getCI()->load->helper('date');
    }

    /*
     * Encargado de generar el alcance y accesorios del proyecto
     * 
     */

    public function setTipoProyecto($nuevoTipo, $descripcion) {
        $consulta = $this->DBC->setTipoProyecto(array('Nombre' => strtoupper(trim($nuevoTipo)), 'Descripcion' => $descripcion, 'Flag' => '1'));
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de solicitar la actualizacion del proyecto.
     */

    public function actualizarTipoProyecto($datos) {
        $consulta = $this->DBC->actualizarTipoProyecto(array(
            'Nombre' => strtoupper(trim($datos['nombre'])),
            'Descripcion' => $datos['descripcion'],
            'Flag' => $datos['estatus']
                ), array('Id' => $datos['id']));
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar el alcance y accesorios del proyecto
     * 
     */

    public function setTareaProyecto($nuevaTarea, $tipo) {
        $consulta = $this->DBC->setTareaProyecto(array('Nombre' => strtoupper(trim($nuevaTarea)), 'IdTipoProyecto' => $tipo, 'Flag' => '1'));
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener el tipo de proyecto
     * 
     */

    public function getTipoProyecto() {
        return $this->DBC->getTiposProyecto();
    }

    /*
     * Encargado de actulaizar la tareas
     */

    public function actualizarTarea(array $datos) {
        $consulta =  $this->DBC->actualizarTarea(array(
                    'Nombre' => $datos['nombre'],
                    'IdTipoProyecto' => $datos['tipo'],
                    'Flag' => $datos['estatus']), array('Id' => $datos['id']));
        if(!empty($consulta)){
            return $consulta;
        }else{
            return FALSE;
        }
    }

}
