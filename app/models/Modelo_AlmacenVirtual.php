<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo;

class Modelo_AlmacenVirtual extends Modelo {

    public function __construct() {
        parent::__construct();
    }

    public function getTipoMaterial() {
        return $this->consulta('select Id, Nombre from cat_v3_accesorios_proyecto where Flag = 1');
    }

    public function getMaterial(string $condicion) {
        return $this->consulta('select 
                                    ces.Id, 
                                    Clave, 
                                    ces.Nombre 
                                from cat_v3_equipos_sae as ces
                                join cat_v3_material_proyectos as cmp on ces.Id = cmp.IdMaterial
                                join cat_v3_accesorios_proyecto as cap on cmp.IdAccesorio = cap.Id ' . $condicion);
    }

    public function insertarMovimientosInventario(array $datos) {
        $this->insertarArray('t_movimientos_inventario', $datos);
    }

    public function consultaInventario(string $idInventario) {
        $consulta = $this->consulta('SELECT * FROM t_inventario WHERE Id = "' . $idInventario . '"');
        return $consulta;
    }

    public function actualizarInventario(array $datos, array $where) {
        $consulta = $this->actualizarArray('t_inventario', $datos, $where);
        return $consulta;
    }

    public function getMarcaEquipo() {
        $consulta = $this->consulta('select 
                                        cvme.Id as IdMar,
                                        cvme.Nombre as Marca
                                    from cat_v3_lineas_equipo cvle inner join cat_v3_sublineas_equipo cvse
                                        on cvle.Id = cvse.Linea
                                    inner join cat_v3_marcas_equipo cvme
                                        on cvse.Id = cvme.Sublinea 
                                    where cvse.Id = 28 and cvme.Flag = 1;');
        return $consulta;
    }

    public function getInventarioUsuario(string $usuario) {
        $consulta = $this->consulta("SELECT 
                                            inve.Id, MODELO(inve.IdProducto) AS Producto, inve.Serie
                                        FROM
                                            t_inventario inve
                                        WHERE
                                            inve.IdAlmacen IN (SELECT 
                                                    Id
                                                FROM
                                                    cat_v3_almacenes_virtuales
                                                WHERE
                                                    (IdTipoAlmacen = 1
                                                        AND IdReferenciaAlmacen = '" . $usuario . "')
                                                        OR (IdTipoAlmacen = 4
                                                        AND IdResponsable = '" . $usuario . "'))
                                                AND inve.Cantidad > 0
                                                AND inve.IdEstatus = 17
                                                AND inve.IdtipoProducto = 1
                                                AND inve.Bloqueado = 0");
        return $consulta;
    }

    public function getCatalogoAlmacenesVirtuales(string $where = '') {
        $consulta = $this->consulta("SELECT * FROM cat_v3_almacenes_virtuales " . $where);
        return $consulta;
    }

    public function consultaInventarioWhere(string $where) {
        $consulta = $this->consulta("SELECT * FROM t_inventario " . $where);
        return $consulta;
    }
    
    public function insertarInventario(array $datos){
        $this->insertarArray('t_inventario', $datos);
    }

}
