<?php

namespace Librerias\Interfaces;

use Librerias\Componentes\Coleccion as Coleccion;
use \Librerias\Modelos\Modelo_Base as Modelo;

abstract class Objeto_General {

    protected $db_adist3;

    public function __construct(Modelo $modelo) {
        $this->db_adist3 = $modelo;
    }

    abstract function generarElementos();

    protected function subirArchivos(array $datos = array(), string $inputName, string $carpeta) {
        $datos['CI']->load->helper(array('FileUpload'));
        $archivosSubidos = setMultiplesArchivos($datos['CI'], $inputName, $carpeta);

        if ($archivosSubidos !== FALSE) {
            $archivos = implode(',', $archivosSubidos);
        } else {
            throw new \Exception("No es posible subir el archivo al servidor");
        }

        return $archivos;
    }

    protected function calcularPorcentaje(string $total, string $avance) {
        $porcentaje = ($total !== '0')? round(($avance * 100 ) / $total, 1): 0;
        return $porcentaje . '%';
    }

}
