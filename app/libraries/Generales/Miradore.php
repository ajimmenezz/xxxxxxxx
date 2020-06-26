<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

class Miradore extends General {

    private $_Url;
    private $_key;
    private $DB;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->_Url = 'https://siccobsolutions.online.miradore.com/API/';
        $this->_key = '2_{QpmU8llNHx,mxk';
        $this->DB = \Modelos\Modelo_Correo::factory();
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    public function getMiradoreInfo() {
        $query = "InvDevice.Model,InvDevice.IMEI,InvDevice.WifiMac,Tag.Name,User.Email,User.LastName,User.FirstName,ReportedLocation.*,InvSim.PhoneNumber";
        $options = "dateformat=yyyy/MM/dd%20HH:mm:ss";
        $getUrl = $this->_Url . "Device?auth=" . $this->_key . "&select=" . $query . "&options=" . $options;

        $opts = array(
            'http' => array(
                "method" => "GET"
            )
        );

        $context = stream_context_create($opts);
        $result = file_get_contents($getUrl, false, $context);

        $xml = simplexml_load_string($result);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $fotosMail = $this->DB->obtenerCorreoFoto();

        return ['data' => $array, 'fotos' => $fotosMail];
    }

}
