<?php

namespace Librerias\Generales;

class Excel extends \PHPExcel {

    private $LibroExcel;
    private $Archivo;

    public function __construct(string $archivo = null) {
        parent::__construct();
        if (!empty($archivo)) {
            $this->Archivo = $archivo;
            $objeto = \PHPExcel_IOFactory::createReaderForFile($archivo);
            $this->LibroExcel = $objeto->load($archivo);
        }
    }

    /*
     * Metodo para algun catalogo en una hoja de excel
     * 
     * @param array $lista recibe el arreglo para insertarlo en el formato de excel 
     * @param int $hoja recibe el numero de la hoja del formato de excel
     * @param string $celda recibe la columna
     */

    public function cargandoCatalogo(array $lista, int $hoja, string $celda) {
        $this->LibroExcel->getSheet($hoja)->fromArray($lista, NULL, $celda);
    }

    /*
     * Metodo para crear una lista de seleccion
     * 
     * @param int $numHoja recibe el numero de la hoja de Excel 
     * @param string $columna recibe el nombre de la columna
     * @param string $fila recibe el nombre de la fila
     * @param int $numFilas recibe el numero de filas
     * @param string $nombreHoja recibe el nombre de la hoja de Excel
     */

    public function crearSelectCeldasExcel(int $numHoja, string $columna, string $fila, int $numFilas, string $nombreHoja) {
        $hoja = $this->LibroExcel->getSheet($numHoja);
        for ($i = $fila; $i <= $numFilas; $i++) {
            ${"objValidation" . $i} = $hoja->getCell($columna . $i)->getDataValidation();
            ${"objValidation" . $i}->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
            ${"objValidation" . $i}->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_STOP);
            ${"objValidation" . $i}->setAllowBlank(false);
            ${"objValidation" . $i}->setShowInputMessage(true);
            ${"objValidation" . $i}->setShowErrorMessage(true);
            ${"objValidation" . $i}->setShowDropDown(true);
            ${"objValidation" . $i}->setErrorTitle('Error');
            ${"objValidation" . $i}->setError('El Articulo no esta en la lista.');
            ${"objValidation" . $i}->setPromptTitle('Articulos');
            ${"objValidation" . $i}->setPrompt('Seleccione una opción de la lista');
            ${"objValidation" . $i}->setFormula1($nombreHoja);
        }
    }

    /*
     * Metodo para descargar el formato de Excel
     * 
     */

    public function actualizarArchivoExcel() {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Plantilla_Equipos_SAE.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($this->LibroExcel, 'Excel2007');
        $objWriter->save($this->Archivo);
    }

    /*
     * Metodo para crear el arreglo para insertar en el excel
     * 
     * @param int $hoja recibe el numero de la hoja de excel
     * @param string $rango recibe el rango 
     * @return data devuelve un array con los valores 
     */

    public function obteniendoArregloExcel(int $hoja, string $rango) {
        $data = array();
        $hoja = $this->LibroExcel->setActiveSheetIndex($hoja);
        $arreglo = $hoja->rangeToArray($rango);
        $contador = 1;
        foreach ($arreglo as $value) {
            if ($value[0] !== 0) {
                if ($value[5] === NULL) {
                    $value[5] = 'sin serie ' . $contador;
                    $contador ++;
                }
                $key = array_search($value[5], array_column($data, 'Serie'));
                if (!is_int($key)) {
                    array_push($data, array('IdTipoEquipo' => $value[0], 'IdModelo' => $value[1], 'DescripcionOtros' => $value[7], 'Serie' => $value[5], 'Cantidad' => $value[6]));
                }
            }
        }
        return $data;
    }

    /*
     * Metodo para saber el total de columnas que tienen datos en excel
     * 
     * @param int $hoja recibe el numero de la hoja de excel 
     * @return el total de columnas que tienen datos
     */

    public function totalCeldasHoja(int $hoja, string $columna) {
        $hoja = $this->LibroExcel->setActiveSheetIndex($hoja);
        $lastRow = $hoja->getHighestRow();
        $sumaCeldas = 0;
        for ($row = 1; $row <= $lastRow; $row++) {
            $celda = $hoja->cellExists($columna . $row);
            $sumaCeldas = $sumaCeldas + $celda;
        }
        return $sumaCeldas;
    }

    /*
     * Metodo para definir operacion a realizar de catalogo de lineas de equipo cuenta con tres
     * 
     * @param int $hoja recibe el numero de la hoja de excel 
     */

    public function protegerHoja(int $hoja) {
        $this->LibroExcel->getSheet($hoja)->getProtection()->setSheet(true);
        $this->LibroExcel->getSheet($hoja)->getProtection()->setSort(true);
        $this->LibroExcel->getSheet($hoja)->getProtection()->setInsertRows(true);
        $this->LibroExcel->getSheet($hoja)->getProtection()->setFormatCells(true);
        $this->LibroExcel->getSheet($hoja)->getProtection()->setPassword('S1cc0bS.');
    }

    /*
     * Metodo para limpiar los datos anteriomente insertados en el formato
     * 
     * @param int $hoja recibe el numero de la hoja de excel 
     * @param string $columna recibe el nombre de la columna
     */

    public function limpiarHojaDatos(int $hoja, string $columna) {
        $hoja = $this->LibroExcel->setActiveSheetIndex($hoja);
        $ultimaFila = $hoja->getHighestRow();
        for ($row = 2; $row <= $ultimaFila; $row++) {
            $hoja->setCellValue($columna . $row, '');
        }
    }

    /*
     * Metodo para mostrar la hoja activa en el formato
     * 
     * @param int $hoja recibe el numero de la hoja del formato de excel
     */

    public function hojaActiva(int $hoja) {
        $this->LibroExcel->setActiveSheetIndex($hoja);
    }

}
