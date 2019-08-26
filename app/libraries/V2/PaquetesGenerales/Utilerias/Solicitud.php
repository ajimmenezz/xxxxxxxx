<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

//use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
//use Modelos\Modelo_NodoRedes as Modelo;

class Solicitud {

//    private $DBNodo;
//    private $idServicio;

    public function __construct(string $idServicio) {
//        $this->DBNodo = new Modelo();
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

}
