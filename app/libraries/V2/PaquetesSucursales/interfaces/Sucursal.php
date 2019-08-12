<?php

namespace Librerias\V2\PaquetesSucursales\interfaces;

interface Sucursal {

    public function __construct(string $idSucursal);

    public function setDatos();

    public function getDatos();
    
    public function getId();

    public function setGasto(array $filtros);

    public function setCompra(array $filtros);

    public function calcularTotalTranferencia(array $filtros);
    
    public function getAreas();
}
