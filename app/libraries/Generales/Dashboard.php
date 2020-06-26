<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

class Dashboard extends General {

    private $DBS;

    public function __construct() {
        parent::__construct();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        parent::getCI()->load->helper('date');
    }

    public function getSolicitudesGeneradas(array $fechas = null) {
        if (count($fechas) <= 0) {
            $fechas = $this->getFechasInicialesDashboard()[0];
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        } else {
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        }
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['estatus']['datos'] = $this->DBS->consultaGral("
            SELECT
            ts.IdEstatus as IdGen,
            estatus(ts.IdEstatus) as Concepto,
            count(*) as Total            
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            GROUP BY IdGen
            order by Total desc;");
        $data['estatus']['total'] = "
            SELECT
            count(*) as Total        
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59';";

        $data['estatus']['titulo'] = 'Estatus de mis solicitudes';
        $data['estatus']['tituloConcepto'] = 'Estatus';
        $data['estatus']['tituloTotal'] = 'Solicitudes';
        $data['estatus']['divId'] = 'solicitudes_generadas_estatus';

        $data['prioridad']['datos'] = $this->DBS->consultaGral("
            SELECT
            if(ts.IdPrioridad = 0, 3,ts.IdPrioridad) as IdGen,
            if(ts.IdPrioridad = 0, prioridad(3), prioridad(ts.IdPrioridad)) as Concepto,
            count(*) as Total
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            GROUP BY IdGen
            order by Total desc;");
        $data['prioridad']['total'] = "
            SELECT
            count(*) as Total
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59';";

        $data['prioridad']['titulo'] = 'Prioridades de mis solicitudes';
        $data['prioridad']['tituloConcepto'] = 'Prioridad';
        $data['prioridad']['tituloTotal'] = 'Solicitudes';
        $data['prioridad']['divId'] = 'solicitudes_generadas_prioridad';

        $data['dpto']['datos'] = $this->DBS->consultaGral("
            SELECT
            ts.IdDepartamento as IdGen,
            departamento(ts.IdDepartamento) as Concepto,
            count(*) as Total
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            GROUP BY IdGen
            order by Total desc;");
        $data['dpto']['total'] = "
            SELECT
            count(*) as Total
            from t_solicitudes ts 
            where ts.Solicita = '" . $usuario['Id'] . "'
            and ts.IdTipoSolicitud <> 4
            and ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59';";

        $data['dpto']['titulo'] = 'Departamentos de mis solicitudes';
        $data['dpto']['tituloConcepto'] = 'Prioridad';
        $data['dpto']['tituloTotal'] = 'Solicitudes';
        $data['dpto']['divId'] = 'solicitudes_generadas_departamento';

        $data['tabla'] = $this->DBS->consulta("
            select 
            ts.Id,
            departamento(ts.IdDepartamento) as Departamento,
            if(tsi.Asunto is null, SUBSTR(tsi.Descripcion, 1, 120), tsi.Asunto) as Asunto,
            estatus(ts.IdEstatus) as Estatus,
            prioridad(ts.IdPrioridad) as Prioridad,
            ts.Ticket,
            ts.FechaCreacion
            FROM
            t_solicitudes ts left join t_solicitudes_internas tsi
            on ts.Id = tsi.IdSolicitud
            where ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.Solicita = '" . $usuario['Id'] . "'                
            and ts.IdTipoSolicitud <> 4;");

        $data['totales1'] = $this->DBS->consulta("
            select 
            count(*) as Total,
            DATEDIFF('" . $fechas['Fin'] . "', '" . $fechas['Inicio'] . "') + 1 as Dias
            FROM
            t_solicitudes ts left join t_solicitudes_internas tsi
            on ts.Id = tsi.IdSolicitud
            where ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.Solicita = '" . $usuario['Id'] . "'                  
            and ts.IdTipoSolicitud <> 4;");

        $data['totales2'] = $this->DBS->consulta("
            select COUNT(*) as Total from (
                select 
                ts.IdDepartamento
                FROM
                t_solicitudes ts left join t_solicitudes_internas tsi
                on ts.Id = tsi.IdSolicitud
                where ts.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
                and ts.Solicita = '" . $usuario['Id'] . "'                
                and ts.IdTipoSolicitud <> 4
                group by IdDepartamento
            ) as tf;");

        return $data;
    }

    public function getFiltrosFecha(array $data = null) {
        switch ($data['id']) {
            case 'btn-anio-pasado':
                return $this->getAnioPasado();
                break;
            case 'btn-trimestre-pasado':
                return $this->getTrimestrePasado();
                break;
            case 'btn-mes-pasado':
                return $this->getMesPasado();
                break;
            case 'btn-semana-pasado':
                return $this->getSemanaPasada();
                break;
            case 'btn-anio-presente':
                return $this->getAnioPresente();
                break;
            case 'btn-mes-presente':
                return $this->getMesPresente();
                break;
            case 'btn-anio-anterior':
                return $this->getAnioAnterior();
                break;
            case 'btn-trimestre-anterior':
                return $this->getTrimestreAnterior();
                break;
            case 'btn-mes-anterior':
                return $this->getMesAnterior();
                break;
            case 'btn-semana-anterior':
                return $this->getFechasInicialesDashboard();
                break;
        }
    }

    public function getFechasInicialesDashboard() {
        return $this->DBS->consultaGral("
            select 
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 6 DAY,'%d/%m/%Y') as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getSemanaPasada() {
        return $this->DBS->consultaGral("
            SELECT 
            DATE_FORMAT(DATE_ADD(CURRENT_DATE() - INTERVAL 1 WEEK, INTERVAL - WEEKDAY(CURRENT_DATE()) DAY),'%d/%m/%Y') AS Inicio,
            DATE_FORMAT(DATE_ADD(CURRENT_DATE() - INTERVAL 1 WEEK, INTERVAL - WEEKDAY(CURRENT_DATE()) DAY) + INTERVAL 1 WEEK - INTERVAL 1 DAY,'%d/%m/%Y') AS Fin;");
    }

    public function getAnioPasado() {
        return $this->DBS->consultaGral("
            select 
            concat('01/01/',DATE_FORMAT(CURRENT_DATE(),'%Y')-1) as Inicio,
            concat('31/12/',DATE_FORMAT(CURRENT_DATE(),'%Y')-1) as Fin;");
    }

    public function getTrimestrePasado() {
        return $this->DBS->consultaGral("
            select
            concat('01/',DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH,'%m/%Y')) as Inicio,
            DATE_FORMAT(concat(DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH,'%Y-%m'),'-01') + INTERVAL 3 MONTH -INTERVAL 1 DAY, '%d/%m/%Y') as Fin;");
    }

    public function getMesPasado() {
        return $this->DBS->consultaGral("
            select
            concat('01/',DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH,'%m/%Y')) as Inicio,
            DATE_FORMAT(concat(DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH,'%Y-%m'),'-01') + INTERVAL 1 MONTH -INTERVAL 1 DAY, '%d/%m/%Y') as Fin;");
    }

    public function getAnioPresente() {
        return $this->DBS->consultaGral("
            select 
            concat('01/01/',DATE_FORMAT(CURRENT_DATE(),'%Y')) as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getMesPresente() {
        return $this->DBS->consultaGral("
            select 
            concat('01/',DATE_FORMAT(CURRENT_DATE(),'%m/%Y')) as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getAnioAnterior() {
        return $this->DBS->consultaGral("
            select 
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY,'%d/%m/%Y') as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getTrimestreAnterior() {
        return $this->DBS->consultaGral("
            select 
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 3 MONTH + INTERVAL 1 DAY,'%d/%m/%Y') as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getMesAnterior() {
        return $this->DBS->consultaGral("
            select 
            DATE_FORMAT(CURRENT_DATE() - INTERVAL 1 MONTH + INTERVAL 1 DAY,'%d/%m/%Y') as Inicio,
            DATE_FORMAT(CURRENT_DATE(),'%d/%m/%Y') as Fin;");
    }

    public function getServiciosAreaLogistica(array $fechas = null) {
        if (count($fechas) <= 0) {
            $fechas = $this->getFechasInicialesDashboard()[0];
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        } else {
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        }
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $data['estatus']['datos'] = $this->DBS->consultaGral("
            select 
            tst.IdEstatus as IdGen,
            estatus(tst.IdEstatus) as Concepto,
            count(*) as Total
            from t_servicios_ticket tst INNER JOIN t_solicitudes ts
            on tst.IdSolicitud = ts.Id
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in 
            (select Id from cat_v3_departamentos_siccob where IdArea = 5)
            group by IdGen order by Total desc;");
        $data['estatus']['titulo'] = 'Estatus de los servicios';
        $data['estatus']['tituloConcepto'] = 'Estatus';
        $data['estatus']['tituloTotal'] = 'Servicios';
        $data['estatus']['divId'] = 'servicios-area-estatus';

        
        $data['atiende']['datos'] = $this->DBS->consultaGral("
            select 
            tst.Atiende as IdGen,
            usuario(tst.Atiende) as Concepto,
            count(*) as Total
            from t_servicios_ticket tst INNER JOIN t_solicitudes ts
            on tst.IdSolicitud = ts.Id
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in 
            (select Id from cat_v3_departamentos_siccob where IdArea = 5)
            group by IdGen order by Total desc;");
        $data['atiende']['titulo'] = 'Usuarios que atienden';
        $data['atiende']['tituloConcepto'] = 'Usuario';
        $data['atiende']['tituloTotal'] = 'Servicio';
        $data['atiende']['divId'] = 'servicios-area-atiende';
        
        

        $data['dpto']['datos'] = $this->DBS->consultaGral("
            select 
            ts.IdDepartamento as IdGen,
            departamento(ts.IdDepartamento) as Concepto,
            count(*) as Total
            from t_servicios_ticket tst INNER JOIN t_solicitudes ts
            on tst.IdSolicitud = ts.Id
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in 
            (select Id from cat_v3_departamentos_siccob where IdArea = 5)
            group by IdGen order by Total desc;");
        $data['dpto']['titulo'] = 'Departamentos de los Servicios';
        $data['dpto']['tituloConcepto'] = 'Departamento';
        $data['dpto']['tituloTotal'] = 'Servicios';
        $data['dpto']['divId'] = 'servicios-area-departamentos';
        
        

        $data['tabla'] = $this->DBS->consulta("
            select 
            ts.Ticket,
            tst.Id,
            tipoServicio(tst.IdTipoServicio) as TipoServ,
            tst.FechaCreacion,
            tst.Descripcion,
            estatus(tst.IdEstatus) as Estatus,
            usuario(tst.Atiende) as Atiende
            from t_servicios_ticket tst INNER JOIN t_solicitudes ts
            on tst.IdSolicitud = ts.Id
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in (select Id from cat_v3_departamentos_siccob where IdArea = 5);");
        
        

        $data['totales1'] = $this->DBS->consulta("
            select 
            count(*) as Total,
            DATEDIFF('" . $fechas['Fin'] . "', '" . $fechas['Inicio'] . "') + 1 as Dias
            from t_servicios_ticket tst INNER JOIN t_solicitudes ts
            on tst.IdSolicitud = ts.Id
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in 
            (select Id from cat_v3_departamentos_siccob where IdArea = 5);");

        $data['totales2'] = $this->DBS->consulta("
            select COUNT(*) as Total from (
                select 
                ts.IdDepartamento
                from t_servicios_ticket tst INNER JOIN t_solicitudes ts
                on tst.IdSolicitud = ts.Id
                where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
                and ts.IdDepartamento in 
                (select Id from cat_v3_departamentos_siccob where IdArea = 5)
                group by IdDepartamento
            ) as tf;");

        return $data;
    }
    
    
    public function getServiciosAreaLogisticaExcel(array $fechas = null) {
        if (count($fechas) <= 0) {
            $fechas = $this->getFechasInicialesDashboard()[0];
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        } else {
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        }  

        $data = $this->DBS->consulta("
            select 
            ts.Id as IdSolicitud,
            ts.FechaCreacion as CreacionSolicitud,
            nombreUsuario(ts.Solicita) as Solicita,
            tsi.Asunto,
            tsi.Descripcion as DescripcionSolicitud,
            tst.Ticket,
            tst.Id as Servicio,
            tipoServicio(tst.IdTipoServicio) as TipoServ,
            estatus(tst.IdEstatus) as EstatusServicio,
            nombreUsuario(tst.Atiende) as Atiende,
            tst.FechaCreacion as CreacionServicio,
            tst.FechaInicio as InicioServicio,
            tst.FechaConclusion as ConclusionServicio,
            tst.Descripcion as DescripcionServicio
            
            from t_solicitudes ts INNER JOIN t_solicitudes_internas tsi
            on ts.Id = tsi.IdSolicitud
            left join t_servicios_ticket tst 
            on ts.Id = tst.IdSolicitud
            
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59'
            and ts.IdDepartamento in (select Id from cat_v3_departamentos_siccob where IdArea = 5);");
 
        return $data;
    }
    
    
    public function getServiciosTraficosAreaLogisticaExcel(array $fechas = null) {
        if (count($fechas) <= 0) {
            $fechas = $this->getFechasInicialesDashboard()[0];
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        } else {
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        }  

        $data = $this->DBS->consulta("
            select 
            tst.Ticket,
            tst.Id as IdServicio,
            estatus(tst.IdEstatus) as Estatus,
            tipoTrafico(ttg.IdTipoTrafico) as TipoTrafico,
            tipoOrigenDestino(ttg.IdTipoOrigen) as TipoOrigen,
            case ttg.IdTipoOrigen
                    when 1 then sucursalCliente(ttg.IdOrigen)
                    when 2 then proveedor(ttg.IdOrigen)
                    when 3 then ttg.OrigenDireccion
            end as Origen,
            tipoOrigenDestino(ttg.IdTipoDestino) as TipoDestino,
            case ttg.IdTipoDestino
                    when 1 then sucursalCliente(ttg.IdDestino)
                    when 2 then proveedor(ttg.IdDestino)
                    when 3 then ttg.DestinoDireccion
            end as Destino,
            tipoEnvio(tte.IdTipoEnvio) as TipoEnvio,
            paqueteria(tte.IdPaqueteria) as Paqueteria,
            tte.FechaEnvio,
            tte.Guia,
            tte.ComentariosEnvio,
            case ttg.IdTipoTrafico
                    when 1 then tte.FechaEntrega
                    when 2 then ttr.Fecha
            end as FechaEntregaRecoleccion,
            case ttg.IdTipoTrafico
                    when 1 then tte.NombreRecibe
                    when 2 then ttr.NombreEntrega
            end as NombreRecibeEntrega,
            case ttg.IdTipoTrafico
                    when 1 then tte.ComentariosEntrega
                    when 2 then ttr.ComentariosRecoleccion
            end as ComentariosEntregaRecoleccion
            
            from t_servicios_ticket tst INNER JOIN t_traficos_generales ttg
            on tst.Id = ttg.IdServicio
            LEFT JOIN t_traficos_envios tte
            on tst.Id = tte.IdServicio
            LEFT JOIN t_traficos_recolecciones ttr
            on tst.Id = ttr.IdServicio
            
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59';");
 
        return $data;
    }
    
    
    public function getEquiposTraficosExcel(array $fechas = null) {
        if (count($fechas) <= 0) {
            $fechas = $this->getFechasInicialesDashboard()[0];
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        } else {
            $fechas['Inicio'] = substr($fechas['Inicio'], 6, 4) . '-' . substr($fechas['Inicio'], 3, 2) . '-' . substr($fechas['Inicio'], 0, 2);
            $fechas['Fin'] = substr($fechas['Fin'], 6, 4) . '-' . substr($fechas['Fin'], 3, 2) . '-' . substr($fechas['Fin'], 0, 2);
        }  

        $data = $this->DBS->consulta("
            select 
            tst.Ticket,
            tst.Id as IdServicio,
            estatus(tst.IdEstatus) as Estatus,
            tipoEquipoTrafico(tteq.IdTipoEquipo) as TipoEquipo,
            case tteq.IdTipoEquipo
                    when 1 then productoSAE(tteq.IdModelo)
                    when 4 then tteq.DescripcionOtros
                    when 5 then productoSAE(tteq.IdModelo)
            end as Producto,
            tteq.Serie,
            tteq.Cantidad,
            tipoTrafico(ttg.IdTipoTrafico) as TipoTrafico,
            tipoOrigenDestino(ttg.IdTipoOrigen) as TipoOrigen,
            case ttg.IdTipoOrigen
                    when 1 then sucursalCliente(ttg.IdOrigen)
                    when 2 then proveedor(ttg.IdOrigen)
                    when 3 then ttg.OrigenDireccion
            end as Origen,
            tipoOrigenDestino(ttg.IdTipoDestino) as TipoDestino,
            case ttg.IdTipoDestino
                    when 1 then sucursalCliente(ttg.IdDestino)
                    when 2 then proveedor(ttg.IdDestino)
                    when 3 then ttg.DestinoDireccion
            end as Destino,
            tipoEnvio(tte.IdTipoEnvio) as TipoEnvio,
            paqueteria(tte.IdPaqueteria) as Paqueteria,
            tte.FechaEnvio,
            tte.Guia,
            case ttg.IdTipoTrafico
                    when 1 then tte.FechaEntrega
                    when 2 then ttr.Fecha
            end as FechaEntregaRecoleccion,
            case ttg.IdTipoTrafico
                    when 1 then tte.NombreRecibe
                    when 2 then ttr.NombreEntrega
            end as NombreRecibeEntrega
            from t_servicios_ticket tst INNER JOIN t_traficos_generales ttg
            on tst.Id = ttg.IdServicio
            INNER JOIN t_traficos_equipo tteq
            on tst.Id = tteq.IdServicio
            LEFT JOIN t_traficos_envios tte
            on tst.Id = tte.IdServicio
            LEFT JOIN t_traficos_recolecciones ttr
            on tst.Id = ttr.IdServicio
            
            where tst.FechaCreacion between '" . $fechas['Inicio'] . " 00:00:00' and '" . $fechas['Fin'] . " 23:59:59';");
 
        return $data;
    }

}
