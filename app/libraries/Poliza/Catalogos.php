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
        $sublineasArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.Flag = 1 GROUP BY IdArea');
        $sublineas = $this->catalogo->catSublineasEquipo(3, array('Flag' => '1'));
        $areasAtencion = $this->catalogo->catUnidadesNegocioArea(3, [], 'Where IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND Flag = 1');

        foreach ($sublineas as $key => $value) {
            $arraySublinea[$key]['id'] = $value['IdSub'];
            $arraySublinea[$key]['text'] = $value['Sublinea'] . ' - ' . $value['Linea'];
        }

        $data['sublineas'] = $arraySublinea;

        $data['tabla'] = array();

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

                array_push($data['tabla'], array(
                    'IdArea' => $value['IdArea'],
                    'Area' => $value['Area'],
                    'Sublineas' => implode('<br>', $arraySulineas),
                    'Cantidad' => implode('<br>', $arrayCantidad)));
            }

            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);
            return array('code' => 200, 'data' => $data);
        } else {
            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);
            return array('code' => 200, 'data' => $data);
        }
    }

    public function getCatalogoSelectAreaAtencion(array $datos, array $areasAtencion = []) {
        $arrayIdsArea = array();
        $arrayAreaAtencion = array();
        $contador = 0;

        foreach ($datos as $key => $value) {
            array_push($arrayIdsArea, $value['IdArea']);
        }

        foreach ($areasAtencion as $key => $value) {
            if (isset($value['Nombre'])) {
                $idArea = $value['Id'];
            } else {
                $idArea = $value['IdArea'];
            }

            if (!in_array($idArea, $arrayIdsArea)) {
                $arrayAreaAtencion[$contador]['id'] = $idArea;
                if (isset($value['Nombre'])) {
                    $arrayAreaAtencion[$contador]['text'] = $value['Nombre'];
                } else {
                    $arrayAreaAtencion[$contador]['text'] = $value['Area'];
                }
                $contador ++;
            }
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

        if (!empty($sublineas)) {
            foreach ($sublineas as $key => $value) {
                if (!in_array($value['IdSub'], $arrayIdsSublineasArea)) {
                    $arraySublinea[$contador]['id'] = $value['IdSub'];
                    $arraySublinea[$contador]['text'] = $value['Sublinea'] . ' - ' . $value['Linea'];
                    $contador ++;
                }
            }
        } else {
            throw new \Exception('No existen sublineas registradas para esta unidad de negocio y àrea de atención');
        }

        $data['sublineas'] = $arraySublinea;

        return array('code' => 200, 'data' => $data);
    }

    public function setSublineas(array $datos) {
        foreach ($datos['sublineas'] as $key => $value) {
            $sublineaArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.IdArea = ' . $datos['IdArea'] . '  AND cvsa.IdSublinea = ' . $value['IdSublinea']);

            if (!$sublineaArea) {
//                var_dump($value);
                $this->catalogo->catSublineasArea(1, array($datos['IdUnidadNegocio'], $datos['IdArea'], $value['IdSublinea'], $value['Cantidad']));
            } else {
                if ($sublineaArea[0]['Flag'] === '0') {
                    $this->catalogo->catSublineasArea(4, array('IdUnidadNegocio' => $datos['IdUnidadNegocio'], 'IdArea' => $datos['IdArea'], 'IdSublinea' => $value['IdSublinea']), '1');
                    $this->catalogo->catSublineasArea(2, array($sublineaArea[0]['Id'], $value['Cantidad']));
                } else {
                    $this->catalogo->catSublineasArea(2, array($value['Id'], $value['Cantidad']));
                }
            }
        }

        return $this->getSublienasArea($datos);
    }

    public function getAreasSublineas(array $datos) {
        $data = array();
        $arraySublinea = array();
        $sublineasArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.Flag = 1 GROUP BY IdArea');
        $contador = 0;

        if (!empty($sublineasArea)) {
            foreach ($sublineasArea as $key => $value) {
                $arraySublinea[$contador]['id'] = $value['IdArea'];
                $arraySublinea[$contador]['text'] = $value['Area'];
                $contador ++;
            }

            $data['areasAtencion'] = $arraySublinea;

            return array('code' => 200, 'data' => $data);
        } else {
            throw new \Exception('No hay áreas de atención para eliminiar');
        }
    }

    public function getSublineasSelectEliminar(array $datos) {
        $data = array();
        $arraySublinea = array();
        $sublineasArea = $this->catalogo->catSublineasArea(3, [], 'WHERE cvsa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvsa.IdArea = ' . $datos['IdArea'] . ' AND cvsa.Flag = 1');
        $contador = 0;

        if (!empty($sublineasArea)) {
            foreach ($sublineasArea as $key => $value) {
                $arraySublinea[$contador]['id'] = $value['Id'];
                $arraySublinea[$contador]['text'] = $value['Sublinea'] . ' - ' . $value['Linea'];
                $contador ++;
            }

            $data['sublineas'] = $arraySublinea;

            return array('code' => 200, 'data' => $data);
        } else {
            throw new \Exception('No hay sublineas para eliminiar');
        }
    }

    public function flagSublineaArea(array $datos) {
        if (isset($datos['IdSublinea'])) {
            $this->catalogo->catSublineasArea(4, array('Id' => $datos['IdSublinea']), '0');
            return $this->getSublineas($datos);
        } else {
            $this->catalogo->catSublineasArea(4, $datos, '0');
            return $this->getSublienasArea($datos);
        }
    }

    public function getModelosArea(array $datos) {
        $data = array();
        $arrayModelosArea = $this->catalogo->catModelosArea(3, [], 'AND cvmxa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvmxa.Flag = 1 GROUP BY IdArea');
        $datos['IdArea'] = 3;
        $areasAtencion = $this->catalogo->catUnidadesNegocioArea(3, [], 'Where IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND Flag = 1');
        $data['tabla'] = array();

        if (!empty($arrayModelosArea)) {
            foreach ($arrayModelosArea as $key => $value) {
                $arrayModelos = array();
                $modelosArea = $this->catalogo->catModelosArea(3, [], 'AND cvmxa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvmxa.IdArea = ' . $value['IdArea'] . ' AND cvmxa.Flag = 1');

                if (!empty($modelosArea)) {
                    foreach ($modelosArea as $k => $v) {
                        array_push($arrayModelos, $v['Modelo']);
                    }
                }

                array_push($data['tabla'], array(
                    'IdArea' => $value['IdArea'],
                    'Area' => $value['Area'],
                    'Modelos' => implode('<br>', $arrayModelos)));
            }

            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);

            return array('code' => 200, 'data' => $data);
        } else {
            if(empty($areasAtencion)){
                throw new \Exception('No hay áreas de atención para esta unidad de negocio');
            }
            
            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);
            return array('code' => 200, 'data' => $data);
        }
    }

    public function getModelos(array $datos) {
        $data = array();
        $arrayModelo = array();
        $arrayIdsModelosArea = array();
        $modelos = $this->consultaModelos($datos);
        $contador = 0;
        $data['modelosArea'] = $this->catalogo->catModelosArea(3, [], 'AND cvmxa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvmxa.IdArea = ' . $datos['IdArea'] . ' AND cvmxa.Flag = 1');

        if(empty($data['modelosArea'])){
            throw new \Exception('No existen modelos para esa área de atención');
        }
        
        foreach ($data['modelosArea'] as $key => $value) {
            array_push($arrayIdsModelosArea, $value['IdModelo']);
        }

        foreach ($modelos as $key => $value) {
            if (!in_array($value['Id'], $arrayIdsModelosArea)) {
                $arrayModelo[$contador]['id'] = $value['Id'];
                $arrayModelo[$contador]['text'] = $value['Equipo'];
                $contador ++;
            }
        }

        $data['modelos'] = $arrayModelo;

        return array('code' => 200, 'data' => $data);
    }

    public function setModelos(array $datos) {
        foreach ($datos['modelos'] as $key => $value) {
            $modeloArea = $this->catalogo->catModelosArea(3, [], 'AND cvmxa.IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND cvmxa.IdArea = ' . $datos['IdArea'] . '  AND cvmxa.IdModelo = ' . $value['IdModelo']);

            if (!$modeloArea) {
                $this->catalogo->catModelosArea(1, array($datos['IdUnidadNegocio'], $datos['IdArea'], $value['IdModelo'], 1));
            } else {
                if ($modeloArea[0]['Flag'] === '0') {
                    $this->catalogo->catModelosArea(2, array('IdUnidadNegocio' => $datos['IdUnidadNegocio'], 'IdArea' => $datos['IdArea'], 'IdModelo' => $value['IdModelo']), '1');
                }
            }
        }

        return $this->getModelosArea($datos);
    }

    public function getModelosAreasSelectEliminar(array $datos) {
        $data = array();
        $arrayAreas = array();
        $modelosArea = $this->catalogo->catModelosArea(3, [], 'WHERE IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND Flag = 1 GROUP BY IdArea');
        $contador = 0;

        if (!empty($modelosArea)) {
            foreach ($modelosArea as $key => $value) {
                $arrayAreas[$contador]['id'] = $value['IdArea'];
                $arrayAreas[$contador]['text'] = $value['Area'];
                $contador ++;
            }

            $data['areasAtencion'] = $arrayAreas;

            return array('code' => 200, 'data' => $data);
        } else {
            throw new \Exception('No hay áreas de atención para eliminiar');
        }
    }

    public function getModelosSelectEliminar(array $datos) {
        $data = array();
        $arrayModelo = array();
        $modelosArea = $this->catalogo->catModelosArea(3, [], 'WHERE IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND IdArea = ' . $datos['IdArea'] . ' AND Flag = 1');
        $contador = 0;

        if (!empty($modelosArea)) {
            foreach ($modelosArea as $key => $value) {
                $arrayModelo[$contador]['id'] = $value['IdModelo'];
                $arrayModelo[$contador]['text'] = $value['Modelo'];
                $contador ++;
            }

            $data['modelos'] = $arrayModelo;

            return array('code' => 200, 'data' => $data);
        } else {
            throw new \Exception('No hay áreas de atención para eliminiar');
        }
    }

    public function FlagModeloArea(array $datos) {
        $this->catalogo->catModelosArea(2, $datos, '0');

        if (isset($datos['IdModelo'])) {
            return $this->getModelos($datos);
        } else {
            return $this->getModelosArea($datos);
        }
    }

    public function consultaModelos(array $datos) {
        return $this->catalogo->catConsultaGeneral("select 
                                                        cme.Id,
                                                        modelo(cme.Id) as Equipo
                                                        from cat_v3_modelos_equipo cme
                                                        where Flag = 1
                                                        and sublineaByModelo(cme.Id) in (
                                                                (select IdSublinea from cat_v3_sublineas_x_area where IdArea = " . $datos['IdArea'] . " and IdUnidadNegocio = " . $datos['IdUnidadNegocio'] . " AND Flag = 1)
                                                        )");
    }

    public function getUnidadesArea(array $datos) {
        $data = array();
        $unidadesArea = $this->catalogo->catUnidadesNegocioArea(3, [], 'WHERE IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND Flag = 1 GROUP BY IdArea');
        $areasAtencion = $this->catalogo->catAreasAtencion(3, array('Flag' => '1'));
        $data['tabla'] = array();

        if (!empty($unidadesArea)) {
            foreach ($unidadesArea as $key => $value) {
                array_push($data['tabla'], array(
                    'IdArea' => $value['IdArea'],
                    'Area' => $value['Area']));
            }

            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);

            return array('code' => 200, 'data' => $data);
        } else {
            $data['areasAtencion'] = $this->getCatalogoSelectAreaAtencion($data['tabla'], $areasAtencion);
            return array('code' => 200, 'data' => $data);
        }
    }

    public function setUnidadesArea(array $datos) {
        foreach ($datos['areas'] as $key => $value) {
            $unidadesArea = $this->catalogo->catUnidadesNegocioArea(3, [], 'WHERE IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND IdArea = ' . $value['Id']);

            if (!$unidadesArea) {
                $this->catalogo->catUnidadesNegocioArea(1, array($datos['IdUnidadNegocio'], $value['Id'], 1));
            } else {
                if ($unidadesArea[0]['Flag'] === '0') {
                    $this->catalogo->catUnidadesNegocioArea(2, array('IdUnidadNegocio' => $datos['IdUnidadNegocio'], 'IdArea' => $value['Id']), '1');
                }
            }
        }

        return $this->getUnidadesArea($datos);
    }

    public function getUnidadesAreasSelectEliminar(array $datos) {
        $data = array();
        $arrayAreas = array();
        $unidadesArea = $this->catalogo->catUnidadesNegocioArea(3, [], 'WHERE IdUnidadNegocio = ' . $datos['IdUnidadNegocio'] . ' AND Flag = 1 GROUP BY IdArea');
        $contador = 0;

        if (!empty($unidadesArea)) {
            foreach ($unidadesArea as $key => $value) {
                $arrayAreas[$contador]['id'] = $value['IdArea'];
                $arrayAreas[$contador]['text'] = $value['Area'];
                $contador ++;
            }

            $data['areasAtencion'] = $arrayAreas;

            return array('code' => 200, 'data' => $data);
        } else {
            throw new \Exception('No hay áreas de atención para eliminiar');
        }
    }

    public function flagUnidadArea(array $datos) {
        $this->catalogo->catUnidadesNegocioArea(2, $datos, '0');
        return $this->getUnidadesArea($datos);
    }

}
