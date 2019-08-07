<?php

namespace Librerias\V2\PaquetesTicket;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class Material{
    
    private $DBServicioGeneralRedes;
    private $id;
    public function __construct($idTecnico) {
        $this->DBServicioGeneralRedes=new Modelo();
        $this->id=$idTecnico;
        $this->getMaterialTecnico();
    }
    
    public function getMaterialTecnico() {
        $query="Select * from table";
        $consulta=$this->DBServicioGeneralRedes->consulta($query);
        
    }
    
}
