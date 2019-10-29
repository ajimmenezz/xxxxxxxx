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
                                        cvp.Id
                                    FROM
                                        t_permisos_dashboard tpd
                                    INNER JOIN cat_v3_permisos cvp
                                    ON cvp.Permiso = tpd.ClavePermiso 
                                    WHERE cvp.Id IN(' . $permisos . ')');
        return $consulta;
    }
    
    public function getDato327(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDato328(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDato329(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDato330(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDato331(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }
    
    public function getDato332(array $datos) {
        $consulta = $this->consulta('SELECT * FROM t_permisos_dashboard');
        return $consulta;
    }

}
