<?php

use Controladores\Controller_Base as Base;

class Controller_PrinterLexmark extends Base
{

    private $mailReader;

    public function __construct()
    {
        parent::__construct();
        $this->mailReader = new \Librerias\Generales\MailReader();
        $this->reporteLexmark = new \Librerias\Reportes\Lexmark();
    }

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'ReadMailLexmark':
                $resultado = $this->mailReader->getMailReportLexmark();
                break;
            case 'SetDailyPrints':
                $resultado = $this->reporteLexmark->setDailyPrints();
                break;
            default:
                $resultado = ['code' => 404, 'message' => "Not found"];
                break;
        }
        echo json_encode($resultado);
    }
}
