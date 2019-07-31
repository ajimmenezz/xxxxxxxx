<?php
namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Modelo_Base;

class Modelo_ServicioGeneralRedes extends Modelo_Base
{
    
    
    public function getDatosServicio(string $idServicio)
    {
        var_dump($idServicio);
//        $this->consulta('SELECT * FROM t_servicios_ticket WHERE campo='.$idServicio.' $idServicio');
//        $this->select="";
        
        
    }
    public function setFolioServiceDesk(string $idServicio)
    {
        
    }
}
