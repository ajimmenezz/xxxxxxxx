<?php

namespace Modelos\Catalogos;

use Librerias\Modelos\Base as Modelo_Base;

class Sucursal extends Modelo_Base
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
            $condicion .= " and cs.Id = '" . $id . "' ";
        }

        if (!is_null($flag)) {
            $condicion .= " and cs.Flag = '" . $flag . "' ";
        }

        if (!is_null($cliente)) {
            $condicion .= " and cs.IdCliente = '" . $cliente . "' ";
        }

        $consulta = $this->consulta("
        select 
        cs.*,
        cliente(cs.IdCliente) as Cliente,
        regionCliente(cs.IdRegionCliente) as Region,
        (select Nombre from cat_v3_paises where Id = cs.IdPais) as Pais,
        (select Nombre from cat_v3_estados where Id = cs.IdEstado) as Estado,
        (select Nombre from cat_v3_municipios where Id = cs.IdMunicipio) as Municipio,
        (select Nombre from cat_v3_colonias where Id = cs.IdColonia) as Colonia,
        nombreUsuario(cs.IdResponsable) as Responsable,
        (select Nombre from cat_v3_unidades_negocio where Id = cs.IdUnidadNegocio) as UnidadNegocio
        from cat_v3_sucursales cs " . $condicion . " order by Nombre");

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
        max(tcp.Punto) as Puntos,
        areaAtencion(tcp.IdArea) as Area
        from t_censos tcp
        where tcp.IdServicio = (
                                select MAX(Id) 
                                from t_servicios_ticket 
                                where IdSucursal = '" . $id . "'
                                and IdEstatus not in (1,6)
                                and IdTipoServicio = 11
        ) group by tcp.IdArea");

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
