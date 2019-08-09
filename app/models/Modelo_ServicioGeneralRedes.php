<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioGeneralRedes extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatosServicio(string $idServicio) {
        $consulta = array();
        try {
            $consulta = $this->consulta('select 
                                            serviciosTicket.FechaCreacion,
                                            serviciosTicket.Ticket,
                                            usuario(serviciosTicket.Atiende) as Atiende,
                                            serviciosTicket.IdSolicitud,
                                            serviciosTicket.Descripcion,
                                            serviciosTicket.IdSucursal,
                                            (select idCliente from cat_v3_sucursales where Id = serviciosTicket.IdSucursal) as IdCliente,
                                            usuario(serviciosTicket.Solicita) as Solicita,
                                            solicitudes.FechaCreacion as FechaSolicitud,
                                            solicitudesInternas.Descripcion as DescripcionSolicitud,
                                            solicitudes.Folio
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
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function setEstatus(string $idServicio, string $estatus) {
        $this->actualizar('update t_servicios_ticket 
                            set IdEstatus = ' . $estatus . '                            
                            where Id = ' . $idServicio);
    }

    public function getDatosSolucion(string $idServicio) {
        $consulta = $this->consulta('select 
                                        Id,
                                        Descripcion as Observaciones,
                                        Archivos,
                                        Fecha as FechaUltimoMovimiento   
                                    from t_servicios_generales tsg
                                    where tsg.IdServicio = ' . $idServicio);
        return $consulta;
    }

    public function setFolioServiceDesk(string $idSolicitud, string $idFolio) {
        $this->actualizar('UPDATE t_solicitudes
                                            SET Folio = "' . $idFolio . '" 
                                            WHERE Id = "' . $idSolicitud . '"');
    }

    public function insertarMaterial($query) {
        var_dump($query);
        $consulta = array();
        try {
            foreach ($array as $key => $query) {
                $consulta = $this->insertar($query);
                var_dump($consulta);
            }
        } catch (Exception $ex) {
            $ex->getMessage();
        }
        return $consulta;
    }

    public function eliminarNodo($delete) {
        $consulta = $this->borrar($delete);
    }

}
