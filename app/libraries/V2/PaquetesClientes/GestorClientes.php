<?php

namespace Librerias\V2\PaquetesClientes;

use Modelos\Modelo_Cliente as Modelo;

class GestorClientes {

    private $DBModelo_Cliente;

    public function __construct() {
        $this->DBModelo_Cliente = new Modelo();
    }

    public function getClientes(string $idClienteActivo = '') {
        $consulta = array();
        $where = '';
        
        if(!empty($idClienteActivo)){
            $where = 'WHERE Id IN(' .  $idClienteActivo . ')';
        }

        $consulta = $this->DBModelo_Cliente->setClientes($where);

        return $consulta;
    }

}
