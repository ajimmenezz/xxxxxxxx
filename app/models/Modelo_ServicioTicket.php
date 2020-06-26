<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_ServicioTicket
 *
 * @author Freddy
 */
class Modelo_ServicioTicket extends Modelo_Base {

    public function __construct() {
        parent::__construct();
        $ci = get_instance();
        $ci->load->helper('date');
        date_default_timezone_set('America/Mexico_City');
    }

    /*
     * Encargado de crear un nuevo servicio
     * 
     */

    public function setNuevoServicio(array $datos) {
        $consulta = $this->insertar('t_servicios_ticket', $datos);
        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de crear un nuevo servicio
     * 
     */

    public function setServicioTrafico(array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar('t_traficos_generales', $datos);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Metodo que obtiene los datos de un usuario que va atender el servicio
     * 
     */

    public function getDatosAtiende(string $usuario) {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_usuarios', array('Id' => $usuario));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['IdUsuario'] = $usuario;
                $datos['Nombre'] = $value['Nombre'];
                $datos['IdPerfil'] = $value['IdPerfil'];
                $datos['EmailCorporativo'] = $value['EmailCorporativo'];
            }
            $perfil = $this->consulta('
                SELECT 
                cp.*,
                cvds.IdArea 
                FROM cat_perfiles cp INNER JOIN cat_v3_departamentos_siccob cvds 
                ON cp.IdDepartamento = cvds.Id 
                WHERE cp.Id = ' . $datos['IdPerfil']
            );

            foreach ($perfil as $value) {
                $datos['Perfil'] = $value['Nombre'];
                $datos['IdDepartamento'] = $value['IdDepartamento'];
                $datos['IdArea'] = $value['IdArea'];
            }
            return $datos;
        }
    }

    /*
     * Encargado de generar la consulta para obtener la lista de los servicios.
     * 
     */

    public function getServicios(string $consulta) {
        $datos = $this->consulta($consulta);

        if (!empty($datos)) {
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener los datos del servicio
     * 
     */

    public function getDatosServicio(string $servicio) {
        $datos = array();
        $notas = array();
        $consulta = $this->consulta('
            select 
                tst.Ticket,
                tst.Id,
                ts.Folio,
                tst.IdSolicitud,
                ts.FechaCreacion as FechaCrecionSolicitud,
                ts.IdTipoSolicitud,
                tst.IdTipoServicio,
                tipoServicio(tst.IdTipoServicio) as TipoServicio,
                (select Seguimiento from cat_v3_servicios_departamento where Id = tst.IdTipoServicio) as tieneSeguimiento,
                tst.IdEstatus,
                estatus(tst.IdEstatus) as Estatus,
                tst.Solicita,
                nombreUsuario(ts.Solicita) as NombreSolicita,
                nombreUsuario(tst.Atiende) as NombreAtiende,
                tst.Atiende,
                tst.FechaCreacion,
                tst.FechaInicio,
                tst.FechaConclusion,
                tst.Descripcion as DescripcionServicio,
                (select SDKey from cat_v3_usuarios where Id = tst.Atiende) as SDKeyAtiende,
                IF(ts.IdSucursal != NULL || ts.IdSucursal = 0, tst.IdSucursal, ts.IdSucursal) AS IdSucursal,
                tst.NombreFirma,
                tst.Firma,
                sucursal(tst.IdSucursal) AS Sucursal
            from t_servicios_ticket tst inner join t_solicitudes ts
            on tst.IdSolicitud = ts.Id where tst.Id = ' . $servicio);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                if ($value['IdTipoSolicitud'] === '3') {
                    $descripcionSolicitud = $this->consulta('select Descripcion from t_solicitudes_internas where IdSolicitud = ' . $value['IdSolicitud']);
                    $datos['descripcionSolicitud'] = $descripcionSolicitud[0]['Descripcion'];
                }
                $datos['IdTipoSolicitud'] = $value['IdTipoSolicitud'];
                $datos['Ticket'] = $value['Ticket'];
                $datos['Folio'] = $value['Folio'];
                $datos['IdSolicitud'] = $value['IdSolicitud'];
                $datos['FechaCrecionSolicitud'] = $value['FechaCrecionSolicitud'];
                $datos['IdTipoServicio'] = $value['IdTipoServicio'];
                $datos['tieneSeguimiento'] = $value['tieneSeguimiento'];
                $datos['TipoServicio'] = $value['TipoServicio'];
                $datos['IdEstatus'] = $value['IdEstatus'];
                $datos['Estatus'] = $value['Estatus'];
                $datos['Solicita'] = $value['Solicita'];
                $datos['NombreSolicita'] = $value['NombreSolicita'];
                $datos['NombreAtiende'] = $value['NombreAtiende'];
                $datos['Atiende'] = $value['Atiende'];
                $datos['FechaCreacion'] = $value['FechaCreacion'];
                $datos['FechaInicio'] = $value['FechaInicio'];
                $datos['FechaConclusion'] = $value['FechaConclusion'];
                $datos['DescripcionServicio'] = $value['DescripcionServicio'];
                $datos['SDKeyAtiende'] = $value['SDKeyAtiende'];
                $datos['IdServicio'] = $value['Id'];
                $datos['IdSucursal'] = $value['IdSucursal'];
                $datos['NombreFirma'] = $value['NombreFirma'];
                $datos['Firma'] = $value['Firma'];
                $datos['Sucursal'] = $value['Sucursal'];
            }

            $consultaNotas = $this->consulta('
                select 
                    usuario(tns.IdUsuario) as usuario,
                    (select UrlFoto from t_rh_personal where IdUsuario = tns.IdUsuario) as Foto,
                    tns.Nota,
                    tns.Archivos,
                    tns.Fecha
                from t_notas_servicio tns where IdServicio = ' . $servicio . ' order by tns.Id desc');
            if (!empty($consultaNotas)) {
                foreach ($consultaNotas as $key => $value) {
                    $archivos = null;
                    if (!empty($value['Archivos'])) {
                        $archivos = explode(',', $value['Archivos']);
                        array_push($notas, array(
                            'usuario' => $value['usuario'],
                            'foto' => $value['Foto'],
                            'nota' => $value['Nota'],
                            'fecha' => $value['Fecha'],
                            'archivos' => $archivos
                                )
                        );
                    } else {
                        array_push($notas, array(
                            'usuario' => $value['usuario'],
                            'foto' => $value['Foto'],
                            'nota' => $value['Nota'],
                            'fecha' => $value['Fecha'],
                            'archivos' => array()
                                )
                        );
                    }
                }
                $datos['Notas'] = $notas;
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actaulizar un servicio
     * 
     */

    public function actualizarServicio(string $tabla, array $datos, array $where) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->actualizar($tabla, $datos, $where);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        return $consulta;
    }

    /*
     * Encargado de obtener la lista de asistentes del proyecto
     * 
     */

    public function getAsistentes() {
        $consulta = $this->consulta('
            select 
                cu.Id, 
                trhp.Nombres,
                trhp.ApPaterno,
                trhp.ApMaterno,                
                trhp.NSS,
                trhp.Tel1,
                trhp.FechaCaptura
            from cat_v3_usuarios cu inner join t_rh_personal trhp
            on cu.Id = trhp.IdUsuario
            where IdPerfil in (33,34,35) and cu.Flag <> 0 and trhp.NSS is not null;    
                ');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener los asistentes del proyecto
     * 
     */

    public function getAsistentesProyecto(string $servicio) {
        $consulta = $this->consulta('
            select 
                tap.IdUsuario, 
                trhp.Nombres,
                trhp.ApPaterno,
                trhp.ApMaterno,                
                trhp.NSS,
                trhp.Tel1,
                trhp.FechaCaptura
            from t_asistentes_proyecto tap inner join t_rh_personal trhp
            on trhp.IdUsuario = tap.IdUsuario
            where IdServicio = ' . $servicio . ' and tap.IdEstatus = 11;');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de insertar los asistentes en el proyecto
     * 
     */

    public function setAsistenteProyecto(array $datos) {
        $consulta = $this->insertar('t_asistentes_proyecto', $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de eliminar los asistentes del proyecto
     * 
     */

    public function eliminarAsistenteProyecto(array $where) {

        $consulta = $this->consulta('
            select 
                *
            from t_tareas_proyecto ttp inner join t_asistentes_tareas tat 
            on ttp.Id = tat.IdTarea
            where ttp.IdProyecto = ' . $where['IdProyecto'] . ' and tat.IdUsuario = ' . $where['IdUsuario'] . ' and ttp.IdEstatus in (1,2)');

        if (empty($consulta)) {
            $eliminarProyecto = $this->eliminar('t_asistentes_proyecto', $where);
            if (!empty($eliminarProyecto)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return 'AsignadoTarea';
        }
    }

    /*
     * Encargado de obtener el Id del proyecto que esta asignado el servicio
     * 
     */

    public function getDatosProyecto(string $ticket) {
        $datos = array();
        $consulta = $this->consulta('
            select
                Id,
                Nombre,
                (select Nombre from cat_tipos_proyecto where Id = IdTipoProyecto ) as Tipo,
                sucursal(IdSucursal) as Sucursal,
                (select Nombre from cat_v3_usuarios where Id = IdUsuario) as Responsable,
                estatus(IdEstatus) as Estatus,
                Grupo,
                FechaInicio,
                FechaTermino
            from t_proyectos tp 
            where Ticket = ' . $ticket);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['IdProyecto'] = $value['Id'];
                $datos['Nombre'] = $value['Nombre'];
                $datos['Tipo'] = $value['Tipo'];
                $datos['Sucursal'] = $value['Sucursal'];
                $datos['Responsable'] = $value['Responsable'];
                $datos['Estatus'] = $value['Estatus'];
                $datos['Grupo'] = $value['Grupo'];
                $datos['FechaInicio'] = $value['FechaInicio'];
                $datos['FechaTermino'] = $value['FechaTermino'];
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener las rutas de los traficos
     * 
     */

    public function getRutas(int $rutaAnterior = null) {
        $data = array();
        $condicion = (is_null($rutaAnterior)) ? '' : ' OR trl.Id = ' . $rutaAnterior;
        $consulta = $this->consulta('SELECT '
                . 'trl.Id, '
                . 'trl.Codigo, '
                . 'cv3.Nombre AS Chofer, '
                . 'trp.ApPaterno AS Paterno  '
                . 'FROM t_rutas_logistica trl '
                . 'INNER JOIN cat_v3_usuarios cv3 '
                . 'ON cv3.Id = trl.IdUsuarioAsignado '
                . 'INNER JOIN t_rh_personal trp '
                . 'ON trp.IdUsuario = cv3.Id '
                . 'WHERE (trl.FechaRuta >= CURRENT_DATE ' . $condicion . ') '
                . 'AND trl.IdEstatus IN (2,12)');
        return $consulta;
    }

    /*
     * Encargado de obtener la informacion de un trafico
     * 
     */

    public function getDatosTrafico(string $servicio) {
        $data = array();
        $trafico = $this->consulta('select * from t_traficos_generales where IdServicio = ' . $servicio);
        if (!empty($trafico)) {
            foreach ($trafico as $value) {
                $data['Clasificacion'] = $value['IdClasificacion'];
                $data['TipoTrafico'] = $value['IdTipoTrafico'];
                $data['Prioridad'] = $value['IdPrioridad'];
                $data['TipoOrigen'] = $value['IdTipoOrigen'];
                $data['Origen'] = $value['IdOrigen'];
                $data['OrigenDireccion'] = $value['OrigenDireccion'];
                $data['TipoDestino'] = $value['IdTipoDestino'];
                $data['Destino'] = $value['IdDestino'];
                $data['DestinoDireccion'] = $value['DestinoDireccion'];
            }
            $porcentaje = $this->consulta('
            select
                round(
                    (
                        (select count(*) from t_condiciones_clasificacion_traficos where IdServicio = ' . $servicio . ' and Flag = 1) /
                        (select count(*) from cat_v3_condiciones_x_clasificacion_traficos  where IdClasificacion = ' . $data['Clasificacion'] . ' and Flag = 1)
                    ) * 100
                ,2) as Porcentaje');
            if (!empty($porcentaje)) {
                foreach ($porcentaje as $valor) {
                    $data['porcentaje'] = $valor['Porcentaje'];
                }
            } else {
                $data['porcentaje'] = NULL;
            }

            $ruta = $this->consulta('select * from t_servicios_x_ruta where IdServicio = ' . $servicio . ' and Flag = 1');
            if (!empty($ruta)) {
                foreach ($ruta as $value) {
                    $data['Ruta'] = $value['IdRuta'];
                    $data['Ruta'] = $value['IdRuta'];
                }
            } else {
                $data['Ruta'] = null;
            }

            $material = $this->consulta('
                select	
                    (select Nombre from cat_v3_equipos_sae where Id = tte.IdTipoEquipo) as Tipo,
                    if (tte.IdTipoEquipo <> 4, (select concat(Clave, " - ", Nombre ) from cat_v3_equipos_sae where Id = tte.IdModelo), tte.DescripcionOtros) as Nombre,
                    tte.Serie,
                    tte.Cantidad,
                    tte.IdTipoEquipo,
                    tte.IdModelo
                from t_traficos_equipo tte
                where tte.IdServicio = ' . $servicio);
            if (!empty($material)) {
                $data['Material'] = $material;
            } else {
                $data['Material'] = null;
            }
        }
        return $data;
    }

    /*
     * Encargado de obtener la lista de Equipos, Materiales y Herramientas para un servicio de trafico
     * 
     */

    public function getMaterial() {
        $consulta = $this->consulta('select Id, concat(Clave, " - ", Nombre ) as Equipo from cat_v3_equipos_sae');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Metodo para verificar si no hay o hay un dato en la BD
     * 
     * @param string $tabla recibe nombre de la tabla 
     * @param array $datos recibe el nombre de la tabla en BD
     * @return true si no hay un dato con ese nombre, false si hay un dato con ese nombre
     */

    public function setServiciosRuta(array $datos, array $where) {
        $consulta = $this->encontrar('t_servicios_x_ruta', $where);
        if (!empty($consulta)) {
            $consulta = $this->actualizar('t_servicios_x_ruta', $datos, $where);
        } else {
            $consulta = $this->insertar('t_servicios_x_ruta', $datos);
        }
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar Equipos, Materiales y Herramientas para un servicio de trafico
     * 
     */

    public function actualizarMaterial(array $datos) {
        $consulta = $this->insertar('t_traficos_equipo', $datos);
        if (!empty($consulta)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Encargado de eliminar Equipos, Materiales y Herramientas para un servicio de trafico
     * 
     */

    public function eliminarMaterial(array $where) {
        $consulta = $this->eliminar('t_traficos_equipo', $where);
        if (!empty($consulta)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Encargado de obtener el chofer y la persona que creo la ruta de pendiendo el servicio
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function datosRuta(string $IdServicio) {
        $consulta = $this->consulta('SELECT trl.IdUsuarioAsignado AS Chofer, trl.IdCreador AS Creador FROM t_traficos_generales ttg INNER JOIN t_servicios_x_ruta ttxr ON ttxr.IdServicio = ttg.IdServicio INNER JOIN t_rutas_logistica trl ON trl.Id = ttxr.IdRuta WHERE ttg.IdServicio = ' . $IdServicio);
        return $consulta;
    }

    /*
     * Metodo que obtiene los datos del chofer que va atender el servicio
     * $usuario = id de usuario
     * 
     */

    public function getDatosChofer(string $usuario) {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_usuarios', array('Id' => $usuario));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['IdUsuario'] = $usuario;
                $datos['Nombre'] = $value['Nombre'];
                $datos['Perfil'] = $value['IdPerfil'];
                $datos['EmailCorporativo'] = $value['EmailCorporativo'];
            }
            $perfil = $this->consulta('
                SELECT 
                cp.*,
                cvds.IdArea 
                FROM cat_perfiles cp INNER JOIN cat_v3_departamentos_siccob cvds 
                ON cp.IdDepartamento = cvds.Id 
                WHERE cp.Id = ' . $datos['Perfil']
            );

            foreach ($perfil as $value) {
                $datos['Perfil'] = $value['Nombre'];
                $datos['IdDepartamento'] = $value['IdDepartamento'];
            }
            return $datos;
        }
    }

    /*
     * Encargado de obtener lo datos del Envio
     * 
     */

    public function getDatosEnvio(string $servicio) {
        $data = array();
        $consulta = $this->consulta('select * from t_traficos_envios where IdServicio = ' . $servicio);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $data['TipoEnvio'] = $value['IdTipoEnvio'];
                $data['Paqueteria'] = $value['IdPaqueteria'];
                $data['FechaEnvio'] = $value['FechaEnvio'];
                $data['Guia'] = $value['Guia'];
                $data['ComentariosEnvio'] = $value['ComentariosEnvio'];
                $data['EvidenciasEnvio'] = explode(',', $value['UrlEnvio']);
                $data['FechaEntrega'] = $value['FechaEntrega'];
                $data['NombreRecibe'] = $value['NombreRecibe'];
                $data['ComentariosEntrega'] = $value['ComentariosEntrega'];
                $data['EvidenciasEntrega'] = explode(',', $value['UrlEntrega']);
            }
        }
        return $data;
    }

    /*
     * Encargado de obtener lo datos de la recoleccion
     * 
     */

    public function getDatosRecoleccion(string $servicio) {
        $data = array();
        $consulta = $this->consulta('select * from t_traficos_recolecciones where IdServicio = ' . $servicio);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $data['Fecha'] = $value['Fecha'];
                $data['Entrega'] = $value['NombreEntrega'];
                $data['Comentarios'] = $value['ComentariosRecoleccion'];
                $data['EvidenciasRecoleccion'] = explode(',', $value['UrlRecoleccion']);
            }
        } else {
            $data = null;
        }
        return $data;
    }

    /*
     * Encargado de insertar un elemento en la base de datos
     * 
     */

    public function setNuevoElemento(string $tabla, array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar($tabla, $datos);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener la lista de los equipos que tienen definido un destino para 
     * la recoleccion y distribución.
     * 
     */

    public function obtenerListaEnviosDistribucion(string $servicio) {
        $consulta = $this->consulta('
            select 
                ttd.Id,
                (select Nombre from cat_v3_tipos_origen_destino where Id = ttd.IdTipoDestino) as TipoDestino,
                estatus(ttd.Idestatus) as Estatus,
                case ttd.IdTipoDestino
                    when 1
			then sucursal(ttd.IdDestino)
                    when 2
                        then proveedor(ttd.IdDestino)
                    when 3 
                        then ttd.DestinoDireccion
                end as NombreDestino
            from t_traficos_distribuciones ttd where ttd.IdServicio = ' . $servicio . ' and ttd.IdEstatus <> 6');
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return array();
        }
    }

    /*
     * Obteniendo lista de equipos para distribuciones
     * 
     */

    public function obtenerEquiposParaDistribuciones(string $servicio) {
        $data = array();

        $equipos = $this->consulta('
            select 
                tted.IdDestino,
                tted.IdTipoEquipo,
                tted.IdModelo,
                tted.DescripcionOtros,
                tted.Serie,
                tted.Cantidad
            from t_traficos_equipo_x_destino tted 
            inner join t_traficos_distribuciones ttd 
            on tted.IdDestino = ttd.Id
            where ttd.IdServicio = ' . $servicio);

        if (!empty($equipos)) {
            foreach ($equipos as $value) {
                array_push($data, array(
                    'Destino' => $value['IdDestino'],
                    'TipoEquipo' => $value['IdTipoEquipo'],
                    'Modelo' => $value['IdModelo'],
                    'Otros' => $value['DescripcionOtros'],
                    'Serie' => $value['Serie'],
                    'Cantidad' => $value['Cantidad']
                ));
            }
        }

        return $data;
    }

    /*
     * Encargado de obtener la lista que faltan por definir un trafico de un distribución
     */

    public function obtenerEquiposFaltantesDistribuciones(string $servicio) {

        $datos = array();
        $consulta = parent::connectDBPrueba()->query('call getDiferenciasLogisticaDistribucion(' . $servicio . ')');
        mysqli_next_result(parent::connectDBPrueba()->conn_id);
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $material) {
                array_push($datos, $material);
            }
        }
        return $datos;
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaGeneral(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    public function getGeneralesSinClasificar(string $servicio) {
        $sentencia = ""
                . "select "
                . "Descripcion, "
                . "Archivos, "
                . "Fecha "
                . "from t_servicios_generales "
                . "where IdServicio = '" . $servicio . "'";
        return $this->consultaGeneral($sentencia);
    }

    public function concluirTicketAdist2(array $datos) {
        $query = "UPDATE t_servicios "
                . "SET Estatus='" . $datos['Estatus'] . "', "
                . "Flag='" . $datos['Flag'] . "', "
                . "F_Cierre='" . $datos['F_Cierre'] . "' "
                . "WHERE Id_Orden = '" . $datos['Id_Orden'] . "'";
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            return parent::connectDBAdist2()->insert_id();
        } else {
            return parent::connectDBAdist3()->insert_id();
        }
    }

    public function Guarda_SinClasificar(array $datos) {
        $sentencia = "select count(*) as Servicios from t_servicios_generales where IdServicio = '" . $datos['servicio'] . "'";
        $servicios = $this->consultaGeneral($sentencia);
        $fecha = date('Y-m-d H:i:s');
        if ($servicios[0]['Servicios'] <= 0) {
            $data = array(
                'IdUsuario' => $datos['Id'],
                'IdServicio' => $datos['servicio'],
                'Descripcion' => $datos['descripcion'],
                'Archivos' => '',
                'Fecha' => $fecha
            );
            $resultado = $this->insertar('t_servicios_generales', $data);
        } else {
            $data = array(
                'IdUsuario' => $datos['Id'],
                'Descripcion' => $datos['descripcion'],
                'Archivos' => '',
                'Fecha' => $fecha
            );
            $where = array(
                'IdServicio' => $datos['servicio']
            );
            $resultado = $this->actualizar('t_servicios_generales', $data, $where);
        }
        return $resultado;
    }

    public function setServicioId(string $tabla, array $datos) {
        if (empty($consulta)) {
            $consulta = $this->insertar($tabla, $datos);
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    public function consultaQuery(string $sentencia) {
        $consulta = parent::connectDBPrueba()->query($sentencia);
        return $consulta;
    }

    public function consultaQueryAdist2(string $sentencia) {
        $consulta = parent::connectDBAdist2()->query($sentencia);
        return $consulta->result_array();
    }

    public function consultaFolio(string $servicio) {
        $consulta = $this->consulta('SELECT 
                                        ts.Folio
                                    FROM t_servicios_ticket tst
                                    INNER JOIN t_solicitudes ts
                                    ON tst.IdSolicitud = ts.Id
                                    WHERE tst.Id =  "' . $servicio . '"');

        if (!empty($consulta)) {
            return $consulta[0]['Folio'];
        } else {
            return FALSE;
        }
    }

    public function totalAreaPuntos(array $datos) {
        $consulta = $this->consulta('select 
                                        Area, count(*) as Puntos
                                    from
                                        (select 
                                            areaAtencion(IdArea) as Area, Punto
                                        from
                                            t_censos
                                        where
                                            IdServicio = (select 
                                                    MAX(Id)
                                                from
                                                    t_servicios_ticket
                                                where
                                                    IdSucursal = (select 
                                                            IdSucursal
                                                        from
                                                            t_servicios_ticket
                                                        where
                                                            Id = "' . $datos['servicio'] . '")
                                                        and IdTipoServicio = 11
                                                        and IdEstatus = 4)
                                        group by IdArea , Punto) as tf
                                    group by Area');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function totalLineasCenso(array $datos) {
        $consulta = $this->consulta('select 
                                        cap_first(strSplit(modelo(IdModelo), " - ", 1)) as Linea,
                                        count(*) as Total
                                    from
                                        t_censos
                                    where
                                        IdServicio = (select 
                                                MAX(Id)
                                            from
                                                t_servicios_ticket
                                            where
                                                IdSucursal = (select 
                                                        IdSucursal
                                                    from
                                                        t_servicios_ticket
                                                    where
                                                        Id = "' . $datos['servicio'] . '")
                                                    and IdTipoServicio = 11
                                                    and IdEstatus = 4)
                                    group by Linea');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function flagearServicioAvance(array $datos) {
        $campos = array('Flag' => '0');
        $resultado = $this->actualizar('t_servicios_avance', $campos, array('Id' => $datos['idAvanceProblema']));

        if (!empty($resultado)) {
            return TRUE;
        } else {
            throw new \Exception('Error con la Base de Datos.');
        }
    }

    public function flagearServicioAvanceEquipo(array $datos) {
        $campos = array('Flag' => '0');
        $resultado = $this->actualizar('t_servicios_avance_equipo', $campos, array('IdAvance' => $datos['idAvanceProblema']));

        if (!empty($resultado)) {
            return TRUE;
        } else {
            throw new \Exception('Error con la Base de Datos.');
        }
    }

    public function serviciosAvanceEquipo(string $idAvance) {
        $consulta = $this->consulta("select
                                    tsae.IdItem,
                                    tsae.Serie,
                                    tsae.IdAvance,
                                    tsae.Cantidad,
                                    CASE tsae.IdItem
                                            when 1 then 'Equipo'
                                            when 2 then 'Material'
                                            when 3 then 'Refacción'
                                            when 4 then 'Elemento'
                                            when 5 then 'Sub-Elemento'
                                    end as Tipo,
                                    CASE tsae.IdItem 
                                        WHEN 1 THEN (SELECT Equipo FROM v_equipos WHERE Id = tsae.TipoItem) 
                                        WHEN 2 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = tsae.TipoItem)
                                        WHEN 3 THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tsae.TipoItem) 
                                        WHEN 4 THEN (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = tsae.TipoItem) 
                                        WHEN 5 THEN (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = tsae.TipoItem) 
                                    end as EquipoMaterial,
                                    (SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = tsae.IdTipoDiagnostico) TipoDiagnostico,
                                    tsae.TipoItem
                                    from t_servicios_avance_equipo tsae
                                    where IdAvance = '" . $idAvance . "'
                                    AND Flag = '1'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function servicioAvanceProblema(string $servicio) {
        $consulta = $this->consulta('SELECT tsa.*,
                                                (SELECT Nombre FROM cat_v3_tipos_avance WHERE Id = tsa.IdTipo) AS TipoAvance,
                                                (SELECT UrlFoto FROM t_rh_personal WHERE Id = tsa.IdUsuario) AS Foto,
                                                nombreUsuario(IdUSuario) AS Usuario
                                                FROM t_servicios_avance tsa
                                                WHERE IdServicio = "' . $servicio . '"
                                                AND Flag = "1"
                                                ORDER BY Fecha ASC');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaAvanceProblema(string $id) {
        $consulta = $this->consulta('SELECT tsa.*,
                                                (SELECT Nombre FROM cat_v3_tipos_avance WHERE Id = tsa.IdTipo) AS TipoAvance,
                                                (SELECT UrlFoto FROM t_rh_personal WHERE Id = tsa.IdUsuario) AS Foto,
                                                nombreUsuario(IdUSuario) AS Usuario
                                                FROM t_servicios_avance tsa
                                                WHERE Id = "' . $id . '"
                                                AND Flag = "1"
                                                ORDER BY Fecha ASC');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function actualizarAvanceProblema(array $datos) {
        $resultado = $this->actualizar('t_servicios_avance', $datos['campos'], $datos['where']);

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function atiendeServicio(string $servicio) {
        $consulta = $this->consulta('SELECT 
                                        tst.Atiende
                                        FROM t_servicios_ticket tst
                                        WHERE tst.Id = "' . $servicio . '"');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaServicio(string $servicio) {
        $consulta = $this->consulta('SELECT 
                                            ts.Folio,
                                            tst.Id,
                                            tst.Ticket,
                                            tst.IdTipoServicio,
                                            (SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = tst.IdTipoServicio) Seguimiento,
                                            tst.IdEstatus,
                                            tst.FechaConclusion,
                                            (SELECT Atiende FROM t_solicitudes WHERE Id = tst.IdSolicitud) Atiende,
                                            tst.IdSucursal,
                                            sucursal(tst.IdSucursal) Sucursal,
                                            tst.IdSolicitud,
                                            tst.Atiende AS AtiendeServicio
                                        FROM t_servicios_ticket tst
                                        INNER JOIN t_solicitudes ts
                                            ON ts.Id = tst.IdSolicitud
                                        WHERE tst.Id = "' . $servicio . '"');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaServicioLaboratorio(string $servicio) {
        $consulta = $this->consulta('SELECT
                                            (SELECT IdDepartamento FROM cat_perfiles WHERE Id = cvu.IdPerfil) IdDepartamento
                                        FROM t_servicios_ticket tst
                                        INNER JOIN cat_v3_usuarios cvu
                                        ON tst.Atiende = cvu.Id
                                        WHERE tst.Id = "' . $servicio . '"');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function insertarHitoricoServiciosAvance(array $datos) {
        $resultado = $this->insertar('historico_servicios_avance', $datos);

        if (!empty($resultado)) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function getTipoByServicio(string $servicio) {
        $consulta = $this->consulta("select IdTipoServicio from t_servicios_ticket where Id = '" . $servicio . "'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function verificarServiciosDepartamento(string $tipoServicio) {
        $consulta = $this->consulta('SELECT Seguimiento FROM cat_v3_servicios_departamento WHERE Id = "' . $tipoServicio . '"');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaDocumentacioFirmadaServicio(string $servicio, string $limite = null) {
        ($limite !== null) ? $limitarServicio = 'ORDER BY Id DESC LIMIT 1' : $limitarServicio = '';
        $consulta = $this->consulta('SELECT 
                                                        Fecha,
                                                        Recibe,
                                                        Correos,
                                                        (SELECT Nombre FROM cat_v3_estatus WHERE Id = tsdf.IdEstatus) Estatus,
                                                        UrlArchivo,
                                                        Firma
                                                    FROM t_servicios_documentacion_firmada tsdf
                                                    WHERE tsdf.IdServicio =  "' . $servicio . '"'
                . $limitarServicio);

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function catalogoSubcategoriaSD() {
        $consulta = $this->consulta('SELECT * FROM cat_v3_sd_subcategoria');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function catalogoItemSD() {
        $consulta = $this->consulta('SELECT * FROM cat_v3_sd_item');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }
    
    public function getServiciosSolicitud(string $ticket) {
        $consulta = $this->consulta('SELECT * FROM t_servicios_ticket WHERE Ticket = "' . $ticket . '"');

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function getInstalaciones(string $idServicio) {
        $consulta = $this->consulta("SELECT tie.*,
                                        (SELECT Nombre FROM cat_v3_tipos_operaciones WHERE Id = tie.IdOperacion) AS Operacion,
                                        areaAtencion(tie.IdArea)  AS Area,
                                        modelo(tie.IdModelo)  AS Modelo
                                         FROM t_instalaciones_equipos_poliza tie
                                         WHERE IdServicio = '" . $idServicio . "'");


        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

}
