<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio {

    private $DBServiciosGeneralRedes;
    private $datos;
    private $id;
    private $sucursales;

    public function __construct(string $idServicio) {
        $this->DBServiciosGeneralRedes = new Modelo();
        $this->id = $idServicio;
        $this->setDatos();
    }

    public function setDatos() {
        $this->datos = $this->DBServiciosGeneralRedes->getDatosServicio($this->id);
        $folio=$this->datos[0]['Folio'];
       
        $info=array($this->datos[0]['FechaCreacion'],
                     $this->datos[0]['Ticket'],
                     $this->datos[0]['atentidoPor'],
                     $this->datos[0]['IdSolicitud'],
                     $this->datos[0]['Descripcion'],
                     $this->datos[0]['solicitadoPor'],
                     $this->datos[0]['descripcionSolicitud']);
        $this->datos=array($info,$folio);
        
        //Obteniendo datos de la sucursal
        $this->sucursales=$this->datos = $this->DBServiciosGeneralRedes->getSucursal();
        
    }

    public function getDatos() {
        return $this->datos;//['FechaCreacion'];
    }

    public function setFolioServiceDesk(string $folio) {
        
    }

}
