<?php

namespace Librerias\Poliza;

use Controladores\Controller_Datos_Usuario as General;

ini_set('memory_limit', '4096M');
set_time_limit('1800');

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
                    $arrayFolios[$key]['IntervaloSolicitudServicioCreacion'] = $value['IntervaloSolicitudServicioCreacion'];
                    $arrayFolios[$key]['Zona'] = $value['Zona'];
                    $datosSucursal = $this->db->consultaGral("SELECT Local FROM cat_v3_sucursales WHERE Id =  " . $value["IdSucursal"]);
                    $datosSolicitud = $this->db->consultaGral("SELECT IdPrioridad FROM t_solicitudes WHERE Id =  " . $value["IdSolicitud"]);

                    if ($datosSucursal[0]["Local"] === '0') {
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
                        $SLA = "Si cumple";
                    } else {
                        $SLA = "No cumple";
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

    public function getExcel(array $datos) {
        $arrayTitulos = [
            'Folio',
            'Sucursal',
            'Zona',
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

        return $this->setExcel($datos['datosSLA'], $arrayTitulos, 'Reporte_SLA.xlsx');
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
