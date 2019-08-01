<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

use CI_Controller;

Class Usuario {

    static private $CI;
    static private $datos;

    static private function setCI() {
        if (empty(self::$CI)) {
            self::$CI =& get_instance();
        }
    }

    static private function setDatos() {
        self::setCI();
        if (empty(self::$datos)) {
            self::$datos = self::$CI->session->userdata();
        }
    }

    static public function getId() {
        self::setDatos();
        $rol = self::$datos['Id'];
        return $rol;
    }

    static public function getIdJefe() {
        self::setDatos();
        $rol = self::$datos['IdJefe'];
        return $rol;
    }

    static public function getRol() {
        self::setDatos();
        $rol = self::$datos['Rol'];
        return $rol;
    }

    static public function getAPIKEY() {
        self::setDatos();
        return self::$datos['SDKey'];
    }

}
