<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Detalles
 *
 * @author ABarcenas
 */
class Detalles extends General {

    private $Busqueda;
    private $Servicio;
    private $DBS;

    public function __construct() {
        parent::__construct();
        $this->Busqueda = \Librerias\Generales\Busqueda::factory();
        $this->Servicio = \Librerias\Generales\Servicio::factory();
        $this->DBS = \Modelos\Modelo_EditarSolicitud::factory();
    }

    public function detallesSolicitud(string $solicitud) {
        $datos = [
            'datos' => [$solicitud, '']
        ];
        $busquedaSolicitud = $this->Busqueda->detalles($datos);        
        $datosConversacion = $this->Servicio->getNotasBySolicitud($solicitud);
        $data = [
            'idSolicitud' => $solicitud,
            'solicitud' => $busquedaSolicitud['solicitud'],
            'detalles' => $this->DBS->getDetalleSolicitud($solicitud)[0],
            'servicios' => $this->DBS->getListaServiciosBySolicitud($solicitud),
            'conversacion' => parent::getCI()->load->view("Generales/Modal/conversacionServicio", ['datos' => $datosConversacion], TRUE)
        ];
        return parent::getCI()->load->view('Generales/Detalles/Solicitud', $data);
    }
    
    public function detallesServicio(string $servicio) {
        $datosServicio = $this->Busqueda->detallesServicio($servicio);
        $data = [
            'idServicio' => $servicio,
            'servicio' => $datosServicio,
            'historial' => $this->Busqueda->getHistorialServicio($servicio),
            'conversacion' => $this->Busqueda->getConversacionServicio($servicio),
            'detalles' => $this->DBS->getDetalleSolicitudByServicio($servicio)[0]
        ];

        return parent::getCI()->load->view('Generales/Detalles/Servicio', $data);
    }

}
