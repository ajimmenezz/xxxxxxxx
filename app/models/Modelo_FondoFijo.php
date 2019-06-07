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
                'TiposCuenta' => implode(",",$datos['tiposCuenta'])
            ]);


            $id = $this->ultimoId();
        } else {
            $this->actualizar("cat_v3_comprobacion_conceptos", [
                'Nombre' => $datos['concepto'],
                'Monto' => $datos['monto'],
                'Extraordinario' => $datos['extraordinario'],
                'TiposComprobante' => implode(",", $datos['comprobantes']),
                'TiposCuenta' => implode(",",$datos['tiposCuenta'])
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
                                        where IdUsuario = '".$id."' and Monto > 0
                                    ) and Flag = 1
                                    order by Nombre");
        return $consulta;
    }

    public function getMaximoMontoAutorizado(int $idUsuario, int $idTipoCuenta){
        $consulta = $this->consulta("
        select 
        Monto 
        from cat_v3_fondofijo_montos_x_usuario_cuenta 
        where IdUSuario = '".$idUsuario."' 
        and IdTipoCuenta = '".$idTipoCuenta."'");        
        return $consulta[0]['Monto'];
    }

    public function getSaldo(int $idUsuario, int $idTipoCuenta){
        $consulta = $this->consulta("
        select 
        Saldo 
        from t_fondofijo_saldos 
        where IdUSuario = '".$idUsuario."' 
        and IdTipoCuenta = '".$idTipoCuenta."'");
        if(!empty($consulta) && isset($consulta[0]) && isset($consulta[0]['Saldo']) && $consulta[0]['Saldo'] > 0){
            return $consulta[0]['Saldo'];
        }else{
            return 0;
        }
    }

}
