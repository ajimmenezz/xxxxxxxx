<?php

namespace Librerias\Reportes;

use Controladores\Controller_Base_General as General;

class Lexmark extends General
{

    private $DB;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_PrinterLexmark::factory();
    }

    public function setDailyPrints()
    {
        $result = $this->DB->setDailyPrints();
        return $result;
    }

    public function setPerformanceByToner($get)
    {

        $fi = isset($get['fi']) ? $get['fi'] . ' 00:00:00' : '2019-07-18 00:00:00';
        $ff = isset($get['ff']) ? $get['ff'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';

        ini_set('memory_limit', '2048M');
        $records = $this->DB->getRecordsBaseLexmark($fi, $ff);
        $array = [];
        foreach ($records as $key => $value) {
            if (!array_key_exists($value['Contacto'], $array)) {
                $array[$value['Contacto']] = [];
            }

            if (empty($array[$value['Contacto']])) {
                array_push($array[$value['Contacto']], [
                    'Serie' => $value['Serie'],
                    'SerieCartucho' => $value['SerieCartuchoNegro'],
                    'FechaPrimerContador' => $value['FileDate'],
                    'FechaUltimoContador' => $value['FileDate'],
                    'Capacidad' => $value['CapacidadCartuchoNegro'],
                    'Nivel' => $value['NivelCartuchoNegro'],
                    'PrimerContador' => ($value['CapacidadCartuchoNegro'] == 6000) ? 0 : $value['CarasCargadas'],
                    'UltimoContador' => $value['CarasCargadas']
                ]);
            } else {
                $k = array_keys($array[$value['Contacto']]);
                $k = end($k);
                if (
                    $array[$value['Contacto']][$k]['Serie'] === $value['Serie']
                    &&
                    $array[$value['Contacto']][$k]['SerieCartucho'] === $value['SerieCartuchoNegro']
                ) {
                    $array[$value['Contacto']][$k]['FechaUltimoContador'] = $value['FileDate'];
                    $array[$value['Contacto']][$k]['Nivel'] = $value['NivelCartuchoNegro'];
                    $array[$value['Contacto']][$k]['UltimoContador'] = $value['CarasCargadas'];
                } else {
                    $agregar = true;
                    $end = strtotime(substr($value['FileDate'], 0, 10));
                    foreach ($array[$value['Contacto']] as $keyR => $valueR) {
                        $start = strtotime(substr($valueR['FechaUltimoContador'], 0, 10));
                        $days_between = ceil(abs($end - $start) / 86400);
                        // echo "<pre>";
                        // var_dump($valueR);
                        // echo "</pre>";
                        if ($valueR['Serie'] === $value['Serie'] && $valueR['SerieCartucho'] == $value['SerieCartuchoNegro'] && $days_between <= 2) {
                            $agregar = false;
                            $array[$value['Contacto']][$keyR]['FechaUltimoContador'] = $value['FileDate'];
                            $array[$value['Contacto']][$keyR]['Nivel'] = $value['NivelCartuchoNegro'];
                            $array[$value['Contacto']][$keyR]['UltimoContador'] = $value['CarasCargadas'];
                        }
                    }

                    if ($agregar) {
                        array_push($array[$value['Contacto']], [
                            'Serie' => $value['Serie'],
                            'SerieCartucho' => $value['SerieCartuchoNegro'],
                            'FechaPrimerContador' => $value['FileDate'],
                            'FechaUltimoContador' => $value['FileDate'],
                            'Capacidad' => $value['CapacidadCartuchoNegro'],
                            'Nivel' => $value['NivelCartuchoNegro'],
                            'PrimerContador' => $value['CarasCargadas'],
                            'UltimoContador' => $value['CarasCargadas']
                        ]);
                    }
                }
            }
        }

        $this->DB->setPerformanceByToner($array);
        echo "<pre>";
        var_dump($array);
        echo "</pre>";
    }
}
