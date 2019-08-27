<?php

namespace Librerias\V2\PaquetesTicket\Redes;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Librerias\V2\PaquetesTicket\Redes\GestorNodosRedes as GestorNodo;
use Librerias\V2\PaquetesGenerales\Utilerias\PDF as PDF;
use Modelos\Modelo_ServicioCableado as Modelo;
use Modelos\Modelo_ServicioTicket as ModeloServicioTicket;

class ServicioCableado implements Servicio {

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

    public function __construct(string $idServicio) {
        $this->id = $idServicio;
        $this->DBServiciosGeneralRedes = new Modelo();
        $this->DBServicioTicket = new ModeloServicioTicket();
        $this->gestorNodos = new GestorNodo($this->id);
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

    public function startServicio(string $atiende) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setFechaAtencion($this->id, $atiende);
        $this->setEstatus('2');
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
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
        $datos['nodos'] = $this->gestorNodos->getNodos();
        $datos['totalMaterial'] = $this->gestorNodos->getTotalMaterial();
        return $datos;
    }

    public function runAccion(string $evento, array $datos = array()) {

        switch ($evento) {
            case 'agregarNodo':
                $this->gestorNodos->setNodo($datos);
                break;
            case 'borrarNodo':
                $this->gestorNodos->deleteNodo($datos['idNodo']);
                break;
            case 'actualizarNodo':
                $this->gestorNodos->updateNodo($datos);
                break;
            case 'borrarNodos':
                $this->borrarNodos();
                $this->setSucursal($datos['idSucursal']);
                break;
            case 'borrarArchivos':
                $this->gestorNodos->deleteArchivosNodo($datos);
                break;
            case 'borrarArchivo':
                $this->gestorNodos->deleteArchivo($datos);
                break;
            default:
                break;
        }
    }

    private function borrarNodos() {
        $nodosEliminados = array();
        $nodos = $this->gestorNodos->getNodos();
        foreach ($nodos as $value) {
            if (!in_array($value['IdNodo'], $nodosEliminados)) {
                $this->gestorNodos->deleteNodo($value['IdNodo']);
                array_push($nodosEliminados, $value['IdNodo']);
            }
        }
    }

    public function setSucursal(string $idSucursal) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setSucursal($this->id, $idSucursal);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function setProblema(array $datos) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setProblema($this->id, $datos);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function getProblemas() {
        $datos = array();
        $consulta = $this->DBServiciosGeneralRedes->getProblemas($this->id);

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

    public function setSolucion(array $datos) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $consulta = $this->DBServiciosGeneralRedes->getEvidencias($this->id);

        if (empty($consulta)) {
            array_push($consulta, array('Archivos' => ''));
        }

        if (!array_key_exists('archivos', $datos)) {
            $datos['archivos'] = $consulta[0]['Archivos'];
        } else if (!empty($consulta[0]['Archivos'])) {
            $datos['archivos'] .= ',' . $consulta[0]['Archivos'];
        }

        $this->DBServiciosGeneralRedes->setSolucion($this->id, $datos);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function endServicio(string $termina) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setConclusion($this->id, $termina);
        $this->setEstatus('4');
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
    }

    public function setConcluir(array $datos) {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setConclusion($this->id, $datos);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
        $archivo = '<p>******* Termino de servicio de cableado ********</p>
                    <p><strong>Descripción:</strong> Se concluye el servicio de cableado</p>';
        return $archivo;
    }

    public function deleteEvidencias() {
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $temporal = $this->DBServiciosGeneralRedes->getEvidencias($this->id);
        $evidencias = explode(',', $temporal[0]['Archivos']);
        $this->DBServiciosGeneralRedes->deleteEvidencias($this->id);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
        return $evidencias;
    }

    public function getFirmas(string $idServicio) {
        $consulta = $this->DBServiciosGeneralRedes->getFirmas($idServicio);
        return $consulta[0]['firmas'];
    }

    public function getPDF(array $datos) {
        $informacionGeneral = $this->DBServiciosGeneralRedes->getDatosSolucionPDF($datos);
        var_dump($informacionGeneral);
        $pdf = new PDF($this->folioSolicitud);
        $pdf->AddPage();        
        $pdf->tituloTabla('#1 Información General');
        $pdf->tabla(array(), $informacionGeneral);
//        $pdf->tablaImagenes(array('/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga2.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga1.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga2.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga2.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga1.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga2.jpg',
//            '/storage/Archivos/Servicios/Servicio-32364/EvidenciaProblemas/descarga2.jpg'));
//        $pdf->tituloTabla('Solución del Servicio');
//        $pdf->firma(array(array('/storage/Archivos/Servicios/Servicio-32364/EvidenciasFirmas/Firma-Cliente-prueba.png','Nombre','Puesto')));
        $carpeta = $pdf->definirArchivo('Servicios/Servicio-' . $this->id . '/PDF', 'PruebaPDF');
        $pdf->Output('F', $carpeta, true);
        $archivo = substr($carpeta, 1);
        return $archivo;
    }

    public function cambiarEstatusServicio(array $datos) {
        $consulta = $this->DBServicioTicket->cambiarEstatusServicio($datos);
        return $consulta;
    }

}
