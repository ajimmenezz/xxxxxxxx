<?php

namespace Librerias\V2\PaquetesTicket\Utilerias;

use Modelos\Modelo_ServicioTicketV2 as ModeloServicioTicket;

class Solicitud {

    private $DBServicioTicket;
    private $idSolicitud;
    private $datosSolicitud;

    public function __construct(string $idSolicitud) {
        $this->DBServicioTicket = new ModeloServicioTicket();
        $this->idSolicitud = $idSolicitud;
    }

    public function setDatos() {
        $datosSolicitud = $this->DBServicioTicket->getDatosSolicitud($this->idSolicitud);
        $this->datosSolicitud['id'] = $datosSolicitud[0]['Id'];
        $this->datosSolicitud['idTipoSolicitud'] = $datosSolicitud[0]['IdTipoSolicitud'];
        $this->datosSolicitud['idEstatus'] = $datosSolicitud[0]['IdEstatus'];
        $this->datosSolicitud['ticket'] = $datosSolicitud[0]['Ticket'];
    }

    public function verificarServiciosParaConcluirSolicitudTicket() {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->setDatos();
        $serviciosConcluidos = $this->DBServicioTicket->getServiciosConcluidos($this->datosSolicitud['ticket']);

        if (empty($serviciosConcluidos)) {
            $this->DBServicioTicket->actualizarSolicitud(
                    array('IdEstatus' => '4', 'FechaConclusion' => $fecha), array('Id' => $this->idSolicitud));
            $datosAdist2 = array(
                'Estatus' => 'CONCLUIDO',
                'Flag' => '1',
                'F_Cierre' => '0',
                'Id_Orden' => $this->datosSolicitud['ticket']
            );
//            $this->DBServicioTicket->concluirTicketAdist2($datosAdist2);
        }
    }
    
    public function folioSolicitudes(array $datos){
        $folioSolicitudes = $this->DBServicioTicket->folioSolicitudes($datos);
        return $folioSolicitudes;
    }

}
