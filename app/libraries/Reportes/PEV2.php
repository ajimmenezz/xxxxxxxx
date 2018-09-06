<?php

namespace Librerias\Reportes;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\PDF as PDF;

class PEV2 extends General {

    private $DB;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_PEV2::factory();
        $this->pdf = new PDF();
    }

    public function getProyectosespeciales() {
        $proyectos = $this->DB->getProyectosespeciales();
        return $proyectos;
    }

    public function generaPDF(array $data = []) {
        $detallePuntos = $this->DB->getDetallePuntosProyectosEspeciales($data['datos'][0]);
        $detalleMaterial = $this->DB->getDetalleMaterialExtraProyectosEspeciales($data['datos'][0]);
        $evidencias = $this->DB->getEvidenciasProyectosEspeciales($data['datos'][0]);

        $this->pdf->AddPage();

        $this->pdf->SetXY(0, 5);
        $this->pdf->SetFont("helvetica", "I", 7);
        $this->pdf->Cell(0, 0, $data['datos'][1] . ' - ' . $data['datos'][2] . '   Pag. ' . $this->pdf->PageNo(), 0, 0, 'R');


        $this->pdf->Image('https://siccob.solutions/assets/img/siccob-logo.png', 10, 10, 20, 0, 'PNG');
        $this->pdf->SetXY(10, 17);
        $this->pdf->SetFont("helvetica", "B", 13);
        $this->pdf->Cell(0, 0, "Reporte de Proyecto Especial", 0, 1, 'C');
        $this->pdf->SetXY(10, 23);
        $this->pdf->SetFont("helvetica", "B", 12);
        $this->pdf->Cell(0, 0, utf8_decode($data['datos'][2]) . ' - SD:' . $data['datos'][1], 0, 1, 'C');

        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Text(10, 43, utf8_decode('Información General.'));
        $this->pdf->Line(8, 46, 202, 46);

        $this->pdf->SetFont("helvetica", "B", 9);
        $this->pdf->Text(10, 55, 'No. Ticket');
        $this->pdf->Text(35, 55, 'Folio SD');
        $this->pdf->Text(60, 55, 'Sucursal');
        $this->pdf->Text(130, 55, utf8_decode('Técnico Asignado'));

        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Text(10, 60, $data['datos'][0]);
        $this->pdf->Text(35, 60, $data['datos'][1]);
        $this->pdf->Text(60, 60, utf8_decode($data['datos'][2]));
        $this->pdf->Text(130, 60, utf8_decode($data['datos'][3]));


        $this->pdf->SetFont("helvetica", "B", 9);
        $this->pdf->Text(10, 70, 'Estatus');
        $this->pdf->Text(62, 70, 'Tipo de Proyecto');
        $this->pdf->Text(104, 70, utf8_decode('Categoría'));
        $this->pdf->Text(151, 70, 'Actividad');

        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Text(10, 75, utf8_decode($data['datos'][4]));
        $this->pdf->Text(62, 75, utf8_decode($data['datos'][5]));
        $this->pdf->Text(104, 75, utf8_decode($data['datos'][6]));
        $this->pdf->Text(151, 75, utf8_decode($data['datos'][7]));

        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Text(10, 92, utf8_decode('Detalle de Puntos.'));
        $this->pdf->Line(8, 95, 202, 95);

        $this->pdf->SetFillColor(226, 231, 235);

        $this->pdf->SetXY(10, 100);
        $this->pdf->SetFont("helvetica", "B", 7);
        $this->pdf->MultiCell(33, 9, utf8_decode('Ubicación'), '1', 'C', true);
        $this->pdf->SetXY(43, 100);
        $this->pdf->MultiCell(12, 9, utf8_decode('Datos'), '1', 'C', true);
        $this->pdf->SetXY(55, 100);
        $this->pdf->MultiCell(12, 9, utf8_decode('Extra'), '1', 'C', true);
        $this->pdf->SetXY(67, 100);
        $this->pdf->MultiCell(12, 9, utf8_decode('Voz'), '1', 'C', true);
        $this->pdf->SetXY(79, 100);
        $this->pdf->MultiCell(12, 9, utf8_decode('Video'), '1', 'C', true);
        $this->pdf->SetXY(91, 100);
        $this->pdf->MultiCell(15, 9, utf8_decode('Jacks Cat6'), '1', 'C', true);
        $this->pdf->SetXY(106, 100);
        $this->pdf->MultiCell(13, 4.5, utf8_decode('Tapa 1 Mod'), '1', 'C', true);
        $this->pdf->SetXY(119, 100);
        $this->pdf->MultiCell(13, 4.5, utf8_decode('Tapa 2 Mod'), '1', 'C', true);
        $this->pdf->SetXY(132, 100);
        $this->pdf->MultiCell(14, 3, utf8_decode('Tapa Contra Agua'), '1', 'C', true);
        $this->pdf->SetXY(146, 100);
        $this->pdf->MultiCell(13, 4.5, utf8_decode('Patch 7Pies'), '1', 'C', true);
        $this->pdf->SetXY(159, 100);
        $this->pdf->MultiCell(14, 9, utf8_decode('Plug RJ45'), '1', 'C', true);
        $this->pdf->SetXY(173, 100);
        $this->pdf->MultiCell(13, 4.5, utf8_decode('Patch 3Pies'), '1', 'C', true);
        $this->pdf->SetXY(186, 100);
        $this->pdf->MultiCell(15, 9, utf8_decode('Total MTS'), '1', 'C', true);


        $fill = false;
        $totales = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0
        ];

        foreach ($detallePuntos as $key => $value) {
            $y = $this->pdf->GetY();
            $this->pdf->SetXY(10, $y);
            $this->pdf->SetFont("helvetica", "", 6);
            $this->pdf->MultiCell(33, 5, utf8_decode($value['UBICACION']), '1', 'C', $fill);
            $this->pdf->SetFont("helvetica", "", 7);

            $this->pdf->SetXY(43, $y);
            $this->pdf->MultiCell(12, 5, utf8_decode($value['DATOS']), '1', 'C', $fill);
            $totales['1'] = $totales['1'] + $value['DATOS'];

            $this->pdf->SetXY(55, $y);
            $this->pdf->MultiCell(12, 5, utf8_decode($value['EXTRAS']), '1', 'C', $fill);
            $totales['2'] = $totales['2'] + $value['EXTRAS'];

            $this->pdf->SetXY(67, $y);
            $this->pdf->MultiCell(12, 5, utf8_decode($value['VOZ']), '1', 'C', $fill);
            $totales['3'] = $totales['3'] + $value['VOZ'];

            $this->pdf->SetXY(79, $y);
            $this->pdf->MultiCell(12, 5, utf8_decode($value['VIDEO']), '1', 'C', $fill);
            $totales['4'] = $totales['4'] + $value['VIDEO'];

            $this->pdf->SetXY(91, $y);
            $this->pdf->MultiCell(15, 5, utf8_decode($value['JACKS CAT 6']), '1', 'C', $fill);
            $totales['5'] = $totales['5'] + $value['JACKS CAT 6'];

            $this->pdf->SetXY(106, $y);
            $this->pdf->MultiCell(13, 5, utf8_decode($value['TAPA 1 MODULO']), '1', 'C', $fill);
            $totales['6'] = $totales['6'] + $value['TAPA 1 MODULO'];

            $this->pdf->SetXY(119, $y);
            $this->pdf->MultiCell(13, 5, utf8_decode($value['TAPA 2 MODULOS']), '1', 'C', $fill);
            $totales['7'] = $totales['7'] + $value['TAPA 2 MODULOS'];

            $this->pdf->SetXY(132, $y);
            $this->pdf->MultiCell(14, 5, utf8_decode($value['TAPA CONTRA AGUA']), '1', 'C', $fill);
            $totales['8'] = $totales['8'] + $value['TAPA CONTRA AGUA'];

            $this->pdf->SetXY(146, $y);
            $this->pdf->MultiCell(13, 5, utf8_decode($value['PATCH CORD 7 PIES']), '1', 'C', $fill);
            $totales['9'] = $totales['9'] + $value['PATCH CORD 7 PIES'];

            $this->pdf->SetXY(159, $y);
            $this->pdf->MultiCell(14, 5, utf8_decode($value['PLUG RJ45']), '1', 'C', $fill);
            $totales['10'] = $totales['10'] + $value['PLUG RJ45'];

            $this->pdf->SetXY(173, $y);
            $this->pdf->MultiCell(13, 5, utf8_decode($value['PATCH CORD 3 PIES']), '1', 'C', $fill);
            $totales['11'] = $totales['11'] + $value['PATCH CORD 3 PIES'];

            $this->pdf->SetXY(186, $y);
            $this->pdf->MultiCell(15, 5, utf8_decode($value['TOTAL MTS']), '1', 'C', $fill);
            $totales['12'] = $totales['12'] + $value['TOTAL MTS'];

            $fill = !$fill;
        }


        $y = $this->pdf->GetY();
        $this->pdf->SetXY(10, $y);
        $this->pdf->SetFont("helvetica", "B", 8);
        $this->pdf->MultiCell(33, 5, "TOTALES", '1', 'C', $fill);
        $this->pdf->SetFont("helvetica", "B", 8);
        $this->pdf->SetXY(43, $y);
        $this->pdf->MultiCell(12, 5, $totales['1'], '1', 'C', $fill);
        $this->pdf->SetXY(55, $y);
        $this->pdf->MultiCell(12, 5, $totales['2'], '1', 'C', $fill);
        $this->pdf->SetXY(67, $y);
        $this->pdf->MultiCell(12, 5, $totales['3'], '1', 'C', $fill);
        $this->pdf->SetXY(79, $y);
        $this->pdf->MultiCell(12, 5, $totales['4'], '1', 'C', $fill);
        $this->pdf->SetXY(91, $y);
        $this->pdf->MultiCell(15, 5, $totales['5'], '1', 'C', $fill);
        $this->pdf->SetXY(106, $y);
        $this->pdf->MultiCell(13, 5, $totales['6'], '1', 'C', $fill);
        $this->pdf->SetXY(119, $y);
        $this->pdf->MultiCell(13, 5, $totales['7'], '1', 'C', $fill);
        $this->pdf->SetXY(132, $y);
        $this->pdf->MultiCell(14, 5, $totales['8'], '1', 'C', $fill);
        $this->pdf->SetXY(146, $y);
        $this->pdf->MultiCell(13, 5, $totales['9'], '1', 'C', $fill);
        $this->pdf->SetXY(159, $y);
        $this->pdf->MultiCell(14, 5, $totales['10'], '1', 'C', $fill);
        $this->pdf->SetXY(173, $y);
        $this->pdf->MultiCell(13, 5, $totales['11'], '1', 'C', $fill);
        $this->pdf->SetXY(186, $y);
        $this->pdf->MultiCell(15, 5, $totales['12'], '1', 'C', $fill);

        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        if ($detalleMaterial[0]['concepto'] !== "") {
            $this->pdf->SetFont("helvetica", "B", 10);
            $this->pdf->Text($x, $y + 10, utf8_decode('Detalle de Material Extra.'));
            $this->pdf->Line($x - 2, $y + 13, 202, $y + 13);

            $this->pdf->SetXY(10, $y + 18);
            $this->pdf->SetFont("helvetica", "B", 7);
            $this->pdf->MultiCell(80, 9, utf8_decode('Concepto'), '1', 'C', true);
            $this->pdf->SetXY(90, $this->pdf->GetY() - 9);
            $this->pdf->MultiCell(20, 9, utf8_decode('Cantidad'), '1', 'C', true);

            $this->pdf->SetFont("helvetica", "", 6);

            $fill = false;
            foreach ($detalleMaterial as $key => $value) {
                $this->pdf->MultiCell(80, 6, utf8_decode($value['concepto']), '1', 'L', $fill);
                $this->pdf->SetXY(90, $this->pdf->GetY() - 6);
                $this->pdf->MultiCell(20, 6, utf8_decode($value['cantidad']), '1', 'C', $fill);
                $fill = !$fill;
            }
        }

        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Text(10, $this->pdf->GetY() + 10, utf8_decode('Evidencias.'));
        $this->pdf->Line(8, $this->pdf->GetY() + 13, 202, $this->pdf->GetY() + 13);

        $this->pdf->Ln();

        $this->pdf->SetXY(10, $this->pdf->GetY() + 72);

        $this->pdf->SetAutoPageBreak(false);

        $cont = 0;
        $contFiles = 0;
        foreach ($evidencias as $key => $value) {
            $cont++;
            $contFiles++;
            $y = $this->pdf->GetY() - 50;
            switch ($cont) {
                case 1:
                    $x = 10;
                    break;
                case 2:
                    $x = 73;
                    break;
                case 3:
                    $x = 136;
                    break;
            }
            $this->pdf->SetXY($x, $y);
            $this->pdf->SetFont("helvetica", "B", 9);
            $this->pdf->Text($this->pdf->GetX(), $this->pdf->GetY() - 3, utf8_decode($value['Nombre']));
            $this->pdf->MultiCell(60, 50, $this->pdf->Image($value['URL'], $this->pdf->GetX(), $this->pdf->GetY(), 60, 50, '', $value['URL']), 1);
            if ($cont == 3 && $contFiles < count($evidencias)) {
                if ($this->pdf->GetY() + 50 > 294) {
                    $this->pdf->AddPage();
                    $this->pdf->SetXY(0, 5);
                    $this->pdf->SetFont("helvetica", "I", 7);
                    $this->pdf->Cell(0, 0, $data['datos'][1] . ' - ' . $data['datos'][2] . '   Pag. ' . $this->pdf->PageNo(), 0, 0, 'R');

                    $this->pdf->SetXY(10, 70);
                } else {
                    $this->pdf->SetXY(10, $y + 110);
                }
                $cont = 0;
            }
        }

        $carpeta = $this->pdf->definirArchivo('PEV2/Reportes', 'Proyectos_Especiales_' . $data['datos'][1] . '_' . $data['datos'][0]);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows')) {
            $carpeta = str_replace("Proyecto_Especiales_", "Proyectos_Especiales_", $carpeta);
        } else {
            exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . str_replace("Proyecto_Especiales_", "Proyectos_Especiales_", $carpeta) . ' ' . $carpeta);
//            unlink($carpeta);
            $carpeta = str_replace("Proyecto_Especiales_", "Proyectos_Especiales_", $carpeta);
        }
        return $carpeta;
    }

}
