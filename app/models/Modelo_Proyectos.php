<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Proyectos extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getSistemas() {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_sistemas_proyecto', array('Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $datos;
    }

    public function getTiposProyecto() {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_tipo_proyecto', array('Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $datos;
    }

    public function getTareasTecnico(string $idUsuario) {
        $datos = array();
        $consulta = $this->consulta('
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
            where tat.IdUsuario = ' . $idUsuario . ' and ttp.Flag = 1 and tp.IdEstatus = 2');
        if (!empty($consulta)) {
            foreach ($consulta as $key => $value) {                
                array_push($datos, array(
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
        return $datos;
    }

    /*
     * Encargado de la lista de todas las sucursales vigentes
     * 
     * @return array regresa Id y nombre de la sucursales vigentes
     */

    public function getSucursales(array $condiciones = NULL) {
        $datos = array();
        if (!empty($condiciones)) {
            $consulta = $this->encontrar('cat_v3_sucursales', $condiciones);
        } else {
            $consulta = $this->encontrar('cat_v3_sucursales', array('Flag' => '1'));
        }

        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $consultaEstado = $this->encontrar('cat_v3_estados', array('Id' => $value['IdEstado']));
                foreach ($consultaEstado as $valor) {
                    array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Estado' => $valor['Nombre']));
                }
            }
        }
        return $datos;
    }

    /*
     * Encargado de la lista de lideres de proyectos que estan activos
     * 
     * @return array regresa Id y Nombre del lider activo.
     */

    public function getLideres() {
        $datos = array();
        $consulta = $this->consulta('select * from cat_v3_usuarios where IdPerfil in (26,27) and Flag = 1');
        ;
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $datos;
    }

    /*
     * Encargado de lista de Proyectos creados que tienen estatus abierto (sin iniciar)
     * 
     * @return array regresa datos de los proyectos creados con estatus abierto (sin iniciar)
     */

    public function getProyectosSinAtender() {
        $datos = array();
        $consulta = $this->consulta('
                select
                    p.Id,
                    p.Ticket,                    
                    p.Nombre,    
                    e.Nombre Estado,
                    s.Nombre Complejo,
                    p.FechaInicio,
                    p.FechaTermino 
                from t_proyectos p left join cat_v3_sucursales s 
                on p.IdSucursal = s.Id 
                left join cat_v3_estados e 
                on s.IdEstado = e.Id 
                where p.IdEstatus = 1');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array(
                    'Id' => $value['Id'],
                    'Ticket' => $value['Ticket'],
                    'Nombre' => $value['Nombre'],
                    'Complejo' => $value['Complejo'],
                    'Estado' => $value['Estado'],
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaTermino' => $value['FechaTermino']
                ));
            }
        }
        return $datos;
    }

    /*
     * Encargado de lista de Proyectos creados que tienen estatus abierto (sin iniciar)
     * 
     * @return array regresa datos de los proyectos creados con estatus abierto (sin iniciar)
     */

    public function getProyectosIniciados() {
        $datos = array();
        $consulta = $this->consulta('
                select
                    p.Id,
                    p.Ticket,                    
                    p.Nombre,    
                    e.Nombre Estado,
                    s.Nombre Complejo,
                    p.FechaInicio,
                    p.FechaTermino,
                    p.Avance
                from t_proyectos p left join cat_v3_sucursales s 
                on p.IdSucursal = s.Id 
                left join cat_v3_estados e 
                on s.IdEstado = e.Id 
                where p.IdEstatus = 2');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array(
                    'Id' => $value['Id'],
                    'Ticket' => $value['Ticket'],
                    'Nombre' => $value['Nombre'],
                    'Complejo' => $value['Complejo'],
                    'Estado' => $value['Estado'],
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaTermino' => $value['FechaTermino'],
                    'Avance' => $value['Avance']
                ));
            }
        }
        return $datos;
    }

    /*
     * Encargado del entregar los datos del solicitado Proyecto.
     * 
     * @param string $Id Recibe el id del proyecto.
     * @return array regresa los datos del proyecto solicitado.
     */

    public function getProyecto($Id) {
        $datos = array();
        $lideres = array();
        $consulta = $this->encontrar('t_proyectos', array('Id' => $Id));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array(
                    'Id' => $value['Id'],
                    'Ticket' => $value['Ticket'],
                    'Nombre' => $value['Nombre'],
                    'Tipo' => $value['IdTipoProyecto'],
                    'Sucursal' => $value['IdSucursal'],
                    'Grupo' => $value['Grupo'],
                    'Observaciones' => $value['Observaciones'],
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaTermino' => $value['FechaTermino']
                ));
            }
            $consulta = $this->encontrar('t_lideres_proyecto', array('IdProyecto' => $Id));
            foreach ($consulta as $value) {
                array_push($lideres, $value['IdUsuario']);
            }
            $datos['lideres'] = $lideres;
        }
        return $datos;
    }

    /*
     * Encargado del actualizar los datos del proyecto
     * @param string $Id Recibe el id del proyecto.
     * 
     */

    public function actualizarProyecto(array $datos, $usuario) {
        $error = '';
        $campos = array(
            'Nombre' => $datos['nombre'],
            'IdTipoProyecto' => $datos['tipo'],
            'IdSucursal' => $datos['sucursal'][0],
            'Observaciones' => $datos['observaciones'],
            'FechaInicio' => $datos['fechaInicio'],
            'FechaTermino' => $datos['fechaTermino'],
            'IdUsuarioModifica' => $usuario
        );
        $consulta = $this->actualizar('t_proyectos', $campos, array('Id' => $datos['id']));
        if (isset($consulta)) {
            if ($consulta >= 0) {
                $anterior = array();
                $consulta = $this->consulta('select IdUsuario from t_lideres_proyecto where IdProyecto =' . $datos['id']);
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($anterior, $value['IdUsuario']);
                    }

                    if (count($anterior) > count($datos['lideres'])) {
                        $diferencia = array_diff($anterior, $datos['lideres']);
                    } else {
                        $diferencia = array_diff($datos['lideres'], $anterior);
                    }

                    if (!empty($diferencia)) {
                        $data = array();
                        foreach ($diferencia as $value) {
                            if (in_array($value, $anterior)) {
                                $consulta = $this->consulta('select count(*) as cantidad from t_lideres_proyecto lp '
                                        . 'inner join t_tareas_proyecto tp on lp.Id = tp.IdLider '
                                        . 'where lp.IdUsuario = ' . $value . ' and lp.IdProyecto =' . $datos['id']);
                                if (!empty($consulta[0]['cantidad'])) {
                                    $consulta = $this->encontrar('cat_v3_usuarios', array('Id' => $value));
                                    foreach ($consulta as $nombre) {
                                        array_push($data, $nombre['Nombre']);
                                    }
                                    return array($data);
                                } else {
                                    $this->eliminar('t_lideres_proyecto', array('IdUsuario' => $value, 'IdProyecto' => $datos['id']));
                                    return TRUE;
                                }
                            } else {
                                $consulta = $this->insertar('t_lideres_proyecto', array('IdUsuario' => $value, 'IdProyecto' => $datos['id']));
                                return TRUE;
                            }
                        }
                    } else {
                        return TRUE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                $error = parent::tipoError();
                return $error;
            }
        }
    }

    /*
     * Encargado de eliminar el proyecto
     * 
     */

    public function eliminarProyecto($IdProyecto, $usuario) {
        $campos = array(
            'IdEstatus' => '6',
            'IdUsuarioModifica' => $usuario['Id']
        );
        $consulta = $this->actualizar('t_proyectos', $campos, array('Id' => $IdProyecto));
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de iniciar el proyecto
     */

    public function iniciarProyecto($datos, $where) {
        $this->actualizar('t_proyectos', $datos, $where);
        return $this->getProyectosSinAtender();
    }

    /*
     * Encargado de regresar el ultimo numero consecutivo del grupo
     * 
     * @param string $grupo recibe el grupo que se va a buscar
     * @return array regresa el numero consecutivo
     */

    public function getGrupoProyecto($grupo) {
        $datos = array();
        $consulta = $this->consulta('select replace(Grupo,"' . $grupo . '", "") as Grupo from t_proyectos where Grupo like "' . $grupo . '%" order by Id desc limit 1');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['grupo'] = $value['Grupo'];
            }
        }
        return $datos;
    }

    /*
     * Encargado de insertar un nuevo registro en la tabla registro del adist2
     * 
     * @param string $sucursal recive el id de la sucursal
     * @return string regresa el Id_Orden de la insercion al adist2
     */

    public function setTicketAdist2($sucursal) {
        $consulta = $this->encontrar('cat_v3_sucursales', array('Id' => $sucursal));
        foreach ($consulta as $value) {
            $cliente = $value['IdCliente'];
        }
        $query = 'insert t_servicios set 
                    F_Start = curdate() + 0,
                    H_Start = curtime(),
                    Cliente = ' . $cliente . ',
                    Sucursal = 0,
                    Reporta = 0,
                    N_Asignador = 0,
                    Estatus = "EN PROCESO DE ATENCION",
                    Flag = 0,
                    F_Cierre = 00000000,
                    Ingeniero = 0,
                    MedioContacto = "INTERNET",
                    F_Asignacion = "",
                    H_Asignacion = "",
                    Observaciones = "CREACION DE PROYECTO ADIST V3",
                    Tipo = 16,
                    Gerente = 0,
                    Enlace = 0,
                    PersonalTI = 0,
                    Prioridad = 0';
        $host = $_SERVER['HTTP_HOST'];
        if ($host === 'siccob.solutions') {
            $consulta = parent::connectDBAdist2()->query($query);
            return parent::connectDBAdist2()->insert_id();
        } else {
            $consulta = parent::connectDBAdist3()->query($query);
            return parent::connectDBAdist3()->insert_id();
        }
    }

    /*
     * Encargado de insertar el proyecto sus lidere asignados
     * 
     * @param array $datos recibe los datos para crear el proyecto
     * @param array $lideres recibe los lideres del proyecto.
     */

    public function crearNuevoProyecto(array $datos, array $lideres) {
        $consulta = $this->insertar('t_proyectos', $datos);
        $id = parent::connectDBPrueba()->insert_id();
        if ($consulta > 0) {
            foreach ($lideres as $value) {
                $consulta = $this->insertar('t_lideres_proyecto', array('IdUsuario' => $value, 'IdProyecto' => $id));
            }
        } else {
            $consulta = 'Error para insertar lider';
        }
        return $id;
    }

    /*
     * Encargado de la lista de conceptos disponibles
     */

    public function getConcepto($tipo) {
        $data = array();
        $consulta = $this->encontrar('cat_conceptos_proyecto', array('IdTipoProyecto' => $tipo, 'Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($data, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $data;
    }

    /*
     * Encargado de la lista de areas por concepto
     */

    public function getAreaConcepto($concepto) {
        $data = array();
        $consulta = $this->encontrar('cat_areas_conceptos_proyectos', array('IdConcepto' => $concepto, 'Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($data, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Concepto' => $value['IdConcepto']));
            }
        }
        return $data;
    }

    /*
     * Encargado de la lista de las ubicaciones por area.
     */

    public function getUbicacionArea($area) {
        $data = array();
        $consulta = $this->encontrar('cat_ubicaciones_areas_proyectos', array('IdAreaConceptos' => $area, 'Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($data, array('Id' => $value['Id'], 'Nombre' => $value['Nombre'], 'Concepto' => $value['IdAreaConceptos']));
            }
        }
        return $data;
    }

    /*
     * Encargado de la lista de los accesorios por tipo de proyecto.
     */

    public function getAccesoriosTipoProyecto($tipo) {
        $data = array();
        $consulta = $this->encontrar('cat_accesorios_proyecto', array('IdTipoProyecto' => $tipo, 'Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($data, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $data;
    }

    /*
     * Encargado de insertar nuevo registro de alcance y accesorios para el proyecto
     * 
     */

    public function setAlcance(array $alcance) {
        $datos = array();
        $ubicacion = $this->encontrar('t_alcance_proyecto', array(
            'IdProyecto' => $alcance['id'],
            'IdConcepto' => $alcance['concepto'],
            'IdArea' => $alcance['area'],
            'IdUbicacion' => $alcance['ubicacion']
        ));

        if (empty($ubicacion)) {
            $datos['alcance'] = $this->insertar('t_alcance_proyecto', array(
                'IdProyecto' => $alcance['id'],
                'IdConcepto' => $alcance['concepto'],
                'IdArea' => $alcance['area'],
                'IdUbicacion' => $alcance['ubicacion'],
                'NodosDatos' => $alcance['nododatos'],
                'NodosVoz' => $alcance['nodovoz'],
                'NodosVideo' => $alcance['nodovideo']
            ));
            $idAlcance = parent::connectDBPrueba()->insert_id();

            foreach ($alcance['accesorios'] as $key => $value) {
                $consulta = $this->encontrar('cat_accesorios_proyecto', array('Nombre' => $value[0]));
                if (!empty($consulta)) {
                    foreach ($consulta as $valor) {
                        $IdAccesorio = $valor['Id'];
                    }
                    $datos['accesorio'] = $this->insertar('t_accesorios_alcance_proyecto', array(
                        'IdProyecto' => $alcance['id'],
                        'IdAlcance' => $idAlcance,
                        'IdAccesorio' => $IdAccesorio,
                        'Cantidad' => $value[1]
                    ));
                }
            }
            return $this->getAlcanceProyecto($alcance['id']);
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de eliminar el registro del alcance del proyecto
     */

    public function eliminarAlcance(array $datos) {
        $data = array();
        $consulta = $this->encontrar('t_alcance_proyecto', array('Id' => $datos['IdAlcance']));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $IdProyecto = $value['IdProyecto'];
            }
            $consulta = $this->eliminar('t_accesorios_alcance_proyecto', $datos);
            if ($consulta > 0) {
                $consulta = $this->eliminar('t_alcance_proyecto', array('Id' => $datos['IdAlcance']));
                if ($consulta > 0) {
                    return $this->getAlcanceProyecto($IdProyecto);
                } else {
                    return $data;
                }
            } else {
                return $data;
            }
        }
    }

    /*
     * Encargado de obtener el total de alcance del proyecto
     * 
     */

    public function getAlcanceTotal($Id) {
        $datos = array();
        $consulta = $this->consulta('call getMaterialTotalProyecto(' . $Id . ')');
        //libera el espacion del resultado para poder ejecutar otras consultas de la base de datos (mysqli).
        mysqli_next_result(parent::connectDBPrueba()->conn_id);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de lista de los lideres del proyecto la informacion es obtiene para 
     * solo obtener los lideres que fueron agregados al proyecto cuando se creo. Por tal motivo se obtiene el id la 
     * tabla t_lideres_preyecto 
     */

    public function getLideresProyecto($IdProyecto) {
        $datos = array();
        $consulta = $this->consulta('
                select 
                    lp.Id,     
                    cu.Id as IdUsuario,
                    concat(rhp.Nombres, " ",rhp.ApPaterno, " ",rhp.ApMaterno) as Nombre,
                    rhp.NSS,
                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil,
                    cu.Flag   
                from t_lideres_proyecto lp inner join cat_v3_usuarios cu 
                on lp.IdUsuario = cu.Id 
                inner join t_rh_personal rhp
                on cu.Id = rhp.IdUsuario
                where lp.IdProyecto =' . $IdProyecto);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de lista de las tareas del por el tipo de proyecto
     */

    public function getTareas() {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_tareas_proyectos', array('IdTipoProyecto' => $tipoProyecto));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de lista de las asistents del proyecto
     */

    public function getAsistentesProyecto(string $IdProyecto = '') {
        $datos = array();
        $datos['asistentes'] = array();
        $datos['oficiales'] = array();

        $consulta = $this->consulta('
                select 
                    cu.Id, 
                    concat(rhp.Nombres, " ",rhp.ApPaterno, " ",rhp.ApMaterno) as Nombre,
                    rhp.NSS,
                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil,
                    cu.Flag
                from cat_v3_usuarios cu                 
                inner join t_rh_personal rhp 
                on cu.Id = rhp.IdUsuario 
                where cu.IdPerfil in (30,31,32,81,82)');

        if (!empty($consulta)) {
//            array_push($datos['oficiales'], $consulta);
//            $consulta = $this->consulta('
//                select 
//                    ap.IdUsuario as Id, 
//                    concat(rhp.Nombres, " ",rhp.ApPaterno, " ",rhp.ApMaterno) as Nombre,
//                    rhp.NSS,
//                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil,
//                    cu.Flag
//                from t_asistentes_proyecto ap inner join cat_v3_usuarios cu 
//                on ap.IdUsuario = cu.Id 
//                inner join t_rh_personal rhp 
//                on cu.Id = rhp.IdUsuario 
//                where ap.IdProyecto =' . $IdProyecto);
//            array_push($datos['asistentes'], $consulta);
//            return $datos;
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de listar el alcance del proyecto
     */

    public function getAlcanceProyecto($IdProyecto) {
        $consulta = $this->consulta('call getTablaAlcance(' . $IdProyecto . ')');
        mysqli_next_result(parent::connectDBPrueba()->conn_id);
        return $consulta;
    }

    /*
     * Encargado de generar una solicitud
     * 
     */

    public function setSolicitud($usuario, $tipoSolicitud, $departamento, $ticket) {
        $consulta = parent::connectDBPrueba()->query('insert t_solicitudes set 
                IdTipoSolicitud = ' . $tipoSolicitud . ',
                IdDepartamento = ' . $departamento . ',
                Ticket = ' . $ticket . ',
                IdEstatus = 9,
                FechaCreacion = now(),
                Solicita = ' . $usuario);
        if (!empty($consulta)) {
            $IdSolicitud = parent::connectDBPrueba()->insert_id();
            return $IdSolicitud;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de listar las lineas de material
     * 
     */

    public function getLineaMaterial() {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_lineas_equipo', array('Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de listas el material
     * 
     */

    public function getMaterial() {
        $datos = array();
        $consulta = $this->consulta('
            select
            cvle.Id as linea,
            cvmoe.Id,
            cvmoe.Nombre,
            cvmoe.NoParte
            from 
            cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
            on cvle.Id = cvse.Linea
            inner join cat_v3_marcas_equipo cvme 
            on cvse.Id = cvme.Sublinea
            inner join cat_v3_modelos_equipo cvmoe
            on cvme.Id = cvmoe.Marca
            where cvmoe.Flag = 1;
                ');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar material para la solicitud
     * 
     */

    public function setMaterialSolicitudProyecto(array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar('t_material_proyecto', $datos);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de listar el la solicitud de material
     */

    public function getSolicitudMaterial($Id) {
        $datos = array();
        $consulta = $this->consulta('select '
                . 'm.IdSolicitud, mq.Nombre, mq.NoParte, m.Cantidad '
                . 'from t_material_proyecto m inner join cat_v3_modelos_equipo mq on m.IdMaterial = mq.Id '
                . 'where m.IdProyecto = ' . $Id);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['solicitud'] = $value['IdSolicitud'];
                array_push($datos, array(
                    'material' => $value['Nombre'],
                    'numParte' => $value['NoParte'],
                    'cantidad' => $value['Cantidad']
                ));
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de eliminar la el material de una solicitud
     */

    public function eliminarMaterialSolicitud(array $datos) {
        $consulta = $this->eliminar('t_material_proyecto', $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar la solicitud de personal de proyecto
     * 
     */

    public function setPersonalSolicitud(array $datos) {
        $consulta = $this->insertar('t_personal_proyecto', $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de listar el los datos de la solicitud de personal
     * 
     */

    public function getPersonal($Id) {
        $datos = array();
        $consulta = $this->encontrar('t_personal_proyecto', array('IdProyecto' => $Id), 1);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['solicitud'] = $value['IdSolicitud'];
                $datos['perfil'] = $value['DescripcionPerfil'];
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar la solicitud
     * 
     */

    public function actualizaSolicitudPersonal(array $datos) {
        //FALTA AGREGAR EL CAMBIO EN LA TABLA DE HISTORICO DE PERSONAL
        $consulta = $this->actualizar('t_personal_proyecto', array('DescripcionPerfil' => $datos['perfil']), array('IdSolicitud' => $datos['solicitud']));
        if (isset($consulta)) {
            if ($consulta >= 0) {
                return TRUE;
            } else {
                $error = parent::tipoError();
                return $error;
            }
        }
    }

    /*
     * Encargado de insertar una nueva tarea del proyecto
     */

    public function setNuevaTarea(array $tarea) {
        $consulta = $this->insertar('t_tareas_proyecto', $tarea);
        if (!empty($consulta)) {
            $IdTarea = parent::connectDBPrueba()->insert_id();
            return $IdTarea;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de validar si la tarea existe en el proyecto
     * 
     */

    public function validarTareaProyecto($datos) {
        $consulta = $this->encontrar('t_tareas_proyecto', $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de insertar los asistentes de la tarea
     * 
     */

    public function setAsistenteTarea(array $datos) {
        $consulta = $this->insertar('t_asistentes_tareas', $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener la lista de las tareas
     * 
     */

    public function getTareasProyecto($IdProyecto) {
        $consulta = $this->consulta('call getTareasProyecto(' . $IdProyecto . ')');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return array();
        }
    }

    /*
     * Encargado de brindar la informacion de la tarea
     * 
     */

    public function getTareaProyecto(array $where) {
        $datos = array();
        $consulta = $this->encontrar('t_tareas_proyecto', $where);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['concepto'] = $value['IdConcepto'];
                $datos['area'] = $value['IdArea'];
                $datos['lider'] = $value['IdLider'];
                $datos['tarea'] = $value['IdTarea'];
                $datos['inicio'] = $value['FechaInicio'];
                $datos['termino'] = $value['Fechatermino'];
            }
            $consulta = $this->consulta('select ast.IdUsuario as Id, cu.Nombre , rhp.NSS from 
                                            t_asistentes_tareas ast inner join cat_v3_usuarios cu 
                                            on ast.IdUsuario = cu.Id 
                                            inner join t_rh_personal rhp 
                                            on ast.IdUsuario = rhp.IdUsuario
                                            where ast.IdTarea =' . $where['Id']);
            $datos['asistentes'] = $consulta;
            return $datos;
        } else {
            
        }
    }

    /*
     * Encargado de actualizar la tarea del proyecto
     * 
     */

    public function actualizarTarea(array $datos) {
        $this->actualizar('t_tareas_proyecto', array(
            'IdTarea' => $datos['tarea'],
            'IdLider' => $datos['lider'],
            'IdConcepto' => $datos['concepto'],
            'IdArea' => $datos['area'],
            'FechaInicio' => $datos['inicio'],
            'Fechatermino' => $datos['termino']
                ), array('Id' => $datos['idTarea']));

        $this->eliminar('t_asistentes_tareas', array('IdTarea' => $datos['idTarea']));
        if (isset($datos['asistentes'])) {
            foreach ($datos['asistentes'] as $value) {
                $this->setAsistenteTarea(array('IdUsuario' => $value[0], 'IdTarea' => $datos['idTarea']));
            }
        }
    }

    /*
     * Encargado de eliminar la tarea del proyecto
     * 
     */

    public function eliminarTarea($id) {
        $consulta = $this->eliminar('t_tareas_proyecto', array('Id' => $id));
        if (!empty($consulta)) {
            $consulta = $this->eliminar('t_asistentes_tareas', array('IdTarea' => $id));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obterner la lista de proyectos que ya estan iniciados
     * 
     */

    public function getProyectos(string $consulta) {
        $datos = $this->consulta($consulta);
        if (!empty($datos)) {
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener los datos del reponsable del proyecto
     * 
     */

    public function getResponsableProyecto(string $IdProyecto) {
        $datos = array();
        $consulta = $this->consulta('
                select 
                    tp.IdUsuario,
                    cu.Nombre                                                        
                from t_proyectos tp inner join cat_v3_usuarios cu 
                on tp.IdUsuario = cu.Id
                where tp.Id =' . $IdProyecto);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

}
