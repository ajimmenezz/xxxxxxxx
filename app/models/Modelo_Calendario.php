<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_Calendario
 *
 * @author Freddy
 */
class Modelo_Calendario extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getServiciosCalendario(string $idUsuario, array $datos = []) {
        switch ($datos['area']) {
            case 'mis':
                $code = ' Atiende = ' . $idUsuario . ' ';
                break;
            case '0': case 0:
                $code = ' 1 = 1 ';
                break;
            default:
                $code = '
                    Atiende in ((
                        select 
                        usuarios.Id
                        from cat_v3_areas_siccob area 
                        inner join cat_v3_departamentos_siccob depto on area.Id = depto.IdArea
                        inner join cat_perfiles puesto on depto.Id = puesto.IdDepartamento
                        inner join cat_v3_usuarios usuarios on puesto.Id = usuarios.IdPerfil
                        where area.Id = "' . $datos['area'] . '"
                    )) ';
                break;
        }


        return $this->consulta('
            select 
            Id,
            Ticket,
            folioByServicio(Id) as Folio,
            tipoServicio(IdTipoServicio) as Tipo,
            tst.IdEstatus,
            estatus(IdEstatus) as Estatus,
            if(FechaTentativa is not null and Fechatentativa <> "0000-00-00",FechaTentativa,SUBSTR(FechaCreacion, 1, 10)) as Fecha,
            sucursal(tst.IdSucursal) as Sucursal,
            nombreUsuario(tst.Atiende) as Atiende
            from t_servicios_ticket tst
            where ' . $code . ' 
            and month(if(FechaTentativa is not null and Fechatentativa <> "0000-00-00",FechaTentativa,FechaCreacion)) = ' . $datos['mes'] . '
            and year(if(FechaTentativa is not null and Fechatentativa <> "0000-00-00",FechaTentativa,FechaCreacion)) = ' . $datos['anio'] . ';'
        );
    }

    public function getCalendarAreasPermissions(string $idUsuario) {
        return $this->consulta("
            select 
            replace(Permiso,'CALENDAR_','') as Id,
            (select Nombre from cat_v3_areas_siccob where Id = replace(Permiso,'CALENDAR_','')) as Area
            from cat_v3_permisos cp 
            where cp.Permiso like 'CALENDAR_%'
            and FIND_IN_SET(cp.Id,concat(\"'\",(select Permisos from cat_perfiles where Id = (select IdPerfil from cat_v3_usuarios where Id = '" . $idUsuario . "')),\",'\"))
            order by Area;");
    }

    public function getDatosServicioCalendario(string $idServicio) {
        return $this->consulta("
            select 
            Id,
            Ticket,
            folioByServicio(Id) as Folio,
            tipoServicio(IdTipoServicio) as Tipo,
            sucursalCliente(IdSucursal) as Sucursal,
            IdEstatus,
            estatus(IdEstatus) as Estatus,
            nombreUsuario(Atiende) as Atiende,
            FechaCreacion,
            FechaInicio,
            FechaConclusion,
            FechaTentativa,
            Descripcion
            from t_servicios_ticket 
            where Id = '" . $idServicio . "';");
    }

    public function actualizaTentativa(array $datos = []) {
        $resultado = $this->actualizar('t_servicios_ticket', ['FechaTentativa' => $datos['tentativa']], ['Id' => $datos['id']]);
        return $resultado;
    }

}
