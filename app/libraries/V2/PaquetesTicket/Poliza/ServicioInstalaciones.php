<?php

namespace Librerias\V2\PaquetesTicket\Poliza;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Modelos\Modelo_ServicioTicketV2 as ModeloServicioTicket;

class ServicioInstalaciones implements Servicio {

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
    private $DBServiciosGeneralRedes;
    private $DBServicioTicket;
    private $gestorNodos;
    private $correoAtiende;

    public function __construct(string $idServicio) {
        $this->id = $idServicio;
//        $this->DBServiciosGeneralRedes = new Modelo();
        $this->DBServicioTicket = new ModeloServicioTicket();
        $this->setDatos();
    }

    public function setDatos() {
        $consulta = $this->DBServicioTicket->getDatosServicio($this->id);
        $this->idServicio = $consulta[0]['IdServicio'];
        $this->idSucursal = $consulta[0]['IdSucursal'];
        $this->idCliente = $consulta[0]['IdCliente'];
        $this->folioSolicitud = $consulta[0]['Folio'];
        $this->fechaCreacion = $consulta[0]['FechaCreacion'];
        $this->fechaInicio = $consulta[0]['FechaInicio'];
        $this->fechaSolicitud = $consulta[0]['FechaSolicitud'];
        $this->ticket = $consulta[0]['Ticket'];
        $this->atiende = $consulta[0]['Atiende'];
        $this->idSolicitud = $consulta[0]['IdSolicitud'];
        $this->descripcion = $consulta[0]['Descripcion'];
        $this->solicita = $consulta[0]['Solicita'];
        $this->descripcionSolicitud = $consulta[0]['DescripcionSolicitud'];
        $this->correoAtiende = $consulta[0]['CorreoAtiende'];
        $this->tipoServicio = $consulta[0]['TipoServicio'];
    }

    public function startServicio(string $atiende) {
//        $this->DBServiciosGeneralRedes->empezarTransaccion();
//        $this->DBServiciosGeneralRedes->setFechaAtencion($this->id, $atiende);
//        $this->setEstatus('2');
//        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

//
    public function setEstatus(string $estatus) {
//        $this->DBServiciosGeneralRedes->empezarTransaccion();
//        $this->DBServiciosGeneralRedes->setEstatus($this->id, $estatus);
//        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function getFolio() {
        return $this->folioSolicitud;
    }

    public function getDatos() {
        return array("folio" => $this->folioSolicitud,
            "fechaCreacion" => $this->fechaCreacion,
            "fechaInicio" => $this->fechaInicio,
            "ticket" => $this->ticket,
            "atiende" => $this->atiende,
            "solicitud" => $this->idSolicitud,
            "servicio" => $this->idServicio,
            "descripcion" => $this->descripcion,
            "solicita" => $this->solicita,
            "sucursal" => $this->idSucursal,
            "fechaSolicitud" => $this->fechaSolicitud,
            "descripcionSolicitud" => $this->descripcionSolicitud,
            "tipoServicio" => $this->tipoServicio,
            "cliente" => $this->idCliente
        );
    }

    public function setFolioServiceDesk(string $folio) {
        $this->DBServicioTicket->empezarTransaccion();
        $this->DBServicioTicket->actualizarSolicitud(array('folio' => $folio), array('Id' => $this->idSolicitud));
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function getCliente() {
        return $this->idCliente;
    }

    public function getSolucion() {
//        $datos = array();
//        $datos['solucion'] = $this->DBServiciosGeneralRedes->getDatosSolucion($this->id);
//        $datos['IdSucursal'] = $this->idSucursal;
//        $datos['nodos'] = $this->gestorNodos->getNodos();
//        $datos['totalMaterial'] = $this->gestorNodos->getTotalMaterial();
//        return $datos;
    }

    public function runAccion(string $evento, array $datos = array()) {

        switch ($evento) {
//            case 'agregarNodo':
//                $this->gestorNodos->setNodo($datos);
//                break;
//            case 'borrarNodo':
//                $this->gestorNodos->deleteNodo($datos['idNodo']);
//                break;
//            case 'actualizarNodo':
//                $this->gestorNodos->updateNodo($datos);
//                break;
//            case 'borrarNodos':
//                $this->borrarNodos();
//                $this->setSucursal($datos['idSucursal']);
//                break;
//            case 'borrarArchivos':
//                $this->gestorNodos->deleteArchivosNodo($datos);
//                break;
//            case 'borrarArchivo':
//                $this->gestorNodos->deleteArchivo($datos);
//                break;
            default:
                break;
        }
    }

    public function setInformacionGeneral(array $datos) {
        $this->DBServicioTicket->empezarTransaccion();
        $this->DBServicioTicket->actualizarServicio(array('IdSucursal' => $datos['sucursal']), array('Id' => $this->id));
        $this->DBServicioTicket->finalizarTransaccion();
    }

}
