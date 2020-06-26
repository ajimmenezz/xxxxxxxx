<?php

namespace Librerias\Proyectos;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Tareas
 *
 * @author Freddy
 */
class Tareas extends General {

    private $DBT;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBT = \Modelos\Modelo_Tareas::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /*
     * Encargada de obtener las tareas que tiene el usuario asignada.
     * 
     */

    public function getTareasAsiganadas() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $this->DBT->getTareas('
                select
                    ttp.Id,
                    tp.Nombre as Proyecto,
                    (select Nombre from cat_v3_sucursales where Id = tp.IdSucursal) as Complejo,
                    (select Nombre from cat_v3_tareas_proyectos where Id = ttp.IdTarea) as Tipo,
                    ttp.FechaInicio,
                    ttp.FechaTermino
                from t_tareas_proyecto ttp inner join t_proyectos tp 
                on ttp.IdProyecto = tp.Id
                where ttp.IdLider =' . $usuario['Id']);
    }

    /*
     * Encargada de obtener la informacion de la tarea del proyecto.
     * 
     */

    public function getTareasTipoProyecto(string $tipoProyecto) {
        $datos = array();
        $consulta = $this->Catalogo->catTareasProyectos('3');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                if ($value['IdTipoProyecto'] === $tipoProyecto) {
                    array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
                }
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de mostrar los detalles de la tarea.
     * 
     */

    public function seguimientoTarea(array $datos) {
        $data = array();
        $datosTarea = $this->DBT->getTarea(array('Id' => $datos['tarea']));
        if (!empty($datosTarea)) {
            if ($datosTarea['Estatus'] === '1') {                
                $data['datosTarea'] = $datosTarea;
                $data['formulario'] = parent::getCI()->load->view('Proyectos/Modal/SeguimientoTarea', $data, TRUE);
                return $data;
            } else if ($datosTarea['Estatus'] === '2') {
                
                $data['datosTarea'] = $datosTarea;
                $data['formulario'] = parent::getCI()->load->view('Proyectos/Modal/SeguimientoTarea', $data, TRUE);
                return $data;
            } else if ($datosTarea['Estatus'] === '4') {
                return $datosTarea;
            }
        }
    }

    /*
     * Encargado de actualizar la tarea 
     * 
     */

    public function actualizarTarea(array $datos) {
        $data = array();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosTarea = $this->DBT->getTarea(array('Id' => $datos['tarea']));
        if ($datos['operacion'] === '1') {
            //Actualiza el estatus como en atencion
            $actualizar = $this->DBT->actualizarTarea(array('IdEstatus' => '2', 'Inicio' => $fecha), array('Id' => $datos['tarea']));
            if (!empty($actualizar)) {
                $data['datosTarea'] = $datosTarea;
                $data['formulario'] = parent::getCI()->load->view('Proyectos/Modal/SeguimientoTarea', $data, TRUE);
                return $data;
            } else {
                return FALSE;
            }
        }
    }

}
