<?php

namespace Librerias\Proyectos;

use Controladores\Controller_Datos_Usuario as General;

class Proyecto extends General {

    private $DBP;
    private $Catalogo;
    private $Tarea;
      
    public function __construct() {
        parent::__construct();
        $this->DBP = \Modelos\Modelo_Proyectos::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Tarea = \Librerias\Proyectos\Tareas::factory();
        parent::getCI()->load->helper('date');
        
        
    }
    
    /*
     * Encargado de obtener el formulario para generar una nueva tarea
     * 
     * @param array $datos  Recibe un arreglo con el dato del tipo de proyecto.
     * @return array Regresa la lista de usuarios que tiene el tipo de proyecto
     */

    public function getFormularioTarea(array $datos) {        
        $data = array();
        $data['tipoProyecto'] = $datos['tipoProyecto'];
        $data['concepto'] = $this->DBP->getConcepto($datos['tipoProyecto']);
        $data['areas'] = array();
        if (!empty($data['concepto'])) {
            foreach ($data['concepto'] as $concepto) {
                $consulta = $this->DBP->getAreaConcepto($concepto['Id']);
                array_push($data['areas'], $consulta);
            }
        }
        $data['lideres'] = $this->DBP->getLideresProyecto($datos['id']);
        $data['tareas'] = $this->Tarea->getTareasTipoProyecto($datos['tipoProyecto']);
        $data['asistentes'] = $this->DBP->getAsistentesProyecto($datos['id']);
        return array('formulario' => parent::getCI()->load->view('Proyectos/Modal/GenerarTarea', $data, TRUE), 'datos' => $data);
    }

    /*
     * Encargado de validar si se van a crear un grupo de proyectos o solo es un 
     * proyecto a partir de la cantidad de sucursales.
     * 
     * @param array $datos recibe los datos del proyecto
     * @return array regresa los datos del proyecto creado
     */

    public function crearProyectos(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $numeroSucursales = count($datos['sucursal']);
        if ($numeroSucursales > 1) {
            $grupo = substr(trim($datos['textTipo']), 0, 2) . $numeroSucursales . strtoupper(substr(trim($datos['nombre']), 0, 2)) . strtoupper(substr(trim($datos['nombre']), -2));
            $consulta = $this->DBP->getGrupoProyecto($grupo);
            if (!empty($consulta)) {
                $grupo .= (1 + $consulta['grupo']);
            } else {
                $grupo .= '1';
            }
            return $this->insertandoProyectos($datos, $usuario, $grupo);
        } else {
            return $this->insertandoProyectos($datos, $usuario);
        }
    }

    /*
     * Encargado de encargado de obtener los datos del proyecto
     * para cargarlos en la seccion de generar proyecto
     * 
     * @param string $Id recibe el Id del proyecto que se va ha cargar
     * @return array regresa los datos del proyecto.
     */

    public function getProyecto(string $Id) {
        $datos = array();
        $datos['datosProyecto'] = $this->DBP->getProyecto($Id);
        if (!empty($datos['datosProyecto'])) {
            $datos['accesorios'] = $this->DBP->getAccesoriosTipoProyecto($datos['datosProyecto'][0]['Tipo']);
            $datos['totalAlcance'] = $this->DBP->getAlcanceTotal($Id);
            $datos['linea'] = $this->DBP->getLineaMaterial();
            $datos['Material'] = $this->DBP->getMaterial();
            $datos['Personal'] = $this->DBP->getPersonal($Id);
            $datos['SolicitudMaterial'] = $this->DBP->getSolicitudMaterial($Id);
            $datos['tareas'] = $this->DBP->getTareasProyecto($Id);
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de eliminar el proyecto
     * 
     */

    public function eliminarProyecto($IdProyecto) {
        $usuario = $this->Usuario->getDatosUsuario();
        $consulta = $this->DBP->eliminarProyecto($IdProyecto, $usuario);
        if (!empty($consulta)) {
            $consulta = $this->DBP->getProyectosSinAtender();
            return $consulta;
        } else {
            return $consulta;
        }
    }

    /*
     * Encargado de actualizar los datos del proyecto
     * 
     */

    public function actualizarProyecto(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        return $this->DBP->actualizarProyecto($datos, $usuario['Id']);
    }

    /*
     * Encargado de crear proyecto(s) y ticket(s) en la base de datos adist2
     * 
     * @param array $datos recibe los datos del proyecto
     * @param array $usuario recibe los datos del usuario de la session
     * @param string $grupo recibe el grupo del proyecto de no estar definido lo asigno como null
     * @return array regresa los datos del proyecto(s) creado(s).
     */

    private function insertandoProyectos(array $datos, array $usuario, $grupo = NULL) {
        $proyecto = array();
        $grupo = (isset($grupo)) ? $grupo : '';
        foreach ($datos['sucursal'] as $key => $value) {
            $consulta = $this->DBP->getSucursales(array('Id' => $value, 'Flag' => '1'));
            $Estado = $consulta[0]['Estado'];
            $nombreSucursal = $consulta[0]['Nombre'];
            $idSucursal = $consulta[0]['Id'];
            $ticket = $this->DBP->setTicketAdist2($value);
            $consulta = $this->DBP->crearNuevoProyecto(array(
                'Ticket' => $ticket,
                'Nombre' => $datos['nombre'],
                'IdTipoProyecto' => $datos['tipo'],
                'IdUsuario' => $usuario['Id'],
                'IdSucursal' => $value,
                'IdEstatus' => '1',
                'Grupo' => $grupo,
                'Observaciones' => $datos['observaciones'],
                'FechaInicio' => $datos['fechaInicio'],
                'FechaTermino' => $datos['fechaTermino'],
                'IdUsuarioModifica' => $usuario['Id']
                    ), $datos['lideres']);
            if (!empty($consulta)) {
                $IdProyecto = $consulta;
                $proyecto[$key] = array(
                    'Id' => $consulta,
                    'Ticket' => $ticket,
                    'Nombre' => $datos['nombre'],
                    'Sucursal' => $nombreSucursal,
                    'IdSucursal' => $idSucursal,
                    'Estado' => $Estado
                );
            } else {
                $proyecto[$key] = 'Error para generar el proyecto ' . $ticket;
            }
        }
        $accesorios = $this->DBP->getAccesoriosTipoProyecto($datos['tipo']);
        return array($grupo, $proyecto, $accesorios);
    }

    /*
     * Encargado de iniciar el proyecto
     */

    public function iniciarProyecto(array $datos) {
        return $this->DBP->iniciarProyecto(array(
                    'IdEstatus' => '2'
                        ), array('Id' => $datos['id']));
    }

    /*
     * Encargado de generar el alcance y accesorios del proyecto
     * 
     */

    public function setAlcanceProyecto(array $datos) {
        $indice = array();
        $consulta = $this->DBP->setAlcance($datos);
        if (!empty($consulta)) {
            foreach ($consulta as $registro) {
                foreach ($registro as $key => $value) {
                    array_push($indice, $key);
                }
                break;
            }
            return array($indice, $consulta);
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtenere total del alcance
     * 
     */

    public function getAlcanceTotal($Id) {
        $datos = array();
        $consulta = $this->DBP->getAlcanceTotal($Id);
        if (!empty($consulta)) {
            $datos['alcance'] = $consulta;
            $datos['linea'] = $this->DBP->getLineaMaterial();
            $datos['Material'] = $this->DBP->getMaterial();
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar el alcance y accesorios del proyecto
     * 
     */

    public function eliminarAlcance($IdAlcance) {
        $indice = array();
        $consulta = $this->DBP->eliminarAlcance(array('IdAlcance' => $IdAlcance));
        if (!empty($consulta)) {
            foreach ($consulta as $registro) {
                foreach ($registro as $key => $value) {
                    array_push($indice, $key);
                }
                break;
            }
            return array($indice, $consulta);
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de generar un nueva solicitud y agregar material
     * 
     */

    public function setSolicitudMaterial(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $consulta = $this->DBP->setSolicitud($usuario['Id'], '2', $usuario['IdDepartamento'], $datos['ticket']);
        if (!empty($consulta)) {
            $idSolicitud = $consulta;
            foreach ($datos['material'] as $value) {
                $consulta = $this->DBP->setMaterialSolicitudProyecto(array(
                    'IdSolicitud' => $idSolicitud,
                    'IdProyecto' => $datos['proyecto'],
                    'IdMaterial' => $value[0],
                    'Cantidad' => $value[3],
                    'IdUsuarioModifica' => $usuario['Id'],
                    'IdEstatus' => '9',
                    'IdRecibe' => '0'
                ));
            }
            $data['id'] = $idSolicitud;
            $data['SolicitudMaterial'] = $this->DBP->getSolicitudMaterial($datos['proyecto']);
            return $data;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar la solicitud de material
     * 
     */

    public function actualizarSolicitudMaterial(array $datos) {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $consulta = $this->DBP->eliminarMaterialSolicitud(array('IdSolicitud' => $datos['solicitud']));
        if ($consulta) {
            foreach ($datos['material'] as $value) {
                $consulta = $this->DBP->setMaterialSolicitudProyecto(array(
                    'IdSolicitud' => $datos['solicitud'],
                    'IdProyecto' => $datos['proyecto'],
                    'IdMaterial' => $value[0],
                    'Cantidad' => $value[3],
                    'IdUsuarioModifica' => $usuario['Id'],
                    'IdEstatus' => '8',
                    'IdRecibe' => '0'
                ));
            }
            $data['SolicitudMaterial'] = $this->DBP->getSolicitudMaterial($datos['proyecto']);
            return $data;
        } else {
            return FALSE;
        }
    }

    /*
     * Encagardo de generar solicitud personal
     */

    public function setSolicitudPersonal(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $solicitud = $this->DBP->setSolicitud($usuario['Id'], '1', $usuario['IdDepartamento'], $datos['ticket']);
        if (!empty($solicitud)) {
            $consulta = $this->DBP->setPersonalSolicitud(array(
                'IdSolicitud' => $solicitud,
                'IdProyecto' => $datos['proyecto'],
                'DescripcionPerfil' => $datos['perfil'],
                'IdUsuarioModifica' => $usuario['Id']
            ));
            if ($consulta) {
                return $solicitud;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar la solicitud de personal
     */

    public function actualizarSolicitudPersonal(array $datos) {
        $consulta = $this->DBP->actualizaSolicitudPersonal($datos);
        return $consulta;
    }

    /*
     * Encargado de generar una nueva tarea
     * 
     */

    public function setTareaNueva(array $datos) {
        $data = array();
        $consulta = $this->DBP->validarTareaProyecto(array(
            'IdProyecto' => $datos['id'],
            'IdTarea' => $datos['tarea'],
            'IdConcepto' => $datos['concepto'],
            'IdArea' => $datos['area']
        ));
        if (!$consulta) {
            $consulta = $this->DBP->setNuevaTarea(array(
                'IdProyecto' => $datos['id'],
                'IdTarea' => $datos['tarea'],
                'IdLider' => $datos['lider'],
                'IdConcepto' => $datos['concepto'],
                'IdArea' => $datos['area'],
                'IdEstatus' => '1',
                'FechaInicio' => $datos['inicio'],
                'Fechatermino' => $datos['termino']
            ));
            if (!empty($consulta)) {
                if (isset($datos['asistentes'])) {
                    foreach ($datos['asistentes'] as $value) {
                        $consulta = $this->DBP->setAsistenteTarea(array('IdUsuario' => $value[0], 'IdTarea' => $consulta));
                    }
                }
                return $this->DBP->getTareasProyecto($datos['id']);
            } else {
                return 'No se pudo crear la tarea';
            }
        } else {
            return 'Ya se cuenta registrada la tarea';
        }
    }

    /*
     * Encardo de obtener los datos de la tarea del proyecto
     * 
     */

    public function getTareaInformacion(array $datos) {
        $data = array();
        $data['datostarea'] = $this->DBP->getTareaProyecto(array('Id' => $datos['tarea']));
        $data['tipoProyecto'] = $datos['tipoProyecto'];
        $data['concepto'] = $this->DBP->getConcepto($datos['tipoProyecto']);
        $data['areas'] = array();
        if (!empty($data['concepto'])) {
            foreach ($data['concepto'] as $concepto) {
                $consulta = $this->DBP->getAreaConcepto($concepto['Id']);
                array_push($data['areas'], $consulta);
            }
        }
        $data['lideres'] = $this->DBP->getLideresProyecto($datos['id']);
        $data['tareas'] = $this->DBP->getTareas($datos['tipoProyecto']);
        $data['asistentes'] = $this->DBP->getAsistentesProyecto($datos['id']);
        return array('formulario' => parent::getCI()->load->view('Proyectos/Modal/GenerarTarea', $data, TRUE), 'datos' => $data);
    }

    /*
     * Encargado de actualizar la tarea del proyecto
     * 
     */

    public function actualizarTarea(array $datos) {
        $this->DBP->actualizarTarea($datos);
        return $this->DBP->getTareasProyecto($datos['id']);
    }

    /*
     * Encargado de eliminar la tarea del proyecto
     * 
     */

    public function eliminarTarea($datos) {
        $consulta = $this->DBP->eliminarTarea($datos['idTarea']);
        if ($consulta) {
            return $this->DBP->getTareasProyecto($datos['id']);
        } else {
            return 'No se puede eliminar la tarea. favor de volver a intentarlo.';
        }
        return $datos;
    }

    /*
     * Encargada de obtener la lista de los proyectos que esten sin iniciar
     * 
     */

    public function getSeguimientoProyectos() {
        $perfilGerente = array('1', '2', '3', '4', '42');
        $perfilResponsables = array('23', '24', '25');
        $usuario = $this->Usuario->getDatosUsuario();
        if (in_array($usuario['IdPerfil'], $perfilGerente)) {
            return $this->DBP->getProyectos('
            select 
                tp.Id,
                tp.Ticket,
                tp.Nombre as Proyecto,
                (select Nombre from cat_v3_sucursales where Id = tp.IdSucursal) as Complejo,
                tp.Grupo,
                (select Nombre from cat_tipos_proyecto where Id = tp.IdTipoProyecto) as Tipo,
                tp.FechaInicio,
                tp.FechaTermino
            from t_proyectos tp            
            where tp.IdEstatus = 2
                ');
        } else if (in_array($usuario['IdPerfil'], $perfilResponsables)) {
            return $this->DBP->getProyectos('
            select 
                tp.Id,
                tp.Ticket,
                tp.Nombre as Proyecto,
                (select Nombre from cat_v3_sucursales where Id = tp.IdSucursal) as Complejo,
                tp.Grupo,
                (select Nombre from cat_tipos_proyecto where Id = tp.IdTipoProyecto) as Tipo,
                tp.FechaInicio,
                tp.FechaTermino
            from t_proyectos tp            
            where tp.IdEstatus = 2 and tp.IdUsuario = ' . $usuario['Id']);
        } else {
            return $this->DBP->getProyectos('
            select 
                tp.Id,
                tp.Ticket,
                tp.Nombre as Proyecto,
                (select Nombre from cat_v3_sucursales where Id = tp.IdSucursal) as Complejo,
                tp.Grupo,
                (select Nombre from cat_tipos_proyecto where Id = tp.IdTipoProyecto) as Tipo,
                tp.FechaInicio,
                tp.FechaTermino
            from t_tareas_proyecto ttp inner join t_proyectos tp 
            on ttp.IdProyecto = tp.Id 
            where (select IdUsuario from t_lideres_proyecto  where Id = ttp.IdLider) = ' . $usuario['Id'] . ' and tp.IdEstatus = 2 group by tp.Id');
        }
    }

    /*
     * Encargado de obtener lo datos del proyecto y la seccion para su seguimiento
     * 
     */

    public function getSeguimientoProyecto(array $datos) {
        $perfilGerente = array('1', '2', '3', '4', '23', '24', '25', '42');
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();
        $data['TiposProyectos'] = $this->Catalogo->catTiposProyecto('3');
        $data['Sucursales'] = $this->Catalogo->catSucursales('3', array('Flag' => '1'));
        $data['Lideres'] = $this->Catalogo->catLideres('3');
        $data['ResponsableProyecto'] = $this->DBP->getResponsableProyecto($datos['proyecto']);
        $data['LideresProyecto'] = $this->DBP->getLideresProyecto($datos['proyecto']);
        $data['AsistentesProyecto'] = $this->DBP->getAsistentesProyecto($datos['proyecto']);
        $data['informacion'] = $this->getProyecto($datos['proyecto']);
        if (in_array($usuario['IdPerfil'], $perfilGerente)) {
            $data['formulario'] = parent::getCI()->load->view('Proyectos/Modal/SeguimientoGerente', $data, true);
        } else {
            $data['formulario'] = parent::getCI()->load->view('Proyectos/Modal/SeguimientoLider', $data, true);
        }
        return $data;
    }

}
