<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenUsuario;
use Librerias\V2\PaquetesSucursales\SucursalAdist as Sucursal;
use Librerias\V2\PaquetesSucursales\Censo as Censo;
use Modelos\Modelo_GestorServicio as ModeloServicio;

Class GestorServicios {

    private $DBServicios;
    private $almacenUsuario;
    private $sucursal;
    private $censo;

    public function __construct() {
        $this->DBServicios = new ModeloServicio();
    }

    public function getServicios() {
        $idUsuario = Usuario::getId();
        $rol = Usuario::getRol();
        $permisos = Usuario::getPermisos();
        $nombre = Usuario::getNombre();
        $acceso = in_array("AMC", $permisos);

        if ($acceso) {
            $informacion['servicios'] = $this->DBServicios->getTodosServiciosCableado();
        } else {
            $informacion['servicios'] = $this->DBServicios->getServiciosDeTecnico($idUsuario);
        }

        $informacion['rol'] = $rol;
        $informacion['acceso'] = $acceso;
        $informacion['nombre'] = $nombre;
        return $informacion;
    }

    public function getInformacion(string $servicio, array $datos = array()) {
//        var_dump($datos);
        $informacion = array();
        switch ($servicio) {
            case 'Cableado':
                $this->almacenUsuario = new AlmacenUsuario();
                $this->sucursal = new Sucursal($datos['datosServicio']['Sucursal']);
                $this->censo = new Censo($this->sucursal);
                $informacion['tipoMaterialAlmacen'] = $this->almacenUsuario->getTipoMaterialAlmacen();
                $informacion['materialAlmacen'] = $this->almacenUsuario->getAlmacen(null);
                $informacion['areasSucursal'] = $this->sucursal->getAreas();
                $informacion['censoSwitch'] = $this->censo->getRegistrosComponente(28);
                break;
            case 'Instalaciones':
                $this->sucursal = new Sucursal($datos['datosServicio']['sucursal']);
                $informacion['areasSucursal'] = $this->sucursal->getAreas();
//                var_dump($informacion);
                break;
            default:
                break;
        }

        return $informacion;
    }

    public function getCatalogoSwitch() {
        $this->almacenUsuario = new AlmacenUsuario();
        $this->sucursal = new Sucursal(1);
        $this->censo = new Censo($this->sucursal);
        $datos['marcaEquipo'] = $this->almacenUsuario->getMarcaEquipo();
        $datos['censoSwitch'] = $this->censo->getRegistrosComponente(28);
        return $datos;
    }

}
