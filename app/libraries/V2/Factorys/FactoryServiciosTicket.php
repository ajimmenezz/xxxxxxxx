<?php
namespace Librerias\V2\Factorys;
use Librerias\V2\PaquetesServicios\Servicio as Servicio;
use Librerias\V2\PaquetesTicket\Redes\ServicioCableado as GeneralRedes;
use Librerias\V2\PaquetesTicket\Nodos as Nodos;
use Librerias\V2\PaquetesTicket\Poliza\ServicioInstalaciones as Instalaciones;


class FactoryServiciosTicket
{
    private $instancia ;
    private $servicio;
    private $nodo;
    
    public function __contruct()
    {
        
    }
    public function getInstancia(){
        
    }

    public function getServicio(string $tipo,string $idServicio){
        
        switch ($tipo)
        {
            case 'Cableado':
                $this->servicio= new GeneralRedes($idServicio);//4
                break;
            case 'Instalaciones':
                $this->servicio= new Instalaciones($idServicio);//4
                break;
        }
        return $this->servicio;
    }
}

