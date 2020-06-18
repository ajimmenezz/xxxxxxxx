<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class GestorProyectos extends General {

    private $DBGestor;
    private $proyectos;
    private $typeProyects;

    public function __construct() {
        parent::__construct();
        $this->DBGestor = \Modelos\Modelo_GestorProyectos::factory();
    }

    public function getDatosGeneralesProyectos() {
        $listaIdProyectos = $this->DBGestor->getListaProyectos();
        $this->crearProyectos($listaIdProyectos);
        $this->getlistTypeProyects();
        return array('TiposProyectos' => $this->typeProyects, 'Proyectos' => $this->proyectos);
    }

    private function crearProyectos(array $listaIdProyectos) {
        $this->proyectos = array();
        foreach ($listaIdProyectos as $key => $proyecto) {
            $temporal = new \Librerias\Gapsi\Proyecto($proyecto['IdProyecto']);
            array_push($this->proyectos, $temporal);
        }
    }

    private function getlistTypeProyects() {
        $temporal = array();

        $this->typeProyects = $this->DBGestor->getListTypeProyects();

        foreach ($this->typeProyects as $typeProject) {
            $gasto = 0;
            $totalProyectos = 0;
            foreach ($this->proyectos as $proyecto) {
                $type = $proyecto->getType();
                if ($typeProject['Nombre'] === $type) {
                    $gasto += $proyecto->getTotal();
                    $totalProyectos++;                    
                }
            }
            array_push($temporal,array($typeProject['Nombre'] =>$gasto, 'Total' => $totalProyectos));
        }        
        $this->typeProyects = $temporal;
    }

    public function getDatosProyectos() {
        return array();
    }

}
