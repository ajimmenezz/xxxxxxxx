<?php

namespace Librerias\Proyectos;

use Librerias\Interfaces\Objeto_General as Objeto_General;
use Librerias\Componentes\Coleccion as Coleccion;
use Librerias\Modelos\Modelo_Base as Modelo;
use Librerias\Proyectos\Actividad as Actividad;

class Tarea extends Objeto_General {

    private $tareas;
    private $actividades;
    private $idProyecto;
    private $usuario;

    public function __construct(Modelo $modelo) {
        parent::__construct($modelo);
        $this->tareas = new Coleccion('Tareas de objeto Tarea');
        $this->actividades = new Coleccion('Actividades de objeto Tarea');
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
    }

    public function generarElementos() {
        $this->tareas->limpiarColeccion();
        $this->actividades->limpiarColeccion();
        $listaTareas = $this->db_adist3->consulta('
            select 
                ttp.Id,
                ttp.IdProyecto,
                ttp.IdAlcance,
                ttp.Nombre,
                ttp.IdLider,
                concat(trp.Nombres, " ",trp.ApPaterno, " ",trp.ApMaterno) as Lider,
                ttp.IdArea,
                ( select Nombre from cat_v3_areas_proyectos where Id = ttp.IdArea ) as NombreArea,
                ttp.FechaInicio,
                ttp.Fechatermino,
                ttp.Avance,
                ttp.AvanceNodos
            from t_tareas_proyecto ttp
            inner join t_rh_personal trp
            on ttp.IdLider = trp.IdUsuario            
            where IdProyecto = "' . $this->idProyecto . '" and Flag = 1');

        if (!empty($listaTareas)) {
            foreach ($listaTareas as $value) {
                $asistentes = $this->listaAsistentes($value['Id']);
                $actividades = $this->listaActividades($value['Id']);
                $nodos = $this->listaNodosTarea($value['Id']);
                $this->tareas->agregar($value['Id'], array(
                    'Alcance' => $value['IdAlcance'],
                    'Nombre' => $value['Nombre'],
                    'Area' => $value['IdArea'],
                    'NombreArea' => $value['NombreArea'],
                    'IdLider' => $value['IdLider'],
                    'Lider' => $value['Lider'],
                    'Asistente' => $asistentes,
                    'Actividades' => $actividades,
                    'Nodos' => $nodos,
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaFin' => $value['Fechatermino'],
                    'Porcentaje' => $value['Avance'],
                    'PorcentajeNodos' => $value['AvanceNodos']
                ));
            }
        }
    }

    private function listaAsistentes(string $idTarea) {
        $lista = array();
        $listaAsistentes = $this->db_adist3->consulta('
            select
                *
            from t_asistentes_tareas         
           where IdTarea = ' . $idTarea);

        if (!empty($listaAsistentes)) {
            foreach ($listaAsistentes as $value) {
                array_push($lista, $value['IdUsuario']);
            }
        }
        return $lista;
    }

    private function listaActividades(string $idTarea) {
        $listaActividades = array();
        $lista = $this->db_adist3->consulta('
            select
                *,
                nombreUsuario(IdUsuario) as Usuario
            from t_actividades_tareas_proyecto         
           where IdTarea = ' . $idTarea . ' and Flag = 1');
        if (!empty($lista)) {
            foreach ($lista as $key => $value) {
                $actividad = new Actividad($this->db_adist3, $value['Id']);
                $this->actividades->agregar($value['Id'], $actividad);
                $listaActividades[$value['Id']] = array(
                    'Tarea' => $value['IdTarea'],
                    'IdUsuario' => $value['IdUsuario'],
                    'NombreUsuario' => $value['Usuario'],
                    'Descripcion' => $value['Descripcion'],
                    'FechaProyectada' => $value['FechaProyectada'],
                    'FechaReal' => $value['FechaReal'],
                    'FechaCaptura' => $value['FechaCaptura'],
                    'Evidencia' => (!empty($value['Evidencia'])) ? explode(',', $value['Evidencia']) : array(),
                    'Nodos' => $actividad->obtenerNodosActividad()
                );
            }
        }
        return $listaActividades;
    }

    private function listaNodosTarea(string $idTarea) {
        $listaNodos = array();

        $idAlcance = $this->db_adist3->consulta('select IdAlcance from t_tareas_proyecto where Id = ' . $idTarea . ' and Flag = 1');
        if (!empty($idAlcance)) {
            $idAlcance = $idAlcance[0]['IdAlcance'];
            if(!empty($idAlcance)){
                $nodos = $this->db_adist3->consulta('
                        select 
                            *, 
                            (select Nombre from cat_v3_ubicaciones_proyectos where Id = tap.IdUbicacion) as Ubicacion
                        from t_nodos_alcance_proyecto tnap
                        inner join t_alcance_proyecto tap
                        on tnap.IdAlcance = tap.Id
                        where tnap.IdAlcance = ' . $idAlcance . ' and tnap.IdTarea = ' . $idTarea) ;
            }else{
                $nodos = NULL;
            }
            if (!empty($nodos)) {
                $listaNodos = $this->generarLista($nodos, $idAlcance);
            }
        }
        return $listaNodos;
    }

    private function generarLista(array $nodos, string $alcance) {
        $lista = array();

        foreach ($nodos as $value) {
            if (!array_key_exists($value['Nombre'], $lista)) {
                $lista[$value['Nombre']] = array(
                    'Alcance' => $value['IdAlcance'],
                    'Ubicacion' => $value['Ubicacion'],
                    'Tipo' => $value['IdTipoNodo'],
                    'Actividad' => $value['IdActividad'],
                    'Nombre' => $value['Nombre'],
                    'Avance' => $value['Avance'],
                    'Material' => $this->listaMaterialNodo($value['Nombre'], $alcance),
                    'Evidencia' => (!empty($value['Evidencia'])) ? explode(',', $value['Evidencia']) : array()
                );
            }
        }

        return $lista;
    }

    private function listaMaterialNodo(string $nombre, string $alcance) {
        $lista = array();
        $listaMaterial = $this->db_adist3->consulta('select * from t_nodos_alcance_proyecto where Nombre = "' . $nombre . '" and IdAlcance = ' . $alcance);

        if (!empty($listaMaterial)) {
            foreach ($listaMaterial as $value) {
                array_push($lista, array(
                    'Accesorio' => $value['IdAccesorio'],
                    'Material' => $value['IdMaterial'],
                    'Cantidad' => $value['Cantidad'],
                    'Utilizado' => $value['Utilizado'],
                    'Justificacion' => $value['Justificacion']
                ));
            }
        }

        return $lista;
    }

    public function nuevaTarea(array $datos, string $claveProyecto) {

        if ($datos['checbox-repetir-tarea'] === 'repetir') {
            $complejos = $this->db_adist3->consulta('select Id from t_proyectos where Grupo = "' . $claveProyecto . '"');
            if (!empty($complejos)) {
                foreach ($complejos as $value) {
                    $this->insertarDBNuevaTarea($value['Id'], $datos);
                }
            }
        } else {
            $this->insertarDBNuevaTarea($datos['idProyecto'], $datos);
        }
    }

    private function insertarDBNuevaTarea(string $idProyecto, array $datos) {
        $alcance = (!empty($datos['idAlcanceNodos'])) ? $datos['idAlcanceNodos'] : '0';
        $idTarea = $this->db_adist3->insertar('
                    insert t_tareas_proyecto set                        
                        IdProyecto = ' . $idProyecto . ',
                        IdAlcance = ' . $alcance . ',
                        Nombre = "' . $datos['input-nombre-tarea'] . '",
                        IdLider = ' . $datos['select-lider-tarea'] . ',
                        IdArea = ' . $datos['select-area-tarea'] . ',
                        IdEstatus = 1,
                        FechaInicio = "' . $datos['fecha-inicio-tarea'] . '",
                        Fechatermino = "' . $datos['fecha-fin-tarea'] . '",
                        Flag = "1"
                    ');
        if (!empty($idTarea)) {
            foreach ($datos['select-asistente-tarea'] as $value) {
                $this->db_adist3->insertar('
                    insert t_asistentes_tareas set                        
                        IdUsuario = "' . $value . '",
                        IdTarea = "' . $idTarea . '"                    
                    ');
            }
            if (!empty($datos['idAlcanceNodos']) && !empty($datos['nodos'])) {
                foreach ($datos['nodos'] as $value) {
                    $this->db_adist3->actualizar('update t_nodos_alcance_proyecto set IdTarea = ' . $idTarea . ' where IdAlcance = ' . $datos['idAlcanceNodos'] . ' and Nombre = "' . $value . '"');
                }
            }
        }
    }

    public function obtenerTareas(string $idProyecto) {
        $this->idProyecto = $idProyecto;
        $this->generarElementos();
        return $this->tareas->elementos();
    }

    public function actualizarInformacion(array $datos) {
        $alcance = (!empty($datos['idAlcanceNodos'])) ? $datos['idAlcanceNodos'] : '0';
        $this->db_adist3->actualizar('
                        update t_tareas_proyecto set
                            IdAlcance = ' . $alcance . ',
                            Nombre = "' . $datos['input-nombre-tarea'] . '",
                            IdLider = ' . $datos['select-lider-tarea'] . ',
                            IdArea = ' . $datos['select-area-tarea'] . ',                        
                            FechaInicio = "' . $datos['fecha-inicio-tarea'] . '",
                            Fechatermino = "' . $datos['fecha-fin-tarea'] . '"                        
                        where Id = ' . $datos['idTarea']);

        $this->db_adist3->borrar('delete from t_asistentes_tareas where IdTarea = ' . $datos['idTarea']);
        foreach ($datos['select-asistente-tarea'] as $value) {
            $this->db_adist3->insertar('
                    insert t_asistentes_tareas set
                        IdUsuario = ' . $value . ',
                        IdTarea = ' . $datos['idTarea'] . '
                    ');
        }

        $this->db_adist3->actualizar('update t_nodos_alcance_proyecto set IdTarea = 0 where IdTarea = ' . $datos['idTarea']);
        if (!empty($datos['nodos'])) {
            foreach ($datos['nodos'] as $value) {
                $this->db_adist3->actualizar('update t_nodos_alcance_proyecto set IdTarea = ' . $datos['idTarea'] . ' where IdAlcance = ' . $datos['idAlcanceNodos'] . ' and Nombre = "' . $value . '"');
            }
        }        
    }

    public function bajaTarea(array $datos) {
        $this->db_adist3->actualizar('update t_nodos_alcance_proyecto set IdTarea = 0 where IdTarea = ' . $datos['idTarea']);
        $this->db_adist3->actualizar('
                        update t_tareas_proyecto set                         
                            Flag = 0
                        where Id = ' . $datos['idTarea']);
    }

    public function nuevaActividad(array $datos) {

        $this->actividades->limpiarColeccion();

        if ($datos['idAlcanceNodos'] === 'null') {
            $actividad = $this->insertarDBActividad($datos);
            $carpeta = 'Proyectos/Proyecto_' . $datos['idProyecto'] . '/Tarea_' . $datos['idTarea'] . '/Actividad_' . $actividad . '/';
            $evidencias = $this->subirArchivos($datos, 'evidenciaActividadSinNodos', $carpeta);
            $datos['Evidencia'] = $evidencias;
            $this->generarElementos();
            $Actividad = $this->actividades->obtenerElemento($actividad);
            $Actividad->actualizar($datos);
            $this->actualizarAvanceTareaPorDia($datos['idTarea']);
        } else {
            $actividad = $this->insertarDBActividad($datos);
            $this->actualizarAvanceTareaPorNodo($datos);
        }
        return $actividad;
    }

    private function insertarDBActividad(array $datos) {
        return $this->db_adist3->insertar('
                insert t_actividades_tareas_proyecto set
                    IdTarea = ' . $datos['idTarea'] . ',
                    IdUsuario = ' . $datos['usuario'] . ',
                    Descripcion = "' . $datos['textArea-descripcion-actividad'] . '",
                    FechaCaptura = now(),
                    FechaProyectada = "' . $datos['fecha-proyectada-actividad'] . '",
                    FechaReal = "' . $datos['fecha-real-actividad'] . '"
                ');
    }

    private function actualizarAvanceTareaPorDia(string $idTarea) {

        $tarea = $this->tareas->obtenerElemento($idTarea);
        $totalActividades = count($tarea['Actividades']);
        $FechaInicio = new \DateTime($tarea['FechaInicio']);
        $FechaFin = new \DateTime($tarea['FechaFin']);
        $dias = $FechaInicio->diff($FechaFin);
        $totalDias = $dias->format('%d');
        $totalDias = ($totalDias !== '0') ? $totalDias : '1';
        $porcentaje = $this->calcularPorcentaje($totalDias, $totalActividades);
        $this->db_adist3->actualizar('
                        update t_tareas_proyecto set                         
                            Avance = "' . $porcentaje . '"
                        where Id = ' . $idTarea);
    }

    private function actualizarAvanceTareaPorNodo(array $datos) {
        $terminados = array();
        $nodos = $this->listaNodosTarea($datos['idTarea']);

        if (!empty($nodos)) {
            foreach ($nodos as $value) {
                if ($value['Avance'] === '100%' && !array_key_exists($value['Nombre'], $terminados)) {
                    $terminados[$value['Nombre']] = $value;
                }
            }
            $total = count($nodos);
            $avance = count($terminados);
            $porcentaje = $this->calcularPorcentaje($total, $avance);
            $this->db_adist3->actualizar('
                        update t_tareas_proyecto set                         
                            AvanceNodos = "' . $porcentaje . '"
                        where Id = ' . $datos['idTarea']);
        }
    }

    public function agregarNodoActividad(array $datos) {
        $actividad = $this->actividades->obtenerElemento($datos['idActividad']);
        $actividad->agregarMaterial($datos);
        $this->actualizarAvanceTareaPorNodo($datos);
    }

    public function eliminarNodoDeActividad(array $datos) {
        $actividad = $this->actividades->obtenerElemento($datos['idActividad']);
        $actividad->eliminarNodo($datos);
        $this->actualizarAvanceTareaPorNodo($datos);
    }

    public function actualizarActividad(array $datos) {
        $actividad = $this->actividades->obtenerElemento($datos['idActividad']);
        $actividad->actualizar($datos);
    }

    public function eliminarActividad(array $datos) {
        $actividad = $this->actividades->obtenerElemento($datos['idActividad']);
        $actividad->eliminarNodos($datos);
        $this->actividades->limpiarColeccion();
        $this->db_adist3->actualizar('
                update t_nodos_alcance_proyecto set                        
                        IdActividad = 0,
                        Utilizado = 0,
                        Justificacion = "",
                        Avance = "0%",
                        Evidencia = ""
                where IdActividad = ' . $datos['idActividad']);
        $this->db_adist3->actualizar('
                update t_actividades_tareas_proyecto set
                        FechaCancelacion = now(),
                        Flag = 0
                where Id = ' . $datos['idActividad']);
        $this->generarElementos();
        if (empty($datos['idAlcanceNodos'])) {
            $this->actualizarAvanceTareaPorDia($datos['idTarea']);
        } else {
            $this->actualizarAvanceTareaPorNodo($datos);
        }
    }

    public function obtenerTareasTecnico(array $datos) {
        $listaTareas = array();
        $consulta = $this->db_adist3->consulta('
            select 
                ttp.IdProyecto,
                tat.IdTarea,
                tp.Ticket,
                tp.Nombre as Proyecto,    
                (select Nombre from cat_v3_sucursales where Id = tp.IdSucursal) as Complejo,	
                ttp.Nombre as Tarea,
                ttp.FechaInicio,
                ttp.Fechatermino,
                ttp.Avance,
                ttp.AvanceNodos
            from t_asistentes_tareas tat
            inner join t_tareas_proyecto ttp
            on tat.IdTarea = ttp.Id
            inner join t_proyectos tp
            on ttp.IdProyecto = tp.Id
            where tat.IdUsuario = ' . $datos['usuario'] . ' and ttp.Flag = 1 and tp.IdEstatus = 2');
        if (!empty($consulta)) {
            foreach ($consulta as $key => $value) {
                array_push($listaTareas, array(
                    'IdProyecto' => $value['IdProyecto'],
                    'IdTarea' => $value['IdTarea'],
                    'Ticket' => $value['Ticket'],
                    'Proyecto' => $value['Proyecto'],
                    'Complejo' => $value['Complejo'],
                    'Tarea' => $value['Tarea'],
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaTermino' => $value['Fechatermino'],
                    'Avance' => $value['Avance'],
                    'AvanceNodos' => $value['AvanceNodos']
                ));
            }
        }
        return $listaTareas;
    }

}
