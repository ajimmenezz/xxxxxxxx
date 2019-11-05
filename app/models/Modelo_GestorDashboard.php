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
                                        CONCAT('SEMANA', ' ', WEEK(ts.FechaCreacion, 1)) AS Semana,
                                        ESTATUS(ts.IdEstatus) AS EstatusTicketAdIST,
                                        COUNT(ts.IdEstatus) AS SumaEstatus
                                    FROM
                                        t_servicios_ticket tst
                                            RIGHT JOIN
                                        t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                    WHERE
                                        WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - " . $datos['numeroSemana'] . ")
                                            AND ts.Folio IS NOT NULL
                                            AND ts.Folio != '0'
                                            AND ts.IdEstatus IN (1 , 2, 3, 4)
                                    GROUP BY EstatusTicketAdIST");
        return $consulta;
    }
    
    public function getDatosVGT(array $datos) {
        $consulta = $this->consulta("(SELECT 
                                            COUNT(tst.Id) AS Incidentes,
                                            CONCAT('SEMANA',  ' ', WEEK(ts.FechaCreacion, 1)) AS Semana
                                        FROM
                                            t_servicios_ticket tst
                                                RIGHT JOIN
                                            t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        WHERE
                                            WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - 4)
                                                AND ts.Folio IS NOT NULL
                                                AND ts.Folio != '0'
                                        GROUP BY Semana) UNION (SELECT 
                                            COUNT(tst.Id) AS Incidentes,
                                            CONCAT('SEMANA',  ' ', WEEK(ts.FechaCreacion, 1)) AS Semana
                                        FROM
                                            t_servicios_ticket tst
                                                RIGHT JOIN
                                            t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        WHERE
                                            WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - 3)
                                                AND ts.Folio IS NOT NULL
                                                AND ts.Folio != '0'
                                        GROUP BY Semana) UNION (SELECT 
                                            COUNT(tst.Id) AS Incidentes,
                                            CONCAT('SEMANA',  ' ', WEEK(ts.FechaCreacion, 1)) AS Semana
                                        FROM
                                            t_servicios_ticket tst
                                                RIGHT JOIN
                                            t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        WHERE
                                            WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - 2)
                                                AND ts.Folio IS NOT NULL
                                                AND ts.Folio != '0'
                                        GROUP BY Semana) UNION (SELECT 
                                            COUNT(tst.Id) AS Incidentes,
                                            CONCAT('SEMANA',  ' ', WEEK(ts.FechaCreacion, 1)) AS Semana
                                        FROM
                                            t_servicios_ticket tst
                                                RIGHT JOIN
                                            t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        WHERE
                                            WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - 1)
                                                AND ts.Folio IS NOT NULL
                                                AND ts.Folio != '0'
                                        GROUP BY Semana) UNION (SELECT 
                                            COUNT(tst.Id) AS Incidentes,
                                            CONCAT('SEMANA',  ' ', WEEK(ts.FechaCreacion, 1)) AS Semana
                                        FROM
                                            t_servicios_ticket tst
                                                RIGHT JOIN
                                            t_solicitudes ts ON tst.IdSolicitud = ts.Id
                                        WHERE
                                            WEEKOFYEAR(ts.FechaCreacion) = (WEEKOFYEAR(CURDATE()) - 0)
                                                AND ts.Folio IS NOT NULL
                                                AND ts.Folio != '0'
                                        GROUP BY Semana)");
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
