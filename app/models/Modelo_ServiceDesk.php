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

    public function saveLogUpgradeSD(array $dataToInsert) {
        $answerQuery = $this->insertar('t_log_actualizacion_sd', $dataToInsert);
        return $answerQuery;
    }

    public function getApiKeyByUser($idUser = '') {
        if ($idUser == '') {
            $consulta = parent::connectDBPrueba()->query('select SDKey from cat_v3_usuarios where Id = 2;');
        } else {
            $consulta = parent::connectDBPrueba()->query('select SDKey from cat_v3_usuarios where Id = "' . $idUser . '";');
        }
        if (!empty($consulta)) {
            $value = $consulta->result_array();
            if ($value[0]['SDKey'] === '' || $value[0]['SDKey'] === NULL) {
                $consulta2 = parent::connectDBPrueba()->query('select SDKey from cat_v3_usuarios where Id = 2;');
                if (!empty($consulta2)) {
                    $value2 = $consulta2->result_array();
                    return $value2[0]['SDKey'];
                } else {
                    return FALSE;
                }
            } else {
                return $value[0]['SDKey'];
            }
        } else {
            return false;
        }
    }

    public function apiKeyUsuario(string $idUsuario) {
        $datosUsuario = parent::connectDBPrueba()->query('select SDKey from cat_v3_usuarios where Id = "' . $idUsuario . '";');
        $keyUsuario = $datosUsuario->result_array();
        return $keyUsuario[0]['SDKey'];
    }

    public function apiKeyJefe(string $idUsuario) {
        $datosJefe = parent::connectDBPrueba()->query('SELECT 
                                                        (SELECT SDKey FROM cat_v3_usuarios WHERE Id = cvu.IdJefe) AS KeyJefe
                                                    FROM
                                                        cat_v3_usuarios cvu
                                                    WHERE
                                                        cvu.Id = "' . $idUsuario . '"');

        $keyJefe = $datosJefe->result_array();
        return $keyJefe[0]['KeyJefe'];
    }

}
