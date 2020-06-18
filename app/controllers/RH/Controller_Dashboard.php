<?php

    use Controladores\Controller_Base as Base;
    
    class Controller_Dashboard extends Base{
        
        private $rh;
        
        public function __construct() {
            parent::__construct();
            $this->rh = new \Librerias\RH\Dashboard();
        }
        
        public function manejarEvento(string $evento = null) {
            
        }
    }

