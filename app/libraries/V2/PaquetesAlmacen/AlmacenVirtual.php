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

    public function getAlmacen() {
        $datos = array();
        $consulta = $this->DBAlmacenVirtual->getMaterial($this->idUsuario);

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Producto'],
                'cantidad' => $value['Cantidad'],
                'bloqueado' => $value['Bloqueado']));
        }
        return $datos;
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

}