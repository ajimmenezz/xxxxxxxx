<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioTicketV2 extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatosSolicitud(string $idSolicitud) {
        $consulta = $this->consulta("SELECT * FROM t_solicitudes WHERE Id = '" . $idSolicitud . "'");
        return $consulta;
    }

    public function getServiciosConcluidos(string $ticket) {
        $consulta = $this->consulta('SELECT Id FROM t_servicios_ticket WHERE Ticket = "' . $ticket . '" AND IdEstatus in(10,5,2,1)');
        return $consulta;
    }

    public function actualizarSolicitud(array $campos, array $where) {
        $consulta = $this->actualizarArray('t_solicitudes', $campos, $where);
    }

    public function concluirTicketAdist2(array $datos) {
        $query = "UPDATE t_servicios "
                . "SET Estatus='" . $datos['Estatus'] . "', "
                . "Flag='" . $datos['Flag'] . "', "
                . "F_Cierre='" . $datos['F_Cierre'] . "' "
                . "WHERE Id_Orden = '" . $datos['Id_Orden'] . "'";
        $host = $_SERVER['SERVER_NAME'];
        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            return parent::connectDBAdist2()->insert_id();
        } else {
            $modeloBase = new Modelo_Base('adist3');
            $this->$modeloBase->actualizar($query);
        }
    }

}
