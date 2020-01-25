<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioCableado extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDatosServicio(string $idServicio) {
        $consulta = $this->consulta('select 
                                            serviciosTicket.FechaCreacion,
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

    public function setEstatus(string $idServicio, string $estatus) {
        $this->actualizar('update t_servicios_ticket 
                            set IdEstatus = ' . $estatus . '                            
                            where Id = ' . $idServicio);
    }

    public function setFechaAtencion(string $idServicio, string $atiende) {
        $this->actualizar('update t_servicios_ticket 
                            set FechaInicio = NOW(),                            
                            Atiende = ' . $atiende . '                            
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

    public function setFolioServiceDesk(string $idSolicitud, string $idFolio = '') {
        $exiteFolio = $this->consulta('SELECT * from t_solicitudes where Folio = "' . $idSolicitud . '"');
        $this->actualizar('UPDATE t_solicitudes
                                            SET Folio = "' . $idFolio . '" 
                                            WHERE Id = "' . $idSolicitud . '"');
    }

    public function setSucursal(string $idServicio, string $idSucursal) {
        $this->actualizar('update t_servicios_ticket set 
                                    IdSucursal = ' . $idSucursal . ' 
                                where Ticket = (select tst.Ticket from (select * from t_servicios_ticket) as tst where tst.Id = ' . $idServicio . ') 
                                    and IdTipoServicio = 49');
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

    public function getProblemas(string $idServicio) {
        return $this->consulta('select 
                                    ctu.Nombre as Usuario,
                                    tsa.Fecha,
                                    tsa.Descripcion,
                                    tsa.Archivos
                                from t_servicios_avance tsa
                                inner join cat_v3_usuarios ctu
                                on tsa.IdUsuario = ctu.Id
                                where IdServicio = ' . $idServicio);
    }

    public function setServicio(string $idServicio, array $datos) {
        $this->insertarArray('t_servicios_generales', array('IdUsuario' => $datos['idUsuario'],
            'IdServicio' => $idServicio,
            'Descripcion' => $datos['observaciones'],
            'Archivos' => $datos['archivos'],
            'Fecha' => $datos['fecha']));
    }

    public function updateServicio(string $idServicio, array $datos) {
        $this->actualizar('update t_servicios_generales set 
                           Descripcion = "' . $datos['observaciones'] . '",
                           Archivos = "' . $datos['archivos'] . '",
                           Fecha = now()
                            where IdServicio = ' . $idServicio);
    }

    public function setConclusion(string $idServicio, array $datos) {
        if ($datos['nombreCliente'] !== '') {
            $this->actualizar('update t_servicios_ticket set 
                           IdEstatus = 5,
                           FechaConclusion = NOW(),
                           Firma = "' . $datos['archivos'][0] . '",
                           NombreFirma = "' . $datos['nombreCliente'] . '",
                           FechaFirma = NOW()                           
                           where Id = ' . $idServicio);
        } else {
            $this->actualizar('update t_servicios_ticket set 
                           IdEstatus = 5,
                           FechaConclusion = NOW(),
                           FechaFirma = NOW()
                           where Id = ' . $idServicio);
        }
    }

    public function getEvidencias(string $idServicio) {
        return $this->consulta('select Archivos from t_servicios_generales where IdServicio = ' . $idServicio);
    }

    public function deleteEvidencias(string $idServicio) {
        $this->actualizar('update t_servicios_generales set 
                                   Archivos = "" where IdServicio = ' . $idServicio);
    }

    public function getFirmas(string $idServicio) {
        return $this->consulta('select concat(Firma,",", FirmaTecnico) as firmas from t_servicios_ticket where Id=' . $idServicio);
    }

    public function getDatosSolucionPDF(array $datosServicio) {
        $datos['infoGeneral'] = $this->consulta('SELECT  
                                    nombreUsuario(tst.Solicita) AS Cliente,  
                                    cs.Nombre AS Sucursal,  
                                    csd.Nombre AS TipoServicio,  
                                    ce.Nombre AS Estatus,  
                                    nombreUsuario(tst.Atiende) AS Atiende  
                                FROM t_servicios_ticket AS tst 
                                INNER JOIN cat_v3_sucursales AS cs ON tst.IdSucursal = cs.Id 
                                INNER JOIN cat_v3_servicios_departamento AS csd ON tst.IdTipoServicio = csd.Id 
                                INNER JOIN cat_v3_estatus AS ce ON tst.IdEstatus = ce.Id 
                                WHERE tst.Id =' . $datosServicio['id']);

        $datos['infoNodos'] = $this->consulta('SELECT  
                                                    caa.Nombre AS Area,  
                                                    trn.Nombre AS Nodo,  
                                                    cme.Nombre AS Switch,  
                                                    trn.NumeroSwitch,
                                                    trn.Archivos AS Evidencias
                                                FROM t_servicios_ticket AS tst 
                                                INNER JOIN t_redes_nodos AS trn ON tst.Id = trn.IdServicio 
                                                INNER JOIN cat_v3_areas_atencion AS caa ON trn.IdArea = caa.Id 
                                                INNER JOIN cat_v3_modelos_equipo AS cme ON trn.IdSwitch = cme.Id 
                                                WHERE tst.Id =' . $datosServicio['id']);

        $datos['infoFirmas'] = $this->consulta('SELECT  
                                                    tst.Firma,  
                                                    tst.NombreFirma,  
                                                    tst.FirmaTecnico,  
                                                    nombreUsuario(tst.Atiende) AS Atiende 
                                                FROM t_servicios_ticket AS tst 
                                                WHERE tst.Id =' . $datosServicio['id']);
        return $datos;
    }

}
