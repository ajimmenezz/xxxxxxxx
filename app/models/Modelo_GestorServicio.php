<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

Class Modelo_GestorServicio extends Base {

    public function __construct() {
        parent::__construct('pruebas');
    }

    public function getServicios($idJefe) {
        $totalServicios = array();
//        try {
            $trabajadores = $this->consulta("SELECT Id FRO `cat_v3_usuarios` WHERE IdJefe = '" . $idJefe . "'");

            if (!empty($trabajadores)) {
                foreach ($trabajadores as $value) {
                    $datosServicio = $this->getServiciosDeTecnico($value['Id']);
                    array_push($totalServicios, $datosServicio);
                }
            } else {
                array_push($totalServicios, array(
                    'Error' => '404'
                ));
            }
            $miServicio = $this->getServiciosDeTecnico($idJefe);
            array_push($totalServicios, $miServicio);
        } else {
            array_push($totalServicios, array(
                'Error' => '404'
            ));
        }
        return $totalServicios;
    }

    public function getServiciosDeTecnico($idTrabajador) {
        $consulta=array();
                try {
        $consulta = $this->consulta("SELECT 
                                        tst.Id, 
                                        tst.Ticket, 
                                        tst.IdSolicitud, 
                                        csd.Nombre AS TipoServicio, 
                                        tst.FechaCreacion,
                                        nombreUsuario(tst.Atiende) Atiende,
                                        tst.Descripcion, 
                                        ce.Nombre As Estatus, 
                                        ts.Folio 
                                    FRO t_servicios_ticket AS tst 
                                    JOIN cat_v3_estatus AS ce ON tst.IdEstatus = ce.Id 
                                    JOIN cat_v3_servicios_departamento AS csd ON tst.IdTipoServicio = csd.Id 
                                    JOIN t_solicitudes AS ts ON tst.IdSolicitud = ts.Id 
                                    WHERE tst.Atiende = '" . $idTrabajador . "' AND tst.IdEstatus IN(1,2,3,10,12)");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

}
