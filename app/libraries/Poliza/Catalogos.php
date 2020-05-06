<?php

namespace Librerias\Poliza;

use Controladores\Controller_Base_General as General;

class Catalogos extends General {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioClasificacionFalla(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_clasificaciones_falla WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioClasificacionFalla', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioTipoFalla(array $datos) {
        $data = array();
        $data['clasificacionFallas'] = $this->catalogo->catClasificacionFallas('3', array('Flag' => '1'));

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_tipos_falla WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioTipoFalla', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioFallaEquipo(array $datos) {
        $data = array();
        $data['tiposFallas'] = $this->consultaTiposFallas();
        $data['equipos'] = $this->catalogo->catConsultaGeneral('SELECT * FROM v_equipos');

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT IdTipoFalla AS IdTipo, IdModeloEquipo AS IdEquipo FROM cat_v3_fallas_equipo WHERE Id = "' . $datos['id'] . '"');
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_fallas_equipo WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioFallaEquipo', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioFallaRefaccion(array $datos) {
        $data = array();
        $data['tiposFallas'] = $this->consultaTiposFallas();
        $data['equipos'] = $this->catalogo->catConsultaGeneral('SELECT * FROM v_equipos');
        $data['componentesEquipos'] = $this->catalogo->catConsultaGeneral('SELECT * FROM cat_v3_componentes_equipo');

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                    cvfr.IdTipoFalla AS IdTipo, 
                                                                    cvfr.IdRefaccion AS IdRefaccion,
                                                                    (SELECT Id FROM v_equipos WHERE Id = cvce.IdModelo) AS IdEquipo
                                                                FROM cat_v3_fallas_refaccion cvfr
                                                                INNER JOIN cat_v3_componentes_equipo cvce
                                                                    ON cvce.Id = cvfr.IdRefaccion WHERE cvfr.Id = "' . $datos['id'] . '"');
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_fallas_refaccion WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioFallaRefaccion', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioSolucionesEquipo(array $datos) {
        $data = array();
        $data['equipos'] = $this->catalogo->catVistaEquipo("3");
        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_soluciones_equipo WHERE Id = "' . $datos['id'] . '"');
            $data['idEquipo'] = $this->catalogo->catConsultaGeneral('SELECT IdModelo FROM cat_v3_soluciones_equipo WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
            $data['idEquipo'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioSolucionesEquipo', $data, TRUE), 'datos' => $data);
    }

    private function consultaTiposFallas() {
        return $this->catalogo->catConsultaGeneral('SELECT 
                                                                    cvtf.Id,
                                                                    cvtf.Nombre,
                                                                    (SELECT Nombre FROM cat_v3_clasificaciones_falla WHERE Id = cvtf.IdClasificacion) Clasificacion
                                                                FROM cat_v3_tipos_falla cvtf');
    }

    public function mostrarFormularioCinemexValidacion(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_cinemex_validadores WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioCinemexValidacion', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioUnidadNegocio(array $datos) {
        $data['clientes'] = $this->catalogo->catClientes(3, array('Flag' => '1'));
        return array('formulario' => parent::getCI()->load->view('Poliza/Formularios/formularioUnidadNegocio', $data, TRUE));
    }

    public function mostrarDatosActualizarUnidadNegocio() {
        $data['clientes'] = $this->catalogo->catClientes(3, array('Flag' => '1'));
        return $data;
    }

    public function getSublienasArea(array $datos) {
        $data = array();
        $sublineasArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' GROUP BY IdArea');
        $sublineas = $this->catalogo->catSublineasEquipo(3, array('Flag' => '1'));

        foreach ($sublineas as $key => $value) {
            $arraySublinea[$key]['id'] = $value['IdSub'];
            $arraySublinea[$key]['text'] = $value['Sublinea'] . ' - ' . $value['Linea'];
        }

        $data['sublineas'] = $arraySublinea;
        $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion();

        if (!empty($sublineasArea)) {
            foreach ($sublineasArea as $key => $value) {
                $arraySulineas = array();
                $arrayCantidad = array();
                $sublineas = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.IdArea = ' . $value['IdArea'] . ' AND cvsa.Flag = 1');

                if (!empty($sublineas)) {
                    foreach ($sublineas as $k => $v) {
                        array_push($arraySulineas, $v['Sublinea']);
                        array_push($arrayCantidad, $v['Cantidad']);
                    }
                }

                $data['IdArea'] = $value['IdArea'];
                $data['Area'] = $value['Area'];
                $data['Sublineas'] = implode('<br>', $arraySulineas);
                $data['Cantidad'] = implode('<br>', $arrayCantidad);
            }
            return array('code' => 200, 'data' => $data);
        } else {
            return array('code' => 200, 'data' => $data);
        }
    }

    public function getCatalogoSelectAreaAtencion() {
        $arrayAreaAtencion = array();
        $areasAtencion = $this->catalogo->catAreasAtencion(3, array('Flag' => '1'));

        foreach ($areasAtencion as $key => $value) {
            $arrayAreaAtencion[$key]['id'] = $value['Id'];
            $arrayAreaAtencion[$key]['text'] = $value['Nombre'];
        }

        return $arrayAreaAtencion;
    }

    public function getSublineas(array $datos) {
        $data = array();
        $arraySublinea = array();
        $arrayIdsSublineasArea = array();
        $sublineas = $this->catalogo->catSublineasEquipo(3, array('Flag' => '1'));
        $contador = 0;
        $data['sublineasArea'] = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.IdArea = ' . $datos['IdArea'] . ' AND cvsa.Flag = 1');

        foreach ($data['sublineasArea'] as $key => $value) {
            array_push($arrayIdsSublineasArea, $value['IdSublinea']);
        }

        foreach ($sublineas as $key => $value) {
            if (!in_array($value['IdSub'], $arrayIdsSublineasArea)) {
                $arraySublinea[$contador]['id'] = $value['IdSub'];
                $arraySublinea[$contador]['text'] = $value['Sublinea'] . ' - ' . $value['Linea'];
                $contador ++;
            }
        }

        $data['sublineas'] = $arraySublinea;

        return array('code' => 200, 'data' => $data);
    }

    public function setSublineas(array $datos) {
        foreach ($datos['sublineas'] as $key => $value) {
            $sublineaArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.IdArea = ' . $datos['IdArea'] . '  AND cvsa.IdSublinea = ' . $value['IdSublinea'] . ' AND cvsa.Flag = 1');
            if (!$sublineaArea) {
                $this->catalogo->catSublineasArea(1, array($datos['IdUnidadNegocio'], $datos['IdArea'], $value['IdSublinea'], $value['Cantidad'], 1));
            } else {
                $this->catalogo->catSublineasArea(2, array($value['Id'], $value['Cantidad']));
            }
        }

        return $this->getSublienasArea($datos);
    }

}
