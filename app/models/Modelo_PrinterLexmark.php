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
        $fileDate = str_replace("Reporte_Full_", "", $filename);
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

                $fechaInstalacion = '';
                if ($value[12] != '') {
                    $fecha = explode("T", $value[12]);
                    $fechaInstalacion = $fecha[0] . ' ' . substr($fecha[1], 0, 8);
                }

                $fechaInstalacionNegro = '';
                if ($value[11] != '') {
                    $fecha = explode("T", $value[11]);
                    $fechaInstalacionNegro = $fecha[0] . ' ' . substr($fecha[1], 0, 8);
                }

                $fechaUltimoTrabajo = '';
                if ($value[13] != '') {
                    $fecha = explode("T", $value[13]);
                    $fechaUltimoTrabajo = $fecha[0] . ' ' . substr($fecha[1], 0, 8);
                }

                $this->insertar("t_lecturas_reporte_markvision_detalle", [
                    'IdLectura' => $id,
                    'IP' => $ip,
                    'EstadoImpresora' => $value[1],
                    'Estado' => $value[2],
                    'Contacto' => $value[3],
                    'CarasCargadas' => $value[10],
                    'CapacidadCartuchoNegro' => $value[4],
                    'NivelCartuchoNegro' => $value[5],
                    'CapacidadKitMantto' => $value[6],
                    'NivelKitMantto' => $value[7],
                    'CapacidadUnidadImagenes' => $value[8],
                    'NivelUnidadImagenes' => $value[9],
                    'FechaInstalacion' => $fechaInstalacion,
                    'FechaInstalacionCartuchoNegro' => $fechaInstalacionNegro,
                    'UltimoTrabajo' => $fechaUltimoTrabajo,
                    'Firmware' => $value[14],
                    'Modelo' => $value[15],
                    'Serie' => $value[16],
                    'MAC' => $value[17],
                    'NombreSistema' => $value[18],
                    'SerieCartuchoNegro' => $value[19],
                    'SerieUnidadImagen' => $value[20]
                ]);

                $shortArray = [
                    'IP' => $ip,
                    'EstadoImpresora' => ($value[1] != '') ? $value[1] : 'Error',
                    'Estado' => $value[2],
                    'Contacto' => $value[3],
                    'Capacidad' => $value[4],
                    'Nivel' => $value[5]
                ];

                if ($value[5] <= 50 && $value[5] >= 41) {
                    array_push($greenPrinters, $shortArray);
                } else if ($value[5] <= 40 && $value[5] > 25) {
                    array_push($yellowPrinters, $shortArray);
                } else if ($value[5] <= 25) {
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
