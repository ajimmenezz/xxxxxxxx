<?php

namespace Librerias\Modelos;

class Base {

    static $DB_Adist3;
    static $DB_Adist2;
    static $DB_Adist2P;
    static $DB_prueba;
    static $DB_SAE;
    static $DB_SAE7;
    static $DB_Gapsi;
    static $DB_Sicsa;
    private $consulta;

    public function __construct() {
        
    }

    /*
     * Realiza la conexion a la base de datos pruebas
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBPrueba() {
        if (empty(self::$DB_prueba)) {
            self::$DB_prueba = get_instance()->load->database('pruebas', TRUE);
        }
        return self::$DB_prueba;
    }

    /*
     * Realiza la conexion a la base de datos Adist produccion 
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBAdist2() {
        if (empty(self::$DB_Adist2)) {
            self::$DB_Adist2 = get_instance()->load->database('adist2', TRUE);
        }
        return self::$DB_Adist2;
    }

    /*
     * Realiza la conexion a la base de datos Adist Pruebas 
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBAdist2P() {
        if (empty(self::$DB_Adist2P)) {
            self::$DB_Adist2P = get_instance()->load->database('pruebasAdist2', TRUE);
        }
        return self::$DB_Adist2P;
    }

    /*
     * Realiza la conexion a la base de datos Nueva base de datos 
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBAdist3() {
        if (empty(self::$DB_Adist3)) {
            self::$DB_Adist3 = get_instance()->load->database('adist3', TRUE);
        }
        return self::$DB_Adist3;
    }

    /*
     * Realiza la conexion a la base de datos Nueva base de datos 
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBSAE() {
        if (empty(self::$DB_SAE)) {
            self::$DB_SAE = get_instance()->load->database('SAE', TRUE);
        }
        return self::$DB_SAE;
    }

    /*
     * Realiza la conexion a la base de datos Nueva base de datos 
     * 
     * @return object regresa la instancia del base de datos
     */

    static public function connectDBSAE7() {
        if (empty(self::$DB_SAE7)) {
            self::$DB_SAE7 = get_instance()->load->database('SAE7', TRUE);
        }
        return self::$DB_SAE7;
    }

    static public function connectDBGapsi() {
        if (empty(self::$DB_Gapsi)) {
            self::$DB_Gapsi = get_instance()->load->database('Gapsi', TRUE);
        }
        return self::$DB_Gapsi;
    }

    static public function connectDBSicsa() {
        if (empty(self::$DB_Sicsa)) {
            self::$DB_Sicsa = get_instance()->load->database('Sicsa', TRUE);
        }
        return self::$DB_Sicsa;
    }

    /*
     * Se encarga de buscar un registro en la base de datos
     * 
     * @param string $table recibe el nombre de la tabla donde realiza la busqueda
     * @param array $condiciones recibe un array para especificar los where de la busqueda
     * @param integer $limit recibe un entero para definir el limite de registos de la busqueda
     * @param integer $offeset recibe un entero para definir la clausula offset para la busqueda
     * @return array regresa el resultado de la consulta como un array o array vacio si no genera la consulta.
     *
     */

    public function encontrar($table, $where = null, $limit = null, $offset = null) {
        if (isset($where)) {
            $this->consulta = self::connectDBPrueba()->where($where)->get($table, $limit, $offset);
            if ($this->consulta) {
                return $this->consulta->result_array();
            } else {
                return self::connectDBPrueba()->error();
            }
        } else {
            $this->consulta = self::connectDBPrueba()->get($table, $limit, $offset);
            return $this->consulta->result_array();
        }
    }

    //Inserta registro 
    public function insertar($table, array $data) {
        if (!empty($data)) {
            self::connectDBPrueba()->insert($table, $data);
            return self::connectDBPrueba()->affected_rows();
        }
        return null;
    }

    //Actualiza el registro
    public function actualizar(string $table, array $data, array $where = null) {
        if (!empty($data)) {
            if (!empty($where)) {
                self::connectDBPrueba()->where($where);
            }
            self::connectDBPrueba()->update($table, $data);
            return self::connectDBPrueba()->affected_rows();
        }
        return null;
    }

    //Elimina un registro
    public function eliminar($table, array $where) {
        self::connectDBPrueba()->delete($table, $where);
        return self::connectDBPrueba()->affected_rows();
    }

    /*
     * Ejecuta la consulta solicitada
     * 
     * @param string $consulta recibe la consulta que se va ha ejecutar.
     * @return array regresa el resultado de la consulta como un array o array vacio si no genera la consulta.
     */

    public function consulta(string $consulta) {
        $this->consulta = self::connectDBPrueba()->query($consulta);
        return $this->consulta->result_array();
    }

    /*
     * Obtiene la cantidad de filas que regresa la consulta
     */

    public function getCantidad() {
        return $this->consulta->num_rows();
    }

    /*
     * Ejecuta el error de la consulta
     *  
     * @return array regresa un codigo y un mensaje del error.
     */

    public function tipoError() {
        return self::connectDBPrueba()->error();
    }

    /*
     * Genera un objeto sin necesidad de instanciarlo
     * 
     */

    static public function factory($driver = null) {
        return new static($driver);
    }

    public function iniciaTransaccion() {
        self::connectDBPrueba()->trans_start();
    }

    public function terminaTransaccion() {
        self::connectDBPrueba()->trans_complete();
    }

    public function commitTransaccion() {
        self::connectDBPrueba()->trans_commit();
    }

    public function roolbackTransaccion() {
        self::connectDBPrueba()->trans_rollback();
    }

    public function estatusTransaccion() {
        return self::connectDBPrueba()->trans_status();
    }

    public function iniciaTransaccionSAE() {
        self::connectDBSAE7()->trans_start();
    }

    public function terminaTransaccionSAE() {
        self::connectDBSAE7()->trans_complete();
    }

    public function commitTransaccionSAE() {
        self::connectDBSAE7()->trans_commit();
    }

    public function roolbackTransaccionSAE() {
        self::connectDBSAE7()->trans_rollback();
    }

    public function estatusTransaccionSAE() {
        return self::connectDBSAE7()->trans_status();
    }

    /*
     * Ejecuta la consulta solicitada a la Base de Datos de AdIST 2
     * 
     * @param string $consulta recibe la consulta que se va ha ejecutar.
     * @return array regresa el resultado de la consulta como un array o array vacio si no genera la consulta.
     */

    public function consultaAD2(string $consulta) {
        $host = $_SERVER['SERVER_NAME'];
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $this->consulta = self::connectDBAdist2()->query($consulta);
            return $this->consulta->result_array();
        } elseif ($host === 'pruebas.siccob.solutions' || $host === 'www.pruebas.siccob.solutions') {
            $this->consulta = self::connectDBAdist2P()->query($consulta);
            return $this->consulta->result_array();
        } else {
            $this->consulta = self::connectDBAdist2P()->query($consulta);
            return $this->consulta->result_array();
        }
    }

    public function queryBolean(string $consulta) {
        $this->consulta = self::connectDBPrueba()->query($consulta);
        return $this->consulta;
    }

    public function limpiarFuncion() {
        mysqli_next_result(self::connectDBPrueba()->conn_id);
    }

    public function ultimoId() {
        return self::connectDBPrueba()->insert_id();
    }

    public function getFecha() {
        $this->consulta = self::connectDBPrueba()->query("select NOW() as Fecha");
        $data = $this->consulta->result_array();
        return $data[0]['Fecha'];
    }

    public function getGeneralInfoByUserID(int $id) {
        $this->consulta = self::connectDBPrueba()->query("select 
                                                            nombreUsuario(Id) as Nombre,
                                                            Email,
                                                            EmailCorporativo
                                                            from cat_v3_usuarios 
                                                            where Id = '" . $id . "'");
        $data = $this->consulta->result_array();
        return $data[0];
    }

    public function consultaGapsi(string $consulta) {
        $this->consulta = self::connectDBGapsi()->query($consulta);
        return $this->consulta->result_array();
    }

}
