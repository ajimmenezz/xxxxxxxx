<?php

namespace Modelos;

use Librerias\V2\PaquetesGenerales\Interfaces\Modelo_Base as Base;

class Modelo_Catalogos_Permisos extends Base {

    public function __construct() {
        parent::__construct();
    }

    public function getRegistros(string $tabla) {
        try {
            $consulta = $this->consulta('select
                                       *
                                    from ' . $tabla . '');
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
        $this->actualizar('UPDATE '.$tabla.
                            ' SET Nombre = '.$datos['nombre'].
                            ' Observaciones = '.$datos['observaciones'].
                            ' Flag = '.$datos['flag']. 
                            ' where Id = '.$datos['id']);
    }

}
