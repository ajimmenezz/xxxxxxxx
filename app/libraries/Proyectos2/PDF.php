<?php

namespace Librerias\Proyectos;

use \FPDF;

class PDF extends \FPDF {

    private $B = 0;
    private $I = 0;
    private $U = 0;
    private $HREF = '';
    private $ALIGN = '';
    private $carpeta = '';

    public function definirArchivo(string $carpeta, string $archivo) {
        $this->carpeta = './storage/Archivos/' . $carpeta;
        if (!file_exists($this->carpeta)) {
            mkdir($this->carpeta, 0777, true);
        }
        return $this->carpeta .= '/' . $archivo . '.pdf';
    }

    public function Header() {

        $this->Image('./assets/img/siccob-logo.png', 10, 14, 14);
        $this->SetFont('Arial', 'I', 9);
        $this->Ln(10);
        $this->Cell(90);
        $this->Cell(100, 14, 'Soluciones Integrales para empresas integrables', 0, 0, 'R');
        $this->Ln(5);
        $this->WriteHTML('<p align="center"><hr></p>');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 9);
        $fecha = date('d-m-Y');
        $this->Cell(100, 9, 'Fecha:' . $fecha, 0, 0, 'L');
        $this->Cell(0, 9, utf8_decode('Página ' . $this->PageNo()), 0, 0, 'R');
    }

    public function WriteHTML($html) {
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                elseif ($this->ALIGN == 'center')
                    $this->Cell(0, 5, $e, 0, 1, 'C');
                else
                    $this->Write(5, $e);
            }
            else {
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    //Extract properties
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $prop = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $prop[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $prop);
                }
            }
        }
    }

    private function OpenTag($tag, $prop) {
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $prop['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
        if ($tag == 'P')
            $this->ALIGN = $prop['ALIGN'];
        if ($tag == 'HR') {
            if (!empty($prop['WIDTH']))
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin - $this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x, $y, $x + $Width, $y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    private function CloseTag($tag) {
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
        if ($tag == 'P')
            $this->ALIGN = '';
    }

    private function SetStyle($tag, $enable) {
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s)
            if ($this->$s > 0)
                $style .= $s;
        $this->SetFont('', $style);
    }

    private function PutLink($URL, $txt) {
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

    public function titulo(string $titulo) {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
        $this->Ln();
    }

    public function subTitulo(string $titulo) {
        $this->Ln();
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode($titulo), 0, 1, 'L');
    }

    public function parrafo(string $parrafo) {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 5, utf8_decode($parrafo), 0, 'L');
    }

    public function lista(array $lista, string $alineacion = 'L') {
        $this->Ln();
        foreach ($lista as $key => $value) {
            $this->SetX(20);
            $this->Cell(0, 5, ($key + 1) . '.- ' . utf8_decode($value), 0, 1, $alineacion);
        }
        $this->Ln();
    }

    public function firma(string $nombre, string $puesto) {
        $this->Ln(30);
        $this->SetFont('Courier', 'BI', 12);
        $this->Cell(0, 5, utf8_decode($nombre), 0, 1, 'L');
        $this->SetFont('Courier', 'BUI', 12);
        $this->Cell(0, 5, utf8_decode($puesto), 0, 1, 'L');
    }

    public function table(array $cabecera, array $data) {
        $this->Ln();
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(112, 116, 120);
        $this->SetDrawColor(226, 231, 235);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        
        // Cabecera
        $ancho = 190 / count($cabecera);
        foreach ($cabecera as $value) {
            $this->Cell($ancho, 7, $value, 1, 0, 'C', true);
        }
        $this->Ln();

        // Restauración de colores y fuentes
        $this->SetFillColor(226, 231, 235);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8);
        
        // Datos
        $fill = false;
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if ($key === 0) {
                    $this->Cell($ancho, 7, $value, 'LR', 0, 'L', $fill);
                }else{
                    $this->Cell($ancho, 7, $value, 'LR', 0, 'C', $fill);
                }
            }
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->Cell(190, 0, '', 'T');
        $this->Ln(10);
    }

}
