<?php

    namespace Librerias\RH;
    
    use Controladores\Controller_Base_General as General;
    
    class Dashboard extends General{
        
        private $usuario;
        private $DBP;
        
        public function __construct() {
            parent::__construct();
            $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
            $this->DBP = \Modelos\Modelo_Administrador::factory();
            parent::getCI()->load->helper('date');
        }
    }

