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
        $this->DBNodo->empezarTransaccion();
        $carpeta = 'Servicios/Servicio-' . $this->idServicio . '/EvidenciaMaterialNodos/';
        Archivo::saveArchivos($carpeta);
        $datos['archivos'] = Archivo::getString();
        $idNodo = $this->DBNodo->setNodo($this->idServicio, $datos);
        $this->DBNodo->setMaterialNodo($idNodo, $this->getArrayMaterial($datos['material']));
        $this->DBNodo->finalizarTransaccion();
    }

    public function updateNodo(array $datos) {
        $informacionNodo = $this->DBNodo->getInformacionNodo($datos['idNodo']);

        if (!empty($informacionNodo)) {
            $this->DBNodo->empezarTransaccion();
            $archivos = explode(',', $informacionNodo[0]['Archivos']);
            $this->deleteArchivos($archivos);
            $this->DBNodo->deleteNodo($datos['idNodo']);
            $carpeta = 'Servicios/Servicio-' . $this->idServicio . '/EvidenciaMaterialNodos/';
            Archivo::saveArchivos($carpeta);
            $datos['archivos'] = Archivo::getString();
            $this->DBNodo->setMaterialNodo($datos['idNodo'], $this->getArrayMaterial($datos['material']));
            $this->DBNodo->finalizarTransaccion();
        }
    }

    public function deleteNodo(string $idNodo) {
        $this->DBNodo->empezarTransaccion();
        $this->DBNodo->deleteNodo($idNodo);
        $this->DBNodo->finalizarTransaccion();
    }

    public function getNodos() {
        return $this->DBNodo->getNodosConMaterial($this->idServicio);
    }

    private function getArrayMaterial(string $material) {
        $datos = array();
        $arregloMaterial = explode('|', $material);

        foreach ($arregloMaterial as $value) {
            array_push($datos, json_decode($value, true));
        }

        return $datos;
    }

    private function deleteArchivos(array $archivos) {
        foreach ($archivos as $value) {
            Archivo::deleteArchivo($value);
        }
    }

}
