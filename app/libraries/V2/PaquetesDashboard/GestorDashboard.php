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
            self::$CI =& get_instance();
        }     
    }
    
    public function getDashboards() {
        $permisos = Usuario::getPermisos();
        $vistas = $this->db->getVistasDashboards(array());
        $dashboars = array();
        
        foreach ($vistas as $value) {
            array_push($dashboars, self::$CI->load->view($value,'',TRUE));
        }
        return $dashboars;
    }
}
