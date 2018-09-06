<?php

namespace Controladores;

use Controladores\Controller_Base_General as General;

/**
 * Description of Controller_Objetos
 *
 * @author Freddy
 */
class Controller_Datos_Usuario extends General {
    
    protected $Usuario;

    public function __construct() {
        parent::__construct();
        $usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->Usuario =& $usuario;
    }
    
}
