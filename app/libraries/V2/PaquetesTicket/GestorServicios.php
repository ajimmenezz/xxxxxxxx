<?php
namespace Librerias\V2\PaquetesTicket;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_GestorServicio as ModeloServicio;

Class GestorServicios {
    private $DBServicios;
    
    public function __construct() {
        $this->DBServicios = new ModeloServicio();
    }
    
    public function getDatosServicios() {
        $idUsuario = Usuario::getId();
        $rol = Usuario::getRol();
        
        if($rol == "Jefe"){
            $informacion['servicios'] = $this->DBServicios->getServicios($idUsuario);
        }else{
            $informacion['servicios'] = $this->DBServicios->getServiciosDeTecnico($idUsuario);
        }
        $informacion['rol'] = $rol;
        return $informacion;
    }
    
    static public function factory($driver = null) {
        return new static($driver);
    }
}