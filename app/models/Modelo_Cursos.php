<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Cursos extends Modelo_Base{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsuarios(){
        return array('usuario' => 'Sara', 'edad' => '26');
    }
}
