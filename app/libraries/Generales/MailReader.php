<?php

namespace Librerias\Generales;

//Clase para obtener informacion de XML
class MailReader
{

    private $DB;
    private $usr;
    private $pass;
    private $server;
    private $connection;
    private $Excel;
    private $Correo;

    public function __construct()
    {
        $this->DB = \Modelos\Modelo_PrinterLexmark::factory();
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usr = 'ajimenez@siccob.com.mx';
        $this->pass = 'unoPunto1.';
        $this->server = '{imap.gmail.com:993/imap/ssl}INBOX';
    }

    private function openConnection()
    {
        $this->connection = \imap_open($this->server, $this->usr, $this->pass);
    }

    private function closeConnection()
    {
        \imap_close($this->connection);
    }

    private function searchEmails(string $stringFilter)
    {
        $emails = \imap_search($this->connection, $stringFilter);
        return $emails;
    }

    private function getEmailHeaders(int $emailID)
    {
        $headers = \imap_headerinfo($this->connection, $emailID);
        return $headers;
    }

    private function getEmailBody(int $emailID)
    {
        $body = \imap_body($this->connection, $emailID);
        return $body;
    }

    private function getEmailAtachments(int $emailID)
    {
        $attachments = array();

        $structure = \imap_fetchstructure($this->connection, $emailID);
        if (isset($structure->parts) && count($structure->parts)) {

            for ($i = 0; $i < count($structure->parts); $i++) {

                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = \imap_fetchbody($this->connection, $emailID, $i + 1);
                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        return $attachments;
    }


    public function getMailReportLexmark()
    {
        $this->openConnection();
        $emails = $this->searchEmails('SUBJECT "Markvision Enterprise Reporte"');
        $attachments = $this->getEmailAtachments(max($emails));

        foreach ($attachments as $key => $value) {
            if ($value['filename'] == '' || pathinfo($value['filename'], PATHINFO_EXTENSION) != 'csv') {
                unset($attachments[$key]);
            }
        }

        $headersContentCSV = [];
        $contentCSV = [];

        if (count($attachments) != 0) {
            foreach ($attachments as $at) {
                if ($at['is_attachment'] == 1) {
                    if (!file_exists('./storage/Archivos/MarkVision/' . $at['filename'])) {
                        $headersContentCSV = [];
                        $contentCSV = [];

                        $content = explode(PHP_EOL, str_replace('"', '', $at['attachment']));
                        $headersContentCSV = explode(',', $content[0]);
                        unset($content[0]);

                        foreach ($content as $k => $v) {
                            array_push($contentCSV, explode(",", $v));
                        }

                        $saveToDatabase = $this->DB->saveMarkvisionRead('/storage/Archivos/MarkVision/' . $at['filename'], $contentCSV);
                        if ($saveToDatabase['code'] == 200) {
                            file_put_contents('./storage/Archivos/MarkVision/' . $at['filename'], $at['attachment']);
                            $file = $this->createMarkvisionReport($saveToDatabase['printersToNotification']);
                            unset($saveToDatabase['printersToNotification']);
                            $saveToDatabase = array_merge($saveToDatabase, $file);
                            $this->sendMailMarkvisionReport($file['pathFile']);
                            return $saveToDatabase;
                            break;
                        } else {
                            return $saveToDatabase;
                        }
                    } else {
                        return ['code' => 200, 'message' => 'We already have the last report. There is nothing to inform'];
                    }
                }
            }
        }
    }

    private function sendMailMarkvisionReport(string $url)
    {
        $titulo = 'Informe Estatus Markvision ' . date("ymd-His");
        $texto = '
        <p>Estimado(a) colaborador(a):</p>
        <p></p>
        <p>Este correo contiene una liga al reporte de niveles de toner de las impresoras Lexmark</p>
        <p>Es importante que atienda la información del mismo y, en caso de ser necesario, apoye en las actividades que le corresponden para el envío e instalación del toner.</p>
        <p></p>
        <a href="' . $url . '" target="_blank">' . $url . '</a>';

        $bodyMail = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', ['ajimenez@siccob.com.mx'], $titulo, $bodyMail);
    }

    private function createMarkvisionReport(array $infoPrinters)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit('1200');

        $arrayTitulos = [
            'IP',
            'Sucursal',
            'Capacidad Total',
            'Nivel Actual'
        ];
        $arrayWidth = [20, 35, 20, 20];
        $arrayAlign = ['', '', 'center', 'center'];

        $this->Excel->createSheet('Red Status', 0);
        $this->Excel->setActiveSheet(0);
        $this->Excel->setTableSubtitles('A', 3, $arrayTitulos);
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $this->Excel->setTableTitle("A1", "D1", "Detalle Focos Rojos", array('titulo'));
        $this->Excel->setTableContent('A', 3, $infoPrinters['red'], true, $arrayAlign);

        $this->Excel->createSheet('Yellow Status', 1);
        $this->Excel->setActiveSheet(1);
        $this->Excel->setTableSubtitles('A', 3, $arrayTitulos);
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $this->Excel->setTableTitle("A1", "D1", "Detalle Focos Amarillo", array('titulo'));
        $this->Excel->setTableContent('A', 3, $infoPrinters['yellow'], true, $arrayAlign);

        $this->Excel->createSheet('Green Status', 2);
        $this->Excel->setActiveSheet(2);
        $this->Excel->setTableSubtitles('A', 3, $arrayTitulos);
        $this->Excel->setColumnsWidth('A', $arrayWidth);
        $this->Excel->setTableTitle("A1", "D1", "Detalle Focos Verdes", array('titulo'));
        $this->Excel->setTableContent('A', 3, $infoPrinters['green'], true, $arrayAlign);

        $this->Excel->setActiveSheet(0);

        $time = date("ymd_H_i_s");
        $nombreArchivo = 'Informe_Estatus_Markvision_' . $time . '.xlsx';
        $nombreArchivo = trim($nombreArchivo);
        $ruta = 'storage/Archivos/Markvision/' . $nombreArchivo;

        //Guarda la hoja envíandole la ruta y el nombre del archivo que se va a guardar.
        $this->Excel->saveFile($ruta);

        return ['pathFile' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $ruta];
    }
}
