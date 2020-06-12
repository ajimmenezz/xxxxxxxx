<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Cursos extends General{
    
    private $DBS;
    
    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Cursos::factory();
    }
    
    public function prueba(array $datos) {
        
        return array($this->DBS->getUsuarios(),$datos);
    }
}
