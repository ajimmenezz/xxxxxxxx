<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_SAE7 extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /* Encargado de hacer la consulta de los almacenes virtuales de SAE */

    public function getAlmacenesSAE() {
        $query = "select
        CVE_ALM as Id,
        DESCR as Almacen,
        ENCARGADO as Encargado,
        CASE
            WHEN STATUS = 'A' THEN 'Activo'
            WHEN STATUS <> 'A' THEN 'Inactivo'
        END as Estatus
        from ALMACENES03
        order by Almacen;";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    /* Encargado de hacer la consulta de los almacenes virtuales de SAE */

    public function getInventarioAlamacenSAE($almacen) {
        $query = "select
        producto.CVE_ART as Clave,
        producto.DESCR as Producto,
        linea.DESC_LIN as Linea,
        producto.UNI_MED as Unidad,
        multi.EXIST as Existencia,
        producto.ULT_COSTO as Costo
        from MULT03 multi inner join INVE03 producto
        on multi.CVE_ART = producto.CVE_ART
        LEFT JOIN CLIN03 linea
        on producto.LIN_PROD = linea.CVE_LIN
        where multi.CVE_ALM = '" . $almacen . "' and multi.EXIST > 0;";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
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

    public function eliminarDatos(string $tabla, array $where) {
        $consulta = $this->eliminar($tabla, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Encargado de hacer la consulta a la BD de SAE */

    public function consultaBDSAE($query) {
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

    public function consultaProveedoresSAE() {
        $query = "select 
                    CLAVE,
                    NOMBRE,
                    concat(CALLE, ' ', NUMEXT, ' ', COLONIA, ' CP ', CODIGO) AS DIRECCION
                from PROV03
                where STATUS = 'A'
                order by NOMBRE asc";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }
    
    public function consultaAlmacenesSAE() {
        $query = "select 
                    CVE_ALM,
                    DESCR,
                    DIRECCION
                  from ALMACENES03
                  where STATUS = 'A'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }
    
    public function consultaProductosSAE() {
        $query = "select 
                    CVE_ALM,
                    DESCR,
                    DIRECCION
                  from ALMACENES03
                  where STATUS = 'A'";
        $consulta = parent::connectDBSAE7()->query($query);
        return $consulta->result_array();
    }

}
