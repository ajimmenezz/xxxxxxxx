<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
use Modelos\Modelo_NodoRedes as Modelo;

class GestorNodosRedes {

    private $DBNodo;
    private $idServicio;

    public function __construct(string $idServicio) {
        $this->DBNodo = new Modelo();
        $this->idServicio = $idServicio;
    }

    public function setNodo(array $datos) {
        $this->DBNodo->empezarTransaccion();
        if ($datos['evidencias'] === 'true') {
            $carpeta = 'Servicios/Servicio-' . $this->idServicio . '/EvidenciaMaterialNodos/';
            Archivo::saveArchivos($carpeta);
            $datos['archivos'] = Archivo::getString();
        } else {
            $datos['archivos'] = '';
        }
        $idNodo = $this->DBNodo->setNodo($this->idServicio, $datos);
        $this->DBNodo->setMaterialNodo($idNodo, $this->getArrayMaterial($datos['material']));
        $this->DBNodo->finalizarTransaccion();
    }

    public function updateNodo(array $datos) {
        $this->DBNodo->empezarTransaccion();
        $this->DBNodo->deleteMaterialNodo($datos['idNodo']);
        if ($datos['evidencias'] === 'true') {
            $carpeta = 'Servicios/Servicio-' . $this->idServicio . '/EvidenciaMaterialNodos/';
            Archivo::saveArchivos($carpeta);
            $datos['archivos'] = Archivo::getString();
        }
        $this->DBNodo->updateNodo($datos);
        $this->DBNodo->setMaterialNodo($datos['idNodo'], $this->getArrayMaterial($datos['material']));
        $this->DBNodo->finalizarTransaccion();
    }

    public function deleteNodo(string $idNodo) {
        $this->DBNodo->empezarTransaccion();
        $informacionNodo = $this->DBNodo->getInformacionNodo($idNodo);
        if (!empty($informacionNodo[0]['Archivos'])) {
            $archivos = explode(',', $informacionNodo[0]['Archivos']);
            $this->deleteArchivos($archivos);
        }
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

    public function getTotalMaterial() {
        return $this->DBNodo->getTotalMaterial($this->idServicio);
    }

    public function deleteArchivo(array $datos) {
        $this->DBNodo->deleteArchivo($this->idServicio, $datos);
    }

}
