<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Catalogos_Permisos extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getRegistros(string $tabla) {
        try {
            if ($tabla === "cat_v3_motivos_ausencia_personal") {
                $consulta = $this->consulta('SELECT 
                                                cmap.*, 
                                                (SELECT 
                                                    cp.Nombre 
                                                FROM cat_perfiles AS cp 
                                                WHERE cp.Id = cmap.NivelCancelacion) AS NombrePerfil 
                                            FROM ' . $tabla . ' AS cmap');
            } else {
                $consulta = $this->consulta('select
                                       *
                                    from ' . $tabla . '');
            }
            return $consulta;
        } catch (\Exception $ex) {
            throw new \Exception('Error con la base de datos : ' . $ex->getMessage());
        }
    }

    public function setRegistro(string $tabla, array $datos) {
        $this->insertar('INSERT INTO '
                . $tabla . ' (Nombre,Observaciones,Flag)
                        VALUES ("' . $datos['nombre'] . '","' . $datos['observaciones'] . '",1)');
    }

    public function actualizarRegistro(string $tabla, array $datos) {
        if ($tabla === "cat_v3_motivos_ausencia_personal") {
            $this->actualizar('UPDATE ' . $tabla .
                    ' SET Nombre = "' . $datos['nombre'] . '", 
                            Observaciones = "' . $datos['observaciones'] . '", 
                            Flag = "' . $datos['flag'] . '", 
                            Cancelacion = "' . $datos['cancelacion'] . '", 
                            NivelCancelacion = ' . $datos['nivelCancelacion'] . ',
                            Archivo = ' . $datos['archivo'] . '
                            where Id = "' . $datos['id'] . '"');
        } else {
            $this->actualizar('UPDATE ' . $tabla .
                    ' SET Nombre = "' . $datos['nombre'] . '", 
                            Observaciones = "' . $datos['observaciones'] . '", 
                            Flag = "' . $datos['flag'] . '" 
                            where Id = "' . $datos['id'] . '"');
        }
    }

}
