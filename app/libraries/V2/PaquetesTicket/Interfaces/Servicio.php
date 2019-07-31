<?php
namespace Librerias\V2\PaquetesTicket\Interfaces;


interface Servicio
{
    
    public function __construct(string $idServicio);
   
    public function setDatos();
    
    public function getDatos();
   
    public function setFolioServiceDesk(string $folio);

}