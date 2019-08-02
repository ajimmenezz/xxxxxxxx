<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_ServiceDesk as Modelo;

class ServiceDesk {

    static private $url;
    static private $FIELDS;
    static private $urlUsers;
    static private $DBServiceDesk;

    static private function setVariables() {
        ini_set('max_execution_time', 300);
        self::$url = "http://mesadeayuda.cinemex.net:8080/sdpapi/request";
        self::$urlUsers = "http://mesadeayuda.cinemex.net:8080/sdpapi/requester/";
        self::$DBServiceDesk = new Modelo();
    }

    static public function getDatos(string $folio) {
        
    }

    static public function getDetallesFolio(string $folio) { 
        self::setVariables();
        $key = Usuario::getAPIKEY();
        self::$FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $key;
        $datosSD = json_decode(file_get_contents(self::$url . '/' . $folio . '?' . self::$FIELDS));
        return $datosSD;
    }

}
