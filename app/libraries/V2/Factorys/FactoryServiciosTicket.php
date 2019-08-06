<?php
namespace Librerias\V2\Factorys;
use Librerias\V2\PaquetesServicios\Servicio as Servicio;
use Librerias\V2\PaquetesTicket\ServicioGeneralRedes as GeneralRedes;
class FactoryServiciosTicket
{
    private $instancia ;
    private $servicio;
    
    public function __contruct()
    {
        
    }
    public function getInstancia(){
        
    }

    public function getServicio(string $tipo,string $idServicio){
        
        switch ($tipo)
        {
            case 'GeneralRedes':
                $this->servicio= new GeneralRedes($idServicio);//4
                break;
        }
        return $this->servicio;
    }
    
}

