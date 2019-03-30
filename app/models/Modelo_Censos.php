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

        if (empty($consulta)) {
            $this->queryBolean("insert into t_censos_puntos
                                    select
                                    null,
                                    IdServicio,
                                    IdArea,
                                    MAX(Punto) as Puntos
                                    from t_censos where IdServicio = '" . $servicio . "'
                                    group by IdArea");

            $consulta = $this->consulta("select 
                tcp.Id,
                tcp.IdArea,
                areaAtencion(tcp.IdArea) as Area,
                tcp.Puntos
                from
                t_censos_puntos tcp
                where tcp.IdServicio = '" . $servicio . "'
                order by Area");
        }

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

    public function getKitStandarArea(int $area) {
        $consulta = $this->consulta("select 
                                    csxa.IdSublinea,
                                    linea(cse.Linea) as Linea,
                                    cse.Nombre as Sublinea,
                                    csxa.Cantidad
                                    from cat_v3_sublineas_x_area csxa 
                                    inner join cat_v3_sublineas_equipo cse on csxa.IdSublinea = cse.Id
                                    where csxa.IdArea = '" . $area . "'
                                    and csxa.Flag = 1
                                    group by csxa.IdSublinea
                                    order by Linea, Sublinea;");
        return $consulta;
    }

    public function getModelosStandarByArea(int $area) {
        $consulta = $this->consulta("select 
                                    modelos.Id,
                                    marcas.Nombre as Marca,
                                    modelos.Nombre as Modelo,
                                    marcas.Sublinea
                                    from cat_v3_marcas_equipo marcas
                                    inner join cat_v3_modelos_equipo modelos on marcas.Id = modelos.Marca
                                    where marcas.Sublinea in (select 
                                                                IdSublinea 
                                                                from cat_v3_sublineas_x_area 
                                                                where IdArea = '" . $area . "')
                                    and modelos.Flag = 1;");
        return $consulta;
    }

    public function getEquiposCensoByAreaPunto(array $datos) {
        $consulta = $this->consulta("select
                                    Id,
                                    IdModelo,
                                    modelo(IdModelo) as Modelo,
                                    Serie,
                                    Extra,
                                    Existe,
                                    Danado
                                    from 
                                    t_censos
                                    where IdServicio = '" . $datos['servicio'] . "'
                                    and IdArea = '" . $datos['area'] . "'
                                    and Punto = '" . $datos['punto'] . "'");
        return $consulta;
    }

    public function getNombreAreaById(int $area) {
        $consulta = $this->consulta("select Nombre from cat_v3_areas_atencion where Id = '" . $area . "'");
        return $consulta[0]['Nombre'];
    }
    
    public function getClienteByIdArea(int $area) {
        $consulta = $this->consulta("select IdCliente from cat_v3_areas_atencion where Id = '" . $area . "'");
        return $consulta[0]['IdCliente'];
    }
    
    public function getSistemasOperativos() {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_sistemas_operativos where Flag = 1");
        return $consulta;
    }
    
    public function getEstatusEquipoPrimeMX() {
        $consulta = $this->consulta("select Id, Nombre from cat_v3_estatus where Id in (42,43,44,45)");
        return $consulta;
    }       

    public function getModelosGenerales() {
        $consulta = $this->consulta("select 
                                    Id,
                                    modelo(Id) as Modelo
                                    from cat_v3_modelos_equipo 
                                    where Flag = 1
                                    order by Modelo");
        return $consulta;
    }

    public function guardaEquiposPuntoCenso(array $datos) {
        $this->iniciaTransaccion();

        if (isset($datos['activosEstandar']) && count($datos['activosEstandar']) > 0) {
            foreach ($datos['activosEstandar'] as $key => $value) {
                if ($value['existe'] == 1) {
                    $this->actualizar("t_censos", [
                        'IdModelo' => $value['modelo'],
                        'Serie' => $value['serie'],
                        'Existe' => $value['existe'],
                        'Danado' => $value['danado']
                            ], ['Id' => $value['id']]);
                } else {
                    $this->eliminar("t_censos", ['Id' => $value['id']]);
                }
            }
        }

        if (isset($datos['nuevosEstandar']) && count($datos['nuevosEstandar']) > 0) {
            foreach ($datos['nuevosEstandar'] as $key => $value) {
                $this->insertar("t_censos", [
                    'IdServicio' => $datos['servicio'],
                    'IdArea' => $datos['area'],
                    'Punto' => $datos['punto'],
                    'IdModelo' => $value['modelo'],
                    'Serie' => $value['serie'],
                    'Existe' => 1,
                    'Danado' => $value['danado']
                ]);
            }
        }

        $this->insertar("t_censos_areas_puntos_revisados", [
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto']
        ]);

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

    public function getPuntosCensoRevisados(int $servicio) {
        $consulta = $this->consulta("select 
                                    * 
                                    from t_censos_areas_puntos_revisados 
                                    where IdServicio = '" . $servicio . "'");

        return $consulta;
    }

    public function guardarEquipoAdicionalCenso(array $datos) {
        $this->iniciaTransaccion();

        $this->insertar("t_censos", [
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'IdModelo' => $datos['modelo'],
            'Punto' => $datos['punto'],
            'Serie' => $datos['serie'],
            'Existe' => 1,
            'Danado' => $datos['danado']
        ]);

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return [
                'code' => 500,
                'error' => $this->tipoError()
            ];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'id' => $this->ultimoId()];
        }
    }

    public function eliminarEquiposAdicionalesCenso(array $datos) {
        $this->iniciaTransaccion();

        $this->eliminar("t_censos", [
            "Id" => $datos['id']
        ]);

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

    public function guardaCambiosEquiposAdicionalesCenso(array $datos) {
        $this->iniciaTransaccion();

        $this->actualizar("t_censos", [
            'IdModelo' => $datos['modelo'],
            'Serie' => $datos['serie'],
            'Existe' => $datos['existe'],
            'Danado' => $datos['danado']
                ], ['Id' => $datos['id']]);

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
