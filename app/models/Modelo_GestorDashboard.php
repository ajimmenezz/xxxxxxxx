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
        $consulta = $this->consulta("select
                                    Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    from (
                                    select
                                    Folio,
                                    if(`Status` = 'Completado', 'Cerrado', `Status`) as Estatus,
                                    CONCAT('SEMANA', ' ', WEEK(CreatedTime, 1)) AS Tiempo
                                    from t_solicitudes
                                    WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            AND WEEK(CreatedTime, 1) = WEEK(CURRENT_DATE(), 1) - " . $datos['numeroTiempo'] . "
                                    and Technician is not null
                                    and Technician <> ''
                                    group by Folio
                                    ) as tf group by Tiempo");
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
        $consulta = $this->consulta("select
                                    Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    from (
                                    select
                                    Folio,
                                    if(`Status` = 'Completado', 'Cerrado', `Status`) as Estatus,
                                    CONCAT('SEMANA', ' ', WEEK(CreatedTime, 1)) AS Tiempo
                                    from t_solicitudes
                                    WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            AND WEEK(CreatedTime, 1) = WEEK(CURRENT_DATE(), 1) - " . $datos['numeroTiempo'] . "
                                    and Technician is not null
                                    and Technician <> ''
                                    group by Folio
                                    ) as tf group by Tiempo");
        return $consulta;
    }

    public function getDatosVGZ(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        IF(Region IS NULL,  'SIN ZONA', Region) Region,
                                        SUM(IF(Estatus = 'Abierto', 1, 0)) AS Abierto,
                                        SUM(IF(Estatus = 'En Atencion', 1, 0)) AS 'En Atencion',
                                        SUM(IF(Estatus = 'Problema', 1, 0)) AS Problema,
                                        SUM(IF(Estatus = 'Cerrado', 1, 0)) AS Cerrado
                                    FROM
                                        (SELECT 
                                            ts.Folio,
                                                IF(ts.IdSucursal IS NULL
                                                    OR ts.IdSucursal <= 0, REGIONBYSUCURSAL((SELECT 
                                                        IdSucursal
                                                    FROM
                                                        t_servicios_ticket
                                                    WHERE
                                                        IdSolicitud = ts.Id AND IdSucursal > 0
                                                    LIMIT 1)), REGIONBYSUCURSAL(ts.IdSucursal)) AS Region,
                                                IF(`Status` = 'Completado', 'Cerrado', `Status`) AS Estatus,
                                                WEEK(CreatedTime, 1) AS Semana
                                        FROM
                                            t_solicitudes ts
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                                AND WEEK(CreatedTime, 1) = WEEK(CURRENT_DATE(), 1) - " . $datos['numeroTiempo'] . "
                                                AND ts.Technician IS NOT NULL
                                                AND ts.Technician <> ''
                                        GROUP BY ts.Folio) AS tf
                                    GROUP BY Region
                                    ORDER BY Region");
        return $consulta;
    }

    public function getDatosVGTO(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

}
