<?php

namespace Librerias\Almacen;

use Modelos\Modelo_InventarioConsignacion as Modelo;

class Inventario{

    private $DBI;

    public function __construct() {
        $this->DBI = new Modelo;
    }

    public function getInventarioUsuario(string $usuario) {
        return $this->DBI->getInventarioUsuario($usuario);
    }
    
    public function getInventarioId(string $idInventario) {
        return $this->DBI->getInventarioUsuario($getInventario);
    }

}
