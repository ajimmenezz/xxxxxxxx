<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenUsuario;
use Librerias\V2\PaquetesTicket\Nodo as Nodo;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class ServicioGeneralRedes implements Servicio {

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
    private $nodo;
    private $almacenUsuario;

    public function __construct(string $idServicio) {
        $this->id = $idServicio;
        $this->DBServiciosGeneralRedes = new Modelo();
        $this->almacenUsuario = new AlmacenUsuario();
        $this->nodo = new Nodo($this->id);
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
            "Sucursal" => $this->idSucursal,
            "FechaSolicitud" => $this->fechaSolicitud,
            "descripcionSolicitud" => $this->descripcionSolicitud
        );
    }

    public function setFolioServiceDesk(string $folio) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setFolioServiceDesk($this->idSolicitud, $folio);
        $this->setDatos();
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function getCliente() {
        return $this->idCliente;
    }

    public function getSolucion() {
        $datos = array();
        $datos['solucion'] = $this->DBServiciosGeneralRedes->getDatosSolucion($this->id);
        $datos['IdSucursal'] = $this->idSucursal;
        $datos['nodos'] = $this->nodo->getNodos();
        $datos['totalMaterial'] = $this->nodo->getTotalMaterial();
        return $datos;
    }

    public function runAccion(string $evento, array $datos = array()) {
        $respuesta = array();

        switch ($evento) {
            case 'agregarNodo':
                $this->nodo->setNodo($datos);
                break;
            case 'borrarNodo':
                $this->nodo->deleteNodo($datos['idNodo']);
                break;
            case 'actualizarNodo':
                $this->nodo->updateNodo($datos);
                break;
            case 'borrarNodos':
                $this->borrarNodos();
                $this->setSucursal($datos['idSucursal']);
                break;
            case 'borrarMaterial':
                break;
            default:
                break;
        }

        $respuesta['solucion'] = $this->getSolucion();
        $respuesta['materialUsuario'] = $this->almacenUsuario->getAlmacen();
        return $respuesta;
    }
       
    private function borrarNodos() {
        $nodosEliminados = array();
        $nodos = $this->nodo->getNodos();
        foreach ($nodos as $value) {
            if(!in_array($value['IdNodo'], $nodosEliminados)){
                $this->nodo->deleteNodo($value['IdNodo']);
                array_push($nodosEliminados, $value['IdNodo']);
            }            
        }
    }
    
    public function setSucursal(string $idSucursal){
        
    }

}
