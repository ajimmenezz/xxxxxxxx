<?php

namespace Librerias\V2\PaquetesTicket\Poliza;

use Librerias\V2\PaquetesTicket\Interfaces\Servicio as Servicio;
use Librerias\V2\PaquetesTicket\GestorServicios as GestorServicio;
use Modelos\Modelo_ServicioTicketV2 as ModeloServicioTicket;
use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenVirtual;
use Librerias\V2\PaquetesSucursales\SucursalAdist as Sucursal;
use Librerias\V2\PaquetesSucursales\Censo as Censo;
use Librerias\Generales\Correo as Correo;

class ServicioInstalaciones implements Servicio {

    private $id;
    private $idSucursal;
    private $idCliente;
    private $folioSolicitud;
    private $fechaCreacion;
    private $ticket;
    private $atiende;
    private $idAtiende;
    private $idSolicitud;
    private $descripcion;
    private $solicita;
    private $descripcionSolicitud;
    private $DBServicioTicket;
    private $correoAtiende;
    private $problemas;
    private $x;
    private $y;
    private $InformacionServicios;

    public function __construct(string $idServicio) {
        $this->id = $idServicio;
        $this->DBServicioTicket = new ModeloServicioTicket();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
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
        $this->idAtiende = $consulta[0]['IdAtiende'];
        $this->idSolicitud = $consulta[0]['IdSolicitud'];
        $this->descripcion = $consulta[0]['Descripcion'];
        $this->solicita = $consulta[0]['Solicita'];
        $this->descripcionSolicitud = $consulta[0]['DescripcionSolicitud'];
        $this->correoAtiende = $consulta[0]['CorreoAtiende'];
        $this->tipoServicio = $consulta[0]['TipoServicio'];
        $this->estatusServicio = $consulta[0]['EstatusServicio'];
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
            "idAtiende" => $this->idAtiende,
            "solicitud" => $this->idSolicitud,
            "servicio" => $this->idServicio,
            "descripcion" => $this->descripcion,
            "solicita" => $this->solicita,
            "sucursal" => $this->idSucursal,
            "fechaSolicitud" => $this->fechaSolicitud,
            "descripcionSolicitud" => $this->descripcionSolicitud,
            "tipoServicio" => $this->tipoServicio,
            "cliente" => $this->idCliente,
            "estatusServicio" => $this->estatusServicio,
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
        $this->DBServicioTicket->actualizarServicio(array('IdEstatus' => '3'), array('Id' => $this->id));
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
        $this->GestorServicio = new GestorServicio();

        switch ($evento) {
            case 'AgregarEquipo':
                $this->GestorServicio->setEquipo($datos);
                break;
            case 'EliminarEquipo':
                $this->GestorServicio->deleteEquipo($datos);
                break;
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

    public function setConcluir(array $datos) {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->DBServicioTicket->empezarTransaccion();
        $this->DBServicioTicket->actualizarServicio(array(
            'IdEstatus' => '5',
            'FechaConclusion' => $fecha,
            'Firma' => $datos['archivos'][0],
            'NombreFirma' => $datos['nombreCliente'],
            'FechaFirma' => $fecha
                ), array('Id' => $this->id));
        $this->DBServicioTicket->finalizarTransaccion();
        $archivo = '<p>******* Termino de servicio de instalaciones ********</p>
                    <p><strong>Descripción:</strong> Se concluye el servicio de instalación</p>';
        return $archivo;
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

    public function getPDF(array $datos) {
        $archivo = $this->InformacionServicios->definirPDF(array('servicio' => $this->id));
        return $archivo;
    }

    public function getFirmas(string $idServicio) {
        $consulta = $this->DBServicioTicket->getFirmas($idServicio);
        return $consulta[0]['firmas'];
    }

    public function setInstalacion(array $datos) {
        $this->DBServicioTicket->empezarTransaccion();
        $almacenVirtual = new AlmacenVirtual();
        $sucursal = new Sucursal($datos['datosServicio']['sucursal']);
        $censo = new Censo($sucursal);
        $ultimoServicioCenso = $sucursal->getServicioUltimoCensoSucursal();
        $datosInventario = $almacenVirtual->consultaInventario($datos['value']['IdModelo']);

        $censo->setCensoIdServicio(array(
            'servicio' => $ultimoServicioCenso[0]['IdServicio'],
            'idModelo' => $datosInventario[0]['IdProducto'],
            'idArea' => $datos['value']['IdArea'],
            'punto' => $datos['value']['Punto'],
            'serie' => $datos['value']['Serie']
        ));

        $idCatAlmacenVirtual = $almacenVirtual->getCatalogoAlmacenesVirtuales("WHERE IdReferenciaAlmacen = '" . $datos['datosServicio']['sucursal'] . "' AND IdTipoAlmacen = 2");
        $datosMovimientos = $this->setDatosMovimientos(array('IdAlmacen' => $idCatAlmacenVirtual[0]['Id'], 'datosInventario' => $datosInventario));

        $almacenVirtual->setMovimientoInventarioEntradaSalida(array(
            'idInventario' => $datos['value']['IdModelo'],
            'primerIdMovimiento' => 4,
            'segundoIdMovimiento' => 5,
            'datosMovimientos' => array($datosMovimientos)));
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function setRetiroEquipo(array $datos) {
        $this->DBServicioTicket->empezarTransaccion();
        $almacenVirtual = new AlmacenVirtual();
        $sucursal = new Sucursal($datos['datosServicio']['sucursal']);
        $censo = new Censo($sucursal);
        $ultimoServicioCenso = $sucursal->getServicioUltimoCensoSucursal();
        $idCatAlmacenVirtual = $almacenVirtual->getCatalogoAlmacenesVirtuales("WHERE IdReferenciaAlmacen = '" . $datos['datosServicio']['sucursal'] . "' AND IdTipoAlmacen = 2");
        $datosInventario = $almacenVirtual->consultaInventarioWhere("WHERE IdProducto = " . $datos['value']['IdModelo'] . " AND IdAlmacen = " . $idCatAlmacenVirtual[0]['Id']);

        if (empty($datosInventario)) {
            $idInventario = $almacenVirtual->insertarInventario(array(
                'IdAlmacen' => $idCatAlmacenVirtual[0]['Id'],
                'IdTipoProducto' => '1',
                'IdProducto' => $datos['value']['IdModelo'],
                'IdEstatus' => '17',
                'Cantidad' => 1,
                'Serie' => $datos['value']['Serie']
            ));
            $datosInventario = $almacenVirtual->consultaInventarioWhere("WHERE Id = " . $idInventario);
        } else {
            $idInventario = $datosInventario[0]['Id'];
        }

        $censo->deleteCenso(array(
            'idServicio' => $ultimoServicioCenso[0]['IdServicio'],
            'idModelo' => $datos['value']['IdModelo'],
            'idArea' => $datos['value']['IdArea'],
            'punto' => $datos['value']['Punto'],
            'serie' => $datos['value']['Serie']
        ));

        $datosInventarioUsuario = $almacenVirtual->getCatalogoAlmacenesVirtuales("WHERE IdReferenciaAlmacen = " . $datos['datosServicio']['idAtiende'] . " AND IdTipoAlmacen = 1");
        $datosMovimientos = $this->setDatosMovimientos(array('IdAlmacen' => $datosInventarioUsuario[0]['Id'], 'datosInventario' => $datosInventario));

        $almacenVirtual->setMovimientoInventarioEntradaSalida(array(
            'idInventario' => $idInventario,
            'primerIdMovimiento' => 4,
            'segundoIdMovimiento' => 5,
            'datosMovimientos' => array($datosMovimientos)));
        $this->DBServicioTicket->finalizarTransaccion();
    }

    public function setDatosMovimientos(array $datos) {
        $datosMovimientos = array(
            'IdAlmacen' => (int) $datos['IdAlmacen'],
            'IdTipoProducto' => $datos['datosInventario'][0]['IdTipoProducto'],
            'IdProducto' => $datos['datosInventario'][0]['IdProducto'],
            'IdEstatus' => $datos['datosInventario'][0]['IdEstatus'],
            'Cantidad' => $datos['datosInventario'][0]['Cantidad'],
            'Serie' => $datos['datosInventario'][0]['Serie']
        );

        return $datosMovimientos;
    }

}
