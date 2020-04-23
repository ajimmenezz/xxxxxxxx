<?php

namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Librerias\V2\PaquetesGenerales\Utilerias\Archivo as Archivo;
use Librerias\V2\PaquetesAlmacen\AlmacenVirtual as AlmacenUsuario;
use Librerias\V2\PaquetesSucursales\SucursalAdist as Sucursal;
use Librerias\V2\PaquetesSucursales\Censo as Censo;
use Librerias\V2\PaquetesEquipo\Equipo as Equipo;
use Modelos\Modelo_GestorServicio as ModeloServicio;

Class GestorServicios {

    private $DBServicios;
    private $almacenUsuario;
    private $sucursal;
    private $censo;
    private $equipo;

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
                $informacion['operaciones'] = $this->getOperacionesPoliza();
                $this->equipo = new Equipo();
                $this->almacenUsuario = new AlmacenUsuario();
                $this->almacenUsuario->getInventarioUsuario();
                $informacion['equipos'] = $this->almacenUsuario->getInventarioUsuario();
                if (!empty($datos['datosServicio']['sucursal'])) {
                    $this->sucursal = new Sucursal($datos['datosServicio']['sucursal']);
                    $informacion['areasAtencionSucursal'] = $this->sucursal->getAreas();
                    $informacion['areasSucursal'] = $this->sucursal->getAreasSucursal();
                    $informacion['areasPuntosSucursal'] = $this->sucursal->getAreasPuntoSucursal();
                    $informacion['instalaciones'] = $this->DBServicios->getInstalaciones($datos['datosServicio']['servicio']);
                }
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

    public function getOperacionesPoliza() {
        $datos = array();
        $consulta = $this->DBServicios->getOperacionesPoliza();

        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Nombre']
            ));
        }

        return $datos;
    }

    public function setEquipo(array $datos) {

        if ($_FILES) {
            $carpeta = 'Servicios/Servicio-' . $datos['id'] . '/ServicioInstalaciones/';
            Archivo::saveArchivos($carpeta);
            $datos['archivos'] = Archivo::getString();
        } else {
            $datos['archivos'] = NULL;
        }
        $this->DBServicios->empezarTransaccion();

        if ($datos['idOperacion'] === '1') {
            $this->almacenUsuario = new AlmacenUsuario();
            $this->almacenUsuario->bloquearInventario($datos['idModelo']);
        }

        $this->DBServicios->setInstalaciones($datos);
        $this->DBServicios->finalizarTransaccion();

    }

    public function deleteEquipo(array $datos) {
        $this->DBServicios->empezarTransaccion();
        $this->DBServicios->deleteInstalacion($datos['idInstalacion']);
        $this->DBServicios->finalizarTransaccion();
    }

}
