<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Almacen\Inventario as Inventario;

class Rehabilitacion extends General {

    private $inventario;

    public function __construct() {
        parent::__construct();
        $this->DBI = \Modelos\Modelo_InventarioConsignacion::factory();
        parent::getCI()->load->helper('date');
    }

    public function getAlmacenUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();
        $this->inventario = new Inventario();
        
        
        //IniciaPruebas
        $this->inventario->getInventarioId(array());
        //TerminaPruebas
        return $this->inventario->getInventarioUsuario($usuario['Id']);
    }
    
    

}
