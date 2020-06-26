<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Correo extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Se encarga de insertar un nuevo registro para la recuperacion de password.
     * Si ya existe otros registros pone sus flag a cero.
     */

    public function insertarRecuperacionPassword(array $data) {
        $consulta = $this->encontrar('t_recuperacion_password', array('IdUsuario' => $data['IdUsuario'], 'Flag' => '1'));
        if (empty($consulta)) {
            //No tiene registro
            $fila = $this->insertar('t_recuperacion_password', $data);
        } else {
            //Existe el registro actualiza los demas a Cero y crea uno nuevo
            $respuesta = $this->actualizar('t_recuperacion_password', array('Flag' => 0), array('IdUsuario' => $data['IdUsuario']));
            if (!empty($respuesta)) {
                $fila = $this->insertar('t_recuperacion_password', $data);
            } else {
                $fila = 'No se puede actualizar los registros encontrados';
            }
        }
        return $fila;
    }

    public function obtenerCorreoFoto() {
        $consulta = $this->consulta("select Id, EmailCorporativo from cat_v3_usuarios where EmailCorporativo <> '';");
        if (!empty($consulta)) {
            $arrayFotos = [];
            foreach ($consulta as $key => $value) {                
                if (file_exists("storage/Archivos/fotoPersonal/" . $value['Id'] . ".png")) {
                    $arrayFotos[$value['EmailCorporativo']] = "/storage/Archivos/fotoPersonal/" . $value['Id'] . ".png";
                }
            }
            return $arrayFotos;
        }
        return [];
    }

}
