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
        $infoModelo['ticketFolio'] = $inventario[0]['TicketFolio'];

        return $infoModelo;
    }

    public function getNotasInventarioId(string $idInventario) {
        $comentarios = array();
        $notasInventario = $this->DBI->getNotasInventarioId("WHERE IdInventario = '" . $idInventario . "'");

        foreach ($notasInventario as $key => $value) {
            $comentarios[$key]['id'] = $value['Id'];
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
            $whereInventario = "AND IdInventarioEquipo <> '" . $idInventario . "'";
        } else {
            $whereInventario = "";
        }

        $inventarioRehabilitacion = $this->DBI->getInventarioRehabilitacionRefaccion("WHERE Bloqueado = '1' " . $whereInventario);

        foreach ($inventarioRehabilitacion as $key => $value) {
            array_push($arrayIds, $value['IdInventarioRefaccion']);
        }

        return implode(',', $arrayIds);
    }

    public function setInventarioRehabilitacionRefaccion(array $datos) {
        $this->DBI->iniciaTransaccion();

        $inventarioReabilitacioRefaccion = $this->DBI->getInventarioRehabilitacionRefaccion('WHERE IdInventarioEquipo = "' . $datos['id'] . '" AND IdInventarioRefaccion = "' . $datos['idRefaccion'] . '"', $datos['id']);

        if (empty($inventarioReabilitacioRefaccion)) {
            $this->DBI->setInventarioRehabilitacionRefaccion(array(
                'IdInventarioEquipo' => $datos['id'],
                'IdInventarioRefaccion' => $datos['idRefaccion'],
                'Bloqueado' => 1
            ));
        } else {
            $this->DBI->actualizarInventarioRehabilitacionRefaccion(array(
                'IdInventarioEquipo' => $datos['id'],
                'IdInventarioRefaccion' => $datos['idRefaccion'],
                'Bloqueado' => $datos['bloqueado']
                    ), array('IdInventarioEquipo' => $datos['id'], 'IdInventarioRefaccion' => $datos['idRefaccion']));
        }
        
        $this->DBI->actualizarInventario(array('Bloqueado' => 1), array('Id' => $datos['id']));

        if ($this->DBI->estatusTransaccion() === FALSE) {
            $this->DBI->roolbackTransaccion();
        } else {
            $this->DBI->commitTransaccion();
        }
    }

    public function editarEstatusAlmacen(array $datos) {
        $this->DBI->editarEstatusAlmacen($datos);
    }

    public function setRefaccionDeshueso(array $datos) {
        $arrayRefacciones = array();
        $datosAlmacen = $this->DBI->getDatosAlmacenVirtualUsuario($datos['idUsuario']);

        foreach ($datos['infoDeshueso'] as $key => $value) {
            $arrayRefacciones[$key]['IdAlmacen'] = $datosAlmacen['Id'];
            $arrayRefacciones[$key]['IdProducto'] = $value[0];
            $arrayRefacciones[$key]['IdTipoProducto'] = '2';
            $arrayRefacciones[$key]['IdEstatus'] = $value[2];
            $arrayRefacciones[$key]['Cantidad'] = '1';
            $arrayRefacciones[$key]['Serie'] = $value[3];
        }

        $this->DBI->guardarRefaccionesDeshueso($arrayRefacciones, $datos['id']);
    }

    public function setRevisionRehabilitacion(array $datos) {
        $arrayRefacciones = array();
        $refacciones = $this->DBI->getInventarioRehabilitacionRefaccion('WHERE IdInventarioEquipo = "' . $datos['id'] . '" AND Bloqueado = 1');

        foreach ($refacciones as $key => $value) {
            $datosInventario = $this->DBI->getInventarioId($value['IdInventarioRefaccion']);
            $arrayRefacciones[$key]['IdAlmacen'] = $datosInventario[0]['IdAlmacen'];
            $arrayRefacciones[$key]['IdProducto'] = $datosInventario[0]['IdProducto'];
            $arrayRefacciones[$key]['IdTipoProducto'] = '2';
            $arrayRefacciones[$key]['IdEstatus'] = '40';
            $arrayRefacciones[$key]['Cantidad'] = '0';
            $arrayRefacciones[$key]['Serie'] = $datosInventario[0]['Serie'];
        }

        $this->DBI->setRevisionRehabilitacion($arrayRefacciones, $datos['id']);
    }

    public function getNotaInventarioWhere(string $where) {
        return $this->DBI->getNotasInventarioId($where);
    }

    public function getEstatusProductoConsignacion() {
        return $this->DBI->getEstatusProductoConsignacion();
    }

    public function actualizarEvidencaNotaInventario(array $datos) {
        $this->DBI->actualizarNotasInventario(array('Archivos' => $datos['archivo']), array('Id' => $datos['id']));
    }
    
    public function getInventarioRefaccionesUsuario(array $datos){
        return $this->DBI->getInventarioRefaccionesUsuario($datos);
    }

}
