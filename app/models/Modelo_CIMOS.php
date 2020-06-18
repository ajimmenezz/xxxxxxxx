<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_CIMOS extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getGastosCimosEvocal() {
        $query = "SELECT
                    reg.Folio,
                    FORMAT(reg.Fecha,'dd/MM/yyy') as FechaS,
                    FORMAT(reg.FCaptura,'dd/MM/yyy') as FCapturaS,
                    reg.Tipo,
                    reg.TipoServicio,
                    pro.Descripcion as Proyecto,
                    (select Nombre from db_Sucursales where ID = reg.Sucursal) as Sucursal,
                    (select Nombre from db_Clientes where ID = pro.Cliente) as Cliente,
                    reg.Beneficiario,
                    reg.TipoTrans,
                    reg.Descripcion,
                    reg.Importe,
                    reg.Moneda,
                    reg.Banco,
                    reg.RefBancaria,
                    reg.Empresa,
                    reg.OrdenCompra,
                    reg.Ticket,
                    reg.Autorizacion
                    from db_Registro reg
                    inner join db_Proyectos pro on reg.Proyecto = pro.ID
                    where Sucursal in (553,1595)";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

}
