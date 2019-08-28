<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo;

class Modelo_AlmacenVirtual extends Modelo {

    public function __construct() {
        parent::__construct();
    }

    public function getMaterial(string $idUsuario) {
        return $this->ejecutaFuncion('call getInventoryByUser(' . $idUsuario . ')');
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

}
