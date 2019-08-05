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
        $folio = $this->datos[0]['Folio'];

        $informacionServicio = array(
            'FechaCreacion' => $this->datos[0]['FechaCreacion'],
            'Ticket' => $this->datos[0]['Ticket'],
            'Atiende' => $this->datos[0]['Atiende'],
            'IdSolicitud' => $this->datos[0]['IdSolicitud'],
            'Descripcion' => $this->datos[0]['Descripcion'],
            'Solicita' => $this->datos[0]['Solicita'],
            'DescripcionSolicitud' => $this->datos[0]['DescripcionSolicitud']);

        $this->datos = array(
            'informacionServicio' => $informacionServicio,
            'folio' => $folio);
    }

    public function getDatos() {
        return $this->datos;
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
