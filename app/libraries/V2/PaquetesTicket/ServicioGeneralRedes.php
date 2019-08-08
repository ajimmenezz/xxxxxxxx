<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio {

    private $DBServiciosGeneralRedes;
    private $consulta;
    private $id;
    private $sucursales;
    private $folioSolicitud;
    private $fechaCreacion;
    private $ticket;
    private $atiende;
    private $idSolicitud;
    private $descripcion;
    private $solicita;
    private $descripcionSolicitud;
    
    

    public function __construct(string $idServicio) {
        $this->DBServiciosGeneralRedes = new Modelo();
        $this->id = $idServicio;
        $this->setDatos();
    }

    public function setDatos() {
        $this->consulta = $this->DBServiciosGeneralRedes->getDatosServicio($this->id);
        
        $this->folioSolicitud = $this->consulta[0]['Folio'];
        $this->fechaCreacion = $this->consulta[0]['FechaCreacion'];
        $this->fechaSolicitud = $this->consulta[0]['FechaSolicitud'];
        $this->ticket = $this->consulta[0]['Ticket'];
        $this->atiende = $this->consulta[0]['Atiende'];
        $this->idSolicitud = $this->consulta[0]['IdSolicitud'];
        $this->descripcion = $this->consulta[0]['Descripcion'];
        $this->solicita = $this->consulta[0]['Solicita'];
        $this->descripcionSolicitud = $this->consulta[0]['DescripcionSolicitud'];
  
    }

    public function getDatos() {
        return array("Folio"=>$this->folioSolicitud,
                     "FechaCreacion"=>$this->fechaCreacion,
                     "Ticket"=>$this->ticket,
                     "Atiende"=>$this->atiende,
                     "idSolicitud"=>$this->idSolicitud,
                     "Descripcion"=>$this->descripcion,
                     "Solicita"=>$this->solicita,
                     "FechaSolicitud"=> $this->fechaSolicitud,
                     "descripcionSolicitud"=>$this->descripcionSolicitud
                     );
    }

    public function getSucursales() {
        $this->sucursales = $this->DBServiciosGeneralRedes->getSucursal('1');
        return $this->sucursales;
    }

    public function setFolioServiceDesk(string $folio) {
        var_dump($folio);
        $respuesta = $this->DBServiciosGeneralRedes->setFolioServiceDesk(array('idServicio' => $this->id, 'folio' => $folio));
        return $respuesta;
    }

}
