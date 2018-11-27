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
    private $db;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->token = $this->getTokenUbicaphone();
        $this->db = \Modelos\Modelo_SegundoPlano::factory();
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

    public function getDeviceRoute(array $data) {
        $get = '?imei=' . $data['imei'] . '&from=' . $data['from'] . '&till=' . $data['till'];

        $this->Url = "http://ubicaphone.com/ubicaphone/api/public/report/historical" . $get;
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

    public function getDistanceBetweenTwoPoints($point1, $point2) {
        // array of lat-long i.e  $point1 = [lat,long]
        $earthRadius = 6371;  // earth radius in km
        $point1Lat = $point1[0];
        $point2Lat = $point2[0];
        $deltaLat = deg2rad($point2Lat - $point1Lat);
        $point1Long = $point1[1];
        $point2Long = $point2[1];
        $deltaLong = deg2rad($point2Long - $point1Long);
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos(deg2rad($point1Lat)) * cos(deg2rad($point2Lat)) * sin($deltaLong / 2) * sin($deltaLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        return $distance * 1000;    // in m
    }

    public function cargaDispositivosGlobal() {
        $dispositivos = $this->db->consulta("select 
                                            IMEI,
                                            nombreUsuario(Id) as Usuario 
                                            from cat_v3_usuarios 
                                            where IMEI <> ''
                                            order by Usuario");
        return $dispositivos;
    }

    public function getRoutebyUniversal(array $datos) {
        $from = strtotime("2018-11-12 00:00:00");
        $to = strtotime("2018-11-12 23:59:59");
        $data = [
            'imei' => $datos['imei'],
            'from' => $from,
            'till' => $to
        ];

        $result = $this->getDeviceRoute($data);

        $cadena = "https://www.google.com.mx/maps/dir/";
        $cont = 0;
        $ultDistancia = [];
        foreach ($result as $key => $value) {
            $cont++;
            if (empty($ultDistancia)) {
                $ultDistancia = [$value['lat'], $value['lng']];
            }
            $distancia = $this->getDistanceBetweenTwoPoints($ultDistancia, [$value['lat'], $value['lng']]);
            $ultDistancia = [$value['lat'], $value['lng']];

            if ($distancia > 200 && $value['lat'] != 0) {
                $cadena .= "'" . $value['lat'] . "," . $value['lng'] . "'/";
            }
        }

        return $cadena;
    }

    public function detallesDispositivo(array $datos) {
        $data = [
            'url' => $this->getRoutebyUniversal(['imei' => $datos['imei']])
        ];        
        return [
            'html' => parent::getCI()->load->view('Localizacion/DetallesDispositivo', $data, TRUE)
        ];
    }

}
