<?php

namespace Librerias\V2\PaquetesSucursales;

use Librerias\V2\PaquetesSucursales\interfaces\Sucursal as Sucursal;
use Modelos\Modelo_Sucursal_Adist as Modelo;

class SucursalAdist implements Sucursal {

    private $id;
    private $nombre;
    private $totalTransferencia;
    private $gasto;
    private $compra;
    private $DBSucursal;

    public function __construct(string $idSucursal) {
        $this->id = $idSucursal;
        $this->DBSucursal = new Modelo();                
        $this->setDatos();
    }

    public function setDatos() {
//        $consulta = $this->DBSucursal->getDatos();
    }

    public function calcularTotalTranferencia(array $filtros) {
        
    }

    public function setCompra(array $filtros) {
        
    }

    public function setGasto(array $filtros) {
        
    }

    public function getDatos() {
    }
    
    public function getAreas(){        
        $datos = array();
        $consulta = $this->DBSucursal->getAreas();
        
        foreach ($consulta as $value) {
            array_push($datos, array(
                'id' => $value['Id'],
                'text' => $value['Nombre']
            ));
        }
        
        return $datos;
    }

    public function getId() {
        return $this->id;
    }
        

}
