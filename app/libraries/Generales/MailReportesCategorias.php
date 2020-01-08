<?php
namespace Librerias\Generales;

class MailReportesCategorias{

    private $usr;
    private $pass;
    private $server;
    private $connection;
    private $Excel;
    private $Correo;

    public function __construct(){
        $this->Excel = new \Librerias\Generales\CExcel();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usr = 'hhuerta@siccob.com.mx'; //'ajimenez@siccob.com.mx';
        $this->pass = 'Damiahv28'; //'unoPunto1.';
        $this->server = '{localhost:993/imap/ssl}INBOX'; //'{imap.gmail.com:993/imap/ssl}INBOX';
    }
    
    private function openConnection(){
        $this->connection = \imap_open($this->server, $this->usr, $this->pass);
    }

    private function closeConnection(){
        \imap_close($this->connection);
    }

    private function searchEmails(string $stringFilter){
        $emails = \imap_search($this->connection, $stringFilter);
        return $emails;
    }

    private function getEmailHeaders(int $emailID){
        $headers = \imap_headerinfo($this->connection, $emailID);
        return $headers;
    }

    private function getEmailBody(int $emailID){
        $body = \imap_body($this->connection, $emailID);
        return $body;
    }

    public function getMailReportCategory(){
        $this->openConnection();
        $emails = $this->searchEmails('SUBJECT "Retardos"');
        
        var_dump($emails);
    }
}
