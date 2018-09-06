<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Usuario extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de insertar el personal sus datos en secccion informacion personal 
     * y crea tambien al usuario
     * @param array $datos insert los datos para crear el personal y usuario
     */

    public function setAltaPersonal(string $tabla, string $tabla2, array $datos, array $datos2, string $idDatos2) {
        parent::connectDBPrueba()->trans_start();

        $consulta = $this->insertar($tabla, $datos);
        $datos2[$idDatos2] = parent::connectDBPrueba()->insert_id();
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->insertar($tabla2, $datos2);
        $idPersonal = parent::connectDBPrueba()->insert_id();
        parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
        parent::connectDBPrueba()->trans_complete();
        if (parent::connectDBPrueba()->trans_status() === FALSE) {
            return $this->registerError('Error al realizar operacion de actualizacion');
        }
        parent::connectDBPrueba()->trans_off();
        return $idPersonal;
    }

    /*
     * Encargado de actualiizar tabla
     * 
     * @return array regresa todos los datos de la tabla
     */

    public function ActualizarPersonal(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        return $consulta;
    }

    /*
     * Metodo para verificar si no hay o hay un dato en la BD
     * 
     * @param string $tabla recibe nombre de la tabla 
     * @param array $datos recibe el nombre de la tabla en BD
     * @return true si no hay un dato con ese nombre, false si hay un dato con ese nombre
     */

    public function verficarDatoRepetido(string $tabla, array $dato) {
        $consulta = $this->encontrar($tabla, $dato);
        if (empty($consulta)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Encargado de actualizar la tabla t_minuta
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function insertarFoto(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        if (isset($consulta)) {
            return true;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * 
     * @return array regresa todos los datos
     */

    public function getPersonal(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

}
