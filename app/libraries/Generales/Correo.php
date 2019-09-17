<?php

namespace Librerias\Generales;

use Controladores\Controller_Base_General as General;

class Correo extends General {

    private $DBCO;
    private $DBRU;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        parent::getCI()->load->library('encrypt');
        parent::getCI()->load->library('email');
        parent::getCI()->load->helper(array('date', 'url'));
        $this->DBCO = \Modelos\Modelo_Correo::factory();
        $this->DBRU = \Modelos\Modelo_Registro_Usuario::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
    }

    /*
     * Se encarga de validar si el correo es valido y le envia una liga de acceso
     * al correo del usuario para generar un nuevo password.
     * 
     * @param string $Email recibe el correo enviado.
     * @return boolean regresa true si envio con exito el correo de lo contrario false.
     */

    public function validarCorreo(string $Email) {
        if (filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $usuario = $this->catalogo->catUsuarios('4', array('email' => $Email));
            if (empty($usuario)) {
                $resultado = FALSE;
            } else {
                $encriptado = parent::getCI()->encrypt->encode($usuario['Usuario']);
                $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
                $datos = array('IdUsuario' => $usuario['Id'], 'Fecha' => $fecha, 'Codigo' => $encriptado, 'Flag' => 1);
                $resultado = $this->DBCO->insertarRecuperacionPassword($datos);
                $id = urlencode($encriptado);
                $urlBase = 'http://' . $_SERVER['HTTP_HOST'];
                $url = '<a style="color: #808080;" href="' . $urlBase . '/Nuevo_Password/?id=' . $id . '">Restaurar Contraseña</a>';
                if (!empty($resultado)) {
                    $resultado = TRUE;
                }
                $titulo = 'Restaure su contraseña en ADIST';
                $texto = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong><br /><br />Para restaurar la contrase&ntilde;a, haga clic en este v&iacute;nculo.<br /><br /> <span style="color: #808080;"><strong>' . $url . '</strong></span></p>';
                $mensaje = $this->mensajeCorreo($titulo, $texto);
                if (empty($usuario['EmailCorporativo'])) {
                    $this->enviarCorreo('notificaciones@siccob.solutions', array($usuario['Email']), 'Recuperacion de contraseña en ADIST', $mensaje);
                } else {
                    $this->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), 'Recuperacion de contraseña en ADIST', $mensaje);
                }
            }
        } else {
            $resultado = FALSE;
        }
        return $resultado;
    }

    /*
     * Envia el correo con formato html
     * 
     * @param string $remitente recibe de quien es el que envia el correo
     * @param array $destinatario recibe a que correos se va a mandar el mensaje
     * @param string $asunto recibe el asunto del correo.
     * @param string $mensaje recibe el mensaje que lleva el correo
     */

    public function enviarCorreo(string $remitente, array $destinatario, string $asunto, string $mensaje, array $archivoAdjunto = [], string $style = '') {
        $host = $_SERVER['SERVER_NAME'];
        $destinatarios = implode(',', $destinatario);

        if ($host !== 'siccobsolutions.com.mx' || $host !== 'siccobsolutions.com.mx') {
            if ($style == '') {
                $style = ''
                        . '<style>
                        .table-striped>tbody>tr:nth-of-type(odd) {
                            background-color: #f9f9f9;
}                       }
                    </style>';
            }
            
            $contenido = '  <html>
                            <head>
                                <title>' . $asunto . '</title>
                                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                                ' . $style . '
                            </head>
                            <body>
                            ' . $mensaje . '
                            </body>
                        </html>';
            $config['mailtype'] = 'html';
            
            parent::getCI()->email->initialize($config);
            parent::getCI()->email->clear(TRUE);
            parent::getCI()->email->from($remitente);
            parent::getCI()->email->to($destinatarios);
            parent::getCI()->email->subject($asunto);
            parent::getCI()->email->message($contenido);

            if (!empty($archivoAdjunto)) {
                foreach ($archivoAdjunto as $key => $value) {
                    parent::getCI()->email->attach($value);
                }
            }

            if (!parent::getCI()->email->send()) {
                $this->crearReporteFallasEnvio(parent::getCI()->email->print_debugger());
            }

            return parent::getCI()->email->send();
        }
    }

    private function crearReporteFallasEnvio(string $contenido) {
        if (file_exists("./storage/Archivos/ReportesTXT/ReporteFallasEnvio.txt")) {
            $archivo = fopen("./storage/Archivos/ReportesTXT/ReporteFallasEnvio.txt", "a");
            fputs($archivo, chr(13) . chr(10));
            fwrite($archivo, PHP_EOL . "$contenido");
            fclose($archivo);
        } else {
            $archivo = fopen("./storage/Archivos/ReportesTXT/ReporteFallasEnvio.txt", "w");
            fwrite($archivo, PHP_EOL . "$contenido");
            fclose($archivo);
        }
    }

    /*
     * Se encarga de validar la liga para generar un nuevo password
     * Solicita a la base de datos los datos de la clave y compara las hora 
     * para ver si la liga todavia esta vigente. Si la liga ya esta vencida la 
     * actualiza para no volve a utilizarla. Regresa a la pagina que se va
     * ha direccionar.
     * 
     * @param string $clave recive la clave que fue enviada por la url
     * @return array regresa los datos de pagina a mostrar 
     */

    public function validarLiga($clave) {
        $datos = array();
        $clave = $this->optenerClave(urldecode($clave));
        $usuario = parent::getCI()->encrypt->decode($clave);
        $datosClave = $this->DBRU->getRecuperarPassword(array('Codigo' => $clave));
        $fechaLiga = new \DateTime($datosClave['Fecha']);
        $fechaActual = new \DateTime(mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')));
        $diferencia = $fechaActual->diff($fechaLiga);
        if ($diferencia->format('%h') >= '1') {
            $actualizar = $this->DBRU->actualizarRecuperarPassword(array('Id' => $datosClave['Id']));
            $datos = array(
                'pagina' => 'error_general',
                'clave' => '600',
                'titulo' => 'Liga vencida',
                'descripcion' => 'Esta liga ha expirado es necesario que vuelva a solicitar su recuperación.');
        } else if ($datosClave['Flag'] === '0') {
            $datos = array(
                'pagina' => 'error_general',
                'clave' => '600',
                'titulo' => 'Liga vencida',
                'descripcion' => 'Esta liga ha expirado es necesario que vuelva a solicitar su recuperación.');
        } else {
            $datos = array('pagina' => 'Nuevo_Password', 'Usuario' => $usuario, 'IdPsw' => $datosClave['Id']);
        }
        return $datos;
    }

    /*
     * Se crea el cuerpo del mensaje en html para el mensaje de correo
     */

    public function mensajeCorreo(string $titulo, string $texto) {
        $mensaje = '<div align="center">
                        <table style="max-width: 600px; height: 240px;" border="0" width="598" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <table style="min-width: 332px; max-width: 600px; border: 1px solid #E0E0E0; border-bottom: 0; border-top-left-radius: 3px; border-top-right-radius: 3px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#707478">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="72px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="32px">&nbsp;</td>
                                                    <td style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 24px; color: #ffffff; line-height: 1.25;">' . $titulo . '</td>
                                                    <td width="32px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                     <td colspan="3" height="18px">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="min-width: 332px; max-width: 600px; border: 1px solid #F0F0F0; border-top: 0;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="18px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="32px">&nbsp;</td>
                                                    <td style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 13px; color: #202020; line-height: 1.5;">' . $texto . '</td>
                                                    <td width="10px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="18px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="32px">&nbsp;</td>
                                                    <td style="font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 13px; color: #202020; line-height: 1.5;">
                                                        <p>&nbsp;<strong><em>Atentamente,</em></strong><br>
                                                        <strong><em>Notificaciones AdIST</em></strong></p>
                                                    </td>
                                                    <td width="10px">&nbsp;</td>
                                                </tr>
                                                  <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="max-width: 600px; font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 10px; color: #bcbcbc; line-height: 1.5;">&nbsp;
                                        <p style="text-align: center;" align="center"><em><span style="font-size: 10.5pt; color: #999999;"><em>Gracias por no responder este correo.<br /> Es solo un robot para mandar e-mails.<br /> Dudas o aclaraciones dirigirse al &aacute;rea correspondiente de la empresa. </em></span></em></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>';
        return $mensaje;
    }

    public function optenerClave(string $clave) {
        if ($posicion = strpos($clave, ' ')) {
            $fragmentoClave = substr($clave, 0, $posicion);
            $clave = $this->DBCO->consulta('select Codigo from t_recuperacion_password where Codigo like "' . $fragmentoClave . '%"');
            if (!empty($clave)) {
                return $clave[0]['Codigo'];
            } else {
                $urlBase = 'http://' . $_SERVER['HTTP_HOST'];
                redirect($urlBase . '/Nuevo_Password/Error_Clave');
            }
        } else {
            return $clave;
        }
    }

}
