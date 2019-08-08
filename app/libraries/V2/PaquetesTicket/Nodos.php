<?php
namespace Librerias\V2\PaquetesTicket;
use Modelos\Modelo_ServicioGeneralRedes as Modelo;

class Nodos
{
    private $DBServiciosGeneralRedes;
    private $insert;
    public function __construct(array $data) {
        $this->DBServiciosGeneralRedes= new Modelo();
        
        $query= "INSERT INTO 
                    `t_redes_nodos`
                        (`IdServicio`, `IdArea`, `Nombre`, `IdSwitch`, `NumeroSwitch`, `Flag`)
                        VALUES 
                            ("+$data['idServicio']+","
                              +$data['idArea']+",'"
                              +$data['Nombre']+"',"
                              +$data['idSwitch']+",'"
                              +$data['NumeroSwitch']+"',"+
                              +$data['Flag']+
                              ")  ";
        $this->insert=$this->DBServiciosGeneralRedes->insertar($query);
        $IdServicio =$this->DBServiciosGeneralRedes->insert_id();
        var_dump($IdServicio);
    }
}