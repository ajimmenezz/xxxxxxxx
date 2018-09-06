<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Componentes\Coleccion as Coleccion;
use \Librerias\Modelos\Modelo_Base as Modelo;

class Actividad extends Objeto_General {

    private $materiales;
    private $IdActividad;

    public function __construct(Modelo $modelo, string $Id) {
        parent::__construct($modelo);
        $this->materiales = new Coleccion('Materiales de objeto Actividad');
        $this->IdActividad = $Id;
    }

    public function generarElementos() {
        return array('todas las actvidades');
    }

    public function obtenerNodosActividad() {
        $listaNodos = array();

        $nodos = $this->db_adist3->consulta('
            select 
        	*,
                (select Nombre from cat_v3_ubicaciones_proyectos where Id = tap.IdUbicacion) as Ubicacion
            from t_nodos_alcance_proyecto  tnap
            inner join t_alcance_proyecto tap
            on tnap.IdAlcance = tap.Id
            where IdActividad = ' . $this->IdActividad);
        if (!empty($nodos)) {
            foreach ($nodos as $key => $value) {
                $material = $this->obtenerMaterialNodo($value['Nombre'], $value['IdAlcance']);
                if (!array_key_exists($value['Nombre'], $listaNodos)) {
                    $listaNodos[$value['Nombre']] = array(
                        'Alcance' => $value['IdAlcance'],
                        'Actividad' => $value['IdActividad'],
                        'Tipo' => $value['IdTipoNodo'],
                        'Ubicacion' => $value['Ubicacion'],
                        'Nombre' => $value['Nombre'],
                        'Evidencia' => (!empty($value['Evidencia'])) ? explode(',', $value['Evidencia']) : array(),
                        'Material' => $material
                    );
                }
            }
        }

        return $listaNodos;
    }

    private function obtenerMaterialNodo(string $nombre, string $alcance) {
        $listaMaterial = array();

        $material = $this->db_adist3->consulta('
                select 
                    *,
                    (select Nombre from cat_v3_equipos_sae where Id = IdMaterial) as Material
                from t_nodos_alcance_proyecto where Nombre = "' . $nombre . '" and IdAlcance = ' . $alcance);
        if (!empty($material)) {
            foreach ($material as $key => $value) {
                array_push($listaMaterial, array(
                    'Accesorio' => $value['IdAccesorio'],
                    'IdMaterial' => $value['IdMaterial'],
                    'Material' => $value['Material'],
                    'Solicitado' => $value['Cantidad'],
                    'Utilizado' => $value['Utilizado'],
                    'Justificacion' => $value['Justificacion']
                ));
            }
        }

        return $listaMaterial;
    }

    public function agregarMaterial(array $datos) {
        $material = json_decode($datos['materialNodo'], true);
        $datos['CI']->load->helper(array('FileUpload', 'date'));
        $carpeta = 'Proyectos/Proyecto_' . $datos['idProyecto'] . '/Tarea_' . $datos['idTarea'] . '/Actividad_' . $this->IdActividad . '/';
        $archivosSubidos = setMultiplesArchivos($datos['CI'], 'evidenciaMaterial', $carpeta);
        if ($archivosSubidos !== FALSE) {
            $archivos = implode(',', $archivosSubidos);
        } else {
            throw new \Exception("No es posible subir el archivo al servidor");
        }

        $this->db_adist3->actualizar('
            update t_nodos_alcance_proyecto set
                    IdActividad = "' . $datos['idActividad'] . '",                   
                    Avance = "100%",
                    Evidencia = "' . $archivos . '"
                where IdAlcance = ' . $datos['idAlcanceNodos'] . ' and Nombre = "' . $datos['select-nodo'] . '"');

        foreach ($material as $value) {
            $this->db_adist3->actualizar('
            update t_nodos_alcance_proyecto set                   
                    Utilizado = "' . $value['Utilizado'] . '",
                    Justificacion = "' . $value['Justificacion'] . '"
                where IdAlcance = ' . $datos['idAlcanceNodos'] . ' and Nombre = "' . $datos['select-nodo'] . '" and IdMaterial = ' . $value['IdMaterial']);
        }
    }

    public function eliminarNodo(array $datos) {
        $this->eliminarArchivosMaterialEliminado($datos['CI'], $datos['archivosEliminados']);
        $this->db_adist3->actualizar('
                update t_nodos_alcance_proyecto set                        
                        IdActividad = 0,
                        Utilizado = 0,
                        Justificacion = "",
                        Avance = "0%",
                        Evidencia = ""
                where IdActividad = ' . $datos['idActividad'] . ' and Nombre = "' . $datos['nodo'] . '"');
    }

    public function eliminarNodos(array $datos) {
        $evidencias = array();
        $archivos = $this->db_adist3->consulta('select distinct Nombre, Evidencia from t_nodos_alcance_proyecto where IdActividad = ' . $datos['idActividad']);
        if (!empty($archivos)) {
            foreach ($archivos as $value) {
                $evidencias = explode(',', $value['Evidencia']);
                $this->eliminarArchivosMaterialEliminado($datos['CI'], $evidencias);
            }
        }
    }

    private function eliminarArchivosMaterialEliminado(&$CI, array $datos) {
        $CI->load->helper(array('FileUpload', 'date'));
        if (!empty($datos)) {
            foreach ($datos as $value) {
                eliminarArchivo($value);
            }
        }
    }

    public function actualizar(array $datos) {
        $evidencia = (!empty($datos['Evidencia'])) ? ', Evidencia = "' . $datos['Evidencia'] . '"' : '';
        $this->db_adist3->actualizar('
                update t_actividades_tareas_proyecto set                         
                    Descripcion = "' . $datos['textArea-descripcion-actividad'] . '",
                    FechaProyectada = "' . $datos['fecha-proyectada-actividad'] . '",
                    FechaReal = "' . $datos['fecha-real-actividad'] . '"
                    ' . $evidencia . '
                where Id = ' . $this->IdActividad);
    }

}
