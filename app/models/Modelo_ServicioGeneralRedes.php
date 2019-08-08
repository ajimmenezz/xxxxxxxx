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

    public function getSucursal(string $idCliente) {
        $consulta = array();
        try {
            $consulta = $this->consulta('select                    
                                         id, 
                                         Nombre as text
                                      from 
                                         cat_v3_sucursales
                                        WHERE IdCliente = "' . $idCliente . '"');
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function setFolioServiceDesk(array $datosServicio) {
        $cosulta = array();
        try {
            $servicio = $this->getDatosServicio($datosServicio['idServicio']);
            $consulta = $this->actualizar('UPDATE t_solicitudes
                                            SET Folio = "' . $datosServicio['folio'] . '" 
                                            WHERE Id = "' . $servicio[0]['IdSolicitud'] . '"');
        } catch (Exception $exc) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }
    public function insertarMaterial($query) {
        var_dump($query);
        $consulta= array();
        try
        {
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
        $consulta=$this->borrar($delete);
    }
        
        

}
