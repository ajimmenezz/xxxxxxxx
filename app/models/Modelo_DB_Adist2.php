<?php

namespace Modelos;

use Librerias\Modelos\Modelo_Base as Base;

class Modelo_DB_Adist2 extends Base {

    private $dominio;

    public function __construct() {

        $this->dominio = $_SERVER['SERVER_NAME'];       
        $posicion = strpos($this->dominio, 'siccob.solutions');
        
        if ($posicion !== FALSE) {
            parent::__construct('adist2');
        } else {            
            parent::__construct('pruebasAdist2');            
        }
    }

}
