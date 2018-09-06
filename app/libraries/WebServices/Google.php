<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Google
 *
 * @author Alberto Barcenas
 */
class Google extends General {

    private $Url;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    public function kilometros() {
        $resultado = $this->Url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=40.6655101,-73.89188969999998&destinations=40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626&key=AIzaSyD3ELeFOp0xTOMrj2GDa9xNyzRuSbI-C3s";
        $json = json_decode(@file_get_contents($resultado));
        var_dump($json);
       
    }


}
