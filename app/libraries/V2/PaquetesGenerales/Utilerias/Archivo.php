<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

class Archivo {

    static private $CI;
    static private $archivos;
    static private $error;
  
    static private function setConfiguracion() {
        self::$CI = & get_instance();
        self::$CI->load->helper('fileupload');
        self::$archivos = null;
    }

    static public function saveArchivos(string $carpeta) {
        self::setConfiguracion();
        $nombre = '';

        foreach ($_FILES as $key => $value) {
            $nombre = $key;
        }
        self::$archivos = setMultiplesArchivos(self::$CI, $nombre, $carpeta);
    }

    static public function getString() {

        if (empty(self::$archivos)) {
            throw new \Exception('No se ha subido ningun archivo');
        }
        $temporal = implode(',', self::$archivos);
        self::$archivos = null;
        return $temporal;
    }

    static public function getArray() {

        if (empty(self::$archivos)) {
            throw new \Exception('No se ha subido ningun archivo');
        }

        $temporal = self::$archivos;
        self::$archivos = null;
        return $temporal;
    }

    static public function deleteArchivo(string $carpeta) {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            self::$error = array();
            switch ($errno) {
                case E_WARNING:
                    self::$error['tipo'] = 'Warning';
                    self::$error['codigo'] = 'ESD001';
                    self::$error['error'] = $errstr;
                    self::$error['archivo'] = $errfile . ': linea : ' . $errline;
                    break;                
            }

            throw new \Exception('Error :' .$errstr );
        }, E_WARNING);


        self::setConfiguracion();
        eliminarArchivo($carpeta);

        restore_error_handler();
    }
    
    static public function saveArchivos64($param) {
        
    }

}
