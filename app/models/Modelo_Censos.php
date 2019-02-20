<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Censos extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getAreasPuntosCensos(int $servicio) {
        $consulta = $this->consulta("select 
                tcp.Id,
                tcp.IdArea,
                areaAtencion(tcp.IdArea) as Area,
                tcp.Puntos
                from
                t_censos_puntos tcp
                where tcp.IdServicio = '" . $servicio . "'
                order by Area");
        return $consulta;
    }

    public function getAreasClienteFaltantesCenso(int $servicio) {
        $consulta = $this->consulta("select 
                                        Id,
                                        Nombre
                                        from cat_v3_areas_atencion
                                        where IdCliente = (select IdCliente 
                                                            from cat_v3_sucursales 
                                                            where Id = (select IdSucursal 
                                                                        from t_servicios_ticket 
                                                                        where Id = '" . $servicio . "')
                                                            )
                                        and Id not in (select IdArea 
                                                        from t_censos_puntos 
                                                        where IdServicio = '" . $servicio . "')
                                        and Flag = 1
                                        order by Nombre;");
        return $consulta;
    }

    public function agregaAreaPuntosCenso(array $datos) {
        $this->iniciaTransaccion();

        $this->queryBolean("insert "
                . "into t_censos_puntos "
                . "set IdServicio = '" . $datos['servicio'] . "', "
                . "IdArea = '" . $datos['area'] . "', "
                . "Puntos = '" . $datos['puntos'] . "'");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function guardaCambiosAreasPuntos(array $datos) {
        $this->iniciaTransaccion();

        foreach ($datos['areasPuntos'] as $key => $value) {
            if ($value['Cantidad'] <= 0) {
                $this->queryBolean("delete from t_censos_puntos where Id = '" . $value['Id'] . "'");
            } else {
                $this->queryBolean("update t_censos_puntos set Puntos = '" . $value['Cantidad'] . "' where Id = '" . $value['Id'] . "'");
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
            return ['code' => 200];
        }
    }

}
