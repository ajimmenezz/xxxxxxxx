<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_GestorDashboard extends Base
{

    public function getVistasDashboards(string $claves)
    {
        $consulta = $this->consulta("SELECT 
                                        VistaHtml
                                    FROM
                                        t_permisos_dashboard
                                    WHERE ClavePermiso IN ('" . $claves . "')");
        return $consulta;
    }

    public function getClavesPermisos(string $permisos)
    {
        $consulta = $this->consulta('SELECT Permiso FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getIdPermisos(string $permisos)
    {
        $consulta = $this->consulta('SELECT Id FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getPermisosDashboard(string $permisos)
    {
        $consulta = $this->consulta('SELECT 
                                        tpd.ClavePermiso
                                    FROM
                                        t_permisos_dashboard tpd
                                    INNER JOIN cat_v3_permisos cvp
                                    ON cvp.Permiso = tpd.ClavePermiso 
                                    WHERE tpd.Id IN(' . $permisos . ')');
        return $consulta;
    }

    public function getDatosVGC(array $datos)
    {        
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        if (isset($datos['tipoServicio']) && $datos['tipoServicio'] !== '') {
            $conditions .= " and base.Tipo = '" . $datos['tipoServicio'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes between ('" . $datos['month'] . "' - 4) and '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes between (MONTH(now()) - " . $datos['numeroTiempo'] . ") and MONTH(now()) ";
                }
                $field = " cap_first(MONTHNAME(CreatedTime)) ";
                $order = " order by Anio, Mes ";

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana between ('" . $datos['week'] . "' - 4) and '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana between (WEEK(now(),1) - " . $datos['numeroTiempo'] . ") and WEEK(now(),1)	";
                }
                $field = " CONCAT('SEMANA', ' ', Semana) ";
                $order = " order by Anio, Semana";

                break;
        }

        $this->query("SET lc_time_names = 'es_ES'");

        $consulta = $this->consulta("SELECT 
                                    " . $field . " as Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema,
                                    SUM(if(Estatus = 'Cerrado',1,0)) as Cerrado
                                    FROM
                                    v_base_dashboard_sd base
                                    " . $conditions . "
                                    GROUP BY Tiempo " . $order);
        return $consulta;
    }

    public function getDatosVGT(array $datos)
    {

        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes between ('" . $datos['month'] . "' - 4) and '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes between (MONTH(now()) - " . $datos['numeroTiempo'] . ") and MONTH(now()) ";
                }
                $field = " cap_first(MONTHNAME(CreatedTime)) ";
                $order = " order by Anio, Mes ";

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana between ('" . $datos['week'] . "' - 4) and '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana between (WEEK(now(),1) - " . $datos['numeroTiempo'] . ") and WEEK(now(),1)	";
                }
                $field = " CONCAT('SEMANA', ' ', Semana) ";
                $order = " order by Anio, Semana";

                break;
        }

        $this->query("SET lc_time_names = 'es_ES'");

        $consulta = $this->consulta("SELECT 
                                    " . $field . " as Tiempo,
                                    COUNT(*) AS Incidentes
                                    FROM
                                    v_base_dashboard_sd base
                                    " . $conditions . "
                                    GROUP BY Tiempo " . $order);
        return $consulta;
    }

    public function getDatosVGHIWEEK(array $datos)
    {
        $conditions = ' where 1 = 1 ';

        if (isset($datos['week']) && $datos['week'] > 0) {
            $conditions .= " AND WEEK(ResolvedTime,1) = '" . $datos['week'] . "' ";
        } else {
            $conditions .= " AND WEEK(ResolvedTime,1) = WEEK(now(),1)";
        }

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " AND YEAR(ResolvedTime) = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " AND YEAR(ResolvedTime) = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " AND Region = '" . $datos['zona'] . "' ";
        }

        $consulta = $this->consulta("select 
                                    Anio as year,
                                    Semana as concept,
                                    count(*) as total
                                    from v_base_dashboard_sd
                                    " . $conditions . "
                                    group by Anio, Semana
                                    order by Anio, Semana");
        return $consulta;
    }

    public function getDatosVGHIMONTH(array $datos)
    {
        $conditions = ' where 1 = 1 ';

        if (isset($datos['month']) && $datos['month'] > 0) {
            $conditions .= " AND MONTH(ResolvedTime) = '" . $datos['month'] . "' ";
        } else {
            $conditions .= " AND MONTH(ResolvedTime) = MONTH(now()) ";
        }

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " AND YEAR(ResolvedTime) = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " AND YEAR(ResolvedTime) = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " AND Region = '" . $datos['zona'] . "' ";
        }

        $this->query("SET lc_time_names = 'es_ES'");
        $consulta = $this->consulta("
                                    select 
                                    Anio as year,
                                    cap_first(MONTHNAME(CreatedTime)) as concept,
                                    count(*) as total
                                    from v_base_dashboard_sd
                                    " . $conditions . "
                                    group by Anio, Mes
                                    order by Anio, Mes");
        return $consulta;
    }

    public function getDatosVGIPWEEK(array $datos)
    {
        $consulta = $this->consulta("select
                                    CONCAT('SEMANA' , ' ', Semana) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                        and Semana between WEEK(NOW(), 1) - (" . $datos['numeroTiempo'] . ") and WEEK(NOW(),1)
                                        " . $datos['where'] . " 
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGIPMONTH(array $datos)
    {
        $consulta = $this->consulta("select
                                    CONCAT('MES', ' ', Mes) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        YEAR(CreatedTime) = YEAR(CURRENT_DATE())
                                            and Mes between MONTH(NOW()) - (" . $datos['numeroTiempo'] . ") and MONTH(NOW())
                                            " . $datos['where'] . " 
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGIPYEAR(array $datos)
    {
        $consulta = $this->consulta("select
                                    CONCAT('AÑO', ' ', Anio) AS Tiempo,
                                    SUM(if(Estatus = 'Abierto',1,0)) as Abierto,
                                    SUM(if(Estatus = 'En Atencion',1,0)) as 'En Atencion',
                                    SUM(if(Estatus = 'Problema',1,0)) as Problema
                                    from v_base_dashboard_sd
                                        WHERE
                                        Anio between YEAR(NOW()) - (" . $datos['numeroTiempo'] . ") and YEAR(NOW())
                                        " . $datos['where'] . " 
                                    group by Tiempo");
        return $consulta;
    }

    public function getDatosVGZWEEK(array $datos)
    {
        if ($datos['where'] === '') {
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
                                            and Semana = WEEK(NOW(), 1)
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        } else {
            $consulta = $this->consulta("SELECT
                                        CONCAT('SEMANA' , ' ', Semana) AS Tiempo,
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
                                    GROUP BY Region, Tiempo");
        }
        return $consulta;
    }

    public function getDatosVGZMONTH(array $datos)
    {
        if ($datos['where'] === '') {
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
                                            and Mes = MONTH(NOW())
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        } else {
            $consulta = $this->consulta("SELECT
                                        CONCAT('MES', ' ', Mes) AS Tiempo,
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
                                    GROUP BY Region, Tiempo");
        }
        return $consulta;
    }

    public function getDatosVGZYEAR(array $datos)
    {
        if ($datos['where'] === '') {
            $consulta = $this->consulta("SELECT 
                                        IF(Region IS NULL,  'SIN ZONA', Region) Region,
                                        SUM(IF(Estatus = 'Abierto', 1, 0)) AS Abierto,
                                        SUM(IF(Estatus = 'En Atencion', 1, 0)) AS 'En Atencion',
                                        SUM(IF(Estatus = 'Problema', 1, 0)) AS Problema,
                                        SUM(IF(Estatus = 'Cerrado', 1, 0)) AS Cerrado
                                    FROM
                                            v_base_dashboard_sd
                                        WHERE
                                            Anio = YEAR(NOW())
                                            " . $datos['where'] . " 
                                    GROUP BY Region");
        } else {
            $consulta = $this->consulta("SELECT 
                                CONCAT('AÑO', ' ', Anio) AS Tiempo,
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
        }
        return $consulta;
    }

    public function getDatosVGTO(array $datos)
    {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        $consulta = [];
        return $consulta;
    }

    public function getDatosTiposServicios()
    {
        $consulta = $this->consulta("SELECT
                                        Id AS id, Nombre AS text
                                    FROM cat_v3_servicios_departamento 
                                    WHERE IdDepartamento = '11' 
                                    AND Flag = '1'
                                    ORDER BY Nombre ASC");
        return $consulta;
    }

    public function getDatosVGTOlexmark(array $datos)
    {
        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and YEAR(DATE_SUB(b1.FileDate,INTERVAL 1 DAY)) = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and YEAR(DATE_SUB(b1.FileDate,INTERVAL 1 DAY)) = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and regionCliente((select IdRegionCliente from cat_v3_sucursales where Nombre = Contacto)) = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and MONTH(DATE_SUB(b1.FileDate,INTERVAL 1 DAY)) = '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and MONTH(DATE_SUB(b1.FileDate,INTERVAL 1 DAY)) = MONTH(now()) ";
                }

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and WEEK(DATE_SUB(b1.FileDate,INTERVAL 1 DAY),1) = '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and WEEK(DATE_SUB(b1.FileDate,INTERVAL 1 DAY),1) = WEEK(now(),1)";
                }

                break;
        }

        $consulta = $this->consulta(" 
                                select
                                Contacto as concept,
                                CAST(FinalNumber AS SIGNED) - CAST(InitialNumber as SIGNED) as total
                                from (
                                    select 
                                    Contacto,
                                    (select CarasCargadas from v_base_lexmark where Contacto = v1.Contacto and FileDate = v1.InitialDate) as InitialNumber,
                                    (select CarasCargadas from v_base_lexmark where Contacto = v1.Contacto and FileDate = v1.EndDate) as FinalNumber
                                    from (
                                        select
                                        b1.Contacto,
                                        MIN(b1.FileDate) as InitialDate,
                                        MAX(b1.FileDate) as EndDate 
                                        from v_base_lexmark b1
                                        " . $conditions . " 
                                        group by b1.Contacto
                                    ) as v1
                            ) as tf order by total desc limit 5");

        foreach ($consulta as $key => $value) {
            array_push($arrayReturn, [$value['concept'], $value['total'], $value['total']]);
        }

        return $arrayReturn;
    }

    public function getDatosVGTOtechnician($datos)
    {
        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes = '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes = MONTH(now()) ";
                }

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana = '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana = WEEK(now(),1)";
                }

                break;
        }

        $consulta = $this->consulta("
        select
        nombreUsuario(tst.Atiende) as Tecnico,
        sum(if(base.Estatus = 'Abierto', 1, 0)) as Abierto,
        sum(if(base.Estatus = 'En Atencion', 1, 0)) as EA,
        sum(if(base.Estatus = 'Problema', 1, 0)) as Problema,
        sum(if(base.Estatus = 'Cerrado', 1, 0)) as Cerrado,
        sum(1) as Total
        from v_base_dashboard_sd base
        inner join t_servicios_ticket tst on base.IdServicio = tst.Id
        " . $conditions . " 
        group by Tecnico 
        order by Total desc limit 5");

        foreach ($consulta as $key => $value) {
            array_push($arrayReturn, [
                $value['Tecnico'],
                $value['Abierto'],
                $value['Abierto'],
                $value['EA'],
                $value['EA'],
                $value['Problema'],
                $value['Problema'],
                $value['Cerrado'],
                $value['Cerrado']
            ]);
        }

        return $arrayReturn;
    }

    public function getDatosVGTObranches($datos)
    {
        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes = '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes = MONTH(now()) ";
                }

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana = '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana = WEEK(now(),1)";
                }

                break;
        }

        $consulta = $this->consulta("
        select
        sucursal(tst.IdSucursal) as Sucursal,
        sum(if(base.Estatus = 'Abierto', 1, 0)) as Abierto,
        sum(if(base.Estatus = 'En Atencion', 1, 0)) as EA,
        sum(if(base.Estatus = 'Problema', 1, 0)) as Problema,
        sum(if(base.Estatus = 'Cerrado', 1, 0)) as Cerrado,
        sum(1) as Total
        from v_base_dashboard_sd base
        inner join t_servicios_ticket tst on base.IdServicio = tst.Id
        " . $conditions . " 
        group by Sucursal 
        order by Total desc limit 5");

        foreach ($consulta as $key => $value) {
            array_push($arrayReturn, [
                $value['Sucursal'],
                $value['Abierto'],
                $value['Abierto'],
                $value['EA'],
                $value['EA'],
                $value['Problema'],
                $value['Problema'],
                $value['Cerrado'],
                $value['Cerrado']
            ]);
        }

        return $arrayReturn;
    }

    public function getDatosVGTOproduct($datos)
    {
        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes = '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes = MONTH(now()) ";
                }

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana = '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana = WEEK(now(),1) - 1";
                }

                break;
        }

        $consulta = $this->consulta("
        select 
        modelo(tcg.IdModelo) as Equipo,
        count(*) as Total
        from v_base_dashboard_sd base
        inner join t_solicitudes ts on base.Folio = ts.Folio
        inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud
        inner join t_correctivos_generales tcg on tst.Id = tcg.IdServicio
        " . $conditions . " 
        group by tcg.IdModelo
        order by Total desc 
        limit 5");

        foreach ($consulta as $key => $value) {
            array_push($arrayReturn, [
                $value['Equipo'],
                $value['Total'],
                $value['Total']
            ]);
        }

        return $arrayReturn;
    }

    public function getDatosVGTOproductline($datos)
    {
        $arrayReturn = array();
        $conditions = ' where 1 = 1 ';

        if (isset($datos['year']) && $datos['year'] > 2018) {
            $conditions .= " and base.Anio = '" . $datos['year'] . "' ";
        } else {
            $conditions .= " and base.Anio = YEAR(now()) ";
        }

        if (isset($datos['zona']) && $datos['zona'] !== '') {
            $conditions .= " and base.Region = '" . $datos['zona'] . "' ";
        }

        switch ($datos['tiempo']) {
            case 'MONTH':
                if (isset($datos['month']) && $datos['month'] > 0) {
                    $conditions .= " and base.Mes = '" . $datos['month'] . "' ";
                } else {
                    $conditions .= " and base.Mes = MONTH(now()) ";
                }

                break;

            default:
                if (isset($datos['week']) && $datos['week'] > 0) {
                    $conditions .= " and base.Semana = '" . $datos['week'] . "' ";
                } else {
                    $conditions .= " and base.Semana = WEEK(now(),1) - 1";
                }

                break;
        }

        $consulta = $this->consulta("
        select 
        linea(lineaByModelo(tcg.Idmodelo)) as Linea,
        count(*) as Total
        from v_base_dashboard_sd base
        inner join t_solicitudes ts on base.Folio = ts.Folio
        inner join t_servicios_ticket tst on ts.Id = tst.IdSolicitud
        inner join t_correctivos_generales tcg on tst.Id = tcg.IdServicio
        " . $conditions . " 
        group by lineaByModelo(tcg.Idmodelo)
        order by Total desc 
        limit 5");

        foreach ($consulta as $key => $value) {
            array_push($arrayReturn, [
                $value['Linea'],
                $value['Total'],
                $value['Total']
            ]);
        }

        return $arrayReturn;
    }
}
