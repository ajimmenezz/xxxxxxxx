<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi extends Modelo_Base {

    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
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
                . "(Beneficiario, IDBeneficiario, Tipo, TipoTrans, TipoServicio, Descripcion, FCaptura, Importe, Observaciones, Proyecto, GastoFrecuente, Sucursal, Status, UsuarioSolicitud, FechaSolicitud, Fecha, Moneda, OrdenCompra) "
                . "VALUES "
                . "('" . $datos['Beneficiario'] . "', '" . $datos['IDBeneficiario'] . "', '" . $datos['Tipo'] . "', '" . $datos['TipoTrans'] . "', '" . $datos['TipoServicio'] . "', '" . $datos['Descripcion'] . "', GETDATE(), '" . $datos['Importe'] . "', '" . $datos['Observaciones'] . "', '" . $datos['Proyecto'] . "', '', '" . $datos['Sucursal'] . "', 'Solicitado', null, GETDATE(), GETDATE(), '" . $datos['Moneda'] . "', '" . $datos['OC'] . "')";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        $registros = [];
        $conceptos = json_decode($datos['Conceptos'], true);

        if (isset($conceptos) && count($conceptos) > 0) {
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
        }

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => $ultimo];
        }
    }

    public function getMisGastos() {
        $condicion = '';
        $todos = true;
        if (!in_array(284, $this->usuario['Permisos'])) {
            $condicion = " where IdUsuario = '" . $this->usuario['Id'] . "' ";
            $todos = false;
        }

        $ids = $this->consulta("select group_concat(IdGasto) as Ids from t_archivos_gastos_gapsi " . $condicion)[0]['Ids'];
        $ids = ($ids !== '') ? ',' . $ids : '';

        $consulta = $this->consulta("select IdGasto, nombreUsuario(IdUsuario) as Usuario, IdUsuario, Email from t_archivos_gastos_gapsi " . $condicion);
        $usuarios = [];
        foreach ($consulta as $key => $value) {
            $usuarios[$value['IdGasto']] = [
                'idUsuario' => $value['IdUsuario'],
                'usuario' => $value['Usuario'],
                'email' => $value['Email']
            ];
        }


        $query = "select "
                . "registro.*, "
                . "(select Descripcion from db_Proyectos where ID = registro.Proyecto) as NameProyecto "
                . "from db_Registro registro "
                . "where ID in (''" . $ids . ")";
        $consulta = parent::connectDBGapsi()->query($query);
        $gastos = $consulta->result_array();

        return [
            'gastos' => $gastos,
            'usuarios' => $usuarios,
            'permiso' => $todos
        ];
    }

    public function detallesGasto($id) {
        $query = "select 
                gasto.*, 
                proyecto.Cliente,
                (select Tipo from db_Beneficiarios where Nombre = gasto.Beneficiario) as TipoBeneficiario
                from db_Registro gasto
                inner join db_Proyectos proyecto on gasto.Proyecto = proyecto.ID
                where gasto.ID = '" . $id . "'";
        $consulta = parent::connectDBGapsi()->query($query);
        $gasto = $consulta->result_array();

        $query = "select * from db_DetalleGasto where Gasto = '" . $id . "'";
        $consulta = parent::connectDBGapsi()->query($query);
        $conceptos = $consulta->result_array();

        $archivosGasto = $this->consulta("select Archivos from t_archivos_gastos_gapsi where IdGasto = '" . $id . "'");
        $archivosGasto = (count($archivosGasto) > 0) ? $archivosGasto[0]['Archivos'] : '';

        $usuarioSolicita = $this->consulta("select IdUsuario from t_archivos_gastos_gapsi where IdGasto = '" . $id . "'")[0]['IdUsuario'];

        return [
            'gasto' => $gasto[0],
            'conceptos' => $conceptos,
            'archivosGasto' => $archivosGasto,
            'usuario' => $usuarioSolicita
        ];
    }

    public function guardarCambiosGasto(array $datos) {
        parent::connectDBGapsi()->trans_begin();
        $query = "update "
                . "db_Registro "
                . "set Beneficiario = '" . $datos['Beneficiario'] . "', "
                . "IDBeneficiario = '" . $datos['IDBeneficiario'] . "', "
                . "Tipo = '" . $datos['Tipo'] . "', "
                . "TipoTrans = '" . $datos['TipoTrans'] . "', "
                . "TipoServicio = '" . $datos['TipoServicio'] . "', "
                . "Descripcion = '" . $datos['Descripcion'] . "', "
                . "Importe = '" . $datos['Importe'] . "', "
                . "Observaciones = '" . $datos['Observaciones'] . "', "
                . "Proyecto = '" . $datos['Proyecto'] . "', "
                . "Sucursal = '" . $datos['Sucursal'] . "', "
                . "Moneda = '" . $datos['Moneda'] . "', "
                . "OrdenCompra = '" . $datos['OC'] . "' "
                . "where ID = '" . $datos['ID'] . "'";

        parent::connectDBGapsi()->query($query);

        parent::connectDBGapsi()->query("delete from db_DetalleGasto where Gasto = '" . $datos['ID'] . "'");

        $registros = [];
        $conceptos = json_decode($datos['Conceptos'], true);

        if (isset($conceptos) && count($conceptos) > 0) {
            foreach ($conceptos as $key => $value) {
                $query = "insert into "
                        . "db_DetalleGasto "
                        . "(Gasto, Categoria, SubCategoria, Concepto, Monto) "
                        . "VALUES "
                        . "('" . $datos['ID'] . "', '" . $value['categoria'] . "', '" . $value['subcategoria'] . "', '" . $value['concepto'] . "', '" . $value['monto'] . "')";
                parent::connectDBGapsi()->query($query);
                $ultimoDetalle = parent::connectDBGapsi()->insert_id();
                array_push($registros, $ultimoDetalle);
            }
        }

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200];
        }
    }

    public function eliminarArchivo(array $datos) {
        $this->iniciaTransaccion();

        $this->queryBolean("
            update 
            t_archivos_gastos_gapsi
            set Archivos = replace(concat(',',Archivos,','),'," . $datos['Source'] . ",','')
            where IdGasto = '" . $datos['Id'] . "'");


        $first = $this->consulta("select SUBSTR(Archivos,1,1) as FirstL from t_archivos_gastos_gapsi where IdGasto = '" . $datos['Id'] . "'")[0]['FirstL'];
        if ($first == ',') {
            $this->queryBolean("
            update 
            t_archivos_gastos_gapsi
            set Archivos = SUBSTR(Archivos,2)
            where IdGasto = '" . $datos['Id'] . "'");
        }


        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

}
