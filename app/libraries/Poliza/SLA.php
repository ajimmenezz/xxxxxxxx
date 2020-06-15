<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

class SLA extends General {

    private $db;
    private $excel;

    public function __construct() {
        parent::__construct();
        $this->db = \Modelos\Modelo_SLA::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
    }

    public function getSla(array $datos = NULL) {
        $sla = $this->db->sla($datos);
        $arrayFolios = array();

        if (!empty($sla)) {
            foreach ($sla as $key => $value) {
                if ($value["IdSucursal"] != '') {
                    $arrayFolios[$key]['Folio'] = $value['Folio'];
                    $arrayFolios[$key]['Sucursal'] = $value['Sucursal'];
                    $arrayFolios[$key]['FechaCreacion'] = $value['FechaCreacion'];
                    $arrayFolios[$key]['FechaCreacionServicio'] = $value['FechaCreacionServicio'];
                    $arrayFolios[$key]['FechaInicio'] = $value['FechaInicio'];
                    $arrayFolios[$key]['Tecnico'] = $value['Tecnico'];
                    $arrayFolios[$key]['AtiendeSolicitud'] = $value['AtiendeSolicitud'];
                    $arrayFolios[$key]['IntervaloSolicitudServicioCreacion'] = $this->tiempoEntreSolicitudTicket(
                            array('FechaCreacionSolicitud' => $value['FechaCreacion'], 'FechaCreacionServicio' => $value['FechaCreacionServicio']));
                    $datosSucursal = $this->db->consultaGral("SELECT Local FROM cat_v3_sucursales WHERE Id =  " . $value["IdSucursal"]);
                    $datosSolicitud = $this->db->consultaGral("SELECT IdPrioridad FROM t_solicitudes WHERE Id =  " . $value["IdSolicitud"]);

                    if ($datosSucursal[0]["Local"] = 0) {
                        $localForaneo = "SLALocal";
                        $stringLocalForaneo = "Local";
                    } else {
                        $localForaneo = "SLAForaneo";
                        $stringLocalForaneo = "Foranea";
                    }

                    switch ($datosSolicitud[0]["IdPrioridad"]) {
                        case '1':
                            $datosPrioridades = $this->db->consultaGral("SELECT TIME_TO_SEC(TIME(" . $localForaneo . ")) AS tiempo, " . $localForaneo . " FROM cat_v3_prioridades WHERE Id = 1");
                            $segundosPrioridad = $datosPrioridades[0]["tiempo"];
                            $tiempoPrioridad = $datosPrioridades[0][$localForaneo];
                            $prioridad = 'Alta';
                            break;
                        case '2':
                            $datosPrioridades = $this->db->consultaGral("SELECT TIME_TO_SEC(TIME(" . $localForaneo . ")) AS tiempo, " . $localForaneo . " FROM cat_v3_prioridades WHERE Id = 2");
                            $segundosPrioridad = $datosPrioridades[0]["tiempo"];
                            $tiempoPrioridad = $datosPrioridades[0][$localForaneo];
                            $prioridad = 'Media';
                            break;
                        case '3':
                            $datosPrioridades = $this->db->consultaGral("SELECT TIME_TO_SEC(TIME(" . $localForaneo . ")) AS tiempo, " . $localForaneo . " FROM cat_v3_prioridades WHERE Id = 3");
                            $segundosPrioridad = $datosPrioridades[0]["tiempo"];
                            $tiempoPrioridad = $datosPrioridades[0][$localForaneo];
                            $prioridad = 'Baja';
                            break;
                    }

                    if ($value['SegundosTiempoTranscurrido'] <= $segundosPrioridad) {
                        $SLA = "si cumple";
                    } else {
                        $SLA = "no cumple";
                    }

                    $arrayFolios[$key]['TiempoTranscurrido'] = $value['TiempoTranscurrido'];
                    $arrayFolios[$key]['SLA'] = $SLA;
                    $arrayFolios[$key]['Prioridad'] = $prioridad;
                    $arrayFolios[$key]['LocalForaneo'] = $stringLocalForaneo;
                    $arrayFolios[$key]['TiempoPrioridad'] = $tiempoPrioridad;
                }
            }
        }
        
        return $arrayFolios;
    }

    public function tiempoEntreSolicitudTicket(array $datos) {
        $anio = '';
        $mes = '';
        $dia = '';
        $fechaDateTimeCreacionSolicitud = new \DateTime($datos['FechaCreacionSolicitud']);
        $fechaDateTimeCreacionServicio = new \DateTime($datos['FechaCreacionServicio']);
        $intervalo = $fechaDateTimeCreacionSolicitud->diff($fechaDateTimeCreacionServicio);

        if ($intervalo->format('%Y') !== '00') {
            $anio = $intervalo->format('%Y') . ' año';
        }

        if ($intervalo->format('%m') !== '0') {
            $anio = $intervalo->format('%m') . ' mes';
        }

        if ($intervalo->format('%d') !== '0') {
            $anio = $intervalo->format('%d') . ' dia(s)';
        }

        return $anio . $mes . $dia . $intervalo->format(' %H:%i:%s');
    }

    public function setTiempoAtencionFolio() {
        try {
            $folios = $this->db->getFoliosCreacionInicio();
            $fechaInicioJornada = '08:00:00';
            $fechaTerminaJornada = '18:00:00';
            $fechaDateTimeJornadaInicio = new \DateTime($fechaInicioJornada);
            $fechaDateTimeJornadaTermina = new \DateTime($fechaTerminaJornada);

            foreach ($folios as $key => $value) {
                $horaCreacion = $value['FechaCreacion'];
                $horaInicio = $value['FechaInicio'];
                $fechaConvertidaCreacion = strtotime($horaCreacion);
                $fechaConvertidaInicio = strtotime($horaInicio);
                $fechaCreacion = date('Y-m-d', $fechaConvertidaCreacion);
                $fechaInicio = date('Y-m-d', strtotime($horaInicio));
                $horaExactaCreacion = date('H:i:s', $fechaConvertidaCreacion);
                $horaExactaInicio = date('H:i:s', $fechaConvertidaInicio);
                $horaDateTimeCreacion = new \DateTime($horaExactaCreacion);
                $horaDateTimeInicio = new \DateTime($horaExactaInicio);

                if ($fechaCreacion === $fechaInicio) {
                    $validacionFecha = TRUE;
                } else {
                    $validacionFecha = FALSE;
                }

                if ($this->dentro_de_horario($fechaInicioJornada, $fechaTerminaJornada, $horaExactaCreacion)) {
                    $validacionHora = TRUE;
                } else {
                    $validacionHora = FALSE;
                }

                if ($validacionFecha && $validacionHora) {
                    $fechaInicio = new \DateTime($horaInicio);
                    $total = $horaDateTimeInicio->diff($horaDateTimeCreacion);
                    $tiempoTranscurrido = $total->format('%H:%I:%S');
                } else if ($validacionFecha) {
                    $total = $horaDateTimeInicio->diff($horaDateTimeCreacion);
                    $tiempoTranscurrido = $total->format('%H:%I:%S');
                } else {
                    $fechaDateTimeCreacion = new \DateTime($fechaCreacion);
                    $fechaDateTimeInicio = new \DateTime($fechaInicio);
                    $totalDias = $fechaDateTimeCreacion->diff($fechaDateTimeInicio);
                    $horasPrimerDia = $fechaDateTimeJornadaTermina->diff($horaDateTimeCreacion);
                    $horasSegundoDia = $horaDateTimeInicio->diff($fechaDateTimeJornadaInicio);

                    if ($totalDias->days > 2) {
                        $horasTranscurridas = $this->diasEntreFechas($fechaCreacion, $totalDias->days);
                        $stringHorasTranscurridas = (string) $horasTranscurridas . ":00:00";
                        $tiempoTranscurrido = $this->sumarHoras(array($stringHorasTranscurridas, $horasPrimerDia->format('%H:%I:%S'), $horasSegundoDia->format('%H:%I:%S')));
                    } else {
                        $tiempoTranscurrido = $this->sumarHoras(array($horasPrimerDia->format('%H:%I:%S'), $horasSegundoDia->format('%H:%I:%S')));
                    }
                }

                $datosChekingTicket = $this->db->getCheking_Ticket($value['Folio']);

                if (empty($datosChekingTicket)) {
                    $this->db->setCheking_Ticket(array('Folio' => $value['Folio'], 'TiempoTranscurrido' => $tiempoTranscurrido));
                }
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    private function sumarHoras(array $horas) {
        $total = 0;
        foreach ($horas as $h) {
            $parts = explode(":", $h);
            $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
        }
        return $this->conversorSegundosHoras($total);
    }

    private function dentro_de_horario($hms_inicio, $hms_fin, $hms_referencia = NULL) { // v2011-06-21
        if (is_null($hms_referencia)) {
            $hms_referencia = date('G:i:s');
        }

        list($h, $m, $s) = array_pad(preg_split('/[^\d]+/', $hms_inicio), 3, 0);
        $s_inicio = 3600 * $h + 60 * $m + $s;

        list($h, $m, $s) = array_pad(preg_split('/[^\d]+/', $hms_fin), 3, 0);
        $s_fin = 3600 * $h + 60 * $m + $s;

        list($h, $m, $s) = array_pad(preg_split('/[^\d]+/', $hms_referencia), 3, 0);
        $s_referencia = 3600 * $h + 60 * $m + $s;

        if ($s_inicio <= $s_fin) {
            return $s_referencia >= $s_inicio && $s_referencia <= $s_fin;
        } else {
            return $s_referencia >= $s_inicio || $s_referencia <= $s_fin;
        }
    }

    private function diasEntreFechas(string $fechaCreacion, int $dias) {
        $fechaComoEntero = strtotime($fechaCreacion);
        $horas = 0;
        for ($x = 1; $x <= $dias; $x++) {
            $fechaComoEntero = strtotime("+1 day", $fechaComoEntero);
            if ($x !== 1 && $x !== $dias) {
                switch (date('l', $fechaComoEntero)) {
                    case "Monday":
                    case "Tuesday":
                    case "Wednesday":
                    case "Thursday":
                    case "Friday":
                        $horas = $horas + 10;
                        break;
                    case "Sunday":
                    case "Saturday":
                        $horas = $horas + 0;
                        break;
                }
            }
        }

        return $horas;
    }

    private function conversorSegundosHoras($tiempo_en_segundos) {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

        if ($minutos == (float) 0) {
            $minutos = '00';
        } else {
            $minutos = (string) $minutos;
        }

        if ($segundos == (float) 0) {
            $segundos = '00';
        } else {
            $segundos = (string) $segundos;
        }

        return $horas . ':' . $minutos . ":" . $segundos;
    }

    public function getExcel(array $datos) {
        $foliosAdist = array();
        $datosSLA = $this->getSla($datos);

        foreach ($datosSLA as $key => $value) {
            $foliosAdist[$key]['Folio'] = $value['Folio'];
            $foliosAdist[$key]['Sucursal'] = $value['Sucursal'];
            $foliosAdist[$key]['SolicitudAsignadaA'] = $value['AtiendeSolicitud'];
            $foliosAdist[$key]['Tecnico'] = $value['Tecnico'];
            $foliosAdist[$key]['CreacionTicket'] = $value['FechaCreacionServicio'];
            $foliosAdist[$key]['IntervaloFolioTicket'] = $value['IntervaloSolicitudServicioCreacion'];
            $foliosAdist[$key]['CreacionFolio'] = $value['FechaCreacion'];
            $foliosAdist[$key]['Inicio Folio'] = $value['FechaInicio'];
            $foliosAdist[$key]['TiempoTranscurrido'] = $value['TiempoTranscurrido'];
            $foliosAdist[$key]['TiempoLimite'] = $value['TiempoPrioridad'];
            $foliosAdist[$key]['Prioridad'] = $value['Prioridad'];
            $foliosAdist[$key]['LocalForaneo'] = $value['LocalForaneo'];
            $foliosAdist[$key]['SLA'] = $value['SLA'];
        }

        $arrayTitulos = [
            'Folio',
            'Sucursal',
            'Solicitud asignada a',
            'Técnico',
            'Creación Ticket',
            'Intervalo de Creación de Folio y Ticket',
            'Creación Folio',
            'Inicio Folio',
            'Tiempo Transcurrido',
            'Tiempo Limite',
            'Prioridad',
            'Local/Foraneo',
            'SLA'
        ];

        return $this->setExcel($foliosAdist, $arrayTitulos, 'Reporte_SLA.xlsx');
    }

    public function setExcel($datosFolio, $arrayTitulos, $nombreArchivo) {
        if (count($arrayTitulos) > 25) {
            $letra = 'AA';
        } else {
            $letra = 'A';
        }

        $this->Excel->createSheet('SLA', 0);
        $this->Excel->setActiveSheet(0);
        $this->Excel->setTableSubtitles($letra, 1, $arrayTitulos);

        $arrayWidth = array();

        for ($i = 0; $i < count($arrayTitulos); $i++) {
            array_push($arrayWidth, 30);
        }

        $this->Excel->setColumnsWidth($letra, $arrayWidth);

        $arrayAlign = array();

        for ($i = 0; $i < count($arrayTitulos); $i++) {
            array_push($arrayAlign, 'center');
        }

        $this->Excel->setTableContent($letra, 1, $datosFolio, true, $arrayAlign);

        if (count($arrayTitulos) > 25) {
            $this->Excel->removeColumn('A', 26);
        }

        $ruta = '../public/storage/Archivos/Reportes/' . $nombreArchivo;
        $path = "../public/storage/Archivos/Reportes";

        if (!is_dir($path)) {
            mkdir($path, 775, true);
        }

        $this->Excel->saveFile($ruta);

        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . '/storage/Archivos/Reportes/' . $nombreArchivo];
    }

}
