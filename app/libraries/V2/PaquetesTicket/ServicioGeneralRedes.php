<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio {

    private $DBServiciosGeneralRedes;
    private $id;
    private $idSucursal;
    private $idCliente;
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
        $consulta = $this->DBServiciosGeneralRedes->getDatosServicio($this->id);
        $this->idSucursal = $consulta[0]['IdSucursal'];
        $this->idCliente = $consulta[0]['IdCliente'];
        $this->folioSolicitud = $consulta[0]['Folio'];
        $this->fechaCreacion = $consulta[0]['FechaCreacion'];
        $this->fechaSolicitud = $consulta[0]['FechaSolicitud'];
        $this->ticket = $consulta[0]['Ticket'];
        $this->atiende = $consulta[0]['Atiende'];
        $this->idSolicitud = $consulta[0]['IdSolicitud'];
        $this->descripcion = $consulta[0]['Descripcion'];
        $this->solicita = $consulta[0]['Solicita'];
        $this->descripcionSolicitud = $consulta[0]['DescripcionSolicitud'];
    }

    public function setEstatus(string $estatus) {
        try {
            $this->DBServiciosGeneralRedes->empezarTransaccion();            
            $this->DBServiciosGeneralRedes->setEstatus($this->id, $estatus);
            $this->DBServiciosGeneralRedes->finalizarTransaccion();
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
    }

    public function getFolio() {
        return $this->folioSolicitud;
    }

    public function getDatos() {
        return array("Folio" => $this->folioSolicitud,
            "FechaCreacion" => $this->fechaCreacion,
            "Ticket" => $this->ticket,
            "Atiende" => $this->atiende,
            "idSolicitud" => $this->idSolicitud,
            "Descripcion" => $this->descripcion,
            "Solicita" => $this->solicita,
            "FechaSolicitud" => $this->fechaSolicitud,
            "descripcionSolicitud" => $this->descripcionSolicitud
        );
    }

    public function getSucursales() {
        $this->sucursales = $this->DBServiciosGeneralRedes->getSucursal('1');
        return $this->sucursales;
    }

    public function setFolioServiceDesk(string $folio) {
        $respuesta = $this->DBServiciosGeneralRedes->setFolioServiceDesk(array('idServicio' => $this->id, 'folio' => $folio));
        return $respuesta;
    }

    public function getCliente() {
        return $this->idCliente;
    }

}
