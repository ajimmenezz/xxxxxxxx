<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

use Modelos\Modelo_Cliente as Modelo;

class GestorClientes {

    private $db;

    public function __construct() {
        $this->db = new Modelo();
    }

    public function setClientes() {
        $clientes = $this->db->setClientes();
        return $clientes;
    }

    public function getIdNombreClientes() {
        $arrayIdNombreClientes = array();
        $arrayClientes = $this->setClientes();

        foreach ($arrayClientes as $key => $value) {
            array_push($arrayIdNombreClientes, array(
                'id' => $value['Id'],
                'text' => $value['Nombre']));
        }

        return $arrayIdNombreClientes;
    }

}
