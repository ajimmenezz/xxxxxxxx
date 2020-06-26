<?php

namespace Controladores;

use CI_Controller;

class Controller_Base_General{

    static public $CI;
    
    public function __construct() {           
    }

    static public function getCI() {
        if (empty(self::$CI)) {
            self::$CI =& get_instance();
        }
        return self::$CI;
    }

    static public function factory($driver = null) {
        return new static($driver);
    }

}
