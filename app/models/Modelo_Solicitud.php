<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Solicitud
 *
 * @author Freddy
 */
class Modelo_Solicitud extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de generar una consulta para obtener las solicitudes
     * 
     * @param string $consulta Resive la consulta que se quiere generar
     * @return array Regresa el resultado de la consulta como un arreglo
     */

    public function getSolicitudes(string $consulta) {
        $resultado = $this->consulta($consulta);
        return $resultado;
    }

    /*
     * Encargado de insertar una nueva solicitud
     * 
     * @param array $datos Recibe un arreglo con los datos tipo, departemento y idusuario para insertar el registro de una nueva solicitd
     * @return string Regresa el id de la fila que se inserto en la tabla
     */

    public function setSolicitud(string $datos) {
        $consulta = parent::connectDBPrueba()->query($datos);
        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado en guardar los detalles de la solicitud
     * 
     * @param string $table Recibe el nombre de la tabla donde se guarda la informacion
     * @param array $datos Recibe los datos que se van a ingresar en el registro nuevo.
     * @return array Regresa un arreglo con la informacion de la insercion en la tabla
     */

    public function setDatosSolicitudInternas(string $table, array $datos) {
        $consulta = $this->insertar($table, $datos);
        return $consulta;
    }

    /*
     * Encargado de actualizar datos de la solicitud
     * 
     * @param string $tabla Obtiene el nombre de la tabla que se va actualizar
     * @param array $datos Obtiene un arreglo de los datos que se actualizan
     * @param array $where Obtiene la condicion para actualización.
     * @return array Regresa un arreglo de el resultado de la actualización.
     */

    public function actualizarSolicitud(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        return $consulta;
    }

    /*
     * Encargado de guardar el historico de cambio de area de una solicitud
     * 
     * @param array Recibe un arreglo con los datos del registro
     * @return array Regresa un arreglo con el resultado de la insercion.
     * 
     */

    public function setHistoricoSolicitud(array $datos) {
        $consulta = $this->insertar('historico_solicitudes', $datos);
        return $consulta;
    }

    /*
     * Encargada de obtener la informacion de la solicitud de cualquier tipo en la base de datos
     * 
     * @param string Recibe le numero de la solicitud que quiere sus datos
     * @return array Regresa un arreglo con los datos de la solicitud.
     */

    public function getDatosSolicitud(string $solicitud) {
        $datos = array();
        $consulta = $this->consulta('
            select
                ts.Ticket,                
                ts.IdDepartamento,
                ts.IdTipoSolicitud as Tipo,
                ts.IdPrioridad,
                (select Nombre from cat_v3_departamentos_siccob where Id = (select IdDepartamento from cat_perfiles where Id = cvu.IdPerfil)) as Departamento,
                ts.Folio,
                ts.FechaCreacion as Fecha,
                (select Nombre from cat_v3_usuarios where Id = ts.Autoriza) as Autoriza,
                ts.Solicita,
                 usuario(ts.Solicita) as NombreSolicita,
                ts.IdEstatus,
                ts.IdSucursal,
                (SELECT IdCliente FROM cat_v3_sucursales WHERE Id = ts.IdSucursal) IdCliente,
                ts.FechaTentativa,
                ts.FechaLimite
            from t_solicitudes as ts 
            inner join cat_v3_usuarios as cvu 
            on ts.Solicita = cvu.Id
            where ts.Id = ' . $solicitud);
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['IdDepartamento'] = $value['IdDepartamento'];
                $datos['TipoSolicitud'] = $value['Tipo'];
                $datos['DepartamentoSolicitante'] = $value['Departamento'];
                $datos['IdPrioridad'] = $value['IdPrioridad'];
                $datos['Folio'] = $value['Folio'];
                $datos['Fecha'] = $value['Fecha'];
                $datos['Ticket'] = $value['Ticket'];
                $datos['Autoriza'] = $value['Autoriza'];
                $datos['Solicita'] = $value['Solicita'];
                $datos['NombreSolicita'] = $value['NombreSolicita'];
                $datos['IdEstatus'] = $value['IdEstatus'];
                $datos['IdSucursal'] = $value['IdSucursal'];
                $datos['IdCliente'] = $value['IdCliente'];
                $datos['FechaTentativa'] = $value['FechaTentativa'];
                $datos['FechaLimite'] = $value['FechaLimite'];
                $datos['detalles'] = $this->getInformacionDetalladaSolicitud($solicitud, $datos['TipoSolicitud']);
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de obtener los datos detallados de la solicitud dependiendo del tipo de solicitud
     * 
     * @param string Recibe el numero de la solicitud.
     * @param string Recibe el tipo de solicitud.
     * @return array Regresa un arreglo con los detalles del tipo de solicitud 
     */

    private function getInformacionDetalladaSolicitud(string $solicitud, string $tipo) {
        $resultado = NULL;
        switch ($tipo) {
            case '1':
                $resultado = $this->consulta('
                    select 
                        tpp.IdProyecto,
                        tpp.DescripcionPerfil,
                        tp.Nombre as NombreProyecto
                    from t_personal_proyecto tpp inner join t_proyectos tp
                    on tpp.IdProyecto = tp.Id 
                    where tpp.IdSolicitud = ' . $solicitud);
                break;
            case '2':
                $resultado = $this->consulta('
                        select
                            m.IdSolicitud,
                            m.IdMaterial,
                            m.IdProyecto,
                            (select Nombre from t_proyectos where Id = m.IdProyecto) as NombreProyecto,
                            (select Ticket from t_proyectos where Id = m.IdProyecto) as Ticket,
                            mq.Nombre, 
                            mq.NoParte, 
                            m.Cantidad,
                            m.IdEstatus,
                            estatus(m.IdEstatus) as Estatus,
                            (select max(IdVersion) as version from historico_material_proyecto where IdSolicitud = ' . $solicitud . ' ) as Version
                        from t_material_proyecto m inner join cat_v3_modelos_equipo mq on m.IdMaterial = mq.Id
                        where m.IdSolicitud = ' . $solicitud);
                break;
            case '3':
                $resultado = $this->encontrar('t_solicitudes_internas', array('IdSolicitud' => $solicitud));
                break;
            case '4':
                $resultado = $this->encontrar('t_solicitudes_internas', array('IdSolicitud' => $solicitud));
            case '6':
                $resultado = $this->encontrar('t_solicitudes_internas', array('IdSolicitud' => $solicitud));
                break;
        }

        return $resultado;
    }

    /*
     * Encargado de obtener los datos del solicitante de la solicitud
     * 
     * @param string $usuario Recibe el id del usuario que se requiere los datos
     * @return array Regresa los datos del usuario
     */

    public function getDatosSolicitante(string $usuario) {
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
     * Metodo encargado de establecer las notas de la solicitud
     * 
     * @param array $datos Recibe los datos que se ingresar en el registro.
     * @return string Regresa el id del registro que se realiso de lo contrario manda false.
     * 
     */

    public function setNotasSolicitud(array $datos) {
        $consulta = $this->insertar('t_notas_solicitudes', $datos);

        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de obtener las notas de la solicitud.
     * 
     * @param string $solicitud Recibe el numero de solicitud
     * @return array Regresa un arreglo con los datos de la consulta
     */

    public function getNotasSolicitud(string $solicitud) {
        $consulta = $this->consulta("
                                    SELECT * FROM (SELECT 
                                        tns.Id,
                                        (select Nombre from cat_v3_estatus where Id = tns.IdEstatus) as Estatus,
                                        nombreUsuario(tns.IdUsuario) as Nombre, 
                                        (select if(UrlFoto is null, '/assets/img/user-5.jpg', UrlFoto) from t_rh_personal where IdUsuario = tns.IdUsuario)  as Foto,
                                        tns.Nota,
                                        tns.Fecha 
                                    FROM t_notas_servicio tns where tns.IdEstatus in(10,13,6)
                                    and tns.IdServicio IN(SELECT Id FROM t_servicios_ticket WHERE IdSolicitud = '" . $solicitud . "')
                                    UNION
                                    SELECT 
                                        tnso.Id,
                                        (select Nombre from cat_v3_estatus where Id = tnso.IdEstatus) as Estatus,
                                        nombreUsuario(tnso.IdUsuario) as Nombre, 
                                        (select if(UrlFoto is null, '/assets/img/user-5.jpg', UrlFoto) from t_rh_personal where IdUsuario = tnso.IdUsuario)  as Foto,
                                        tnso.Nota,
                                        tnso.Fecha
                                    FROM t_notas_solicitudes tnso 
                                    WHERE tnso.IdSolicitud = '" . $solicitud . "') AS TABLAS 
                                    ORDER BY TABLAS.Fecha DESC");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de eliminar los registro del material de una solicitud de proyecto
     * 
     * @param array $datos Recibe el idSolicitud para eliminar los registros.
     * @return boolean Regresa true si se realizo con exito la eliminacion de lo cantrario un false.
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
     * Acutalizando la tabla de material de una solicitud de proyecto
     * 
     * @param array $datos Recibe los datos que se insertar en la tabla
     * @param array $historico Recibe los datos que se insertan en el historico de material.
     */

    public function actualizarSolicitudMaterial(array $datos, array $historico) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar('t_material_proyecto', $datos);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        if (!empty($consulta)) {
            $respuesta = $this->insertar('historico_material_proyecto', $historico);
            return $respuesta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de obtener los folios en la base de datos de adist2
     * 
     */

    public function getFoliosAdist2(string $folios) {
        $data = array();
        $host = $_SERVER['HTTP_HOST'];
        if ($host === 'siccob.solutions') {
            $consulta = parent::connectDBAdist2()->query('select Folio_Cliente from t_servicios where Folio_Cliente in (' . $folios . ')');
        } else {
            $consulta = parent::connectDBAdist3()->query('select Folio_Cliente from t_servicios where Folio_Cliente in (' . $folios . ')');
        }
        foreach ($consulta->result_array() as $value) {
            array_push($data, $value['Folio_Cliente']);
        }
        return $data;
    }

    /*
     * Encargado de obtener las solicitudes que vienen de Service Desk para actualizar en el sistema adist
     * 
     */

    public function getSoliditudesRechadasSD(string $operacion, string $folios) {
        $data = array();
        switch ($operacion) {
            case '1':
                //Obtiente todos las solicitudes que fueron rechadaz y se reasignaron al tecnico
                $consulta = $this->consulta('select * from t_solicitudes where Folio in (' . $folios . ') and IdEstatus = 10');
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($data, $value['Folio']);
                    }
                }
                break;
            case '2':
                //Actualiza estatus todos las solicitudes que fueron rechadaz y se reasignaron al tecnico
                $consulta = parent::connectDBPrueba()->query('update t_solicitudes set IdEstatus = 1 where Folio in (' . $folios . ')');
                if (!empty($consulta)) {
                    $data = true;
                } else {
                    $data = false;
                }
                break;
            case '3':
                //Obtiene todas la solicitudes coincidentes con folios nuevos de Service Desk
                $consulta = $this->consulta('select * from t_solicitudes where Folio in (' . $folios . ')');
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($data, $value['Folio']);
                    }
                }
                break;
            case '4':
                //Obtiente todos las solicitudes que fueron rechadaz y se reasignaron al tecnico
                $consulta = $this->consulta('select * from t_solicitudes where Folio in (' . $folios . ') and IdEstatus = 1');
                if (!empty($consulta)) {
                    foreach ($consulta as $value) {
                        array_push($data, array('folio' => $value['Folio'], 'solicitud' => $value['Id']));
                    }
                }
                break;
        }
        return $data;
    }

    /*
     * Encargado de obtener la APIKey de la mesa
     * 
     */

    public function getApiKeyMesaAyuda() {
        $key = NULL;
        $consulta = $this->consulta('select SDKey from cat_v3_usuarios where IdPerfil = 66 and SDKey is not null and SDKey <> "" limit 1');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $key = $value['SDKey'];
            }
        }
        return $key;
    }

    public function consultaGral(string $consulta) {
        $resultado = $this->consulta($consulta);
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function eliminarSolicitud(string $tabla, array $datos) {
        $consulta = $this->eliminar($tabla, $datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getFolioSolicitudesAbiertas() {
        $consulta = $this->consulta("SELECT
                                        Id,
                                        Folio,
                                        Ticket
                                    FROM
                                        t_solicitudes
                                    WHERE
                                         Folio IS NOT NULL
                                    AND Folio != '0'
                                    AND IdEstatus = '1'
                                    LIMIT 0, 100");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function cambiarEstatusSolicitud(array $datos, array $where) {
        $consulta = $this->actualizar('t_solicitudes', $datos, $where);
        return $consulta;
    }

    public function obtenerFolios($folio) {
        $consulta = $this->consulta("SELECT Id, Ticket, Folio, FechaCreacion, estatus(IdEstatus) AS Estado
			FROM t_solicitudes 
			WHERE Folio ='" . $folio . "'");
        return $consulta;
    }

    public function getFoliosCreacionInicio() {
        $consulta = $this->consulta("SELECT * FROM (SELECT 
                                        T.FechaInicio, ts.FechaCreacion, ts.Folio
                                    FROM
                                        (SELECT 
                                            FechaInicio, IdSolicitud
                                        FROM
                                            (SELECT 
                                            tst.FechaInicio, tst.IdSolicitud
                                        FROM
                                            t_servicios_ticket tst
                                        WHERE
                                            tst.FechaInicio IS NOT NULL
                                        ORDER BY tst.IdSolicitud , FechaInicio ASC) AS Tabla
                                        GROUP BY IdSolicitud) AS T
                                            INNER JOIN
                                        t_solicitudes ts ON T.IdSolicitud = ts.Id
                                    WHERE
                                        ts.Folio IS NOT NULL AND ts.Folio <> 0
                                            AND ts.FechaCreacion BETWEEN '2020-01-01 00:00:00' AND NOW()
                                            ORDER BY ts.Folio, FechaCreacion ASC) AS TABLAFINAL
                                            GROUP BY Folio");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function sla() {
        $consulta = $this->consulta("SELECT * FROM (SELECT 
                                        TIME_TO_SEC(TIME(tct.TiempoTranscurrido)) AS SegundosTiempoTranscurrido,
                                        tct.TiempoTranscurrido,
                                        ts.Id AS IdSolicitud,
                                        T.FechaInicio, ts.FechaCreacion, ts.Folio, T.Tecnico, ts.IdPrioridad, IF(ts.IdSucursal = 0, sucursal(T.IdSucursalServicio), sucursal(ts.IdSucursal )) AS Sucursal,
                                        IF(ts.IdSucursal = 0, T.IdSucursalServicio, ts.IdSucursal ) AS IdSucursal
                                    FROM
                                        (SELECT 
                                            FechaInicio, IdSolicitud, Tecnico, IdSucursalServicio
                                        FROM
                                            (SELECT 
                                            tst.FechaInicio, tst.IdSolicitud, nombreUsuario(tst.Atiende) AS Tecnico, tst.IdSucursal AS IdSucursalServicio
                                        FROM
                                            t_servicios_ticket tst
                                        WHERE
                                            tst.FechaInicio IS NOT NULL
                                        ORDER BY tst.IdSolicitud , FechaInicio ASC) AS Tabla
                                        GROUP BY IdSolicitud) AS T
                                            INNER JOIN
                                        t_solicitudes ts ON T.IdSolicitud = ts.Id
                                        INNER JOIN t_cheking_ticket tct
                                        ON tct.Folio = ts.Folio
                                    WHERE
                                        ts.Folio IS NOT NULL AND ts.Folio <> 0
                                        AND ts.FechaCreacion BETWEEN '2020-01-01 00:00:00' AND NOW()
                                            ORDER BY ts.Folio, FechaCreacion ASC) AS TABLAFINAL
                                            GROUP BY Folio;");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function getCheking_Ticket(string $folio) {
        $consulta = $this->consulta("SELECT * FROM t_cheking_ticket WHERE Folio = " . $folio);

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return '';
        }
    }

    public function setCheking_Ticket(array $datos) {
        $this->insertar("t_cheking_ticket", $datos);
    }

}
