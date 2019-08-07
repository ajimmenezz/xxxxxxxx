<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

use \CI_Controller;

Class Usuario {

    static private $CI;
    static private $datos;

    static private function setCI() {
        if (empty(self::$CI)) {
            self::$CI =& get_instance();           
        }
        self::$CI->load->library('session');
    }

    static private function setDatos() {
        self::setCI();
        if (empty(self::$datos)) {
            self::$datos = self::$CI->session->userdata();
        }        
    }

    static public function getId() {
        self::setDatos();
        return self::$datos['Id'];
    }

    static public function getIdJefe() {
        self::setDatos();
        return self::$datos['IdJefe'];
    }

    static public function getRol() {
        self::setDatos();
        return self::$datos['Rol'];
    }

    static public function getAPIKEY() {
        self::setDatos();
        return self::$datos['SDKey'];
    }

}
