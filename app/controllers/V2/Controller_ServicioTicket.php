<?php
namespace Controladores\V2;
use Librerias\V2\Factorys\FactoryServiciosTicket as FactoryServiciosTicket;

class Controller_ServicioTicket
{
     private $factory;
     private $servicio;
     private $serviceDesk;
     
     public function atenderServicio(array $datos)
     {
        vardump($datos);
     }
     public function guardarFolio(array $datos)
     {
         
     }
}