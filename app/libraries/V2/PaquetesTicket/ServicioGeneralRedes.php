<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio {

    private $DBServiciosGeneralRedes;
    private $datos;
    private $id;

    public function __construct(string $idServicio) {
        $this->DBServiciosGeneralRedes = new Modelo();
        $this->id = $idServicio;
        $this->setDatos();
    }

    public function setDatos() {
        $this->datos = $this->DBServiciosGeneralRedes->getDatosServicio($this->id);
    }

    public function getDatos() {
        return $this->datos;
    }

    public function setFolioServiceDesk(string $folio) {
        
    }

}
