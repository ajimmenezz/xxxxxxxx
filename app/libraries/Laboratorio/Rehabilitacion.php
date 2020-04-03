<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Almacen\Inventario as Inventario;
use Librerias\Almacen\Equipo as Equipo;

class Rehabilitacion extends General {

    private $inventario;

    public function __construct() {
        parent::__construct();
        $this->DBI = \Modelos\Modelo_InventarioConsignacion::factory();
        $this->inventario = new Inventario();
        parent::getCI()->load->helper('date');
    }

    public function getAlmacenUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();

        return $this->inventario->getInventarioUsuario($usuario['Id']);
    }

    public function getModelo(array $datos) {
        $data = array();
        $infoModelo = $this->inventario->getInventarioId($datos['id']);
        $data['infoBitacora'] = $infoModelo;
        $data['infoBitacora']['comentarios'] = $this->inventario->getNotasInventarioId($datos['id']);
        $equipo = new Equipo($infoModelo['idModelo']);
        $data['infoBitacora']['refacciones'] = $equipo->getRefaccionesEquipo();
        return $data;
    }

    public function setComentario(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $archivos = NULL;
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasNota/';
            $archivos = setMultiplesArchivos($CI, 'archivosAgregarNotas', $carpeta);
            $archivos = implode(',', $archivos);
        }

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fecha'] = $fechaCaptura;
        $datos['evidencia'] = $archivos;

        if ($datos['operacion'] === 'actualizar') {
            $this->inventario->actualizarNotasInventario($datos);
        } else {
            $this->inventario->setArrayNotaInventario($datos);
        }
    }

}
