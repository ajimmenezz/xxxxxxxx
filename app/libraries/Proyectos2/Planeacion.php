<?php

namespace Librerias\Proyectos2;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\PDF as PDF;

class Planeacion extends General {

    private $DB;
    private $pdf;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->DB = \Modelos\Modelo_Proyectos2::factory();
        $this->pdf = new PDFAux();
    }

    public function formularioNuevoProyecto() {
        $datos = [
            'clientes' => $this->DB->getClientes(),
            'sistemas' => $this->DB->getSistemas(),
            'tipos' => $this->DB->getTipos()
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/NuevoProyecto', $datos, TRUE)
        ];
    }

    public function formularioDetallesProyecto(array $data) {
        $cliente = $this->DB->getClienteProyecto($data['id']);
        $lideres = $this->DB->getLideresProyecto($data['id']);
        $generales = $this->DB->getGeneralesProyecto($data['id']);
        $almacen = $this->DB->getAlmacenSAEAsignado($data['id']);

        $datos = [
            'generales' => array_merge($generales, ['cve_almacen' => $almacen, 'IdCliente' => $cliente, 'Lideres' => explode(',', $lideres['Lideres'])]),
            'clientes' => $this->DB->getClientes(),
            'sucursales' => $this->DB->getSucursalesEstados($cliente),
            'sistemas' => $this->DB->getSistemas(),
            'tipos' => $this->DB->getTipos(),
            'lideres' => $this->DB->getLideres($generales['IdSistema'])
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

    public function lideresBySistema(array $datos) {
        $lideres = $this->DB->getLideres($datos['id']);
        return $lideres;
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
        if (!is_numeric($datos['almacen']) || $datos['almacen'] < 0) {
            $almacen = 0;
        } else {
            $almacen = $datos['almacen'];
        }
        $sae = $this->DB->cargaMaterialAsignadoSAE($almacen);
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
            'tecnicos' => $this->DB->getTecnicosAsistentes($datos['id']),
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
            'generales' => $this->DB->getGeneralesProyecto($data['id']),
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
                'error' => 'No se ha recibido la información de la tarea.'
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

    public function detallesTarea(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del nodo.'
            ];
        } else {
            $data = [
                'tarea' => $this->DB->cargaTareasProyecto($datos['id'], $datos['tarea']),
                'generales' => $this->DB->getGeneralesProyecto($datos['id']),
                'lideres' => $this->DB->getLideres(),
                'lideresProyecto' => $this->DB->getLideresProyecto($datos['id']),
                'predecesoras' => $this->DB->getTareasPredecesoras($datos['id'], $datos['tarea']),
                'tecnicos' => $this->DB->getAsistentesProyecto($datos['id'])
            ];
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/EditarTarea', $data, TRUE)
            ];
        }
    }

    public function eliminarTarea(array $datos) {
        $result = $this->DB->eliminarTarea($datos['id']);
        return $result;
    }

    public function cargaNodosTarea(array $datos) {
        $result = $this->DB->getNodosActivosProyecto($datos['id'], $datos['tarea'], $datos['nodo']);
        return $result;
    }

    public function cargaNodosTareaAgrupados(array $datos) {
        $result = $this->DB->getNodosActivosProyectoAgrupados($datos['id'], $datos['tarea']);
        return $result;
    }

    public function guardarNodosTarea(array $datos) {
        $result = $this->DB->guardarNodosTarea($datos);
        return $result;
    }

    public function formularioMaterialesTarea(array $data) {
        $generales = $this->DB->getGeneralesProyecto($data['id']);
        $datos = [
            'materiales' => $this->DB->getMaterialTarea($data['idTarea']),
            'tipos' => $this->DB->getTiposNodo(),
            'accesorios' => $this->DB->getAccesoriosBySistema($generales['IdSistema']),
            'kits' => $this->DB->getKits(null, true)
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/MaterialTarea', $datos, TRUE)
        ];
    }

    public function formularioEditarMaterialTarea(array $datos) {
        if (!isset($datos['id'])) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del nodo. Intente de nuevo'
            ];
        } else {
            $generales = $this->DB->getGeneralesProyecto($datos['id']);
            $datos = [
                'material' => $datos,
                'accesorios' => $this->DB->getAccesoriosBySistema($generales['IdSistema'])
            ];
            return [
                'code' => 200,
                'formulario' => parent::getCI()->load->view('Proyectos2/Planeacion/Formularios/EditarMaterialTarea', $datos, TRUE)
            ];
        }
    }

    public function guardarMaterialTarea(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información de los nodos.'
            ];
        } else {
            $result = $this->DB->guardarMaterialTarea($datos);
            return $result;
        }
    }

    public function generaDocumentoInicial(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {

            $infoHeader = $this->DB->getInfoHeaderInicioProyecto($datos['id'])[0];
            $lideres = $this->DB->getLideresProyectoString($datos['id']);
            $asistentes = $this->DB->getAsistentesProyectoString($datos['id']);
            $material = $this->DB->cargaMaterialProyectado($datos['id']);
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Inicio de Proyecto", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(15, 0, "Cliente:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Cliente']))));

            $this->pdf->SetXY(7, 45);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(13, 0, "Plaza:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sucursal']))));

            $this->pdf->SetXY(7, 50);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(20, 0, utf8_decode("Dirección:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Direccion']))));

            $this->pdf->SetXY(7, 55);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Sistema:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sistema']))));

            $this->pdf->SetXY(7, 60);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(11, 0, utf8_decode("Tipo:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Tipo']))));

            $this->pdf->SetXY(7, 65);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(12, 0, utf8_decode("Inicio:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(30, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Inicio']))));
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Termino:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(18, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Fin']))));

            if ($infoHeader['IdSistema'] == 1) {
                $this->pdf->Ln('10');
                $this->pdf->SetFont("helvetica", "B", 15);
                $this->pdf->Cell(0, 0, "Equipo Solicitado para el Personal", 0, 0, 'L');
                $y = $this->pdf->GetY() + 4;
                $this->pdf->Line($pline1, $y, $pline2, $y);

                $this->pdf->SetFont("helvetica", "", 10);
                $text = utf8_decode("Botas, Chaleco, Casco, Identificación de la empresa, kit básico de herramientas incluyendo Lámparas de Minero, arnés de seguridad,línea de vida. Todo esto por integrante del equipo");
                $this->pdf->Ln('7');
                $this->pdf->MultiCell(190, 4, $text);
            }

            $tlideres = count($lideres);
            $tasistentes = count($asistentes);
            $totalPersonal = $tlideres + $tasistentes;
            if ($totalPersonal > 0) {
                $this->pdf->Ln('10');
                $this->pdf->SetFont("helvetica", "B", 15);
                $this->pdf->Cell(0, 0, "Personal del proyecto", 0, 0, 'L');
                $y = $this->pdf->GetY() + 4;
                $this->pdf->Line($pline1, $y, $pline2, $y);

                if ($totalPersonal > 1) {
                    $text = "Esta obra contará con " . $totalPersonal . " participantes. ";
                } else {
                    $text = "Esta obra contará con " . $totalPersonal . " participante. ";
                }

                if ($tlideres > 0) {
                    if ($tlideres > 1) {
                        $text .= "Los encargados o líderes del proyecto serán ";
                        $c = 0;
                        $textLideres = '';
                        foreach ($lideres as $key => $value) {
                            $c++;
                            if ($c == $tlideres) {
                                $textLideres .= ' y ' . $value['Lider'] . ". ";
                            } else {
                                $textLideres .= ', ' . $value['Lider'];
                            }
                        }

                        $text .= substr($textLideres, 1);
                    } else {
                        $text .= "El encargado o líder del proyecto será " . $lideres[0]['Lider'] . ". ";
                    }
                }

                if ($tasistentes > 0) {
                    if ($tasistentes > 1) {
                        $text .= "Los técnicos asignados al proyecto serán";
                        $c = 0;
                        $textAsistentes = '';
                        foreach ($asistentes as $key => $value) {
                            $c++;
                            if ($c == $tasistentes) {
                                $textAsistentes .= ' y ' . $value['Asistente'] . ". ";
                            } else {
                                $textAsistentes .= ', ' . $value['Asistente'];
                            }
                        }

                        $text .= substr($textAsistentes, 1);
                    } else {
                        $text .= "El técnico asignado al proyecto será " . $asistentes[0]['Asistente'] . ". ";
                    }
                }

                $this->pdf->SetFont("helvetica", "", 10);
                $text = utf8_decode($text);
                $this->pdf->Ln('7');
                $this->pdf->MultiCell(190, 4, $text);
            }

            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Hospedaje", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFont("helvetica", "", 10);
            $text = utf8_decode("Favor de buscar un hotel en la zona para el personal asignado al proyecto. Ayudar con el sondeo en la zona para determinar si tenemos hoteles con el presupuesto establecido para este rubro.");
            $this->pdf->Ln('7');
            $this->pdf->MultiCell(190, 4, $text);


            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Material para el proyecto", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFont("helvetica", "", 10);
            $text = utf8_decode("Se adjunta la lista de materiales necesarios para el desarrollo de este proyecto. El/Los líderes del proyecto se encargarán de la recepción del mismo.");
            $this->pdf->Ln('7');
            $this->pdf->MultiCell(190, 4, $text);


            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Apoyos necesarios", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFont("helvetica", "", 10);
            $text = utf8_decode("Para el correcto desarrollo de este proyecto se solicita el apoyo de las diferentes áreas para conocer lo siguiente:");
            $this->pdf->Ln('7');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode("1. Estado actual de las compras de materiales.");
            $this->pdf->Ln('5');
            $this->pdf->MultiCell(190, 4, $text);
            $text = utf8_decode("2. Disponibilidad de herramental solicitado y/o estado de la compra.");
            $this->pdf->Ln('2');
            $this->pdf->MultiCell(190, 4, $text);
            $text = utf8_decode("3. Elaboración del presupuesto que ejerceremos en Obra.");
            $this->pdf->Ln('2');
            $this->pdf->MultiCell(190, 4, $text);
            $text = utf8_decode("4. Programa de envíos de material a la Obra.");
            $this->pdf->Ln('2');
            $this->pdf->MultiCell(190, 4, $text);

            $text = utf8_decode("Sin más quedo a sus ordenes, indicándoles que el proyecto está levantado en el sistema AdIST V3 y BaseCamp. Solicitó su seguimiento y participación en el mismo. Gracias.");
            $this->pdf->Ln('15');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetFont("helvetica", "BI", 13);
            $text = utf8_decode("Ing. Victor Ricardo Mojica Leines");
            $this->pdf->Ln('25');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');
            $text = utf8_decode("Gerente de Operaciones Siccob Solutions");
            $this->pdf->Ln('3');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');


            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Material Proyectado", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');
            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);


            $this->pdf->SetFillColor(226, 231, 235);

            $headers = ['Producto', 'Clave', 'Cantidad'];
            $widths = [125, 48, 23];

            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 8);
            $height = 6;
            foreach ($material as $key => $value) {
                $this->pdf->SetX('7');
                $this->pdf->MultiCell(125, $height, $value['Material'], 1, 'L', $fill);
                $this->pdf->SetXY(132, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(48, $height, $value['Clave'], 1, 'L', $fill);
                $this->pdf->SetXY(180, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(23, $height, $value['Total'], 1, 'C', $fill);
                $fill = !$fill;
            }


            $carpeta = $this->pdf->definirArchivo('Proyectos/Proyecto_' . $datos['id'], 'Inicio_Proyecto');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }

    public function generaSolicitudMaterial(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {

            $infoHeader = $this->DB->getInfoHeaderInicioProyecto($datos['id'])[0];
            $material = $this->DB->cargaMaterialProyectado($datos['id']);
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Solicitud de Material", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(15, 0, "Cliente:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Cliente']))));

            $this->pdf->SetXY(7, 45);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(13, 0, "Plaza:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sucursal']))));

            $this->pdf->SetXY(7, 50);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(20, 0, utf8_decode("Dirección:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Direccion']))));

            $this->pdf->SetXY(7, 55);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Sistema:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sistema']))));

            $this->pdf->SetXY(7, 60);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(11, 0, utf8_decode("Tipo:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Tipo']))));

            $this->pdf->SetXY(7, 65);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(12, 0, utf8_decode("Inicio:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(30, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Inicio']))));
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Termino:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(18, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Fin']))));

            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Material", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFillColor(226, 231, 235);
            $this->pdf->Ln('10');

            $headers = ['Producto', 'Clave', 'Cantidad'];
            $widths = [125, 48, 23];

            $this->pdf->SetX('7');
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 8);
            $height = 6;
            foreach ($material as $key => $value) {
                $this->pdf->SetX('7');
                $this->pdf->MultiCell(125, $height, $value['Material'], 1, 'L', $fill);
                $this->pdf->SetXY(132, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(48, $height, $value['Clave'], 1, 'L', $fill);
                $this->pdf->SetXY(180, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(23, $height, $value['Total'], 1, 'C', $fill);
                $fill = !$fill;
            }

            $text = utf8_decode("Sin más quedo a sus ordenes, indicándoles que el proyecto está levantado en el sistema AdIST V3 y BaseCamp. Solicitó su seguimiento y participación en el mismo. Gracias.");
            $this->pdf->Ln('15');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetFont("helvetica", "BI", 13);
            $text = utf8_decode("Ing. Victor Ricardo Mojica Leines");
            $this->pdf->Ln('25');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');
            $text = utf8_decode("Gerente de Operaciones Siccob Solutions");
            $this->pdf->Ln('3');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');

            $carpeta = $this->pdf->definirArchivo('Proyectos/Proyecto_' . $datos['id'], 'Solicitud_Material');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }

    public function generaDocumentoMaterialNodos(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {

            $infoHeader = $this->DB->getInfoHeaderInicioProyecto($datos['id'])[0];
            $material = $this->DB->cargaMaterialProyectado($datos['id']);
            $nodos = $this->DB->getNodosActivosForPdf($datos['id']);
            $nodosConcepto = $this->DB->getTotalesNodosConceptoTipoForPdf($datos['id']);
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Resumen de Material y Nodos", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(15, 0, "Cliente:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Cliente']))));

            $this->pdf->SetXY(7, 45);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(13, 0, "Plaza:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sucursal']))));

            $this->pdf->SetXY(7, 50);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(20, 0, utf8_decode("Dirección:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Direccion']))));

            $this->pdf->SetXY(7, 55);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Sistema:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sistema']))));

            $this->pdf->SetXY(7, 60);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(11, 0, utf8_decode("Tipo:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Tipo']))));

            $this->pdf->SetXY(7, 65);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(12, 0, utf8_decode("Inicio:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(30, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Inicio']))));
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Termino:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(18, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Fin']))));

            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Material", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFillColor(226, 231, 235);
            $this->pdf->Ln('10');

            $headers = ['Producto', 'Clave', 'Cantidad'];
            $widths = [125, 48, 23];

            $this->pdf->SetX('7');
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 8);
            $height = 6;
            foreach ($material as $key => $value) {
                $this->pdf->SetX('7');
                $this->pdf->MultiCell(125, $height, $value['Material'], 1, 'L', $fill);
                $this->pdf->SetXY(132, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(48, $height, $value['Clave'], 1, 'L', $fill);
                $this->pdf->SetXY(180, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(23, $height, $value['Total'], 1, 'C', $fill);
                $fill = !$fill;
            }


            $this->pdf->Ln('10');
            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Nodos por Concepto y Tipo", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);


            $this->pdf->Ln('10');

            $headers = ['Concepto', 'Tipo Nodo', 'Cantidad'];
            $widths = [50, 50, 25];

            $this->pdf->SetX('7');
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 8);
            $height = 6;
            $totalNodos = 0;
            foreach ($nodosConcepto as $key => $value) {
                $this->pdf->SetX('7');
                $this->pdf->MultiCell(50, $height, $value['Concepto'], 1, 'L', $fill);
                $this->pdf->SetXY(57, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(50, $height, $value['TipoNodo'], 1, 'L', $fill);
                $this->pdf->SetXY(107, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(25, $height, $value['Total'], 1, 'C', $fill);
                $totalNodos += (int) $value['Total'];
                $fill = !$fill;
            }

            $this->pdf->SetFont('helvetica', 'B', 11);
            $this->pdf->SetX('7');
            $this->pdf->MultiCell(100, $height, 'TOTAL NODOS', 1, 'R', $fill);
            $this->pdf->SetXY(107, $this->pdf->GetY() - 6);
            $this->pdf->MultiCell(25, $height, $totalNodos, 1, 'C', $fill);            

            $this->pdf->SetFont('helvetica', '', 8);


            $text = utf8_decode("Sin más quedo a sus ordenes, indicándoles que el proyecto está levantado en el sistema AdIST V3 y BaseCamp. Solicitó su seguimiento y participación en el mismo. Gracias.");
            $this->pdf->Ln('5');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetFont("helvetica", "BI", 13);
            $text = utf8_decode("Ing. Victor Ricardo Mojica Leines");
            $this->pdf->Ln('10');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');
            $text = utf8_decode("Gerente de Operaciones Siccob Solutions");
            $this->pdf->Ln('3');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');

            $carpeta = $this->pdf->definirArchivo('Proyectos/Proyecto_' . $datos['id'], 'Material_Nodos');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }
    
    public function generaDocumentoNodos(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {

            $infoHeader = $this->DB->getInfoHeaderInicioProyecto($datos['id'])[0];            
            $nodos = $this->DB->getNodosActivosForPdf($datos['id']);            
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Nodos del Proyecto", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(15, 0, "Cliente:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Cliente']))));

            $this->pdf->SetXY(7, 45);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(13, 0, "Plaza:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sucursal']))));

            $this->pdf->SetXY(7, 50);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(20, 0, utf8_decode("Dirección:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Direccion']))));

            $this->pdf->SetXY(7, 55);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Sistema:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sistema']))));

            $this->pdf->SetXY(7, 60);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(11, 0, utf8_decode("Tipo:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Tipo']))));

            $this->pdf->SetXY(7, 65);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(12, 0, utf8_decode("Inicio:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(30, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Inicio']))));
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Termino:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(18, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Fin']))));

            $this->pdf->SetFillColor(226, 231, 235);
            
            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Nodos del Proyecto", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->Ln('10');

            $headers = ['#', 'Nodo', 'Tipo Nodo', 'Concepto', 'Área', 'Ubicación'];
            $widths = [10, 30, 30, 25, 50, 50];

            $this->pdf->SetX('7');
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 7);
            $height = 6;
            $c = 0;
            foreach ($nodos as $key => $value) {
                $c++;
                $this->pdf->SetX('7');
                $this->pdf->MultiCell(10, $height, $c, 1, 'C', $fill);
                $this->pdf->SetXY(17, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(30, $height, $value['Nombre'], 1, 'C', $fill);
                $this->pdf->SetXY(47, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(30, $height, $value['TipoNodo'], 1, 'L', $fill);
                $this->pdf->SetXY(77, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(25, $height, $value['Concepto'], 1, 'C', $fill);
                $this->pdf->SetXY(102, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(50, $height, $value['Area'], 1, 'L', $fill);
                $this->pdf->SetXY(152, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(50, $height, $value['Ubicacion'], 1, 'L', $fill);
                $fill = !$fill;
            }


            $this->pdf->SetFont('helvetica', '', 8);


            $text = utf8_decode("Sin más quedo a sus ordenes, indicándoles que el proyecto está levantado en el sistema AdIST V3 y BaseCamp. Solicitó su seguimiento y participación en el mismo. Gracias.");
            $this->pdf->Ln('5');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetFont("helvetica", "BI", 13);
            $text = utf8_decode("Ing. Victor Ricardo Mojica Leines");
            $this->pdf->Ln('10');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');
            $text = utf8_decode("Gerente de Operaciones Siccob Solutions");
            $this->pdf->Ln('3');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');

            $carpeta = $this->pdf->definirArchivo('Proyectos/Proyecto_' . $datos['id'], 'Nodos del Proyecto');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }

    public function generaSolicitudMaterialFaltante(array $datos) {
        if (!isset($datos)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto.'
            ];
        } else {

            $infoHeader = $this->DB->getInfoHeaderInicioProyecto($datos['id'])[0];
            $material = $this->cargaMaterialTotales(['id' => $datos['id'], 'almacen' => $datos['almacen']])['diferencia'];
            $pline1 = 9;
            $pline2 = 201;

            $fecha = date('d/m/Y');

            $this->pdf->AddPage();
            $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
            $this->pdf->SetXY(0, 18);
            $this->pdf->SetFont("helvetica", "B", 18);
            $this->pdf->Cell(0, 0, "Material Faltante", 0, 0, 'C');

            $this->pdf->SetXY(0, 27);
            $this->pdf->SetFont("helvetica", "", 9);
            $this->pdf->Cell(0, 0, "Soluciones Integrales para empresas Integrales", 0, 0, 'R');

            $this->pdf->Line(5, 32, 205, 32);
            $this->pdf->SetXY(7, 40);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(15, 0, "Cliente:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Cliente']))));

            $this->pdf->SetXY(7, 45);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(13, 0, "Plaza:");
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sucursal']))));

            $this->pdf->SetXY(7, 50);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(20, 0, utf8_decode("Dirección:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Direccion']))));

            $this->pdf->SetXY(7, 55);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Sistema:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Sistema']))));

            $this->pdf->SetXY(7, 60);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(11, 0, utf8_decode("Tipo:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(180, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Tipo']))));

            $this->pdf->SetXY(7, 65);
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(12, 0, utf8_decode("Inicio:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(30, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Inicio']))));
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Cell(17, 0, utf8_decode("Termino:"));
            $this->pdf->SetFont("helvetica", "", 10);
            $this->pdf->Cell(18, 0, utf8_decode(ucwords(mb_strtolower($infoHeader['Fin']))));

            $this->pdf->Ln('10');
            $this->pdf->SetFont("helvetica", "B", 15);
            $this->pdf->Cell(0, 0, "Material", 0, 0, 'L');
            $y = $this->pdf->GetY() + 4;
            $this->pdf->Line($pline1, $y, $pline2, $y);

            $this->pdf->SetFillColor(226, 231, 235);
            $this->pdf->Ln('10');

            $headers = ['Producto', 'Clave', 'Cantidad'];
            $widths = [125, 48, 23];

            $this->pdf->SetX('7');
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            $push_right = 0;

            $this->pdf->SetFont('helvetica', 'B', 10);
            foreach ($headers as $key => $value) {
                $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
                $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $this->pdf->Ln();

            $fill = false;

            $this->pdf->SetFont('helvetica', '', 8);
            $height = 6;
            foreach ($material as $key => $value) {
                if ($value['Diferencia'] < 0) {
                    $this->pdf->SetX('7');
                    $this->pdf->MultiCell(125, $height, $value['Material'], 1, 'L', $fill);
                    $this->pdf->SetXY(132, $this->pdf->GetY() - 6);
                    $this->pdf->MultiCell(48, $height, $value['Clave'], 1, 'L', $fill);
                    $this->pdf->SetXY(180, $this->pdf->GetY() - 6);
                    $this->pdf->MultiCell(23, $height, abs($value['Diferencia']), 1, 'C', $fill);
                    $fill = !$fill;
                }
            }

            $text = utf8_decode("Sin más quedo a sus ordenes, indicándoles que el proyecto está levantado en el sistema AdIST V3 y BaseCamp. Solicitó su seguimiento y participación en el mismo. Gracias.");
            $this->pdf->Ln('15');
            $this->pdf->MultiCell(190, 4, $text);

            $this->pdf->SetFont("helvetica", "BI", 13);
            $text = utf8_decode("Ing. Victor Ricardo Mojica Leines");
            $this->pdf->Ln('25');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');
            $text = utf8_decode("Gerente de Operaciones Siccob Solutions");
            $this->pdf->Ln('3');
            $this->pdf->MultiCell(190, 4, $text, 0, 'C');

            $carpeta = $this->pdf->definirArchivo('Proyectos/Proyecto_' . $datos['id'], 'Material_Faltante');
            $this->pdf->Output('F', $carpeta, true);
            $carpeta = substr($carpeta, 1);
            return $carpeta;
        }
    }

    public function formularioDetallesProyectoAlmacen(array $data) {
        $generales = $this->DB->getGeneralesProyecto($data['id']);
        $cliente = $this->DB->getClienteProyecto($data['id']);
        $lideres = $this->DB->getLideresProyecto($data['id']);
        $almacenes = $this->DB->getAlmacenesVirtualesSAE();

        $datos = [
            'generales' => array_merge($generales, ['cve_almacen' => $data['almacen'], 'IdCliente' => $cliente, 'Lideres' => explode(',', $lideres['Lideres'])]),
            'clientes' => $this->DB->getClientes(),
            'sucursales' => $this->DB->getSucursalesEstados($cliente),
            'sistemas' => $this->DB->getSistemas(),
            'tipos' => $this->DB->getTipos(),
            'lideres' => $this->DB->getLideres($generales['IdSistema']),
            'almacenes' => $this->DB->getAlmacenesVirtualesSAE()
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Almacen/Formularios/DetallesProyecto', $datos, TRUE)
        ];
    }

    public function asignarAlmacenVirtual(array $data) {
        if (!isset($data)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del proyecto o del almacén. Recargue su página e intente de nuevo.'
            ];
        } else {
            $result = $this->DB->asignarAlmacenVirtual($data);
            return $result;
        }
    }

    public function formularioSeguimientoTarea(array $data) {
        $generales = $this->DB->getGeneralesTarea($data['id']);
        $tienePredecesora = false;
        $avancePredecesora = 0;
        if ($generales['IdPredecesora'] > 0) {
            $tienePredecesora = true;
            $avancePredecesora = $generales['AvancePredecesora'];
        }

        $datos = [
            'generales' => $generales,
            'predecesora' => $tienePredecesora,
            'avancePredecesora' => $avancePredecesora
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/SeguimientoTarea', $datos, TRUE)
        ];
    }

    public function guardarAvanceTarea(array $data) {
        if (!isset($data)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información correcta. Recargue su página e intente de nuevo.'
            ];
        } else {
            $result = $this->DB->guardarAvanceTarea($data);
            return $result;
        }
    }

    public function cargaMaterialNodosTarea(array $data) {
        $generales = $this->DB->getGeneralesTarea($data['id']);
        $tienePredecesora = false;
        $avancePredecesora = 0;
        if ($generales['IdPredecesora'] > 0) {
            $tienePredecesora = true;
            $avancePredecesora = $generales['AvancePredecesora'];
        }

        $nodos = $this->DB->getNodosByTarea($data['id']);
        $_nodos = [];

        foreach ($nodos as $key => $value) {
            if (!array_key_exists($value['IdNodo'], $_nodos)) {
                $_nodos[$value['IdNodo']] = [
                    'Concepto' => $value['Concepto'],
                    'Area' => $value['Area'],
                    'Ubicacion' => $value['Ubicacion'],
                    'Tipo' => $value['TipoNodo'],
                    'Nodo' => $value['Nodo'],
                    'Material' => []
                ];
            }

            array_push($_nodos[$value['IdNodo']]['Material'], [
                'Id' => $value['IdRegistroMaterial'],
                'Accesorio' => $value['Accesorio'],
                'Material' => $value['Material'],
                'Cantidad' => $value['Cantidad'],
                'Utilizado' => $value['Utilizado'],
            ]);
        }


        $datos = [
            'nodos' => $_nodos,
            'predecesora' => $tienePredecesora,
            'avancePredecesora' => $avancePredecesora
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/NodosTarea', $datos, TRUE)
        ];
    }

    public function guardaMaterialUtilizadoNodosTarea(array $data) {
        if (!isset($data)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del material y los nodos. Recargue su página e intente de nuevo.'
            ];
        } else {
            $result = $this->DB->guardaMaterialUtilizadoNodosTarea($data['data']);
            if ($result['code'] == 200) {
                return $this->cargaMaterialNodosTarea($data);
            } else {
                return $result;
            }
        }
    }

    public function cargaConsumirMaterial(array $data) {
        $generales = $this->DB->getGeneralesTarea($data['id']);
        $tienePredecesora = false;
        $avancePredecesora = 0;
        if ($generales['IdPredecesora'] > 0) {
            $tienePredecesora = true;
            $avancePredecesora = $generales['AvancePredecesora'];
        }

        $material = $this->DB->getMaterialByTarea($data['id']);

        $datos = [
            'material' => $material,
            'predecesora' => $tienePredecesora,
            'avancePredecesora' => $avancePredecesora
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/MaterialTarea', $datos, TRUE)
        ];
    }

    public function guardaMaterialUtilizadoTarea(array $data) {
        if (!isset($data)) {
            return [
                'code' => 500,
                'error' => 'No se ha recibido la información del material. Recargue su página e intente de nuevo.'
            ];
        } else {
            $result = $this->DB->guardaMaterialUtilizadoTarea($data['data']);
            if ($result['code'] == 200) {
                return $this->cargaConsumirMaterial($data);
            } else {
                return $result;
            }
        }
    }

    public function guardarNotasAdjuntos(array $datos) {
        $generales = $this->DB->getGeneralesTarea($datos['id']);
        $proyecto = $generales['IdProyecto'];

        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'Proyectos/Proyecto_' . $proyecto . '/Tareas/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'adjuntosTarea', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $datos = array_merge($datos, ['archivos' => $archivos]);
        $resultado = $this->DB->guardarNotasAdjuntos($datos);

        return $resultado;
    }

    public function cargaNotasAdjuntos(array $data) {
        $generales = $this->DB->getGeneralesTarea($data['id']);
        $tienePredecesora = false;
        $avancePredecesora = 0;
        if ($generales['IdPredecesora'] > 0) {
            $tienePredecesora = true;
            $avancePredecesora = $generales['AvancePredecesora'];
        }

        $notas = $this->DB->getNotasAdjuntos($data['id']);

        $datos = [
            'notas' => $notas,
            'predecesora' => $tienePredecesora,
            'avancePredecesora' => $avancePredecesora,
            'usuario' => $this->usuario['Id']
        ];
        return [
            'code' => 200,
            'formulario' => parent::getCI()->load->view('Proyectos2/Tareas/Formularios/NotasAdjuntos', $datos, TRUE)
        ];
    }

}

class PDFAux extends PDF {

    function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(68, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

}
