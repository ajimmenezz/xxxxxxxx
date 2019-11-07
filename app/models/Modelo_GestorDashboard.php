<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_GestorDashboard extends Base {

    public function getVistasDashboards(string $claves) {
        $consulta = $this->consulta("SELECT 
                                        VistaHtml
                                    FROM
                                        t_permisos_dashboard
                                    WHERE ClavePermiso IN ('" . $claves . "')");
        return $consulta;
    }

    public function getClavesPermisos(string $permisos) {
        $consulta = $this->consulta('SELECT Permiso FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getIdPermisos(string $permisos) {
        $consulta = $this->consulta('SELECT Id FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getPermisosDashboard(string $permisos) {
        $consulta = $this->consulta('SELECT 
                                        tpd.ClavePermiso
                                    FROM
                                        t_permisos_dashboard tpd
                                    INNER JOIN cat_v3_permisos cvp
                                    ON cvp.Permiso = tpd.ClavePermiso 
                                    WHERE tpd.Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getDatosVGC(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        CONCAT('SEMANA', ' ', WEEK(ts.FechaCreacion, 1)) AS Tiempo,
                                        ESTATUS(ts.IdEstatus) AS EstatusTicketAdIST,
                                        COUNT(DISTINCT ts.Folio) AS SumaEstatus
                                    FROM
                                        t_solicitudes ts
                                    WHERE
                                        YEAR(ts.FechaCreacion) = YEAR(CURRENT_DATE())
                                            AND WEEK(ts.FechaCreacion, 1) = WEEK(CURRENT_DATE(), 1)- " . $datos['numeroTiempo'] . "
                                            AND ts.Folio IS NOT NULL
                                            AND ts.Folio != '0'
                                            AND ts.IdEstatus != '6'
                                            AND ts.IdEstatus IN (1 , 2, 3, 4)
                                    GROUP BY Tiempo, EstatusTicketAdIST");
        return $consulta;
    }

    public function getDatosVGTSemana(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        (SELECT 
                                            Folio, CONCAT('SEMANA', ' ', WEEK(CreatedTime, 1)) AS Tiempo
                                        FROM
                                            t_solicitudes
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                                AND WEEK(CreatedTime, 1) = WEEK(CURRENT_DATE(), 1) - " . $datos['numeroTiempo'] . "
                                                AND Technician IS NOT NULL
                                                AND Technician <> ''
                                        GROUP BY Folio) AS tf
                                    GROUP BY Tiempo");
        return $consulta;
    }
    
    public function getDatosVGTMes(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        (SELECT 
                                            Folio,
                                            CONCAT('Mes', ' ', MONTH(CreatedTime)) AS Tiempo
                                        FROM
                                            t_solicitudes
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                                AND MONTH(CreatedTime) = MONTH(CURRENT_DATE()) - " . $datos['numeroTiempo'] . "
                                                AND Technician IS NOT NULL
                                                AND Technician <> ''
                                        GROUP BY Folio) AS tf
                                    GROUP BY Tiempo");
        return $consulta;
    }
    
    public function getDatosVGTAnual(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        (SELECT 
                                            Folio,
                                            CONCAT('AÑO', ' ', YEAR(CreatedTime)) AS Tiempo
                                        FROM
                                            t_solicitudes
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE()) - " . $datos['numeroTiempo'] . "
                                                AND Technician IS NOT NULL
                                                AND Technician <> ''
                                        GROUP BY Folio) AS tf
                                    GROUP BY Tiempo");
        return $consulta;
    }

    public function getDatosVGHI(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

    public function getDatosVGIP(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

    public function getDatosVGZ(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

    public function getDatosVGTO(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

}
