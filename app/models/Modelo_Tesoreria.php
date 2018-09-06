<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Tesoreria extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function guardarViaticosOutsourcing(array $datos) {
        foreach ($datos['viaticos'] as $value) {
            $viaticos = explode("_", $value);
            $consulta = $this->consulta('SELECT * FROM cat_v3_viaticos_outsourcing WHERE IdTecnico = "' . $datos['tecnico'] . '" AND IdSucursal = "' . $viaticos[0] . '"');

            if (!empty($consulta)) {
                $this->actualizar('cat_v3_viaticos_outsourcing', [
                    'IdTecnico' => $datos['tecnico'],
                    'IdSucursal' => $viaticos[0],
                    'Monto' => $viaticos[1]], ['Id' => $consulta[0]['Id']]);
            } else {
                $this->insertar('cat_v3_viaticos_outsourcing', array('IdTecnico' => $datos['tecnico'],
                    'IdSucursal' => $viaticos[0],
                    'Monto' => $viaticos[1]));
            }
        }

        return TRUE;
    }

    public function guardarMontosOutsourcing(array $datos) {
        foreach ($datos as $key => $value) {
            $consulta = $this->consulta('SELECT * FROM t_montos_x_vuelta_outsourcing WHERE Concepto = "' . $key . '"');

            if (!empty($consulta)) {
                $this->actualizar('t_montos_x_vuelta_outsourcing', [
                    'Concepto' => $key,
                    'Monto' => $value], ['Id' => $consulta[0]['Id']]);
            } else {
                $this->insertar('t_montos_x_vuelta_outsourcing', array(
                    'Concepto' => $key,
                    'Monto' => $value));
            }
        }

        return TRUE;
    }

}
