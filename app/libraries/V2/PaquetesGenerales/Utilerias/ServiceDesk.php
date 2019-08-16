<?php

namespace Librerias\V2\PaquetesGenerales\Utilerias;

use Librerias\V2\PaquetesGenerales\Utilerias\Usuario as Usuario;
use Modelos\Modelo_ServiceDeskNew as Modelo;

class ServiceDesk {

    static private $url;
    static private $FIELDS;
    static private $urlUsuarios;
    static private $DBServiceDesk;
    static private $error;

    static private function setVariables() {
        ini_set('max_execution_time', 300);
        self::$url = "http://mesadeayuda.cinemex.net:8080/sdpapi/request";
        self::$urlUsuarios = "http://mesadeayuda.cinemex.net:8080/sdpapi/requester/";
        self::$DBServiceDesk = new Modelo();
    }

    static public function getErrorPHP($errno, $errstr, $errfile, $errline) {
        self::$error = array();

        switch ($errno) {
            case E_WARNING:
                self::$error['tipo'] = 'Warning';
                self::$error['codigo'] = 'ESD001';
                self::$error['error'] = $errstr;
                self::$error['archivo'] = $errfile . ': linea : ' . $errline;
                break;
            case E_NOTICE:
                self::$error['tipo'] = 'Notice';
                self::$error['codigo'] = 'ESD002';
                self::$error['error'] = $errstr;
                self::$error['archivo'] = $errfile . ': linea : ' . $errline;
                break;
        }

        throw new \Exception('Error para ingresar al SD');
    }

    static private function sendSolicitud(string $url) {
        set_error_handler(array('Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk', 'getErrorPHP'), E_WARNING);
        set_error_handler(array('Librerias\V2\PaquetesGenerales\Utilerias\ServiceDesk', 'getErrorPHP'), E_NOTICE);

        $respuesta = json_decode(file_get_contents($url));

        if ($respuesta === NULL) {
            throw new \Exception('Error con la comunicación al Service Desk');
        }

        restore_error_handler();

        return $respuesta;
    }

    static private function validarError(\stdClass $datos) {
        $estatus = null;
        $message = null;

        if (property_exists($datos, 'operation')) {
            $estatus = $datos->operation->result->status;
            $message = $datos->operation->result->message;
        }

        if ($estatus == 'Failed') {
            $mensageError = self::getMensajeError($message);
            throw new \Exception($mensageError);
        }
    }

    static private function getMensajeError(string $error) {
        $textoError = '';
        switch ($error) {
            case 'API key received is not associated to any technician. Authentication failed.':
                $textoError = 'La clave API recibida no está asociada a ningún técnico. Autenticación fallida.';
                break;
            case 'Invalid requestID in given URL':
                $textoError = 'El folio proporcionado no es correcto.';
                break;
            case 'Technician key in the request is invalid. Unable to authenticate.':
                $textoError = 'La clave del técnico en la solicitud no es válida. Imposible de autenticar.';
                break;
            case 'Error when validating URL - Invalid URL for the requested operation.':
                $textoError = 'URL no válida para la operación solicitada.';
                break;
            default :
                $textoError = 'Error con la comunicación al Service Desk.';
                break;
        }
        return $textoError;
    }

    static private function validarAPIKey(string $key) {
        try {
            self::getFoliosTecnico($key);
        } catch (\Exception $ex) {
            $key = self::$DBServiceDesk->getApiKeyDefault();
        }
        return $key;
    }

    static public function getDatos(string $folio) {
        self::setVariables();
        $key = Usuario::getAPIKEY();
        $key = self::validarAPIKey(strval($key));
        self::$FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $key;
        $respuesta = self::sendSolicitud(self::$url . '/' . $folio . '?' . self::$FIELDS);
        self::validarError($respuesta);
        return $respuesta;
    }

    static public function getNotas(string $folio) {
        self::setVariables();
        $key = Usuario::getAPIKEY();
        $key = self::validarAPIKey(strval($key));
        self::$FIELDS = 'format=json&OPERATION_NAME=GET_NOTES&TECHNICIAN_KEY=' . $key;
        $respuesta = self::sendSolicitud(self::$url . '/' . $folio . '/notes/?' . self::$FIELDS);
        self::validarError($respuesta);
        $respuesta = $respuesta->operation->Details;
        return $respuesta;
    }

    static public function getFoliosTecnico(string $key) {
        self::setVariables();
        $input_data = '{"operation":{"details":{ "from": "0","limit": "5000","filterby": "All_Pending_User"}}}';
        self::$FIELDS = 'format=json&OPERATION_NAME=GET_REQUESTS&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $respuesta = self::sendSolicitud(self::$url . '?' . self::$FIELDS);
        self::validarError($respuesta);
        return $respuesta;
    }

    static public function setEstatus(string $estatus, string $folio) {
        $key = Usuario::getAPIKEY();
        $key = self::validarAPIKey(strval($key));
        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '             "status": ' . $estatus
                . '     }'
                . ' }'
                . '}';
        self::$FIELDS = "format=json&"
                . "OPERATION_NAME=EDIT_REQUEST&"
                . "INPUT_DATA=" . urlencode($input_data) . "&"
                . "TECHNICIAN_KEY=" . $key;

        $respuesta = self::sendSolicitud(self::$url . '/' . $folio . '?' . self::$FIELDS);
        self::validarError($respuesta);
    }

    static public function setNota(string $folio, string $mensaje) {
        $key = Usuario::getAPIKEY();
        $key = self::validarAPIKey(strval($key));
        static::$url .= "/" . $folio . "/notes/";
        $input_data = '{operation:{details:{notes:{note:{isPublic:true,notesText:"' . static::remplazarCaracteresEspeciales($mensaje) . '"}}}}}';
        self::$FIELDS = "format=json"
                . "&OPERATION_NAME=ADD_NOTE"
                . "&TECHNICIAN_KEY=" . $key
                . "&INPUT_DATA=" . urlencode($input_data);
        $respuesta = self::sendSolicitud(self::$url . '?' . self::$FIELDS);
        self::validarError($respuesta);
        self::setWorkLog($folio, $mensaje);
    }

    static private function setWorkLog(string $folio, string $mensaje) {
        $mensaje = strip_tags($mensaje);
        $key = Usuario::getAPIKEY();
        $key = self::validarAPIKey(strval($key));
        static::$url .= "/" . $folio . "/worklogs/";
        $input_data = '{operation:{details:{worklogs:{worklog:{description:"' . static::remplazarCaracteresEspeciales($mensaje) . '",workMinutes:1}}}}}';
        self::$FIELDS = "format=json"
                . "&OPERATION_NAME=ADD_WORKLOG"
                . "&TECHNICIAN_KEY=" . $key
                . "&INPUT_DATA=" . urlencode($input_data);
        $respuesta = self::sendSolicitud(self::$url . '?' . self::$FIELDS);
        self::validarError($respuesta);
        return $respuesta;
    }

    static private function remplazarCaracteresEspeciales(string $texto) {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $texto);
    }

}
