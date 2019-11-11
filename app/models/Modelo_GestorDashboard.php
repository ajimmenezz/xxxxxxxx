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

    public function getDatosVGCWEEK(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('SEMANA' , ' ', Semana) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    from v_base_dashboard_sd
                                    WHERE
                                         YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Semana between WEEK(NOW(), 1) - (" . $datos['numeroTiempo'] . ") and WEEK(NOW(),1)
                                            " . $datos['where'] . " 
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGCMONTH(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('MES', ' ', Mes) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    from v_base_dashboard_sd
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                           and Mes between MONTH(NOW()) - (" . $datos['numeroTiempo'] . ") and MONTH(NOW())
                                                " . $datos['where'] . "
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGCYEAR(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('AÑO', ' ', Anio) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    from v_base_dashboard_sd
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE()) - " . $datos['numeroTiempo'] . "
                                            " . $datos['where'] . " 
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGTWEEK(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        CONCAT('SEMANA', ' ', Semana) AS Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        v_base_dashboard_sd
                                    WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Semana between WEEK(NOW(), 1) - (" . $datos['numeroTiempo'] . ") and WEEK(NOW(),1) 
                                            " . $datos['where'] . " 
                                    GROUP BY Tiempo;");
        return $consulta;
    }

    public function getDatosVGTMONTH(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        CONCAT('MES', ' ', Mes) AS Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        v_base_dashboard_sd
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                                and Mes between MONTH(NOW()) - (" . $datos['numeroTiempo'] . ") and MONTH(NOW())
                                                " . $datos['where'] . "
                                    GROUP BY Tiempo");
        return $consulta;
    }

    public function getDatosVGTYEAR(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        CONCAT('AÑO', ' ', Anio) AS Tiempo, COUNT(*) AS Incidentes
                                    FROM
                                        v_base_dashboard_sd
                                        WHERE
                                            Anio between YEAR(NOW()) - (" . $datos['numeroTiempo'] . ") and YEAR(NOW())
                                            " . $datos['where'] . " 
                                    GROUP BY Tiempo");
        return $consulta;
    }

    public function getDatosVGHIWEEK(array $datos) {
        $consulta = $this->consulta("select
                                    Semana,
                                    count(*) as Total
                                    from (
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Semana = WEEK(now(),1)
                                    and Anio = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    union
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Semana <> WEEK(now(),1)
                                    and WEEK(ResolvedTime,1) = WEEK(now(),1)
                                    and YEAR(ResolvedTime) = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    ) as tf  WHERE Semana != WEEK(now(),1) group by Anio, Semana");
        return $consulta;
    }
    public function getDatosVGHIMONTH(array $datos) {
        $consulta = $this->consulta("select
                                    Mes,
                                    count(*) as Total
                                    from (
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Mes = MONTH(NOW())
                                    and Anio = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    union
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Mes <> MONTH(NOW())
                                    and MONTH(ResolvedTime) = MONTH(NOW())
                                    and YEAR(ResolvedTime) = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    ) as tf  WHERE Mes != MONTH(NOW()) group by Anio, Mes");
        return $consulta;
    }
    public function getDatosVGHIYEAR(array $datos) {
        $consulta = $this->consulta("select
                                    Anio,
                                    count(*) as Total
                                    from (
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Anio = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    union
                                    select
                                    *
                                    from v_base_dashboard_sd
                                    where Anio <> YEAR(NOW())
                                    and YEAR(ResolvedTime) = YEAR(now())
                                    " . $datos['where'] . "
                                    and Estatus = 'Cerrado'
                                    ) as tf  WHERE Anio != MONTH(NOW()) group by Anio");
        return $consulta;
    }

    public function getDatosVGIPWEEK(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('SEMANA' , ' ', Semana) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                        and Semana between WEEK(NOW(), 1) - (" . $datos['numeroTiempo'] . ") and WEEK(NOW(),1) 
                                    group by Tiempo");
        return $consulta;
    }
    public function getDatosVGIPMONTH(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('MES', ' ', Mes) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Mes between MONTH(NOW()) - (" . $datos['numeroTiempo'] . ") and MONTH(NOW())
                                    group by Tiempo");
        return $consulta;
    }
    public function getDatosVGIPYEAR(array $datos) {
        $consulta = $this->consulta("select
                                    CONCAT('AÑO', ' ', Anio) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        Anio between YEAR(NOW()) - (" . $datos['numeroTiempo'] . ") and YEAR(NOW())
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGZWEEK(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        IF(Region IS NULL,  'SIN ZONA', Region) Region,
                                        SUM(IF(Estatus = 'Abierto', 1, 0)) AS Abierto,
                                        SUM(IF(Estatus = 'En Atencion', 1, 0)) AS 'En Atencion',
                                        SUM(IF(Estatus = 'Problema', 1, 0)) AS Problema,
                                        SUM(IF(Estatus = 'Cerrado', 1, 0)) AS Cerrado
                                    FROM
                                            v_base_dashboard_sd
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Semana between WEEK(NOW(), 1) - (" . $datos['numeroTiempo'] . ") and WEEK(NOW(),1)
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        return $consulta;
    }
    public function getDatosVGZMONTH(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        IF(Region IS NULL,  'SIN ZONA', Region) Region,
                                        SUM(IF(Estatus = 'Abierto', 1, 0)) AS Abierto,
                                        SUM(IF(Estatus = 'En Atencion', 1, 0)) AS 'En Atencion',
                                        SUM(IF(Estatus = 'Problema', 1, 0)) AS Problema,
                                        SUM(IF(Estatus = 'Cerrado', 1, 0)) AS Cerrado
                                    FROM
                                            v_base_dashboard_sd
                                        WHERE
                                            YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Mes between MONTH(NOW()) - (" . $datos['numeroTiempo'] . ") and MONTH(NOW())
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        return $consulta;
    }
    public function getDatosVGZYEAR(array $datos) {
        $consulta = $this->consulta("SELECT 
                                        IF(Region IS NULL,  'SIN ZONA', Region) Region,
                                        SUM(IF(Estatus = 'Abierto', 1, 0)) AS Abierto,
                                        SUM(IF(Estatus = 'En Atencion', 1, 0)) AS 'En Atencion',
                                        SUM(IF(Estatus = 'Problema', 1, 0)) AS Problema,
                                        SUM(IF(Estatus = 'Cerrado', 1, 0)) AS Cerrado
                                    FROM
                                            v_base_dashboard_sd
                                        WHERE
                                            Anio between YEAR(NOW()) - (" . $datos['numeroTiempo'] . ") and YEAR(NOW())
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        return $consulta;
    }

    public function getDatosVGTO(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

    public function getDatosTiposServicios() {
        $consulta = $this->consulta("SELECT
                                        Id AS id, Nombre AS text
                                    FROM cat_v3_servicios_departamento 
                                    WHERE IdDepartamento = '11' 
                                    AND Flag = '1'
                                    ORDER BY Nombre ASC");
        return $consulta;
    }

}
