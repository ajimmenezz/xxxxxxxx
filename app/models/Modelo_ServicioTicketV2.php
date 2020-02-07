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

    public function folioSolicitudes(array $datos) {
        $consulta = $this->consulta('SELECT Id FROM t_solicitudes WHERE Folio = "' . $datos['folio'] . '"');
        return $consulta;
    }

    public function getDatosServicio(string $idServicio) {
        $consulta = $this->consulta('select 
                                            serviciosTicket.Id AS IdServicio,
                                            serviciosTicket.FechaCreacion,
                                            serviciosTicket.FechaInicio,
                                            serviciosTicket.Ticket,
                                            nombreUsuario(serviciosTicket.Atiende) as Atiende,
                                            serviciosTicket.IdSolicitud,
                                            serviciosTicket.Descripcion,
                                            serviciosTicket.IdSucursal,
                                            (select idCliente from cat_v3_sucursales where Id = serviciosTicket.IdSucursal) as IdCliente,
                                            usuario(serviciosTicket.Solicita) as Solicita,
                                            solicitudes.FechaCreacion as FechaSolicitud,
                                            solicitudesInternas.Descripcion as DescripcionSolicitud,
                                            solicitudes.Folio,
                                            tipoServicio(serviciosTicket.IdTipoServicio) AS TipoServicio,
                                            (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = serviciosTicket.Atiende) CorreoAtiende
                                        from 
                                                t_servicios_ticket serviciosTicket
                                        join 
                                                t_solicitudes solicitudes
                                        on
                                                serviciosTicket.IdSolicitud=solicitudes.Id
                                        join
                                                t_solicitudes_internas solicitudesInternas
                                        on
                                                solicitudes.Id=solicitudesInternas.IdSolicitud
                                        where
                                                serviciosTicket.Id = ' . $idServicio);
        return $consulta;
    }

    public function actualizarServicio(array $campos, array $where) {
        $consulta = $this->actualizarArray('t_servicios_ticket', $campos, $where);
    }
}
