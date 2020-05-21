<?php

namespace Librerias\Reportes;

use Controladores\Controller_Base_General as General;

class Censos extends General
{

    private $DB;
    private $Excel;
    private $Poliza;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Censos::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->Poliza = \Librerias\Poliza\Seguimientos::factory();
    }

    public function getInventories(array $data = [])
    {
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $services = $this->DB->getCensosServicesId($data);
        $this->Excel->createSheet('Inventario', 0);
        $this->Excel->setActiveSheet(0);
        $arrayTitulos = [
            'Fecha',
            'Sucursal',
            'Zona',
            'Unidad de Negocio',
            'Área de Atención',
            'Equipo',
            'Línea',
            'Sublinea',
            'Marca',
            'Modelo',
            'Serie'
        ];

        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [20, 35, 30, 25, 35, 15, 25, 25, 25, 30, 30];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['center', '', '', '', 'center', '', '', '', '', ''];


        $cont = 0;
        $arrayCenso = [];
        $arraySobrantes = [];
        $arrayFaltantes = [];
        foreach ($services as $ks => $vs) {
            // if ($cont <= 5) {
                $dataInventory = $this->Poliza->getCensoDetailsForExport($vs['Id']);
                foreach ($dataInventory['inventario'] as $kinv => $vinv) {
                    array_push($arrayCenso, [
                        'Fecha' => $vinv['Fecha'],
                        'Sucursal' => $vinv['Sucursal'],
                        'Zona' => $vinv['Zona'],
                        'UnidadNegocio' => $vinv['UnidadNegocio'], 
                        'Area' => $vinv['Area'],
                        'Punto' => $vinv['Dominio'],
                        'Linea' => $vinv['Linea'],
                        'Sublinea' => $vinv['Sublinea'],
                        'Marca' => $vinv['Marca'],
                        'Modelo' => $vinv['Modelo'],
                        'Serie' => $vinv['Serie']
                    ]);
                }
                foreach ($dataInventory['faltantes'] as $kinv => $v) {
                    array_push($arrayFaltantes, [
                        'Sucursal' => $vs['Sucursal'],
                        'Zona' => $vs['Zona'],
                        'Area' => $v['Area'],
                        'Punto' => $v['Punto'],
                        'Linea' => $v['Linea'],
                        'Sublinea' => $v['Sublinea'],
                        'Cantidad' => $v['Cantidad']
                    ]);
                }
                foreach ($dataInventory['sobrantes'] as $kinv => $v) {
                    array_push($arraySobrantes, [
                        'Sucursal' => $vs['Sucursal'],
                        'Zona' => $vs['Zona'],
                        'Area' => $v['Area'],
                        'Punto' => $v['Punto'],
                        'Linea' => $v['Linea'],
                        'Sublinea' => $v['Sublinea'],
                        'Marca' => $v['Marca'],
                        'Modelo' => $v['Modelo'],
                        'Serie' => $v['Serie']
                    ]);
                }
            // }
            $cont++;
        }

        $this->Excel->setTableContent('A', 2, $arrayCenso, true, $arrayAlign);

        $this->Excel->createSheet('Faltantes', 1);
        $this->Excel->setActiveSheet(1);
        $arrayTitulos = [
            'Sucursal',
            'Zona',
            'Área de Atención',
            'Punto',
            'Línea',
            'Sublinea',
            'Cantidad'
        ];

        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [35, 25, 35, 15, 25, 25, 15];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['', '', '', 'center', '', '', 'center'];
        $this->Excel->setTableContent('A', 2, $arrayFaltantes, true, $arrayAlign);

        $this->Excel->createSheet('Sobrantes', 2);
        $this->Excel->setActiveSheet(2);
        $arrayTitulos = [
            'Sucursal',
            'Zona',
            'Área de Atención',
            'Punto',
            'Línea',
            'Sublinea',
            'Marca',
            'Modelo',
            'Serie'
        ];

        $this->Excel->setTableSubtitles('A', 2, $arrayTitulos);
        $arrayWidth = [35, 25, 35, 15, 25, 25, 25, 30, 30];
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $arrayAlign = ['', '', '', 'center', '', '', '', '', ''];
        $this->Excel->setTableContent('A', 2, $arraySobrantes, true, $arrayAlign);

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'InventarioCinemex_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/Reportes/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }

    private function getDataForInventory($serviceId)
    {
        $actual = $this->DB->getCensoForCompare($serviceId);
        $unidadNegocio = $this->DB->getUnidadNegocioByServicio($serviceId);
        $diferenciasKit = $this->Poliza->getCensoDiferenciasKit($actual, $unidadNegocio);
        $arrayActual = [];
        foreach ($actual as $k => $v) {
            array_push($arrayActual, [
                'Fecha' => $v['Fecha'],
                'Sucursal' => $v['Sucursal'],
                'Zona' => $v['Zona'],
                'Area' => $v['Area'],
                'Punto' => $v['Punto'],
                'Linea' => $v['Linea'],
                'Sublinea' => $v['Sublinea'],
                'Marca' => $v['Marca'],
                'Modelo' => $v['Modelo'],
                'Serie' => $v['Serie']
            ]);
        }
        return [
            'actual' => $arrayActual,
            'd' => $diferenciasKit
        ];
    }
}
