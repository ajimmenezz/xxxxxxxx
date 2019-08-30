<?php

namespace Librerias\RH;
use Modelos\Modelo_Calendar as Calendario;

class CalendarioPermisos extends Calendario{
    private $calendario;
    public function __construct() {
    parent::__construct();
            $this->calendario= new Calendario;
        }
        
    public function PermisosUsuario(array $datos, string $fecha) {
        $dia = $this->calcularDia($fecha);
        switch ($dia){
            case "Domingo":
                $diasAntes=0;
                $dia=13;
            break;
            case "Lunes":
                $diasAntes=1;
                $dia=12;
            break;
            case "Martes":
                $diasAntes=2;
                $dia=11;
            break;
            case "Miercoles":
                $diasAntes=3;
                $dia=10;
            break;
            case "Jueves":
                $diasAntes=4;
                $dia=9;
            break;
            case "Viernes":
                $diasAntes=5;
                $dia=8;
            break;
            case "Sabado":
                $diasAntes=6;
                $dia=7;
            break;
        
            default;
                echo "No es un número de fecha válido";
        }
        
        $fechaMinima=date("Y-m-d",strtotime($fecha."- ".$diasAntes." days"));
        $fechaMaxima=date("Y-m-d",strtotime($fecha."+ ".$dia." days"));
        
        $res = $this->calendario->consultaPermisos($datos['id'],$fechaMinima, $fechaMaxima);
        
        return $res;
    }
     private function calcularDia($inFecha) {
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
        $fecha = $dias[date('N', strtotime($inFecha))];
        return($fecha);
    }

    
    
}

