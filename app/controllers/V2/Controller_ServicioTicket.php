<?php

use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;
use Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk as ServiceDesk;
use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
use Librerias\V2\PaquetesSucursales\GestorSucursales as GestorSucursal;
use Librerias\V2\PaquetesTicket\GestorServicios as GestorServicio;

class Controller_ServicioTicket extends CI_Controller {

    private $factory;
    private $gestorSucursales;
    private $gestorServicios;
    private $servicio;
    private $datos;

    public function __construct() {
        parent::__construct();
        $this->factory = new FactoryServiciosTicket();
        $this->gestorSucursales = new GestorSucursal();
        $this->gestorServicios = new GestorServicio();
        $this->datos = array();
    }

    public function atenderServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $idUsuario = Usuario::getId();
            $this->servicio->startServicio($idUsuario);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales($this->servicio->getCliente());
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = null;
            $this->datos['firmas'] = null;
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($tipoServicio, array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio();
            $this->setEstatusServiceDesk();
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function seguimientoServicio(string $tipoServicio) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($tipoServicio, $datosServicio['id']);
            $this->datos['servicio'] = $this->servicio->getDatos();
            $this->datos['sucursales'] = $this->gestorSucursales->getSucursales($this->servicio->getCliente());
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['problemas'] = $this->servicio->getProblemas();
            $this->datos['firmas'] = null;
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($tipoServicio, array('datosServicio' => $this->servicio->getDatos()));
            $this->getInformacionFolio();
            echo json_encode($this->datos);
        } catch (Exception $exc) {
            $this->datos['ERROR'] = $exc->getMessage();
            echo json_encode($this->datos);
        }
    }

    private function getInformacionFolio() {
        try {
            $this->datos['folio'] = null;
            $this->datos['notasFolio'] = null;

            if (!empty($this->servicio->getFolio())) {
                $this->datos['folio'] = ServiceDesk::getDatos($this->servicio->getFolio());
                $this->datos['notasFolio'] = ServiceDesk::getNotas($this->servicio->getFolio());
            }
        } catch (Exception $ex) {
            $this->datos['folio'] = array('Error' => $ex->getMessage());
            $this->datos['notasFolio'] = array('Error' => $ex->getMessage());
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
            $this->servicio->setFolioServiceDesk($datosServicio['folio']);
            $this->getInformacionFolio();
            $this->datos['operacion'] = TRUE;
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function runEvento(string $evento) {
        try {
            $datosServicio = $this->input->post();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->runAccion($evento, $datosServicio);
            $this->datos['solucion'] = $this->servicio->getSolucion();
            $this->datos['datosServicio'] = $this->gestorServicios->getInformacion($datosServicio['tipo'], array('datosServicio' => $this->servicio->getDatos()));
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['ERROR'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function setProblema() {
        try {

            $datosServicio = $this->input->post();
            $datosServicio['idUsuario'] = Usuario::getId();
            $carpeta = 'Servicios/Servicio-' . $datosServicio['id'] . '/EvidenciaProblemas/';
            Archivo::saveArchivos($carpeta);
            $datosServicio['archivos'] = Archivo::getString();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio->setProblema($datosServicio);
            $this->setNotaServiceDesk($datosServicio);
            $this->datos['problemas'] = $this->servicio->getProblemas();
            $this->getInformacionFolio();
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }

    public function setSolucion() {
        try {
            $datosServicio = $this->input->post();
            $datosServicio['archivos'] = '';
            $datosServicio['idUsuario'] = Usuario::getId();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);                        
            if ($datosServicio['material'] !== 'false') {
                $carpeta = 'Servicios/Servicio-' . $datosServicio['id'] . '/EvidenciaSolucion/';
                Archivo::saveArchivos($carpeta);
                $datosServicio['archivos'] = Archivo::getString();
            }            
            $this->servicio->setSolucion($datosServicio);
            $this->datos['solucion'] = $this->servicio->getSolucion();
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
            $firmas = array(
                'Firma-Cliente-'.$datosServicio['nombreCliente'] => $datosServicio['firmaCliente'],
                'Firma-Tecnico-'.Usuario::getNombre() => $datosServicio['firmaTecnico']
                    );
            Archivo::saveArchivos64($carpeta,$firmas);
            $datosServicio['archivos'] = Archivo::getString();
            $this->servicio = $this->factory->getServicio($datosServicio['tipo'], $datosServicio['id']);
            $this->servicio = $this->servicio->setConclusion($datosServicio);
            $this->setResolucionServiceDesk($datosServicio);
            echo json_encode($this->datos);
        } catch (Exception $ex) {
            $this->datos['operacion'] = FALSE;
            $this->datos['Error'] = $ex->getMessage();
            echo json_encode($this->datos);
        }
    }
    
    private function setResolucionServiceDesk(array $datosServicio) {
        $mensaje = 'Una nueva Resolución en service desk';
        
        ServiceDesk::setResolucion($datosServicio['folio'],$mensaje);
        ServiceDesk::setEstatus('Validacion',$datosServicio['folio']);
    }

}
