<?php

namespace Librerias\Generales;

class CExcel {

    private $excel;

    public function __construct() {
        $this->excel = new \Librerias\Generales\Excel();
        return $this->excel;
    }

    public function getExcelObject() {
        return $this->excel;
    }

    public function createWorksheet($nombre) {
        $mySheet = new \PHPExcel_Worksheet($this->excel, $nombre);
        return $mySheet;
    }

    public function createSheet($nombre, $h) {
        $this->mySheet = new \PHPExcel_Worksheet($this->excel, $nombre);
        $this->excel->addSheet($this->mySheet, $h);
    }

    public function setActiveSheet($h) {
        $this->activeSheet = $this->excel->setActiveSheetIndex($h);
    }

    public function setTableSubtitles($l, $pos, $titles) {
        foreach ($titles as $key => $value) {
            $this->activeSheet->SetCellValue($l . $pos, $value);
            $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle('sub'));
            $l++;
        }
    }

    public function setColumnsWidth($l, $widths) {
        foreach ($widths as $key => $value) {
            $this->activeSheet->getColumnDimension($l)->setWidth($value);
            $l++;
        }
    }

    public function setTableTitle($cellBegin, $cellEnd, $title, $sty = []) {
        $this->activeSheet->mergeCells($cellBegin . ':' . $cellEnd);
        $this->activeSheet->SetCellValue($cellBegin, $title);
        $this->activeSheet->getStyle($cellBegin . ":" . $cellEnd)->applyFromArray($this->returnStyle('titulo'));
        if (count($sty) > 0) {
            foreach ($sty as $key => $value) {
                $this->activeSheet->getStyle($cellBegin . ":" . $cellEnd)->applyFromArray($this->returnStyle($value));
            }
        }
    }

    public function setTableContent($letter, $p, $contenido, $autoFiltro, $arrayAlign, $tipoValor = []) {
        if (count($contenido) > 0) {
            $pos = $p;
            foreach ($contenido as $kc => $row) {
                $l = $letter;
                $pos++;
                $styleFila = (($pos % 2) == 0) ? 'fila1' : 'fila2';
                $c = 0;
                foreach ($row as $kr => $valor) {
                    if (isset($tipoValor[$c]) && $tipoValor[$c] == 'link') {
                        if ($valor == '' || $valor == '-') {
                            $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle($styleFila));
                            $this->activeSheet->SetCellValue($l . $pos, $valor);
                        } else {
                            $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle($styleFila));
                            $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle('link'));
                            $this->activeSheet->SetCellValue($l . $pos, 'link');
                            $p_ = strpos($valor, '</a>');
                            if ($p_ !== false) {
                                $a_ = new \SimpleXMLElement($valor);
                                $valor = $a_['href'];
                            }
                            $this->activeSheet->getCell($l . $pos)->getHyperlink()->setUrl($valor);
                        }
                    } else {
                        if ($valor == '' || $valor == '-') {
                            $valor = ' - ';
                        }
                        $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle($styleFila));
                        $this->activeSheet->SetCellValue($l . $pos, $valor);
                    }
                    if (isset($arrayAlign[$c])) {
                        if ($arrayAlign[$c] != '') {
                            $this->activeSheet->getStyle($l . $pos)->applyFromArray($this->returnStyle($arrayAlign[$c]));
                        }
                    }

                    $l++;
                    $c++;
                }
            }

            $this->activeSheet->getStyle($letter . $p . ":" . $l . $pos)->getAlignment()->setWrapText(true);

            $lastLetter = chr(ord($l) - 1);
            if ($autoFiltro) {
                $this->activeSheet->setAutoFilter($letter . $p . ":" . $lastLetter . $pos);
            }
        }
    }

    public function removeLastSheet() {
        $sheetIndex = $this->excel->getIndex($this->excel->getSheetByName('Worksheet'));
        $this->excel->removeSheetByIndex($sheetIndex);
        $this->excel->setActiveSheetIndex(0);
    }

    public function saveFile($ruta) {
        $this->removeLastSheet();
        $objWriter = $this->createObjectWriter();
        $objWriter->save($ruta);
    }

    public function setCellContent($cellBegin, $cellEnd = '', $contenido, $sty = []) {
        if ($cellEnd == '') {
            $pos = $cellBegin;
        } else {
            $this->activeSheet->mergeCells($cellBegin . ':' . $cellEnd);
            $pos = $cellBegin . ':' . $cellEnd;
        }

        $this->activeSheet->SetCellValue($cellBegin, $contenido);
        if (count($sty) > 0) {
            foreach ($sty as $key => $value) {
                $this->activeSheet->getStyle($pos)->applyFromArray($this->returnStyle($value));
            }
        }
    }

    public function removeColumn($letter, $numCols) {
        $this->activeSheet->removeColumn($letter, $numCols);
    }

    public function freezePanels($letter, $numCol) {
        $this->activeSheet->freezePane($letter . $numCol);
    }

    public function createObjectWriter() {
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, "Excel2007");
        return $objWriter;
    }

    public function returnStyle($style) {
        switch ($style) {
            case 'titulo':
                return $this->sty_titulos;
                break;
            case 'sub':
                return $this->sty_subtitulos;
                break;
            case 'sub-small':
                return $this->sty_subtitulos_small;
                break;
            case 'gen-inventario':
                return $this->sty_generales_inventario;
                break;
            case 'total':
                return $this->sty_totales;
                break;
            case 'fila1':
                return $this->sty_fila1;
                break;
            case 'fila1-red':
                return $this->sty_fila1_red;
                break;
            case 'fila2':
                return $this->sty_fila2;
                break;
            case 'fila2-red':
                return $this->sty_fila2_red;
                break;
            case 'center':
                return $this->sty_center;
                break;
            case 'justify':
                return $this->sty_justify;
                break;
            case 'link':
                return $this->sty_link;
                break;
            default:
                # code...
                break;
        }
    }

    /* Apply new Style from array */

    private $sty_titulos = array(
        'font' => array(
            'name' => 'Calibri',
            'bold' => true,
            'size' => 15,
            'color' => array('rgb' => '44546A')
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'bottom' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THICK,
                'color' => array(
                    'rgb' => '4F81BD'
                )
            )
        )
    );
    public $sty_subtitulos = array(
        'font' => array(
            'name' => 'Calibri',
            'bold' => true,
            'size' => 11,
            'color' => array('rgb' => '44546A')
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    public $sty_subtitulos_small = array(
        'font' => array(
            'name' => 'Calibri',
            'bold' => true,
            'size' => 10,
            'color' => array('rgb' => '44546A')
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    public $sty_generales_inventario = array(
        'font' => array(
            'name' => 'Calibri',
            'bold' => true,
            'size' => 11,
            'color' => array('rgb' => '44546A')
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    public $sty_totales = array(
        'font' => array(
            'name' => 'Calibri',
            'bold' => true,
            'size' => 11,
            'color' => array('rgb' => '44546A')
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'bottom' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THICK,
                'color' => array(
                    'rgb' => '4F81BD'
                )
            )
        )
    );
    public $sty_fila1 = array(
        'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'B8CCE4')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            )
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    public $sty_fila1_red = array(
        'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F2DCDB')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            )
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'font' => array(
            'color' => array('rgb' => 'FF0000')
        ),
    );
    public $sty_fila2 = array(
        'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'DCE6F1')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            )
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    public $sty_fila2_red = array(
        'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E6B8B7')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            )
        ),
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'font' => array(
            'color' => array('rgb' => 'FF0000')
        ),
    );
    public $sty_center = array(
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
    public $sty_justify = array(
        'alignment' => array(
            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
        )
    );
    public $sty_link = array(
        'font' => array(
            'color' => array('rgb' => '0000FF'),
            'underline' => 'single'
        )
    );

}
