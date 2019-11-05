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
        $arrayTitulos = array();
        $arrayConsulta = $this->getConsultas(array('numeroSemana' => '4', 'nombreConsulta' => $getConsulta));

        $arrayTitulos[0] = 'Semana';
        $arrayTitulos[1] = $arrayConsulta[0][0]['EstatusTicketAdIST'];
        $arrayTitulos[2] = $arrayConsulta[0][1]['EstatusTicketAdIST'];
        $arrayTitulos[3] = $arrayConsulta[0][2]['EstatusTicketAdIST'];
        $arrayComparacion[0] = $arrayTitulos;

        foreach ($arrayConsulta as $key => $value) {
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
        $arrayConsulta = $this->getConsultas(array('numeroSemana' => '4', 'nombreConsulta' => $getConsulta));

        foreach ($arrayConsulta as $key => $value) {
            array_push($arrayTendencia, array($value[0]['Semana'], $value[0]['Incidentes']));
        }

        return array('VGT' => $arrayTendencia);
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

    private function getConsultas(array $datos) {
        $arrayConsulta = array();
        $contador = $datos['numeroSemana'];
        $nombreConsulta = $datos['nombreConsulta'];

        while ($contador >= 0) {
            $consulta = $this->db->$nombreConsulta(array('numeroSemana' => $contador));
            array_push($arrayConsulta, $consulta);
            $contador--;
        }

        return $arrayConsulta;
    }

}
