<?php

namespace Librerias\Reportes;

use Controladores\Controller_Base_General as General;

class Servicedesk extends General
{
    private $DB;
    private $webServiceSD;
    private $sdKey;
    private $excel;
    private $correo;

    public function __construct()
    {
        parent::__construct();
        $this->DB = \Modelos\Modelo_ServiceDesk::factory();
        $this->webServiceSD = new \Librerias\WebServices\ServiceDesk();
        $this->correo = \Librerias\Generales\Correo::factory();
        $this->excel = new \Librerias\Generales\CExcel();
        $this->sdKey = $this->DB->getApiKeyByUser('2');
    }

    public function sendRedesReport()
    {
        $requests = $this->getRedesRequests();
        $ruta = $this->createRedesReviewReport($requests);

        $titulo = 'Reporte Pendientes Redes ' . date("YmdHis");
        $texto = ''
            . '<p>'
            . 'Se ha generado un nuevo reporte que contiene los incidentes pendientes del usuario de Redes Siccob en Service Desk.'
            . '</p>'
            . '<p>'
            . 'Se deja el link a continuación.'
            . '</p>'
            . '<p>'
            . '<a target="_blank" href="' . $ruta['ruta'] . '">Link del archivo</a>'
            . '</p>'
            . '<p>'
            . 'En caso de tener comentarios al respecto, por favor reenvíe este mail a los siguientes correos:<br />'
            . 'fpatino@siccob.com.mx<br />'
            . 'alejandro.salas@siccob.com.mx'
            . '</p>';
        $mensaje = $this->correo->mensajeCorreo($titulo, $texto);
        $this->correo->enviarCorreo('notificaciones@siccob.solutions', array('fpatino@siccob.com.mx', 'alejandro.salas@siccob.com.mx', 'luisrg@cinemex.net', 'marior@cinemex.net', 'franciscop@cinemex.net', 'joseht@cinemex.net', 'jorgej@cinemex.net'), $titulo, $mensaje);
        return ['code' => 200, 'link' => $ruta, 'requests' => $requests];
    }

    public function sendRedesReviewReport()
    {
        $requests = $this->getRedesRequests();
        $ruta = $this->createRedesReviewReport($requests);

        $titulo = 'Revisión Pendientes Redes ' . date("YmdHis");
        $texto = ''
            . '<p>'
            . 'Se ha generado un nuevo reporte que contiene los incidentes pendientes del usuario de Redes Siccob.'
            . '</p>'
            . '<p>'
            . 'Es importante que revise la información contenida en el archivo y actualice las notas '
            . 'en Service Desk en caso de ser necesario.'
            . '</p>'
            . '<p>'
            . 'El siguiente reporte le será enviado a personal de Cinemex el día de mañana a las 8:00 horas'
            . '</p>'
            . '<p>'
            . '<a target="_blank" href="' . $ruta['ruta'] . '">Link del archivo</a>'
            . '</p>';
        $mensaje = $this->correo->mensajeCorreo($titulo, $texto);
        $this->correo->enviarCorreo('notificaciones@siccob.solutions', array('fpatino@siccob.com.mx', 'alejandro.salas@siccob.com.mx'), $titulo, $mensaje);
        return ['code' => 200, 'link' => $ruta, 'requests' => $requests];


        $requests = $this->getRedesRequests();
        return ['code' => 200, 'requests' => $requests];
    }

    private function getRedesRequests()
    {
        $viewId = $this->webServiceSD->getViewId("Redes Siccob", $this->sdKey);
        $initialRequests = $this->webServiceSD->getRequestsByFilter($viewId, $this->sdKey, 0);
        $requestsWithNotes = $this->addLastNoteToRequests($initialRequests);
        $requestsWithDetails = $this->addDetailsToRequests($requestsWithNotes);
        $requests = $this->processRequestForReport($requestsWithDetails);
        return $requests;
    }

    private function addLastNoteToRequests(array $requests)
    {
        if (!empty($requests)) {
            foreach ($requests as $key => $val) {
                $note = (object) [];
                $notesResult = $this->webServiceSD->getNotas($this->sdKey, $val->WORKORDERID);
                if (isset($notesResult->operation) && isset($notesResult->operation->Details)) {
                    $note = $notesResult->operation->Details[0];
                }
                $requests[$key]->LASTNOTE = $note;
            }
        }
        return $requests;
    }


    private function addNotesToRequests(array $requests)
    {
        if (!empty($requests)) {
            foreach ($requests as $key => $val) {
                $notes = [];
                $notesResult = $this->webServiceSD->getNotas($this->sdKey, $val->WORKORDERID);
                if (isset($notesResult->operation) && isset($notesResult->operation->Details)) {
                    $notes = $notesResult->operation->Details;
                }
                $requests[$key]->NOTES = $notes;
            }
        }
        return $requests;
    }

    private function addDetailsToRequests(array $requests)
    {
        if (!empty($requests)) {
            foreach ($requests as $key => $val) {
                $detailsResult = $this->webServiceSD->getRequestDetails($val->WORKORDERID, $this->sdKey);
                $requests[$key]->DETAILS = $detailsResult;
            }
        }
        return $requests;
    }

    private function processRequestForReport(array $requests)
    {
        $processedRequests = [];
        if (!empty($requests)) {
            foreach ($requests as $key => $val) {
                $note = '';
                $noteDate = '';
                if (isset($val->LASTNOTE->NOTESTEXT)) {
                    $note = strip_tags($val->LASTNOTE->NOTESTEXT);
                    $noteDate = date('Y-m-d H:i:s', $val->LASTNOTE->NOTESDATE / 1000);
                }

                array_push($processedRequests, [
                    'workorderid' => $val->WORKORDERID,
                    'createdtime' => date('Y-m-d H:i:s', $val->CREATEDTIME / 1000),
                    'priority' => $val->PRIORITY,
                    'requester' => $val->REQUESTER,
                    'duebytime' => $val->DUEBYTIME != -1 ? date('Y-m-d H:i:s', $val->DUEBYTIME / 1000) : '',
                    'subject' => $val->SUBJECT,
                    'description' => $val->DETAILS->SHORTDESCRIPTION,
                    'status' => $val->STATUS,
                    'lastnote' => $note,
                    'notedate' => $noteDate
                ]);
            }
        }
        return $processedRequests;
    }

    private function createRedesReviewReport(array $requests)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');

        $arrayTitulos = [
            'Incidente SD',
            'Fecha de Creación',
            'Prioridad',
            'Solicitante',
            'Fecha de Vencimiento',
            'Asunto',
            'Descripción',
            'Estado',
            'Última Nota',
            'Fecha de Nota'
        ];
        $arrayWidth = [15, 20, 15, 35, 20, 40, 40, 20, 40, 20];
        $arrayAlign = ['center', '', 'center', '', '', 'justify', 'justify', '', 'justify', ''];

        $info = $requests;
        $this->excel->createSheet('Pendientes Redes', 0);
        $this->excel->setActiveSheet(0);
        $this->excel->setTableSubtitles('A', 2, $arrayTitulos);
        $this->excel->setColumnsWidth('A', $arrayWidth);
        $this->excel->setTableTitle("A1", "J1", "Incidentes pendientes Redes", array('titulo'));
        $this->excel->setTableContent('A', 2, $info, true, $arrayAlign);

        $time = date("Ymd_His");
        $nombreArchivo = 'Pendientes_Redes_' . $time . '.xlsx';
        if (!file_exists('./storage/Archivos/Reportes/Redes')) {
            mkdir('./storage/Archivos/Reportes/Redes', 0775, true);
        }
        $ruta = 'storage/Archivos/Reportes/Redes/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->excel->saveFile($ruta);

        return ['ruta' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }
}
