<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Solicitud
 *
 * @author Freddy
 */
class Modelo_Archivo extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de insertar una nuevo archivo
     *  $datos = datos para insertar en la tabla t_archivos_formatos
     */

    public function setArchivoFormato(array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->insertar('t_archivos_formatos', $datos);
        return parent::connectDBPrueba()->insert_id();
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * $where = id del para la consulta
     * @return array regresa todos los datos dependiendo el IdTipoArchivo
     */

    public function getHistoricoArchivos(string $where) {
        $consulta = $this->consulta("SELECT b.*, c.Nombre FROM t_archivos_formatos AS a INNER JOIN historico_archivos_formatos AS b ON b.IdArchivoFormato = a.Id INNER JOIN cat_v3_usuarios AS c ON c.Id = b.IdUsuario WHERE b.IdArchivoFormato = " . $where);
        return $consulta;
    }

    /*
     * Encargado de mandar los datos para visializar tabla 
     * 
     * $where = id del para la consulta y asi solo visualisar esos campos
     * @return array regresa los datos
     */

    public function getArchivosTabla(array $where) {
        $consulta = $this->encontrar('t_archivos_formatos', $where);
        return $consulta;
    }

    /*
     * Encargado de mandar a llamar la url dependiedo id
     * $Id = id del archivo 
     * @return array regresa campo URL dependiendo del Id
     */

    public function mostrarNombreArchivo(string $Id) {
        $consulta = $this->consulta("SELECT Url FROM t_archivos_formatos WHERE Id = " . $Id);
        return $consulta;
    }

    /*
     * Encargado de mandar a llamar la descripcion dependiendo id
     * $Id = id del archivo 
     * @return array regresa campo Archivo dependiendo del where
     */

    public function mostrarDescripcionArchivo(string $Id) {
        $consulta = $this->consulta("SELECT Descripcion FROM t_archivos_formatos WHERE Id = " . $Id);
        return $consulta;
    }

    /*
     * Encargado de mandar a llamar el IdTipoArchivo
     * $Id = id del archivo 
     * @return array regresa campo Archivo dependiendo del where
     */

    public function mostrarTipoArchivo(string $Id) {
        $consulta = $this->consulta("SELECT IdTipoArchivo FROM t_archivos_formatos WHERE Id = " . $Id);
        return $consulta;
    }

    /*
     * Encargado de actualizar en la tabla t_archivos_formatos
     *  $datos = datos para insertar
     *  @return array regresa todos los datos
     */

    public function actualizarArchivo(array $datos, array $where) {
        $consulta = $this->actualizar('t_archivos_formatos', $datos, $where);
        return $consulta;
    }

    /*
     * Metodo para verificar si no hay o hay un dato en la BD
     * 
     * @param array $dato recibe el campo de la tabla en BD
     * @return consulta si hay un dato con ese nombre, false si hay un dato con ese nombre
     */

    public function verficarRepetidoArchivo(array $dato) {
        $consulta = $this->encontrar('t_archivos_formatos', $dato);
        if (empty($consulta)) {
            return $consulta;
        } else {
            return false;
        }
    }

}
