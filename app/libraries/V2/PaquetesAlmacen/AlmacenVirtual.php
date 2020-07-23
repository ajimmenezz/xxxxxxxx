<?php

namespace Librerias\V2\PaquetesAlmacen;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_AlmacenVirtual as Modelo;

class AlmacenVirtual {

    private $idUsuario;
    private $DBAlmacenVirtual;

    public function __construct() {
        $this->DBAlmacenVirtual = new Modelo();
        $this->idUsuario = Usuario::getId();
    }

    public function getTipoMaterialAlmacen() {
        $datos = array();
        $consulta = $this->DBAlmacenVirtual->getTipoMaterial();

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Nombre']));
        }
        return $datos;
    }

    public function getAlmacen(string $datos = null) {
        if ($datos == null) {
            $condicion = '';
        } else {
            $condicion = 'where cap.Id = ' . $datos;
        }
        $informacion = array();
        $consulta = $this->DBAlmacenVirtual->getMaterial($condicion);

        foreach ($consulta as $value) {
            array_push($informacion, array(
                'id' => $value['Id'],
                'text' => $value['Nombre']));
        }
        return $informacion;
    }

    public function updateAlmacen(array $datos) {
        $this->DBAlmacenVirtual->empezarTransaccion();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        foreach ($datos['nodos'] as $key => $value) {
            $informacionInventario = $this->DBAlmacenVirtual->consultaInventario($value['IdMaterialTecnico']);
            $datosMovimientoInventario = array(
                'IdTipoMovimiento' => '4',
                'IdServicio' => $datos['id'],
                'IdAlmacen' => $informacionInventario[0]['IdAlmacen'],
                'IdTipoProducto' => $informacionInventario[0]['IdTipoProducto'],
                'IdProducto' => $value['IdMaterialTecnico'],
                'IdEstatus' => '17',
                'IdUsuario' => $datos['idUsuario'],
                'Cantidad' => $informacionInventario[0]['Cantidad'],
                'Serie' => $informacionInventario[0]['Serie'],
                'Fecha' => $fecha);
            $cantidadTotal = $informacionInventario[0]['Cantidad'] - $value['Cantidad'];

            $this->DBAlmacenVirtual->insertarMovimientosInventario($datosMovimientoInventario);
            $this->DBAlmacenVirtual->actualizarInventario(array('Bloqueado' => 0, 'Cantidad' => $cantidadTotal), array('Id' => $value['IdMaterialTecnico']));
        }

        $this->DBAlmacenVirtual->finalizarTransaccion();
    }

    public function getMarcaEquipo() {
        $informacion = array();
        $consulta = $this->DBAlmacenVirtual->getMarcaEquipo();

        foreach ($consulta as $value) {
            array_push($informacion, array(
                'id' => $value['IdMar'],
                'text' => $value['Marca']));
        }
        return $informacion;
    }

    public function getInventarioUsuario() {
        $datos = array();
        $consulta = $this->DBAlmacenVirtual->getInventarioUsuario($this->idUsuario);

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Producto'],
                'serie' => $value['Serie']
            ));
        }

        return $datos;
    }

    public function bloquearInventario(string $idInvetario) {
        $this->DBAlmacenVirtual->actualizarInventario(array('Bloqueado' => 1), array('Id' => $idInvetario));
    }

    public function consultaInventario(string $idInventario) {
        return $this->DBAlmacenVirtual->consultaInventario($idInventario);
    }

    public function actualizarInventario(array $datos, array $where) {
        $this->DBAlmacenVirtual->actualizarInventario($datos, $where);
    }

    public function getCatalogoAlmacenesVirtuales(string $where) {
        return $this->DBAlmacenVirtual->getCatalogoAlmacenesVirtuales($where);
    }

    public function setMovimientoInventarioEntradaSalida(array $datos) {
        $this->DBAlmacenVirtual->empezarTransaccion();
        $datosInventario = $this->consultaInventario($datos['idInventario']);

        if (!empty($datosInventario)) {
            $fecha = $this->DBAlmacenVirtual->fechaActualBD();

            $this->DBAlmacenVirtual->insertarMovimientosInventario(array(
                "IdTipoMovimiento" => $datos['primerIdMovimiento'],
                "IdAlmacen" => $datosInventario[0]['IdAlmacen'],
                "IdTipoProducto" => $datosInventario[0]['IdTipoProducto'],
                "IdProducto" => $datosInventario[0]['IdProducto'],
                "IdEstatus" => 17,
                "IdUsuario" => $this->idUsuario,
                "Cantidad" => $datosInventario[0]['Cantidad'],
                "Serie" => $datosInventario[0]['Serie'],
                "Fecha" => $fecha[0]['Fecha']
            ));

            $idSalida = $this->DBAlmacenVirtual->ultimoId();

            foreach ($datos['datosMovimientos'] as $key => $value) {
                $this->actualizarInventario(array(
                    "IdAlmacen" => $value['IdAlmacen'],
                    "IdTipoProducto" => $value['IdTipoProducto'],
                    "IdProducto" => $value['IdProducto'],
                    "IdEstatus" => $value['IdEstatus'],
                    "Cantidad" => $value['Cantidad'],
                    "Serie" => $value['Serie']
                        ), array('Id' => $datos['idInventario']));

                $this->DBAlmacenVirtual->insertarMovimientosInventario(array(
                    "IdMovimientoEnlazado" => $idSalida,
                    "IdTipoMovimiento" => $datos['segundoIdMovimiento'],
                    "IdAlmacen" => $value['IdAlmacen'],
                    "IdTipoProducto" => $value['IdTipoProducto'],
                    "IdProducto" => $value['IdProducto'],
                    "IdEstatus" => $value['IdEstatus'],
                    "IdUsuario" => $this->idUsuario,
                    "Cantidad" => $value['Cantidad'],
                    "Serie" => $value['Serie'],
                    "Fecha" => $fecha[0]['Fecha']
                ));
            }
        }
        
        $this->DBAlmacenVirtual->finalizarTransaccion();
    }

    public function consultaInventarioWhere(string $where){
        return $this->DBAlmacenVirtual->consultaInventarioWhere($where);
    }
    
    public function insertarInventario(array $datos){
        $this->DBAlmacenVirtual->insertarInventario($datos);
        return $this->DBAlmacenVirtual->ultimoId();
    }
}
