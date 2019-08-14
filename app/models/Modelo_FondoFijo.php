<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_FondoFijo extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getTiposCuenta(int $tipo = null)
    {
        $condicion = (!is_null($tipo)) ? " where Id = '" . $tipo . "'" : '';
        $consulta = $this->consulta("select  
                                    Id,
                                    Nombre,
                                    if(Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    Flag
                                    from cat_v3_fondofijo_tipos_cuenta
                                    " . $condicion . "
                                    order by Nombre");
        return $consulta;
    }

    public function agregarTipoCuenta(string $tipo)
    {
        $insert = $this->insertar("cat_v3_fondofijo_tipos_cuenta", ['Nombre' => mb_strtoupper($tipo)]);
        if (!is_null($insert)) {
            return [
                'id' => $this->ultimoId()
            ];
        } else {
            return [
                'id' => null,
                'error' => $this->tipoError()
            ];
        }
    }

    public function editarTipoCuenta(array $datos)
    {
        $edit = $this->actualizar("cat_v3_fondofijo_tipos_cuenta", ['Nombre' => mb_strtoupper($datos['tipo']), 'Flag' => $datos['estatus']], ['Id' => $datos['id']]);
        if (!is_null($edit)) {
            return [
                'datos' => $this->getTiposCuenta($datos['id'])
            ];
        } else {
            return [
                'datos' => [],
                'error' => $this->tipoError()
            ];
        }
    }

    public function getUsuarios()
    {
        $consulta = $this->consulta("select 
                                    cu.Id,
                                    nombreUsuario(cu.Id) as Nombre,
                                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil
                                    from cat_v3_usuarios cu
                                    where Id > 1 
                                    and Flag = 1
                                    order by Usuario");
        return $consulta;
    }

    public function getMontosUsuario(int $id)
    {
        $consulta = $this->consulta("select 
                                    Id,
                                    IdTipoCuenta,
                                    Monto,
                                    nombreUsuario(IdUsuarioAsigna) as Asigna,
                                    FechaAsignacion
                                    from cat_v3_fondofijo_montos_x_usuario_cuenta
                                    where IdUsuario = '" . $id . "'");
        return $consulta;
    }

    public function guardarMontos(array $datos)
    {
        $this->iniciaTransaccion();

        $montos = json_decode($datos['montos'], true);
        foreach ($montos as $key => $value) {
            $monto = $this->consulta("select 
                                    Id 
                                    from cat_v3_fondofijo_montos_x_usuario_cuenta 
                                    where IdUsuario = '" . $datos['id'] . "' 
                                    and IdTipoCuenta = '" . $value['tipoCuenta'] . "'");
            if (!empty($monto) && isset($monto[0]) && isset($monto[0]['Id']) && $monto[0]['Id'] > 0) {
                $this->actualizar("cat_v3_fondofijo_montos_x_usuario_cuenta", [
                    'IdUsuarioAsigna' => $this->usuario['Id'],
                    'Monto' => $value['monto'],
                    'FechaAsignacion' => $this->getFecha()
                ], ['Id' => $monto[0]['Id']]);
            } else {
                $this->insertar("cat_v3_fondofijo_montos_x_usuario_cuenta", [
                    'IdUsuario' => $datos['id'],
                    'IdTipoCuenta' => $value['tipoCuenta'],
                    'IdUsuarioAsigna' => $this->usuario['Id'],
                    'Monto' => $value['monto'],
                    'FechaAsignacion' => $this->getFecha()
                ]);
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getTiposComprobante()
    {
        $consulta = $this->consulta("select * from cat_v3_tipos_comprobante where Flag = 1");
        return $consulta;
    }

    public function getSucursales()
    {
        $consulta = $this->consulta("select Id, sucursalCliente(Id) as Nombre from cat_v3_sucursales where Flag = 1 order by Nombre");
        return $consulta;
    }

    public function getConceptos(int $id = null)
    {
        $condicion = '';
        if (!is_null($id)) {
            $condicion = "where conc.Id = '" . $id . "'";
        }

        $consulta = $this->consulta("select 
                                    conc.Id,
                                    conc.Nombre,
                                    conc.TiposComprobante,
                                    conc.TiposCuenta,
                                    (select GROUP_CONCAT(Nombre SEPARATOR '<br />') from cat_v3_fondofijo_tipos_cuenta where concat(',',conc.TiposCuenta,',') REGEXP(concat(',',Id,','))) as Cuentas,
                                    (select GROUP_CONCAT(Nombre SEPARATOR '<br />') from cat_v3_tipos_comprobante where concat(',',conc.TiposComprobante,',') REGEXP(concat(',',Id,','))) as Comprobante,
                                    if(conc.Extraordinario = 1, 'Si', 'No') as Extraordinario,
                                    conc.Monto,
                                    (select count(*) from cat_v3_comprobacion_conceptos_alternativas where IdConcepto = conc.Id and Flag = 1) as Alternativos,
                                    if(conc.Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                    conc.Flag
                                    from cat_v3_comprobacion_conceptos conc " . $condicion . " order by Nombre");

        return $consulta;
    }

    public function getAlternativasByConcepto(int $id)
    {
        $consulta = $this->consulta("select 
                                    alt.Id,
                                    alt.IdUsuario,
                                    alt.IdSucursal,
                                    alt.Monto,
                                    nombreUsuario(alt.IdUsuario) as Usuario,
                                    sucursalCliente(alt.IdSucursal) as Sucursal,
                                    alt.Monto

                                    from cat_v3_comprobacion_conceptos_alternativas alt
                                    where alt.IdConcepto = '" . $id . "' 
                                    and alt.Flag = 1");
        return $consulta;
    }

    public function guardarConcepto(array $datos)
    {
        $this->iniciaTransaccion();

        if ($datos['id'] == 0) {
            $this->insertar("cat_v3_comprobacion_conceptos", [
                'Nombre' => $datos['concepto'],
                'Monto' => $datos['monto'],
                'Extraordinario' => $datos['extraordinario'],
                'TiposComprobante' => implode(",", $datos['comprobantes']),
                'TiposCuenta' => implode(",", $datos['tiposCuenta'])
            ]);


            $id = $this->ultimoId();
        } else {
            $this->actualizar("cat_v3_comprobacion_conceptos", [
                'Nombre' => $datos['concepto'],
                'Monto' => $datos['monto'],
                'Extraordinario' => $datos['extraordinario'],
                'TiposComprobante' => implode(",", $datos['comprobantes']),
                'TiposCuenta' => implode(",", $datos['tiposCuenta'])
            ], ['Id' => $datos['id']]);

            $id = $datos['id'];

            $this->actualizar('cat_v3_comprobacion_conceptos_alternativas', ['Flag' => 0], ['IdConcepto' => $id]);
        }

        if (isset($datos['alternativos']) && count($datos['alternativos']) > 0) {
            foreach ($datos['alternativos'] as $key => $value) {
                $this->insertar("cat_v3_comprobacion_conceptos_alternativas", [
                    'IdConcepto' => $id,
                    'IdUsuario' => $value['usuario'],
                    'IdSucursal' => $value['sucursal'],
                    'Monto' => $value['monto'],
                    'IdUsuarioAlta' => $this->usuario['Id'],
                    'Fecha' => $this->getFecha()
                ]);
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'fila' => $this->getConceptos($id)[0]];
        }
    }

    public function habInhabConcepto(array $datos, int $flag)
    {
        $this->iniciaTransaccion();

        $this->actualizar("cat_v3_comprobacion_conceptos", ['Flag' => $flag], ['Id' => $datos['id']]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'fila' => $this->getConceptos($datos['id'])[0]];
        }
    }

    public function getUsuariosConFondoFijo()
    {
        $consulta = $this->consulta("select 
                                    cu.Id,
                                    nombreUsuario(cu.Id) as Nombre,
                                    (select Nombre from cat_perfiles where Id = cu.IdPerfil) as Perfil
                                    from cat_v3_usuarios cu
                                    where Id > 1
                                    and cu.Id in (select IdUsuario from cat_v3_fondofijo_montos_x_usuario_cuenta group by IdUsuario) 
                                    and Flag = 1
                                    order by Usuario");
        return $consulta;
    }

    public function getTiposCuentaXUsuario(int $id)
    {
        $consulta = $this->consulta("select 
                                    Id,
                                    Nombre
                                    from cat_v3_fondofijo_tipos_cuenta 
                                    where Id in (
                                        select 
                                        IdTipoCuenta 
                                        from cat_v3_fondofijo_montos_x_usuario_cuenta 
                                        where IdUsuario = '" . $id . "' and Monto > 0
                                    ) and Flag = 1
                                    order by Nombre");
        return $consulta;
    }

    public function getMaximoMontoAutorizado(int $idUsuario, int $idTipoCuenta)
    {
        $consulta = $this->consulta("
        select 
        Monto 
        from cat_v3_fondofijo_montos_x_usuario_cuenta 
        where IdUSuario = '" . $idUsuario . "' 
        and IdTipoCuenta = '" . $idTipoCuenta . "'");
        return $consulta[0]['Monto'];
    }

    public function getSaldo(int $idUsuario, int $idTipoCuenta)
    {
        $consulta = $this->consulta("
        select 
        Saldo 
        from t_fondofijo_saldos 
        where IdUSuario = '" . $idUsuario . "' 
        and IdTipoCuenta = '" . $idTipoCuenta . "'");
        if (!empty($consulta) && isset($consulta[0]) && isset($consulta[0]['Saldo']) && is_numeric($consulta[0]['Saldo'])) {
            return $consulta[0]['Saldo'];
        } else {
            return 0;
        }
    }

    public function registrarDeposito(array $datos)
    {
        $this->iniciaTransaccion();

        $saldoPrevio = $this->getSaldo($datos['id'], $datos['tipoCuenta']);
        $saldoNuevo = (double)$saldoPrevio + (double)$datos['depositar'];

        $this->insertar("t_fondofijo_movimientos", [
            "FechaRegistro" => $this->getFecha(),
            "FechaMovimiento" => $this->getFecha(),
            "FechaAutorizacion" => $this->getFecha(),
            "IdUsuarioRegistra" => $this->usuario['Id'],
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "IdUsuarioFondoFijo" => $datos['id'],
            "IdTipoCuenta" => $datos['tipoCuenta'],
            "IdTipoMovimiento" => 6,
            "IdTipoComprobante" => 2,
            "IdEstatus" => 7,
            "Monto" => str_replace(",", "", number_format($datos['depositar'], 2)),
            "SaldoPrevio" => str_replace(",", "", number_format($saldoPrevio, 2)),
            "SaldoNuevo" => str_replace(",", "", number_format($saldoNuevo, 2)),
            "Archivos" => $datos["archivos"]
        ]);

        $idMovimiento = $this->ultimoId();

        $idSaldo = $this->consulta("
        select 
        Id 
        from t_fondofijo_saldos 
        where IdUsuario = '" . $datos['id'] . "' 
        and IdTipoCuenta = '" . $datos['tipoCuenta'] . "'");

        if (isset($idSaldo[0]) && isset($idSaldo[0]['Id'])) {
            $this->actualizar("t_fondofijo_saldos", [
                "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Fecha" => $this->getFecha(),
                "IdUltimoMovimiento" => $idMovimiento
            ], ['Id' => $idSaldo[0]['Id']]);
        } else {
            $this->insertar("t_fondofijo_saldos", [
                "IdUsuario" => $datos['id'],
                "IdTipoCuenta" => $datos['tipoCuenta'],
                "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Fecha" => $this->getFecha(),
                "IdUltimoMovimiento" => $idMovimiento
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $deposito = [
                'Id' => $idMovimiento,
                'TipoCuenta' => $this->getTiposCuenta($datos['tipoCuenta'])[0]['Nombre'],
                'Fecha' => $this->getFecha(),
                'SaldoAnterior' => '$' . number_format((float)$saldoPrevio, 2),
                'Deposito' => '$' . number_format((float)$datos['depositar'], 2),
                'Saldo' => '$' . number_format((float)$saldoNuevo, 2)
            ];


            $this->commitTransaccion();
            return ['code' => 200, 'deposito' => $deposito];
        }
    }

    public function getDepositos(int $idUsuario, int $idTipoCuenta = null)
    {

        $condicion = '';
        if (!is_null($idTipoCuenta)) {
            $condicion = " and tfm.IdTipoCuenta = '" . $idTipoCuenta . "'";
        }

        $consulta = $this->consulta("select
        tfm.Id,
        (select Nombre from cat_v3_fondofijo_tipos_cuenta where Id = tfm.IdTipoCuenta) as TipoCuenta,
        tfm.FechaRegistro as Fecha,
        tfm.SaldoPrevio,
        tfm.Monto,
        tfm.SaldoNuevo
        from 
        t_fondofijo_movimientos tfm
        where IdTipoMovimiento = 6
        and tfm.IdEstatus = 7
        and tfm.IdUsuarioFondoFijo = '" . $idUsuario . "'" . $condicion);
        return $consulta;
    }

    public function getSaldosCuentasXUsuario(int $id)
    {
        $consulta = $this->consulta("select
        cat.Id as IdTipoCuenta,
        cat.Nombre as TipoCuenta,
        montos.IdUsuario,
        saldo.Saldo,
        saldo.Fecha
        from 
        cat_v3_fondofijo_tipos_cuenta cat 
        inner join cat_v3_fondofijo_montos_x_usuario_cuenta montos on cat.Id = montos.IdTipoCuenta
        left join t_fondofijo_saldos saldo on cat.Id = saldo.IdTipoCuenta and montos.IdUsuario = saldo.IdUsuario
        where montos.IdUsuario = '" . $id . "'");

        return $consulta;
    }

    public function getMovimientos(int $idUsuario, int $idTipoCuenta)
    {

        $consulta = $this->consulta("select
        tfm.Id,
        if(
            tfm.IdTipoMovimiento = 6 and tfm.Monto < 0, 
            'Ajuste de Cuenta', 
            (select Nombre from cat_v3_comprobacion_tipos_movimiento where Id = tfm.IdTipoMovimiento)
        ) as TipoMovimiento,        
        case
            when tfm.IdTipoMovimiento = 1 then 'Depósito'
            when tfm.IdTIpoMovimiento = 3 then tfm.Observaciones
            when tfm.IdTipoMovimiento = 4 then 'Depósito Gasolina'
            when tfm.IdTipoMovimiento = 5 then 'Ajuste de gasolina'
            when tfm.IdTipoMovimiento = 6 and tfm.Monto > 0 then 'Abono a Cuenta' 
            when tfm.IdTipoMovimiento = 6 and tfm.Monto < 0 then 'Ajuste de Cuenta'           
            else (select Nombre from cat_v3_comprobacion_conceptos where Id = tfm.IdConcepto)
        end as Concepto,        
        estatus(tfm.IdEstatus) as Estatus,
        tfm.FechaRegistro as FechaRegistro,
        tfm.FechaAutorizacion as FechaAutorizacion,
        tfm.SaldoPrevio,
        tfm.Monto,
        tfm.SaldoNuevo
        from 
        t_fondofijo_movimientos tfm
        where tfm.IdUsuarioFondoFijo = '" . $idUsuario . "' 
        and tfm.IdTipoCuenta = '" . $idTipoCuenta . "' 
        order by Id desc");
        return $consulta;
    }

    public function getConceptosXTipoCuenta(int $tipo)
    {
        $consulta = $this->consulta("
        select 
        Id,
        Nombre,
        TiposComprobante
        from cat_v3_comprobacion_conceptos 
        where concat(',',TiposCuenta,',') like '%," . $tipo . ",%' 
        and Flag = 1");
        return $consulta;
    }

    public function getTicketsByUsuario(int $id)
    {
        $consulta = $this->consulta("select 
                                    Ticket 
                                    from (
                                        select 
                                        Ticket
                                        from t_servicios_ticket tst
                                        where Atiende = '" . $id . "'
                                        and IdEstatus in (1,2,3,5)

                                        UNION

                                        select 
                                        Ticket
                                        from t_servicios_ticket tst
                                        where Atiende = '" . $id . "'
                                        and IdEstatus = 4
                                        and tst.FechaConclusion >= '2018-10-05 00:00:00'
                                    ) as tf group by tf.Ticket;");
        return $consulta;
    }

    public function cargaMontoMaximoConcepto(array $datos)
    {
        $datos['destino'] = (!isset($datos['destino']) || in_array($datos['destino'], ['', 'o'])) ? '99999999999' : $datos['destino'];

        $comb = '';
        $query = "select "
            . "Monto "
            . "from cat_v3_comprobacion_conceptos_alternativas "
            . "where IdUsuario = '" . $datos['usuario'] . "' "
            . "and IdSucursal = '" . $datos['destino'] . "' "
            . "and IdConcepto = '" . $datos['concepto'] . "' "
            . "and Flag = 1";
        $consulta = $this->consulta($query);

        if (!empty($consulta)) {
            $monto = $consulta[0]['Monto'];
            $comb = 'CST';
        } else {
            $query = "select "
                . "Monto "
                . "from cat_v3_comprobacion_conceptos_alternativas "
                . "where IdUsuario = 0 "
                . "and IdSucursal = '" . $datos['destino'] . "' "
                . "and IdConcepto = '" . $datos['concepto'] . "' "
                . "and Flag = 1";
            $consulta = $this->consulta($query);
            if (!empty($consulta)) {
                $monto = $consulta[0]['Monto'];
                $comb = 'CS';
            } else {
                $query = "select "
                    . "Monto "
                    . "from cat_v3_comprobacion_conceptos_alternativas "
                    . "where IdUsuario = '" . $datos['usuario'] . "' "
                    . "and IdSucursal = 0 "
                    . "and IdConcepto = '" . $datos['concepto'] . "' "
                    . "and Flag = 1";
                $consulta = $this->consulta($query);
                if (!empty($consulta)) {
                    $monto = $consulta[0]['Monto'];
                    $comb = 'CT';
                } else {
                    $query = "select "
                        . "Monto "
                        . "from cat_v3_comprobacion_conceptos "
                        . "where Id = '" . $datos['concepto'] . "' "
                        . "and Flag = 1";
                    $consulta = $this->consulta($query);
                    $monto = $consulta[0]['Monto'];
                    $comb = 'C';
                }
            }
        }

        return ['monto' => $monto];
    }

    public function cargaServiciosTicket(array $datos)
    {
        $consulta = $this->consulta("select 
                                    Id,
                                    tipoServicio(tst.IdTipoServicio) as Tipo,
                                    tst.Descripcion
                                    from t_servicios_ticket tst 
                                    where Ticket = '" . $datos['ticket'] . "'
                                    and tst.Atiende = '" . $datos['usuario'] . "'");
        return $consulta;
    }

    public function registrarComprobante(array $datos)
    {
        $this->iniciaTransaccion();

        $revisarUUID = $this->consulta(""
            . "select * "
            . "from t_fondofijo_movimientos "
            . "where UUID = '" . $datos['cfdi']['uuid'] . "' "
            . "and UUID <> '' "
            . "and UUID is not null "
            . "and IdEstatus in (7,8)");

        if (!empty($revisarUUID)) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'errorBack' => 'Esta factura ya se utilizò con anterioridad. Revise su información'
            ];
        }

        $saldoPrevio = $this->getSaldo($this->usuario['Id'], $datos['tipoCuenta']);
        if ($datos['enPresupuesto'] == 1) {
            $saldoNuevo = (double)$saldoPrevio + (double)$datos['monto'];
        } else {
            $saldoNuevo = $saldoPrevio;
        }

        $this->insertar("t_fondofijo_movimientos", [
            "FechaRegistro" => $this->getFecha(),
            "FechaMovimiento" => str_replace("T", " ", $datos['fecha']),
            "FechaAutorizacion" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? $this->getFecha() : null,
            "IdUsuarioRegistra" => $this->usuario['Id'],
            "IdUsuarioFondoFijo" => $this->usuario['Id'],
            "IdUsuarioAutoriza" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? $this->usuario['Id'] : null,
            "IdTipoCuenta" => $datos['tipoCuenta'],
            "IdTipoMovimiento" => 7,
            "IdConcepto" => $datos['concepto'],
            "IdTipoComprobante" => $datos['tipoComprobante'],
            "IdEstatus" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? 7 : 8,
            "Monto" => str_replace(",", "", number_format($datos['monto'], 2)),
            "SaldoPrevio" => str_replace(",", "", number_format($saldoPrevio, 2)),
            "SaldoNuevo" => str_replace(",", "", number_format($saldoNuevo, 2)),
            "IdServicio" => $datos['servicio'],
            "IdOrigen" => $datos['origen'],
            "IdDestino" => $datos['destino'],
            "OrigenOtro" => $datos['stringOrigen'],
            "DestinoOtro" => $datos['stringDestino'],
            "Observaciones" => $datos["observaciones"],
            "Archivos" => $datos["archivos"],
            "EnPresupuesto" => $datos['enPresupuesto'],
            "XML" => $datos['xml'],
            "PDF" => $datos['pdf'],
            "MontoConcepto" => $datos['montoMaximo'],
            "SerieCFDI" => $datos['cfdi']['serie'],
            "FolioCFDI" => $datos['cfdi']['folio'],
            "UUID" => $datos['cfdi']['uuid'],
            "Receptor" => $datos['cfdi']['receptor'],
            "Pagado" => 0
        ]);

        $idMovimiento = $this->ultimoId();

        $idSaldo = $this->consulta("
        select 
        Id 
        from t_fondofijo_saldos 
        where IdUsuario = '" . $this->usuario['Id'] . "' 
        and IdTipoCuenta = '" . $datos['tipoCuenta'] . "'");

        if (isset($idSaldo[0]) && isset($idSaldo[0]['Id'])) {
            $this->actualizar("t_fondofijo_saldos", [
                "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Fecha" => $this->getFecha(),
                "IdUltimoMovimiento" => $idMovimiento
            ], ['Id' => $idSaldo[0]['Id']]);
        } else {
            $this->insertar("t_fondofijo_saldos", [
                "IdUsuario" => $this->usuario['Id'],
                "IdTipoCuenta" => $datos['tipoCuenta'],
                "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Fecha" => $this->getFecha(),
                "IdUltimoMovimiento" => $idMovimiento
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'errorBack' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getDetallesFondoFijoXId(int $id)
    {
        $consulta = $this->consulta("select 
                                    tcff.Id,
                                    tcff.Fecharegistro,
                                    tcff.FechaMovimiento,
                                    tcff.FechaAutorizacion,
                                    tcff.IdUsuarioFondoFijo,
                                    nombreUsuario(tcff.IdUsuarioRegistra) as Registra,
                                    nombreUsuario(tcff.IdUsuarioAutoriza) as Autoriza,
                                    tcff.IdTipoCuenta,
                                    (select Nombre from cat_v3_fondofijo_tipos_cuenta where Id = tcff.IdTipoCuenta) as TipoCuenta,
                                    tcff.IdTipoMovimiento,
                                    if(tcff.IdTipoMovimiento = 6 and tcff.Monto < 0, 'Ajuste de Cuenta', 
                                    (select Nombre from cat_v3_comprobacion_tipos_movimiento where Id = tcff.IdTipoMovimiento)) as TipoMovimiento,
                                    case
                                        when tcff.IdTipoMovimiento = 1 then 'Deposito'
                                        when tcff.IdTIpoMovimiento = 3 then 'Reembolso por Cancelación'
                                        when tcff.IdTipoMovimiento = 4 then 'Depósito Gasolina'
                                        when tcff.IdTipoMovimiento = 5 then 'Ajuste de gasolina'
                                        when tcff.IdTipoMovimiento = 6 and tcff.Monto > 0 then 'Abono a Cuenta' 
                                        when tcff.IdTipoMovimiento = 6 and tcff.Monto < 0 then 'Ajuste de Cuenta' 
                                        else ccc.Nombre
                                    end as Nombre,
                                    tcff.IdConcepto,
                                    if(ccc.Extraordinario = 1, 'SI', 'NO') as Extraordinario,
                                    if(tcff.EnPresupuesto = 1, 'SI', 'NO') as EnPresupuesto,
                                    tcff.Monto,
                                    tcff.SaldoPrevio,
                                    tcff.SaldoNuevo,
                                    ticketByServicio(tcff.IdServicio) as Ticket,
                                    (select Nombre from cat_v3_tipos_comprobante where Id = tcff.IdTipoComprobante) as TipoComprobante,
                                    estatus(tcff.IdEstatus) as Estatus,
                                    tcff.IdEstatus,                                    
                                    if(IdOrigen <> 0, sucursalCliente(tcff.IdOrigen), tcff.OrigenOtro) as Origen,
                                    if(IdDestino <> 0, sucursalCliente(tcff.IdDestino), tcff.DestinoOtro) as Destino,
                                    tcff.Observaciones,
                                    tcff.Archivos, 
                                    tcff.XML,
                                    tcff.PDF

                                    from
                                    t_fondofijo_movimientos tcff
                                    left join cat_v3_comprobacion_conceptos ccc on tcff.IdConcepto = ccc.Id
                                    where tcff.Id = '" . $id . "'");
        return $consulta;
    }

    public function cancelarMovimiento(array $datos)
    {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $this->actualizar("t_fondofijo_movimientos", [
            "IdEstatus" => 6,
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "FechaAutorizacion" => $this->getFecha()
        ], ["Id" => $datos['id']]);

        if ($generales['IdEstatus'] == 7) {

            $saldoPrevio = $this->getSaldo($generales['IdUsuarioFondoFijo'], $generales['IdTipoCuenta']);
            $saldoNuevo = (double)$saldoPrevio + abs((double)$generales['Monto']);

            $this->insertar("t_fondofijo_movimientos", [
                "FechaRegistro" => $this->getFecha(),
                "FechaMovimiento" => $this->getFecha(),
                "FechaAutorizacion" => $this->getFecha(),
                "IdUsuarioRegistra" => $this->usuario['Id'],
                "IdUsuarioFondoFijo" => $generales['IdUsuarioFondoFijo'],
                "IdUsuarioAutoriza" => null,
                "IdTipoCuenta" => $generales['IdTipoCuenta'],
                "IdTipoMovimiento" => 3,
                "IdConcepto" => null,
                "IdTipoComprobante" => 3,
                "IdEstatus" => 7,
                "Monto" => abs($generales['Monto']),
                "SaldoPrevio" => str_replace(",", "", number_format($saldoPrevio, 2)),
                "SaldoNuevo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Observaciones" => "Reembolso por cancelación del movimiento " . $datos['id'],
                "EnPresupuesto" => 1,
                "Pagado" => 2
            ]);

            $idMovimiento = $this->ultimoId();

            $idSaldo = $this->consulta("
            select 
            Id 
            from t_fondofijo_saldos 
            where IdUsuario = '" . $generales['IdUsuarioFondoFijo'] . "' 
            and IdTipoCuenta = '" . $generales['IdTipoCuenta'] . "'");


            $this->actualizar("t_fondofijo_saldos", [
                "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
                "Fecha" => $this->getFecha(),
                "IdUltimoMovimiento" => $idMovimiento
            ], ['Id' => $idSaldo[0]['Id']]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200
            ];
        }
    }

    public function getEmpleadosByIdJefe(int $id)
    {
        $arrayUsuarios = [$id];
        $arrayUsuariosTemp = $arrayUsuarios;

        while (!empty($arrayUsuariosTemp)) {
            $ids = implode(",", $arrayUsuariosTemp);
            $consulta = $this->consulta("select Id from cat_v3_usuarios where IdJefe in (" . $ids . ")");
            $arrayUsuariosTemp = [];
            if (!empty($consulta)) {
                foreach ($consulta as $key => $value) {
                    if (!in_array($value['Id'], $arrayUsuarios)) {
                        array_push($arrayUsuarios, $value['Id']);
                        array_push($arrayUsuariosTemp, $value['Id']);
                    }
                }
            }
        }

        return $arrayUsuarios;
    }

    public function pendientesXAutorizar(int $id)
    {
        $empleados = $this->getEmpleadosByIdJefe($id);
        $ids = implode(",", $empleados);

        $consulta = $this->consulta("
            select
        tfm.Id,
        nombreUsuario(tfm.IdUsuarioFondoFijo) as Usuario,
        (select Nombre from cat_v3_fondofijo_tipos_cuenta where Id = tfm.IdTipoCuenta) as TipoCuenta,
        (select Nombre from cat_v3_comprobacion_conceptos where Id = tfm.IdConcepto) as Concepto,                
        tfm.FechaRegistro as FechaRegistro,                
        (select Nombre from cat_v3_tipos_comprobante where Id = tfm.IdTipoComprobante) as TipoComprobante,
        tfm.Monto 
        from 
        t_fondofijo_movimientos tfm
        where tfm.IdUsuarioFondoFijo in (" . $ids . ")
        and tfm.IdEstatus = 8
        order by Id asc");
        return $consulta;
    }

    public function rechazarMovimiento(array $datos)
    {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $this->actualizar("t_fondofijo_movimientos", [
            "IdEstatus" => 10,
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "FechaAutorizacion" => $this->getFecha(),
            "ObservacionesRechazo" => trim($datos['observaciones'])
        ], ["Id" => $datos['id']]);


        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'id' => $generales['IdUsuarioFondoFijo']
            ];
        }
    }

    public function autorizarMovimiento(array $datos)
    {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $saldoPrevio = $this->getSaldo($generales['IdUsuarioFondoFijo'], $generales['IdTipoCuenta']);
        $saldoNuevo = (double)$saldoPrevio + (double)$generales['Monto'];

        $this->actualizar("t_fondofijo_movimientos", [
            "IdEstatus" => 7,
            "FechaAutorizacion" => $this->getFecha(),
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "SaldoPrevio" => $saldoPrevio,
            "SaldoNuevo" => $saldoNuevo,
        ], ["Id" => $datos['id']]);

        $idSaldo = $this->consulta("
            select 
            Id 
            from t_fondofijo_saldos 
            where IdUsuario = '" . $generales['IdUsuarioFondoFijo'] . "' 
            and IdTipoCuenta = '" . $generales['IdTipoCuenta'] . "'");

        $this->actualizar("t_fondofijo_saldos", [
            "Saldo" => str_replace(",", "", number_format($saldoNuevo, 2)),
            "Fecha" => $this->getFecha(),
            "IdUltimoMovimiento" => $datos['id']
        ], ['Id' => $idSaldo[0]['Id']]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'id' => $generales['IdUsuarioFondoFijo']
            ];
        }
    }
    public function getSaldosXTecnico(String $idSupervisor) {
        $resultado= $this->consulta('
            	select
                usua.Id as idUsuario,
        cat.Id as IdTipoCuenta,
        nombreUsuario(usua.Id) as Nombre,
        usua.IdJefe,
        cat.Nombre as TipoCuenta,
        montos.IdUsuario,
        saldo.Saldo,
        saldo.Fecha
        from 
        cat_v3_fondofijo_tipos_cuenta cat 
        inner join cat_v3_fondofijo_montos_x_usuario_cuenta montos on cat.Id = montos.IdTipoCuenta
        left join t_fondofijo_saldos saldo on cat.Id = saldo.IdTipoCuenta and montos.IdUsuario = saldo.IdUsuario
        join cat_v3_usuarios usua on montos.Id=usua.id
        where usua.idJefe='.$idSupervisor.';');
        return $resultado;
    }
    public function getMovimientosTecnico($consulta) {
        $resp=$this->consulta($consulta);
        return $resp;
    }
}
