<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_MesaDeAyuda_Seguimiento extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de unir tablas para mostrar los datos
     * 
     * @return array regresa todos los datos de una o varias tablas
     */

    public function bindingQuery(string $sentencia, array $datos) {
        $consulta = parent::connectDBPrueba()->query($sentencia, $datos);
        return $consulta;
    }

    public function getGeneralesUber(string $servicio){
        $sentencia = ""
                . "select "
                . "Ticket, "
                . "Personas, "
                . "Fecha, "
                . "Origen, "
                . "Destino, "
                . "Proyecto "
                . "from t_uber_generales "
                . "where IdServicio = '".$servicio."'";
        return parent::consulta($sentencia);
    }
    
}
