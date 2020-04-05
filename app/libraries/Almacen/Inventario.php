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
        $infoModelo = array();
        $inventario = $this->DBI->getInventarioId($idInventario);
        $infoModelo['id'] = $inventario[0]['Id'];
        $infoModelo['modelo'] = $inventario[0]['Producto'];
        $infoModelo['serie'] = $inventario[0]['Serie'];
        $infoModelo['estatus'] = $inventario[0]['Estatus'];
        $infoModelo['idModelo'] = $inventario[0]['IdProducto'];
        $infoModelo['ticketFolio'] = '0';

        return $infoModelo;
    }

    public function getNotasInventarioId(string $idInventario) {
        $comentarios = array();
        $notasInventario = $this->DBI->getNotasInventarioId($idInventario);

        foreach ($notasInventario as $key => $value) {
            $comentarios[$key]['nombre'] = $value['Usuario'];
            $comentarios[$key]['comentario'] = $value['Nota'];
            $comentarios[$key]['fecha'] = $value['Fecha'];
            $comentarios[$key]['evidencias'] = $value['Archivos'];
        }

        return $comentarios;
    }

    public function actualizarNotasInventario(array $datos) {
        $arrayNotaInventario = $this->setArrayNotaInventario($datos);
        $this->DBI->actualizarNotasInventario($arrayNotaInventario, array('Id' => $datos['id']));
    }

    public function setNotaInventario(array $datos) {
        $arrayNotaInventario = $this->setArrayNotaInventario($datos);
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

    public function getIdsRehabilitacion(string $idInventario = '') {
        $arrayIds = array();

        if (!empty($idInventario)) {
            $whereInventario = "AND IdInventario <> '" . $idInventario . "'";
        } else {
            $whereInventario = "";
        }

        $inventarioRehabilitacion = $this->DBI->getInventarioRehabilitacionRefaccion("WHERE Bloqueado = '1' " . $whereInventario);

        foreach ($inventarioRehabilitacion as $key => $value) {
            array_push($arrayIds, $value['IdRefaccion']);
        }

        return implode(',', $arrayIds);
    }

    public function setInventarioRehabilitacionRefaccion(array $datos) {
        $inventarioReabilitacioRefaccion = $this->DBI->getInventarioRehabilitacionRefaccion('WHERE IdInventario = "' . $datos['id'] . '" AND IdRefaccion = "' . $datos['idRefaccion'] . '"');

        if (empty($inventarioReabilitacioRefaccion)) {
            $this->DBI->setInventarioRehabilitacionRefaccion(array(
                'IdInventario' => $datos['id'],
                'IdRefaccion' => $datos['idRefaccion'],
                'Bloqueado' => 1
            ));
        } else {
            $this->DBI->actualizarInventarioRehabilitacionRefaccion(array(
                'IdInventario' => $datos['id'],
                'IdRefaccion' => $datos['idRefaccion'],
                'Bloqueado' => $datos['bloqueado']
            ),array('IdInventario' => $datos['id'], 'IdRefaccion' => $datos['idRefaccion']));
        }
    }

}
