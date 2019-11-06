<?php

namespace Librerias\V2\PaquetesDashboard;

use Modelos\Modelo_GestorDashboard as Modelo;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Librerias\V2\PaquetesGenerales\Utilerias\GestorClientes as GestorClientes;
use CI_Controller;

class GestorDashboard {

    private $db;
    static private $CI;
    private $gestorClientes;

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

    public function getDatosDashboards(string $cliente) {
        $arrayConsultas = array();

        if ($cliente === '1') {
            $idPermisos = Usuario::getPermisos();
            $stringPermisos = implode(',', $idPermisos);
            $claves = $this->db->getPermisosDashboard($stringPermisos);

            foreach ($claves as $key => $value) {
                $getConsulta = 'getDatos' . $value['ClavePermiso'];
                array_push($arrayConsultas, $this->$getConsulta($getConsulta));
            }
        }

        return $arrayConsultas;
    }

    private function getDatosVGC(string $getConsulta) {
        $arrayComparacion = array();
        $arraySemanas = array();
        $arrayTitulos = array();
        $contador = 5;

        while ($contador >= 1) {
            $consulta = $this->db->$getConsulta(array('numeroSemana' => $contador));
            array_push($arraySemanas, $consulta);
            $contador--;
        }

        $arrayTitulos[0] = 'Semana';
        $arrayTitulos[1] = $arraySemanas[0][0]['EstatusTicketAdIST'];
        $arrayTitulos[2] = $arraySemanas[0][1]['EstatusTicketAdIST'];
        $arrayTitulos[3] = $arraySemanas[0][2]['EstatusTicketAdIST'];
        $arrayComparacion[0] = $arrayTitulos;

        foreach ($arraySemanas as $key => $value) {
            foreach ($value as $k => $v) {
                $arrayComparacion[$key + 1][0] = $value[0]['Semana'];
                $arrayComparacion[$key + 1][$k + 1] = (int) $v['SumaEstatus'];
            }
        }

        $this->gestorClientes = new GestorClientes();
        $clientes = $this->gestorClientes->getIdNombreClientes();

        return array('VGC' => $arrayComparacion, 'clientes' => $clientes);
    }

    private function getDatosVGT(string $getConsulta) {
        $arrayTendencia = array();
        $consulta = $this->db->$getConsulta([]);

        foreach ($consulta as $key => $value) {
            array_push($arrayTendencia, array($value['Semana'], $value['Incidentes']));
        }

        return array('VGT' => [
                    ["SEMANA", "Incidentes",['role'=> 'annotation', 'type'=> 'number'], "Completados",['role'=> 'annotation', 'type'=> 'number']],
                    ["SEMANA 41", 821, 821,10,10],
                    ["SEMANA 42", 854, 821,10,10],
                    ["SEMANA 43", 915, 821,10,10],
                    ["SEMANA 44", 669, 821,10,10],
                    ["SEMANA 45", 484, 821,10,10]
                ]);
    }

    private function getDatosVGHI(string $getConsulta) {
        return array('VGHI' => []);
    }

    private function getDatosVGIP(string $getConsulta) {
        return array('VGIP' => []);
    }

    private function getDatosVGZ(string $getConsulta) {
        return array('VGZ' => []);
    }

    private function getDatosVGTO(string $getConsulta) {
        return array('VGTO' => []);
    }
    
    

}
