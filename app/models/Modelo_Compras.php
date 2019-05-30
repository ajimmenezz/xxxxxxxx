<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Compras extends Modelo_Base
{
    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getSAEProductos()
    {
        $consulta = $this->consulta("
        select 
        Clave, 
        Nombre 
        from cat_v3_equipos_sae 
        where Flag = 1 
        order by Nombre");
        return $consulta;
    }

    public function insertarSolicitudCompra(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertar("t_solicitudes_compra", [
            'IdUsuario' => $this->usuario['Id'],
            'IdCliente' => $datos['idCliente'],
            'IdProyecto' => $datos['idProyecto'],
            'IdSucursal' => $datos['idSucursal'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => $datos['observaciones']
        ]);

        $idSolicitud = $this->ultimoId();

        $this->insertar("t_solicitudes_compra_estatus", [
            'IdSolicitudCompra' => $idSolicitud,
            'IdEstatus' => 9,
            'IdUsuario' => $this->usuario['Id'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => ''
        ]);

        $partidas = json_decode($datos['partidas'], true);

        foreach ($partidas as $key => $value) {
            $this->insertar("t_solicitudes_compra_partidas", [
                'IdSolicitudCompra' => $idSolicitud,
                'ClaveSAE' => $value['cve'],
                'DescripcionSAE' => $value['producto'],
                'Cantidad' => $value['cantidad']
            ]);
        }

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            return ['code' => 200, 'id' => $idSolicitud];
        }
    }


    public function actualizarArchivosSolicitud(int $idSolicitud, string $archivos)
    {

        $this->actualizar("t_solicitudes_compra", [
            'Archivos' => $archivos
        ], ['Id' => $idSolicitud]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'id' => $idSolicitud];
        }
    }

    public function getListaMisSolicitudes(int $id = null)
    {
        $condicion = "";
        if (!is_null($id)) {
            $condicion = "and tsc.Id = '" . $id . "'";
        }

        $consulta = $this->consulta("select
        tsc.*,
        tsce.IdEstatus,
        estatus(tsce.IdEstatus) as Estatus,
        tsce.Fecha
        from t_solicitudes_compra tsc 
        inner join t_solicitudes_compra_estatus tsce on tsce.Id = (
        select MAX(Id) 
        from t_solicitudes_compra_estatus 
        where IdSolicitudCompra = tsc.Id)        
        where tsc.IdUsuario = '" . $this->usuario['Id'] . "' " . $condicion);
        $solicitudes = [];

        foreach ($consulta as $key => $value) {
            array_push($solicitudes, [
                'Id' => $value['Id'],
                'IdCliente' => $value['IdCliente'],
                'IdProyecto' => $value['IdProyecto'],
                'IdSucursal' => $value['IdSucursal'],
                'Cliente' => $this->getClienteGapsi($value['IdCliente']),
                'Proyecto' => $this->getProyectoGapsi($value['IdProyecto']),
                'Sucursal' => $this->getSucursalGapsi($value['IdSucursal']),
                'Fecha' => $value['Fecha'],
                'IdEstatus' => $value['IdEstatus'],
                'Estatus' => $value['Estatus'],
                'Descripcion' => $value['Descripcion'],
                'Archivos' => $value['Archivos']
            ]);
        }

        return $solicitudes;
    }

    public function getPartidasSolicitudCompra(int $solicitud)
    {
        $consulta = $this->consulta("
        select 
        ClaveSAE, 
        DescripcionSAE, 
        Cantidad 
        from t_solicitudes_compra_partidas 
        where IdSolicitudCompra = '" . $solicitud . "'");
        return $consulta;
    }

    private function getClienteGapsi(int $cliente)
    {
        $consulta = $this->consultaGapsi("select Nombre from db_Clientes where ID = '" . $cliente . "'");
        return $consulta[0]['Nombre'];
    }

    private function getProyectoGapsi(int $proyecto)
    {
        $consulta = $this->consultaGapsi("select Descripcion from db_Proyectos where ID = '" . $proyecto . "'");
        return $consulta[0]['Descripcion'];
    }

    private function getSucursalGapsi(int $sucursal)
    {
        $consulta = $this->consultaGapsi("select Nombre from db_Sucursales where ID = '" . $sucursal . "'");
        return $consulta[0]['Nombre'];
    }
}
