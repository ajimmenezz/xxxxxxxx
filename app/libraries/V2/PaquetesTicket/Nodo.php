<?php

namespace Librerias\V2\PaquetesTicket;

use Modelos\Modelo_NodoRedes as Modelo;

class Nodo {

    private $DBNodo;
    private $idServicio;

    public function __construct(string $idServicio) {
        $this->DBNodo = new Modelo();
        $this->idServicio = $idServicio;
    }

    public function setNodo(array $datos) {
        $datos['archivos'] = $this->saveArchivos($datos['id']);
        $idNodo = $this->DBNodo->setNodo($this->idServicio, $datos);
        $this->DBNodo->setMaterialNodo($idNodo, $this->getArrayMaterial($datos['material']));
    }

    public function updateNodo(array $datos) {
        
    }

    public function delateNodo(array $datos) {
        
    }

    public function getNodos() {
        
    }

    private function saveArchivos(string $idServicio) {
        $CI = & get_instance();
        $CI->load->helper('fileupload');
        $nombre = '';
        foreach ($_FILES as $key => $value) {
            $nombre = $key;
        }
        $carpeta = 'Servicios/Servicio-' . $idServicio . '/EvidenciaMaterialNodos/';
        $archivos = implode(',', setMultiplesArchivos($CI, $nombre, $carpeta));
        return $archivos;
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
