<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_PEV2 extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getProyectosespeciales() {
        $query = "select
        ts.Id_Orden as Ticket,
        ts.Folio_Cliente as Folio,
        sucursal(ts.Sucursal) as Sucursal,
        tecnico(ts.Ingeniero) as Ingeniero,
        ts.Estatus,
        tse.TipoProyecto as Tipo,
        tse.Categoria,
        tse.Actividad
        from t_servicios ts inner join t_servicios_especiales tse
        on ts.Id_Orden = tse.Id_Orden
        where ts.Tipo = 7 and ts.Sucursal <> 0 and ts.Estatus in ('CONCLUIDO','EN PROCESO DE VALIDACION')
        order by Ticket desc;";
        $consulta = parent::connectDBAdist2()->query($query);
        return $consulta->result_array();
    }

    public function getDetallePuntosProyectosEspeciales(int $ticket) {
        $query = "call getPuntosProyectosEspeciales((select Arreglo from hist_lev_seg_especiales where Id_Orden = '" . $ticket . "' order by Fecha desc limit 1));";
        $consulta = parent::connectDBAdist2()->query($query);
        \mysqli_next_result(parent::connectDBAdist2()->conn_id);
        return $consulta->result_array();
    }

    public function getDetalleMaterialExtraProyectosEspeciales(int $ticket) {
        $query = "call getMaterialExtraProyectosEspeciales((select Arreglo_mat_ex from hist_lev_seg_especiales where Id_Orden = '" . $ticket . "' order by Fecha desc limit 1));";
        $consulta = parent::connectDBAdist2()->query($query);
        \mysqli_next_result(parent::connectDBAdist2()->conn_id);
        return $consulta->result_array();
    }

    public function getEvidenciasProyectosEspeciales(int $ticket) {
        $query = "select
        cde.Nombre,
        cde.Descripcion,
        tde.URL
        from t_doc_especiales tde inner join cat_doc_especiales cde
        on tde.Documento = cde.Id
        where tde.Id_Orden = '" . $ticket . "'";
        $consulta = parent::connectDBAdist2()->query($query);
        return $consulta->result_array();
    }

}
