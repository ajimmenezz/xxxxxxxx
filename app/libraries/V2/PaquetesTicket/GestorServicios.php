<?php
namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Usuario as Usuario;

Class GestorServicios {
    private $DBServicios;
    
    public function __construct() {
        $this->DBServicios = \Modelos\Modelo_GestorServicio::factory();
    }
    
    public function getDatosServicios() {
        $idUsuario = Usuario::getId();
        $rol = Usuario::getRol();
        
        if($rol == "Jefe"){
            $servicios = $this->DBServicios->getServicios($idUsuario);
        }else{
            $servicios = $this->DBServicios->getServiciosDeTecnico($idUsuario);
        }
        return $servicios;
    }
    
    public function getRol() {
        $rol = Usuario::getRol();
        return $rol;
    }
    
    static public function factory($driver = null) {
        return new static($driver);
    }
}