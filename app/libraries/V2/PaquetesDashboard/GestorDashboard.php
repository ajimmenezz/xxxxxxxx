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
        $arrayConsulta = $this->getConsultas(array('numeroTiempo' => 4, 'nombreConsulta' => $datos['nombreConsulta']));
        $arrayTitulos[0] = 'Tiempo';

        foreach ($arrayConsulta as $key => $value) {
            foreach ($value as $k => $v) {
                if (!in_array($v['EstatusTicketAdIST'], $arrayTitulos)) {
                    array_push($arrayTitulos, $v['EstatusTicketAdIST']);
                    array_push($arrayTitulos, ['role' => 'annotation', 'type' => 'number']);
                }
            }
        }

        $arrayComparacion[0] = $arrayTitulos;
        $contador = 0;
        $contadorArreglo = 1;

        while ($contador < 2) {
            foreach ($arrayConsulta as $key => $value) {
                $contadorArregloAdentro = $contadorArreglo;
                foreach ($value as $k => $v) {
                    if ($contador === 0) {
                        $arrayComparacion[$key + $contador + 1][$contador] = $value[0]['Tiempo'];
                    }
                    $arrayComparacion[$key + 1][$k + $contadorArregloAdentro] = (int) $v['SumaEstatus'];
                    $contadorArregloAdentro = $contadorArregloAdentro + 1;
                }
                ksort($arrayComparacion[k+1]);
            }
            $contador ++;
            $contadorArreglo ++;
        }

        $this->gestorClientes = new GestorClientes();
        $clientes = $this->gestorClientes->getIdNombreClientes();

        return array('VGC' => $arrayComparacion, 'clientes' => $clientes);
    }

    public function getDatosVGT(array $datos) {
        $arrayTendecia = array();
        $arrayTendencia[0] = ["TIEMPO", "Incidentes", ['role' => 'annotation', 'type' => 'number']];

        if ($datos['cliente'] === '1') {
            $arrayConsulta = $this->mostrarConsultaVGT($datos);

            foreach ($arrayConsulta as $key => $value) {
                if (!empty($value)) {
                    $incidentes = (int) $value[0]['Incidentes'];
                    array_push($arrayTendencia, array($value[0]['Tiempo'], $incidentes, $incidentes));
                }
            }
        }

        return array('VGT' => $arrayTendencia);
    }

    private function mostrarConsultaVGT(array $datos) {
        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                $arrayConsulta = $this->getConsultas(array('numeroTiempo' => 4, 'nombreConsulta' => 'getDatosVGTMes'));
                break;
            case 'WEEK':
                $arrayConsulta = $this->getConsultas(array('numeroTiempo' => 4, 'nombreConsulta' => 'getDatosVGTSemana'));
                break;
            case 'YEAR':
                $arrayConsulta = $this->getConsultas(array('numeroTiempo' => 4, 'nombreConsulta' => 'getDatosVGTAnual'));
                break;
            default :
                $arrayConsulta = $this->getConsultas(array('numeroTiempo' => 4, 'nombreConsulta' => $datos['nombreConsulta']));
                break;
        }

        return $arrayConsulta;
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
        $contador = $datos['numeroTiempo'];
        $nombreConsulta = $datos['nombreConsulta'];

        while ($contador >= 0) {
            $consulta = $this->db->$nombreConsulta(array('numeroTiempo' => $contador));
            array_push($arrayConsulta, $consulta);
            $contador--;
        }

        return $arrayConsulta;
    }

}
