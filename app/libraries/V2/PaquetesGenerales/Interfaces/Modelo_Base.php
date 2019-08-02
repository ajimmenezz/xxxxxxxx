<?php
namespace Librerias\V2\PaquetesGenerales\Interfaces;

class Modelo_Base
{
    private $nombreBD;
    static $DB = array();

    public function __construct(string $db = 'pruebas') {
        $this->nombreBD = $db;
        if (empty(self::$DB[$db])) {
            self::$DB[$db] = get_instance()->load->database($db, TRUE);
        }
        return self::$DB[$db];
    }
    
    public function query(string $query) {
        if ($consulta = self::$DB[$this->nombreBD]->simple_query($query)) {
            return $consulta;
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    public function consulta(string $query) {
        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            $consulta = self::$DB[$this->nombreBD]->query($query);
            return $consulta->result_array();
        } else {
            $this->lanzarExcepcion($query);
        }
    }

    public function insertar(string $query) {

        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            return self::$DB[$this->nombreBD]->insert_id();
        } else {
            $this->lanzarExcepcion($query);                    
        }
    }
    
    public function actualizar(string $query) {

        if (self::$DB[$this->nombreBD]->simple_query($query)) {
            return self::$DB[$this->nombreBD]->affected_rows();
        } else {
            $this->lanzarExcepcion($query);                    
        }
    }
    
    public function borrar(string $query) {

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

    private function lanzarExcepcion(string $query) {
        
        $error = self::$DB[$this->nombreBD]->error();        
        throw new \Exception('Error para genera la consulta: ' . $query . ' donde presenta el siguiente error : ' . $error['message']);
    }
}