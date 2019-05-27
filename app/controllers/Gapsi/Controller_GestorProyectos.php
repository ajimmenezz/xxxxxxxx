<?php

use Controladores\Controller_Base as Base;

class Controller_GestorProyectos extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();        
    }

    public function manejarEvento(string $evento = null) {
        
    }

}