<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_PrinterLexmark extends Modelo_Base
{
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function saveMarkvisionRead(string $path, array $data)
    {
        $this->iniciaTransaccion();
        $filename = basename($path, ".csv");
        $fileDate = str_replace("Reporte_", "", $filename);
        $fileDate = substr($fileDate, 0, 4) . '-' . substr($fileDate, 4, 2) . '-' . substr($fileDate, 6, 2) . ' ' . substr($fileDate, 9, 2) . ':' . substr($fileDate, 11, 2) . ':' . substr($fileDate, 13, 2);

        $this->insertar("t_lecturas_reporte_markvision", [
            'ReadDate' => $this->getFecha(),
            'FileDate' => $fileDate,
            'Filename' => $path
        ]);

        $id = $this->ultimoId();

        $greenPrinters = [];
        $yellowPrinters = [];
        $redPrinters = [];

        foreach ($data as $key => $value) {
            if (isset($value[1])) {
                $ip = explode(" (", $value[0])[0];
                $this->insertar("t_lecturas_reporte_markvision_detalle", [
                    'IdLectura' => $id,
                    'IP' => $ip,
                    'Contacto' => $value[1],
                    'CarasCargadas' => $value[2],
                    'CapacidadCartuchoNegro' => $value[3],
                    'NivelCartuchoNegro' => $value[4],
                    'CapacidadKitMantto' => $value[5],
                    'NivelKitMantto' => $value[6],
                    'CapacidadUnidadImagenes' => $value[7],
                    'NivelUnidadImagenes' => $value[8]
                ]);

                $shortArray = [
                    'IP' => $ip,
                    'Contacto' => $value[1],
                    'Capacidad' => $value[3],
                    'Nivel' => $value[4]
                ];

                if ($value[4] <= 50 && $value[4] >= 41) {
                    array_push($greenPrinters, $shortArray);
                } else if ($value[4] <= 40 && $value[4] > 25) {
                    array_push($yellowPrinters, $shortArray);
                } else if ($value[4] <= 25) {
                    array_push($redPrinters, $shortArray);
                }
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => 'All was saved correctly',
                'printersToNotification' => [
                    'red' => $redPrinters,
                    'yellow' => $yellowPrinters,
                    'green' => $greenPrinters
                ]
            ];
        }
    }
}
