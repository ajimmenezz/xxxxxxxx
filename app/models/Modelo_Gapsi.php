<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function getClientes() {
        $query = "select * from db_Clientes where Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getSucursales() {
        $query = "select * from db_Sucursales where Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getTiposServicio() {
        $query = "select * from db_TipoServicio order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getTiposBeneficiario() {
        $query = "select * from db_TipoBeneficiario order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getBeneficiarioByTipo(int $id) {
        $query = "select ID, Nombre from db_Beneficiarios where Tipo = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function proyectosByCliente(int $id) {
        $query = "select ID, Tipo, Descripcion as Nombre from db_Proyectos where Cliente = '" . $id . "' and Descripcion <> '' order by Descripcion";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function sucursalesByProyecto(int $id) {
        $query = "select 
                    suc.ID,
                    suc.Nombre
                    from db_Sucursales suc
                    INNER JOIN db_SucProy sp on suc.ID = sp.Sucursal
                    where sp.Proyecto = '" . $id . "' 
                    AND suc.Nombre <> '' 
                    order by suc.Nombre;";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getTiposTransferencia() {
        $query = "select * from db_TipoTrans order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getCategoriasByTipoTrans(int $id) {
        $query = "select ID, Nombre from db_Categorias where TipoTrans = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getSubcategoriasByCategoria(int $id) {
        $query = "select ID, Nombre from db_SubCategorias where Categoria = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getConceptosBySubcategoria(int $id) {
        $query = "select ID, Nombre from db_SubSubCategorias where SubCategoria = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function solicitarGasto(array $datos) {
        parent::connectDBGapsi()->trans_begin();
        $query = "insert into "
                . "db_Registro "
                . "(Beneficiario, IDBeneficiario, Tipo, TipoTrans, TipoServicio, Descripcion, FCaptura, Importe, Observaciones, Proyecto, GastoFrecuente, Sucursal, Status, UsuarioSolicitud, FechaSolicitud, Fecha, Moneda) "
                . "VALUES "
                . "('" . $datos['Beneficiario'] . "', '" . $datos['IDBeneficiario'] . "', '" . $datos['Tipo'] . "', '" . $datos['TipoTrans'] . "', '" . $datos['TipoServicio'] . "', '" . $datos['Descripcion'] . "', GETDATE(), '" . $datos['Importe'] . "', '" . $datos['Observaciones'] . "', '" . $datos['Proyecto'] . "', '', '" . $datos['Sucursal'] . "', 'Solicitado', null, GETDATE(), GETDATE(), '" . $datos['Moneda'] . "')";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        $registros = [];
        $conceptos = json_decode($datos['Conceptos'], true);

        foreach ($conceptos as $key => $value) {
            $query = "insert into "
            . "db_DetalleGasto "
            . "(Gasto, Categoria, SubCategoria, Concepto, Monto) "
            . "VALUES "
            . "('" . $ultimo . "', '" . $value['categoria'] . "', '" . $value['subcategoria'] . "', '" . $value['concepto'] . "', '" . $value['monto'] . "')";
            parent::connectDBGapsi()->query($query);
            $ultimoDetalle = parent::connectDBGapsi()->insert_id();
            array_push($registros, $ultimoDetalle);
        }

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {            
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => $ultimo];
        }
    }

}
