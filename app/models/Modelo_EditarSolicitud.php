<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_EditarSolicitud extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getDepartamentos() {
        $consulta = $this->consulta("select 
                                        dpto.Id,
                                        departamentoArea(dpto.Id) as Nombre
                                        from cat_v3_departamentos_siccob dpto
                                        order by Nombre;");
        return $consulta;
    }

    public function getSolicitudes($departamentos = '', $ids = '') {
        $condicion = ' where 1=1 ';
        $condicion .= ($departamentos != '') ? ' and ts.IdDepartamento in ("' . $departamentos . '")' : '';
        $condicion .= ($ids != '') ? ' and ts.Id in ("' . $ids . '")' : '';

        $consulta = $this->consulta('select 
                                    ts.Id,
                                    ts.Ticket,
                                    nombreUsuario(ts.Solicita) as Solicita,
                                    departamentoArea(ts.IdDepartamento) as Departamento,
                                    prioridad(ts.IdPrioridad) as Prioridad,
                                    nombreUsuario(ts.Atiende) as Atiende,
                                    estatus(ts.IdEstatus) as Estatus,
                                    ts.FechaCreacion as Fecha,
                                    tsi.Asunto                                   
                                    from t_solicitudes ts inner join t_solicitudes_internas tsi 
                                    on ts.Id = tsi.IdSolicitud ' . $condicion . ' order by ts.Id;');

        return $consulta;
    }

    public function getDetalleSolicitud($id = '') {
        $condicion = ' where ts.Id = "' . $id . '"';

        $consulta = $this->consulta('select 
                                    ts.*,
                                    nombreUsuario(ts.Solicita) as SolicitaString,
                                    departamentoArea(ts.IdDepartamento) as DepartamentoString,
                                    prioridad(ts.IdPrioridad) as PrioridadString,
                                    nombreUsuario(ts.Atiende) as AtiendeString,
                                    estatus(ts.IdEstatus) as EstatusString,
                                    tsi.Asunto,
                                    tsi.Descripcion,
                                    tsi.Evidencias
                                    from t_solicitudes ts inner join t_solicitudes_internas tsi 
                                    on ts.Id = tsi.IdSolicitud ' . $condicion . ' order by ts.Id;');

        return $consulta;
    }
    
    public function getDetalleSolicitudByServicio($id = '') {
        $condicion = ' where ts.Id = (select IdSolicitud from t_servicios_ticket where Id = "' . $id . '") ';

        $consulta = $this->consulta('select 
                                    ts.*,
                                    nombreUsuario(ts.Solicita) as SolicitaString,
                                    departamentoArea(ts.IdDepartamento) as DepartamentoString,
                                    prioridad(ts.IdPrioridad) as PrioridadString,
                                    nombreUsuario(ts.Atiende) as AtiendeString,
                                    estatus(ts.IdEstatus) as EstatusString,
                                    tsi.Asunto,
                                    tsi.Descripcion,
                                    tsi.Evidencias
                                    from t_solicitudes ts inner join t_solicitudes_internas tsi 
                                    on ts.Id = tsi.IdSolicitud ' . $condicion . ' order by ts.Id;');

        return $consulta;
    }

    public function getPrioridades() {
        $consulta = $this->consulta("select p.Id, prioridad(p.Id) as Nombre from cat_v3_prioridades p;");
        return $consulta;
    }

    public function getUsuarios() {
        $consulta = $this->consulta("select u.Id, nombreUsuario(u.Id) as Nombre from cat_v3_usuarios u where u.Id > 1 order by Nombre;");
        return $consulta;
    }

    public function getEstatus() {
        $consulta = $this->consulta("select e.Id, e.Nombre from cat_v3_estatus e order by Nombre;");
        return $consulta;
    }

    public function guardaCambiosSolicitud($data) {
        $this->iniciaTransaccion();
        $return_array = [
            'code' => 500
        ];

        $this->queryBolean('SET FOREIGN_KEY_CHECKS = 0');

        $this->actualizar("t_solicitudes", [
            "Solicita" => $data['detalles']['solicita'],
            "IdDepartamento" => $data['detalles']['departamento'],
            "IdPrioridad" => $data['detalles']['prioridad'],
            "Atiende" => $data['detalles']['atiende'],
            "FechaCreacion" => $data['detalles']['fecha']
                ], ['Id' => $data['detalles']['id']]
        );

        $this->actualizar("t_solicitudes_internas", [
            "Asunto" => $data['detalles']['asunto'],
            "Descripcion" => $data['detalles']['descripcion'],
            "Evidencias" => $data['files']
                ], ['IdSolicitud' => $data['detalles']['id']]
        );

        $this->queryBolean('SET FOREIGN_KEY_CHECKS = 1');

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->commitTransaccion();
            $return_array['code'] = 200;
        }
        return $return_array;
    }
    
    
    /*Metodos para mostrar los detalles de servicios y solicitudes sin login*/
    public function getListaServiciosBySolicitud($solicitud){
        $consulta = $this->consulta("select 
                                    tst.Id,
                                    tst.Ticket,
                                    tst.FechaCreacion as Fecha,
                                    estatus(tst.IdEstatus) as Estatus,
                                    tipoServicio(tst.IdTipoServicio) as Tipo,
                                    sucursal(tst.IdSucursal) as Sucursal,
                                    nombreUsuario(tst.Atiende) as Atiende,
                                    tst.Descripcion
                                    from t_servicios_ticket tst where tst.IdSolicitud = '".$solicitud."';");
        return $consulta;
    }

}
