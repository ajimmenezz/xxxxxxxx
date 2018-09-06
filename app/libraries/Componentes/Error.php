<?php

namespace Librerias\Componentes;

use CI_Controller;

class Error {

   
    private $CI;

    public function __construct() {
        if (empty(self::$CI)) {
            $this->CI = & get_instance();            
        }
        $this->CI->load->helper('url');
    }

    public function mostrarError(\Exception $ex) {
        log_message('error', 'Se genero el siguiente error : ' .$ex->getMessage());
        return array('Error' => '/Error/ErrorDB');
    }

}

/*
 * Lista de errores fatales:
 * 
 *      404: Recurso no econtrado
 *      408: Sesion caducada
 *      409: Error para validar clave
 *      601: Sin acceso a pagina
 *      602: Error en la base de datos
 */