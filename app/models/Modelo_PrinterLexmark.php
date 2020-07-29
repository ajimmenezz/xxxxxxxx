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

        $consultaProyeccion = $this->consulta("
        select
        tf.IP,
        tf.Sucursal,
        tf.Impresiones as ImpresionesTotales,
        DATEDIFF(now(),if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion)) as DiasTotales,
        if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion) as FechaInstalacion,
        ceil(tf.Impresiones / DATEDIFF(now(),if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion))) as ImpresionesPromedioDiarias,        
        tf.ImpresionesRestantes,
        tf.CapacidadCartuchoNegro as CapacidadToner,
        tf.NivelCartuchoNegro / 100 as PorcentajeRestante,
        FLOOR(tf.ImpresionesRestantes / (tf.Impresiones / DATEDIFF(now(),if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion)))) as DiasCubiertos,
        DATE_ADD(now(),INTERVAL FLOOR(tf.ImpresionesRestantes / tf.Impresiones / DATEDIFF(now(),if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion))) DAY) as FechaTentativaCambio,
        DATE_ADD(now(),INTERVAL FLOOR(tf.ImpresionesRestantes / tf.Impresiones / DATEDIFF(now(),if(tf.FechaInstalacion is null or tf.FechaInstalacion = '',tf.FechaInstalacionMV,tf.FechaInstalacion))) - 5 DAY) as FechaTentativaEnvio
        from (
            select
            tld.IP,
            tld.Contacto as Sucursal,
            tld.CarasCargadas as Impresiones,	
            (tld.CapacidadCartuchoNegro * tld.NivelCartuchoNegro) / 100 as ImpresionesRestantes,            
            (
                select
                FechaConclusion
                from t_servicios_ticket where Id = (
                    select 
                    IdServicio
                    from t_instalaciones_equipos where Id = (
                        select
                        IdInstalacion
                        from
                        t_instalaciones_adicionales_45
                        where IP = tld.IP limit 1
                    )
                )
            ) as FechaInstalacion,	
            tld.FechaInstalacion as FechaInstalacionMV,
            tld.CapacidadCartuchoNegro,
            tld.NivelCartuchoNegro
            from t_lecturas_reporte_markvision_detalle tld
            where tld.IdLectura = (select MAX(Id) from t_lecturas_reporte_markvision)
        ) as tf");

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
                    'green' => $greenPrinters,
                    'proyeccion' => $consultaProyeccion
                ]
            ];
        }
    }

    public function setDailyPrints()
    {
        $this->iniciaTransaccion();
        $consulta = $this->consulta("select * from t_lecturas_reporte_markvision_detalle where ImpresionesDia is null");
        if (!empty($consulta)) {
            foreach ($consulta as $key => $value) {
                $carasCargadas = $this->consulta("
                select CarasCargadas 
                from t_lecturas_reporte_markvision_detalle 
                where IdLectura = (" . $value['IdLectura'] . " - 1) 
                and IP = '" . $value['IP'] . "' 
                and Contacto = '" . $value['Contacto'] . "'");
                if (!empty($carasCargadas) && isset($carasCargadas[0]) && isset($carasCargadas[0]['CarasCargadas'])) {
                    if ($carasCargadas[0]['CarasCargadas'] > 0 && $carasCargadas[0]['CarasCargadas'] != '') {
                        $carasCargadas = $value['CarasCargadas'] - $carasCargadas[0]['CarasCargadas'];
                    } else {
                        $carasCargadas = 0;
                    }

                    $this->actualizar("t_lecturas_reporte_markvision_detalle", [
                        'ImpresionesDia' => $carasCargadas
                    ], ['Id' => $value['Id']]);
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
                'message' => 'All was saved correctly'
            ];
        }
    }

    public function getRecordsBaseLexmark($fi, $ff)
    {
        return $this->consulta("select * from v_base_lexmark where FileDate between '" . $fi . "' and '" . $ff . "'");
    }

    public function setPerformanceByToner($data)
    {
        $this->queryBolean("truncate t_base_rendimiento_toner");
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                $this->insertar("t_base_rendimiento_toner", [
                    'Complejo' => $key,
                    'SerieImpresora' => $v['Serie'],
                    'SerieCartucho' => $v['SerieCartucho'],
                    'FechaPrimerContador' => $v['FechaPrimerContador'],
                    'FechaUltimoContador' => $v['FechaUltimoContador'],
                    'Capacidad' => $v['Capacidad'],
                    'Nivel' => $v['Nivel'],
                    'PrimerContador' => $v['PrimerContador'],
                    'UltimoContador' => $v['UltimoContador'],
                    'ImpresionesCartucho' => $v['UltimoContador'] - $v['PrimerContador'],
                    'ImpresionesEsperadas' => ceil($v['Capacidad'] - ($v['Capacidad'] * ($v['Nivel'] / 100)))
                ]);
            }
        }
    }

    public function setTemporalLexmark()
    {
        $this->iniciaTransaccion();
        $this->queryBolean("SET lc_time_names = 'es_ES'");
        $this->queryBolean("truncate temp_lexmark");
        $this->queryBolean("
        insert into temp_lexmark
        select 
        vlcs.Semana, 
        vlcs.FechaReal,
        vlcs.UltimoDia,
        vlcs.Mes,
        vlcs.FechaLectura,
        vlcs.Contacto,
        regionCliente((select IdRegionCliente from cat_v3_sucursales where Alias = vlcs.Contacto limit 1)) as Zona,
        vlcs.CarasCargadas,
        (select 
            CarasCargadas 
            from v_lexmark_conteo_semanal 
            where Semana = if(vlcs.Semana = 1, 52, (vlcs.Semana - 1)) 
            and Anio = if(vlcs.Semana = 1, vlcs.Anio - 1, vlcs.Anio)
            and Contacto = vlcs.Contacto 
            limit 1
        ) as CarasSemanaPasada
        from v_lexmark_conteo_semanal vlcs");
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
                'message' => 'All was saved correctly'
            ];
        }
    }
}
