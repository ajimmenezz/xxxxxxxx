<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GestorProyectos extends General {

    private $DBGestor;
    private $proyectos;

    public function __construct() {
        parent::__construct();
        $this->DBGestor = \Modelos\Modelo_GestorProyectos::factory();
    }
    
    public function getDatosGeneralesProyectos() { 
        $listaIdProyectos = $this->DBGestor->getListaProyectos();        
        return $this->crearProyectos($listaIdProyectos);
    }
    
    public function getDatosProyectos() {
        return array();
    }
    
    private function crearProyectos(array $listaIdProyectos){
        $this->proyectos = array();
        foreach ($listaIdProyectos as $key => $proyecto) {
            $temporal = new \Librerias\Gapsi\Proyecto($proyecto['IdProyecto']);
            array_push($this->proyectos, $temporal);
        }
        echo '<pre>';
        var_dump($this->proyectos);
    }
}
