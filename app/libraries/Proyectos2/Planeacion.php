<?php

namespace Librerias\Proyectos2;

use Controladores\Controller_Base_General as General;

class Planeacion extends General {

    private $DB;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Proyectos2::factory();
    }

    public function formularioNuevoProyecto() {
        $datos = [
            'clientes' => $this->DB->getClientes(),
            'sistemas' => $this->DB->getSistemas(),
            'tipos' => $this->DB->getTipos(),
            'lideres' => $this->DB->getLideres()
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/NuevoProyecto', $datos, TRUE)
        ];
    }

    public function formularioDetallesProyecto(array $data) {
        $cliente = $this->DB->getClienteProyecto($data['id']);
        $lideres = $this->DB->getLideresProyecto($data['id']);

        $datos = [
            'generales' => array_merge($this->DB->getGeneralesProyecto($data['id']), ['IdCliente' => $cliente, 'Lideres' => explode(',', $lideres['Lideres'])]),
            'clientes' => $this->DB->getClientes(),
            'sucursales' => $this->DB->getSucursalesEstados($cliente),
            'sistemas' => $this->DB->getSistemas(),
            'tipos' => $this->DB->getTipos(),
            'lideres' => $this->DB->getLideres()
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/DetallesProyecto', $datos, TRUE)
        ];
    }

    public function sucursalesByCliente(array $datos) {
        $sucursales = $this->DB->getSucursalesEstados($datos['id']);
        return $sucursales;
    }

    public function generarProyecto(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {
            $ticketsV2 = $this->DB->generaTicketsProyectoV2($datos['cliente'], count($datos['sucursal']));

            if ($ticketsV2['code'] == 200) {
                $result = $this->DB->generaProyecto($datos, $ticketsV2['ids']);
                return $result;
            } else {
                return $ticketsV2;
            }
        }
    }

    public function guardarGeneralesProyecto(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {
            $generales = $this->DB->getGeneralesProyecto($datos['id']);
            $cliente = $this->DB->getClienteProyecto($datos['id']);

            if ($cliente != $datos['cliente']) {
                $dataEditar = ['cliente' => $datos['cliente'], 'ticket' => $generales['Ticket']];
                $this->DB->editarClienteProyecto($dataEditar);
            }

            $result = $this->DB->guardarGeneralesProyecto($datos);
            return $result;
        }
    }

    public function formularioNuevaUbicacion(array $data) {
        $generales = $this->DB->getGeneralesProyecto($data['id']);

        if (isset($data['alcance'])) {
            $alcance = $this->DB->getAlcanceById($data['alcance'])[0];
            $conceptos = $this->DB->getConceptosBySistema($generales['IdSistema']);
            $areas = $this->areasByConcepto(['id' => $alcance['IdConcepto']]);
            $ubicaciones = $this->ubicacionesByArea(['id' => $alcance['IdArea']]);
            $datos = [
                'conceptos' => $conceptos,
                'areas' => $areas,
                'ubicaciones' => $ubicaciones,
                'alcance' => $alcance
            ];
        } else {
            $datos = [
                'conceptos' => $this->DB->getConceptosBySistema($generales['IdSistema'])
            ];
        }
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/NuevaUbicacion', $datos, TRUE)
        ];
    }

    public function areasByConcepto(array $datos) {
        $areas = $this->DB->areasByConcepto($datos['id']);
        return $areas;
    }

    public function ubicacionesByArea(array $datos) {
        $ubicaciones = $this->DB->ubicacionesByArea($datos['id']);
        return $ubicaciones;
    }

    public function formularioNodosUbicacion(array $data) {
        $generales = $this->DB->getGeneralesProyecto($data['id']);
        $nodos = $this->DB->getNodosByUbicacion($data);
        $datos = [
            'tipos' => $this->DB->getTiposNodo(),
            'accesorios' => $this->DB->getAccesoriosBySistema($generales['IdSistema']),
            'nodos' => $nodos,
            'kits' => $this->DB->getKits(null, true)
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/NodosUbicacion', $datos, TRUE)
        ];
    }

    public function formularioEditarNodo(array $datos) {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del nodo. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesProyecto($datos['id']);
            $datos = [
                'nodo' => $datos,
                'tipos' => $this->DB->getTiposNodo(),
                'accesorios' => $this->DB->getAccesoriosBySistema($generales['IdSistema'])
            ];
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/EditarNodo', $datos, TRUE)
            ];
        }
    }

    public function guardarNodosUbicacion(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información de los nodos.'
            ];
        } else {
            $result = $this->DB->guardarNodosUbicacion($datos);
            return $result;
        }
    }

    public function cargaUbicacionesProyecto(array $datos) {
        $ubicaciones = $this->DB->cargaUbicacionesProyecto($datos['id']);
        return $ubicaciones;
    }    

    public function eliminarNodo(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del nodo.'
            ];
        } else {
            $result = $this->DB->eliminarNodo($datos);
            return $result;
        }
    }

    public function cargaMaterialTotales(array $datos) {
        $proyectado = $this->DB->cargaMaterialProyectado($datos['id']);
        $sae = $this->DB->cargaMaterialAsignadoSAE(234);
        $diferencias = [];

        foreach ($proyectado as $key => $value) {
            $diferencias[$value['Clave']] = [
                'Material' => $value['Material'],
                'Clave' => $value['Clave'],
                'Unidad' => $value['Unidad'],
                'Solicitado' => $value['Total'],
                'Asignado' => 0,
                'Diferencia' => (0 - $value['Total'])
            ];
            foreach ($sae as $k => $v) {
                if ($v['Clave'] == $value['Clave']) {
                    $diferencias[$value['Clave']]['Asignado'] = $v['Total'];
                    $diferencias[$value['Clave']]['Diferencia'] = $v['Total'] - $diferencias[$value['Clave']]['Solicitado'];
                }
            }
        }

        foreach ($sae as $key => $value) {

            if (!array_key_exists($value['Clave'], $diferencias)) {
                $diferencias[$value['Clave']] = [
                    'Material' => $value['Material'],
                    'Clave' => $value['Clave'],
                    'Unidad' => $value['Unidad'],
                    'Solicitado' => 0,
                    'Asignado' => $value['Total'],
                    'Diferencia' => $value['Total']
                ];

                foreach ($proyectado as $k => $v) {
                    if ($v['Clave'] == $value['Clave']) {
                        $diferencias[$value['Clave']]['Solicitado'] = $v['Total'];
                        $diferencias[$value['Clave']]['Diferencia'] = $diferencias[$value['Clave']]['Asignado'] - $v['Total'];
                    }
                }
            }
        }

        $arrayReturn = [
            'proyectado' => $proyectado,
            'sae' => $sae,
            'diferencia' => $diferencias
        ];

        return $arrayReturn;
    }

    public function cargaDatosTecnicos(array $datos) {
        $arrayReturn = [
            'tecnicos' => $this->DB->getTecnicosAsistentes(),
            'asignados' => $this->DB->getAsistentesProyecto($datos['id'])
        ];
        return $arrayReturn;
    }

    public function guardaAsistenteProyecto(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del técnico.'
            ];
        } else {
            $result = $this->DB->guardaAsistenteProyecto($datos);
            return $result;
        }
    }

    public function formDetallesAsistente(array $datos) {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del asistente. Intente de nuevo'
            ];
        } else {
            $datos = [
                'asistente' => $this->DB->getAsistentesProyecto($datos['id'], $datos['idRegistro'])[0]
            ];
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/EditarAsistente', $datos, TRUE)
            ];
        }
    }

    public function eliminarAsistente(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del asistente.'
            ];
        } else {
            $result = $this->DB->eliminarAsistente($datos);
            return $result;
        }
    }

    public function formularioNuevaTarea(array $data) {
        $datos = [
            'lideres' => $this->DB->getLideres(),
            'lideresProyecto' => $this->DB->getLideresProyecto($data['id']),
            'predecesoras' => $this->DB->getTareasPredecesoras($data['id']),
            'tecnicos' => $this->DB->getAsistentesProyecto($data['id'])
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/NuevaTarea', $datos, TRUE)
        ];
    }

    public function nuevaTarea(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {
            $result = $this->DB->guardaTarea($datos);
            return $result;
        }
    }
    
    public function cargaTareasProyecto(array $datos) {
        $tareas = $this->DB->cargaTareasProyecto($datos['id']);
        return $tareas;
    }

}
