<?php

namespace Librerias\Almacen;

use Modelos\Modelo_InventarioConsignacion as Modelo;

class Inventario {

    private $DBI;

    public function __construct() {
        $this->DBI = new Modelo;
    }

    public function getInventarioUsuario(string $usuario) {
        return $this->DBI->getInventarioUsuario($usuario);
    }

    public function getInventarioId(string $idInventario) {
        return $this->DBI->getInventarioId($idInventario);
    }

    public function getNotasInventarioId(string $idInventario) {
        return $this->DBI->getNotasInventarioId($idInventario);
    }

    public function actualizarNotasInventario(array $datos) {
        $arrayNotaInventario = $this->setArrayNotaInventario($datos);

        $this->DBI->actualizarNotasInventario($arrayNotaInventario, array('Id' => $datos['id']));
    }

    public function setNotaInventario(array $datos) {
        $this->DBI->setNotaInventario($arrayNotaInventario);
    }

    public function setArrayNotaInventario(array $datos) {
        return array(
        'IdInventario' => $datos['idInventario'],
        'IdEstatus' => $datos['estatus'],
        'IdUsuario' => $datos['usuario'],
        'Nota' => $datos['comentario'],
        'Archivos' => $datos['evidencia'],
        'Fecha' => $datos['fecha']);
    }

}
