<?php

namespace Librerias\Calendar;

use Controladores\Controller_Base_General as General;
use Google_Client;
use Google_Service_Calendar;

class Calendar extends General {
    
    private $usuario;
    private $DBC;
    
    public function __construct() {
        parent::__construct();
        $this->DBC = \Modelos\Modelo_Calendar::factory();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
    }

    public function MostrarDetallesServicio() {
        $detallesServicio = $this->DBC->obtenerInformacionServicio();
        $today = mdate('%Y-%m-%d', now('America/Mexico_City'));
        $arrayDatosServicio = array();
        
        foreach ($detallesServicio as $servicio) {
            $arrayDatos = array(
                                    'emailSolicita' => $servicio['emailSolicita'],
                                    'emailAtiende' => $servicio['emailAtiende'],
                                    'fechaInicio' => $servicio['fechaInicio'],
                                    'tipoServicio' => $servicio['IdTipoServicio']
                                );
                                array_push($arrayDatosServicio, $arrayDatos);
        }
        return $arrayDatosServicio;
//        foreach ($fechaServicio as $datos) {
//            $dato = substr($datos['FechaCreacion'],0,10);
//            if($dato === $today){
//                var_dump($datos['FechaCreacion']);
//            }else{
//                var_dump("false");
//            }
//        }

    }

}
