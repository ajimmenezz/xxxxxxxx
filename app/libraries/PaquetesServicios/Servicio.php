<?php

namespace Librerias\PaquetesServicios;

interface Servicio {

    public function __construct(string $servicio);

    public function setDatos();

    public function getDatos();

    public function setGasto(array $filtros);

    public function setCompra(array $filtros);

    public function calcularTotalTranferencia(array $filtros);
}
