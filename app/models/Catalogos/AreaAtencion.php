<?php

namespace Modelos\Catalogos;

use Librerias\Modelos\Base as Modelo_Base;

class AreaAtencion extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function get(int $id = null, int $flag = null, int $cliente = null)
    {

        $this->iniciaTransaccion();

        $condicion = " where 1 = 1 ";
        if (!is_null($id)) {
            $condicion .= " and caa.Id = '" . $id . "' ";
        }

        if (!is_null($flag)) {
            $condicion .= " and caa.Flag = '" . $flag . "' ";
        }

        if (!is_null($cliente)) {
            $condicion .= " and caa.IdCliente = '" . $cliente . "' ";
        }

        $consulta = $this->consulta("
        select 
        caa.*,
        cliente(caa.IdCliente) as Cliente
        from cat_v3_areas_atencion caa " . $condicion . " 
        order by Nombre");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError(),
                'result' => []
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => 'Success',
                'result' => $consulta
            ];
        }
    }

    public function ubicacionesCenso(int $id)
    {

        $this->iniciaTransaccion();

        $consulta = $this->consulta("
        select 
        tcp.IdArea,
        tcp.Puntos,
        areaAtencion(tcp.IdArea) as Area
        from t_censos_puntos tcp
        where IdServicio = (
                    select MAX(Id) 
                    from t_servicios_ticket 
                    where IdSucursal = '" . $id . "'
                    and IdEstatus = 4 
                    and IdTipoServicio = 11
        )");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'message' => $this->tipoError(),
                'result' => []
            ];
        } else {
            $this->commitTransaccion();
            return [
                'code' => 200,
                'message' => 'Success',
                'result' => $consulta
            ];
        }
    }
}
