<?php

namespace Librerias\Proyectos2;

use Controladores\Controller_Base_General as General;

class Catalogos extends General {

    private $DB;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Proyectos2::factory();
    }

    public function getSistemas() {
        $sistemas = $this->DB->getSistemas();
        return $sistemas;
    }

    public function agregarSistema(array $datos) {
        if (!isset($datos['sistema'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del sistema. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarSistema(mb_strtoupper($datos['sistema']));
            if (!is_null($insert['id'])) {
                return [
                    'code' => 200,
                    'id' => $insert['id'],
                    'sistema' => mb_strtoupper($datos['sistema'])
                ];
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function formularioEditarSistema(array $datos) {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del sistema. Intente de nuevo'
            ];
        } else {
            $sistema = $this->DB->getSistemas($datos['id']);
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarSistema', ['data' => $sistema[0]], TRUE)
            ];
        }
    }

    public function editarSistema(array $datos) {
        if (!isset($datos['sistema']) || !isset($datos['id']) || !isset($datos['estatus'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarSistema($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getTipos() {
        $tipos = $this->DB->getTipos();
        return $tipos;
    }

    public function agregarTipo(array $datos) {
        if (!isset($datos['tipo'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del Tipo de Proyecto. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarTipo(mb_strtoupper($datos['tipo']));
            if (!is_null($insert['id'])) {
                return [
                    'code' => 200,
                    'id' => $insert['id'],
                    'tipo' => mb_strtoupper($datos['tipo'])
                ];
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function formularioEditarTipo(array $datos) {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del Tipo de Proyecto. Intente de nuevo'
            ];
        } else {
            $tipo = $this->DB->getTipos($datos['id']);
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarTipo', ['data' => $tipo[0]], TRUE)
            ];
        }
    }

    public function editarTipo(array $datos) {
        if (!isset($datos['tipo']) || !isset($datos['id']) || !isset($datos['estatus'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarTipo($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getConceptos() {
        $conceptos = $this->DB->getConceptos();
        return $conceptos;
    }

    public function formularioAgregarConcepto() {
        $sistemas = $this->DB->getSistemas();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarConcepto', ['sistemas' => $sistemas], TRUE)
        ];
    }

    public function agregarConcepto(array $datos) {
        if (!isset($datos['concepto']) || !isset($datos['sistema'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarConcepto($datos);
            if (!is_null($insert['id'])) {
                $concepto = $this->DB->getConceptos($insert['id']);
                return array_merge(['code' => 200], $concepto[0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function formularioEditarConcepto(array $datos) {
        $sistemas = $this->DB->getSistemas();
        $data = $this->DB->getConceptos($datos['id']);
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarConcepto', ['sistemas' => $sistemas, 'data' => $data[0]], TRUE)
        ];
    }

    public function formularioEditarArea(array $datos) {
        $conceptos = $this->DB->getConceptos();
        $data = $this->DB->getAreas($datos['id']);
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarArea', ['conceptos' => $conceptos, 'data' => $data[0]], TRUE)
        ];
    }

    public function formularioEditarUbicacion(array $datos) {
        $areas = $this->DB->getAreas();
        $data = $this->DB->getUbicaciones($datos['id']);
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarUbicacion', ['areas' => $areas, 'data' => $data[0]], TRUE)
        ];
    }

    public function editarConcepto(array $datos) {
        if (!isset($datos['concepto']) || !isset($datos['id']) || !isset($datos['estatus']) || !isset($datos['sistema'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarConcepto($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getAreas() {
        $conceptos = $this->DB->getAreas();
        return $conceptos;
    }

    public function formularioAgregarArea() {
        $conceptos = $this->DB->getConceptos();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarArea', ['conceptos' => $conceptos], TRUE)
        ];
    }

    public function agregarArea(array $datos) {
        if (!isset($datos['area']) || !isset($datos['concepto'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarArea($datos);
            if (!is_null($insert['id'])) {
                $concepto = $this->DB->getAreas($insert['id']);
                return array_merge(['code' => 200], $concepto[0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function editarArea(array $datos) {
        if (!isset($datos['concepto']) || !isset($datos['id']) || !isset($datos['estatus']) || !isset($datos['area'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarArea($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getUbicaciones() {
        $conceptos = $this->DB->getUbicaciones();
        return $conceptos;
    }

    public function formularioAgregarUbicacion() {
        $areas = $this->DB->getAreas();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarUbicacion', ['areas' => $areas], TRUE)
        ];
    }

    public function agregarUbicacion(array $datos) {
        if (!isset($datos['area']) || !isset($datos['ubicacion'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarUbicacion($datos);
            if (!is_null($insert['id'])) {
                $ubicacion = $this->DB->getUbicaciones($insert['id']);
                return array_merge(['code' => 200], $ubicacion[0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function editarUbicacion(array $datos) {
        if (!isset($datos['ubicacion']) || !isset($datos['id']) || !isset($datos['estatus']) || !isset($datos['area'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarUbicacion($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getAccesorios() {
        $accesorios = $this->DB->getAccesorios();
        return $accesorios;
    }

    public function formularioAgregarAccesorio() {
        $sistemas = $this->DB->getSistemas();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarAccesorio', ['sistemas' => $sistemas], TRUE)
        ];
    }

    public function agregarAccesorio(array $datos) {
        if (!isset($datos['accesorio']) || !isset($datos['sistema'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarAccesorio($datos);
            if (!is_null($insert['id'])) {
                $accesorio = $this->DB->getAccesorios($insert['id']);
                return array_merge(['code' => 200], $accesorio[0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function agregarMaterial(array $datos) {
        if (!isset($datos['accesorio']) || !isset($datos['material'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            $insert = $this->DB->agregarMaterial($datos);
            if (!is_null($insert['id'])) {
                $accesorio = $this->DB->getMaterial($insert['id']);
                return array_merge(['code' => 200], $accesorio[0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                ];
            }
        }
    }

    public function formularioEditarAccesorio(array $datos) {
        $sistemas = $this->DB->getSistemas();
        $data = $this->DB->getAccesorios($datos['id']);
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarAccesorio', ['sistemas' => $sistemas, 'data' => $data[0]], TRUE)
        ];
    }

    public function editarAccesorio(array $datos) {
        if (!isset($datos['accesorio']) || !isset($datos['id']) || !isset($datos['estatus']) || !isset($datos['sistema'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarAccesorio($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getMaterial() {
        $material = $this->DB->getMaterial();
        return $material;
    }

    public function formularioAgregarMaterial() {
        $accesorios = $this->DB->getAccesorios();
        $material = $this->DB->getMaterialSAE();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarMaterial', ['accesorios' => $accesorios, 'material' => $material], TRUE)
        ];
    }

    public function formularioEditarMaterial(array $datos) {
        $accesorios = $this->DB->getAccesorios();
        $material = $this->DB->getMaterialSAE();
        $data = $this->DB->getMaterial($datos['id']);
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarMaterial', ['accesorios' => $accesorios, 'material' => $material, 'data' => $data[0]], TRUE)
        ];
    }

    public function editarMaterial(array $datos) {
        if (!isset($datos['accesorio']) || !isset($datos['id']) || !isset($datos['material'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo.'
            ];
        } else {
            $result = $this->DB->editarMaterial($datos);
            if (!empty($result['datos'])) {
                return array_merge(['code' => 200], $result['datos'][0]);
            } else {
                return [
                    'code' => 500,
                    'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                ];
            }
        }
    }

    public function getKits() {
        $material = $this->DB->getKits();
        return $material;
    }

    public function formularioAgregarKit() {
        $material = $this->DB->getMaterial();
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/AgregarKit', ['material' => $material], TRUE)
        ];
    }

    public function formularioEditarKit(array $datos) {
        $material = $this->DB->getMaterial();
        $kit = $this->DB->getKits($datos['id'])[0];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Catalogos/Formularios/EditarKit', ['material' => $material, 'data' => $kit], TRUE)
        ];
    }

    public function agregarEditarKit(array $datos) {
        if (!isset($datos['kit']) || !isset($datos['idKit']) || !isset($datos['material'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información completa. Intente de nuevo'
            ];
        } else {
            if ($datos['idKit'] <= 0) {


                $insert = $this->DB->agregarKit($datos);
                if (!is_null($insert['id'])) {
                    $kit = $this->DB->getKits($insert['id']);
                    return array_merge(['code' => 200, 'move' => 'add'], $kit[0]);
                } else {
                    return [
                        'code' => 500,
                        'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $insert['error']
                    ];
                }
            } else {
                $result = $this->DB->editarKit($datos);
                if (!empty($result['datos'])) {
                    return array_merge(['code' => 200, 'move' => 'edit'], $result['datos'][0]);
                } else {
                    return [
                        'code' => 500,
                        'error' => 'No se ha podido guardar la información en la Base de Datos. ' . $result['error']
                    ];
                }
            }
        }
    }

}
