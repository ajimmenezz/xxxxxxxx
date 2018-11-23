<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Dashboard extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    private function getDepartamentosAreaByUsuario() {
        $consulta = $this->consulta("SELECT
                                    Id
                                    from cat_v3_departamentos_siccob cds
                                    where IdArea = (
                                                    select 
                                                    IdArea 
                                                    from cat_v3_departamentos_siccob 
                                                    where Id = (
                                                                select 
                                                                IdDepartamento 
                                                                from cat_perfiles 
                                                                where Id = (
                                                                            select IdPerfil from cat_v3_usuarios where Id = '" . $this->usuario['Id'] . "'
                                                                            )
                                                                )
                                                    )");

        $departamentos = '';
        if (!empty($consulta)) {
            $arrayAux = [];
            foreach ($consulta as $key => $value) {
                array_push($arrayAux, $value['Id']);
            }
            $departamentos = implode(",", $arrayAux);
        } else {
            $departamentos = 0;
        }

        return $departamentos;
    }

    public function getFechasInicialesDashboard() {
        $consulta = $this->consulta("
            select 
            DATE_FORMAT((CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY),'%d/%m/%Y') as Inicio,
            DATE_FORMAT(CURDATE(),'%d/%m/%Y') as Fin;");
        return $consulta;
    }

    public function convertFechasToSQL($fechaIni = '', $fechaFin = '') {
        $fechas = [
            'inicio' => ($fechaIni != '') ? substr($fechaIni, 6, 4) . '-' . substr($fechaIni, 3, 2) . '-' . substr($fechaIni, 0, 2) : '',
            'fin' => ($fechaFin != '') ? substr($fechaFin, 6, 4) . '-' . substr($fechaFin, 3, 2) . '-' . substr($fechaFin, 0, 2) : ''
        ];

        return $fechas;
    }

    public function getGroupEstatus($fechaIni = '', $fechaFin = '', $prioridad = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    ts.IdEstatus as Id,
                                    estatus(ts.IdEstatus) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts 
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")                                  
                                    group by ts.IdEstatus
                                    order by Total desc;");

        return $consulta;
    }

    public function getGroupPrioridades($fechaIni = '', $fechaFin = '', $estatus = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($estatus !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatus . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    if(ts.IdPrioridad = 0, 1, ts.IdPrioridad) as Id,
                                    prioridad(ts.IdPrioridad) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts 
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ") 
                                    group by ts.IdPrioridad
                                    order by Total desc;");

        return $consulta;
    }

    public function getGroupTipos($fechaIni = '', $fechaFin = '', $estatus = '', $prioridad = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($estatus !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatus . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    tst.IdTipoServicio as Id,
                                    tipoServicio(tst.IdTipoServicio) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")                                     
                                    group by tst.IdTipoServicio
                                    order by Total desc;");

        return $consulta;
    }

    public function getListaSolicitudes($fechaIni = '', $fechaFin = '', $estatus = '', $prioridad = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($estatus !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatus . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    ts.Id,
                                    ts.Ticket,
                                    estatus(ts.IdEstatus) as Estatus,
                                    prioridad(ts.IdPrioridad) as Prioridad,
                                    ts.FechaCreacion,
                                    nombreUsuario(ts.Solicita) as Solicita,
                                    cap_first(tsi.Asunto) as Asunto
                                    from t_solicitudes ts inner join t_solicitudes_internas tsi
                                    on ts.Id = tsi.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ") ");

        return $consulta;
    }

    public function getEstatusName($id = '') {
        $consulta = $this->consulta("select cap_first(estatus('" . $id . "')) as Estatus;");
        return $consulta[0]['Estatus'];
    }

    public function getPrioridadName($id = '') {
        $consulta = $this->consulta("select cap_first(prioridad('" . $id . "')) as Prioridad;");
        return $consulta[0]['Prioridad'];
    }

    public function getTipoName($id = '') {
        $consulta = $this->consulta("select cap_first(tipoServicio('" . $id . "')) as Tipo;");
        return $consulta[0]['Tipo'];
    }

    public function getSucursalName($id = '') {
        $consulta = $this->consulta("select cap_first(sucursal('" . $id . "')) as Sucursal;");
        if ($consulta[0]['Sucursal'] == '') {
            return 'Sin Sucursal';
        } else {
            return $consulta[0]['Sucursal'];
        }
    }

    public function getUsuarioName($id = '') {
        $consulta = $this->consulta("select cap_first(nombreUsuario('" . $id . "')) as Usuario;");
        return $consulta[0]['Usuario'];
    }

    public function getGroupEstatusServicios($fechaIni = '', $fechaFin = '', $tipo = '', $estatusSolicitud = '', $prioridad = '', $sucursal = '', $atiende = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($tipo !== '') {
            $condicion .= " and tst.IdTipoServicio = '" . $tipo . "' ";
        }

        if ($estatusSolicitud !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatusSolicitud . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        if ($sucursal !== '') {
            if ($sucursal == 'NA') {
                $condicion .= " and (tst.IdSucursal is null || tst.IdSucursal in (0,'')) ";
            } else {
                $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
            }
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    tst.IdEstatus as Id,
                                    estatus(tst.IdEstatus) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")
                                    group by tst.IdEstatus
                                    order by Total desc;");

        return $consulta;
    }

    public function getGroupSucursalesServicios($fechaIni = '', $fechaFin = '', $tipo = '', $estatusSolicitud = '', $prioridad = '', $sucursal = '', $atiende = '', $estatusServicio = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($tipo !== '') {
            $condicion .= " and tst.IdTipoServicio = '" . $tipo . "' ";
        }

        if ($estatusSolicitud !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatusSolicitud . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        if ($sucursal !== '') {
            if ($sucursal == 'NA') {
                $condicion .= " and (tst.IdSucursal is null || tst.IdSucursal in (0,'')) ";
            } else {
                $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
            }
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }

        if ($estatusServicio !== '') {
            $condicion .= " and tst.IdEstatus = '" . $estatusServicio . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    if(tst.IdSucursal is null or tst.IdSucursal = 0,'NA',tst.IdSucursal) as Id,
                                    if(tst.IdSucursal is null or tst.IdSucursal = 0,'Sin Sucursal',cap_first(sucursal(tst.IdSucursal))) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")
                                    group by tst.IdSucursal
                                    order by Total desc;");

        return $consulta;
    }

    public function getGroupAtiendeServicios($fechaIni = '', $fechaFin = '', $tipo = '', $estatusSolicitud = '', $prioridad = '', $sucursal = '', $atiende = '', $estatusServicio = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($tipo !== '') {
            $condicion .= " and tst.IdTipoServicio = '" . $tipo . "' ";
        }

        if ($estatusSolicitud !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatusSolicitud . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }

        if ($sucursal !== '') {
            if ($sucursal == 'NA') {
                $condicion .= " and (tst.IdSucursal is null || tst.IdSucursal in (0,'')) ";
            } else {
                $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
            }
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }

        if ($estatusServicio !== '') {
            $condicion .= " and tst.IdEstatus = '" . $estatusServicio . "' ";
        }

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    tst.Atiende as Id,
                                    nombreUsuario(tst.Atiende) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")
                                    group by tst.Atiende
                                    order by Total desc;");

        return $consulta;
    }

    public function getListaServicios($fechaIni = '', $fechaFin = '', $tipo = '', $estatus = '', $estatusSolicitud = '', $sucursal = '', $atiende = '', $prioridad = '') {
        $fechas = $this->convertFechasToSQL($fechaIni, $fechaFin);
        $fechaIni = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        if ($fechaIni == '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else if ($fechaIni != '' && $fechaFin == '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "'";
        } else if ($fechaIni != '' && $fechaFin != '') {
            $condicion = " ts.FechaCreacion >= '" . $fechaIni . "' and ts.FechaCreacion <= '" . $fechaFin . "' ";
        } else {
            $condicion = " ts.FechaCreacion >= (CURDATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY) and ts.FechaCreacion <= CURDATE() ";
        }

        if ($tipo !== '') {
            $condicion .= " and tst.IdTipoServicio = '" . $tipo . "' ";
        }

        if ($sucursal !== '') {
            if ($sucursal == 'NA') {
                $condicion .= " and (tst.IdSucursal is null || tst.IdSucursal in (0,'')) ";
            } else {
                $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
            }
        }       

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "' ";
        }

        if ($estatus !== '') {
            $condicion .= " and tst.IdEstatus = '" . $estatus . "'";
        }

        if ($estatusSolicitud !== '') {
            $condicion .= " and ts.IdEstatus = '" . $estatusSolicitud . "' ";
        }

        if ($prioridad !== '') {
            $condicion .= " and ts.IdPrioridad = '" . $prioridad . "' ";
        }                

        $deptos = $this->getDepartamentosAreaByUsuario();

        $consulta = $this->consulta("select 
                                    tst.Id,
                                    ts.Ticket,
                                    sucursal(tst.IdSucursal) as Sucursal,
                                    estatus(tst.IdEstatus) as Estatus,
                                    tipoServicio(tst.IdTipoServicio) as Tipo,
                                    tst.FechaCreacion as Fecha,
                                    nombreUsuario(tst.Atiende) as Atiende,
                                    tst.Descripcion
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento in (" . $deptos . ")");

        return $consulta;
    }

}
