<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

class PDF extends \FPDF {

    private $B = 0;
    private $I = 0;
    private $U = 0;
    private $HREF = '';
    private $ALIGN = '';
    private $carpeta = '';
    private $dato;

    public function __construct(string $dato = '') {
        parent::__construct();
        $this->dato = $dato;
    }

    public function definirArchivo(string $carpeta, string $archivo) {
        $this->carpeta = './storage/Archivos/' . $carpeta;
        if (!file_exists($this->carpeta)) {
            mkdir($this->carpeta, 0777, true);
        }
        return $this->carpeta .= '/' . $archivo . '.pdf';
    }

    public function Header() {

        $this->Image('./assets/img/siccob-logo.png', 10, 8, 20);
        $this->SetFont('helvetica', 'B', 15);
        $this->SetXY(0, 13);
        $this->Cell(0, 0, utf8_decode('Resumen de Incidente Service Desk'), 0, 0, 'C');
        $this->SetXY(0, 20);
        $this->SetFont("helvetica", "I", 13);
        $this->Cell(0, 0, utf8_decode($this->dato), 0, 0, 'C');
        $this->Ln(20);
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
            } else {
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

    /*     * **************************************** */

    public function tituloTabla(string $titulo, array $colorFondoRGB = []) {
        $this->SetTextColor(255, 255, 255);
        if ($colorFondoRGB !== []) {
            $this->SetFillColor($colorFondoRGB[0], $colorFondoRGB[1], $colorFondoRGB[2]);
        } else {
            $this->SetFillColor(31, 56, 100);
        }
        $this->SetFont('', 'B', 12);
        $this->Cell(0, 6, utf8_decode($titulo), 1, 0, 'L', true);
    }

    public function tabla(array $cabecera, array $datos) {
        $this->Ln();
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(217, 217, 217);
        $this->SetTextColor(10, 10, 10);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 10);

        // Cabecera
        if ($cabecera !== []) {
            $ancho = 190 / count($cabecera);
            foreach ($cabecera as $value) {
                $this->Cell($ancho, 7, $value, 1, 0, 'C', true);
            }
            $this->Ln();
        }

        // Restauración de colores y fuentes
        $this->SetFillColor(226, 231, 235);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8);

        // Datos
        $fill = false;
        if ($cabecera !== []) {
            foreach ($datos as $row) {
                foreach ($row as $key => $value) {
                    if ($key === 0) {
                        $this->Cell($ancho, 7, utf8_decode($value), 'LR', 0, 'L', $fill);
                    } else {
                        $this->Cell($ancho, 7, utf8_decode($value), 'LR', 0, 'C', $fill);
                    }
                }
                $this->Ln();
                $fill = !$fill;
            }
        } else {
            foreach ($datos as $row) {
                foreach ($row as $key => $value) {
                    $this->SetFont('', 'B');
                    $this->Cell(30, 7, utf8_decode($key) . ': ', 'LR', 0, 'R', $fill);
                    $this->SetFont('', '');
                    $this->Cell(0, 7, utf8_decode($value), 'LR', 0, 'L', $fill);
                    $this->Ln();
                    $fill = !$fill;
                }
            }
        }
        // Línea de cierre
        $this->Cell(190, 0, '', 'T');
        $this->Ln(10);
    }

    public function tablaImagenes(array $imagenes) {
        $this->Ln(2);
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
                    $this->Image('.' . $imagen, $x, $y, 40, 35);
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

    public function firma(array $datosPersona) {
        $this->Ln(10);
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(217, 217, 217);
        $this->SetFont('Courier', 'BI', 12);
        $totalFirmas = count($datosPersona);

        if ($totalFirmas > 2) {
            $ancho = $this->GetPageWidth() - 20;
            $y = $this->GetY();
            $x = 30;

            if ((!is_null($datosPersona['FirmaTecnico']) && $datosPersona['FirmaTecnico'] != '')) {
                foreach ($datosPersona as $key) {
                    if ($x < $ancho) {
                        if ($this->compararNombreOArchivo($key)) {
                            $this->Image('.' . $key, $x, $y, 80, 30);
                        } else {
                            $this->SetXY($x, $y + 35);
                            $this->Cell(70, 7, utf8_decode($key), 1, 1, 'C', true);
                            $x += 80;
                        }
                    } else {
                        $x = 30;
                        $y += 50;
                    }
                    $altura = $y + 35;
                    if ($altura > ($this->GetPageHeight() - 40)) {
                        $this->AddPage();
                        $y = 25;
                    }
                }
            } else {
                $this->Image('.' . $datosPersona['Firma'], 60, $y, 80, 30);
                $this->SetXY(70, $y + 35);
                $this->Cell(70, 7, utf8_decode($datosPersona['NombreFirma']), 1, 1, 'C', true);
            }
            $this->SetY($y);
        } else {
            foreach ($datosPersona as $key) {
                if ($this->compararNombreOArchivo($key)) {
                    $this->SetX(65);
                    $this->Image('.' . $key, null, null, 80);
                } else {
                    $this->SetX(60);
                    $this->Cell(90, 7, utf8_decode($key), 1, 1, 'C', true);
                }
            }
        }
    }

    function compararNombreOArchivo($campo) {
        if (preg_match("[^/]", $campo)) {
            return true;
        } else {
            return false;
        }
    }

}
