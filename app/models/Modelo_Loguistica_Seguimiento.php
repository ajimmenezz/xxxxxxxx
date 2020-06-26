<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Loguistica_Seguimiento extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de consultar la tabla dependiendo de la sentencia
     * @param string $sentencia recibe la sentencia para hacer la consulta
     * @return array regresa todos los datos de una o varias tablas
     */

    public function insertarSeguimiento(string $tabla, array $datos) {
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $consulta = $this->insertar($tabla, $datos);
        
        if (!empty($consulta)) {
            $Id = parent::connectDBPrueba()->insert_id();
            parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
            return $Id;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar
     *  @param string $tabla = tabla en la BD
     *  @param string $datos = datos para actualizar
     *  @param string $where = id que necesitamos para saber que campos se modificaran
     *  @return boolean TRUE si fue correcto de lo contrario el tipo de error
     */

    public function actualizarSeguimiento(string $tabla, array $datos, array $where = null) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaGeneralSeguimiento(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaQuery(string $sentencia) {
        $consulta = parent::connectDBPrueba()->query($sentencia);
        return $consulta;
    }

    /*
     * Encargado de eliminar informacion de la tabla
     */

    public function eliminarDatos(string $tabla, array $where ) {
        $consulta = $this->eliminar($tabla, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
