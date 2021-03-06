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
            self::$CI = &get_instance();
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
        ini_set('max_execution_time', 90);
        set_time_limit(90);
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

    public function getDatosVGC(array $datos) {
        $arrayComparacion = array();
        $arrayTitulos = $this->titulosArrayGrafica('TIEMPO');
        $arrayComparacion[0] = $arrayTitulos;

        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }
        $datos['numeroTiempo'] = 5;

        $arrayConsulta = $this->db->getDatosVGC($datos);

        foreach ($arrayConsulta as $key => $value) {
            array_push(
                    $arrayComparacion, array(
                $value['Tiempo'],
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema'],
                (int) $value['Cerrado'],
                (int) $value['Cerrado']
                    )
            );
        }

        $this->gestorClientes = new GestorClientes();
        $clientes = $this->gestorClientes->getIdNombreClientes();

        return array('VGC' => $arrayComparacion, 'clientes' => $clientes);
    }

    public function getDatosVGT(array $datos) {
        $arrayTendecia = array();
        $arrayTendencia[0] = ["TIEMPO", "Incidentes", ['role' => 'annotation', 'type' => 'number']];
        $tiposServicios = $this->db->getDatosTiposServicios();

        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }
        $datos['numeroTiempo'] = 5;

        $arrayConsulta = $this->db->getDatosVGT($datos);

        foreach ($arrayConsulta as $key => $value) {
            if (!empty($value)) {
                $incidentes = (int) $value['Incidentes'];
                array_push($arrayTendencia, array($value['Tiempo'], $incidentes, $incidentes));
            }
        }

        return array('VGT' => $arrayTendencia, 'tipoServicios' => $tiposServicios);
    }

    public function getDatosVGHI(array $datos) {
        $arrayConsulta = $this->mostrarConsultaVGHI($datos);
        return array('VGHI' => $arrayConsulta);
    }

    private function mostrarConsultaVGHI(array $datos) {
        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        $metodoConsulta = 'getDatosVGHI' . $datos['tiempo'];
        $arrayConsulta = $this->db->$metodoConsulta($datos);

        return $arrayConsulta;
    }

    public function getDatosVGIP(array $datos) {
        $arrayComparacion = array();
        $arrayTitulos = array();
        $datos['numeroTiempo'] = 5;

        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        $arrayConsulta = $this->db->getDatosVGIP($datos);
        $arrayTitulos = [
            "TIEMPO",
            "Abierto", ['role' => 'annotation', 'type' => 'number'],
            "En Atencion", ['role' => 'annotation', 'type' => 'number'],
            "Problema", ['role' => 'annotation', 'type' => 'number']
        ];
        $arrayComparacion[0] = $arrayTitulos;

        foreach ($arrayConsulta as $key => $value) {
            array_push(
                    $arrayComparacion, array(
                $value['Tiempo'],
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema']
                    )
            );
        }

        return array('VGIP' => $arrayComparacion);
    }

    public function getDatosVGZ(array $datos) {
        $arrayZonas = array();
        $datos['numeroTiempo'] = 5;

        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        $arrayConsultaZonas = $this->db->getDatosVGZ($datos);
        $arrayTitulos = $this->titulosArrayGrafica('REGION');
        $arrayZonas[0] = $arrayTitulos;

        foreach ($arrayConsultaZonas as $key => $value) {
            $titulo = $value['Region'];

            switch ($datos['tiempo']) {
                case 'MONTH':
                    if (isset($datos['month']) && $datos['month'] > 0 && isset($datos['zona']) && $datos['zona'] !== '') {
                        $titulo = $value['Tiempo'];
                    }
                    break;
                case 'WEEK':
                    if (isset($datos['week']) && $datos['week'] > 0 && isset($datos['zona']) && $datos['zona'] !== '') {
                        $titulo = $value['Tiempo'];
                    }
                    break;
            }

            array_push(
                    $arrayZonas, array(
                $titulo,
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema'],
                (int) $value['Cerrado'],
                (int) $value['Cerrado']
                    )
            );
        }

        return array('VGZ' => $arrayZonas);
    }

    public function getDatosVGTO(array $datos) {
        $arrayTop = array();

        if (!isset($datos['reportType'])) {
            $datos['reportType'] = 'branches';
        }

        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        $metodoConsulta = 'getDatosVGTO' . $datos['reportType'];
        $arrayTop[0] = $this->titulosArrayTop($datos['reportType']);
        $arrayConsulta = $this->db->$metodoConsulta($datos);

        foreach ($arrayConsulta as $key => $value) {
            if (!in_array($datos['reportType'], array('lexmark', 'product', 'productline'))) {
                array_push($arrayTop, array(
                    $value[0],
                    (int) $value[1],
                    (int) $value[2],
                    (int) $value[3],
                    (int) $value[4],
                    (int) $value[5],
                    (int) $value[6],
                    (int) $value[7],
                    (int) $value[8]
                ));
            } else {
                array_push($arrayTop, array(
                    $value[0],
                    (int) $value[1],
                    (int) $value[2]
                ));
            }
        }

        return array('VGTO' => $arrayTop);
    }

    private function titulosArrayTop(string $tipoTop) {
        $arrayTitulos = array();
        switch ($tipoTop) {
            case 'branches':
                $arrayTitulos = $this->titulosArrayGrafica('Sucursal');
                break;
            case 'technician':
                $arrayTitulos = $this->titulosArrayGrafica('Tecnicos');
                break;
            case 'lexmark':
            case 'product':
            case 'productline':
                $arrayTitulos = [
                    'Sucursales',
                    "Total", ['role' => 'annotation', 'type' => 'number']
                ];
                break;
        }

        return $arrayTitulos;
    }

    private function titulosArrayGrafica(string $tipo) {
        $arrayTitulos = [
            $tipo,
            "Abierto", ['role' => 'annotation', 'type' => 'number'],
            "En Atencion", ['role' => 'annotation', 'type' => 'number'],
            "Problema", ['role' => 'annotation', 'type' => 'number'],
            "Cerrado", ['role' => 'annotation', 'type' => 'number']
        ];
        return $arrayTitulos;
    }

}
