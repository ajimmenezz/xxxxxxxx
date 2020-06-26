<?php

namespace Librerias\V2\PaquetesGenerales\Interfaces;

class Modelo_Base {

    private $nombreBD;
    static $DB = array();

    public function __construct(string $db = 'pruebas') {
        $this->nombreBD = $db;
        if (empty(self::$DB[$db])) {
            self::$DB[$db] = get_instance()->load->database($db, TRUE);
        }
        return self::$DB[$db];
    }

    protected function query(string $query) {
        if ($consulta = self::$DB[$this->nombreBD]->simple_query($query)) {
            return $consulta;
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    protected function consulta(string $query) {
        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            $consulta = self::$DB[$this->nombreBD]->query($query);
            return $consulta->result_array();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    protected function insertar(string $query) {

        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            return self::$DB[$this->nombreBD]->insert_id();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    protected function actualizar(string $query) {

        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            return self::$DB[$this->nombreBD]->affected_rows();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    protected function borrar(string $query) {

        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            return self::$DB[$this->nombreBD]->affected_rows();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    public function empezarTransaccion() {
        self::$DB[$this->nombreBD]->trans_begin();
    }

    public function finalizarTransaccion() {
        if (self::$DB[$this->nombreBD]->trans_status() === FALSE) {
            self::$DB[$this->nombreBD]->trans_rollback();
            throw new \Exception('No se genero la transacción');
        } else {
            self::$DB[$this->nombreBD]->trans_commit();
        }
    }

    public function ejecutaFuncion(string $query) {
        if ($consulta = self::$DB[$this->nombreBD]->query($query)) {
            \mysqli_next_result(self::$DB[$this->nombreBD]->conn_id);
            return $consulta->result_array();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    private function lanzarExcepcion(string $query) {
        $error = self::$DB[$this->nombreBD]->error();
        throw new \Exception('Error para genera la consulta: ' . $query . ' donde presenta el siguiente error : ' . $error['message']);
    }

    public function insertarArray($table, array $data) {
        if (self::$DB[$this->nombreBD]->insert($table, $data)) {
            return self::$DB[$this->nombreBD]->affected_rows();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    public function actualizarArray(string $table, array $data, array $where = null) {
        if (!empty($data)) {
            if (!empty($where)) {
                self::$DB[$this->nombreBD]->where($where);
            }
            if (self::$DB[$this->nombreBD]->update($table, $data)) {
                return self::$DB[$this->nombreBD]->affected_rows();
            } else {
                $this->lanzarExcepcion('Error con la base de datos.');
            }
        }
        return null;
    }

    public function ultimoId() {
        return self::$DB[$this->nombreBD]->insert_id();
    }

    public function fechaActualBD() {
        return $this->consulta("select now() as Fecha;");
    }

}
