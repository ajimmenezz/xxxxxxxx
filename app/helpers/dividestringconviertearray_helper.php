<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('divideString')) {

    function divideString(string $string, int $salto) {
        $array1 = explode(",", $string);
        $arraySalida = [];
        $stringAuxiliar = '';
        $cont = 0;

        foreach ($array1 as $k => $v) {
            $cont++;
            if ($cont <= $salto) {
                $stringAuxiliar .= $v . ',';
                if ($cont == $salto) {
                    $stringAuxiliar = substr($stringAuxiliar, 0, -1);
                    array_push($arraySalida, explode(",", $stringAuxiliar));
                    $cont = 0;
                    $stringAuxiliar = "";
                }
            }
        }

        return $arraySalida;
    }

}