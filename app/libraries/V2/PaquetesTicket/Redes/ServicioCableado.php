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
        $this->DBServiciosGeneralRedes->empezarTransaccion();
        $this->DBServiciosGeneralRedes->setEstatus($this->id, $estatus);
        $this->DBServiciosGeneralRedes->finalizarTransaccion();
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

        $consulta = $this->DBServiciosGeneralRedes->getDatosServicio($this->id);

        if (empty($consulta)) {
            $this->DBServiciosGeneralRedes->setServicio($this->id, $datos);
        } else {
            $this->DBServiciosGeneralRedes->updateServicio($this->id, $datos);
        }

        $this->DBServiciosGeneralRedes->setSucursal($this->id, $datos['idSucursal']);
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
        $informacionServicio = $this->DBServiciosGeneralRedes->getDatosSolucionPDF($datos);
        $pdf = new PDF($this->folioSolicitud);
        $pdf->AddPage();
        $pdf->tituloTabla('#1 Información General');
        $pdf->tabla(array(), $informacionServicio['infoGeneral']);
        $pdf->tituloTabla('Solución del Servicio');
        $pdf->tabla(array(), $informacionServicio['infoNodos']);
        $evidencias = explode(',', $informacionServicio['evidencias'][0]['Archivos']);
        $pdf->tablaImagenes($evidencias);
        $pdf->tituloTabla('Firmas del Servicio');
        $pdf->firma($informacionServicio['infoFirmas'][0]);
        $carpeta = $pdf->definirArchivo('Servicios/Servicio-' . $this->id . '/PDF', 'PruebaPDF');
        $pdf->Output('F', $carpeta, true);
        $archivo = substr($carpeta, 1);
        return $archivo;
    }
    
    public function enviarServicioConcluido(array $datos){
        var_dump('pumas');
//        var_dump($this->setDatos());
        $archivoPDF = $this->getPDF($datos);
        var_dump($archivoPDF);
//                        $serviciosConcluidos = $this->DBST->consultaGeneral('SELECT 
//                                                                        tse.Id, 
//                                                                        tse.Ticket,
//                                                                        nombreUsuario(tso.Atiende) Atiende,
//                                                                        (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = tso.Atiende) CorreoAtiende,
//                                                                        tso.Solicita
//                                                                FROM t_servicios_ticket tse
//                                                                INNER JOIN t_solicitudes tso
//                                                                ON tse.IdSolicitud = tso.Id
//                                                                WHERE tse.Ticket = "' . $datos['ticket'] . '"');
//
//                foreach ($serviciosConcluidos as $key => $value) {
//                    $contador++;
//                    $linkPdfServiciosConcluidos = $this->getServicioToPdf(array('servicio' => $value['Id']));
//                    $infoServicioServiciosConcluidos = $this->getInformacionServicio($value['Id']);
//                    $tipoServicioServiciosConcluidos = stripAccents($infoServicioServiciosConcluidos[0]['NTipoServicio']);
//
//                    if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
//                        $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $value['Id'] . '/Pdf/Ticket_' . $value['Ticket'] . '_Servicio_' . $value['Id'] . '_' . $tipoServicioServiciosConcluidos . '.pdf';
//                        $linkDetallesSolicitud = 'http://siccob.solutions/Detalles/Solicitud/' . $datosDescripcionConclusion[0]['IdSolicitud'];
//                    } else {
//                        $path = 'http://' . $host . '/' . $linkPdfServiciosConcluidos['link'];
//                        $linkDetallesSolicitud = 'http://' . $host . '/Detalles/Solicitud/' . $datosDescripcionConclusion[0]['IdSolicitud'];
//                    }
//
//                    $linkPDF .= '<br>Ver Servicio PDF-' . $contador . ' <a href="' . $path . '" target="_blank">Aquí</a>';
//                }
//
//                $titulo = 'Solicitud Concluida';
//                $linkSolicitud = 'Ver detalles de la Solicitud <a href="' . $linkDetallesSolicitud . '" target="_blank">Aquí</a>';
//                $textoCorreo = '<p>Estimado(a) <strong>' . $value['Atiende'] . ',</strong> se ha concluido la Solicitud.</p><br>Ticket: <strong>' . $value['Ticket'] . '</strong><br> Número Solicitud: <strong>' . $datosDescripcionConclusion[0]['IdSolicitud'] . '</strong><br><br>' . $linkSolicitud . '<br>' . $linkPDF;
//
//                $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $textoCorreo);
//                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($value['CorreoAtiende']), $titulo, $mensajeFirma);
    }

}
