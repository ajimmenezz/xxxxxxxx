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
    private $DBServicioTicket;
    private $correoAtiende;
    private $problemas;

    public function __construct(string $idServicio) {
        $this->id = $idServicio;
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
        $this->problemas = $this->getAvanceProblema();
    }

    public function startServicio(string $atiende) {
        $this->DBServicioTicket->empezarTransaccion();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->DBServicioTicket->actualizarServicio(array('FechaInicio' => $fecha, 'Atiende' => $atiende), array('Id' => $this->id));
        $this->setEstatus('2');
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function setEstatus(string $estatus) {
        $this->DBServicioTicket->empezarTransaccion();
        $this->DBServicioTicket->actualizarServicio(array('IdEstatus' => $estatus), array('Id' => $this->id));
        $this->DBServicioTicket->finalizarTransaccion();
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
            "cliente" => $this->idCliente,
            "problemas" => $this->problemas
        );
    }

    public function setFolioServiceDesk(string $folio) {
        $this->DBServicioTicket->empezarTransaccion();
        $this->DBServicioTicket->actualizarSolicitud(array('folio' => $folio), array('Id' => $this->idSolicitud));
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function validarFolioServiceDesk(string $folio) {
        $this->DBServicioTicket->empezarTransaccion();
        $registrosFolio = $this->DBServicioTicket->folioSolicitudes(array('folio' => $folio));
        if (count($registrosFolio) > 1) {
            throw new \Exception('Ya esta asignado a un folio');
        }
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

    public function setProblema(array $datos) {
        $this->DBServicioTicket->empezarTransaccion();
        if ($datos['tipoOperacion'] === 'guardar') {
            $this->DBServicioTicket->setProblema($this->id, $datos);
        } else {
            $arrayAvanceProblema = $this->DBServicioTicket->getAvanceProblemaPorId($datos['idAvanceProblema']);
            if (!empty($datos['archivos'])) {
                $archivos = $arrayAvanceProblema[0]['Archivos'] . ',' . $datos['archivos'];
            } else {
                $archivos = $arrayAvanceProblema[0]['Archivos'];
            }


            if (isset($datos['archivosEleminar'])) {
                $arrayArchivos = explode(',', $archivos);

                foreach ($arrayArchivos as $key => $value) {
                    if (in_array($value, explode(',', $datos['archivosEleminar']))) {
                        unset($arrayArchivos[$key]);
                    }
                }

                $archivos = implode(',', $arrayArchivos);
            }

            $this->DBServicioTicket->actualizarServiciosAvance(array('Descripcion' => $datos['descripcion'], 'Archivos' => $archivos), array('Id' => $datos['idAvanceProblema']));
        }
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function getProblemas() {
        $datos = array();
        $consulta = $this->DBServicioTicket->getAvanceProblema($this->id);

        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $temporal = explode(',', $value['Archivos']);
                array_push($datos, array(
                    'usuario' => $value['Usuario'],
                    'fecha' => $value['Fecha'],
                    'descripcion' => $value['Descripcion'],
                    'archivos' => $temporal
                ));
            }
        }

        return $datos;
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

    public function getAvanceProblema() {
        return $this->DBServicioTicket->getAvanceProblema($this->id);
    }

    public function deleteAvanceProblema(string $idAvanceProblema) {
        $this->DBServicioTicket->empezarTransaccion();
        $temporal = $this->DBServicioTicket->getAvanceProblemaPorId($idAvanceProblema);
        $evidencias = explode(',', $temporal[0]['Archivos']);
        $this->DBServicioTicket->actualizarServiciosAvance(array('Flag' => '0'), array('Id' => $idAvanceProblema));
        $this->DBServicioTicket->finalizarTransaccion();
        return $evidencias;
    }

    public function deleteArchivoProblema(array $datos) {
        $temporal = null;
        $this->DBServicioTicket->empezarTransaccion();

        $arrayAvanceProblema = $this->DBServicioTicket->getAvanceProblemaPorId($datos['idAvanceProblema']);

        if (!empty($arrayAvanceProblema)) {
            foreach ($arrayAvanceProblema as $value) {
                $temporal = explode(',', $value['Archivos']);
            }
        }

        if (in_array($datos['evidencia'], $temporal)) {
            $key = array_search($datos['evidencia'], $temporal);
            unset($temporal[$key]);
        }

        $archivos = implode(',', $temporal);
        $this->DBServicioTicket->actualizarServiciosAvance(array('Archivos' => $archivos), array('Id' => $datos['idAvanceProblema']));
        $this->DBServicioTicket->finalizarTransaccion();
    }

}
