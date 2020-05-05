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

    public function mostrarTablaSublienasArea(array $datos) {
        $data = array();

//        $data['flag'] = $this->getSublineasArea($array);



        return array('formulario' => parent::getCI()->load->view('Poliza/SublineasArea', $data, TRUE), 'datos' => $data);
    }
    
//    public function getSublineasArea(array $datos){
//        $arrayRespuesta = array();
//        $this->catalogo->catConsultaGeneral('    SELECT 
//    sublinea(IdSublinea) AS Sublinea
//FROM
//    cat_v3_sublineas_x_area
//WHERE
//    IdUnidadNegocio = 2 AND IdArea = 1');
//        return $arrayRespuesta;
//    }

}
