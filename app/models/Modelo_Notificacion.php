<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Notificacion
 *
 * @author Freddy
 */
class Modelo_Notificacion extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Obteneiendo las notificaciones del usuario.
     * 
     */

    public function getNotificaciones(string $Id) {
        $datos = array();
        return $this->consulta('
            select 
                tn.Id, 
                (select Nombre from cat_v3_usuarios where Id = tn.Remitente ) as Remitente,
                ctds.Nombre as Departamento, 
                ctn.Nombre as Tipo, 
                tn.Descripcion, 
                tn.Fecha, 
                tn.Flag,
                ctn.Url
            from t_notificaciones tn inner join cat_v3_departamentos_siccob ctds
            on tn.IdDepartamento = ctds.Id 
            inner join cat_tipos_notificacion ctn on tn.IdTipo = ctn.Id 
            where tn.Destinatario = ' . $Id . ' order by tn.Id desc'
        );
    }

    /*
     * Se encarga de regresar el total de notificaciones que estan sin ser revisadas
     */

    public function getCantidadNotificaciones(string $Id) {
        $datos = array();
        $consulta = $this->consulta('
            select 
                tn.Id, 
                ctds.Nombre as Departamento, 
                ctn.Nombre as Tipo, 
                tn.Fecha
                from t_notificaciones tn inner join cat_v3_departamentos_siccob ctds
                on tn.IdDepartamento = ctds.Id 
                inner join cat_tipos_notificacion ctn on tn.IdTipo = ctn.Id 
                where tn.Destinatario = ' . $Id . '  and tn.Flag <> 0 order by tn.Id desc;
                ');

        array_push($consulta, array('cantidad' => $this->getCantidad()));
        return $consulta;
    }

    public function showSupervisorAndBranchTechnician(string $idSolicitud) {
        return $this->consulta('(SELECT 
                                    cvrc.IdResponsableInterno AS IdUsuario,
                                    nombreUsuario(cvu.Id) AS Nombre,
                                    cvu.Usuario,
                                    cp.Nombre as Perfil,
                                    cvu.EmailCorporativo,
                                    cvu.SDKey,
                                    cp.IdDepartamento
                                FROM
                                    t_solicitudes ts
                                        INNER JOIN
                                    cat_v3_sucursales cvs ON cvs.Id = ts.IdSucursal
                                        INNER JOIN
                                    cat_v3_regiones_cliente cvrc ON cvrc.Id = cvs.IdRegionCliente
                                        INNER JOIN
                                    cat_v3_usuarios cvu ON cvu.Id = cvrc.IdResponsableInterno
                                        INNER JOIN
                                    cat_perfiles cp ON cp.Id = cvu.IdPerfil
                                WHERE
                                    ts.Id = "' . $idSolicitud . '")
                                UNION
                                (SELECT 
                                    cvs.IdResponsable AS IdUsuario,
                                    nombreUsuario(cvu.Id) AS Nombre,
                                    cvu.Usuario,
                                    cp.Nombre as Perfil,
                                    cvu.EmailCorporativo,
                                    cvu.SDKey,
                                    cp.IdDepartamento
                                FROM
                                    t_solicitudes ts
                                        INNER JOIN
                                    cat_v3_sucursales cvs ON cvs.Id = ts.IdSucursal
                                        INNER JOIN
                                    cat_v3_usuarios cvu ON cvu.Id = cvs.IdResponsable
                                        INNER JOIN
                                    cat_perfiles cp ON cp.Id = cvu.IdPerfil
                                WHERE
                                    ts.Id = "' . $idSolicitud . '")'
        );
    }

    public function showSupervisorsPolicyCoordinator() {
        return $this->consulta('SELECT 
                                    cvu.Id as IdUsuario,
                                    nombreUsuario(cvu.Id) AS Nombre,
                                    cvu.Usuario,
                                    cp.Nombre as Perfil, 
                                    cvu.EmailCorporativo, 
                                    cvu.SDKey, 
                                    cp.IdDepartamento 
                                FROM cat_v3_usuarios cvu 
                                INNER JOIN cat_perfiles cp ON cp.Id = cvu.IdPerfil 
                                WHERE cp.IdDepartamento = 11
                                AND cp.Nivel IN(3,4)
                                AND cvu.flag = 1'
        );
    }

    public function showIdSucursalTableSolicitudes(string $idSolicitud) {
        return $this->consulta('SELECT 
                                        IdSucursal
                                    FROM
                                        t_solicitudes
                                    WHERE
                                        Id = "' . $idSolicitud . '"'
        );
    }

    /*
     * Se encarga de actualizar la notificacion
     * 
     */

    public function actualizarNotificacion(array $datos, array $where) {
        $consulta = $this->actualizar('t_notificaciones', $datos, $where);
        return $consulta;
    }

    /*
     * Se encarga de generar una nueva notificacion
     * 
     */

    public function setNotificacion(array $datos) {
        $consulta = $this->insertar('t_notificaciones', $datos);
        return parent::connectDBPrueba()->insert_id();
    }

    public function consultaNotificacion(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

}
