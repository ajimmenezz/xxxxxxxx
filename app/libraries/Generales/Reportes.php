<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

class Reportes extends General {

    private $DBR;
    private $Excel;

    public function __construct() {
        parent::__construct();
        $this->DBR = \Modelos\Modelo_Reporte::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date', 'archivosAdjuntosCorreo'));
        $this->Excel = new \Librerias\Generales\CExcel();
    }

    public function getFoliosSemanal() {
        $foliosAdist = $this->DBR->obtenerFoliosAdist();
        $titulos = $this->cabeceraExcelFolios();
        return $this->crearExcel($foliosAdist, $titulos, 'Lista_Folios.xlsx');
    }

    public function getFoliosAnual() {
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');
        $foliosAdist = $this->DBR->obtenerFoliosAnualAdist();
        $titulos = [
            'Año',
            'Mes',
            'Semana',
            'Ticket Service Desk',
            'Estatus Ticket AdIST',
            'Servicio AdIST',
            'Tipo Servicio',
            'Estatus Servicio',
            'Departamento',
            'Tecnico Asignado',
            'Region',
            'Sucursal',
            'Fecha Solicitud',
            'Solicitante',
            'Asunto',
            'Descripcion Solicitud',
            'Fecha Servicio',
            'Fecha Inicio Servicio',
            'Fecha Conclusion Servicio',
            'Area Atencion',
            'Punto',
            'Modelo',
            'Marca',
            'Linea',
            'Sublinea',
            'Componente',
            'Tipo Diagnostico',
            'Tipo Falla',
            'Falla',
            'Fecha Diagnostico',
            'Observaciones Diagnostico',
            'Tipo Solucion',
            'Solucion Sin Equipo',
            'Cambio Equipo',
            'Cambio Refaccion',
            'Solucion Servicio Sin Clasificar',
            'Tiempo Solicitud',
            'Tiempo Servicio',
            'Tiempo Transcurrido Entre Solicitud Servicio'
        ];
        return $this->crearExcel($foliosAdist, $titulos, 'Lista_Folios_Anual.xlsx');
    }

    public function getEquiposRefaccionesCorrectivo() {
        $datos = array();
        $equiposRefaccionesCorrectivos = $this->DBR->getEquiposRefaccionesCorrectivo();
        $titulosCorrectivos = [
            'Servicio',
            'Ticket SD',
            'Ticket AD',
            'Fecha',
            'Técnico',
            'Tipo Servicio',
            'Zona',
            'Sucursal',
            'Estatus SD',
            'Estatus AD',
            'Línea del Equipo',
            'Sublínea del Equipo',
            'Marca del Equipo',
            'Modelo del Equipo',
            'Tipo de Falla',
            'Falla',
            'Tipo Problema',
            'Equipo Requerido',
            'Cantidad',
            'Asignación en SD'
        ];
        $equiposRefaccionesAdicionales = $this->DBR->getEquiposRefaccionesAdicional();
        $titulosAdicionales = [
            'Ticket SD',
            'Ticket AD',
            'Servicio AD',
            'Fecha',
            'Zona',
            'Sucursal',
            'Técnico',
            'Tipo Falla',
            'Requerido',
            'Cantidad',
            'Asignación en SD'
        ];

        $datos[0] = array(
            'nombreHoja' => 'Correctivos',
            'titulos' => $titulosCorrectivos,
            'datos' => $equiposRefaccionesCorrectivos
        );

        $datos[1] = array(
            'nombreHoja' => 'Adicionales',
            'titulos' => $titulosAdicionales,
            'datos' => $equiposRefaccionesAdicionales
        );

        return $this->crearExcelVariasHojas($datos, 'Equipos y Refaciones Requeridas.xlsx');
    }

    private function cabeceraExcelFolios() {
        $titulos = [
            'Mes',
            'Semana',
            'Ticket Service Desk',
            'Estatus Ticket AdIST',
            'Servicio AdIST',
            'Tipo Servicio',
            'Estatus Servicio',
            'Departamento',
            'Tecnico Asignado',
            'Region',
            'Sucursal',
            'Fecha Solicitud',
            'Solicitante',
            'Asunto',
            'Descripcion Solicitud',
            'Fecha Servicio',
            'Fecha Inicio Servicio',
            'Fecha Conclusion Servicio',
            'Area Atencion',
            'Punto',
            'Equipo Diagnosticado',
            'Componente',
            'Tipo Diagnostico',
            'Tipo Falla',
            'Falla',
            'Fecha Diagnostico',
            'Observaciones Diagnostico',
            'Tipo Solucion',
            'Solucion Sin Equipo',
            'Cambio Equipo',
            'Cambio Refaccion',
            'Solucion Servicio Sin Clasificar',
            'Tiempo Solicitud',
            'Tiempo Servicio',
            'Tiempo Transcurrido Entre Solicitud Servicio'
        ];
        return $titulos;
    }

    public function crearExcel($datosFolio, $arrayTitulos, $nombreArchivo) {
        if (count($arrayTitulos) > 25) {
            $letra = 'AA';
        } else {
            $letra = 'A';
        }

        $this->Excel->createSheet('Folios', 0);
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

        //        $nombreArchivo = trim($nombreArchivo);
        $ruta = '../public/storage/Archivos/Reportes/' . $nombreArchivo;

        $path = "../public/storage/Archivos/Reportes";
        if (!is_dir($path)) {
            mkdir($path, 775, true);
        }
        $this->Excel->saveFile($ruta);

        return ['ruta' => 'https://' . $_SERVER['SERVER_NAME'] . '/storage/Archivos/Reportes/' . $nombreArchivo];
    }

    public function crearExcelVariasHojas(array $datos, string $nombreArchivo) {

        foreach ($datos as $key => $value) {
            if (count($value['titulos']) > 25) {
                $letra = 'AA';
            } else {
                $letra = 'A';
            }

            $this->Excel->createSheet($value['nombreHoja'], $key);
            $this->Excel->setActiveSheet($key);
            $this->Excel->setTableSubtitles($letra, 1, $value['titulos']);

            $arrayWidth = array();
            
            for ($i = 0; $i < count($value['titulos']); $i++) {
                array_push($arrayWidth, 30);
            }
            
            $this->Excel->setColumnsWidth($letra, $arrayWidth);

            $arrayAlign = array();
            
            for ($i = 0; $i < count($value['titulos']); $i++) {
                array_push($arrayAlign, 'center');
            }
            
            $this->Excel->setTableContent($letra, 1, $value['datos'], true, $arrayAlign);

            if (count($value['titulos']) > 25) {
                $this->Excel->removeColumn('A', 26);
            }
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
