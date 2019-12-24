<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Gapsi extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function getClientes()
    {
        $query = "select * from db_Clientes where Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);

        return $consulta->result_array();
    }

    public function getSucursales()
    {
        $query = "select * from db_Sucursales where Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getTiposServicio()
    {
        $query = "select * from db_TipoServicio order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getTiposBeneficiario()
    {
        $query = "select * from db_TipoBeneficiario order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getBeneficiarioByTipo(array $datos)
    {
        if ($datos['id'] == 1) {
            $query = "select 
                    db.ID, 
                    db.Nombre 
                    from db_Beneficiarios db 
                    inner join db_BenProy dbp on db.ID = dbp.Beneficiario
                    where db.Tipo = '" . $datos['id'] . "' 
                    and db.Nombre <> '' 
                    and dbp.Proyecto = '" . $datos['proyecto'] . "'
                    order by db.Nombre";
        } else {
            $query = "select ID, Nombre from db_Beneficiarios where Tipo = '" . $datos['id'] . "' and Nombre <> '' order by Nombre";
        }
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function proyectosByCliente(int $id)
    {
        $query = "select ID, Tipo, Descripcion as Nombre from db_Proyectos where Cliente = '" . $id . "' and Descripcion <> '' order by Descripcion";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function sucursalesByProyecto(int $id)
    {
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

    public function getTiposTransferencia()
    {
        $query = "select * from db_TipoTrans order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getCategoriasByTipoTrans(int $id)
    {
        $query = "select ID, Nombre from db_Categorias where TipoTrans = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getSubcategoriasByCategoria(int $id)
    {
        $query = "select ID, Nombre from db_SubCategorias where Categoria = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function getConceptosBySubcategoria(int $id)
    {
        $query = "select ID, Nombre from db_SubSubCategorias where SubCategoria = '" . $id . "' and Nombre <> '' order by Nombre";
        $consulta = parent::connectDBGapsi()->query($query);
        return $consulta->result_array();
    }

    public function solicitarGasto(array $datos)
    {
        $consulta = $this->consulta("select nombreUsuario('" . $this->usuario['Id'] . "') as Usuario");
        $solicita = $consulta[0]['Usuario'];
        $fechaCredito = 'null';
        if ($datos['Credito'] == 1) {
            $time = strtotime($datos['FechaCredito']);
            $fechaCredito = "'" . date('Y-m-d\TH:i:s.v', $time) . "'";
        }

        parent::connectDBGapsi()->trans_begin();
        $query = "select "
            . "count(*) as Total "
            . "from db_Registro "
            . "where Tipo = '" . $datos['Tipo'] . "' "
            . "and TipoServicio = '" . $datos['TipoServicio'] . "' "
            . "and Proyecto = '" . $datos['Proyecto'] . "' "
            . "and Beneficiario = '" . $datos['Beneficiario'] . "' "
            . "and TipoTrans = '" . $datos['TipoTrans'] . "' "
            . "and Descripcion = '" . $datos['Descripcion'] . "' "
            . "and Importe = '" . $datos['Importe'] . "' "
            . "and Moneda = '" . $datos['Moneda'] . "' "
            . "and DATEDIFF(minute, FechaSolicitud, GETDATE()) <= 10";
        $consulta = parent::connectDBGapsi()->query($query);
        $row = $consulta->result_array();

        if ($row[0]['Total'] > 0) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 508, 'message' => 'Al parecer ya existe un registro con esas características. Espera al menos 10 minutos para volver a solicitar un gasto similar.'];
        }

        $query = "insert into "
            . "db_Registro "
            . "(Beneficiario, IDBeneficiario, Tipo, TipoTrans, TipoServicio, Descripcion, FCaptura, Importe, Observaciones, Proyecto, GastoFrecuente, Sucursal, Status, UsuarioSolicitud, FechaSolicitud, Fecha, Moneda, OrdenCompra, SolicADIST, FechaPagoCredito) "
            . "VALUES "
            . "('" . $datos['Beneficiario'] . "', '" . $datos['IDBeneficiario'] . "', '" . $datos['Tipo'] . "', '" . $datos['TipoTrans'] . "', '" . $datos['TipoServicio'] . "', '" . $datos['Descripcion'] . "', GETDATE(), '" . $datos['Importe'] . "', '" . $datos['Observaciones'] . "', '" . $datos['Proyecto'] . "', '', '" . $datos['Sucursal'] . "', 'Solicitado', null, GETDATE(), GETDATE(), '" . $datos['Moneda'] . "', '" . $datos['OC'] . "', '" . $solicita . "', " . $fechaCredito . ")";

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

    public function getMisGastos()
    {
        ini_set('memory_limit', '5048M');

        $condicion = '';
        $todos = true;
        if (!in_array(284, $this->usuario['Permisos'])) {
            $arrayTrabajadores = array();
            $stringIds = '';
            $trabajadores = $this->consulta("SELECT Id FROM `cat_v3_usuarios` WHERE IdJefe = '" . $this->usuario['Id'] . "'");
            if (!empty($trabajadores)) {
                foreach ($trabajadores as $key => $value) {
                    array_push($arrayTrabajadores, $value['Id']);
                }
                $stringIds = implode(",", $arrayTrabajadores);
            }
            $condicion = " and IdUsuario IN ('" . $this->usuario['Id'] . "','" . $stringIds . "')";
            $todos = false;
        }

        $this->queryBolean("SET SESSION group_concat_max_len = 1000000");

        $ids = $this->consulta(""
            . "select "
            . "group_concat(IdGasto) as Ids "
            . "from t_archivos_gastos_gapsi "
            . "where 1 = 1 "
            . " " . $condicion . " "
            . "and if((CONCAT(',', Leido, ',') like '%," . $this->usuario['Id'] . ",%'), 1, 0) = 0")[0]['Ids'];
        $ids = ($ids !== '') ? ',' . $ids : '';

        $consulta = $this->consulta("select "
            . "IdGasto, "
            . "nombreUsuario(IdUsuario) as Usuario, "
            . "IdUsuario, "
            . "Email, "
            . "if((CONCAT(',', Leido, ',') like '%," . $this->usuario['Id'] . ",%'), 1, 0) as Leido "
            . "from t_archivos_gastos_gapsi "
            . "where 1 = 1 " . $condicion);
        $usuarios = [];
        foreach ($consulta as $key => $value) {
            if ($value['Leido'] != 1) {
                $usuarios[$value['IdGasto']] = [
                    'idUsuario' => $value['IdUsuario'],
                    'usuario' => $value['Usuario'],
                    'email' => $value['Email']
                ];
            }
        }

        $query = "select "
            . "registro.*, "
            . "(select Descripcion from db_Proyectos where ID = registro.Proyecto) as NameProyecto "
            . "from db_Registro registro "
            . "where ID in (''" . $ids . ")";

        if ($ids !== ',') {
            $consulta = parent::connectDBGapsi()->query($query);
            $gastos = $consulta->result_array();
        } else {
            $gastos = array();
        }

        return [
            'gastos' => $gastos,
            'usuarios' => $usuarios,
            'permiso' => $todos
        ];
    }


    public function exportMisGastos()
    {
        ini_set('memory_limit', '2048M');

        $condicion = '';
        $todos = true;
        if (!in_array(284, $this->usuario['Permisos'])) {
            $arrayTrabajadores = array();
            $stringIds = '';
            $trabajadores = $this->consulta("SELECT Id FROM `cat_v3_usuarios` WHERE IdJefe = '" . $this->usuario['Id'] . "'");
            if (!empty($trabajadores)) {
                foreach ($trabajadores as $key => $value) {
                    array_push($arrayTrabajadores, $value['Id']);
                }
                $stringIds = implode(",", $arrayTrabajadores);
            }
            $condicion = " and IdUsuario IN ('" . $this->usuario['Id'] . "','" . $stringIds . "')";
            $todos = false;
        }

        $this->queryBolean("SET SESSION group_concat_max_len = 1000000");

        $ids = $this->consulta(""
            . "select "
            . "group_concat(IdGasto) as Ids "
            . "from t_archivos_gastos_gapsi "
            . "where 1 = 1 "
            . " " . $condicion . " ")[0]['Ids'];
        $ids = ($ids !== '') ? ',' . $ids : '';

        $query = "select
        ID,
        OrdenCompra,
        FechaSolicitud as FechaCaptura,
        FechaAutorizacion,
        FCaptura as FechaAplicacionGasto,
        Fecha as FechaTransferencia,
        Status,
        Beneficiario,
        (select  Descripcion from db_Proyectos where ID = Proyecto) as Proyecto,
        Observaciones as Descripcion,
        Importe,
        Moneda
        from db_Registro
        where ID in (''" . $ids . ")";

        if ($ids !== ',') {
            $consulta = parent::connectDBGapsi()->query($query);
            $gastos = $consulta->result_array();
        } else {
            $gastos = array();
        }

        return $gastos;
    }

    public function getComprobarGastos()
    {
        $condicion = '';
        $todos = false;

        $ids = $this->consulta(""
            . "select "
            . "group_concat(IdGasto) as Ids "
            . "from t_archivos_gastos_gapsi "
            . "where 1 = 1 "
            . "and Comprobado = 0"
            . " and IdUsuario = '" . $this->usuario['Id'] . "' "
            . "and if((CONCAT(',', Leido, ',') like '%," . $this->usuario['Id'] . ",%'), 1, 0) = 0")[0]['Ids'];
        $ids = ($ids !== '') ? ',' . $ids : '';

        $consulta = $this->consulta("select "
            . "IdGasto, "
            . "nombreUsuario(IdUsuario) as Usuario, "
            . "IdUsuario, "
            . "Email, "
            . "if((CONCAT(',', Leido, ',') like '%," . $this->usuario['Id'] . ",%'), 1, 0) as Leido "
            . "from t_archivos_gastos_gapsi "
            . "where 1 = 1 "
            . " and IdUsuario = '" . $this->usuario['Id'] . "' ");
        $usuarios = [];
        foreach ($consulta as $key => $value) {
            if ($value['Leido'] != 1) {
                $usuarios[$value['IdGasto']] = [
                    'idUsuario' => $value['IdUsuario'],
                    'usuario' => $value['Usuario'],
                    'email' => $value['Email']
                ];
            }
        }

        $query = "select "
            . "registro.*, "
            . "(select Descripcion from db_Proyectos where ID = registro.Proyecto) as NameProyecto "
            . "from db_Registro registro "
            . "where ID in (''" . $ids . ") "
            . "and Status in ('Aprobado')";
        //                . "and AplicaComprobacion = 1";

        if ($ids !== ',') {
            $consulta = parent::connectDBGapsi()->query($query);
            $gastos = $consulta->result_array();
        } else {
            $gastos = array();
        }

        $datos = ['Gastos' => [
            'gastos' => $gastos,
            'usuarios' => $usuarios,
            'permiso' => $todos
        ]];

        return $datos;
    }

    public function detallesGasto($id)
    {
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

    public function guardarCambiosGasto(array $datos)
    {
        $fechaCredito = 'null';
        if ($datos['Credito'] == 1) {
            $time = strtotime($datos['FechaCredito']);
            $fechaCredito = "'" . date('Y-m-d\TH:i:s.v', $time) . "'";
        }

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
            . "OrdenCompra = '" . $datos['OC'] . "',"
            . "FechaPagoCredito = " . $fechaCredito . ", "
            . "Status = 'Solicitado' "
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

    public function eliminarArchivo(array $datos)
    {
        $this->iniciaTransaccion();

        $this->queryBolean("
update
t_archivos_gastos_gapsi
set Archivos = replace(concat(',', Archivos, ','), '," . $datos['Source'] . ",', '')
where IdGasto = '" . $datos['Id'] . "'");


        $first = $this->consulta("select SUBSTR(Archivos, 1, 1) as FirstL from t_archivos_gastos_gapsi where IdGasto = '" . $datos['Id'] . "'")[0]['FirstL'];
        if ($first == ',') {
            $this->queryBolean("
update
t_archivos_gastos_gapsi
set Archivos = SUBSTR(Archivos, 2)
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

    public function marcarLeido(array $datos)
    {
        $this->iniciaTransaccion();

        $leidos = $this->consulta("select Leido from t_archivos_gastos_gapsi where IdGasto = '" . $datos['Id'] . "'")[0]['Leido'];
        if ($leidos != '') {
            $leidos .= ',' . $this->usuario['Id'];
        } else {
            $leidos = $this->usuario['Id'];
        }

        $this->queryBolean("
update
t_archivos_gastos_gapsi
set Leido = '" . $leidos . "'
where IdGasto = '" . $datos['Id'] . "'");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function ordenCompra(array $datos)
    {
        parent::connectDBGapsi()->trans_begin();
        $query = "insert into "
            . "db_Registro "
            . "(Beneficiario, IDBeneficiario, Tipo, TipoTrans, TipoServicio, Descripcion, FCaptura, Importe, Observaciones, Proyecto, GastoFrecuente, Sucursal, Status, UsuarioSolicitud, FechaSolicitud, Fecha, Moneda, OrdenCompra) "
            . "VALUES "
            . "('" . $datos['Beneficiario'] . "', '" . $datos['IDBeneficiario'] . "', '" . $datos['Tipo'] . "', '" . $datos['TipoTrans'] . "', '" . $datos['TipoServicio'] . "', '" . $datos['Descripcion'] . "', GETDATE(), '" . $datos['Importe'] . "', '" . $datos['Observaciones'] . "', '" . $datos['Proyecto'] . "', '', '" . $datos['Sucursal'] . "', 'Solicitado', null, GETDATE(), GETDATE(), '" . $datos['Moneda'] . "', '" . $datos['OC'] . "')";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => $ultimo];
        }
    }

    public function actualizarOrdenCompra(array $datos)
    {
        parent::connectDBGapsi()->trans_begin();
        $query = "update db_Registro set
Beneficiario = '" . $datos['Beneficiario'] . "',
 IDBeneficiario = '" . $datos['IDBeneficiario'] . "',
 Tipo = '" . $datos['Tipo'] . "',
 TipoTrans = '" . $datos['TipoTrans'] . "',
 TipoServicio = '" . $datos['TipoServicio'] . "',
 Descripcion = '" . $datos['Descripcion'] . "',
 FCaptura = GETDATE(),
 Importe = '" . $datos['Importe'] . "',
 Observaciones = '" . $datos['Observaciones'] . "',
 Proyecto = '" . $datos['Proyecto'] . "',
 Sucursal = '" . $datos['Sucursal'] . "',
 FechaSolicitud = GETDATE(),
 Fecha = GETDATE(),
 Moneda = '" . $datos['Moneda'] . "'
WHERE OrdenCompra = '" . $datos['OC'] . "'";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => $ultimo];
        }
    }

    public function consultaIdOrdenCompra(array $datos)
    {
        $query = "select
*
from db_Registro
where OrdenCompra = '" . $datos['ordenCompra'] . "'";
        $consulta = parent::connectDBGapsi()->query($query);
        $gasto = $consulta->result_array();
        return $gasto;
    }

    public function consultaDatosGasto(array $datos)
    {
        $query = "select
*,
 (SELECT Cliente FROM db_Proyectos WHERE ID = Proyecto) Cliente,
 (SELECT ID FROM db_TipoServicio WHERE Nombre = TipoServicio) TipoServicio,
 (SELECT Tipo FROM db_Beneficiarios WHERE ID = IDBeneficiario) TipoBeneficiario
from db_Registro
where OrdenCompra = '" . $datos['ordenCompra'] . "'";

        $consulta = parent::connectDBGapsi()->query($query);
        $gasto = $consulta->result_array();

        return $gasto;
    }

    public function registrarSinXML($datos)
    {

        parent::connectDBGapsi()->trans_begin();
        $query = "insert into "
            . "db_ComprobacionRegistro"
            . "(Registro, Monto, Comentario, Status, UUID) "
            . "VALUES "
            . "('" . $datos['idGasto'] . "', '" . $datos['monto'] . "', null, 'Enviado', null)";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => $ultimo];
        }
    }

    public function insertarComprobanteGapsi($datos)
    {
        parent::connectDBGapsi()->trans_begin();

        $query = "insert into "
            . "db_ComprobacionRegistro"
            . "(Registro, Monto, Comentario, Status, UUID) "
            . "VALUES "
            . "('" . $datos['idGasto'] . "', '" . $datos['Monto'] . "', 'null', '" . $datos['Status'] . "', '" . $datos['UUID'] . "')";

        parent::connectDBGapsi()->query($query);
        $ultimo = parent::connectDBGapsi()->insert_id();

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 500, 'errorBack' => "No se registro comprobante"];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'errorBack' => "Nuevo registro agregado"];
        }
    }

    public function marcarComprobado($datos)
    {
        $this->iniciaTransaccion();

        $this->queryBolean("
update
t_archivos_gastos_gapsi
set Comprobado = '1'
where IdGasto = '" . $datos['idGasto'] . "'");

        if ($this->estatusTransaccion() === FALSE) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200, 'idGasto' => $datos['idGasto']];
        }
    }

    public function consultaRegistro($datos)
    {
        $consultaComprobante = "select Monto from db_ComprobacionRegistro where Registro = '" . $datos['idGasto'] . "'";
        $consulta = parent::connectDBGapsi()->query($consultaComprobante);
        $montoComprobante = $consulta->result_array();

        $consultaRegistro = "select Importe from db_Registro where ID = '" . $datos['idGasto'] . "'";
        $registro = parent::connectDBGapsi()->query($consultaRegistro);
        $montoRegistro = $registro->result_array();

        if ($montoComprobante >= $montoRegistro) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarMontoComprobado($datos)
    {
        parent::connectDBGapsi()->trans_begin();

        $consultaMontoCR = "select SUM(Monto) as monto from db_ComprobacionRegistro where Registro = '" . $datos['idGasto'] . "'";
        $consulta = parent::connectDBGapsi()->query($consultaMontoCR);
        $monto = $consulta->result_array();

        $consultaMontoR = "select MontoComprobado from db_Registro where ID = '" . $datos['idGasto'] . "'";
        $consultaRegistro = parent::connectDBGapsi()->query($consultaMontoR);
        $montoRegistro = $consultaRegistro->result_array();

        $total = $monto[0]['monto'] + $montoRegistro[0]['MontoComprobado'];

        $query = "update db_Registro set
MontoComprobado = '" . $total . "'
WHERE ID = '" . $datos['idGasto'] . "'";

        parent::connectDBGapsi()->query($query);

        if (parent::connectDBGapsi()->trans_status() === FALSE) {
            parent::connectDBGapsi()->trans_rollback();
            return ['code' => 400];
        } else {
            parent::connectDBGapsi()->trans_commit();
            return ['code' => 200, 'last' => "OK"];
        }
    }

    public function insertarArchivosGastosGapsi(array $datos)
    {
        $consulta = parent::connectDBPrueba()->query("insert into "
            . "t_archivos_gastos_gapsi"
            . "(IdGasto, Archivos, Email, IdUsuario)"
            . "VALUES "
            . "('" . $datos['idGapsi'] . "', '" . $datos['archivos'] . "', '" . $datos['email'] . "', '" . $datos['idUsuario'] . "')");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }
}
