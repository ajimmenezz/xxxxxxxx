<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/**
 * Description of Modelo_ServiceDesk
 *
 * @author Alberto Barcenas
 */
class Modelo_ServiceDesk extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    public function guardarLogSD(array $datos) {
        $consulta = $this->encontrar('t_log_sd_cierres', array('Folio' => $datos['Folio']));

        if (empty($consulta)) {
            $consultaLog = $this->insertar('t_log_sd_cierres', $datos);
        } else {
            $contador = $consulta[0]['Contador'] + 1;

            $datosLogSD = array(
                'MensajeSD' => $datos['MensajeSD'],
                'Fecha' => $datos['Fecha'],
                'Contador' => $contador
            );

            $consultaLog = $this->actualizar('t_log_sd_cierres', $datosLogSD, array('Folio' => $datos['Folio']));
        }
    }

    public function consultarFlagLogSDCierres() {
        $consulta = $this->consulta('SELECT Folio FROM t_log_sd_cierres WHERE Flag = 0');
        return $consulta;
    }

    public function actualizarFlagLogSDCierres(string $folio) {
        $consulta = $this->actualizar('t_log_sd_cierres', array('Flag' => '1'), array('Folio' => $folio));
        return $consulta;
    }

}
