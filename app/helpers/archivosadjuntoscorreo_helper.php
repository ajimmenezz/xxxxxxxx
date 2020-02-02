<?php

defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('archivosAdjuntosCorreo')) {

    function archivosAdjuntosCorreo(array $datos) {
        try {
            if (!function_exists('imap_open')) {
                throw new \Exception("IMAP no está configurado.");
                exit();
            } else {
                /* Conectando el servidor de Gmail con IMAP */
                $conexion = imap_open('{imap.gmail.com:993/imap/ssl}INBOX', $datos['correo'], $datos['password']) or die('Cannot connect to Gmail: ' . imap_last_error());

                /* Buscar correos electrónicos que tengan la palabra clave especificada en el asunto del correo electrónico */
                $datosCorreo = imap_search($conexion, 'SUBJECT "' . $datos['asunto'] . '"');
                if (!empty($datosCorreo)) {
                    foreach ($datosCorreo as $correoIdentidad) {
                        $visionGeneral = imap_fetch_overview($conexion, $correoIdentidad, 0);
                        $estructura = imap_fetchstructure($conexion, $correoIdentidad);
                        $archivosAdjuntos = '';
                        $att = count($estructura->parts);
                        if ($att >= 2) {
                            for ($a = 0; $a < $att; $a++) {
                                if (isset($estructura->parts[$a]->disposition)) {
                                    if ($estructura->parts[$a]->disposition == 'ATTACHMENT') {
                                        $archivo = imap_base64(imap_fetchbody($conexion, $correoIdentidad, $a + 1));
                                        foreach ($estructura->parts[$a]->dparameters as $key => $value) {
                                            $extencion = strpos(strtolower($value->value), '.xls');
                                            if ($extencion !== FALSE) {
                                                $archivosAdjuntos .= $value->value;
                                            }
                                        }
                                        $direccion = '/storage/Archivos/Reportes/' . $archivosAdjuntos;
                                        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccion, $archivo);
                                    }
                                }
                            }
                        }
                    }
                }
                imap_close($conexion);
                
                return array('code' => 200, 'message' => $direccion);
            }
        } catch (\Exception $ex) {
            return array('code' => 400, 'message' => $ex->getMessage());
        }
    }

    
}