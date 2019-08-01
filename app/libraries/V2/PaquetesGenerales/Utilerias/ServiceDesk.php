<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;

class ServiceDesk {

    private $url;
    private $FIELDS;
    private $urlUsers;
    private $modeloServiceDesck;

    public function __construct() {
        ini_set('max_execution_time', 300);
        $this->url = "http://mesadeayuda.cinemex.net:8080/sdpapi/request";
        $this->urlUsers = "http://mesadeayuda.cinemex.net:8080/sdpapi/requester/";
//        $this->modeloServiceDesck = \Modelos\Modelo_ServiceDesk::factory();
    }

    public static function getDatos(string $folio) {
        
    }

    public function getDetallesFolio(string $folio) {
        $key = Usuario::getAPIKEY();
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $key;
        $datosSD = json_decode(file_get_contents($this->url . '/' . $folio . '?' . $this->FIELDS));
        return $datosSD;
    }

}
