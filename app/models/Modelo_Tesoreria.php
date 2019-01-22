<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Tesoreria extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function tablaFacturacionOutsourcingAutorizado(array $data) {
        if (in_array('283', $data['permisosAdicionales']) || in_array('283', $data['permisos'])) {
            $idUsuario = '';
        } else {
            $idUsuario = 'AND tfo.IdUsuario = "' . $data['usuario'] . '"';
        }

        $consulta = $this->consulta('SELECT 
                                            tfo.Id,
                                            tfo.IdServicio,
                                            tfo.Folio,
                                            tfo.Fecha,
                                            tfo.Archivo,
                                            tfo.Monto,
                                            tfo.Viatico,
                                            tfo.Vuelta,
                                            tst.Ticket,
                                            sucursal(IdSucursal) Sucursal,
                                            estatus(tfo.IdEstatus) Estatus,
                                            nombreUsuario(tfo.IdUsuario) NombreAtiende,
                                            tst.Atiende
                                        FROM
                                            t_facturacion_outsourcing tfo
                                                INNER JOIN
                                            t_servicios_ticket tst ON tst.Id = tfo.IdServicio
                                        WHERE tfo.IdEstatus = 7
                                        ' . $idUsuario . '
                                        ORDER BY tfo.Folio ASC');
        return $consulta;
    }

    public function facturasOutsourcing(string $listaIds) {
        $consulta = $this->consulta('SELECT
                                        Id,
                                        Vuelta,
                                        Monto,
                                        Viatico,
                                        (SELECT Ticket FROM t_servicios_ticket WHERE Id = IdServicio) Ticket
                                        FROM t_facturacion_outsourcing
                                        WHERE Id IN(' . $listaIds . ')');
        return $consulta;
    }

    public function facturasTesoreriaPago() {
        $consulta = $this->consulta('SELECT
                                            tfod.Id,
                                            nombreUsuario(tfod.IdUsuario) Tecnico,
                                            nombreUsuario(tfo.IdSupervisor) Autoriza,
                                            tfod.Fecha,
                                            tfod.MontoFactura
                                        FROM t_facturacion_outsourcing_documentacion tfod
                                        INNER JOIN t_facturacion_outsourcing tfo
                                        ON tfod.IdVuelta = tfo.Id
                                        WHERE tfo.IdEstatus = 14
                                        AND tfod.Fecha < (select 
                                                            case
                                                            when WEEKDAY(now()) < 4
                                                            then DATE_ADD(now(), INTERVAL + (4 - WEEKDAY(now())) day)
                                                            when WEEKDAY(now()) = 4
                                                            then DATE_ADD(now(), INTERVAL + 0 day)
                                                            when WEEKDAY(now()) = 5
                                                            then DATE_ADD(now(), INTERVAL + 6 day)
                                                            when WEEKDAY(now()) = 6
                                                            then DATE_ADD(now(), INTERVAL + 5 day)
                                                            end as Fecha)
                                        GROUP BY tfod.XML');
        return $consulta;
    }

    public function consultaFacturaOutsourcingDocumantacion(string $idFactura) {
        $consulta = $this->consulta('SELECT 
                                            nombreUsuario(IdUsuario) Tecnico,
                                            XML,
                                            PDF,
                                            MontoFactura
                                        FROM t_facturacion_outsourcing_documentacion
                                        WHERE Id = "' . $idFactura . '"');
        return $consulta;
    }

    public function consultaFacturasOutsourcingDocumantacionXML(string $xml) {
        $consulta = $this->consulta('SELECT 
                                            *
                                        FROM t_facturacion_outsourcing_documentacion
                                        WHERE XML = "' . $xml . '"');
        return $consulta;
    }

    public function consultaDetallesFactura(string $xml) {
        $consulta = $this->consulta('SELECT 
                                        tfo.IdServicio,
                                        (SELECT 
                                                Ticket
                                            FROM
                                                t_servicios_ticket
                                            WHERE
                                                Id = tfo.IdServicio) Ticket,
                                        tfo.Folio,
                                        tfo.Vuelta,
                                        (SELECT 
                                                sucursal(IdSucursal)
                                            FROM
                                                t_servicios_ticket
                                            WHERE
                                                Id = tfo.IdServicio) Sucursal,
                                            nombreUsuario(tfo.IdUsuario) Tecnico,
                                            tfo.Fecha,
                                            tfo.Archivo,
                                            tfo.Monto,
                                            tfo.Viatico
                                    FROM
                                        t_facturacion_outsourcing_documentacion tfoc
                                            INNER JOIN
                                        t_facturacion_outsourcing tfo ON tfoc.IdVuelta = tfo.Id
                                    WHERE tfoc.XML = "' . $xml . '"');
        return $consulta;
    }

    public function consultaCorreoUsuario(string $usuario) {
        $consulta = $this->consulta('SELECT 
                                            EmailCorporativo
                                        FROM cat_v3_usuarios
                                        WHERE Id = "' . $usuario . '"');
        return $consulta[0]['EmailCorporativo'];
    }

    public function evidenciaPagoFactura(string $idVuelta) {
        $consulta = $this->consulta('SELECT 
                                            ArchivoPago 
                                        FROM t_facturacion_outsourcing_documentacion
                                        WHERE IdVuelta = "' . $idVuelta . '"');

        if (!empty($consulta)) {
            return $consulta[0]['ArchivoPago'];
        } else {
            return FALSE;
        }
    }

    public function consultaFactura(string $id) {
        $consulta = $this->consulta('SELECT 
                                        tfo.*,
                                        nombreUsuario(IdSupervisor) Supervisor,
                                        tst.Ticket,
                                        tst.Descripcion,
                                        tst.IdSolicitud,
                                        (SELECT Asunto FROM t_solicitudes_internas WHERE IdSolicitud = tst.IdSolicitud) Asunto,
                                        (SELECT Descripcion FROM t_solicitudes_internas WHERE IdSolicitud = tst.IdSolicitud) DescripcionSolicitud
                                    FROM
                                        t_facturacion_outsourcing tfo
                                    INNER JOIN t_servicios_ticket tst
                                    ON tst.Id = tfo.IdServicio
                                    WHERE
                                        tfo.Id = "' . $id . '"');
        return $consulta[0];
    }

    public function consultaObservacionesFactura(string $id) {
        $consulta = $this->consulta('SELECT 
                                            Observaciones 
                                        FROM t_facturacion_outsourcing
                                        WHERE Id = "' . $id . '"');
        return $consulta[0]['Observaciones'];
    }

    public function vueltasAnteriores(array $datos) {
        $ultimaFechaVuelta = mdate("%Y-%m-%d %H:%i:%s", strtotime("-14 hour"));

        $consulta = $this->consulta('SELECT
                                        tfo.Fecha, cvs.Nombre
                                    FROM
                                        t_facturacion_outsourcing tfo
                                            INNER JOIN
                                        t_servicios_ticket tst ON tfo.IdServicio = tst.Id
                                            INNER JOIN
                                        cat_v3_sucursales cvs ON tst.IdSucursal = cvs.Id
                                    WHERE Folio = "' . $datos['folio'] . '"
                                    AND Fecha >= "' . $ultimaFechaVuelta . '"
                                    ORDER BY Fecha DESC LIMIT 1');
        return $consulta;
    }

    public function vueltasFacturasOutsourcing(string $folio) {
        $consulta = $this->consulta('SELECT 
                                            Vuelta
                                        FROM
                                        t_facturacion_outsourcing
                                        WHERE Folio = "' . $folio . '"
                                        ORDER BY Id DESC LIMIT 1');

        return $consulta;
    }

    public function guardarViaticosOutsourcing(array $datos) {
        foreach ($datos['viaticos'] as $value) {
            $viaticos = explode("_", $value);
            $consulta = $this->consulta('SELECT * FROM cat_v3_viaticos_outsourcing WHERE IdTecnico = "' . $datos['tecnico'] . '" AND IdSucursal = "' . $viaticos[0] . '"');

            if (!empty($consulta)) {
                $this->actualizar('cat_v3_viaticos_outsourcing', [
                    'IdTecnico' => $datos['tecnico'],
                    'IdSucursal' => $viaticos[0],
                    'Monto' => $viaticos[1]], ['Id' => $consulta[0]['Id']]);
            } else {
                $this->insertar('cat_v3_viaticos_outsourcing', array('IdTecnico' => $datos['tecnico'],
                    'IdSucursal' => $viaticos[0],
                    'Monto' => $viaticos[1]));
            }
        }

        return TRUE;
    }

    public function guardarMontosOutsourcing(array $datos) {
        foreach ($datos as $key => $value) {
            $consulta = $this->consulta('SELECT * FROM t_montos_x_vuelta_outsourcing WHERE Concepto = "' . $key . '"');

            if (!empty($consulta)) {
                $this->actualizar('t_montos_x_vuelta_outsourcing', [
                    'Concepto' => $key,
                    'Monto' => $value], ['Id' => $consulta[0]['Id']]);
            } else {
                $this->insertar('t_montos_x_vuelta_outsourcing', array(
                    'Concepto' => $key,
                    'Monto' => $value));
            }
        }

        return TRUE;
    }

    public function guardarFacturaOutsourcingDocumentacion(array $datos) {
        $this->iniciaTransaccion();
        $rutaActual = getcwd();

        foreach ($datos['datosFacturasOutsourcing'] as $k => $v) {
            foreach ($datos['archivos'] as $key => $value) {
                $extencion = pathinfo($value, PATHINFO_EXTENSION);
                if ($extencion === 'pdf') {
                    $nombreNuevo = $rutaActual . "/storage/Archivos/" . $datos['carpeta'] . "Tickets_" . $datos['tickets'] . "_Total_" . $datos['total'] . ".pdf";
                    $rutaBD = "/storage/Archivos/" . $datos['carpeta'] . "Tickets_" . $datos['tickets'] . "_Total_" . $datos['total'] . ".pdf";
                    $rutaPDF = $rutaBD;
                } else {
                    $nombreNuevo = $rutaActual . "/storage/Archivos/" . $datos['carpeta'] . "Tickets_" . $datos['tickets'] . "_Total_" . $datos['total'] . ".xml";
                    $rutaBD = "/storage/Archivos/" . $datos['carpeta'] . "Tickets_" . $datos['tickets'] . "_Total_" . $datos['total'] . ".xml";
                    $rutaXML = $rutaBD;
                }
                copy($rutaActual . $value, $nombreNuevo);
            }

            $this->insertar('t_facturacion_outsourcing_documentacion', array(
                'IdVuelta' => $v['Id'],
                'IdUsuario' => $datos['usuario'],
                'Fecha' => $datos['fecha'],
                'XML' => $rutaXML,
                'PDF' => $rutaPDF,
                'MontoFactura' => $datos['total'],
                'Folio' => $datos['folio'],
                'Serie' => $datos['serie']));

            $this->actualizar('t_facturacion_outsourcing', [
                'IdEstatus' => '14',
                'FechaEstatus' => $datos['fecha']], ['Id' => $v['Id']]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return TRUE;
        }
    }

    public function guardarVueltaOutsourcing(array $datos) {
        $consulta = $this->insertar('t_facturacion_outsourcing', $datos);

        if (!empty($consulta)) {
            return parent::connectDBPrueba()->insert_id();
        } else {
            return FALSE;
        }
    }

    public function guardarEvidenciaPagoFactura(array $datos) {
        $this->iniciaTransaccion();
        $facturasDocumentacion = $this->consultaFacturasOutsourcingDocumantacionXML($datos['xml']);

        foreach ($facturasDocumentacion as $key => $value) {

            $this->actualizar('t_facturacion_outsourcing_documentacion', [
                'IdUsuarioPaga' => $datos['usuarioPaga'],
                'FechaPago' => $datos['fecha'],
                'ArchivoPago' => $datos['evidencias']], ['IdVuelta' => $value['IdVuelta']]);

            $this->actualizar('t_facturacion_outsourcing', [
                'IdEstatus' => '15',
                'FechaEstatus' => $datos['fecha']], ['Id' => $value['IdVuelta']]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
        } else {
            $this->terminaTransaccion();
            return $facturasDocumentacion[0]['IdUsuario'];
        }
    }

    public function getFondosFijos(int $id = null) {
        $condicion = '';
        if (!is_null($id)) {
            $condicion = " where cffu.IdUsuario = '" . $id . "'";
        }

        $consulta = $this->consulta("SELECT
                                    cffu.IdUsuario,
                                    nombreUsuario(cffu.IdUsuario) as Usuario,                                    
                                    cffu.Monto as MontoUsuario,
                                    (select 
                                        FechaMovimiento 
                                        from t_comprobacion_fondo_fijo 
                                        where IdUsuarioFF = cffu.IdUsuario 
                                        and IdTipoMovimiento = 1 
                                        and IdEstatus = 7 
                                        order by FechaAutorizacion desc limit 1) as Fecha,
                                    /*tcff.FechaMovimiento as Fecha,*/
                                    (select 
                                        Monto
                                        from t_comprobacion_fondo_fijo 
                                        where IdUsuarioFF = cffu.IdUsuario 
                                        and IdTipoMovimiento = 1 
                                        and IdEstatus = 7 
                                        order by FechaAutorizacion desc limit 1) as Monto,
                                    tcff.SaldoGasolina,    
                                    tcff.Saldo,
                                    tcff.FechaAutorizacion as FechaSaldo,
                                    cffu.Flag
                                    from cat_v3_fondo_fijo_usuarios cffu
                                    LEFT JOIN t_comprobacion_fondo_fijo tcff on tcff.Id = (
                                                                                    select 
                                                                                    Id 
                                                                                    from t_comprobacion_fondo_fijo 
                                                                                    where IdUsuarioFF = cffu.IdUsuario 
                                                                                    and IdEstatus = 7 
                                                                                    order by FechaAutorizacion desc 
                                                                                    limit 1)
                                    " . $condicion);
        return $consulta;
    }

    public function getDetallesFondoFijoXUsuario(int $id) {
        $fechas = $this->consulta("select 
                                   DATE_SUB(CONCAT(DATE_FORMAT(now(),'%Y-%m'),'-01'), INTERVAL 2 DAY) as FechaIni,
                                   CONCAT(DATE_FORMAT(now(),'%Y-%m'),'-31') as FechaFin");


        $consulta = $this->consulta("select 
                                    tcff.Id,
                                    tcff.FechaAutorizacion as Fecha,
                                    tcff.FechaMovimiento,
                                    case
                                        when tcff.IdTipoMovimiento = 1 then 'Depósito Fondo Fijo'
                                        when tcff.IdTipoMovimiento = 3 then 'Reembolso por Cancelación'
                                        when tcff.IdTipoMovimiento = 4 then 'Depósito Gasolina'
                                        when tcff.IdTipoMovimiento = 5 then 'Ajuste de Gasolna'
                                        else ccc.Nombre
                                    end as Nombre,
                                    /*if(tcff.IdTipoMovimiento = 1, 'Depósito', if(tcff.IdTipoMovimiento = 3 ,'Reembolso por Cancelación' , ccc.Nombre)) as Nombre,*/
                                    if(ccc.Extraordinario = 1, 'SI', 'NO') as Extraordinario,
                                    if(tcff.EnPresupuesto = 1, 'SI', 'NO') as EnPresupuesto,
                                    tcff.Monto,
                                    tcff.Saldo,
                                    tcff.SaldoGasolina,
                                    ticketByServicio(tcff.IdServicio) as Ticket,
                                    (select Nombre from cat_v3_tipos_comprobante where Id = tcff.IdTipoComprobante) as TipoComprobante,
                                    estatus(tcff.IdEstatus) as Estatus,
                                    tcff.IdEstatus,
                                    tcff.Cobrable

                                    from
                                    t_comprobacion_fondo_fijo tcff
                                    left join cat_v3_comprobacion_conceptos ccc on tcff.IdConcepto = ccc.Id
                                    where tcff.IdUsuarioFF = '" . $id . "'
                                    and tcff.Fecha >= DATE_FORMAT(DATE_SUB(now(),INTERVAL 2 MONTH),'%Y-%m-%d')
                                    order by tcff.FechaAutorizacion desc");
        return $consulta;
    }

    public function getComprobacionesSinPagar(int $id) {
        $consulta = $this->consulta("select 
                                    Id,
                                    case
                                            when IdConcepto <> 8 and IdTipoComprobante in (2,3) then 1
                                            when IdConcepto = 8 then 2
                                            when IdConcepto <> 8 and Receptor = 'SSO0101179Z7' then 3
                                            when IdConcepto <> 8 and Receptor = 'RSD130305DI7' then 4
                                    end as TipoRegistro,
                                    (select Nombre from cat_v3_comprobacion_conceptos where Id = IdConcepto) as Concepto,
                                    Monto,
                                    if(EnPresupuesto = 1, 'Automático', nombreUsuario(IdUsuarioAutoriza)) as Autoriza,
                                    FechaAutorizacion,
                                    Receptor
                                    from t_comprobacion_fondo_fijo
                                    where IdUsuarioFF = '" . $id . "'
                                    and Pagado = 0
                                    and IdTipoMovimiento = 2
                                    and IdEstatus = 7");
        return $consulta;
    }

    public function getDetallesFondoFijoXId(int $id) {
        $consulta = $this->consulta("select 
                                    tcff.Id,
                                    tcff.FechaAutorizacion as Fecha,
                                    tcff.FechaMovimiento,
                                    tcff.IdTipoMovimiento,
                                    tcff.IdUsuarioFF,
                                    (select Nombre from cat_v3_comprobacion_tipos_movimiento where Id = tcff.IdTipoMovimiento) as TipoMovimiento,
                                    case
                                        when tcff.IdTipoMovimiento = 1 then 'Deposito'
                                        when tcff.IdTIpoMovimiento = 3 then 'Reembolso por Cancelación'
                                        when tcff.IdTipoMovimiento = 4 then 'Depósito Gasolina'
                                        when tcff.IdTipoMovimiento = 5 then 'Ajuste de gasolina'
                                        else ccc.Nombre
                                    end as Nombre,
                                    tcff.IdConcepto,
                                    if(ccc.Extraordinario = 1, 'SI', 'NO') as Extraordinario,
                                    if(tcff.EnPresupuesto = 1, 'SI', 'NO') as EnPresupuesto,
                                    tcff.Monto,
                                    tcff.Saldo,
                                    ticketByServicio(tcff.IdServicio) as Ticket,
                                    (select Nombre from cat_v3_tipos_comprobante where Id = tcff.IdTipoComprobante) as TipoComprobante,
                                    estatus(tcff.IdEstatus) as Estatus,
                                    tcff.IdEstatus,
                                    nombreUsuario(tcff.IdUsuarioAutoriza) as Autoriza,
                                    if(IdOrigen <> 0, sucursalCliente(tcff.IdOrigen), tcff.OrigenOtro) as Origen,
                                    if(IdDestino <> 0, sucursalCliente(tcff.IdDestino), tcff.DestinoOtro) as Destino,
                                    tcff.Observaciones,
                                    tcff.Archivos, 
                                    tcff.XML,
                                    tcff.PDF

                                    from
                                    t_comprobacion_fondo_fijo tcff
                                    left join cat_v3_comprobacion_conceptos ccc on tcff.IdConcepto = ccc.Id
                                    where tcff.Id = '" . $id . "'");
        return $consulta;
    }

    public function getNombreUsuarioById(int $id) {
        $consulta = $this->consulta("select nombreUsuario('" . $id . "') as Usuario");
        return $consulta[0]['Usuario'];
    }

    public function registrarDeposito(array $datos) {
        $this->iniciaTransaccion();

        $saldo = $this->getSaldoByUsuario($datos['id']);
        $saldoGasolina = $this->getSaldoGasolinaByUsuario($datos['id']);

        $saldoNuevo = ((float) $saldo + (float) $datos['monto']);
        $saldoGasolinaNuevo = (float) $saldoGasolina;
        $tipoMovimiento = 1;
        if (in_array($datos['concepto'], [2, '2'])) {
            $saldoNuevo = (float) $saldo;
            $saldoGasolinaNuevo = ((float) $saldoGasolina + (float) $datos['monto']);
            $tipoMovimiento = 4;
        }

        $this->insertar("t_comprobacion_fondo_fijo", [
            "IdUsuario" => $this->usuario['Id'],
            "Fecha" => $this->getFecha(),
            "IdUsuarioFF" => $datos['id'],
            "IdTipoMovimiento" => $tipoMovimiento,
            "IdTipoComprobante" => 2,
            "IdEstatus" => 7,
            "Monto" => $datos['monto'],
            "Saldo" => $saldoNuevo,
            "SaldoGasolina" => $saldoGasolinaNuevo,
            "FechaMovimiento" => str_replace("T", " ", $datos['fecha']),
            "Observaciones" => $datos["observaciones"],
            "Archivos" => $datos["archivos"],
            "FechaAutorizacion" => $this->getFecha(),
            "IdUsuarioAutoriza" => $this->usuario['Id']
        ]);

        if (isset($datos['comprobaciones']) && count($datos['comprobaciones']) > 0) {
            $comprobaciones = explode(",", $datos['comprobaciones']);
            foreach ($comprobaciones as $key => $value) {
                if ($value != '') {
                    $this->actualizar("t_comprobacion_fondo_fijo", [
                        'Pagado' => 1,
                        'MontoPagado' => $datos['monto'],
                        'ArchivosPago' => $datos['archivos']
                            ], ['Id' => $value]);
                }
            }
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
//            $this->roolbackTransaccion();
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function getSaldoByUsuario(int $id) {
        $saldo = $this->consulta(""
                . "select "
                . "Saldo "
                . "from t_comprobacion_fondo_fijo "
                . "where Id = ("
                . "             select "
                . "             Id "
                . "             from t_comprobacion_fondo_fijo "
                . "             where IdUsuarioFF = '" . $id . "' "
                . "             and (IdEstatus = 7 || ("
                . "                                     IdEstatus = 10 "
                . "                                     and Cobrable = 1 "
                . "                                     and IdUsuarioAutoriza is not null "
                . "                                     and FechaAutorizacion is not null)"
                . "                                    )  "
                . "             order by FechaAutorizacion desc limit 1)");
        if (!empty($saldo)) {
            $saldo = $saldo[0]['Saldo'];
        } else {
            $saldo = 0;
        }

        return $saldo;
    }

    public function getMontoDepositoSiccob(int $id) {
        $monto = $this->consulta("select
                                if(sum(Monto) is null, 0, sum(Monto)) as Total
                                from t_comprobacion_fondo_fijo
                                where IdUsuarioFF = '" . $id . "'
                                and Pagado = 0
                                and Receptor = 'SSO0101179Z7'
                                and IdEstatus = 7;");
        if (!empty($monto)) {
            $monto = $monto[0]['Total'];
        } else {
            $monto = 0;
        }

        return $monto;
    }

    public function getMontoDepositoResidig(int $id) {
        $monto = $this->consulta("select
                                if(sum(Monto) is null, 0, sum(Monto)) as Total
                                from t_comprobacion_fondo_fijo
                                where IdUsuarioFF = '" . $id . "'
                                and Pagado = 0
                                and Receptor = 'RSD130305DI7'
                                and IdEstatus = 7;");
        if (!empty($monto)) {
            $monto = $monto[0]['Total'];
        } else {
            $monto = 0;
        }

        return $monto;
    }

    public function getMontoDepositoGasolina(int $id) {
        $monto = $this->consulta("select 
                                if(sum(Monto) is null, 0, sum(Monto)) as Total 
                                from t_comprobacion_fondo_fijo 
                                where IdUsuarioFF = '" . $id . "' 
                                and Pagado = 0 
                                and IdConcepto = 8 
                                and IdEstatus = 7 
                                and IdTipoMovimiento = 2;");
        if (!empty($monto)) {
            $monto = $monto[0]['Total'];
        } else {
            $monto = 0;
        }

        return $monto;
    }

    public function getMontoDepositoOtros(int $id) {
        $monto = $this->consulta("select 
                                if(sum(Monto) is null, 0, sum(Monto)) as Total 
                                from t_comprobacion_fondo_fijo 
                                where IdUsuarioFF = '" . $id . "' 
                                and Pagado = 0 
                                and IdTipoComprobante in (2,3) 
                                and IdConcepto <> 8 
                                and IdEstatus = 7 
                                and IdTipoMovimiento = 2;");
        if (!empty($monto)) {
            $monto = $monto[0]['Total'];
        } else {
            $monto = 0;
        }

        return $monto;
    }

    public function getSaldoGasolinaByUsuario(int $id) {
        $saldo = $this->consulta(""
                . "select "
                . "SaldoGasolina "
                . "from t_comprobacion_fondo_fijo "
                . "where Id = ("
                . "             select "
                . "             Id "
                . "             from t_comprobacion_fondo_fijo "
                . "             where IdUsuarioFF = '" . $id . "' "
                . "             and (IdEstatus = 7 || ("
                . "                                     IdEstatus = 10 "
                . "                                     and Cobrable = 1 "
                . "                                     and IdUsuarioAutoriza is not null "
                . "                                     and FechaAutorizacion is not null)"
                . "                                    )  "
                . "             order by FechaAutorizacion desc limit 1)");
        if (!empty($saldo)) {
            $saldo = $saldo[0]['SaldoGasolina'];
        } else {
            $saldo = 0;
        }

        return $saldo;
    }

    public function getSaldoRechazadoSinPagar(int $id) {
        $saldo = $this->consulta("select 
                                sum(Monto) as Total
                                from t_comprobacion_fondo_fijo 
                                where IdUsuarioFF = '" . $id . "'
                                and IdEstatus = 10
                                and Cobrable = 1
                                and Pagado = 0");
        if (!empty($saldo)) {
            $saldo = abs($saldo[0]['Total']);
        } else {
            $saldo = 0;
        }

        return $saldo;
    }

    public function getUltimoMovimientoSaldo(int $id) {
        $ultimo = $this->consulta(""
                . "select "
                . "Id "
                . "from t_comprobacion_fondo_fijo "
                . "where Id = (select Id from t_comprobacion_fondo_fijo where IdUsuarioFF = '" . $id . "' and IdEstatus = 7 order by FechaAutorizacion desc limit 1)");
        if (!empty($ultimo)) {
            $ultimo = $ultimo[0]['Id'];
        } else {
            $ultimo = '';
        }

        return $ultimo;
    }

    public function getSaldoXAutorizarByUsuario(int $id) {
        $saldo = $this->consulta(""
                . "select "
                . "sum(Monto) as Saldo "
                . "from t_comprobacion_fondo_fijo "
                . "where IdUsuarioFF = '" . $id . "' and IdEstatus = 8");
        if (!empty($saldo)) {
            $saldo = $saldo[0]['Saldo'];
        } else {
            $saldo = 0;
        }

        return $saldo;
    }

    public function getTicketsByUsuario(int $id) {
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

    public function getSucursales() {
        $consulta = $this->consulta("select Id, sucursalCliente(Id) as Nombre from cat_v3_sucursales where Flag = 1 order by Nombre");
        return $consulta;
    }

    public function cargaMontoMaximoConcepto(array $datos) {
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

    public function cargaServiciosTicket(array $datos) {
        $consulta = $this->consulta("select 
                                    Id,
                                    tipoServicio(tst.IdTipoServicio) as Tipo,
                                    tst.Descripcion
                                    from t_servicios_ticket tst 
                                    where Ticket = '" . $datos['ticket'] . "'
                                    and tst.Atiende = '" . $datos['usuario'] . "'");
        return $consulta;
    }

    public function registrarComprobante(array $datos) {
        $this->iniciaTransaccion();

        $revisarUUID = $this->consulta(""
                . "select * "
                . "from t_comprobacion_fondo_fijo "
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

        $saldo = $this->getSaldoByUsuario($this->usuario['Id']);
        $saldoGasolina = $this->getSaldoGasolinaByUsuario($this->usuario['Id']);

        $saldoNuevo = ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? ((float) $saldo + (float) $datos['monto']) : null;
        $saldoGasolinaNuevo = (float) $saldoGasolina;
        if (in_array($datos['concepto'], [8, '8'])) {
            $saldoNuevo = (float) $saldo;
            $saldoGasolinaNuevo = ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? ((float) $saldoGasolina + (float) $datos['monto']) : null;
        }

        $this->insertar("t_comprobacion_fondo_fijo", [
            "IdUsuario" => $this->usuario['Id'],
            "Fecha" => $this->getFecha(),
            "IdUsuarioFF" => $this->usuario['Id'],
            "IdTipoMovimiento" => 2,
            "IdConcepto" => $datos['concepto'],
            "IdTipoComprobante" => $datos['tipoComprobante'],
            "IdEstatus" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? 7 : 8,
            "Monto" => $datos['monto'],
            "Saldo" => $saldoNuevo,
            "SaldoGasolina" => $saldoGasolinaNuevo,
            "FechaMovimiento" => str_replace("T", " ", $datos['fecha']),
            "IdServicio" => $datos['servicio'],
            "IdOrigen" => $datos['origen'],
            "IdDestino" => $datos['destino'],
            "OrigenOtro" => $datos['stringOrigen'],
            "DestinoOtro" => $datos['stringDestino'],
            "Observaciones" => $datos["observaciones"],
            "Archivos" => $datos["archivos"],
            "FechaAutorizacion" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? $this->getFecha() : null,
            "IdUsuarioAutoriza" => ($datos['enPresupuesto'] == 1 && $datos['stringDestino'] == "") ? $this->usuario['Id'] : null,
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

    public function cancelarMovimiento(array $datos) {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $this->actualizar("t_comprobacion_fondo_fijo", [
            "IdEstatus" => 6,
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "FechaAutorizacion" => $this->getFecha()
                ], ["Id" => $datos['id']]);

        if ($generales['IdEstatus'] == 7) {
            $saldo = $this->getSaldoByUsuario($generales['IdUsuarioFF']);
            $ultimo = $this->getUltimoMovimientoSaldo($generales['IdUsuarioFF']);

            $saldoNuevo = ($ultimo < $datos['id']) ? (float) $saldo : ((float) $saldo + (float) abs($generales['Monto']));

            $this->insertar("t_comprobacion_fondo_fijo", [
                "IdUsuario" => $this->usuario['Id'],
                "Fecha" => $this->getFecha(),
                "IdUsuarioFF" => $this->usuario['Id'],
                "IdTipoMovimiento" => 3,
                "IdTipoComprobante" => 3,
                "IdEstatus" => 7,
                "Monto" => abs($generales['Monto']),
                "Saldo" => $saldoNuevo,
                "FechaMovimiento" => $this->getFecha(),
                "Observaciones" => "Reembolso por cancelación del movimiento " . $datos['id'],
                "Archivos" => "",
                "FechaAutorizacion" => $this->getFecha(),
                "IdUsuarioAutoriza" => $this->usuario['Id']
            ]);
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

    public function rechazarMovimiento(array $datos) {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $this->actualizar("t_comprobacion_fondo_fijo", [
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
                'id' => $generales['IdUsuarioFF']
            ];
        }
    }

    public function autorizarMovimiento(array $datos) {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $saldo = $this->getSaldoByUsuario($generales['IdUsuarioFF']);
        $saldoGasolina = $this->getSaldoGasolinaByUsuario($generales['IdUsuarioFF']);

        $saldoNuevo = ((float) $saldo + (float) $generales['Monto']);
        $saldoGasolinaNuevo = (float) $saldoGasolina;
        if (in_array($generales['IdConcepto'], [8, '8'])) {
            $saldoNuevo = (float) $saldo;
            $saldoGasolinaNuevo = ((float) $saldoGasolina + (float) $generales['Monto']);
        }

        $this->actualizar("t_comprobacion_fondo_fijo", [
            "IdEstatus" => 7,
            "Saldo" => $saldoNuevo,
            "SaldoGasolina" => $saldoGasolinaNuevo,
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "FechaAutorizacion" => $this->getFecha()
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
                'id' => $generales['IdUsuarioFF']
            ];
        }
    }

    public function ajustarGasolina(array $datos) {
        $this->iniciaTransaccion();

        $saldo = $this->getSaldoByUsuario($datos['id']);
        $saldoGasolina = $this->getSaldoGasolinaByUsuario($datos['id']);
        $saldoGasolinaNuevo = (float) $datos['monto'];
        $diferencia = (float) $saldoGasolina - (float) $saldoGasolinaNuevo;

        $this->insertar("t_comprobacion_fondo_fijo", [
            "IdUsuario" => $this->usuario['Id'],
            "Fecha" => $this->getFecha(),
            "IdUsuarioFF" => $datos['id'],
            "IdTipoMovimiento" => 5,
            "IdTipoComprobante" => 3,
            "IdEstatus" => 7,
            "Monto" => (float) $diferencia * -1,
            "Saldo" => (float) $saldo,
            "SaldoGasolina" => $saldoGasolinaNuevo,
            "FechaMovimiento" => $datos['fecha'],
            "Observaciones" => "Ajuste de Gasolina",
            "Archivos" => "",
            "FechaAutorizacion" => $this->getFecha(),
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "EnPresupuesto" => 1
        ]);

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
                'id' => $datos['id']
            ];
        }
    }

    public function rechazarMovimientoCobrable(array $datos) {
        $this->iniciaTransaccion();

        $generales = $this->getDetallesFondoFijoXId($datos['id'])[0];
        $saldo = $this->getSaldoByUsuario($generales['IdUsuarioFF']);
        $saldoGasolina = $this->getSaldoGasolinaByUsuario($generales['IdUsuarioFF']);

        $saldoNuevo = ((float) $saldo + (float) $generales['Monto']);
        $saldoGasolinaNuevo = (float) $saldoGasolina;
        if (in_array($generales['IdConcepto'], [8, '8'])) {
            $saldoNuevo = (float) $saldo;
            $saldoGasolinaNuevo = ((float) $saldoGasolina + (float) $generales['Monto']);
        }

        $this->actualizar("t_comprobacion_fondo_fijo", [
            "IdEstatus" => 10,
            "Saldo" => $saldoNuevo,
            "SaldoGasolina" => $saldoGasolinaNuevo,
            "IdUsuarioAutoriza" => $this->usuario['Id'],
            "FechaAutorizacion" => $this->getFecha(),
            "EnPresupuesto" => 0,
            "ObservacionesRechazo" => trim($datos['observaciones']),
            "Cobrable" => 1
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
                'id' => $generales['IdUsuarioFF']
            ];
        }
    }

    public function getEmpleadosByIdJefe(int $id) {
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

    public function getComprobacionesXAutorizar(int $id) {
        $empleados = $this->getEmpleadosByIdJefe($id);
        $ids = implode(",", $empleados);

        $consulta = $this->consulta("select 
                                    tcff.Id,
                                    nombreUsuario(tcff.IdUsuarioFF) as Usuario,
                                    tcff.Fecha,
                                    tcff.FechaMovimiento,
                                    if(tcff.IdTipoMovimiento = 1, 'Depósito', if(tcff.IdTipoMovimiento = 3 ,'Reembolso por Cancelación' , ccc.Nombre)) as Nombre,
                                    if(ccc.Extraordinario = 1, 'SI', 'NO') as Extraordinario,
                                    if(tcff.EnPresupuesto = 1, 'SI', 'NO') as EnPresupuesto,
                                    tcff.Monto,                                    
                                    ticketByServicio(tcff.IdServicio) as Ticket,
                                    (select Nombre from cat_v3_tipos_comprobante where Id = tcff.IdTipoComprobante) as TipoComprobante                                    
                                    from
                                    t_comprobacion_fondo_fijo tcff
                                    left join cat_v3_comprobacion_conceptos ccc on tcff.IdConcepto = ccc.Id
                                    where tcff.IdUsuarioFF in (" . $ids . ")
                                    and tcff.IdEstatus = 8
                                    order by tcff.Fecha");

        return $consulta;
    }

    public function cambiarEstatusTablaFacturacionOutsourcing(array $datos) {
        $consulta = $this->actualizar('t_facturacion_outsourcing', [
            "IdEstatus" => $datos['estatus']
                ], ["Id" => $datos['idVuelta']]);
        
        return $consulta;
    }

}
