<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Calendario
 *
 * @author Freddy
 */
class Calendario extends General {

    private $DBN;
    private $Correo;
    private $Catalogo;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->DBC = \Modelos\Modelo_Calendario::factory();
        $this->usuario = $this->Usuario->getDatosUsuario();
        parent::getCI()->load->helper('date');
    }

    public function getServiciosCalendario($datos) {
        $datos = $this->DBC->getServiciosCalendario($this->usuario['Id'], $datos);
        return $datos;
    }

    public function getCalendarAreasPermissions() {
        $permisos = $this->DBC->getCalendarAreasPermissions($this->usuario['Id']);
        return $permisos;
    }

    public function getDetallesServicioCalendario($datos) {
        $datosServicio = $this->DBC->getDatosServicioCalendario($datos['servicio']);
        $html = parent::getCI()->load->view("Generales/Modal/servicioCalendario", ['datos' => $datosServicio], TRUE);

        return ['html' => $html];
    }
    
    public function actualizaTentativa($datos) {
        $datosServicio = $this->DBC->actualizaTentativa($datos);        
        return $datosServicio;
    }

}
