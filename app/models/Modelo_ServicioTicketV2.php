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
                                            (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = serviciosTicket.Atiende) CorreoAtiende,
                                            serviciosTicket.IdEstatus AS EstatusServicio
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

    public function getAvanceProblema(string $servicio) {
        $consulta = $this->consulta('SELECT tsa.*,
                                                (SELECT Nombre FROM cat_v3_tipos_avance WHERE Id = tsa.IdTipo) AS TipoAvance,
                                                (SELECT UrlFoto FROM t_rh_personal WHERE Id = tsa.IdUsuario) AS Foto,
                                                nombreUsuario(IdUSuario) AS Usuario
                                                FROM t_servicios_avance tsa
                                                WHERE IdServicio = "' . $servicio . '"
                                                AND Flag = "1"
                                                ORDER BY Fecha ASC');
        return $consulta;
    }

    public function setProblema(string $idServicio, array $datos) {
        $this->insertar('insert into t_servicios_avance values (
                            null,
                            ' . $idServicio . ',
                            ' . $datos['idUsuario'] . ',
                            2,
                            now(),
                            "' . $datos['descripcion'] . '",
                            "' . $datos['archivos'] . '",
                            1
                        )');
    }

    public function actualizarServiciosAvance(array $campos, array $where) {
        $this->actualizarArray('t_servicios_avance', $campos, $where);
    }

    public function getAvanceProblemaPorId(string $idAvanceProblema) {
        $consulta = $this->consulta('SELECT tsa.*
                                                FROM t_servicios_avance tsa
                                                WHERE tsa.Id = "' . $idAvanceProblema . '"
                                                AND Flag = "1"
                                                ORDER BY Fecha ASC');
        return $consulta;
    }

    public function getFirmas(string $idServicio) {
        return $this->consulta('select concat(Firma,",", FirmaTecnico) as firmas from t_servicios_ticket where Id=' . $idServicio);
    }

}
