<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Catalogo_Proyectos extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getTiposProyecto() {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_sistemas_proyecto');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Descripcion' => $value['Descripcion'], 'Flag' => $value['Flag']));
            }
        }
        return $datos;
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getTareas() {
        $datos = array();
        $consulta = $this->consulta('select cvtp.Id, cvtp.Nombre , cvt.Nombre as Tipo, cvtp.Flag from cat_v3_tareas_proyectos cvtp inner join cat_v3_sistemas_proyecto cvt on cvtp.IdTipoProyecto = cvt.Id');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Tipo' => $value['Tipo'], 'Flag' => $value['Flag']));
            }
        }
        return $datos;
    }

    /*
     * Encargado de generar un nuevo tipo de proyecto
     * 
     */

    public function setTipoProyecto(array $data) {
        $consulta = $this->encontrar('cat_tipos_proyecto', array('Nombre' => $data['Nombre']));
        if (empty($consulta)) {
            $consulta = $this->insertar('cat_tipos_proyecto', $data);
            $tipos = $this->getTiposProyecto();
            return $tipos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar un nuevo tipo de tarea para los proyectos
     * 
     */

    public function setTareaProyecto(array $data) {
        $consulta = $this->encontrar('cat_v3_tareas_proyectos', array('Nombre' => $data['Nombre'], 'IdTipoProyecto' => $data['IdTipoProyecto']));
        if (empty($consulta)) {
            $consulta = $this->insertar('cat_v3_tareas_proyectos', $data);
            $tareas = $this->getTareas();
            return $tareas;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar el tipo de proyecto
     */

    public function actualizarTipoProyecto(array $datos, array $where) {
        $consulta = $this->consulta('select * from cat_tipos_proyecto where Nombre = "' . $datos['Nombre'] . '" and Id <> ' . $where['Id']);
        if (empty($consulta)) {
            $consulta = $this->actualizar('cat_tipos_proyecto', $datos, $where);
            if (isset($consulta)) {
                return $this->getTiposProyecto();
            } else {
                return parent::tipoError();
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar el tipo de tarea
     */

    public function actualizarTarea(array $datos, array $where) {
        $consulta = $this->consulta('select * from cat_v3_tareas_proyectos where Nombre="' . $datos['Nombre'] . '" and Id <> ' . $where['Id'] . ' and IdTipoProyecto = ' . $datos['IdTipoProyecto']);
        if (empty($consulta)) {
            $consulta = $this->actualizar('cat_v3_tareas_proyectos', $datos, $where);
            if (isset($consulta)) {
                return $this->getTareas();
            } else {
                return parent::tipoError();
            }
        } else {
            return FALSE;
        }
    }

}
