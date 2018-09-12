<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Tesoreria extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function tablaFacturacionOutsourcingAutorizado(array $data) {
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
                                        AND tfo.IdUsuario = "' . $data['usuario'] . '"
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
                                            tfo.Archivo
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
        }else{
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

    public function vueltasAnteriores(string $folio) {
        $ultimaFechaVuelta = mdate("%Y-%m-%d %H:%i:%s", strtotime("-14 hour"));

        $consulta = $this->consulta('SELECT 
                                        Fecha 
                                    FROM t_facturacion_outsourcing
                                    WHERE Folio = "' . $folio . '"
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

}
