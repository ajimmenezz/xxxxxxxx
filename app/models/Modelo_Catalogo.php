<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Catalogo extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function setArticulo(string $tabla, array $datos, array $validar = null, string $key = null) {
        if (!empty($validar)) {
            $consulta = $this->encontrar($tabla, $validar);
        } else {
            $consulta = null;
        }
        if (empty($consulta)) {
            if ($key === 'TRUE') {
                parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 0');
                $this->insertar($tabla, $datos);
                $last = parent::connectDBPrueba()->insert_id();
                parent::connectDBPrueba()->query('SET FOREIGN_KEY_CHECKS = 1');
                $tipos = $this->getArticulos($tabla);
            } else {
                $consulta = $this->insertar($tabla, $datos);
                $last = parent::connectDBPrueba()->insert_id();
                $tipos = $this->getArticulos($tabla);
            }
            $tipos['ultimoId'] = $last;
            return $tipos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar
     *  $tabla = tabla en la BD
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     *  $parametro = nombre del campo de la tabla a la cual vamos a validar si existe
     *  $validar = texto que se compara con el paramatro para validar si ya existe
     */

    public function actualizarArticulo(string $tabla, array $datos, array $where, string $validar = null, string $parametro = null, array $campos_ = null) {
        $consulta = array();
        if (!empty($validar)) {
            $consulta = $this->consulta('SELECT * FROM ' . $tabla . ' WHERE ' . $parametro . ' = "' . $validar . '" and Id <> "' . $where['Id'] . '"');
        } else {
            if (!empty($campos_)) {
                $campos = " WHERE";
                foreach ($campos_ as $key => $value) {
                    $campos .= " " . $value['campo'] . " " . $value['signo'] . " '" . $value['valor'] . "' " . $value['operacion'];
                }
                $consulta = $this->consulta('SELECT * FROM ' . $tabla . $campos);
            }
        }
        if (empty($consulta)) {
            $consulta = $this->actualizar($tabla, $datos, $where);
            if (isset($consulta)) {
                return $this->getArticulos($tabla);
            } else {
                return parent::tipoError();
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de mandar los datos para visializar tabla especifica
     * 
     * @return array regresa todos los datos
     */

    public function getArticulos(string $tabla, array $condicion = null) {
        $consulta = $this->encontrar($tabla, $condicion);
        return $consulta;
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function getJuntarTablas(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar y devolver el id en que se inserto
     *  $tabla = tabla en la BD
     *  $datos = datos para actualizar
     *  @return id si es correcto y false de lo contrario
     */

    public function setArticuloId(string $tabla, array $datos) {
        if (empty($consulta)) {
            $consulta = $this->insertar($tabla, $datos);
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de solo actualizar 
     *  $tabla = tabla en la BD
     *  $datos = datos para actualizar
     *  $where = id que necesitamos para saber que campos se modificaran
     */

    public function actualizarUnicoDato(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        if (isset($consulta)) {
            return $this->getArticulos($tabla);
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de obtener el id de un catálogo para saber si existe o no el registro duplicado
     * $tabla_ = tabla en donde se buscará el registro
     * $campos_ = arreglo que guarda el campo, el simbolo de comparacion y el valor a comparar 
     * Ejemplo de $campo_: array('campo' => Nombre, 'signo' => '=', 'valor' = 'ASUS')
     * Ninguno de los parametros puede ser nulo
     */

    public function revisaSiExiste(string $tabla_, array $campos_) {
        $campos = " where";
        foreach ($campos_ as $key => $value) {
            $campos .= " " . $value['campo'] . " " . $value['signo'] . " '" . $value['valor'] . "' and";
        }
        $campos = substr($campos, 0, -3);

        $query = "select Id from " . $tabla_ . $campos . ";";

        $consulta = $this->consulta($query);
        if (!empty($consulta)) {
            return $consulta['0']['Id'];
        } else {
            return '';
        }
    }

    public function limpiarFuncion() {
        mysqli_next_result(parent::connectDBPrueba()->conn_id);
    }

    public function getAlmacenesVirtuales($id = null, $flag = null, $userInfo) {
        $condicion = '';
        if (!is_null($id)) {
            $condicion .= " and cav.Id = '" . $id . "'";
        }

        if (!is_null($flag)) {
            $condicion .= " and cav.Flag = '" . $flag . "'";
        }

        $condicionPermiso = " 
        and (
            (IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $userInfo['Id'] . "') 
            or (IdTipoAlmacen = 4 and IdResponsable = '" . $userInfo['Id'] . "')
        ) ";
        if (in_array(337, $userInfo['Permisos']) || in_array(337, $userInfo['PermisosAdicionales'])) {
            $condicionPermiso = "";
        }

        $this->queryBolean("
                insert into cat_v3_almacenes_virtuales(IdTipoAlmacen,IdReferenciaAlmacen,Nombre, Flag)
                select 
                1,
                cu.Id,
                concat('Almacén de ',nombreUsuario(cu.Id)),
                1
                from cat_v3_usuarios cu
                where cu.Id 
                not in (select IdReferenciaAlmacen from cat_v3_almacenes_virtuales where IdTipoAlmacen = 1)
                and cu.Flag = 1
                and cu.Id <> 1");

        $query = ''
                . 'select '
                . 'cav.Id, '
                . 'cav.Nombre, '
                . 'ctav.Nombre as Tipo, '
                . 'cav.IdReferenciaAlmacen as Referencia, '
                . 'cav.Flag '
                . 'from cat_v3_almacenes_virtuales cav '
                . 'inner join cat_v3_tipos_almacenes_virtuales ctav on cav.IdtipoAlmacen = ctav.Id '
                . 'where 1 = 1 ' . $condicion . $condicionPermiso;
        return $this->consulta($query);
    }

}
