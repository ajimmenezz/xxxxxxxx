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

    public function getDatosDashboards() {
        $arrayConsultas = array();
        $idPermisos = Usuario::getPermisos();
        $stringPermisos = implode(',', $idPermisos);
        $claves = $this->db->getPermisosDashboard($stringPermisos);

        foreach ($claves as $key => $value) {
            $getConsulta = 'getDatos' . $value['ClavePermiso'];
            array_push($arrayConsultas, $this->$getConsulta(array('nombreConsulta' => $getConsulta, 'cliente' => '1')));
        }

        return $arrayConsultas;
    }

    private function getDatosVGC(array $datos) {
        $arrayComparacion = array();
        $arrayTitulos = array();
        $arrayConsulta = $this->getConsultas(array('numeroSemana' => 4, 'nombreConsulta' => $datos['nombreConsulta']));

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

    public function getDatosVGT(array $datos) {
        $arrayTendencia = array();

        if ($datos['cliente'] === '1') {
            $arrayConsulta = $this->getConsultas(array('numeroSemana' => 4, 'nombreConsulta' => $datos['nombreConsulta']));

            foreach ($arrayConsulta as $key => $value) {
                if (!empty($value)) {
                    array_push($arrayTendencia, array($value[0]['Semana'], $value[0]['Incidentes']));
                }
            }
        }

        return array('VGT' => $arrayTendencia);
    }

    public function getDatosVGHI(array $datos) {
        return array('VGHI' => []);
    }

    public function getDatosVGIP(array $datos) {
        return array('VGIP' => []);
    }

    public function getDatosVGZ(array $datos) {
        return array('VGZ' => []);
    }

    public function getDatosVGTO(array $datos) {
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
