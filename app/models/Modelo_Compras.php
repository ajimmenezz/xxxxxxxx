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

    public function getListaMisSolicitudes(int $id = null, bool $autorizarSolicitud = false)
    {

        if ($autorizarSolicitud) {
            $condicion = " where 1 = 1 ";
        } else {
            $condicion = " where tsc.IdUsuario = '" . $this->usuario['Id'] . "' ";
        }
        if (!is_null($id)) {
            $condicion = "and tsc.Id = '" . $id . "'";
        }

        $consulta = $this->consulta("select
        tsc.*,
        tsce.IdEstatus,
        estatus(tsce.IdEstatus) as Estatus,
        tsce.Fecha,
        tsce.Descripcion as DescEstatus
        from t_solicitudes_compra tsc 
        inner join t_solicitudes_compra_estatus tsce on tsce.Id = (
        select MAX(Id) 
        from t_solicitudes_compra_estatus 
        where IdSolicitudCompra = tsc.Id)        
         " . $condicion);
        $solicitudes = [];

        foreach ($consulta as $key => $value) {
            array_push($solicitudes, [
                'Id' => $value['Id'],
                'IdUsuario' => $value['IdUsuario'],
                'IdCliente' => $value['IdCliente'],
                'IdProyecto' => $value['IdProyecto'],
                'IdSucursal' => $value['IdSucursal'],
                'Cliente' => $this->getClienteGapsi($value['IdCliente']),
                'Proyecto' => $this->getProyectoGapsi($value['IdProyecto']),
                'Sucursal' => $this->getSucursalGapsi($value['IdSucursal']),
                'Fecha' => $value['Fecha'],
                'IdEstatus' => $value['IdEstatus'],
                'Estatus' => $value['Estatus'],
                'DescEstatus' => $value['DescEstatus'],
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

    public function getListaSolicitudesPorAutorizar()
    {

        $usuarios = implode(",", $this->getEmpleadosByIdJefe($this->usuario['Id']));
        if ($usuarios == '') {
            $condicion = " and tsc.IdUsuario in (0)";
        } else {
            $condicion = " and tsc.IdUsuario in (" . $usuarios . ")";
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
        where tsce.IdEstatus = 9 " . $condicion);
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

    public function guardarCambiosSolicitudCompra(array $datos)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_solicitudes_compra", [
            'IdUsuario' => $this->usuario['Id'],
            'IdCliente' => $datos['idCliente'],
            'IdProyecto' => $datos['idProyecto'],
            'IdSucursal' => $datos['idSucursal'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => $datos['observaciones']
        ], ['Id' => $datos['idSolicitud']]);

        $this->insertar("t_solicitudes_compra_estatus", [
            'IdSolicitudCompra' => $datos['idSolicitud'],
            'IdEstatus' => 9,
            'IdUsuario' => $this->usuario['Id'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => 'Cambios a la solicitud de compra previos a su autorización'
        ]);

        $this->eliminar("t_solicitudes_compra_partidas", ['IdSolicitudCompra' => $datos['idSolicitud']]);

        $partidas = json_decode($datos['partidas'], true);

        foreach ($partidas as $key => $value) {
            $this->insertar("t_solicitudes_compra_partidas", [
                'IdSolicitudCompra' => $datos['idSolicitud'],
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
            return ['code' => 200, 'id' => $datos['idSolicitud']];
        }
    }

    private function getEmpleadosByIdJefe(int $id)
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

    public function getJefesByEmpleado()
    {
        $arrayJefes = [];

        $idEmpleado = $this->usuario['Id'];
        while ($idEmpleado != '' && $idEmpleado > 0) {
            $jefe = $this->consulta("select IdJefe from cat_v3_usuarios where Id = '" . $idEmpleado . "'")[0]['IdJefe'];
            $idEmpleado = $jefe;
            if ($jefe != '') {
                if (in_array($jefe, $arrayJefes)) {
                    $idEmpleado = '';
                } else {
                    array_push($arrayJefes, $jefe);
                }
            }
        }

        return ['jefes' => $arrayJefes, 'nombre' => $this->usuario['Nombre']];
    }



    public function autorizarSolicitudCompra(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertar("t_solicitudes_compra_estatus", [
            'IdSolicitudCompra' => $datos['id'],
            'IdEstatus' => 7,
            'IdUsuario' => $this->usuario['Id'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => 'La solicitud de compra fué autorizada por el usuario ' . $this->usuario['Nombre']
        ]);

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

    public function rechazarSolicitudCompra(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertar("t_solicitudes_compra_estatus", [
            'IdSolicitudCompra' => $datos['id'],
            'IdEstatus' => 10,
            'IdUsuario' => $this->usuario['Id'],
            'Fecha' => $this->getFecha(),
            'Descripcion' => 'La solicitud de compra fué rechazada por el usuario ' . $this->usuario['Nombre'] . ' con las siguientes observaciones: ' . $datos['motivos']
        ]);

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
