<?php

namespace Librerias\Salas4D;

use Controladores\Controller_Base_General as General;
use Librerias\Proyectos\PDF as PDF;

class Inventario extends General {

    private $DB;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Salas4D::factory();
        $this->pdf = new PDF();
    }

    public function mostrarInventarioSucursal(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'ids' => (isset($datos['ids'])) ? $datos['ids'] : ''
        ];

        if (!empty($datos)) {
            $data = [
                'elementos' => $this->DB->getElementosSucursal($datos['id']),
                'subelementos' => $this->DB->getSubelementosSucursal($datos['id'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/InventarioSucursal', $data, TRUE);
            $returnArray['code'] = 200;
        }

        return $returnArray;
    }

    public function mostrarFormularioAgregarElemento() {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'error' => ""
        ];

        try {
            $data = [
                'ubicaciones' => $this->DB->getUbicaciones(),
                'sistemas' => $this->DB->getSistemas(),
                'elementos' => $this->DB->getElementos()
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/FormularioAgregarElemento', $data, TRUE);
            $returnArray['code'] = 200;
        } catch (Exception $e) {
            $returnArray['error'] = $e->getMessage();
        }


        return $returnArray;
    }

    public function guardaElementos(array $datos) {
        $returnArray = [
            'code' => 400,
            'error' => ""
        ];

        $result = $this->DB->insertaElementos($datos);
        $ids = implode(",", $result['ids']);

        if ($result['code'] == '200') {
            return $this->mostrarInventarioSucursal(['id' => $datos['sucursal'], 'ids' => $ids]);
        }

        return $returnArray;
    }

    public function guardaSubelementos(array $datos) {
        $returnArray = [
            'code' => 200,
            'error' => ""
        ];

        $result = $this->DB->insertaSublemento($datos['data']);
        $returnArray['code'] = $result['code'];
        $returnArray['id'] = $result['id'];

        return $returnArray;
    }

    public function guardaElementosFoto(array $datos) {
        $returnArray = [
            'code' => 400,
            'error' => ""
        ];

        $archivos = null;
        $CI = parent::getCI();
        $carpeta = 'inventario4D/' . $datos['sucursal'] . '/elementos/';

        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosElemento', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
                $returnArray['code'] = 200;
                $returnArray['files'] = $archivos;
            } else {
                $returnArray['code'] = 500;
            }
        }

        return $returnArray;
    }

    public function guardaSubelementosFoto(array $datos) {
        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'inventario4D/' . $datos['sucursal'] . '/subelementos/';

        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosSubelemento', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
                $result = $this->DB->insertaSublemento($datos, $archivos);
            }
        } else {
            $result = $this->DB->insertaSublemento($datos);
        }
//
        return ['archivos' => $archivos, 'id' => $result];
    }

    public function mostrarFormularioAgregarSubelementos(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'error' => ""
        ];

        try {
            $data = [
                'elementos' => $this->DB->getElementosRegistrados($datos['ids'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/FormularioAgregarSubelementos', $data, TRUE);
            $returnArray['code'] = 200;
        } catch (Exception $e) {
            $returnArray['error'] = $e->getMessage();
        }


        return $returnArray;
    }

    public function mostrarListaSubelementos(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'error' => ""
        ];

        try {
            $data = [
                'subelementos' => $this->DB->getSublementosByRegistro($datos['elemento'])
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/ListaSubelementos', $data, TRUE);
            $returnArray['code'] = 200;
        } catch (Exception $e) {
            $returnArray['error'] = $e->getMessage();
        }


        return $returnArray;
    }

    public function cargaInfoElemento(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'error' => ""
        ];

        try {
            $data = [
                'detalles' => $this->DB->getDetallesElemento($datos['data']['registro'])[0],
                'subelementos' => $this->DB->getCatalogoSublementosByRegistro($datos['data']['registro']),
                'subelementosRegistrados' => $this->DB->getSubelementosByRegistro($datos['data']['registro']),
                'ubicaciones' => $this->DB->getUbicaciones(),
                'sistemas' => $this->DB->getSistemas(),
                'elementos' => $this->DB->getElementos()
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/FormularioAgregarSubelementos', $data, TRUE);
            $returnArray['code'] = 200;
        } catch (Exception $e) {
            $returnArray['error'] = $e->getMessage();
        }


        return $returnArray;
    }

    public function cargaInfoSubelemento(array $datos) {
        $returnArray = [
            'html' => "",
            'code' => 400,
            'error' => ""
        ];

        try {
            $data = [
                'detalles' => $this->DB->getDetallesSubelemento($datos['data']['registro'])[0],
//                'subelementos' => $this->DB->getCatalogoSublementosByRegistro($datos['data']['registro']),
//                'subelementosRegistrados' => $this->DB->getSubelementosByRegistro($datos['data']['registro']),
//                'ubicaciones' => $this->DB->getUbicaciones(),
//                'sistemas' => $this->DB->getSistemas(),
//                'elementos' => $this->DB->getElementos()
            ];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/DetallesSubelemento', $data, TRUE);
            $returnArray['code'] = 200;
        } catch (Exception $e) {
            $returnArray['error'] = $e->getMessage();
        }


        return $returnArray;
    }

    public function eliminarElemento(array $datos) {
        $returnArray = [
            'code' => '500',
            'error' => ""
        ];

        $delete = $this->DB->eliminarElemento($datos['id']);
        $returnArray['error'] = $delete['error'];
        $returnArray['code'] = $delete['code'];

        if ($returnArray['code'] == 200) {
            $data = ['elementos' => $this->DB->getElementosSucursal($datos['sucursal'])];
            $returnArray['html'] = parent::getCI()->load->view('Salas4D/Modal/InventarioSucursal', $data, TRUE);
        }


        return $returnArray;
    }

    public function eliminarSubelemento(array $datos) {
        $returnArray = [
            'code' => '500',
            'error' => ""
        ];

        $delete = $this->DB->eliminarSubelemento($datos['id']);
        $returnArray['error'] = $delete['error'];
        $returnArray['code'] = $delete['code'];

        return $returnArray;
    }

    public function eliminarArchivoElemento(array $datos) {
        $path = $datos['key'];
        $elemento = $datos['extra']['elemento'];
        $detalles = $this->DB->getDetallesElemento($elemento)[0];

        $arrayImagenes = [];
        $arrayNuevoImagenes = [];
        if ($detalles['Imagen'] != '') {
            $arrayImagenes = explode(",", $detalles['Imagen']);
            foreach ($arrayImagenes as $key => $value) {
                if ($value != $path) {
                    array_push($arrayNuevoImagenes, $value);
                }
            }
        }

        $nuevoString = implode(",", $arrayNuevoImagenes);
        $result = $this->DB->actualizaImagenesElemento($elemento, $nuevoString);

        return $result;
    }

    public function guardaCambiosElemento(array $datos) {
        $elemento = $datos['registro'];
        $detalles = $this->DB->getDetallesElemento($elemento)[0];
        $arrayImagenes = [];
        if ($detalles['Imagen'] != '') {
            $arrayImagenes = explode(",", $detalles['Imagen']);
        }

        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = 'inventario4D/' . $datos['sucursal'] . '/elementos/';

        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosEditarElemento', $carpeta);
            if ($archivos) {
                if (!empty($arrayImagenes)) {
                    $archivos = array_merge($arrayImagenes, $archivos);
                }
                $archivos = implode(',', $archivos);
                $result = $this->DB->guardaCambiosElemento($datos, $archivos);
            }
        } else {
            $archivos = '';
            if (!empty($arrayImagenes)) {
                $archivos = implode(',', $arrayImagenes);
            }
            $result = $this->DB->guardaCambiosElemento($datos, $archivos);
        }
//
        return $result;
    }

    public function crearReporte(array $datos) {
        $elementos = $this->DB->getElementosSucursal($datos['idsucursal']);
        $elementosAux = [];
        foreach ($elementos as $key => $value) {
            array_push($elementosAux, [
                str_replace(" - ", ' ', $value['Elemento']),
                $value['Serie'],
                $value['ClaveCinemex'],
                $value['Ubicacion'],
                $value['Sistema']
            ]);
        }

        $subelementos = $this->DB->getSubelementosSucursal($datos['idsucursal']);
        $subelementosAux = [];
        foreach ($subelementos as $key => $value) {
            array_push($subelementosAux, [
                str_replace(" - ", ' ', $value['Subelemento']),
                str_replace(" - ", ' ', $value['Elemento']),
                $value['Serie'],
                $value['ClaveCinemex'],
                $value['Ubicacion'],
                $value['Sistema']
            ]);
        }

        $this->pdf->SetAutoPageBreak(false);
        $this->pdf->SetFillColor(226, 231, 235);

        $this->pdf->AddPage('L', 'Letter');
        $this->pdf->titulo('Inventario Sala 4D - ' . $datos['sucursal']);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 10, utf8_decode('Lista de elementos en la sucursal.'), 0, 1, 'L');

        $headers = ['Elemento', 'No. Serie', 'Clave Cinemex', 'Ubicación', 'Sistema'];
        $widths = [78, 30, 30, 61, 61];
        $aligns = ['L', 'C', 'C', 'L', 'L'];
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
        foreach ($elementosAux as $key => $value) {
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
        }

        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 10, utf8_decode('Lista de sub-elementos en la sucursal.'), 0, 1, 'L');


        $headers = ['Subelemento', 'Elemento', 'No. Serie', 'Clave Cinemex', 'Ubicación', 'Sistema'];
        $widths = [30, 70, 30, 30, 50, 50];
        $aligns = ['L', 'L', 'C', 'C', 'L', 'L'];
        $widthStandar = 260 / count($headers);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        if ($y >= 190) {
            $this->pdf->AddPage('L', 'Letter');
            $y = $this->pdf->GetY();
        }
        $push_right = 0;

        $this->pdf->SetFont('Arial', 'B', 10);
        foreach ($headers as $key => $value) {
            $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
            $this->pdf->MultiCell($w, 9, utf8_decode($value), 1, 'C', true);
            $push_right += $w;
            $this->pdf->SetXY($x + $push_right, $y);
        }
        $this->pdf->Ln();


        $this->pdf->SetFont('Arial', '', 8);
        foreach ($subelementosAux as $key => $value) {
            $x = $this->pdf->GetX();
            $y = $this->pdf->GetY();
            if ($y >= 190) {
                $this->pdf->AddPage('L', 'Letter');
                $y = $this->pdf->GetY();
            }
            $h = 6;
            $push_right = 0;
            foreach ($value as $k => $v) {
                $w = (isset($widths[$k])) ? $widths[$k] : $widthStandar;
                $align = (isset($aligns[$k])) ? $aligns[$k] : $aligns[$k];
                $this->pdf->MultiCell($w, $h, utf8_decode($v), 1, $align, $fill);
                $h = $this->pdf->GetY() - $y;
                if ($y >= 190) {
                    $this->pdf->AddPage('L', 'Letter');
                    $y = $this->pdf->GetY();
                }
                $push_right += $w;
                $this->pdf->SetXY($x + $push_right, $y);
            }
            $fill = !$fill;
            $this->pdf->Ln();
        }
        
        $carpeta = $this->pdf->definirArchivo('inventario4D/' . $datos['idsucursal'], 'inventario_sala4d_' . $datos['sucursal']);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

}
