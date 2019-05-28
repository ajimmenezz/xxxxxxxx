<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Proyecto extends General {

    private $DBProyectoGAPSI;
    private $id;
    private $tipo;    
    private $sucursales;
       

    public function __construct(int $idProyecto) {
        parent::__construct();
        $this->DBProyectoGAPSI = \Modelos\Modelo_GapsiProyecto::factory();
        $this->id = $idProyecto;
    }        
    
    public function setSucursales(){
        var_dump($this->id);
    }
}
