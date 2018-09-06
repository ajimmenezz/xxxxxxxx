<?php

namespace Modelos;

use Librerias\Modelos\Base as Base;

/**
 * Description of Modelo_Tareas
 *
 * @author Freddy
 */
class Modelo_Tareas extends Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de obtenener las tareas
     * 
     */

    public function getTareas(string $consulta) {
        $datos = $this->consulta($consulta);
        if (!empty($datos)) {
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener los datos de la tarea
     * 
     */

    public function getTarea(array $where) {
        $data = array();
        $consulta = $this->consulta('
            select 
                ttp.IdEstatus,
                usuario(tat.IdUsuario) as asistente
            from t_tareas_proyecto ttp left join t_asistentes_tareas tat
            on ttp.Id = tat.IdTarea
            where ttp.Id = '.$where['Id']);
        if (!empty($consulta)) {
            $data['Tarea'] = $where['Id'];
            $data['asistentes'] = array();
            foreach ($consulta as $value) {
                $data['Estatus'] = $value['IdEstatus'];
                array_push($data['asistentes'], array('asistente' => $value['asistente']));                
            }
        }
        return $data;
    }
    
    /*
     * Encargado de actualizar la tarea
     * 
     */
    public function actualizarTarea(array $datos, array $where) {
        return $this->actualizar('t_tareas_proyecto',$datos, $where);
        
    }

}
