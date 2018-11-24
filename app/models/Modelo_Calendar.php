<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Calendar extends Modelo_Base {
    
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }
    
    //obtener informacion, dependiendo del dia
    public function obtenerInformacionServicio() {
        $today = mdate('%Y-%m-%d', now('America/Mexico_City'));
        $consultaFechaCreacion = "SELECT 
                                        tst.Id, tst.IdTipoServicio, tst.Solicita, tst.Atiende, tst.FechaCreacion as fechaInicio,
                                        tst.IdSucursal,
                                        tipoServicio(tst.IdTipoServicio) as TipoServicio,
                                        (SELECT cvu.EmailCorporativo FROM cat_v3_usuarios cvu WHERE Id = tst.Solicita) as emailSolicita,
                                        (SELECT cvu.EmailCorporativo FROM cat_v3_usuarios cvu WHERE Id = tst.Atiende) as emailAtiende
                                    FROM
                                        t_servicios_ticket tst
                                    WHERE FechaCreacion LIKE '". $today ."%'";
        $consultaFecha = $this->consulta($consultaFechaCreacion);
        return $consultaFecha;
    }
    
}
