<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
use Librerias\V2\PaquetesClientes\GestorClientes as GestorCliente;
use Librerias\V2\PaquetesSucursales\GestorSucursales as GestorSucursal;
use Librerias\V2\PaquetesTicket\GestorServicios as GestorServicio;
use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenVirtual;
use Librerias\V2\PaquetesTicket\Utilerias\Solicitud as Solicitud;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $gestorClientes;
    private $gestorSucursales;
    private $gestorServicios;
    private $servicio;
    private $datos;
    private $almacenVirtual;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->gestorClientes = new GestorCliente();
        $this->gestorSucursales = new GestorSucursal();
        $this->gestorServicios = new GestorServicio();
        $this->almacenVirtual = new AlmacenVirtual();
        $this->datos = array();
        $this->load->helper(array('conversionpalabra'));
    }

    public function atenderServicio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $idUsuario = Usuario::getId();
            $this->servicio->startServicio($idUsuario);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['clientes'] = $this->gestorClientes->getClientes('1,4,12,18,20');
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales();
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = null;
            $this->datos['firmas'] = null;
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($datosServicio['tipo'], array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio($this->servicio->getFolio());
            $this->setEstatusServiceDesk();
            $this->datos['html'] = $this->getHtml($datosServicio['tipo'], $this->datos);
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['operacion'] = FALSE;
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function seguimientoServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales();
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = $this->servicio->getProblemas();
            $this->datos['firmas'] = $this->servicio->getFirmas($datosServicio['id']);
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($tipoServicio, array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio($this->servicio->getFolio());
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['operacion'] = FALSE;
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function getInformacionFolio(string $folio = NULL) {
            $this->datos['folio'] = null;
            $this->datos['notasFolio'] = null;
            $this->datos['resolucionFolio'] = null;

            if (!empty($folio)) {               
                $this->datos['folio'] = ServiceDesk::getDatos($folio);
                $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
                $this->datos['resolucionFolio'] = ServiceDesk::getResolucion($folio);
            }
    }

    private function setEstatusServiceDesk() {
        $estatusFolio = null;
        try {
            if (!empty($this->datos['folio'] && property_exists($this->datos['folio'], 'STATUS'))) {
                $estatusFolio = $this->datos['folio']->STATUS;
            }

            if ($estatusFolio === 'Abierto') {
                ServiceDesk::setEstatus('En Atención', $this->servicio->getFolio());
            }
        } catch (Exception $ex) {
            
        }
    }

    public function setFolio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            if ($datosServicio['folio'] !== '') {
                $this->servicio->validarFolioServiceDesk($datosServicio['folio']);
            }
            $this->getInformacionFolio($datosServicio['folio']);
            $this->servicio->setFolioServiceDesk($datosServicio['folio']);
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function eliminarFolio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->deleteFolio();
            echo json_encode(TRUE);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function getInformacionFolioNuevo(string $folio) {
        try {
            $this->datos['folio'] = null;
            $this->datos['notasFolio'] = null;
            $this->datos['resolucionFolio'] = null;

            if (!empty($this->servicio->getFolio())) {
                $this->datos['folio'] = ServiceDesk::getDatos($folio);
                $this->datos['notasFolio'] = ServiceDesk::getNotas($folio);
                $this->datos['resolucionFolio'] = ServiceDesk::getResolucion($folio);
            }
        } catch (Exception $ex) {
            $this->datos['folio'] = array('Error' => $ex->getMessage());
            $this->datos['notasFolio'] = array('Error' => $ex->getMessage());
            $this->datos['resolucionFolio'] = array('Error' => $ex->getMessage());
        }
    }

    public function runEvento(string $evento) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->runAccion($evento, $datosServicio);
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($datosServicio['tipo'], array('datosServicio' => $this->servicio->getDatos()));
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['ERROR'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function setProblema() {
        try {
            $datosServicio = $this->input->post();
            $datosServicio['idUsuario'] = Usuario::getId();
            if ($datosServicio['evidencia'] !== 'false') {
                $carpeta = 'Servicios/Servicio-' . $datosServicio['id'] . '/EvidenciaProblemas/';
                Archivo::saveArchivos($carpeta);
                $datosServicio['archivos'] = Archivo::getString();
            } else {
                $datosServicio['archivos'] = null;
            }
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->setProblema($datosServicio);

            $key = Usuario::getAPIKEY();
            $key = ServiceDesk::validarAPIKey(strval($key));

            if ($key !== '') {
                $this->setNotaServiceDesk($datosServicio);
                $this->getInformacionFolio($this->servicio->getFolio());
            }

            $this->datos['problemas'] = $this->servicio->getProblemas();
            $this->datos['html']['bitacora'] = $this->getHtmlBitacora();
            $this->datos['operacion'] = TRUE;

            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function getMaterial() {
        $datosServicio = $this->input->post();
        $this->datos['materialAlmacen'] = $this->almacenVirtual->getAlmacen($datosServicio["tipoMaterial"]);
        echo json_encode($this->datos);
    }

    public function setSolucion() {
        try {
            $datosServicio = $this->input->post();
            $datosServicio['idUsuario'] = Usuario::getId();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            if ($datosServicio['material'] !== 'false') {
                $carpeta = 'Servicios/Servicio-' . $datosServicio['id'] . '/EvidenciaSolucion/';
                Archivo::saveArchivos($carpeta);
                $datosServicio['archivos'] = Archivo::getString();
            }
            $this->servicio->setSolucion($datosServicio);
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function setNotaServiceDesk(array $datosServicio) {
        $descripcion = '';
        $evidencias = '<p>';
        $host = 'http://' . $_SERVER['SERVER_NAME'];

        if (!empty($datosServicio['folio'])) {
            $archivos = explode(',', $datosServicio['archivos']);
            $descripcion = '<p>***** Problema ******</p>';
            $descripcion .= '<p>Descripcion: <strong>' . $datosServicio['descripcion'] . '</strong></p>';
            foreach ($archivos as $key => $path) {
                $evidencias .= '<a href="' . $host . $path . '" target="_blank">Evidencia-' . ($key + 1) . '</a>  ';
            }
            $evidencias .= '</p>';
            $descripcion .= $evidencias;

            ServiceDesk::setEstatus('Problema', $datosServicio['folio']);
            ServiceDesk::setNota($datosServicio['folio'], $descripcion);
        }
    }

    public function setConcluir() {
        try {
            $datosServicio = $this->input->post();
            $datosServicio['idUsuario'] = Usuario::getId();
            $carpeta = 'Servicios/Servicio-' . $datosServicio['id'] . '/EvidenciasFirmas';
            $firmaCliente = stripAccents($datosServicio['nombreCliente']);
            $firmas = array(
                'Firma-Cliente-' . str_replace(" ", "_", $firmaCliente) => $datosServicio['firmaCliente'],
            );
            Archivo::saveArchivos64($carpeta, $firmas);
            $datosServicio['archivos'] = Archivo::getArray();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $datosServicio['mensaje'] = $this->servicio->setConcluir($datosServicio);
            $this->setResolucionServiceDesk($datosServicio);
            $this->almacenVirtual->updateAlmacen($datosServicio);
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (\Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function setResolucionServiceDesk(array $datosServicio) {
        $this->getInformacionFolio($this->servicio->getFolio());

        if ($this->datos['folio'] !== NULL) {
            ServiceDesk::setNota($datosServicio['folio'], $datosServicio['mensaje']);
        }
    }

    public function deleteEvidencias() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $archivos = $this->servicio->deleteEvidencias();
            foreach ($archivos as $value) {
                Archivo::deleteArchivo($value);
            }
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($datosServicio['tipo'], array('datosServicio' => $this->servicio->getDatos()));
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function getPDF() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->datos['PDF'] = $this->servicio->getPDF($datosServicio);
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function validarServicio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $idSolicitud = $this->servicio->getDatos()['idSolicitud'];
            $solicitud = new Solicitud($idSolicitud);
            $this->servicio->setEstatus('4');
            $solicitud->verificarServiciosParaConcluirSolicitudTicket();
            $this->servicio->enviarServicioConcluido($datosServicio);
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function rechazarServicio() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->setEstatus('2');
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function guardarInformacionGeneral() {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->setInformacionGeneral($datosServicio);
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function getHtml(string $tipoServicio, array $datos) {
        $html = array();
       
        $html['folio'] = $this->getHtmlFolio($datos);
        $html['bitacora'] = $this->getHtmlBitacora();

        switch ($tipoServicio) {
            case 'Instalaciones':
                $html['solucion'] = $this->load->view('V2/PaquetesTickets/Poliza/SolucionServicioInstalaciones', $datos, TRUE);
                break;
            default:
                break;
        }

        return $html;
    }

    private function getHtmlFolio(array $datos) {
        return $this->load->view('V2/PaquetesTickets/InformacionFolio', $datos, TRUE);
    }

    private function getHtmlBitacora() {
        $datosAvacenProblema['avanceServicio'] = $this->servicio->getAvanceProblema();
        return $this->load->view('Generales/Detalles/HistorialAvancesProblemas', $datosAvacenProblema, TRUE);
    }

}
