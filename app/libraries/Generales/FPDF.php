<?php

namespace Librerias\Generales;

use Librerias\Generales\PDF as PDF;

class FPDF extends PDF {

    private $contenidoHeader;

    public function __construct($contenido, $orientation = 'P', $unit = 'mm', $size = 'A4') {
        parent::__construct($orientation, $unit, $size);
        $this->contenidoHeader = $contenido;
    }

    public function Header() {
        $this->SetFont('Helvetica', '', 8.4);
        $this->Image('./assets/img/siccob-logo.png', 13, 8, 13, 15, 'PNG');
        $this->SetXY(25, 12);
        $this->MultiCell(0, 5, $this->contenidoHeader, 0, 'R');
    }

    public function subTitulo(string $titulo) {
        $this->Ln();
        $this->SetFont("helvetica", "", 9);
        $this->Cell(0, 10, utf8_decode($titulo));
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
    }

    public function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(68, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

    public function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    // Tabla simple
    public function BasicTable($header, $data) {
        $this->Ln(3);
        $ancho = ($this->GetPageWidth() - 20) / count($header);
        // Cabecera
        foreach ($header as $col) {
            $this->SetFont("Helvetica", "B", 9);
            $this->Cell($ancho, 7, utf8_decode($col), 0);
        }
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->SetFont("Helvetica", "", 10);
                $this->Cell($ancho, 6, utf8_decode($col), 0);
            }
            $this->Ln();
        }
    }

    public function multiceldaConTitulo($titulo, $txt) {
        $this->Ln();
        $this->SetFont("Helvetica", "B", 9);
        $this->Cell(0, 7, utf8_decode($titulo));
        $this->Ln(4);
        $this->SetFont("Helvetica", "", 10);
        $this->MultiCell(0, 7, utf8_decode($txt));
    }

    public function imagenConTiuloYSubtitulo($url, $titulo, $subtitulo, $y) {
        $this->Ln();
        $this->SetFont("Helvetica", "B", 9);
        $this->Cell(0, 7, $titulo, 0, 0, 'C');
        $this->Ln(4);
        $x = ($this->GetPageWidth() - 54) / 2;
        $this->Image("." . $url, $x, $y, 60, 0, 'PNG');
        $y = $this->GetY() + 40;
        $this->SetY($y);
        $this->SetFont("Helvetica", "", 10);
        $this->Cell(0, 7, $subtitulo, 0, 0, 'C');
    }

    public function tablaImagenes(array $imagenes) {
        $this->Ln(7);
        $countFilas = ((count($imagenes) / 4) < 0.5) ? round(count($imagenes) / 4, 0, PHP_ROUND_HALF_UP) + 1 : ceil(count($imagenes) / 4);
        $columna = 0;
        $listaImagenes = array();
        $tempImagenes = array();

        for ($j = 0; $j < $countFilas; $j++) {

            foreach ($imagenes as $key => $imagen) {
                if ($columna < 4) {
                    array_push($tempImagenes, $imagen);
                    $columna += 1;
                    unset($imagenes[$key]);
                }
            }
            array_push($listaImagenes, $tempImagenes);
            $tempImagenes = array();
            $columna = 0;
        }

        //insertar imagenes
        $ancho = $this->GetPageWidth() - 20;
        $y = $this->GetY();
        $x = 10;
        foreach ($listaImagenes as $imagenes) {
            foreach ($imagenes as $imagen) {
                if ($x < $ancho) {
                    $this->Image('.' . $imagen, $x, $y, 40, 35, 'JPG');
                    $x += 50;
                }
            }
            $x = 10;
            $y += 40;
            $altura = $y + 35;
            if ($altura > ($this->GetPageHeight() - 40)) {
                $this->AddPage();
                $y = 25;
            }
        }
        $this->SetY($y);
    }

}