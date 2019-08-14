<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
use Modelos\Modelo_NodoRedes as Modelo;

class Nodo {

    private $DBNodo;
    private $idServicio;

    public function __construct(string $idServicio) {
        $this->DBNodo = new Modelo();
        $this->idServicio = $idServicio;
    }

    public function setNodo(array $datos) {
        $carpeta = 'Servicios/Servicio-' . $this->idServicio . '/EvidenciaMaterialNodos/';
        Archivo::saveArchivos($carpeta);
        $datos['archivos'] = Archivo::getString();        
        $idNodo = $this->DBNodo->setNodo($this->idServicio, $datos);
        $this->DBNodo->setMaterialNodo($idNodo, $this->getArrayMaterial($datos['material']));
    }

    public function updateNodo(array $datos) {
        
    }

    public function delateNodo(array $datos) {
        
    }

    public function getNodos() {
        return $this->DBNodo->getNodos($this->idServicio);
    }

    private function getArrayMaterial(string $material) {
        $datos = array();
        $arregloMaterial = explode('|', $material);

        foreach ($arregloMaterial as $value) {
            array_push($datos, json_decode($value, true));
        }
        
        return $datos;
    }

}
