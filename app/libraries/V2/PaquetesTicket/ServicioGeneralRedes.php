<?php
namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio
{
    private $DBServiciosGeneralRedes;
    private $datos;
    
    
    public function __construct(string $idServicio) {
        $this->DBServiciosGeneralRedes= new Modelo();
        $this->setDatos();
        
    }

    public function getDatos() {
        
    }

    public function setDatos() {
        $this->datos=$this->DBServiciosGeneralRedes->getDatosServicio($idServicio);
    }

    public function setFolioServiceDesk(string $folio) {
        
    }

}
