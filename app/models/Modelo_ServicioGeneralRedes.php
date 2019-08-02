<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioGeneralRedes extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatosServicio(string $idServicio) {
        $consulta = array();
        try{            
            $consulta = $this->consulta('
                                        select 
                                            serviciosTicket.FechaCreacion,
                                            serviciosTicket.Ticket,
                                            usuario(serviciosTicket.Atiende) as atentidoPor,
                                            serviciosTicket.IdSolicitud,
                                            serviciosTicket.Descripcion,
                                            usuario(serviciosTicket.Solicita) as solicitadoPor,
                                            solicitudes.FechaCreacion as fechaSolicitud,
                                            solicitudesInternas.Descripcion as descripcionSolicitud,
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
                                                serviciosTicket.Id=' . $idServicio);
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }
    public function getSucursal()
    {
        $consulta=array();
        try
        {
           $consulta=$this->consulta('select                    
                                         id, 
                                         Nombre 
                                      from 
                                         cat_v3_sucursales;' ); 
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
        
    }

    public function setFolioServiceDesk(string $idServicio) {
        
    }

}
