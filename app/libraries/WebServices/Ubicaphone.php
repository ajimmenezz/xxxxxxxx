<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of ServiceDesck
 *
 * @author Alonso
 */
class Ubicaphone extends General {

    private $Url;
    private $token;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->token = $this->getTokenUbicaphone();
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    private function getTokenUbicaphone() {
        $this->Url = "http://ubicaphone.com/ubicaphone/api/public/ubicaphone/login?acc=ajimenez@siccob.com.mx&pwd=5829&usr=android_apps";

        $opts = array(
            'http' => array(
                "method" => "GET",
                "header" => "Content-type: application/json"
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($this->Url, false, $context), true);

        return $result['data'][0]['token'];
    }

    public function getAllDevices() {
        $this->Url = "http://ubicaphone.com/ubicaphone/api/public/report/last/all";
        $opts = array(
            'http' => array(
                "method" => "GET",
                "header" => "Content-type: application/json\r\n"
                . "Authorization: Bearer $this->token\r\n"
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($this->Url, false, $context), true);

        return $result['data'];
    }

    public function getGeofenceActivations(array $data) {

        $get = '?xxx=1';

        $imei = ($data['imei'] !== '') ? '/' . $data['imei'] : '';
        $from = ($data['from'] !== '') ? '&fromtimestamp=' . $data['from'] : '';
        $to = ($data['to'] !== '') ? '&tilltimestamp=' . $data['to'] : '';

        $get = $get . $from . $to;

        $this->Url = "http://ubicaphone.com/ubicaphone/api/public/reports/geofences" . $imei . $get;
        $opts = array(
            'http' => array(
                "method" => "GET",
                "header" => "Content-type: application/json\r\n"
                . "Authorization: Bearer $this->token\r\n"
            )
        );

        $context = stream_context_create($opts);
        $result = json_decode(file_get_contents($this->Url, false, $context), true);

        return $result['data'];
    }

}
