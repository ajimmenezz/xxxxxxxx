<?php
namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Videos
 *
 * @author asus
 */
class Modelo_Videos extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }
    
    /*
     * Encargado de responder con la lista de videos correspondientes a la capacitación.
     *  $id = Id de la capacitación
     */
    
    public function cargaVideosCapacitacion(int $id){
        $consulta = $this->encontrar('t_videos_capacitaciones', array('IdCapacitacion' => $id));
        return $consulta;
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
        $consulta = $this->consulta("select * from t_minutas where ' . $IdUsuario . ' REGEXP(replace(Miembros,',','|'))");
        return $consulta;
    }

    /*
     * Encargado de mandar los datos para visializar tabla 
     * 
     * @return array regresa todos los datos
     */

    public function getArchivosMinutas(string $Id) {
        $consulta = $this->consulta('SELECT a.Id, b.Nombre, a.Fecha, a.Archivo FROM t_archivos_minuta a INNER JOIN cat_v3_usuarios b on a.IdUsuario = b.Id WHERE a.IdMinuta = ' . $Id);
        return $consulta;
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * $Id = id de la minuta 
     * @return array regresa campo Archivo dependiendo del where
     */

    public function mostrarNombreArchivo(string $Id) {
        $consulta = $this->consulta("SELECT Archivo FROM adist3_prod.t_minutas WHERE Id = " . $Id);
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

}
