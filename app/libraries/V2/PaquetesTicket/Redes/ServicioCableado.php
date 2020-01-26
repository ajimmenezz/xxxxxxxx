<?php

namespace Librerias\V2\PaquetesTicket\Redes;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Librerias\V2\PaquetesTicket\Redes\GestorNodosRedes as GestorNodo;
use Librerias\V2\PaquetesGenerales\Utilerias\PDF as PDF;
use Librerias\V2\PaquetesTicket\Utilerias\Solicitud as Solicitud;
use Modelos\Modelo_ServicioCableado as Modelo;
use Modelos\Modelo_ServicioTicket as ModeloServicioTicket;
use Librerias\Generales\Correo as Correo;

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
    private $correoAtiende;
    private $pdf;

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
        $this->correoAtiende = $consulta[0]['CorreoAtiende'];
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

    public function setFolioServiceDesk(string $folio = NULL) {
        $solicitud = new solicitud($this->idSolicitud);
        $folioSolicitudes = $solicitud->folioSolicitudes(array('folio' => $folio));

        if (empty($folioSolicitudes)) {
            $this->DBServiciosGeneralRedes->empezarTransaccion();
            $this->DBServiciosGeneralRedes->setFolioServiceDesk($this->idSolicitud, $folio);
            $this->setDatos();
            $this->DBServiciosGeneralRedes->finalizarTransaccion();
            return TRUE;
        } else {
            return FALSE;
        }
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
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datos['fecha'] = $fecha;

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

        $consulta = $this->DBServiciosGeneralRedes->getDatosSolucion($this->id);

        if (empty($consulta)) {
            $this->DBServiciosGeneralRedes->setServicio($this->id, $datos);
        } else {
            $consulta = $this->DBServiciosGeneralRedes->updateServicio($this->id, $datos);
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

    public function deleteFolio() {
        $datos = $this->getDatos();
        $this->DBServiciosGeneralRedes->setFolioServiceDesk($datos['idSolicitud']);
    }

    public function getFirmas(string $idServicio) {
        $consulta = $this->DBServiciosGeneralRedes->getFirmas($idServicio);
        return $consulta[0]['firmas'];
    }

    public function getPDF(array $datos) {
        $informacionServicio = $this->DBServiciosGeneralRedes->getDatosSolucionPDF($datos);

        $this->pdf = new PDF($this->id);
        $this->pdf->AddPage();
        $this->pdf->tituloTabla('Información General');
        $this->pdf->tabla(array(), $informacionServicio['infoGeneral']);

        $totalMaterial = $this->gestorNodos->getTotalMaterial();
        $arrayNuevoTotalMaterial = array();

        foreach ($totalMaterial as $key => $value) {
            $arrayNuevoTotalMaterial[$key] = array($value['Producto'], $value['Cantidad']);
        }

        $this->FancyTable(array('Material', 'Cantidad'), $arrayNuevoTotalMaterial);

        $contador = 1;
        foreach ($informacionServicio['infoNodos'] as $key => $value) {
            $ancho = $this->pdf->GetPageWidth() - 20;
//            var_dump($ancho);
            $y = $this->pdf->GetY();
            $x = 30;

            if ($x < $ancho) {
                $arrayNuevo = array(
                    'Area' => $value['Area'],
                    'Nodo' => $value['Nodo'],
                    'Switch' => $value['Switch'],
                    'NumeroSwitch' => $value['NumeroSwitch']);
                $this->pdf->tituloTabla('Solución del Nodo: ' . $contador);
                $this->pdf->tabla(array(), array($arrayNuevo));
                $evidencias = explode(',', $value['Evidencias']);
                $this->pdf->tablaImagenes($evidencias);
                $contador ++;
                $x += 80;
            } else {
                $x = 30;
                $y += 50;
            }
            $altura = $y + 35;

//            var_dump($altura);
//            var_dump($this->pdf->GetPageHeight() - 80);

            if ($altura > ($this->pdf->GetPageHeight() - 250)) {
                $this->pdf->AddPage();
//                $y = 25;
            }
        }

        $this->pdf->tituloTabla('Firmas del Servicio');
        $this->pdf->firma($informacionServicio['infoFirmas'][0]);
        $carpeta = $this->pdf->definirArchivo('Servicios/Servicio-' . $this->id . '/PDF', $this->id . '-PDF');
        $this->pdf->Output('F', $carpeta, true);
        $archivo = substr($carpeta, 1);
        return $archivo;
    }

    function FancyTable($header, $data) {
        // Colors, line width and bold font
        $this->pdf->SetFillColor(31, 56, 100);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetLineWidth(.3);
        $this->pdf->SetFont('', 'B');
        // Header
        $w = array(95, 95);
        for ($i = 0; $i < count($header); $i++)
            $this->pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->pdf->Ln();
        // Color and font restoration
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        // Data
        $fill = false;

        foreach ($data as $row) {
            $this->pdf->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->pdf->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->pdf->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->pdf->Cell(array_sum($w), 0, '', 'T');
        $this->pdf->Ln(10);
    }

    public function enviarServicioConcluido(array $datos) {
        $correo = new Correo();
        $host = $_SERVER['SERVER_NAME'];
        $archivoPDF = $this->getPDF($datos);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/' . $archivoPDF;
        } else {
            $path = 'http://' . $host . '/' . $archivoPDF;
        }

        $linkPDF = '<br>Ver Servicio PDF <a href="' . $path . '" target="_blank">Aquí</a>';
        $titulo = 'Servicio Concluido';
        $textoCorreo = '<p>Estimado(a) <strong>' . $this->atiende . ',</strong> se ha concluido el </p><br>Servicio: <strong>' . $this->id . '</strong><br> Número Solicitud: <strong>' . $this->idSolicitud . '</strong><br>' . $linkPDF;
        $mensajeFirma = $correo->mensajeCorreo($titulo, $textoCorreo);

        $correo->enviarCorreo('notificaciones@siccob.solutions', array($this->correoAtiende), $titulo, $mensajeFirma);
    }

}
