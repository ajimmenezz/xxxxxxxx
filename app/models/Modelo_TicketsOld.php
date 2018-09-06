<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Solicitud
 *
 * @author Freddy
 */
class Modelo_TicketsOld extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /* Encargado de obtener el tipo de Servicio desde un Ticket */

    public function getTipoByTicket(string $ticket = null) {
        $sentencia = "select Tipo from t_servicios where Id_Orden = '" . $ticket . "'";
        $consulta = $this->consultaAD2($sentencia);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de insertar una nueva minuta
     *  $datos = datos para insertar en la tabla t_minutas
     */

    public function setMinuta(array $datos) {
        $consulta = $this->insertar('t_minutas', $datos);
        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar la tabla t_minuta
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function insertarArchivos(array $datos, array $where) {
        $consulta = $this->actualizar('t_minutas', $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Metodo que obtiene los datos de un usuario que va atender el servicio
     * $usuario = id de usuario
     * 
     */

    public function getDatosUsuario(string $usuario) {
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
     * Encargado de mandar los datos para visializar tabla especifica
     * $IdUsuario = id del usuario para la consulta
     * @return array regresa todos los datos dependeiendo el IdUsuario
     */

    public function getMinutas(string $IdUsuario) {
        $consulta = $this->consulta("select *, usuario(IdUsuario) AS Usuario from t_minutas where Concat(',',Miembros,',') REGEXP('," . $IdUsuario . ",') or IdUsuario = " . $IdUsuario);
        return $consulta;
    }

    /*
     * Encargado de mandar los datos para visializar tabla 
     * 
     * @return array regresa todos los datos
     */

    public function getArchivosMinutas(string $Id) {
        $consulta = $this->consulta('SELECT a.Id, b.Nombre AS Miembro, a.Nombre, a.Fecha, a.Archivo FROM t_archivos_minuta a INNER JOIN cat_v3_usuarios b on a.IdUsuario = b.Id WHERE a.IdMinuta = ' . $Id . ' AND a.Flag = 1');
        return $consulta;
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * $Id = id de la minuta 
     * @return array regresa campo Archivo dependiendo del where
     */

    public function mostrarNombreArchivo(string $Id) {
        $consulta = $this->consulta("SELECT Archivo FROM t_minutas WHERE Id = " . $Id);
        return $consulta;
    }

    /*
     * Encargado de insertar en la tabla t_archivos_mnuta
     *  $datos = datos para insertar
     *  @return array regresa todos los datos
     */

    public function setArchivoNuevo(array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar('t_archivos_minuta', $datos);
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        return $consulta;
    }

    /*
     * Encargado de verificar traer la consulta dependiendo el id y el idUSuario
     * 
     * @return array regresa todos los datos
     */

    public function getEstatus(array $datos) {
        $consulta = $this->consulta("SELECT * FROM t_archivos_minuta WHERE Id = " . $datos['id'] . " AND IdUsuario = " . $datos['usuario']);
        return $consulta;
    }

    /*
     * Encargado de actualizar la tabla t_archivos_minuta
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function actualizarEstatus(array $datos, array $where) {
        $consulta = $this->actualizar('t_archivos_minuta', $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de mandar los datos para visializar tabla t_minutas
     * $Id = id de la minuta 
     * @return array regresa campo Archivo dependiendo del where
     */

    public function getDatosMinuta(string $Id) {
        $consulta = $this->consulta("SELECT * FROM t_minutas WHERE Id = " . $Id);
        return $consulta;
    }

    /*
     * Encargado de actualizar la tabla t_minuta los miembros de la minuta y el archivo
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function actualizarMinuta(array $datos, array $where) {
        $consulta = $this->actualizar('t_minutas', $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Metodo para verficar que no se repita el nombre del archivo
     * $nombre = nombre del archivo
     * $minuta = id de la minuta
     * 
     */

    public function verificarNombreAA(string $nombre, string $minuta) {
        $consulta = $this->consulta('SELECT Id FROM t_archivos_minuta WHERE Nombre = "' . $nombre . '" AND IdMinuta = "' . $minuta . '"');
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Encargado de obtener el todos los datos del Servicio desde un Ticket */

    public function getServicioTicket(string $ticket = null) {
        $sentencia = "select * from t_servicios where Id_Orden = '" . $ticket . "'";
        $consulta = $this->consultaAD2($sentencia);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

}
