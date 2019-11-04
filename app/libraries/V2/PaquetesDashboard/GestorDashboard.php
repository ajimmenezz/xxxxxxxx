<?php

namespace Librerias\V2\PaquetesDashboard;

use Modelos\Modelo_GestorDashboard as Modelo;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use CI_Controller;

class GestorDashboard {

    private $db;
    static private $CI;

    public function __construct() {
        $this->db = new Modelo();
        $this->setCI();
    }

    private function setCI() {
        if (empty(self::$CI)) {
            self::$CI = & get_instance();
        }
    }

    public function getDashboards() {
        $dashboars = array();
        $arrayClaves = array();
        $idPermisos = Usuario::getPermisos();
        $permisos = implode(',', $idPermisos);
        $consultaClaves = $this->db->getClavesPermisos($permisos);

        foreach ($consultaClaves as $key => $value) {
            array_push($arrayClaves, $value['Permiso']);
        }

        $stringClaves = implode("','", $arrayClaves);
        $consultaVistas = $this->db->getVistasDashboards($stringClaves);

        foreach ($consultaVistas as $key => $value) {
            array_push($dashboars, self::$CI->load->view($value['VistaHtml'], '', TRUE));
        }

        return $dashboars;
    }

    public function getDatosDashboards() {
        $arrayConsultas = array();
        $idPermisos = Usuario::getPermisos();
        $stringPermisos = implode(',', $idPermisos);
        $claves = $this->db->getPermisosDashboard($stringPermisos);

        foreach ($claves as $key => $value) {
            $getConsulta = 'getDatos' . $value['ClavePermiso'];
            array_push($arrayConsultas, $this->$getConsulta($getConsulta));
        }

        return $arrayConsultas;
    }

    public function getDatosVGC(string $getConsulta) {
//        $arrayTitulos = array();
//            array_push($arrayTitulos, array($value['Semana']));
        return array('VGC' => []);
    }
    
    public function getDatosVGT(string $getConsulta) {
        $arrayTendencia = array();
        $consulta = $this->db->$getConsulta([]);
        
        foreach ($consulta as $key => $value) {
            array_push($arrayTendencia, array($value['Semana'],$value['Incidentes']));
        }
                
        return array('VGT' => $arrayTendencia);
    }

    public function getDatosVGHI(string $getConsulta) {
        return array('VGHI' => []);
    }

    public function getDatosVGIP(string $getConsulta) {
        return array('VGIP' => []);
    }

    public function getDatosVGZ(string $getConsulta) {
        return array('VGZ' => []);
    }

    public function getDatosVGTO(string $getConsulta) {
        return array('VGTO' => []);
    }

}
