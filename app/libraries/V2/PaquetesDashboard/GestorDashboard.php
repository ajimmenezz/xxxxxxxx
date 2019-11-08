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
        $metodoConsulta = $datos['nombreConsulta'];
        $arrayTitulos = $this->titulosArrayGrafica('TIEMPO');
        $arrayComparacion[0] = $arrayTitulos;
        $arrayConsulta = $this->mostrarConsultaVGC($datos);

        foreach ($arrayConsulta as $key => $value) {
            array_push(
                    $arrayComparacion, array($value['Tiempo'],
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema'],
                (int) $value['Cerrado'],
                (int) $value['Cerrado'])
            );
        }

        $this->gestorClientes = new GestorClientes();
        $clientes = $this->gestorClientes->getIdNombreClientes();

        return array('VGC' => $arrayComparacion, 'clientes' => $clientes);
    }

    private function mostrarConsultaVGC(array $datos) {
        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        if (!isset($datos['tipoServicio']) || $datos['tipoServicio'] === '') {
            $whereTipoServicio = "";
        } else {
            $whereTipoServicio = "AND Tipo = '" . $datos['tipoServicio'] . "'";
        }

        $metodoConsulta = 'getDatosVGC' . $datos['tiempo'];

        $arrayConsulta = $this->db->$metodoConsulta(array('numeroTiempo' => 4, 'where' => $whereTipoServicio));

        return $arrayConsulta;
    }

    public function getDatosVGT(array $datos) {
        $arrayTendecia = array();
        $arrayTendencia[0] = ["TIEMPO", "Incidentes", ['role' => 'annotation', 'type' => 'number']];
        $tiposServicios = $this->db->getDatosTiposServicios();

        if ($datos['cliente'] === '1') {
            $arrayConsulta = $this->mostrarConsultaVGT($datos);
            foreach ($arrayConsulta as $key => $value) {
                if (!empty($value)) {
                    $incidentes = (int) $value['Incidentes'];
                    array_push($arrayTendencia, array($value['Tiempo'], $incidentes, $incidentes));
                }
            }
        }

        return array('VGT' => $arrayTendencia, 'tipoServicios' => $tiposServicios);
    }

    private function mostrarConsultaVGT(array $datos) {
        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        if (!isset($datos['zona']) || $datos['zona'] === '') {
            $whereZona = "";
        } else {
            $whereZona = "AND Region = '" . $datos['zona'] . "'";
        }

        $metodoConsulta = 'getDatosVGT' . $datos['tiempo'];
        $arrayConsulta = $this->db->$metodoConsulta(array('numeroTiempo' => 4, 'where' => $whereZona));

        return $arrayConsulta;
    }

    public function getDatosVGHI(array $datos) {
        return array('VGHI' => []);
    }

    public function getDatosVGIP(array $datos) {
        $arrayComparacion = array();
        $arrayTitulos = array();
        $metodoConsulta = $datos['nombreConsulta'];
        $arrayConsulta = $this->mostrarConsultaVGIP($datos);
        $arrayTitulos = ["TIEMPO",
            "Abierto", ['role' => 'annotation', 'type' => 'number'],
            "En Atencion", ['role' => 'annotation', 'type' => 'number'],
            "Problema", ['role' => 'annotation', 'type' => 'number']];
        $arrayComparacion[0] = $arrayTitulos;

        foreach ($arrayConsulta as $key => $value) {
            array_push(
                    $arrayComparacion, array($value['Tiempo'],
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema']
            ));
        }

        return array('VGIP' => $arrayComparacion);
    }

    private function mostrarConsultaVGIP(array $datos) {
        if (!isset($datos['tiempo'])) {
            $datos['tiempo'] = 'WEEK';
        }

        if (!isset($datos['zona']) || $datos['zona'] === '') {
            $whereZona = "";
        } else {
            $whereZona = "AND Region = '" . $datos['zona'] . "'";
        }

        $metodoConsulta = 'getDatosVGIP' . $datos['tiempo'];
        $arrayConsulta = $this->db->$metodoConsulta(array('numeroTiempo' => 4, 'where' => $whereZona));

        return $arrayConsulta;
    }

    public function getDatosVGZ(array $datos) {
        $arrayZonas = array();
        $nombreConsulta = $datos['nombreConsulta'];
        $arrayConsultaZonas = $this->db->$nombreConsulta(array('numeroTiempo' => 0));
        $arrayTitulos = $this->titulosArrayGrafica('REGION');
        $arrayZonas[0] = $arrayTitulos;

        foreach ($arrayConsultaZonas as $key => $value) {
            array_push(
                    $arrayZonas, array($value['Region'],
                (int) $value['Abierto'],
                (int) $value['Abierto'],
                (int) $value['En Atencion'],
                (int) $value['En Atencion'],
                (int) $value['Problema'],
                (int) $value['Problema'],
                (int) $value['Cerrado'],
                (int) $value['Cerrado'])
            );
        }

        return array('VGZ' => $arrayZonas);
    }

    public function getDatosVGTO(array $datos) {
        return array('VGTO' => []);
    }

    private function titulosArrayGrafica(string $tipo) {
        $arrayTitulos = [$tipo,
            "Abierto", ['role' => 'annotation', 'type' => 'number'],
            "En Atencion", ['role' => 'annotation', 'type' => 'number'],
            "Problema", ['role' => 'annotation', 'type' => 'number'],
            "Cerrado", ['role' => 'annotation', 'type' => 'number']];
        return $arrayTitulos;
    }

}
