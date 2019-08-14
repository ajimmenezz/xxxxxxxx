<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

class Archivo {

    static private $CI;
    static private $archivos;

    static private function setConfiguracion() {
        self::$CI =& get_instance();
        self::$CI->load->helper('fileupload');
        self::$archivos = null;
    }

    static public function saveArchivos(string $carpeta) {
        self::setConfiguracion();
        self::$archivos = null;
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

}
