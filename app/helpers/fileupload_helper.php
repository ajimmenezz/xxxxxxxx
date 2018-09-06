<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('setMultiplesArchivos')) {

    /**
     *
     * Funcion que se encarga de guardar los archivos en el sistema.
     *
     * @param	string
     * @param	array
     * @param	mixed
     * @return	mixed	depends on what the array contains
     */
    function setMultiplesArchivos(&$CI, string $name, string $carpeta) {
        $CI->load->helper(array('conversionpalabra'));
        $archivos = array();
        $posicionExtencion = null;
        $carpeta = './storage/Archivos/' . $carpeta;

        $files = $_FILES;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'jpg|bmp|jpeg|gif|png|doc|docx|xls|xlsx|pdf|xml';
        $CI->load->library('upload');
        if (!empty($files)) {
            for ($i = 0; $i < count($files[$name]['name']); $i++) {
                $posicionExtencion = strrpos($files[$name]['name'][$i], '.');
                $extencion = substr($files[$name]['name'][$i], $posicionExtencion);
                $files[$name]['name'][$i] = stripAccents($files[$name]['name'][$i]);
                $files[$name]['name'][$i] = str_replace($extencion, strtolower($extencion), utf8_decode($files[$name]['name'][$i]));

                $_FILES[$name]['name'] = $files[$name]['name'][$i];
                $_FILES[$name]['type'] = $files[$name]['type'][$i];
                $_FILES[$name]['tmp_name'] = $files[$name]['tmp_name'][$i];
                $_FILES[$name]['error'] = $files[$name]['error'][$i];
                $_FILES[$name]['size'] = $files[$name]['size'][$i];
                $CI->upload->initialize($config);
                if (!$CI->upload->do_upload($name)) {
                    $archivos = FALSE;
                } else {
                    array_push($archivos, substr($carpeta, 1) . utf8_encode($CI->upload->data('file_name')));
                }
            }
            return $archivos;
        } else {
            return FALSE;
        }
    }

}

if (!function_exists('eliminarArchivo')) {

    /*
     * Funcion que se encarga de eliminar un archivo del sistema
     * 
     */

    function eliminarArchivo(string $carpeta) {
        return unlink('.' . $carpeta);
    }

}

