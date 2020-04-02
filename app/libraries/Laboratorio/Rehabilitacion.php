<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Almacen\Inventario as Inventario;

class Rehabilitacion extends General {

    private $inventario;

    public function __construct() {
        parent::__construct();
        $this->DBI = \Modelos\Modelo_InventarioConsignacion::factory();
        parent::getCI()->load->helper('date');
    }

    public function getAlmacenUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();
        $this->inventario = new Inventario();

        return $this->inventario->getInventarioUsuario($usuario['Id']);
    }

    public function getModelo(array $datos) {
        $data = array();
        $infoModelo = $this->infoModelo($datos['id']);
        $data['infoBitacora'] = $infoModelo;
        $data['infoBitacora']['comentarios'] = $this->notasInventario($datos['id']);
        
        return $data;
    }

    public function infoModelo(string $idInventario) {
        $infoModelo = array();
        $inventario = $this->inventario->getInventarioId($idInventario);
        $infoModelo['id'] = $inventario[0]['Id'];
        $infoModelo['modelo'] = $inventario[0]['Producto'];
        $infoModelo['serie'] = $inventario[0]['Serie'];
        $infoModelo['estatus'] = $inventario[0]['Estatus'];
        $infoModelo['ticketFolio'] = '0';

        return $infoModelo;
    }

    public function notasInventario(string $idInventario) {
        $comentarios = array();
        $notasInventario = $this->inventario->getNotasInventarioId($idInventario);

        foreach ($notasInventario as $key => $value) {
            $comentarios[$key]['nombre'] = $value['Usuario'];
            $comentarios[$key]['comentario'] = $value['Nota'];
            $comentarios[$key]['fecha'] = $value['Fecha'];
            $comentarios[$key]['evidencias'] = $value['Archivos'];
        }

        return $comentarios;
    }

}
