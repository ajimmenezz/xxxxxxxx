<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Proyecto extends General {

    private $DBProyectoGAPSI;
    private $id;
    private $tipo;    
    private $sucursales;
    private $servicios;    

    public function __construct() {
        parent::__construct();
        $this->DBProyectoGAPSI = \Modelos\Modelo_GapsiProyecto::factory();
    }        
}
