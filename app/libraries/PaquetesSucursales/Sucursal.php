<?php

namespace Librerias\PaquetesSucursales;

interface Sucursal {

    public function __construct(string $idSucursal);

    public function setDatos();

    public function getDatos();

    public function setGasto(array $filtros);

    public function setCompra(array $filtros);

    public function calcularTotalTranferencia(array $filtros);
}
