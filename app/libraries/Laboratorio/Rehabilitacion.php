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
        $data = array();
        
        $inventario = $this->inventario->getInventarioId('19550');
        $data['infoBitacora']['id'] = $inventario[0]['Id'];
        $data['infoBitacora']['modelo'] = $inventario[0]['Producto'];
        $data['infoBitacora']['serie'] = $inventario[0]['Serie'];
        $data['infoBitacora']['estatus'] = $inventario[0]['Estatus'];
        $data['infoBitacora']['ticketFolio'] = '0';
        $data['infoBitacora']['comentarios'] = array();
        var_dump($data);
        //TerminaPruebas
        return $this->inventario->getInventarioUsuario($usuario['Id']);
    }
    
    

}
