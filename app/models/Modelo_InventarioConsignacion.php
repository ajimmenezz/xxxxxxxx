<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_InventarioConsignacion extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getInventarioPoliza($idAlmacen) {
        $consulta = $this->consulta("select 
                                    inve.Id,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = inve.IdAlmacen) as Almacen,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as Tipo,
                                    CASE inve.IdtipoProducto
                                            WHEN 1 THEN
                                                    modelo(inve.IdProducto)
                                            WHEN 2 THEN
                                                    CONCAT(
                                                            (select Nombre from cat_v3_componentes_equipo where Id = inve.IdProducto), 
                                                            ' (',
                                                            modelo((select IdModelo from cat_v3_componentes_equipo where Id = inve.IdProducto)),
                                                            ')'
                                                            )
                                    END AS Producto,
                                    estatus(inve.IdEstatus) as Estatus,
                                    Cantidad,
                                    Serie

                                    from t_inventario inve where IdAlmacen = '" . $idAlmacen . "' and inve.IdtipoProducto in (1,2) and inve.Cantidad > 0;");
        return $consulta;
    }

    public function getInventarioSalas($idAlmacen) {
        $consulta = $this->consulta("select 
                                    inve.Id,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = inve.IdAlmacen) as Almacen,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as Tipo,
                                    CASE inve.IdtipoProducto
                                        WHEN 3 THEN
                                            elementoSalas4D(inve.IdProducto)                                                    
                                        WHEN 4 THEN
                                            CONCAT(
                                                subelementoSalas4D(inve.IdProducto),
                                                ' [',
                                                elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = inve.IdProducto)),
                                                ']'
                                            )
                                    END AS Producto,
                                    estatus(inve.IdEstatus) as Estatus,
                                    Cantidad,
                                    Serie

                                    from t_inventario inve where IdAlmacen = '" . $idAlmacen . "' and inve.IdtipoProducto in (3,4) and inve.Cantidad > 0;");
        return $consulta;
    }

    public function getInventarioSAE($idAlmacen) {
        $consulta = $this->consulta("select 
                                    inve.Id,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = inve.IdAlmacen) as Almacen,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as Tipo,
                                    (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = inve.IdProducto) as Producto,                      
                                    estatus(inve.IdEstatus) as Estatus,
                                    Cantidad,
                                    Serie
                                    from t_inventario inve where IdAlmacen = '" . $idAlmacen . "' and inve.IdtipoProducto in (5) and inve.Cantidad > 0;");
        return $consulta;
    }

    public function getNewAltaInicial() {
        $consulta = $this->consulta("select 
                                    tiai.*,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = tiai.IdAlmacen) as Almacen
                                    from t_inventario_altas_iniciales tiai
                                    where IdUsuario = '" . $this->usuario['Id'] . "' and Flag = 1;");
        return $consulta;
    }

    public function getModelosPoliza() {
        $consulta = $this->consulta("select * from v_equipos order by Equipo");
        return $consulta;
    }

    public function getElementosSalas() {
        $consulta = $this->consulta("select ele.Id, elementoSalas4D(ele.Id) as Elemento from cat_v3_x4d_elementos ele where ele.Flag = 1 order by Elemento;");
        return $consulta;
    }

    public function getProductosSAE() {
        $consulta = $this->consulta("select Id, concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Flag = 1 order by productos.Nombre;");
        return $consulta;
    }

    public function getEstatusProductoConsignacion() {
        $consulta = $this->consulta("select * from cat_v3_estatus where Descripcion = 'Inventario Virtual' order by Nombre");
        return $consulta;
    }

    public function getComponentesPoliza($modelo) {
        $consulta = $this->consulta("select * from cat_v3_componentes_equipo where IdModelo = '" . $modelo . "' order by Nombre;");
        return $consulta;
    }

    public function getSubelementosSalas($elemento) {
        $consulta = $this->consulta("select subele.Id, subelementoSalas4D(subele.Id) as Nombre from cat_v3_x4d_subelementos subele where IdElemento = '" . $elemento . "' order by Nombre;");
        return $consulta;
    }

    public function getAlmacenesOrigenTraspaso() {
        $consulta = $this->consulta("select * from cat_v3_almacenes_virtuales almacen where IdTipoAlmacen in (1,4) and Flag = 1 order by almacen.Nombre;");
        return $consulta;
    }

    public function getAlmacenesDestinoTraspaso() {
        $consulta = $this->consulta("select * from cat_v3_almacenes_virtuales almacen where IdTipoAlmacen in (1,4) and Flag = 1 order by almacen.Nombre;");
        return $consulta;
    }

    public function getSiguienteTraspaso() {
        $consulta = $this->consulta("select if(MAX(NoTraspaso) is null, 0, MAX(NoTraspaso)) + 1 as NoTraspaso from t_movimientos_inventario;");
        return $consulta;
    }

    public function getMovimientosByAlmacen($id, array $datos = []) {
        $condicion = "";

        if (!empty($datos)) {
            $fechaIni = $datos['desde'];
            $fechaFin = $datos['hasta'];

            if ($fechaIni == '' && $fechaFin != '') {
                $condicion .= " and inve.Fecha <= '" . $fechaFin . " 23:59:59' ";
            } else if ($fechaIni != '' && $fechaFin == '') {
                $condicion .= " and inve.Fecha >= '" . $fechaIni . " 00:00:00'";
            } else if ($fechaIni != '' && $fechaFin != '') {
                $condicion .= " and inve.Fecha >= '" . $fechaIni . " 00:00:00' and inve.Fecha <= '" . $fechaFin . " 23:59:59' ";
            }

            if ($datos['tipoMov'] != '') {
                $condicion .= " and inve.IdTipoMovimiento = '" . $datos['tipoMov'] . "'";
            }

            if ($datos['tipoProd'] != '') {
                $condicion .= " and inve.IdTipoProducto = '" . $datos['tipoProd'] . "'";
            }
        }



        $consulta = $this->consulta("select 
                                    (select Nombre from cat_v3_tipos_movimiento_inventario where Id = inve.IdTipoMovimiento) as Movimiento,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = (select IdAlmacen from t_movimientos_inventario where Id = inve.IdMovimientoEnlazado)) as Origen,
                                    nombreUsuario(inve.IdUsuario) as Usuario,
                                    inve.Fecha,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as TipoProducto,
                                    CASE inve.IdTipoProducto
                                        WHEN 1 THEN
                                            modelo(inve.IdProducto)
                                        WHEN 2 THEN
                                            CONCAT(
                                                (select Nombre from cat_v3_componentes_equipo where Id = inve.IdProducto), 
                                                ' (',
                                                modelo((select IdModelo from cat_v3_componentes_equipo where Id = inve.IdProducto)),
                                                ')'
                                            )
                                        WHEN 3 THEN
                                            elementoSalas4D(inve.IdProducto)                                                    
                                        WHEN 4 THEN
                                            CONCAT(
                                                subelementoSalas4D(inve.IdProducto),
                                                ' [',
                                                elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = inve.IdProducto)),
                                                ']'
                                            )
                                        WHEN 5 THEN 
                                            (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = inve.IdProducto)
                                    END AS Producto,
                                    inve.Cantidad,
                                    inve.Serie,
                                    estatus(inve.IdEstatus) as Estatus
                                    from t_movimientos_inventario inve
                                    where inve.IdAlmacen = '" . $id . "' " . $condicion . ";");
        return $consulta;
    }

    public function getTiposMovimientosInventario() {
        $consulta = $this->consulta("select * from cat_v3_tipos_movimiento_inventario order by Nombre");
        return $consulta;
    }

    public function getTiposProductosInvenario() {
        $consulta = $this->consulta("select * from cat_v3_tipos_producto_inventario order by Nombre");
        return $consulta;
    }

    public function getDatosAlmacenVirtual($id) {
        $consulta = $this->consulta("select * from cat_v3_almacenes_virtuales where Id = '" . $id . "'");
        return $consulta[0];
    }

    public function getInventarioSucursalPoliza($id) {
        $consulta = $this->consulta("select 
                                    areaAtencion(tc.IdArea) as Area,
                                    tc.Punto,
                                    modelo(tc.IdModelo) as Equipo,
                                    tc.Serie,
                                    tc.Extra as Terminal
                                    from t_censos tc 
                                    where IdServicio = (select MAX(Id) from t_servicios_ticket where IdSucursal = '" . $id . "' and IdTipoServicio = 11 and IdEstatus = 4)
                                    order by Area, Punto, Equipo;");
        return $consulta;
    }

    public function getElementosSalas4D($id) {
        $consulta = $this->consulta("select
                                    (select Nombre from cat_v3_x4d_elementos where Id = tes.IdElemento) as Elemento,
                                    tes.Serie,
                                    tes.ClaveCinemex,
                                    (select Nombre from cat_v3_x4d_ubicaciones where Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema where Id = tes.IdSistema) as Sistema
                                    from
                                    t_elementos_salas4D tes
                                    where tes.IdSucursal = '" . $id . "' and tes.Flag = 1;");
        return $consulta;
    }

    public function getSubelementosSalas4D($id) {
        $consulta = $this->consulta("select 
                                    (select Nombre from cat_v3_x4d_subelementos where Id = tss.IdSubelemento) as Subelemento,
                                    (select Nombre from cat_v3_x4d_elementos where Id = tes.IdElemento) as Elemento,
                                    tss.Serie,
                                    tss.ClaveCinemex,
                                    (select Nombre from cat_v3_x4d_ubicaciones where Id = tes.IdUbicacion) as Ubicacion,
                                    (select Nombre from cat_v3_x4d_tipos_sistema where Id = tes.IdSistema) as Sistema
                                    from t_subelementos_salas4D tss 
                                    inner join t_elementos_salas4D tes on tss.IdRegistroElemento = tes.Id
                                    where tes.IdSucursal = '" . $id . "' and tes.Flag = 1 and tss.Flag = 1;");
        return $consulta;
    }

    public function getDetallesTraspaso($id) {
        $consulta = $this->consulta("select 
                                    inve.Id,
                                    (select Nombre from cat_v3_tipos_movimiento_inventario where Id = inve.IdTipoMovimiento) as Movimiento,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = (select IdAlmacen from t_movimientos_inventario where Id = inve.IdMovimientoEnlazado)) as Origen,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = inve.IdAlmacen) as Destino,
                                    nombreUsuario(inve.IdUsuario) as Usuario,
                                    inve.Fecha,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as TipoProducto,
                                    CASE inve.IdtipoProducto
                                        WHEN 1 THEN
                                            modelo(inve.IdProducto)
                                        WHEN 2 THEN
                                            CONCAT(
                                                (select Nombre from cat_v3_componentes_equipo where Id = inve.IdProducto), 
                                                ' (',
                                                modelo((select IdModelo from cat_v3_componentes_equipo where Id = inve.IdProducto)),
                                                ')'
                                            )
                                        WHEN 3 THEN
                                            elementoSalas4D(inve.IdProducto)                                                    
                                        WHEN 4 THEN
                                            CONCAT(
                                                subelementoSalas4D(inve.IdProducto),
                                                ' [',
                                                elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = inve.IdProducto)),
                                                ']'
                                            )
                                        WHEN 5 THEN 
                                            (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = inve.IdProducto)
                                    END AS Producto,
                                    inve.Cantidad,
                                    inve.Serie,
                                    estatus(inve.IdEstatus) as Estatus

                                    from t_movimientos_inventario inve
                                    where NoTraspaso = '" . $id . "' and inve.IdTipoMovimiento = 3;");
        return $consulta;
    }

    public function getDetallesAltaInicial($id) {
        $consulta = $this->consulta("select 
                                    nombreUsuario(tiai.IdUsuario) as Usuario,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = inve.IdAlmacen) as Almacen,
                                    tiai.FechaInicio,
                                    tiai.FechaTermino,
                                    (select Nombre from cat_v3_tipos_producto_inventario where Id = inve.IdTipoProducto) as TipoProducto,
                                    CASE inve.IdtipoProducto
                                        WHEN 1 THEN
                                            modelo(inve.IdProducto)
                                        WHEN 2 THEN
                                            CONCAT(
                                                (select Nombre from cat_v3_componentes_equipo where Id = inve.IdProducto), 
                                                ' (',
                                                modelo((select IdModelo from cat_v3_componentes_equipo where Id = inve.IdProducto)),
                                                ')'
                                            )
                                        WHEN 3 THEN
                                            elementoSalas4D(inve.IdProducto)                                                    
                                        WHEN 4 THEN
                                            CONCAT(
                                                subelementoSalas4D(inve.IdProducto),
                                                ' [',
                                                elementoSalas4D((select IdElemento from cat_v3_x4d_subelementos where Id = inve.IdProducto)),
                                                ']'
                                            )
                                        WHEN 5 THEN 
                                            (select concat('[',Clave,']  ',Nombre) as Nombre from cat_v3_equipos_sae productos where Id = inve.IdProducto)
                                    END AS Producto,
                                    inve.Cantidad,
                                    inve.Serie,
                                    estatus(inve.IdEstatus) as Estatus
                                    from t_inventario inve 
                                    inner join t_inventario_altas_iniciales tiai on inve.IdAltaInicial = tiai.Id
                                    where inve.IdAltaInicial = '" . $id . "';");

        return $consulta;
    }

    public function getTraspasos(bool $todos) {
        $condicion = '';
        if (!$todos) {
            $condicion .= " and (
                            (select IdAlmacen from t_movimientos_inventario where Id = tmi.IdMovimientoEnlazado) in (select Id from cat_v3_almacenes_virtuales where (IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $this->usuario['Id'] . "') or IdResponsable = '" . $this->usuario['Id'] . "')
                            or
                            tmi.IdAlmacen in (select Id from cat_v3_almacenes_virtuales where (IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $this->usuario['Id'] . "') or IdResponsable = '" . $this->usuario['Id'] . "')
                            ) ";
        }
        $consulta = $this->consulta("select 
                                    NoTraspaso,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = (select IdAlmacen from t_movimientos_inventario where Id = tmi.IdMovimientoEnlazado)) as Origen,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = tmi.IdAlmacen) as Destino,                                    
                                    tmi.Fecha,
                                    nombreUsuario(tmi.IdUsuario) as Usuario
                                    from t_movimientos_inventario tmi
                                    where tmi.IdTipoMovimiento = 3 
                                    " . $condicion . " 
                                    group by NoTraspaso;");
        return $consulta;
    }

    public function getAltasIniciales(bool $todos) {
        $condicion = '';
        if (!$todos) {
            $condicion .= " and tiai.IdAlmacen in (select Id from cat_v3_almacenes_virtuales where (IdTipoAlmacen = 1 and IdReferenciaAlmacen = '" . $this->usuario['Id'] . "') or IdResponsable = '" . $this->usuario['Id'] . "') ";
        }
        $consulta = $this->consulta("select 
                                    Id,
                                    (select Nombre from cat_v3_almacenes_virtuales where Id = tiai.IdAlmacen) as Almacen,
                                    tiai.FechaInicio,
                                    tiai.FechaTermino,
                                    nombreUsuario(tiai.IdUsuario) as Usuario
                                    from t_inventario_altas_iniciales tiai
                                    where Flag = 0 " . $condicion);
        return $consulta;
    }

    public function getKitsEquipos() {
        $consulta = $this->consulta("select
                                    Id,
                                    cke.IdModelo,
                                    modelo(cke.IdModelo) as Equipo,
                                    Componentes,
                                    nombreUsuario(cke.IdUsuario) as Usuario,
                                    Fecha,
                                    Flag
                                    from cat_v3_kits_equipos cke order by Equipo;");

        $arrayReturn = [];
        foreach ($consulta as $key => $value) {
            $componentes = explode(",", $value['Componentes']);
            $arrayComponentes = [];
            foreach ($componentes as $k => $v) {
                $data = explode("_", $v);
                array_push($arrayComponentes, [
                    'Id' => $data[0],
                    'Nombre' => $this->getComponenteById($data[0]),
                    'Cantidad' => $data[1]
                ]);
            }

            array_push($arrayReturn, [
                'Id' => $value['IdModelo'],
                'Equipo' => $value['Equipo'],
                'Componentes' => $arrayComponentes,
                'Usuario' => $value['Usuario'],
                'Fecha' => $value['Fecha'],
                'Flag' => $value['Flag']
            ]);
        }

        return $arrayReturn;
    }

    public function getComponenteById($id) {
        $consulta = $this->consulta("select Nombre from cat_v3_componentes_equipo where Id = '" . $id . "'");
        return $consulta[0]['Nombre'];
    }

    public function getComponentesByModelo($modelo) {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_componentes_equipo where IdModelo = '" . $modelo . "' and Flag = 1 order by Nombre");
        return $consulta;
    }

    public function getKitByModelo($modelo) {
        $consulta = $this->consulta("select Componentes from cat_v3_kits_equipos where IdModelo = '" . $modelo . "'");
        $arrayReturn = [];
        if (!empty($consulta)) {
            $componentes = explode(",", $consulta[0]['Componentes']);
            foreach ($componentes as $key => $value) {
                $data = explode("_", $value);
                $arrayReturn[$data[0]] = $data[1];
            }
        }

        return $arrayReturn;
    }

    public function guardarKit(array $data) {
        $this->iniciaTransaccion();

        $modelo = $data['modelo'];
        $componentes = implode(",", $data['componentes']);

        $fecha = $this->consulta("select now() as Fecha;");

        $consulta = $this->consulta("select * from cat_v3_kits_equipos where IdModelo = '" . $modelo . "'");

        if (!empty($consulta)) {
            $this->actualizar("cat_v3_kits_equipos", [
                'Componentes' => $componentes,
                'IdUsuario' => $this->usuario['Id'],
                'Fecha' => $fecha[0]['Fecha']], ['Id' => $consulta[0]['Id']]);
        } else {
            $this->insertar('cat_v3_kits_equipos', [
                "IdModelo" => $modelo,
                'Componentes' => $componentes,
                'IdUsuario' => $this->usuario['Id'],
                'Fecha' => $fecha[0]['Fecha'],
                'Flag' => 1
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['estatus' => false];
        } else {
            $this->commitTransaccion();
            return ['estatus' => true];
        }
    }

    public function getProductosDeshuesar() {
        $consulta = $this->consulta("select 
                                    ti.Id as IdRegistroInventario,
                                    cav.Id as IdAlmacen,
                                    cav.Nombre as Almacen,
                                    ti.IdProducto as IdEquipo,
                                    modelo(ti.IdProducto) as Equipo,
                                    estatus(ti.IdEstatus) as Estatus,
                                    ti.Serie
                                    from t_inventario ti 
                                    inner join cat_v3_kits_equipos cke on ti.IdProducto = cke.IdModelo
                                    inner join cat_v3_almacenes_virtuales cav on ti.IdAlmacen = cav.Id
                                    where ((cav.IdTipoAlmacen = 1 and cav.IdReferenciaAlmacen = '" . $this->usuario['Id'] . "') or cav.IdResponsable = '" . $this->usuario['Id'] . "')
                                    and ti.IdTipoProducto = 1 and Cantidad >= 1
                                    and cke.Componentes is not null
                                    and cke.Componentes <> ''
                                    and cke.Flag = 1;");
        return $consulta;
    }

    public function traspasarProductos(array $data) {
        $siguiente = $this->getSiguienteTraspaso()[0]['NoTraspaso'];
        $this->iniciaTransaccion();

        if (isset($data['equipos']) && count($data['equipos']) > 0) {
            foreach ($data['equipos'] as $key => $value) {
                $fecha = $this->consulta("select now() as Fecha;");
                $datos = $this->consulta("select * from t_inventario where Id = '" . $value . "'")[0];
                $this->actualizar("t_inventario", ['IdAlmacen' => $data['destino']], ['Id' => $value]);
                $this->insertar('t_movimientos_inventario', [
                    "IdTipoMovimiento" => 2,
                    "IdAlmacen" => $datos['IdAlmacen'],
                    "IdTipoProducto" => $datos['IdTipoProducto'],
                    "IdProducto" => $datos['IdProducto'],
                    "IdEstatus" => $datos['IdEstatus'],
                    "IdUsuario" => $this->usuario['Id'],
                    "Cantidad" => $datos['Cantidad'],
                    "Serie" => $datos['Serie'],
                    "Fecha" => $fecha[0]['Fecha'],
                    'NoTraspaso' => $siguiente
                ]);

                $id = $this->connectDBPrueba()->insert_id();

                $this->insertar('t_movimientos_inventario', [
                    "IdTipoMovimiento" => 3,
                    "IdMovimientoEnlazado" => $id,
                    "IdAlmacen" => $data['destino'],
                    "IdTipoProducto" => $datos['IdTipoProducto'],
                    "IdProducto" => $datos['IdProducto'],
                    "IdEstatus" => $datos['IdEstatus'],
                    "IdUsuario" => $this->usuario['Id'],
                    "Cantidad" => $datos['Cantidad'],
                    "Serie" => $datos['Serie'],
                    "Fecha" => $fecha[0]['Fecha'],
                    'NoTraspaso' => $siguiente
                ]);
            }
        }

        if (isset($data['otros']) && count($data['otros']) > 0) {
            foreach ($data['otros'] as $key => $value) {
                $fecha = $this->consulta("select now() as Fecha;");
                $datos = $this->consulta("select * from t_inventario where Id = '" . $value['id'] . "'")[0];

                $revision = $this->consulta("select * "
                        . "from t_inventario "
                        . "where IdAlmacen = '" . $data['destino'] . "' "
                        . "and IdTipoProducto = 5 "
                        . "and IdProducto = '" . $datos['IdProducto'] . "' "
                        . "and IdEstatus = '" . $datos['IdEstatus'] . "'");

                $this->actualizar("t_inventario", ['Cantidad' => ($datos['Cantidad'] - $value['cantidad'])], ['Id' => $value['id']]);

                if (!empty($revision)) {
                    $this->actualizar("t_inventario", ['Cantidad' => ($revision[0]['Cantidad'] + $value['cantidad'])], ['Id' => $revision[0]['Id']]);
                } else {
                    $this->insertar("t_inventario", [
                        "IdAlmacen" => $data['destino'],
                        "IdTipoProducto" => $datos['IdTipoProducto'],
                        "IdProducto" => $datos['IdProducto'],
                        "IdEstatus" => $datos['IdEstatus'],
                        "Cantidad" => $value['cantidad'],
                        "Serie" => ""
                    ]);
                }


                $this->insertar('t_movimientos_inventario', [
                    "IdTipoMovimiento" => 2,
                    "IdAlmacen" => $datos['IdAlmacen'],
                    "IdTipoProducto" => $datos['IdTipoProducto'],
                    "IdProducto" => $datos['IdProducto'],
                    "IdEstatus" => $datos['IdEstatus'],
                    "IdUsuario" => $this->usuario['Id'],
                    "Cantidad" => $value['cantidad'],
                    "Serie" => "",
                    "Fecha" => $fecha[0]['Fecha'],
                    'NoTraspaso' => $siguiente
                ]);

                $id = $this->connectDBPrueba()->insert_id();

                $this->insertar('t_movimientos_inventario', [
                    "IdTipoMovimiento" => 3,
                    "IdMovimientoEnlazado" => $id,
                    "IdAlmacen" => $data['destino'],
                    "IdTipoProducto" => $datos['IdTipoProducto'],
                    "IdProducto" => $datos['IdProducto'],
                    "IdEstatus" => $datos['IdEstatus'],
                    "IdUsuario" => $this->usuario['Id'],
                    "Cantidad" => $value['cantidad'],
                    "Serie" => "",
                    "Fecha" => $fecha[0]['Fecha'],
                    'NoTraspaso' => $siguiente
                ]);
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['estatus' => false];
        } else {
            $this->commitTransaccion();
            return ['estatus' => true, 'id' => $siguiente];
        }
    }

    public function nuevaAltaInicial($almacen) {
        $this->iniciaTransaccion();
        $fecha = $this->consulta("select now() as Fecha;");
        $this->insertar('t_inventario_altas_iniciales', [
            'IdUsuario' => $this->usuario['Id'],
            'IdAlmacen' => $almacen,
            'FechaInicio' => $fecha[0]['Fecha'],
            'Flag' => 1
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['estatus' => false];
        } else {
            $this->commitTransaccion();
            return ['estatus' => true];
        }
    }

    public function cerrarAltaInicial() {
        $alta = $this->getNewAltaInicial()[0]['Id'];
        $this->iniciaTransaccion();
        $fecha = $this->consulta("select now() as Fecha;");

        $this->actualizar("t_inventario_altas_iniciales", ['FechaTermino' => $fecha[0]['Fecha'], 'Flag' => 0], ['Id' => $alta]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['estatus' => false];
        } else {
            $this->commitTransaccion();
            return ['estatus' => true, 'altaInicial' => $alta];
        }
    }

    public function guardarProductosInventario($data, $inicial) {
        $this->iniciaTransaccion();
        $return_array = [
            'estatus' => 500
        ];
        foreach ($data['data'] as $key => $value) {
            $fecha = $this->consulta("select now() as Fecha;");

            $this->insertar("t_inventario", [
                "IdAlmacen" => $value['IdAlmacen'],
                "IdTipoProducto" => $value['IdTipoProducto'],
                "IdProducto" => $value['IdProducto'],
                "IdEstatus" => $value['IdEstatus'],
                "Cantidad" => $value['Cantidad'],
                "Serie" => $value['Serie'],
                "IdAltaInicial" => $inicial
            ]);

            $this->insertar('t_movimientos_inventario', [
                "IdTipoMovimiento" => 1,
                "IdAlmacen" => $value['IdAlmacen'],
                "IdTipoProducto" => $value['IdTipoProducto'],
                "IdProducto" => $value['IdProducto'],
                "IdEstatus" => $value['IdEstatus'],
                "IdUsuario" => $this->usuario['Id'],
                "Cantidad" => $value['Cantidad'],
                "Serie" => $value['Serie'],
                "Fecha" => $fecha[0]['Fecha']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->commitTransaccion();
            $return_array['estatus'] = 200;
        }
        return $return_array;
    }

    public function guardarComponentesDeshueso($data, $registroInventario) {
        $this->iniciaTransaccion();
        $return_array = [
            'estatus' => 500
        ];

        $fecha = $this->consulta("select now() as Fecha;");

        $inventario = $this->consulta("select * from t_inventario where Id = '" . $registroInventario . "'");
        if (!empty($inventario)) {
            $this->actualizar("t_inventario", ['Cantidad' => 0], ['Id' => $inventario[0]['Id']]);
            $this->insertar('t_movimientos_inventario', [
                "IdTipoMovimiento" => 6,
                "IdAlmacen" => $inventario[0]['IdAlmacen'],
                "IdTipoProducto" => $inventario[0]['IdTipoProducto'],
                "IdProducto" => $inventario[0]['IdProducto'],
                "IdEstatus" => $inventario[0]['IdEstatus'],
                "IdUsuario" => $this->usuario['Id'],
                "Cantidad" => $inventario[0]['Cantidad'],
                "Serie" => $inventario[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
            ]);

            $idSalida = $this->connectDBPrueba()->insert_id();

            foreach ($data as $key => $value) {
                $this->insertar("t_inventario", [
                    "IdAlmacen" => $value['IdAlmacen'],
                    "IdTipoProducto" => $value['IdTipoProducto'],
                    "IdProducto" => $value['IdProducto'],
                    "IdEstatus" => $value['IdEstatus'],
                    "Cantidad" => $value['Cantidad'],
                    "Serie" => $value['Serie'],
                    "IdEquipoDeshuesado" => $inventario[0]['Id']
                ]);

                $this->insertar('t_movimientos_inventario', [
                    "IdMovimientoEnlazado" => $idSalida,
                    "IdTipoMovimiento" => 7,
                    "IdAlmacen" => $value['IdAlmacen'],
                    "IdTipoProducto" => $value['IdTipoProducto'],
                    "IdProducto" => $value['IdProducto'],
                    "IdEstatus" => $value['IdEstatus'],
                    "IdUsuario" => $this->usuario['Id'],
                    "Cantidad" => $value['Cantidad'],
                    "Serie" => $value['Serie'],
                    "Fecha" => $fecha[0]['Fecha']
                ]);
            }
        }
        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->commitTransaccion();
            $return_array['estatus'] = 200;
        }
        return $return_array;
    }

    public function buscaSerieDuplicada(array $datos) {
        $consulta = $this->consulta(""
                . "select Serie "
                . "from t_inventario "
                . "where IdTipoProducto = '" . $datos['IdTipoProducto'] . "' "
                . "and IdProducto = '" . $datos['IdProducto'] . "' "
                . "and Serie = '" . $datos['Serie'] . "'");
        return $consulta;
    }

    /*     * *********************************************************** */

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

                                    where IdSucursal = '" . $id . "' and tes.Flag = 1 and tss.Flag = 1;");
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
                                    concat(cxs.Nombre,' - ',(select Nombre from cat_v3_x4d_marcas where Id = cxs.IdMarca)) as Subelemento,
                                    tss.Serie,
                                    tss.ClaveCinemex,
                                    tss.Imagen
                                    from t_subelementos_salas4D tss 
                                    inner join cat_v3_x4d_subelementos cxs on tss.IdSubelemento = cxs.Id

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

}
