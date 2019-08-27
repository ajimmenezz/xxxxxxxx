<?php

namespace Librerias\Reportes;

use Controladores\Controller_Base_General as General;

class Lexmark extends General {

    private $DB;    

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_PrinterLexmark::factory();        
    }

    public function setDailyPrints() {
        $result = $this->DB->setDailyPrints();
        return $result;
    }

}
