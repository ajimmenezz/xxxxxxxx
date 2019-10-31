<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_GestorDashboard extends Base {

    public function getVistasDashboards(string $claves) {
        $consulta = $this->consulta("SELECT 
                                        VistaHtml
                                    FROM
                                        t_permisos_dashboard
                                    WHERE ClavePermiso IN ('" . $claves . "')");
        return $consulta;
    }

    public function getClavesPermisos(string $permisos) {
        $consulta = $this->consulta('SELECT Permiso FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }
    
    public function getIdPermisos(string $permisos) {
        $consulta = $this->consulta('SELECT Id FROM cat_v3_permisos WHERE Id IN(' . $permisos . ')');
        return $consulta;
    }
    
    public function getPermisosDashboard(string $permisos) {
        $consulta = $this->consulta('SELECT 
                                        tpd.ClavePermiso
                                    FROM
                                        t_permisos_dashboard tpd
                                    INNER JOIN cat_v3_permisos cvp
                                    ON cvp.Permiso = tpd.ClavePermiso 
                                    WHERE tpd.Id IN(' . $permisos . ')');
        return $consulta;
    }
    
    public function getDatosVGC(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDatosVGT(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDatosVGHI(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDatosVGIP(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDatosVGZ(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDatosVGTO(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }

}
