<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Aws\S3\S3Client;

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
    function setMultiplesArchivos(&$CI, string $name, string $carpeta, string $bucket = 'storagesolutions') {
        $host = $_SERVER['SERVER_NAME'];
        $CI->load->helper(array('conversionpalabra'));
        $archivos = array();
        $posicionExtencion = null;
        if ($bucket === 'storagesolutions') {
            $carpeta = './storage/Archivos/' . $carpeta;
        }

        $files = $_FILES;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'jpg|bmp|jpeg|gif|png|doc|docx|xls|xlsx|pdf|xml|csv';
        $CI->load->library('upload');
        $CI->load->library('image_lib');
        $S3 = new S3Client([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => [
                'key' => 'AKIAJS7DH4TPDSKDHXSA',
                'secret' => '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'
            ]
        ]);
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
//                    $error = array('error' => $CI->upload->display_errors());
//                    var_dump($error);
                    return FALSE;
                } else {
                    array_push($archivos, substr($carpeta, 1) . utf8_encode($CI->upload->data('file_name')));
                    $_image = end($archivos);
                    $image_data = getimagesize("." . $_image);
                    if ($image_data[0] > 640 || $image_data[1] > 640) {
                        $_ancho = ($image_data[0] > $image_data[1]) ? 640 : ((640 * (int) $image_data[0]) / (int) $image_data[1]);
                        $_alto = ($image_data[1] > $image_data[0]) ? 640 : ((640 * (int) $image_data[1]) / (int) $image_data[0]);

                        $_config['image_library'] = 'gd2';
                        $_config['source_image'] = "." . $_image;
                        $_config['create_thumb'] = FALSE;
                        $_config['maintain_ratio'] = TRUE;
                        $_config['width'] = $_ancho;
                        $_config['height'] = $_alto;

                        $CI->image_lib->clear();
                        $CI->image_lib->initialize($_config);
                        if (!$CI->image_lib->resize()) {
                            array_pop($archivos);
                            unlink("." . $_image);
                            return false;
                        } else {
                            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                                try {
                                    $src = '.' . $_image;
                                    if ($bucket !== 'storagesolutions') {
                                        $_image = str_replace('/storage', '', $_image);
                                    }

                                    $respuesta = $S3->putObject(array(
                                        'Bucket' => $bucket,
                                        'Key' => substr($_image, 1),
                                        'ACL' => 'public-read',
                                        'SourceFile' => $src
                                    ));
                                    $url = $respuesta->get('ObjectURL');
//                                    array_pop($archivos);
//                                    array_push($archivos, $url);
                                } catch (Aws\S3\Exception\S3Exception $e) {
                                    array_pop($archivos);
                                    unlink("." . $_image);
                                    return false;
                                }
                            }
                        }
                    } else {
                        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                            try {
                                $src = '.' . $_image;
                                if ($bucket !== 'storagesolutions') {
                                    $_image = str_replace('/storage', '', $_image);
                                }
                                $respuesta = $S3->putObject(array(
                                    'Bucket' => $bucket,
                                    'Key' => substr($_image, 1),
                                    'ACL' => 'public-read',
                                    'SourceFile' => $src
                                ));
                                $url = $respuesta->get('ObjectURL');
//                                array_pop($archivos);
//                                array_push($archivos, $url);
                            } catch (Aws\S3\Exception\S3Exception $e) {
                                array_pop($archivos);
                                unlink("." . $_image);
                                return false;
                            }
                        }
                    }
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

