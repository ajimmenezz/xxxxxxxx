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

            throw new \Exception('Error :' . $errstr);
        }, E_WARNING);


        self::setConfiguracion();
        eliminarArchivo($carpeta);

        restore_error_handler();
    }

    static public function saveArchivos64(string $carpeta, array $imagenes) {

        self::$archivos = array();
        $temporal = $_SERVER['DOCUMENT_ROOT'] . '/storage/Archivos/' . $carpeta;

        if (!file_exists($temporal)) {
            mkdir($temporal, 0777, true);
        }

        foreach ($imagenes as $key => $imagen) {
            $temporal = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imagen));
            $archivo = base64_decode($temporal);
            $temporal = '/storage/Archivos/' . $carpeta . '/' . $key . '.png';

            if (!file_put_contents($_SERVER['DOCUMENT_ROOT'] . $temporal, $archivo)) {
                throw new \Exception('Error : No se puede guardar la imagen de tipo 64 ');
            }
            array_push(self::$archivos, $temporal);
        }
    }

}
