<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Salas4D extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getSucursales4D() {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_sucursales where Salas4D = 1 and Flag = 1 order by Nombre;");
        return $consulta;
    }

    public function getElementosSucursal($id = "") {
        $consulta = $this->consulta("select
                                    tes.Id,
                                    (select Nombre from cat_v3_x4d_ubicaciones WHERE Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema WHERE Id = tes.IdSistema) as Sistema,
                                    concat(
                                            (select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),
                                            ' - ',
                                            cxe.Nombre
                                    ) as Elemento,                                    
                                    Serie,
                                    ClaveCinemex,
                                    Imagen
                                    from t_elementos_salas4D tes inner join cat_v3_x4d_elementos cxe
                                    on tes.IdElemento = cxe.Id
                                    where IdSucursal = '" . $id . "' and tes.Flag = 1;");
        return $consulta;
    }

    public function getSubelementosSucursal($id = "") {
        $consulta = $this->consulta("select
                                    tss.Id,
                                    (select Nombre from cat_v3_x4d_ubicaciones WHERE Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema WHERE Id = tes.IdSistema) as Sistema,
                                    concat(
                                            (select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),
                                            ' - ',
                                            cxe.Nombre
                                    ) as Elemento,                                    
                                    concat(	
                                            cxs.Nombre,
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxs.IdMarca)

                                    ) as Subelemento,  
                                    tss.Serie,
                                    tss.ClaveCinemex,
                                    tss.Imagen
                                    from t_elementos_salas4D tes 
                                    inner join t_subelementos_salas4D tss on tes.Id = tss.IdRegistroElemento
                                    inner join cat_v3_x4d_elementos cxe on tes.IdElemento = cxe.Id
                                    inner join cat_v3_x4d_subelementos cxs on tss.IdSubelemento = cxs.Id

                                    where IdSucursal = '" . $id . "' and tes.Flag = 1 and tss.Flag = 1");
        return $consulta;
    }

    public function getUbicaciones($id = '') {
        $condicion = ($id != '') ? ' WHERE Id = "' . $id . '"' : ' WHERE Flag = 1 ';
        $consulta = $this->consulta('select Id, Nombre from cat_v3_x4d_ubicaciones ' . $condicion . ' order by Nombre');
        return $consulta;
    }

    public function getSistemas($id = '') {
        $condicion = ($id != '') ? ' WHERE Id = "' . $id . '"' : ' WHERE Flag = 1 ';
        $consulta = $this->consulta('select Id, Nombre from cat_v3_x4d_tipos_sistema ' . $condicion . ' order by Nombre');
        return $consulta;
    }

    public function getElementos($id = '') {
        $condicion = ($id != '') ? ' WHERE Id = "' . $id . '"' : ' WHERE Flag = 1 ';
        $consulta = $this->consulta("select 
                                    Id, 
                                    concat(
                                            (select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),
                                            ' - ',
                                            Nombre
                                    ) as Nombre from cat_v3_x4d_elementos cxe " . $condicion . " order by Nombre");
        return $consulta;
    }

    public function insertaElementos($data) {
        $this->iniciaTransaccion();
        $return_array = [
            'code' => 500,
            'ids' => []
        ];
        foreach ($data['data'] as $key => $value) {
            $fecha = $this->consulta("select now() as Fecha;");

            $this->insertar("t_elementos_salas4d", [
                "IdUsuario" => $this->usuario['Id'],
                "IdSucursal" => $data['sucursal'],
                "IdUbicacion" => $value['ubicacion'],
                "IdSistema" => $value['sistema'],
                "IdElemento" => $value['elemento'],
                "Serie" => $value['serie'],
                "ClaveCinemex" => $value['clave'],
                "Imagen" => $data['images'],
                "FechaCaptura" => $fecha[0]['Fecha']]
            );
            array_push($return_array['ids'], $this->connectDBPrueba()->insert_id());
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            $return_array['ids'] = [];
        } else {
            $this->commitTransaccion();
            $return_array['code'] = 200;
        }
        return $return_array;
    }

    public function insertaSublemento($data, $imagenes = '') {
        $id = '';
        $this->iniciaTransaccion();

        $fecha = $this->consulta("select now() as Fecha;");

        $this->insertar("t_subelementos_salas4D", [
            "IdUsuario" => $this->usuario['Id'],
            "IdRegistroElemento" => $data['elemento'],
            "IdSubelemento" => $data['subelemento'],
            "Serie" => $data['serie'],
            "ClaveCinemex" => $data['clave'],
            "Imagen" => $imagenes,
            "FechaCaptura" => $fecha[0]['Fecha']]
        );

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $id = $this->connectDBPrueba()->insert_id();
            $this->commitTransaccion();
        }

        return $id;
    }

    public function guardaCambiosElemento($data, $imagenes = '') {
        $fecha = $this->consulta("select now() as Fecha;");
        if (!$this->connectDBPrueba()->simple_query(""
                        . "update t_elementos_salas4d "
                        . "set IdUsuario = '" . $this->usuario['Id'] . "', "
                        . "IdSucursal = '" . $data['sucursal'] . "', "
                        . "IdUbicacion = '" . $data['ubicacion'] . "', "
                        . "IdSistema = '" . $data['sistema'] . "', "
                        . "IdElemento = '" . $data['elemento'] . "', "
                        . "Serie = '" . $data['serie'] . "', "
                        . "ClaveCinemex = '" . $data['clave'] . "', "
                        . "Imagen = '" . $imagenes . "', "
                        . "FechaCaptura = '" . $fecha[0]['Fecha'] . "' "
                        . "where Id = '" . $data['registro'] . "';")) {
            return ['code' => 500, 'error' => $this->connectDBPrueba()->error()];
        } else {
            return ['code' => 200, 'error' => ''];
        }
    }

    public function getElementosRegistrados($ids = '0') {
        $consulta = $this->consulta("select 
                                    tes.Id,
                                    tes.IdElemento,
                                    concat(
                                            (select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),
                                            ' - ',
                                            Nombre,
                                            ' (Serie: ',
                                            if(tes.Serie <> '', tes.Serie, 'S/N'),
                                            ', Clave: ',
                                            if(tes.ClaveCinemex <> '', tes.ClaveCinemex, 'S/N'),
                                            ')'
                                    ) as Nombre

                                    from t_elementos_salas4d tes inner join cat_v3_x4d_elementos cxe
                                    on tes.IdElemento = cxe.Id
                                    where tes.Id in (" . $ids . ") and tes.Flag = 1;");
        return $consulta;
    }

    public function getCatalogoSublementosByRegistro($idRegistro = '') {
        $consulta = $this->consulta("select 
                                    cxs.Id,
                                    concat(cxs.Nombre,' - ',(select Nombre from cat_v3_x4d_marcas where Id = cxs.IdMarca)) as Nombre
                                    from cat_v3_x4d_subelementos cxs 
                                    where IdElemento = (select IdElemento from t_elementos_salas4d where Id = '" . $idRegistro . "');");
        return $consulta;
    }

    public function getSubelementosByRegistro($idRegistro = '') {
        $consulta = $this->consulta("select 
                                    tss.Id,
                                    subelementoSucursal(tss.Id) as Subelemento,
                                    tss.Serie,
                                    tss.ClaveCinemex,
                                    tss.Imagen
                                    from t_subelementos_salas4D tss 
                                    where tss.IdRegistroElemento = '" . $idRegistro . "' and tss.Flag = 1
                                    ORDER BY tss.FechaCaptura desc; ");
        return $consulta;
    }

    public function getDetallesElemento($idRegistro = '') {
        $consulta = $this->consulta("select 
                                    tes.*,
                                    (select Nombre from cat_v3_x4d_ubicaciones where Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema where Id = tes.IdSistema) as Sistema,
                                    (select                                     
                                        concat(
                                            Nombre,                                            
                                            ' - ',
                                            (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca)
                                        ) as Nombre from cat_v3_x4d_elementos cxe
                                        where cxe.Id = tes.IdElemento
                                    ) as Elemento                                    
                                    from t_elementos_salas4d  tes
                                    where tes.Id = '" . $idRegistro . "';");
        return $consulta;
    }

    public function getDetallesSubelemento($idRegistro = '') {
        $consulta = $this->consulta("select
                                    tss.Id,
                                    (select Nombre from cat_v3_x4d_ubicaciones WHERE Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema WHERE Id = tes.IdSistema) as Sistema,
                                    concat(
                                                                    (select Nombre from cat_v3_x4d_equipos where Id = cxe.IdEquipo),
                                                                    ' - ',
                                                                    (select Nombre from cat_v3_x4d_marcas where Id = cxe.IdMarca),
                                                                    ' - ',
                                                                    cxe.Nombre
                                    ) as Elemento,                                    
                                    concat(	
                                                                    cxs.Nombre,
                                                                    ' - ',
                                                                    (select Nombre from cat_v3_x4d_marcas where Id = cxs.IdMarca)

                                    ) as Subelemento,  
                                    tss.Serie,
                                    tss.ClaveCinemex,
                                    tss.Imagen
                                    from t_elementos_salas4D tes 
                                    inner join t_subelementos_salas4D tss on tes.Id = tss.IdRegistroElemento
                                    inner join cat_v3_x4d_elementos cxe on tes.IdElemento = cxe.Id
                                    inner join cat_v3_x4d_subelementos cxs on tss.IdSubelemento = cxs.Id

                                    where tss.Id = '" . $idRegistro . "';");
        return $consulta;
    }

    public function eliminarSubelemento($idRegistro = '') {
        $fecha = $this->consulta("select now() as Fecha;");
        if (!$this->connectDBPrueba()->simple_query(""
                        . "update t_subelementos_salas4D "
                        . "set Flag = 0, "
                        . "FechaElimina = '" . $fecha[0]['Fecha'] . "', "
                        . "UsuarioElimina = '" . $this->usuario['Id'] . "'"
                        . "where Id = '" . $idRegistro . "';")) {
            return ['code' => 500, 'error' => $this->connectDBPrueba()->error()];
        } else {
            return ['code' => 200, 'error' => ''];
        }
    }

    public function eliminarElemento($idRegistro = '') {
        $fecha = $this->consulta("select now() as Fecha;");
        if (!$this->connectDBPrueba()->simple_query(""
                        . "update t_elementos_salas4d "
                        . "set Flag = 0, "
                        . "FechaElimina = '" . $fecha[0]['Fecha'] . "', "
                        . "UsuarioElimina = '" . $this->usuario['Id'] . "'"
                        . "where Id = '" . $idRegistro . "';")) {
            return ['code' => 500, 'error' => $this->connectDBPrueba()->error()];
        } else {
            return ['code' => 200, 'error' => ''];
        }
    }

    public function actualizaImagenesElemento($elemento, $imagenes) {
        $fecha = $this->consulta("select now() as Fecha;");
        if (!$this->connectDBPrueba()->simple_query(""
                        . "update t_elementos_salas4d "
                        . "set IdUsuario = '" . $this->usuario['Id'] . "', "
                        . "FechaCaptura = '" . $fecha[0]['Fecha'] . "', "
                        . "Imagen = '" . $imagenes . "' "
                        . "where Id = '" . $elemento . "';")) {
            return ['code' => 500, 'error' => $this->connectDBPrueba()->error()];
        } else {
            return ['code' => 200, 'error' => ''];
        }
    }

    public function getActividadesAutorizadasManttoSalas4D($servicio = '') {
        $consulta = $this->consulta("select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $servicio . "' and Flag = 1;");
        return $consulta;
    }

    public function getActividadesSeguimientoSalas4D(string $servicio = '') {
        $consulta = $this->consulta("select                                    
                                    aut.Id,
                                    aut.IdSistema,
                                    aut.IdPadre,
                                    (select Nombre from cat_v3_actividades_mantto_salas4d where Id = aut.IdPadre) as ActividadPadre,
                                    aut.Nombre as Actividad, 
                                    if(tsma.IdEstatus is not null, estatus(tsma.IdEstatus), estatus(1)) as Estatus,
                                    if(tsma.IdEstatus is not null, tsma.IdEstatus, 1) as IdEstatus,
                                    tsma.IdAtiende,
                                    nombreUsuario(tsma.IdAtiende) NombreAtiende,
                                    tsma.Fecha,
                                    tsma.Id AS IdManttoActividades
                                    from (
                                            select 
                                            *
                                            from cat_v3_actividades_mantto_salas4d
                                            where concat(',',(select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $servicio . "' and Flag = 1),',') REGEXP (concat(',',Id,','))
                                            ) as aut
                                    LEFT JOIN t_salas4d_mantto_actividades tsma on IdServicio = '" . $servicio . "' and aut.Id = tsma.IdActividad
                                    ");
        return $consulta;
    }

    public function getActividadesSeguimientoActividadesSalas4(string $servicio = '') {
        $consulta = $this->consulta("select                                    
                                    aut.Id,
                                    aut.IdSistema,
                                    aut.IdPadre,
                                    (select Nombre from cat_v3_actividades_mantto_salas4d where Id = aut.IdPadre) as ActividadPadre,
                                    aut.Nombre as Actividad, 
                                    if(tsma.IdEstatus is not null, estatus(tsma.IdEstatus), estatus(1)) as Estatus,
                                    if(tsma.IdEstatus is not null, tsma.IdEstatus, 1) as IdEstatus,
                                    tsma.IdAtiende,
                                    nombreUsuario(tsma.IdAtiende) NombreAtiende,
                                    tsma.Fecha,
                                    tsma.Id AS IdManttoActividades
                                    from (
                                            select 
                                            *
                                            from cat_v3_actividades_mantto_salas4d
                                            where concat(',',(select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $servicio . "' and Flag = 1),',') REGEXP (concat(',',Id,','))
                                            ) as aut
                                    INNER JOIN t_salas4d_mantto_actividades tsma on IdServicio = '" . $servicio . "' and aut.Id = tsma.IdActividad
                                    WHERE IdEstatus IN (1,2)
                                    ");
        return $consulta;
    }

    public function getActividadesSeguimientoActividadesSalas4Usuario(string $servicio = '', string $usuario) {
        $consulta = $this->consulta("select                                    
                                    aut.Id,
                                    aut.IdSistema,
                                    aut.IdPadre,
                                    (select Nombre from cat_v3_actividades_mantto_salas4d where Id = aut.IdPadre) as ActividadPadre,
                                    aut.Nombre as Actividad, 
                                    if(tsma.IdEstatus is not null, estatus(tsma.IdEstatus), estatus(1)) as Estatus,
                                    if(tsma.IdEstatus is not null, tsma.IdEstatus, 1) as IdEstatus,
                                    tsma.IdAtiende,
                                    nombreUsuario(tsma.IdAtiende) NombreAtiende,
                                    tsma.Fecha,
                                    tsma.Id AS IdManttoActividades
                                    from (
                                            select 
                                            *
                                            from cat_v3_actividades_mantto_salas4d
                                            where concat(',',(select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $servicio . "' and Flag = 1),',') REGEXP (concat(',',Id,','))
                                            ) as aut
                                    INNER JOIN t_salas4d_mantto_actividades tsma on IdServicio = '" . $servicio . "' and aut.Id = tsma.IdActividad
                                    WHERE tsma.IdAtiende = '" . $usuario . "
                                    AND WHERE IdEstatus IN (1,2)'
                                    ");
        return $consulta;
    }

    public function getUsuariosDepartamento() {
        $consulta = $this->consulta("select 
                                    Id,
                                    nombreUsuario(Id) as Nombre
                                    from cat_v3_usuarios 
                                    where IdPerfil in (select Id from cat_perfiles where IdDepartamento = 7)");
        return $consulta;
    }

    /* Comienzan metodos para Dashboard Salas X4D */

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

        $consulta = $this->consulta("select 
                                    ts.IdEstatus as Id,
                                    estatus(ts.IdEstatus) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts 
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6
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

        $consulta = $this->consulta("select 
                                    if(ts.IdPrioridad = 0, 1, ts.IdPrioridad) as Id,
                                    prioridad(ts.IdPrioridad) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts 
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6
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

        $consulta = $this->consulta("select 
                                    tst.IdTipoServicio as Id,
                                    tipoServicio(tst.IdTipoServicio) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6
                                    and tst.IdEstatus <> 6
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
                                    and ts.IdDepartamento = 7 
                                    and ts.IdEstatus <> 6;");

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
        return $consulta[0]['Sucursal'];
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
            $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }


        $consulta = $this->consulta("select 
                                    tst.IdEstatus as Id,
                                    estatus(tst.IdEstatus) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6 
                                    and tst.IdEstatus <> 6  
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
            $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }

        $consulta = $this->consulta("select 
                                    if(tst.IdSucursal is null or tst.IdSucursal = 0,'NA',tst.IdSucursal) as Id,
                                    if(tst.IdSucursal is null or tst.IdSucursal = 0,'Sin Sucursal',cap_first(sucursal(tst.IdSucursal))) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6 
                                    and tst.IdEstatus <> 6
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
            $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
        }

        if ($atiende !== '') {
            $condicion .= " and tst.Atiende = '" . $atiende . "'";
        }

        $consulta = $this->consulta("select 
                                    tst.Atiende as Id,
                                    nombreUsuario(tst.Atiende) as Nombre,
                                    count(*) as Total
                                    from t_solicitudes ts inner join t_servicios_ticket tst
                                    on ts.Id = tst.IdSolicitud
                                    where " . $condicion . "
                                    and ts.IdDepartamento = 7
                                    and ts.IdEstatus <> 6
                                    and tst.IdEstatus <> 6
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
            $condicion .= " and tst.IdSucursal = '" . $sucursal . "' ";
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
                                     and ts.IdEstatus <> 6
                                    and tst.IdEstatus <> 6    
                                    and ts.IdDepartamento = 7;");

        return $consulta;
    }

    /* Terminan metodos para Dashboard Salas X4D */

    public function insertaActividadesTransaccion(array $arraydatos) {
        $this->iniciaTransaccion();

        $consulta = $this->connectDBPrueba()->simple_query("UPDATE t_actividades_autorizadas_salas4d "
                . "SET Flag = 0 "
                . "WHERE  IdServicio ='" . $arraydatos['IdServicio'] . "'");

        $this->insertar("t_actividades_autorizadas_salas4d", array(
            "IdServicio" => $arraydatos['IdServicio'],
            "IdUsuario" => $arraydatos['IdUsuario'],
            "Actividades" => $arraydatos['Actividades'],
            "Fecha" => $arraydatos['Fecha'],
            'Flag' => '1'
                )
        );
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['estatus' => false];
        } else {
            $this->terminaTransaccion();
            return ['estatus' => true];
        }
    }

    public function insertaActividadesTransaccionids(array $arraydataids) {
        $this->iniciaTransaccion();
        $idPadre = $arraydataids['IdActividad'];
        $consulta = $this->consulta("SELECT * FROM t_salas4d_mantto_actividades WHERE IdActividad = '" . $arraydataids['IdActividad'] . "' and IdServicio = '" . $arraydataids['IdServicio'] . "'");

        if (!empty($consulta)) {
            $consultaActualizar = $this->actualizar("t_salas4d_mantto_actividades", array(
                "IdAtiende" => $arraydataids['IdAtiende'],
                "Fecha" => $arraydataids['Fecha']
                    ), array('Id' => $consulta[0]['Id']));
        } else {
            $this->insertar("t_salas4d_mantto_actividades", array(
                "IdServicio" => $arraydataids['IdServicio'],
                "IdAtiende" => $arraydataids['IdAtiende'],
                "IdUsuario" => $arraydataids['IdUsuario'],
                "IdActividad" => $arraydataids['IdActividad'],
                "Fecha" => $arraydataids['Fecha']
                    )
            );
        }

        $consulta = $this->consulta("
                                select 
                                *
                                from cat_v3_actividades_mantto_salas4d
                                where concat(',',(select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $arraydataids['IdServicio'] . "' and Flag = 1),',') REGEXP (concat(',',Id,','))
                                AND IdPadre= '" . $idPadre . "'");
        if (!empty($consulta)) {

            foreach ($consulta as $key => $value) {
                $IdHijo = $value['Id'];
                $consultaHijos = $this->consulta("SELECT * FROM t_salas4d_mantto_actividades WHERE IdActividad = '" . $value['Id'] . "' and IdServicio = '" . $arraydataids['IdServicio'] . "' AND IdEstatus IN (1,2)");

                if (!empty($consultaHijos)) {
                    $consultaActualizarHijos = $this->actualizar("t_salas4d_mantto_actividades", array(
                        "IdAtiende" => $arraydataids['IdAtiende'],
                        "Fecha" => $arraydataids['Fecha']
                            ), array('Id' => $consultaHijos[0]['Id']));
                } else {
                    $this->insertar("t_salas4d_mantto_actividades", array(
                        "IdServicio" => $arraydataids['IdServicio'],
                        "IdAtiende" => $arraydataids['IdAtiende'],
                        "IdUsuario" => $arraydataids['IdUsuario'],
                        "IdActividad" => $value['Id'],
                        "Fecha" => $arraydataids['Fecha']
                            )
                    );
                }

                $consulta2 = $this->consulta("
                                select 
                                *
                                from cat_v3_actividades_mantto_salas4d
                                where concat(',',(select Actividades from t_actividades_autorizadas_salas4d where IdServicio = '" . $arraydataids['IdServicio'] . "' and Flag = 1),',') REGEXP (concat(',',Id,','))
                                AND IdPadre= '" . $IdHijo . "'");

                if (!empty($consulta2)) {
                    foreach ($consulta2 as $key2 => $value2) {
                        $consultaNietos = $this->consulta("SELECT * FROM t_salas4d_mantto_actividades WHERE IdActividad = '" . $value2['Id'] . "' and IdServicio = '" . $arraydataids['IdServicio'] . "' AND IdEstatus IN (1,2)");

                        if (!empty($consultaNietos)) {

                            $this->actualizar("t_salas4d_mantto_actividades", array(
                                "IdAtiende" => $arraydataids['IdAtiende'],
                                "Fecha" => $arraydataids['Fecha']
                                    ), array('Id' => $consultaNietos[0]['Id']));
                        } else {

                            $this->insertar("t_salas4d_mantto_actividades", array(
                                "IdServicio" => $arraydataids['IdServicio'],
                                "IdAtiende" => $arraydataids['IdAtiende'],
                                "IdUsuario" => $arraydataids['IdUsuario'],
                                "IdActividad" => $value2['Id'],
                                "Fecha" => $arraydataids['Fecha']
                                    )
                            );
                        }
                    }
                }
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function insertaMantenimientoGeneral(array $arrayDatos) {
        $this->iniciaTransaccion();

        if ($arrayDatos['IdElemento'] === '' && $arrayDatos['IdSubelemento'] === '') {
            $this->insertar("t_salas4d_mantto_actividades_avances", array(
                "IdActividad" => $arrayDatos['IdActividad'],
                "IdUsuario" => $arrayDatos['IdUsuario'],
                "Fecha" => $arrayDatos['Fecha'],
                "Observaciones" => $arrayDatos['Observaciones'],
                "Archivos" => $arrayDatos['Archivos'],
                "IdUbicacion" => $arrayDatos['IdUbicacion']
                    )
            );
        } else {
            $this->insertar("t_salas4d_mantto_actividades_avances", array(
                "IdActividad" => $arrayDatos['IdActividad'],
                "IdUsuario" => $arrayDatos['IdUsuario'],
                "Fecha" => $arrayDatos['Fecha'],
                "Observaciones" => $arrayDatos['Observaciones'],
                "Archivos" => $arrayDatos['Archivos'],
                "IdUbicacion" => $arrayDatos['IdUbicacion'],
                "IdRegistroElemento" => $arrayDatos['IdElemento'],
                "IdRegistroSubelemento" => $arrayDatos['IdSubelemento']
                    )
            );
        }

        $idActividadesAvences = $this->connectDBPrueba()->insert_id();

        if (!empty($arrayDatos['DatosProductos'])) {
            foreach ($arrayDatos['DatosProductos'] as $key => $value) {
                $consultaInventario = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $value[5] . "'");

                $this->insertar("t_salas4d_mantto_actividades_avance_productos", array(
                    "IdRegistroAvance" => $idActividadesAvences,
                    "IdRegistroInventario" => $consultaInventario[0]['Id'],
                    "Cantidad" => $value[4]
                        )
                );

                $cantidadInvetario = $consultaInventario[0]['Cantidad'];
                $cantidadAcences = $value[4];
                $resultadoCantidad = $cantidadInvetario - $cantidadAcences;

                $this->actualizar('t_inventario', array(
                    'Cantidad' => $resultadoCantidad
                        ), array('Id' => $consultaInventario[0]['Id']));

                $this->insertar("t_movimientos_inventario", array(
                    "IdTipoMovimiento" => '4',
                    "IdServicio" => $arrayDatos['IdServicio'],
                    "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                    "IdTipoProducto" => $consultaInventario[0]['IdTipoProducto'],
                    "IdProducto" => $consultaInventario[0]['IdProducto'],
                    "IdEstatus" => $consultaInventario[0]['IdEstatus'],
                    "IdUsuario" => $arrayDatos['IdUsuario'],
                    "Cantidad" => $value[4],
                    "Serie" => $consultaInventario[0]['Serie'],
                    "Fecha" => $arrayDatos['Fecha'],
                        )
                );

                $idMovimientoInvetario = $this->connectDBPrueba()->insert_id();
                $consultaAlmacenesVirtuales = $this->consulta("SELECT 
                                                        Id 
                                                    FROM cat_v3_almacenes_virtuales 
                                                    WHERE IdTipoAlmacen = 3 
                                                    AND IdReferenciaAlmacen= '" . $arrayDatos['IdSucursal'] . "'");

                $this->insertar("t_movimientos_inventario", array(
                    "IdMovimientoEnlazado" => $idMovimientoInvetario,
                    "IdTipoMovimiento" => '5',
                    "IdServicio" => $arrayDatos['IdServicio'],
                    "IdAlmacen" => $consultaAlmacenesVirtuales[0]['Id'],
                    "IdTipoProducto" => $consultaInventario[0]['IdTipoProducto'],
                    "IdProducto" => $consultaInventario[0]['IdProducto'],
                    "IdEstatus" => $consultaInventario[0]['IdEstatus'],
                    "IdUsuario" => $arrayDatos['IdUsuario'],
                    "Cantidad" => $value[4],
                    "Serie" => $consultaInventario[0]['Serie'],
                    "Fecha" => $arrayDatos['Fecha'],
                        )
                );

                if ($arrayDatos['IdElemento'] !== '' && $arrayDatos['IdSubelemento'] === '') {
                    $this->insertar("t_elementos_salas4d", array(
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "IdSucursal" => $arrayDatos['IdSucursal'],
                        "IdUbicacion" => $arrayDatos['IdUbicacion'],
                        "IdSistema" => $arrayDatos['IdSistema'],
                        "IdElemento" => $consultaInventario[0]['IdProducto'],
                        "Serie" => $consultaInventario[0]['Serie'],
                        "FechaCaptura" => $arrayDatos['Fecha'],
                        "Flag" => '1'
                            )
                    );

                    $this->actualizar('t_elementos_salas4d', array(
                        'Flag' => '0',
                        'UsuarioElimina' => $arrayDatos['IdUsuario'],
                        'FechaElimina' => $arrayDatos['Fecha']
                            ), array('Id' => $arrayDatos['IdElemento']));

                    $consultaElementosSalas = $this->consulta("SELECT 
                                                        *
                                                    FROM t_elementos_salas4d
                                                    WHERE Id = '" . $arrayDatos['IdElemento'] . "'");

                    $this->insertar("t_inventario", array(
                        "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                        "IdTipoProducto" => $consultaInventario[0]['IdTipoProducto'],
                        "IdProducto" => $consultaElementosSalas[0]['IdElemento'],
                        "IdEstatus" => '22',
                        "Cantidad" => $value[4],
                        "Serie" => $consultaElementosSalas[0]['Serie']
                            )
                    );

                    $idInventario = $this->connectDBPrueba()->insert_id();

                    $consultaInventario2 = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $idInventario . "'");

                    $this->insertar("t_movimientos_inventario", array(
                        "IdTipoMovimiento" => '4',
                        "IdServicio" => $arrayDatos['IdServicio'],
                        "IdAlmacen" => $consultaAlmacenesVirtuales[0]['Id'],
                        "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
                        "IdProducto" => $consultaInventario2[0]['IdProducto'],
                        "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "Cantidad" => $value[4],
                        "Serie" => $consultaElementosSalas[0]['Serie'],
                        "Fecha" => $arrayDatos['Fecha'],
                            )
                    );

                    $idMovimientoInvetario2 = $this->connectDBPrueba()->insert_id();

                    $this->insertar("t_movimientos_inventario", array(
                        "IdMovimientoEnlazado" => $idMovimientoInvetario2,
                        "IdTipoMovimiento" => '5',
                        "IdServicio" => $arrayDatos['IdServicio'],
                        "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                        "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
                        "IdProducto" => $consultaInventario2[0]['IdProducto'],
                        "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "Cantidad" => $value[4],
                        "Serie" => $consultaElementosSalas[0]['Serie'],
                        "Fecha" => $arrayDatos['Fecha'],
                            )
                    );
                } else if ($arrayDatos['IdElemento'] !== '' && $arrayDatos['IdSubelemento'] !== '') {
                    $this->insertar("t_subelementos_salas4d", array(
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "IdRegistroElemento" => $arrayDatos['IdElemento'],
                        "IdSubelemento" => $consultaInventario[0]['IdProducto'],
                        "Serie" => $consultaInventario[0]['Serie'],
                        "FechaCaptura" => $arrayDatos['Fecha'],
                        "Flag" => '1'
                            )
                    );

                    $this->actualizar('t_subelementos_salas4d', array(
                        'Flag' => '0',
                        'UsuarioElimina' => $arrayDatos['IdUsuario'],
                        'FechaElimina' => $arrayDatos['Fecha']
                            ), array('Id' => $arrayDatos['IdSubelemento']));

                    $consultaSubelementosSalas = $this->consulta("SELECT 
                                                        *
                                                    FROM t_subelementos_salas4d
                                                    WHERE Id = '" . $arrayDatos['IdSubelemento'] . "'");

                    $this->insertar("t_inventario", array(
                        "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                        "IdTipoProducto" => $consultaInventario[0]['IdTipoProducto'],
                        "IdProducto" => $consultaSubelementosSalas[0]['IdSubelemento'],
                        "IdEstatus" => '22',
                        "Cantidad" => $value[4],
                        "Serie" => $consultaSubelementosSalas[0]['Serie']
                            )
                    );

                    $idInventario = $this->connectDBPrueba()->insert_id();

                    $consultaInventario2 = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $idInventario . "'");

                    $this->insertar("t_movimientos_inventario", array(
                        "IdTipoMovimiento" => '4',
                        "IdServicio" => $arrayDatos['IdServicio'],
                        "IdAlmacen" => $consultaAlmacenesVirtuales[0]['Id'],
                        "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
                        "IdProducto" => $consultaInventario2[0]['IdProducto'],
                        "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "Cantidad" => $value[4],
                        "Serie" => $consultaSubelementosSalas[0]['Serie'],
                        "Fecha" => $arrayDatos['Fecha'],
                            )
                    );

                    $idMovimientoInvetario2 = $this->connectDBPrueba()->insert_id();

                    $this->insertar("t_movimientos_inventario", array(
                        "IdMovimientoEnlazado" => $idMovimientoInvetario2,
                        "IdTipoMovimiento" => '5',
                        "IdServicio" => $arrayDatos['IdServicio'],
                        "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                        "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
                        "IdProducto" => $consultaInventario2[0]['IdProducto'],
                        "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
                        "IdUsuario" => $arrayDatos['IdUsuario'],
                        "Cantidad" => $value[4],
                        "Serie" => $consultaSubelementosSalas[0]['Serie'],
                        "Fecha" => $arrayDatos['Fecha'],
                            )
                    );
                }
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function consultaGeneral(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    public function getInformeActividades(int $datos, int $servicio) {
        $consulta = $this->consulta("SELECT 
                                            tsma.Id,
                                            tsma.IdServicio,
                                            tsmaa.*,
                                            nombreUsuario(tsmaa.IdUsuario) NombreUsuario,
                                            (SELECT Nombre FROM cat_v3_x4d_ubicaciones WHERE Id = tsmaa.IdUbicacion) Ubicación,
                                            if(tsmaa.IdRegistroElemento is null or tsmaa.IdRegistroElemento = 0,'', (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = tes.IdElemento)) Elemento,
                                            if(tsmaa.IdRegistroSubelemento is null or tsmaa.IdRegistroSubelemento = 0,'', (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = tss.IdSubelemento)) Subelemento
                                    FROM t_salas4d_mantto_actividades_avances tsmaa
                                    INNER JOIN t_salas4d_mantto_actividades tsma
                                    ON tsmaa.IdActividad = tsma.Id
                                    LEFT JOIN t_elementos_salas4d tes
                                    ON tes.Id = tsmaa.IdRegistroElemento
                                    LEFT JOIN t_subelementos_salas4d tss
                                    ON tss.Id = tsmaa.IdRegistroSubelemento
                                    WHERE tsma.IdActividad = '" . $datos . "'
                                    and tsma.IdServicio = '" . $servicio . "'
                                    ORDER BY tsmaa.Fecha DESC");
        return $consulta;
    }

    public function getProductosInforme(int $dato) {
        $consulta = $this->consulta("SELECT 
                                            tsmaap.Id,
                                            (SELECT Nombre FROM cat_v3_tipos_producto_inventario WHERE Id = ti.IdTipoProducto) TipoProducto,
                                            CASE ti.IdTipoProducto
                                                    WHEN 3 THEN (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = ti.IdProducto) 
                                            WHEN 4 THEN (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = ti.IdProducto)
                                            WHEN 5 THEN (SELECT Nombre FROM cat_v3_equipos_sae WHERE Id = ti.IdProducto) 
                                            END as Producto,
                                            ti.Serie,
                                            tsmaap.Cantidad
                                    FROM t_salas4d_mantto_actividades_avance_productos tsmaap
                                    INNER JOIN t_inventario ti
                                    ON tsmaap.IdRegistroInventario = ti.Id 
                                    WHERE IdRegistroAvance = '" . $dato . "'");
        return $consulta;
    }

    public function concluirServicio(array $dato) {

        $this->actualizar('t_servicios_ticket', array(
            'IdEstatus' => $dato['Estatus'],
            'FechaConclusion' => $dato['FechaConclusion'],
            'Firma' => $dato['Firma'],
            'NombreFirma' => $dato['NombreFirma'],
            'CorreoCopiaFirma' => $dato['CorreoCopiaFirma'],
            'FechaFirma' => $dato['FechaFirma']
                ), array('Id' => $dato['servicio']));

        return $this->consulta("SELECT * FROM t_servicios_ticket where Id = '" . $dato['servicio'] . "'");
    }

    public function getInformacionServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "tst.Ticket, "
                . "if(tst.IdSucursal is not null and tst.IdSucursal > 0, sucursal(tst.IdSucursal),'') as Sucursal, "
                . "tst.IdTipoServicio, "
                . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
                . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
                . "tst.FechaCreacion as FechaServicio, "
                . "estatus(tst.IdEstatus) as EstatusServicio, "
                . "tst.Descripcion as DescripcionServicio, "
                . "nombreUsuario(tst.Atiende) as AtiendeServicio, "
                . "case "
                . " when ts.IdEstatus in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
                . " when ts.IdEstatus in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
                . "end as TiempoSolicitud, "
                . ""
                . "case "
                . " when tst.IdEstatus  in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
                . " when tst.IdEstatus  in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
                . "end as TiempoServicio "
                . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
                . "on tst.IdSolicitud = ts.Id "
                . "where tst.Id = '" . $servicio . "'";
        return $this->consultaGeneral($sentencia);
    }

    public function getGeneralesSolicitudServicio(string $servicio) {
        $sentencia = ""
                . "select ts.Id as Solicitud, "
                . "ts.Folio, "
                . "tst.Id as Servicio, "
                . "nombreUsuario(ts.Solicita) as Solicitante, "
                . "ts.FechaCreacion as FechaSolicitud, "
                . "(select Nombre from cat_v3_departamentos_siccob where Id = ts.IdDepartamento) as DepartamentoSolicitud, "
                . "(select cvas.Nombre from cat_v3_departamentos_siccob cvs INNER JOIN cat_v3_areas_siccob cvas ON cvas.Id = cvs.IdArea where cvs.Id = ts.IdDepartamento) as AreaSolicitud, "
                . "estatus(ts.IdEstatus) as EstatusSolicitud, "
                . "(select Asunto from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as AsuntoSolicitud, "
                . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
                . "(select Nombre from cat_v3_prioridades where Id = ts.IdPrioridad) as Prioridad, "
                . "tst.Ticket, "
                . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
                . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
                . "if(
                            tst.IdSucursal is not null and tst.IdSucursal > 0, 
                        sucursal(tst.IdSucursal), 
                            case tst.IdTipoServicio
                                    when 11 then sucursal((select IdSucursal from t_censos_generales where IdServicio = tst.Id order by Id desc limit 1))
                            when 12 then sucursal((select IdSucursal from t_mantenimientos_generales where IdServicio = tst.Id order by Id desc limit 1))
                            end
                    ) as Sucursal, "
                . "tst.FechaCreacion as FechaServicio, "
                . "tst.FechaInicio, "
                . "if(tst.FechaFirma is not null and tst.FechaFirma <> '', tst.FechaFirma, tst.FechaConclusion) as FechaConclusion, "
                . "estatus(tst.IdEstatus) as EstatusServicio, "
                . "tst.Descripcion as DescripcionServicio, "
                . "tst.Firma, "
                . "tst.FirmaTecnico, "
                . "tst.NombreFirma, "
                . "tst.CorreoCopiaFirma, "
                . "tst.FechaFirma, "
                . "nombreUsuario(tst.Atiende) as AtiendeServicio, "
                . "tst.Atiende, "
                . "case "
                . " when ts.IdEstatus in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
                . " when ts.IdEstatus in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
                . "end as TiempoSolicitud, "
                . ""
                . "case "
                . " when tst.IdEstatus  in (4,'4') then "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
                . " when tst.IdEstatus  in (6,'6') then "
                . "     '' "
                . " else "
                . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
                . "end as TiempoServicio "
                . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
                . "on tst.IdSolicitud = ts.Id "
                . "where tst.Id = '" . $servicio . "';";
        $detallesSolicitud = $this->consultaGeneral($sentencia);

        $arrayReturn = array();
        if (array_key_exists(0, $detallesSolicitud)) {
            $arrayReturn['solicitud'] = ($detallesSolicitud[0]['Solicitud'] !== '') ? $detallesSolicitud[0]['Solicitud'] : 'Sin Información';
            $arrayReturn['servicio'] = ($detallesSolicitud[0]['Servicio'] !== '') ? $detallesSolicitud[0]['Servicio'] : 'Sin Información';
            $arrayReturn['folio'] = ($detallesSolicitud[0]['Folio'] !== '') ? $detallesSolicitud[0]['Folio'] : 'Sin Información';
            $arrayReturn['solicitante'] = ($detallesSolicitud[0]['Solicitante'] !== '') ? $detallesSolicitud[0]['Solicitante'] : 'Sin Información';
            $arrayReturn['fechaSolicitud'] = ($detallesSolicitud[0]['FechaSolicitud'] !== '') ? $detallesSolicitud[0]['FechaSolicitud'] : 'Sin Información';
            $arrayReturn['departamentoSolcitud'] = ($detallesSolicitud[0]['DepartamentoSolicitud'] !== '') ? $detallesSolicitud[0]['DepartamentoSolicitud'] : 'Sin Información';
            $arrayReturn['areaSolicitud'] = ($detallesSolicitud[0]['AreaSolicitud'] !== '') ? $detallesSolicitud[0]['AreaSolicitud'] : 'Sin Información';
            $arrayReturn['estatusSolicitud'] = ($detallesSolicitud[0]['EstatusSolicitud'] !== '') ? $detallesSolicitud[0]['EstatusSolicitud'] : 'Sin Información';
            $arrayReturn['asuntoSolicitud'] = ($detallesSolicitud[0]['AsuntoSolicitud'] !== '') ? $detallesSolicitud[0]['AsuntoSolicitud'] : 'Sin Información';
            $arrayReturn['descripcionSolicitud'] = ($detallesSolicitud[0]['DescripcionSolicitud'] !== '') ? $detallesSolicitud[0]['DescripcionSolicitud'] : 'Sin Información';
            $arrayReturn['prioridad'] = ($detallesSolicitud[0]['Prioridad'] !== '') ? $detallesSolicitud[0]['Prioridad'] : 'Sin Información';
            $arrayReturn['ticket'] = ($detallesSolicitud[0]['Ticket'] !== '') ? $detallesSolicitud[0]['Ticket'] : 'Sin Información';
            $arrayReturn['sucursal'] = ($detallesSolicitud[0]['Sucursal'] !== '') ? $detallesSolicitud[0]['Sucursal'] : 'Sin Información';
            $arrayReturn['tipoServicio'] = ($detallesSolicitud[0]['TipoServicio'] !== '') ? $detallesSolicitud[0]['TipoServicio'] : 'Sin Información';
            $arrayReturn['fechaServicio'] = ($detallesSolicitud[0]['FechaServicio'] !== '') ? $detallesSolicitud[0]['FechaServicio'] : 'Sin Información';
            $arrayReturn['fechaInicio'] = ($detallesSolicitud[0]['FechaInicio'] !== '') ? $detallesSolicitud[0]['FechaInicio'] : 'Sin Información';
            $arrayReturn['fechaConclusion'] = ($detallesSolicitud[0]['FechaConclusion'] !== '') ? $detallesSolicitud[0]['FechaConclusion'] : 'Sin Información';
            $arrayReturn['estatusServicio'] = ($detallesSolicitud[0]['EstatusServicio'] !== '') ? $detallesSolicitud[0]['EstatusServicio'] : 'Sin Información';
            $arrayReturn['descripcionServicio'] = ($detallesSolicitud[0]['DescripcionServicio'] !== '') ? $detallesSolicitud[0]['DescripcionServicio'] : 'Sin Información';
            $arrayReturn['firma'] = ($detallesSolicitud[0]['Firma'] !== NULL) ? $detallesSolicitud[0]['Firma'] : 'Sin Información';
            $arrayReturn['firmaTecnico'] = ($detallesSolicitud[0]['FirmaTecnico'] !== NULL) ? $detallesSolicitud[0]['FirmaTecnico'] : 'Sin Información';
            $arrayReturn['nombreFirma'] = ($detallesSolicitud[0]['NombreFirma'] !== NULL) ? $detallesSolicitud[0]['NombreFirma'] : 'Sin Información';
            $arrayReturn['correoCopiaFirma'] = ($detallesSolicitud[0]['CorreoCopiaFirma'] !== NULL) ? $detallesSolicitud[0]['CorreoCopiaFirma'] : 'Sin Información';
            $arrayReturn['fechaFirma'] = ($detallesSolicitud[0]['FechaFirma'] !== NULL) ? $detallesSolicitud[0]['FechaFirma'] : 'Sin Información';
            $arrayReturn['atiendeServicio'] = ($detallesSolicitud[0]['AtiendeServicio'] !== NULL) ? $detallesSolicitud[0]['AtiendeServicio'] : 'Sin Información';
            $arrayReturn['atiende'] = ($detallesSolicitud[0]['Atiende'] !== NULL) ? $detallesSolicitud[0]['Atiende'] : 'Sin Información';
            $arrayReturn['tiempoSolicitud'] = ($detallesSolicitud[0]['TiempoSolicitud'] !== '') ? $detallesSolicitud[0]['TiempoSolicitud'] : 'Sin Información';
            $arrayReturn['tiempoServicio'] = ($detallesSolicitud[0]['TiempoServicio'] !== '') ? $detallesSolicitud[0]['TiempoServicio'] : 'Sin Información';
        }
        return $arrayReturn;
    }

    public function getGeneralesSinClasificar(string $servicio) {
        $sentencia = ""
                . "select "
                . "Descripcion, "
                . "Archivos, "
                . "Fecha "
                . "from t_servicios_generales "
                . "where IdServicio = '" . $servicio . "'";
        return $this->consultaGeneral($sentencia);
    }

    public function getNombreServicio(string $servicio) {
        $consulta = $this->consulta("select
                                            tipoServicio(tst.IdTipoServicio) as nombreServicio
                                    from t_servicios_ticket tst
                                    where Id = '" . $servicio . "'");
        return $consulta;
    }

    public function getAvancesPDF(int $datos, int $servicio) {
        $consulta = $this->consulta("SELECT 
                                            tsma.Id,
                                            tsma.IdServicio,
                                            tsmaa.*,
                                            nombreUsuario(tsmaa.IdUsuario) NombreUsuario,
                                            (SELECT Nombre FROM cat_v3_x4d_ubicaciones WHERE Id = tsmaa.IdUbicacion) Ubicación,
                                            if(tsmaa.IdRegistroElemento is null or tsmaa.IdRegistroElemento = 0,'', (SELECT Nombre FROM cat_v3_x4d_elementos WHERE Id = tes.IdElemento)) Elemento,
                                            if(tsmaa.IdRegistroSubelemento is null or tsmaa.IdRegistroSubelemento = 0,'', (SELECT Nombre FROM cat_v3_x4d_subelementos WHERE Id = tss.IdSubelemento)) Subelemento
                                    FROM t_salas4d_mantto_actividades_avances tsmaa
                                    INNER JOIN t_salas4d_mantto_actividades tsma
                                    ON tsmaa.IdActividad = tsma.Id
                                    LEFT JOIN t_elementos_salas4d tes
                                    ON tes.Id = tsmaa.IdRegistroElemento
                                    LEFT JOIN t_subelementos_salas4d tss
                                    ON tss.Id = tsmaa.IdRegistroSubelemento
                                    WHERE tsma.Id = '" . $datos . "'
                                    and tsma.IdServicio = '" . $servicio . "'
                                    ORDER BY tsmaa.Fecha DESC");
        return $consulta;
    }

    public function getProductosServicio(int $servicio) {
        $consulta = $this->consulta("select 
                                        (select Nombre from cat_v3_tipos_producto_inventario where Id = ti.IdTipoProducto) as TipoProducto,
                                        CASE ti.IdTipoProducto
                                        WHEN 1 THEN
                                                        modelo(ti.IdProducto)
                                        WHEN 2 THEN
                                                        CONCAT(
                                                                        (select Nombre from cat_v3_componentes_equipo where Id = ti.IdProducto), 
                                                                        ' (',
                                                                        modelo((select IdModelo from cat_v3_componentes_equipo where Id = ti.IdProducto)),
                                                                        ')'
                                                        )
                                        WHEN 3 THEN
                                                        elementoSalas4D(ti.IdProducto)                                                    
                                        WHEN 4 THEN
                                                        CONCAT(
                                                                        subelementoSalas4D(ti.IdProducto),
                                                                        ' [',
                                                                        elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = ti.IdProducto)),
                                                                        ']'
                                                        )
                                        END AS Producto,
                                        ti.Serie,
                                        tsmaap.Cantidad

                                    from t_salas4d_mantto_actividades tsma
                                    inner join t_salas4d_mantto_actividades_avances tsmaa on tsma.Id = tsmaa.IdActividad
                                    inner join t_salas4d_mantto_actividades_avance_productos tsmaap on tsmaa.Id = tsmaap.IdRegistroAvance
                                    inner join t_inventario ti on ti.Id = tsmaap.IdRegistroInventario
                                    where tsma.IdServicio = '" . $servicio . "' and ti.IdTipoProducto <> 5

                                        union

                                        select 
                                        (select Nombre from cat_v3_tipos_producto_inventario where Id = ti.IdTipoProducto) as TipoProducto,
                                        (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = ti.IdProducto) as Producto,
                                        '' as Serie,
                                        sum(tsmaap.Cantidad) as Cantidad

                                    from t_salas4d_mantto_actividades tsma
                                    inner join t_salas4d_mantto_actividades_avances tsmaa on tsma.Id = tsmaa.IdActividad
                                    inner join t_salas4d_mantto_actividades_avance_productos tsmaap on tsmaa.Id = tsmaap.IdRegistroAvance
                                    inner join t_inventario ti on ti.Id = tsmaap.IdRegistroInventario
                                    where tsma.IdServicio = '" . $servicio . "' and ti.IdTipoProducto = 5 
                                    GROUP BY ti.IdProducto;");
        return $consulta;
    }

    public function insertarMantenimientoCorrectivo(array $arrayCorrectivo) {
        $arreglo = [
            "IdServicio" => $arrayCorrectivo['IdServicio'],
            "IdTipoFalla" => $arrayCorrectivo['IdTipoFalla'],
            "IdRegistroInventario" => $arrayCorrectivo['IdRegistroInventario']
        ];
        return $this->insertar("t_salas4d_correctivos_generales", $arreglo);
    }

    public function getTipoFalla() {
        $consulta = $this->consulta("SELECT * FROM cat_v3_salas4d_correctivo_tipos_falla where flag = 1");
        return $consulta;
    }
    
    public function getTipoSolucion() {
        $consulta = $this->consulta("SELECT Id as id, Nombre as text, Flag FROM cat_v3_salas4d_correctivo_tipos_solucion WHERE flag = 1");
        return $consulta;
    }
    
    public function registroServicio(array $datos) {
        $consulta = $this->consulta("SELECT IdSucursal FROM t_servicios_ticket tst WHERE Id = '" . $datos['servicio'] . "'");
        return $consulta;
    }

    public function getCorrectivosGenerales(array $servicio) {
        $consulta = $this->consulta("SELECT * FROM t_salas4d_correctivos_generales where IdServicio ='" . $servicio['servicio'] . "'");

        if ($consulta) {
            $data['IdServicio'] = $consulta[0]['IdServicio'];
            $data['tipoFalla'] = $consulta[0]['IdTipoFalla'];
            $data['elementoRadio'] = $consulta[0]['IdRegistroInventario'];
            return $data;
        } else {
            return FALSE;
        }
    }

    public function editarMantenimientoCorrectivo(array $datos) {
        $editar = $this->actualizar('t_salas4d_correctivos_generales', array(
            'IdServicio' => $datos['IdServicio'],
            'IdTipoFalla' => $datos['IdTipoFalla'],
            'IdRegistroInventario' => $datos['IdRegistroInventario']
                ), array('IdServicio' => $datos['IdServicio'])
        );
        return $editar;
    }

    public function insertaMantenimientoCorrectivo(array $arrayDatos) {
        $this->iniciaTransaccion();

        $datosInsertar = array(
                "IdServicio" => $arrayDatos['IdServicio'],
                "IdTipoSolucion" => $arrayDatos['IdTipoSolucion'],
                "Observaciones" => $arrayDatos['Observaciones'],
                "Archivos" => $arrayDatos['evidencias'],
                "Fecha" => $arrayDatos['Fecha']);
        
        if ($arrayDatos['IdTipoSolucion'] === "1") {//Sin Equipo
            $this->insertar("t_salas4d_correctivos_soluciones",$datosInsertar);
            
        } else if ($arrayDatos['IdTipoSolucion'] === "2") {//con Elemento
            $this->insertar("t_salas4d_correctivos_soluciones",$datosInsertar);
            $ultimaIdSoluciones = $this->ultimoId();
            
            $this->insertar("t_salas4d_correctivos_elementos", array(
                "IdRegistroSolucion" => $ultimaIdSoluciones,
                "IdRegistroInventario" => $arrayDatos['elementoUtilizado']
                    )
            );
        } else if ($arrayDatos['IdTipoSolucion'] === "3") {//con Subelemento
            $this->insertar("t_salas4d_correctivos_soluciones", $datosInsertar);
            
            if (!empty($arrayDatos['DatosProductos'])) {
                $ultimaIdSoluciones = $this->ultimoId();
                foreach ($arrayDatos['DatosProductos'] as $key => $value) {
                    $this->insertar("t_salas4d_correctivos_subelementos", array(
                        "IdRegistroSolucion" => $ultimaIdSoluciones,
                        "IdSubelementoDañado" => $value['IdDanado'],
                        "IdSubelementoInventario" => $value['IdUtilizado']
                            )
                    );
                }
            }
        }
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function todosSubelementosMiAlmacen(int $usuario, int $servicio) {

        $subelementosUtilizadosServicio = array();
        $datos = array();
        $datosSubelementos = "SELECT 
                                    tscsub.IdSubelementoInventario,
                                    tscsub.IdSubelementoDañado
                            FROM t_salas4d_correctivos_soluciones as tscs 
                            inner join t_salas4d_correctivos_subelementos as tscsub
                            on tscs.Id = tscsub.IdRegistroSolucion
                            WHERE tscsub.IdRegistroSolucion = (SELECT Max(Id) FROM t_salas4d_correctivos_soluciones WHERE IdServicio = " . $servicio . ")";
        $consulta = $this->consulta($datosSubelementos);

        foreach ($consulta as $value) {
            array_push($subelementosUtilizadosServicio, array('inventario' => $value['IdSubelementoInventario'], 'danado' => $value['IdSubelementoDañado']));
        }
        
        $datosInventario = "SELECT 
                                tin.Id,                                
                                tin.IdTipoProducto,
                                (SUBELEMENTOSALAS4D(tin.IdProducto)) as Producto,
                                tin.Serie
                            FROM t_inventario as tin 
                            WHERE tin.IdTipoProducto = 4 
                            and tin.Cantidad > 0
                            and tin.IdEstatus = 17
                            and tin.IdAlmacen = (SELECT Id FROM cat_v3_almacenes_virtuales WHERE IdTipoAlmacen = 1 and IdReferenciaAlmacen = " . $usuario . ")";
        $inventarioDisponible = $this->consulta($datosInventario);
        
        $datosSubelementosOcupados = "SELECT 
                                            IdSubelementoInventario 
                                      FROM t_salas4d_correctivos_subelementos 
                                      WHERE IdRegistroSolucion IN (SELECT Max(Id) FROM t_salas4d_correctivos_soluciones GROUP BY IdServicio)";
        
        $subelementosUtilizados =  $this->consulta($datosSubelementosOcupados);
        
        foreach ($inventarioDisponible as $value) {
            $flag = 0;
            $idDanado = 0;
            $SubElementOcupado = false;
            
            foreach ($subelementosUtilizadosServicio as $valor) {
                if ($valor['inventario'] == $value['Id']) {
                    $flag = 1;
                    $idDanado = $valor['danado'];                    
                }
            }     
            
            foreach ($subelementosUtilizados as $valor) {
                if ($valor['IdSubelementoInventario'] == $value['Id'] && $flag == 0) {
                    $SubElementOcupado = true;
                }
            }
            
            if(!$SubElementOcupado) {
                array_push($datos, array(
                    'id' => $value['Id'],
                    'text' => $value['Producto'] . " " . $value['Serie'],
                    'idTipoProducto' => $value['IdTipoProducto'],
                    'idDanado' => $idDanado,
                    'flag' => $flag
                ));
            }
        }
        
        return $datos;
    }

    public function subelmentoDanadoCorrectivo($servicio) {
        $consulta = $this->consulta("SELECT * FROM t_salas4d_correctivos_generales WHERE IdServicio = '" . $servicio['servicio'] . "'");
        $condicion = '';
        if (!empty($consulta)) {
            $datos = $consulta[0];
            if ($datos['IdTipoFalla'] == 2) {
                $condicion = " AND tscs.IdSubelementoDañado = '" . $datos['IdRegistroInventario'] . "' ";
            }
        }
        $consultaSubelementosDanado = "SELECT tscs.IdSubelementoDañado,
                                            subelementoSucursal(tscs.IdSubelementoDañado) as SubelementoDanado,
                                            tscs.IdSubelementoInventario,
                                            subelementoInventario(tscs.IdSubelementoInventario) as SubelementoInventario
                                       FROM t_salas4d_correctivos_subelementos tscs
                                        INNER JOIN t_salas4d_correctivos_soluciones tscsol on tscsol.Id = tscs.IdRegistroSolucion 
                                        AND tscsol.Id = (SELECT MAX(Id) FROM t_salas4d_correctivos_soluciones WHERE IdServicio = '" . $servicio['servicio'] . "')
                                       WHERE tscsol.IdServicio = '" . $servicio['servicio'] . "' " . $condicion;
        $consulta2 = $this->consulta($consultaSubelementosDanado);
        return $consulta2;
    }

    public function getSolucionByServicio($servicio) {
        $consultaSolucionServicio = "SELECT *
                                     FROM t_salas4d_correctivos_soluciones
                                     WHERE Id = (SELECT MAX(Id) FROM t_salas4d_correctivos_soluciones WHERE IdServicio = '" . $servicio['servicio'] . "')";
        $consulta = $this->consulta($consultaSolucionServicio);
        return $consulta;
    }

    public function actualizarEvidencia($evidencia, $soloEliminar) {

        $update = $this->actualizar('t_salas4d_correctivos_soluciones', array(
            "Archivos" => $evidencia
                ), array('IdServicio' => $soloEliminar));
        return $update;
    }

    public function elementoUtil($servicio) {
        $correctivoElementos = $this->consulta("SELECT * FROM t_salas4d_correctivos_elementos WHERE IdRegistroSolucion = (SELECT MAX(Id) FROM t_salas4d_correctivos_soluciones WHERE IdServicio = '" . $servicio . "')");
        if (!empty($correctivoElementos)) {
            $elementoSalas4D = $this->consulta("SELECT concat(elementoSalas4D(inven.IdProducto),'-',inven.Serie) as Nombre FROM t_inventario inven WHERE Id = '" . $correctivoElementos[0]['IdRegistroInventario'] . "'");
            return $elementoSalas4D;
        } else {
            return false;
        }
    }

    public function actualizarSoloElemento(array $datos) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("SELECT now() as Fecha;");
        $servicioTicket = $this->consulta("SELECT * FROM t_servicios_ticket WHERE Id = '" . $datos['servicio'] . "'");
        $almacenVirtual = $this->consulta("SELECT * FROM cat_v3_almacenes_virtuales WHERE IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $datos['idUsuario'] . "'");
        $almacenSucursal = $this->consulta("SELECT * FROM cat_v3_almacenes_virtuales WHERE IdTipoAlmacen = 3 and IdReferenciaAlmacen = '" . $servicioTicket[0]['IdSucursal'] . "'");

        $this->actualizar('t_elementos_salas4d', array(
            'Flag' => 0,
                ), array('Id' => $datos['elementoDanado'], 'IdUsuario' => $datos['idUsuario'])
        );
        $consultaElemento = $this->consulta("select * from t_elementos_salas4d
                                                WHERE  IdUsuario = '" . $datos['idUsuario'] . "'
                                                AND Id = '" . $datos['elementoDanado'] . "'");

        $this->insertar("t_inventario", array(
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => 3,
            "IdProducto" => $consultaElemento[0]['IdElemento'],
            "IdEstatus" => 22,
            "Cantidad" => 1
                )
        );

        $this->insertar("t_movimientos_inventario", array(
            "IdTipoMovimiento" => 4,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $almacenSucursal[0]['Id'],
            "IdTipoProducto" => 3,
            "IdProducto" => $consultaElemento[0]['IdElemento'],
            "IdEstatus" => 22,
            "IdUsuario" => $datos['idUsuario'],
            "Cantidad" => 1,
            "Serie" => $consultaElemento[0]['Serie'],
            "Fecha" => $fecha[0]['Fecha']
                )
        );

        $ultimaIdMovimiento = $this->ultimoId();
        $ultimoMovimientoInven = $this->consulta("SELECT * FROM t_movimientos_inventario WHERE Id = '" . $ultimaIdMovimiento . "'");

        $this->insertar("t_movimientos_inventario", array(
            "IdMovimientoEnlazado" => $ultimoMovimientoInven[0]['Id'],
            "IdTipoMovimiento" => 5,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => $ultimoMovimientoInven[0]['IdTipoProducto'],
            "IdProducto" => $ultimoMovimientoInven[0]['IdProducto'],
            "IdEstatus" => $ultimoMovimientoInven[0]['IdEstatus'],
            "IdUsuario" => $ultimoMovimientoInven[0]['IdUsuario'],
            "Cantidad" => $ultimoMovimientoInven[0]['Cantidad'],
            "Serie" => $ultimoMovimientoInven[0]['Serie'],
            "Fecha" => $ultimoMovimientoInven[0]['Fecha']
                )
        );
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function actualizarSoloSubelemento(array $datos) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("SELECT now() as Fecha;");
        $servicioTicket = $this->consulta("SELECT * FROM t_servicios_ticket WHERE Id = '" . $datos['servicio'] . "'");
        $almacenVirtual = $this->consulta("SELECT * FROM cat_v3_almacenes_virtuales WHERE IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $datos['idUsuario'] . "'");
        $almacenSucursal = $this->consulta("SELECT * FROM cat_v3_almacenes_virtuales WHERE IdTipoAlmacen = 3 and IdReferenciaAlmacen = '" . $servicioTicket[0]['IdSucursal'] . "'");


        $this->actualizar('t_subelementos_salas4d', array(
            'Flag' => 0,
                ), array('Id' => $datos['elementoDanado'], 'IdUsuario' => $datos['idUsuario'])
        );

        $consultaSubElemento = $this->consulta("SELECT * FROM t_subelementos_salas4d WHERE  IdUsuario = '" . $datos['idUsuario'] . "' AND Id = '" . $datos['elementoDanado'] . "'");

        $this->insertar("t_inventario", array(
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => 4,
            "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
            "IdEstatus" => 22,
            "Cantidad" => 1
                )
        );

        $this->insertar("t_movimientos_inventario", array(
            "IdTipoMovimiento" => 4,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $almacenSucursal[0]['Id'],
            "IdTipoProducto" => 4,
            "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
            "IdEstatus" => 22,
            "IdUsuario" => $datos['idUsuario'],
            "Cantidad" => 1,
            "Serie" => $consultaSubElemento[0]['Serie'],
            "Fecha" => $fecha[0]['Fecha']
                )
        );

        $ultimaIdSoluciones = $this->ultimoId();
        $ultimoMovimientoInven = $this->consulta("SELECT * FROM t_movimientos_inventario WHERE Id = '" . $ultimaIdSoluciones . "'");

        $this->insertar("t_movimientos_inventario", array(
            "IdMovimientoEnlazado" => $ultimoMovimientoInven[0]['Id'],
            "IdTipoMovimiento" => 5,
            "IdServicio" => $ultimoMovimientoInven[0]['IdServicio'],
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => $ultimoMovimientoInven[0]['IdTipoProducto'],
            "IdProducto" => $ultimoMovimientoInven[0]['IdProducto'],
            "IdEstatus" => $ultimoMovimientoInven[0]['IdEstatus'],
            "IdUsuario" => $ultimoMovimientoInven[0]['IdUsuario'],
            "Cantidad" => $ultimoMovimientoInven[0]['Cantidad'],
            "Serie" => $ultimoMovimientoInven[0]['Serie'],
            "Fecha" => $ultimoMovimientoInven[0]['Fecha']
                )
        );
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function actualizarElementoDanado(array $datos) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("select now() as Fecha;");
        $servicioTicket = $this->consulta("select * from t_servicios_ticket where Id = '" . $datos['servicio'] . "'");
        $almacenVirtual = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $datos['idUsuario'] . "'");
        $almacenSucursal = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 3 and IdReferenciaAlmacen = '" . $servicioTicket[0]['IdSucursal'] . "'");

        $this->actualizar('t_elementos_salas4d', array(
            'Flag' => 0,
                ), array('Id' => $datos['elementoDanado'], 'IdUsuario' => $datos['idUsuario'])
        );
        $consultaElemento = $this->consulta("select * from t_elementos_salas4d
                                                WHERE  IdUsuario = '" . $datos['idUsuario'] . "'
                                                AND Id = '" . $datos['elementoDanado'] . "'");

        $this->insertar("t_inventario", array(
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => 3,
            "IdProducto" => $consultaElemento[0]['IdElemento'],
            "IdEstatus" => 22,
            "Cantidad" => 1
                )
        );

        $this->insertar("t_movimientos_inventario", array(
            "IdTipoMovimiento" => 4,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $almacenSucursal[0]['Id'],
            "IdTipoProducto" => 3,
            "IdProducto" => $consultaElemento[0]['IdElemento'],
            "IdEstatus" => 22,
            "IdUsuario" => $datos['idUsuario'],
            "Cantidad" => 1,
            "Serie" => $consultaElemento[0]['Serie'],
            "Fecha" => $fecha[0]['Fecha']
                )
        );

        $ultimaIdMovimiento = $this->ultimoId();
        $ultimoMovimientoInven = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdMovimiento . "'");

        $this->insertar("t_movimientos_inventario", array(
            "IdMovimientoEnlazado" => $ultimoMovimientoInven[0]['Id'],
            "IdTipoMovimiento" => 5,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $almacenVirtual[0]['Id'],
            "IdTipoProducto" => $ultimoMovimientoInven[0]['IdTipoProducto'],
            "IdProducto" => $ultimoMovimientoInven[0]['IdProducto'],
            "IdEstatus" => $ultimoMovimientoInven[0]['IdEstatus'],
            "IdUsuario" => $ultimoMovimientoInven[0]['IdUsuario'],
            "Cantidad" => $ultimoMovimientoInven[0]['Cantidad'],
            "Serie" => $ultimoMovimientoInven[0]['Serie'],
            "Fecha" => $ultimoMovimientoInven[0]['Fecha']
                )
        );

        $idSolucion = $datos['solucion'][0]['Id'];
        $correctivoElemento = $this->consulta("select * from t_salas4d_correctivos_elementos where IdRegistroSolucion = '" . $idSolucion . "'");
        $elementoUtilizado = $correctivoElemento[0]['IdRegistroInventario'];
        $consultaInventario = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $elementoUtilizado . "'");

        $idelementoUtil = $consultaInventario[0]['Id'];
        $cantidad = $consultaInventario[0]['Cantidad'];
        $cantidadResultado = $cantidad - 1;

        $this->actualizar('t_inventario', array(
            'Cantidad' => $cantidadResultado,
                ), array('Id' => $idelementoUtil)
        );

        $consultaInventario2 = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $elementoUtilizado . "'");

        $this->insertar("t_elementos_salas4d", array(
            "IdUsuario" => $datos['idUsuario'],
            "IdSucursal" => $consultaElemento[0]['IdSucursal'],
            "IdUbicacion" => $consultaElemento[0]['IdUbicacion'],
            "IdSistema" => $consultaElemento[0]['IdSistema'],
            "IdElemento" => $consultaInventario2[0]['IdProducto'],
            "Serie" => $consultaInventario2[0]['Serie'],
            "FechaCaptura" => $fecha[0]['Fecha'],
            "Flag" => 1
                )
        );

        $this->insertar("t_movimientos_inventario", array(
            "IdTipoMovimiento" => 4,
            "IdServicio" => $datos['servicio'],
            "IdAlmacen" => $consultaInventario2[0]['IdAlmacen'],
            "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
            "IdProducto" => $consultaInventario2[0]['IdProducto'],
            "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
            "IdUsuario" => $datos['idUsuario'],
            "Cantidad" => 1,
            "Serie" => $consultaInventario2[0]['Serie'],
            "Fecha" => $fecha[0]['Fecha']
                )
        );

        $ultimaIdMovi = $this->ultimoId();
        $ultimoMovimiento = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdMovi . "'");

        $this->insertar("t_movimientos_inventario", array(
            "IdMovimientoEnlazado" => $ultimoMovimiento[0]['Id'],
            "IdTipoMovimiento" => 5,
            "IdServicio" => $ultimoMovimiento[0]['IdServicio'],
            "IdAlmacen" => $almacenSucursal[0]['Id'],
            "IdTipoProducto" => $ultimoMovimiento[0]['IdTipoProducto'],
            "IdProducto" => $ultimoMovimiento[0]['IdProducto'],
            "IdEstatus" => $ultimoMovimiento[0]['IdEstatus'],
            "IdUsuario" => $ultimoMovimiento[0]['IdUsuario'],
            "Cantidad" => $ultimoMovimiento[0]['Cantidad'],
            "Serie" => $ultimoMovimiento[0]['Serie'],
            "Fecha" => $ultimoMovimiento[0]['Fecha']
                )
        );

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function masSubelementoSolucion(array $datos) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("select now() as Fecha;");
        $servicioTicket = $this->consulta("select * from t_servicios_ticket where Id = '" . $datos['servicio'] . "'");
        $almacenVirtual = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $datos['idUsuario'] . "'");
        $almacenSucursal = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 3 and IdReferenciaAlmacen = '" . $servicioTicket[0]['IdSucursal'] . "'");
        $masSubelementos = $this->subelmentoDanadoCorrectivo(array('servicio' => $datos['servicio']));

        foreach ($masSubelementos as $value) {

            $this->actualizar('t_subelementos_salas4d', array(
                'Flag' => 0,
                    ), array('Id' => $value['IdSubelementoDañado'], 'IdUsuario' => $datos['idUsuario'])
            );

            $consultaSubElemento = $this->consulta("select * from t_subelementos_salas4d
                                                    WHERE  IdUsuario = '" . $datos['idUsuario'] . "'
                                                    AND Id = '" . $value['IdSubelementoDañado'] . "'");

            $this->insertar("t_inventario", array(
                "IdAlmacen" => $almacenVirtual[0]['Id'],
                "IdTipoProducto" => 4,
                "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
                "IdEstatus" => 22,
                "Cantidad" => 1
                    )
            );

            $this->insertar("t_movimientos_inventario", array(
                "IdTipoMovimiento" => 4,
                "IdServicio" => $datos['servicio'],
                "IdAlmacen" => $almacenSucursal[0]['Id'],
                "IdTipoProducto" => 4,
                "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
                "IdEstatus" => 22,
                "IdUsuario" => $datos['idUsuario'],
                "Cantidad" => 1,
                "Serie" => $consultaSubElemento[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
                    )
            );

            $ultimaIdSoluciones = $this->ultimoId();
            $ultimoMovimientoInven = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdSoluciones . "'");

            $this->insertar("t_movimientos_inventario", array(
                "IdMovimientoEnlazado" => $ultimoMovimientoInven[0]['Id'],
                "IdTipoMovimiento" => 5,
                "IdServicio" => $ultimoMovimientoInven[0]['IdServicio'],
                "IdAlmacen" => $almacenVirtual[0]['Id'],
                "IdTipoProducto" => $ultimoMovimientoInven[0]['IdTipoProducto'],
                "IdProducto" => $ultimoMovimientoInven[0]['IdProducto'],
                "IdEstatus" => $ultimoMovimientoInven[0]['IdEstatus'],
                "IdUsuario" => $ultimoMovimientoInven[0]['IdUsuario'],
                "Cantidad" => $ultimoMovimientoInven[0]['Cantidad'],
                "Serie" => $ultimoMovimientoInven[0]['Serie'],
                "Fecha" => $ultimoMovimientoInven[0]['Fecha']
                    )
            );

            $consultaInventario = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $value['IdSubelementoInventario'] . "'");

            $idSubelementoUtil = $consultaInventario[0]['Id'];
            $cantidad = $consultaInventario[0]['Cantidad'];
            $cantidadResultado = $cantidad - 1;

            $this->actualizar('t_inventario', array(
                'Cantidad' => $cantidadResultado,
                    ), array('Id' => $idSubelementoUtil)
            );
            $consultaInventario2 = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $value['IdSubelementoInventario'] . "'");

            $this->insertar("t_subelementos_salas4d", array(
                "IdUsuario" => $datos['idUsuario'],
                "IdRegistroElemento" => $consultaSubElemento[0]['IdRegistroElemento'],
                "IdSubelemento" => $consultaInventario2[0]['IdProducto'],
                "Serie" => $consultaInventario2[0]['Serie'],
                "FechaCaptura" => $fecha[0]['Fecha'],
                "Flag" => 1
                    )
            );

            $this->insertar("t_movimientos_inventario", array(
                "IdTipoMovimiento" => 4,
                "IdServicio" => $datos['servicio'],
                "IdAlmacen" => $consultaInventario2[0]['IdAlmacen'],
                "IdTipoProducto" => $consultaInventario2[0]['IdTipoProducto'],
                "IdProducto" => $consultaInventario2[0]['IdProducto'],
                "IdEstatus" => $consultaInventario2[0]['IdEstatus'],
                "IdUsuario" => $datos['idUsuario'],
                "Cantidad" => 1,
                "Serie" => $consultaInventario2[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
                    )
            );

            $ultimaIdMovi = $this->ultimoId();
            $ultimoMovimiento = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdMovi . "'");

            $this->insertar("t_movimientos_inventario", array(
                "IdMovimientoEnlazado" => $ultimoMovimiento[0]['Id'],
                "IdTipoMovimiento" => 5,
                "IdServicio" => $ultimoMovimiento[0]['IdServicio'],
                "IdAlmacen" => $almacenSucursal[0]['Id'],
                "IdTipoProducto" => $ultimoMovimiento[0]['IdTipoProducto'],
                "IdProducto" => $ultimoMovimiento[0]['IdProducto'],
                "IdEstatus" => $ultimoMovimiento[0]['IdEstatus'],
                "IdUsuario" => $ultimoMovimiento[0]['IdUsuario'],
                "Cantidad" => $ultimoMovimiento[0]['Cantidad'],
                "Serie" => $ultimoMovimiento[0]['Serie'],
                "Fecha" => $ultimoMovimiento[0]['Fecha']
                    )
            );
        }
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function elementoConSubelemento(array $datos) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("select now() as Fecha;");
        $servicioTicket = $this->consulta("select * from t_servicios_ticket where Id = '" . $datos['servicio'] . "'");
        $almacenVirtual = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $datos['idUsuario'] . "'");
        $almacenSucursal = $this->consulta("select * from cat_v3_almacenes_virtuales where IdTipoAlmacen = 3 and IdReferenciaAlmacen = '" . $servicioTicket[0]['IdSucursal'] . "'");
        $masSubelementos = $this->subelmentoDanadoCorrectivo(array('servicio' => $datos['servicio']));

        foreach ($masSubelementos as $value) {

            $this->actualizar('t_subelementos_salas4d', array(
                'Flag' => 0,
                    ), array('Id' => $value['IdSubelementoDañado'], 'IdUsuario' => $datos['idUsuario'])
            );

            $consultaSubElemento = $this->consulta("select * from t_subelementos_salas4d
                                                    WHERE  IdUsuario = '" . $datos['idUsuario'] . "'
                                                    AND Id = '" . $value['IdSubelementoDañado'] . "'");

            $this->insertar("t_inventario", array(
                "IdAlmacen" => $almacenVirtual[0]['Id'],
                "IdTipoProducto" => 4,
                "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
                "IdEstatus" => 22,
                "Cantidad" => 1
                    )
            );

            $this->insertar("t_movimientos_inventario", array(
                "IdTipoMovimiento" => 4,
                "IdServicio" => $datos['servicio'],
                "IdAlmacen" => $almacenSucursal[0]['Id'],
                "IdTipoProducto" => 4,
                "IdProducto" => $consultaSubElemento[0]['IdSubelemento'],
                "IdEstatus" => 22,
                "IdUsuario" => $datos['idUsuario'],
                "Cantidad" => 1,
                "Serie" => $consultaSubElemento[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
                    )
            );

            $ultimaIdSoluciones = $this->ultimoId();
            $ultimoMovimientoInven = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdSoluciones . "'");

            $this->insertar("t_movimientos_inventario", array(
                "IdMovimientoEnlazado" => $ultimoMovimientoInven[0]['Id'],
                "IdTipoMovimiento" => 5,
                "IdServicio" => $ultimoMovimientoInven[0]['IdServicio'],
                "IdAlmacen" => $almacenVirtual[0]['Id'],
                "IdTipoProducto" => $ultimoMovimientoInven[0]['IdTipoProducto'],
                "IdProducto" => $ultimoMovimientoInven[0]['IdProducto'],
                "IdEstatus" => $ultimoMovimientoInven[0]['IdEstatus'],
                "IdUsuario" => $ultimoMovimientoInven[0]['IdUsuario'],
                "Cantidad" => $ultimoMovimientoInven[0]['Cantidad'],
                "Serie" => $ultimoMovimientoInven[0]['Serie'],
                "Fecha" => $ultimoMovimientoInven[0]['Fecha']
                    )
            );

            $consultaInventario = $this->consulta("SELECT * FROM t_inventario WHERE Id = '" . $value['IdSubelementoInventario'] . "'");

            $idSubelementoUtil = $consultaInventario[0]['Id'];
            $cantidad = $consultaInventario[0]['Cantidad'];
            $cantidadResultado = $cantidad - 1;

            $this->actualizar('t_inventario', array(
                'Cantidad' => $cantidadResultado,
                    ), array('Id' => $idSubelementoUtil)
            );

            $this->insertar("t_subelementos_salas4d", array(
                "IdUsuario" => $datos['idUsuario'],
                "IdRegistroElemento" => $consultaSubElemento[0]['IdRegistroElemento'],
                "IdSubelemento" => $consultaInventario[0]['IdProducto'],
                "Serie" => $consultaInventario[0]['Serie'],
                "FechaCaptura" => $fecha[0]['Fecha'],
                "Flag" => 1
                    )
            );

            $this->insertar("t_movimientos_inventario", array(
                "IdTipoMovimiento" => 4,
                "IdServicio" => $datos['servicio'],
                "IdAlmacen" => $consultaInventario[0]['IdAlmacen'],
                "IdTipoProducto" => $consultaInventario[0]['IdTipoProducto'],
                "IdProducto" => $consultaInventario[0]['IdProducto'],
                "IdEstatus" => $consultaInventario[0]['IdEstatus'],
                "IdUsuario" => $datos['idUsuario'],
                "Cantidad" => 1,
                "Serie" => $consultaInventario[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
                    )
            );

            $ultimaIdMovi = $this->ultimoId();
            $ultimoMovimiento = $this->consulta("select * from t_movimientos_inventario where Id = '" . $ultimaIdMovi . "'");

            $this->insertar("t_movimientos_inventario", array(
                "IdMovimientoEnlazado" => $ultimoMovimiento[0]['Id'],
                "IdTipoMovimiento" => 5,
                "IdServicio" => $ultimoMovimiento[0]['IdServicio'],
                "IdAlmacen" => $almacenSucursal[0]['Id'],
                "IdTipoProducto" => $ultimoMovimiento[0]['IdTipoProducto'],
                "IdProducto" => $ultimoMovimiento[0]['IdProducto'],
                "IdEstatus" => $ultimoMovimiento[0]['IdEstatus'],
                "IdUsuario" => $ultimoMovimiento[0]['IdUsuario'],
                "Cantidad" => $ultimoMovimiento[0]['Cantidad'],
                "Serie" => $ultimoMovimiento[0]['Serie'],
                "Fecha" => $ultimoMovimiento[0]['Fecha']
                    )
            );
        }
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function concluirSolitud($servicio, $ticket) {
        $fecha = $this->consulta("select now() as Fecha;");
        $servicioTicket = $this->consulta("SELECT *
                                FROM t_servicios_ticket
                                WHERE IdEstatus NOT IN (4,6)
                                AND Ticket = '" . $ticket . "'
                                AND Id <> '" . $servicio . "'");
        $IdSolicitud = $servicioTicket[0]['IdSolicitud'];
        if (empty($servicioTicket)) {
            $this->actualizar('t_solicitudes', array(
                'IdEstatus' => 4,
                'FechaConclusion' => $fecha[0]['Fecha']
                    ), array('Id' => $IdSolicitud)
            );
        }
    }
    
    public function elementoDisponiblesServicio(int $usuario,$condicion){
        $elementosDisponibles = "SELECT 
                                    Id as id,
                                    inve.Cantidad,
                                    inve.Serie,
                                    CASE inve.IdtipoProducto
                                        WHEN 3 THEN CONCAT(ELEMENTOSALAS4D(inve.IdProducto),' - ', inve.Serie)
                                    END AS text,
                                    inve.IdtipoProducto
                                FROM
                                    t_inventario inve
                                WHERE
                                    inve.IdTipoProducto IN (3)
                                        AND inve.IdAlmacen IN (SELECT 
                                            Id
                                        FROM
                                            cat_v3_almacenes_virtuales
                                        WHERE
                                        IdTipoAlmacen = 1
                                        AND IdReferenciaAlmacen = " . $usuario . ")
                                        AND inve.IdEstatus = 17
                                        AND inve.Cantidad > 0
                                        AND inve.Id not in (
                                            SELECT IdRegistroInventario
                                            FROM t_salas4d_correctivos_elementos
                                            WHERE IdRegistroSolucion IN (
                                                SELECT MAX(Id) FROM t_salas4d_correctivos_soluciones GROUP BY IdServicio
                                            )
                                            $condicion
                                        )";
    
        return $this->consulta($elementosDisponibles);
    }

}
