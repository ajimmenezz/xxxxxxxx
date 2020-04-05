<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Equipo extends Modelo_Base {
    
    public function __construct() {
        parent::__construct();
    }

    public function getRefaccionesEquipo(string $idModelo, string $where = '') {        
        $consulta = $this->consulta("SELECT 
                                        *
                                        FROM cat_v3_componentes_equipo
                                        WHERE IdModelo = '" . $idModelo . "'
                                        AND Flag = 1
                                        " . $where);
        return $consulta;
    }
    
    public function getRefaccionesEquipoRehabilitacion(string $idModelo) {        
        $consulta = $this->consulta("SELECT 
                                        cvce.Id,
                                        cvce.Nombre,
                                        cvce.NoParte,
                                        tirr.Bloqueado
                                        FROM cat_v3_componentes_equipo AS cvce
                                        LEFT JOIN t_inventario_rehabilitacion_refaccion AS tirr
                                        ON tirr.IdRefaccion = cvce.Id
                                        WHERE cvce.IdModelo = '" .  $idModelo . "'
                                        AND cvce.Flag = 1");
        return $consulta;
    }

}
