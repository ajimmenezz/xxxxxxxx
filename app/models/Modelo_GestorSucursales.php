<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo;

class Modelo_GestorSucursales extends Modelo {

    public function __construct(string $db = 'pruebas') {
        parent::__construct($db);
    }

    public function getSucursalesCliente(string $idCliente) {
        $consulta = array();
        try {
            $consulta = $this->consulta('select                    
                                         id, 
                                         Nombre as text                                         
                                      from 
                                         cat_v3_sucursales
                                        WHERE IdCliente = "' . $idCliente . '"');
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function getSucursales() {
        $consulta = array();
        try {
            $consulta = $this->consulta('select                    
                                         id, 
                                         Nombre as text
                                      from 
                                         cat_v3_sucursales');
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

}
