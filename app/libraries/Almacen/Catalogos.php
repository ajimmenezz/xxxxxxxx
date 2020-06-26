<?php

namespace Librerias\Almacen;

use Controladores\Controller_Base_General as General;
use Librerias\Proyectos\PDF as PDF;

class Catalogos extends General {

    private $catalogo;
    private $usuario;
    private $DB;
    private $pdf;

    public function __construct() {
        parent::__construct();
        parent::getCI()->load->helper('date');
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DB = \Modelos\Modelo_InventarioConsignacion::factory();
        $this->pdf = new PDF();
    }

    public function mostrarFormularioLinea(array $datos) {
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioLinea', '', TRUE));
    }

    public function mostrarFormularioSublinea(array $datos) {
        $data = ['lineas' => $datos];
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioSublinea', $data, TRUE));
    }

    public function mostrarFormularioEditarSublinea(array $datos) {
        $data = ['datos' => $datos[0], 'lineas' => $datos[1]];
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioEditarSublinea', $data, TRUE));
    }

    public function mostrarFormularioMarca() {
        $data = ['lineas' => $this->catalogo->catLineasEquipo('3', array('Flag' => '1'))];
        $sublineas = $this->catalogo->catSublineasEquipo('3', array('Flag' => '1'));
        $sublineasReturn = array();
        foreach ($sublineas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($sublineasReturn, array(
                    'Id' => $value['IdSub'],
                    'Nombre' => $value['Sublinea'],
                    'IdLinea' => $value['IdLinea']
                ));
            }
        }

        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioMarca', $data, TRUE),
            'sublineas' => $sublineasReturn);
    }

    public function mostrarFormularioEditarMarca($datos) {
        $lineas = $this->catalogo->catLineasEquipo('3', array('Flag' => '1'));
        $sublineas = $this->catalogo->catSublineasEquipo('3', array('Flag' => '1'));
        $sublineasReturn = array();
        foreach ($sublineas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($sublineasReturn, array(
                    'Id' => $value['IdSub'],
                    'Nombre' => $value['Sublinea'],
                    'IdLinea' => $value['IdLinea']
                ));
            }
        }

        $data = ['datos' => $datos, 'lineas' => $lineas, 'sublineas' => $sublineas];

        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioEditarMarca', $data, TRUE),
            'sublineas' => $sublineasReturn);
    }

    public function mostrarFormularioModelo() {
        $data = ['lineas' => $this->catalogo->catLineasEquipo('3', array('Flag' => '1'))];
        $sublineas = $this->catalogo->catSublineasEquipo('3', array('Flag' => '1'));
        $marcas = $this->catalogo->catMarcasEquipo('3', array('Flag' => '1'));
        $sublineasReturn = array();
        $marcasReturn = array();
        foreach ($sublineas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($sublineasReturn, array(
                    'Id' => $value['IdSub'],
                    'Nombre' => $value['Sublinea'],
                    'IdLinea' => $value['IdLinea']
                ));
            }
        }
        foreach ($marcas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($marcasReturn, array(
                    'Id' => $value['IdMar'],
                    'Nombre' => $value['Marca'],
                    'IdSub' => $value['IdSub']
                ));
            }
        }

        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioModelo', $data, TRUE),
            'sublineas' => $sublineasReturn, 'marcas' => $marcasReturn);
    }

    public function mostrarFormularioEditarModelo($datos) {
        $lineas = $this->catalogo->catLineasEquipo('3', array('Flag' => '1'));
        $sublineas = $this->catalogo->catSublineasEquipo('3', array('Flag' => '1'));
        $marcas = $this->catalogo->catMarcasEquipo('3', array('Flag' => '1'));
        $modelo = $this->catalogo->catModelosEquipo('5', array('id' => $datos['idmod']));
        $datos['descripcion'] = $modelo[0]['Descripcion'];
        $archivos = $archivos = explode(',', $modelo[0]['Archivos']);
        $datos['archivos'] = $archivos;
        $sublineasReturn = array();
        $marcasReturn = array();

        foreach ($sublineas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($sublineasReturn, array(
                    'Id' => $value['IdSub'],
                    'Nombre' => $value['Sublinea'],
                    'IdLinea' => $value['IdLinea']
                ));
            }
        }

        foreach ($marcas as $key => $value) {
            if ($value['Flag'] > 0) {
                array_push($marcasReturn, array(
                    'Id' => $value['IdMar'],
                    'Nombre' => $value['Marca'],
                    'IdSub' => $value['IdSub']
                ));
            }
        }

        $data = ['datos' => $datos, 'lineas' => $lineas, 'sublineas' => $sublineas, 'marcas' => $marcas];

        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioEditarModelo', $data, TRUE),
            'sublineas' => $sublineasReturn, 'marcas' => $marcasReturn, 'datos' => $datos);
    }

    public function mostrarFormularioComponente() {
        $equiposReturn = $this->catalogo->catVistaEquipo('3');
        $data = ['equipos' => $equiposReturn];
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioComponente', $data, TRUE));
    }

    public function mostrarFormularioEditarComponente($datos) {
        $equiposReturn = $this->catalogo->catVistaEquipo('3');
        $data = ['datos' => $datos, 'equipos' => $equiposReturn];

        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioEditarComponente', $data, TRUE));
    }

    public function mostrarFormularioAlmacen() {
        $data = [
            'usuarios' => $this->catalogo->catUsuarios('3')
        ];
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioAlmacen', $data, TRUE));
    }

    public function mostrarEditarAlmacen(array $datos) {
        $data = [
            'usuarios' => $this->catalogo->catUsuarios('3'),
            'datos' => $datos
        ];
        return array('formulario' => parent::getCI()->load->view('Almacen/Modal/FormularioEditarAlmacen', $data, TRUE));
    }

    public function mostrarAlamacenVirtual(array $datos) {
        $almacen = $this->DB->getDatosAlmacenVirtual($datos['datos'][0]);

        if (in_array($almacen['IdTipoAlmacen'], [1, 4])) {
            $where = $this->whereEstatusAlmacenVirtual();
            $arrayExtra = array(
                'permisoEditarEstatus' => (in_array('338', $this->usuario['Permisos'])) ? true : false,
                'permisoAdicionalEditarEstatus' => (in_array('338', $this->usuario['PermisosAdicionales'])) ? true : false,
                'permisoLaboratorio' => ('38' === $this->usuario['IdPerfil']) ? true : false,
                'estatus' => $this->catalogo->catStatus('4', array('where' => $where)));

            $data = [
                'tiposMovimientos' => $this->DB->getTiposMovimientosInventario(),
                'tiposProductos' => $this->DB->getTiposProductosInvenario(),
                'datos' => $datos['datos'],
                'poliza' => $this->DB->getInventarioPoliza($datos['datos'][0]),
                'salas4D' => $this->DB->getInventarioSalas($datos['datos'][0]),
                'otros' => $this->DB->getInventarioSAE($datos['datos'][0]),
                'movimientos' => $this->DB->getMovimientosByAlmacen($datos['datos'][0]),
                'inventarioInicial' => (in_array('214', $this->usuario['Permisos'])) ? true : false,
                'alta' => $this->DB->getNewAltaInicial()
            ];
            return array('html' => parent::getCI()->load->view('Almacen/Modal/InventarioAlmacen', $data, TRUE), 'tipoAlmacen' => $almacen['IdTipoAlmacen'], 'arrayExtra' => $arrayExtra);
        } else if ($almacen['IdTipoAlmacen'] == 2) {
            $data = [
                'datos' => $datos['datos'],
                'censo' => $this->DB->getInventarioSucursalPoliza($almacen['IdReferenciaAlmacen'])
            ];
            return array('html' => parent::getCI()->load->view('Almacen/Modal/InventarioSucursalPoliza', $data, TRUE), 'tipoAlmacen' => $almacen['IdTipoAlmacen']);
        } else if ($almacen['IdTipoAlmacen'] == 3) {
            $data = [
                'datos' => $datos['datos'],
                'elementos' => $this->DB->getElementosSalas4D($almacen['IdReferenciaAlmacen']),
                'subelementos' => $this->DB->getSubelementosSalas4D($almacen['IdReferenciaAlmacen'])
            ];
            return array('html' => parent::getCI()->load->view('Almacen/Modal/InventarioSala4D', $data, TRUE), 'tipoAlmacen' => $almacen['IdTipoAlmacen']);
        } else {
            return array('html' => "<p>Ocurrió un error. Intente de nuevo.</p>", 'tipoAlmacen' => $almacen['IdTipoAlmacen']);
        }
    }

    public function whereEstatusAlmacenVirtual() {
        switch ($this->usuario['IdPerfil']) {
            case '38':
                $where = ('WHERE Id = 25');
                break;
            case '51':
            case '61':
            case '62':
            case '70':
                $where = ('WHERE Id IN(25,50)');
                break;
            default :
                $where = ('WHERE Descripcion = "Inventario Virtual"');
        }

        return $where;
    }

    public function mostrarFormularioProductoPoliza() {
        $data = [
            'modelos' => $this->DB->getModelosPoliza(),
            'estatus' => $this->DB->getEstatusProductoConsignacion()
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioAgregarProductoPoliza', $data, TRUE),
            'estatus' => $data['estatus']
        );
    }

    public function mostrarFormularioProductoSalas() {
        $data = [
            'elementos' => $this->DB->getElementosSalas(),
            'estatus' => $this->DB->getEstatusProductoConsignacion()
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioAgregarProductoSalas', $data, TRUE),
            'estatus' => $data['estatus']
        );
    }

    public function mostrarFormularioProductoSAE() {
        $data = [
            'productos' => $this->DB->getProductosSAE(),
            'estatus' => $this->DB->getEstatusProductoConsignacion()
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioAgregarProductoSAE', $data, TRUE),
            'estatus' => $data['estatus']
        );
    }

    public function cargaComponentesPoliza(array $datos) {
        return array('componentes' => $this->DB->getComponentesPoliza($datos['modelo']));
    }

    public function cargaSubelementosSalas4D(array $datos) {
        return array('componentes' => $this->DB->getSubelementosSalas($datos['modelo']));
    }

    public function guardarProductosInventario(array $datos) {
        $returnArray = [
            'estatus' => 400,
            'error' => ""
        ];

        $inicial = $this->DB->getNewAltaInicial()[0]['Id'];

        $result = $this->DB->guardarProductosInventario($datos, $inicial);
        if ($result['estatus'] == 200) {
            $result['poliza'] = $this->DB->getInventarioPoliza($datos['data'][0]['IdAlmacen']);
            $result['salas'] = $this->DB->getInventarioSalas($datos['data'][0]['IdAlmacen']);
            $result['otros'] = $this->DB->getInventarioSAE($datos['data'][0]['IdAlmacen']);
        }
        return $result;
    }

    public function revisaSeriesDuplicadas(array $datos) {
        $info = $datos['data'];
        $seriesDuplicadas = [];
        foreach ($info as $key => $value) {
            if ($value['Serie'] != '') {
                $array = [
                    'IdTipoProducto' => $value['IdTipoProducto'],
                    'IdProducto' => $value['IdProducto'],
                    'Serie' => $value['Serie']
                ];

                $buscar = $this->DB->buscaSerieDuplicada($array);
                if (!empty($buscar) && isset($buscar[0]['Serie'])) {
                    array_push($seriesDuplicadas, $buscar[0]['Serie']);
                }
            }
        }

        return ['series' => $seriesDuplicadas];
    }

    public function guardarComponentesDeshueso(array $datos) {
        $returnArray = [
            'estatus' => 400,
            'error' => ""
        ];

//        $result = $this->DB->guardarProductosInventario($datos, $inicial);
        $result = $this->DB->guardarComponentesDeshueso($datos['data']['componentes'], $datos['data']['idInventario']);
        return $result;
    }

    public function filtrarMovimientosInventario(array $datos) {
        $result = $this->DB->getMovimientosByAlmacen($datos['id'], $datos);
        return $result;
    }

    public function mostrarFormularioTraspaso() {
        $data = [
            'almacenesOrigen' => $this->DB->getAlmacenesOrigenTraspaso(),
            'almacenesDestino' => $this->DB->getAlmacenesDestinoTraspaso()
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioTraspaso', $data, TRUE)
        );
    }

    public function mostrarTraspasos() {
        $verTodosTraspasos = (in_array('218', $this->usuario['Permisos'])) ? true : false;
        $data = [
            'traspasos' => $this->DB->getTraspasos($verTodosTraspasos)
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/ListaTraspasos', $data, TRUE)
        );
    }

    public function mostrarAltasIniciales() {
        $verTodasAltas = (in_array('219', $this->usuario['Permisos'])) ? true : false;
        $data = [
            'altas' => $this->DB->getAltasIniciales($verTodasAltas)
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/ListaAltasIniciales', $data, TRUE)
        );
    }

    public function mostrarKitsEquipos() {
        $agregarEditarKits = (in_array('222', $this->usuario['Permisos'])) ? true : false;
        $data = [
            'kits' => $this->DB->getKitsEquipos(),
            'agregarEditar' => $agregarEditarKits,
            'equipos' => $this->catalogo->catModelosEquipo("4")
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/KitsEquipos', $data, TRUE)
        );
    }

    public function mostrarDeshuesarEquipo() {
        $deshuesar = (in_array('224', $this->usuario['Permisos'])) ? true : false;
        $data = [
            'productos' => $this->DB->getProductosDeshuesar(),
            'deshuesar' => $deshuesar
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/DeshuesarEquipo', $data, TRUE)
        );
    }

    public function mostrarComponentesDeshueso(array $datos) {
        $data = [
            'kit' => $this->DB->getKitByModelo($datos['datos'][1]),
            'componentes' => $this->DB->getComponentesByModelo($datos['datos'][1]),
            'estatus' => $this->DB->getEstatusProductoConsignacion()
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/ComponentesDeshuesarEquipo', $data, TRUE)
        );
    }

    public function mostrarComponentesEquipoKit(array $datos) {
        $data = [
            'componentes' => $this->DB->getComponentesByModelo($datos['equipo']),
            'cantidades' => $this->DB->getKitByModelo($datos['equipo'])
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/ComponentesEquipoKit', $data, TRUE)
        );
    }

    public function guardarKit(array $datos) {
        $result = $this->DB->guardarKit($datos);
        return $result;
    }

    public function mostrarProductosTraspaso(array $datos) {
        $data = [
            'poliza' => $this->DB->getInventarioPoliza($datos['origen']),
            'salas' => $this->DB->getInventarioSalas($datos['origen']),
            'otros' => $this->DB->getInventarioSAE($datos['origen'])
        ];
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioProductosTraspaso', $data, TRUE)
        );
    }

    public function traspasarProductos(array $datos) {
        $result = $this->DB->traspasarProductos($datos);
        $file = '';
        if ($result['estatus']) {
            $file = $this->crearPDFTraspaso($result['id']);
        }

        return array('estatus' => $result['estatus'], 'file' => $file);
    }

    public function cerrarAltaInicial() {
        $result = $this->DB->cerrarAltaInicial();
        $file = '';
        if ($result['estatus']) {
            $file = $this->crearPDFAltaInicial($result['altaInicial']);
        }

        return array('estatus' => $result['estatus'], 'file' => $file);
    }

    public function nuevaAltaInicial(array $datos) {
        $result = $this->DB->nuevaAltaInicial($datos['datos'][0]);
        return $result;
    }

    public function imprimirTraspaso(array $datos) {
        $file = $this->crearPDFTraspaso($datos['id']);

        return array('file' => $file);
    }

    public function imprimirAltaInicial(array $datos) {
        $file = $this->crearPDFAltaInicial($datos['id']);

        return array('file' => $file);
    }

    public function crearPDFTraspaso($id) {
        $productos = $this->DB->getDetallesTraspaso($id);
        $productosAux = [];
        $origen = '';
        $destino = '';
        $usuario = '';
        $fecha = '';
        foreach ($productos as $key => $value) {
            $origen = $value['Origen'];
            $destino = $value['Destino'];
            $usuario = $value['Usuario'];
            $fecha = $value['Fecha'];
            array_push($productosAux, [
//                $value['Movimiento'],
//                $value['Origen'],
//                $value['Destino'],
                $value['TipoProducto'],
                $value['Producto'],
                $value['Cantidad'],
                $value['Serie'],
                $value['Estatus']
            ]);
        }

        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetFillColor(226, 231, 235);

        $this->pdf->AddPage('L', 'Letter');
        $this->pdf->titulo('Detalles de Movimiento ' . sprintf("%'.011d\n", $id));

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $this->pdf->SetXY($x, ($y - 10));

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Almacén de Origen:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $origen), 1, 'L', false);

        $x = $x + 90;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Almacén Destino:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $destino), 1, 'L', false);

        $this->pdf->SetXY(10, $y + 10);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Usuario:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $usuario), 1, 'L', false);

        $x = $x + 90;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Fecha:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $fecha), 1, 'L', false);

        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 10, utf8_decode('Lista de productos del movimiento.'), 0, 1, 'L');

        $headers = ['Tipo de Producto', 'Producto', 'Cantidad', 'Serie', 'Estatus'];
        $widths = [50, 125, 25, 30, 30];
        $aligns = ['L', 'L', 'C', 'C', 'L'];
        $widthStandar = 260 / count($headers);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $push_right = 0;

        $this->pdf->SetFont('Arial', 'B', 10);
        foreach ($headers as $key => $value) {
            $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
            $this->pdf->MultiCell($w, 9, utf8_decode($value), 1, 'C', true);
            $push_right += $w;
            $this->pdf->SetXY($x + $push_right, $y);
        }
        $this->pdf->Ln();

        $fill = false;
        $this->pdf->SetFont('Arial', '', 8);
        foreach ($productosAux as $key => $value) {
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            if ($y >= 190) {
                $this->pdf->AddPage('L', 'Letter');
            }
            $h = 6;
            $push_right = 0;
            foreach ($value as $k => $v) {
                $w = (isset($widths[$k])) ? $widths[$k] : $widthStandar;
                $align = (isset($aligns[$k])) ? $aligns[$k] : $aligns[$k];
                $this->pdf->MultiCell($w, $h, utf8_decode($v), 1, $align, $fill);
                $h = $this->pdf->GetY() - $y;
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $fill = !$fill;
            $this->pdf->Ln();

            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            if ($y >= 190) {
                $this->pdf->AddPage('L', 'Letter');
            }
        }

        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', 'B', 12);


        $carpeta = $this->pdf->definirArchivo('traspasos', 'Movimiento_' . $id);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

    public function crearPDFAltaInicial($id) {
        $productos = $this->DB->getDetallesAltaInicial($id);
        $productosAux = [];
        $inicio = '';
        $termino = '';
        $almacen = '';
        $usuario = '';
        foreach ($productos as $key => $value) {
            $inicio = $value['FechaInicio'];
            $termino = $value['FechaTermino'];
            $usuario = $value['Usuario'];
            $almacen = $value['Almacen'];
            array_push($productosAux, [
                $value['TipoProducto'],
                $value['Producto'],
                $value['Cantidad'],
                $value['Serie'],
                $value['Estatus']
            ]);
        }

        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetFillColor(226, 231, 235);

        $this->pdf->AddPage('L', 'Letter');
        $this->pdf->titulo('Detalles de Alta Inicial ' . sprintf("%'.011d\n", $id));

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $this->pdf->SetXY($x, ($y - 10));

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Almacén:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $almacen), 1, 'L', false);

        $x = $x + 90;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Usuario:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $usuario), 1, 'L', false);

        $this->pdf->SetXY(10, $y + 10);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Inicio:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $inicio), 1, 'L', false);

        $x = $x + 90;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(40, 10, utf8_decode(' Termino:'), 1, 'L', true);

        $x = $x + 40;
        $this->pdf->SetXY($x, $y);

        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(90, 10, utf8_decode(" " . $termino), 1, 'L', false);

        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 10, utf8_decode('Lista de productos del alta inicial.'), 0, 1, 'L');

        $headers = ['Tipo de Producto', 'Producto', 'Cantidad', 'Serie', 'Estatus'];
        $widths = [45, 122, 23, 40, 30];
        $aligns = ['L', 'L', 'C', 'C', 'L'];
        $widthStandar = 260 / count($headers);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $push_right = 0;

        $this->pdf->SetFont('Arial', 'B', 10);
        foreach ($headers as $key => $value) {
            $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
            $this->pdf->MultiCell($w, 9, utf8_decode($value), 1, 'C', true);
            $push_right += $w;
            $this->pdf->SetXY($x + $push_right, $y);
        }
        $this->pdf->Ln();

        $fill = false;
        $this->pdf->SetFont('Arial', '', 8);
        foreach ($productosAux as $key => $value) {
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            if ($y >= 190) {
                $this->pdf->AddPage('L', 'Letter');
            }
            $h = 6;
            $push_right = 0;
            foreach ($value as $k => $v) {
                $w = (isset($widths[$k])) ? $widths[$k] : $widthStandar;
                $align = (isset($aligns[$k])) ? $aligns[$k] : $aligns[$k];
                $this->pdf->MultiCell($w, $h, utf8_decode($v), 1, $align, $fill);
                $h = $this->pdf->GetY() - $y;
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $fill = !$fill;
            $this->pdf->Ln();
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            if ($y >= 190) {
                $this->pdf->AddPage('L', 'Letter');
            }
        }

        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', 'B', 12);


        $carpeta = $this->pdf->definirArchivo('alta inicial', 'Alta_Inicial_' . $id);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

    public function mostrarFormularioHistorialEquipo() {
        return array(
            'html' => parent::getCI()->load->view('Almacen/Modal/FormularioHistorialEquipo', [], TRUE)
        );
    }

    public function mostrarHistorialEquipo(array $datos) {
        $movimientos = $this->DB->getMovimientosByAlmacen(0, ['serie' => $datos['id']]);
        return $movimientos;
    }

    public function cambiarEstatus(array $datos) {
        try {
            $this->DB->iniciaTransaccion();

            $return_array = ['code' => 400];
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $datosInventario = $this->DB->getInventarioId($datos['idInventario']);

            $this->DB->editarEstatusAlmacen($datos);
            $this->DB->movimientoInventario(array('idInventario' => $datos['idInventario'], 'tipoMovimiento' => 10));

            $this->DB->setHistorioIncentarioEstatus(array(
                'IdInventario' => $datos['idInventario'],
                'IdUsuario' => $this->usuario['Id'],
                'IdEstatusAnterior' => $datosInventario[0]['IdEstatus'],
                'IdEstatusNuevo' => $datos['idEstatus'],
                'FechaModifica' => $fecha));

            $invetarioPoliza = $this->DB->getInventarioPoliza($datos['idAlmacenVirtual']);

            if ($this->DB->estatusTransaccion() === FALSE) {
                $this->DB->roolbackTransaccion();
            } else {
                $this->DB->commitTransaccion();
                $return_array = ['code' => 200, 'message' => 'Correcto', 'datos' => $invetarioPoliza];
            }

            return $return_array;
        } catch (Exception $ex) {
            return ['code' => 400, 'message' => $ex];
        }
    }

    public function eliminarEvidenciaCatalogoModelo(array $datos) {
        $modelo = $this->catalogo->catModelosEquipo('6', $datos);
        $evidencias = explode(',', $modelo[0]['Archivos']);

        foreach ($evidencias as $key => $value) {
            if ($datos['key'] === $value) {
                unset($evidencias[$key]);
            }
        }
        
        if (eliminarArchivo($datos['key'])) {
            $evidencias = implode(',', $evidencias);
            $consulta = $this->catalogo->catModelosEquipo('7', array('Archivos' => $evidencias), array('id' => $datos['id']));
            
            if (!empty($consulta)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
