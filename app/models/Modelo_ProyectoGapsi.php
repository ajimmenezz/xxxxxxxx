<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_ProyectoGapsi extends Modelo_Base {

    public function getInformacion(string $idProyecto) {
        
        return array($idProyecto);
    }

}
