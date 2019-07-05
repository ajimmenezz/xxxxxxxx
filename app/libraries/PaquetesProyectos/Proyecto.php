<?php

namespace Librerias\PaquetesProyectos;

interface Proyecto {

    public function __construct(string $idProyecto);

    public function setDatos(string $idProyecto);

    public function getType();

    public function getIdSucursales();

    public function getDatosGenerales();

    public function getGasto();

    public function getCompra();

    public function calcularTotalTranferencia();
}
