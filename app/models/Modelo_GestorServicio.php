<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

Class Modelo_GestorServicio extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getServicios($idJefe) {
        $totalServicios = array();
        try {
            $trabajadores = $this->consulta("SELECT Id FROM cat_v3_usuarios WHERE IdJefe = '" . $idJefe . "'");

            if (!empty($trabajadores)) {
                foreach ($trabajadores as $value) {
                    $datosServicio = $this->getServiciosDeTecnico($value['Id']);

                    array_push($totalServicios, $datosServicio);
                }
                $miServicio = $this->getServiciosDeTecnico($idJefe);
                array_push($totalServicios, $miServicio);
            } else {
                array_push($totalServicios, array(
                    'Error' => '404'
                ));
            }
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }

        return $totalServicios;
    }

    public function getServiciosDeTecnico(string $idTrabajador) {
        $consulta = array();
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
                                    FROM t_servicios_ticket AS tst 
                                    JOIN cat_v3_estatus AS ce ON tst.IdEstatus = ce.Id 
                                    JOIN cat_v3_servicios_departamento AS csd ON tst.IdTipoServicio = csd.Id 
                                    JOIN t_solicitudes AS ts ON tst.IdSolicitud = ts.Id 
                                    WHERE tst.Atiende = '" . $idTrabajador . "' AND tst.IdEstatus IN(1, 2, 5, 3, 10, 12)
                                    AND tst.IdTipoServicio = 49
                                    ");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function getTodosServiciosCableado() {
        $consulta = array();
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
                                        FROM t_servicios_ticket AS tst 
                                        JOIN cat_v3_estatus AS ce ON tst.IdEstatus = ce.Id 
                                        JOIN cat_v3_servicios_departamento AS csd ON tst.IdTipoServicio = csd.Id 
                                        JOIN t_solicitudes AS ts ON tst.IdSolicitud = ts.Id 
                                        WHERE tst.IdEstatus IN(1, 2, 5, 3, 10, 12)
                                        AND tst.IdTipoServicio = 49");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function getOperacionesPoliza() {
        $consulta = array();
        try {
            $consulta = $this->consulta("SELECT * FROM cat_v3_tipos_operaciones WHERE Flag = 1");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function getInstalaciones(string $idServicio) {
        $consulta = array();
        try {
            $consulta = $this->consulta("SELECT tie.*,
                                        (SELECT Nombre FROM cat_v3_tipos_operaciones WHERE Id = tie.IdOperacion) AS Operacion,
                                        areaAtencion(tie.IdArea)  AS Area,
                                        modelo(tie.IdModelo)  AS Modelo
                                         FROM t_instalaciones_equipos_poliza tie
                                         WHERE IdServicio = '" . $idServicio . "'");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }

    public function setInstalaciones(array $datos) {
        $this->insertarArray('t_instalaciones_equipos_poliza', array(
            'IdServicio' => $datos['id'],
            'IdOperacion' => $datos['idOperacion'],
            'IdArea' => $datos['idArea'],
            'Punto' => $datos['punto'],
            'IdModelo' => $datos['idModelo'],
            'Serie' => $datos['serie'],
            'Archivos' => $datos['archivos']
        ));
    }

    public function deleteInstalacion(string $id) {
        $consulta = array();
        try {
            $consulta = $this->borrar("DELETE FROM t_instalaciones_equipos_poliza
                                         WHERE Id = '" . $id . "'");
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
        return $consulta;
    }



}
