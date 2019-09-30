<?php

namespace Librerias\WebServices;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of ServiceDesck
 *
 * @author Freddy
 */
class ServiceDesk extends General {

    private $Url;
    private $FIELDS;
    private $UrlUsers;
    private $modeloServiceDesck;
    private $error;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->Url = "http://mesadeayuda.cinemex.net:8080/sdpapi/request";
        $this->UrlUsers = "http://mesadeayuda.cinemex.net:8080/sdpapi/requester/";
        $this->modeloServiceDesck = \Modelos\Modelo_ServiceDesk::factory();
    }

    private function getErrorPHP($errno, $errstr, $errfile, $errline) {
        $this->error = array();

        switch ($errno) {
            case E_WARNING:
                $this->error['tipo'] = 'Warning';
                $this->error['codigo'] = 'ESD001';
                $this->error['error'] = $errstr;
                $this->error['archivo'] = $errfile . ': linea : ' . $errline;
                break;
            case E_NOTICE:
                $this->error['tipo'] = 'Notice';
                $this->error['codigo'] = 'ESD002';
                $this->error['error'] = $errstr;
                $this->error['archivo'] = $errfile . ': linea : ' . $errline;
                break;
        }

        throw new \Exception('Error para ingresar al SD');
    }

    private function getDatosSD(string $url) {
        set_error_handler(array($this, 'getErrorPHP'), E_WARNING);
        set_error_handler(array($this, 'getErrorPHP'), E_NOTICE);
        $datosSD = json_decode(file_get_contents($url));

        restore_error_handler();

        return $datosSD;
    }

    private function validarError(\stdClass $datos) {
        $estatus = null;
        $message = null;

        if (property_exists($datos, 'operation')) {
            $estatus = $datos->operation->result->status;
            $message = $datos->operation->result->message;
        }

        if ($estatus == 'Failed') {
            $this->error['algo'] = $message;
            $mensageError = $this->textoError($message);
            throw new \Exception($mensageError);
        }
    }

    private function textoError(string $error) {
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
                $textoError = $error;
                break;
        }

        if (strpos($error, 'Error when adding note to request') !== FALSE) {
            if (strpos($error, 'User does not have enough permission to add note') !== FALSE) {
                $textoError = 'El usuario no tiene permiso suficiente para agregar una nota al ServiceDesk.';
            }else{
                $textoError = 'No cuenta con información para subirlo al ServiceDesk.';
            }
        }

        return $textoError;
    }

    public function validarAPIKey(string $key) {
        try {
            $this->getFoliosTecnico($key);
        } catch (\Exception $ex) {
            $key = '';
//            Se comenta el codigo para que se planche la información solo la persona que haga los cambios.
//            $key = $this->modeloServiceDesck->getApiKeyByUser('2');
        }
        return $key;
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    public function getFoliosTecnico(string $key) {
        $input_data = '{"operation":{"details":{ "from": "0","limit": "5000","filterby": "All_Pending_User"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUESTS&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargaado de obtener los detalles del folio
     * 
     */

    public function getDetallesFolio(string $key, string $folio) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '/' . $folio . '?' . $this->FIELDS);

        if ($datosSD !== NULL) {
            $this->validarError($datosSD);
        } else {
            $datosSD = 'Sin respuesta con ServiceDesk';
        }
        return $datosSD;
    }

    /*
     * Encargaado de obtener la resolucion del folio
     * 
     */

    public function getResolucionFolio(string $key, string $folio) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_RESOLUTION&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '/' . $folio . '?' . $this->FIELDS);

        if ($datosSD !== NULL) {
            $this->validarError($datosSD);
        } else {
            $datosSD = 'Sin respuesta con ServiceDesk';
        }
        return $datosSD;
    }

    /*
     * Encargado de obtener la lista de los tecnicos de Service Desk
     * 
     */

    public function getTecnicosSD(string $key) {
        $Url2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/technician";
        $input_data = '{"operation":{"details":{ "parameter": { "name":"department", "value" : ""}}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_ALL&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($Url2 . '/?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargado de reasingar el folio en SD
     * 
     */

    public function reasignarFolioSD(string $folio, string $tecnico, string $key) {
        $input_data = '{"operation":{"details":{"technicianid":"' . $tecnico . '"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=ASSIGN_REQUEST&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '/' . $folio . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargado de agregar resolicion del folio en SD
     * 
     */

    public function resolucionFolioSD(string $folio, string $tecnico, string $key, string $descripcion) {
        $input_data = '{"operation":{"details":{"resolution":{"resolutiontext":"' . $descripcion . '"}}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=EDIT_RESOLUTION&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '/' . $folio . '/resolution?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargado de obtener todas los folios de los tecnicos que estan registrados en Service Desk
     * 
     */

    public function getFolios(string $key) {
        $input_data = '{"operation":{"details":{ "from": "0","limit": "5000","filterby": "All_Pending"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST_FILTERS&TECHNICIAN_KEY=' . $key;
        $datos = file_get_contents($this->Url . '?' . $this->FIELDS);
        $filtros = json_decode($datos);

        foreach ($filtros->operation->Details as $value) {
            if ($value->VIEWNAME === 'Todos Ingenieros') {
                $IdFiltro = $value->VIEWID;
                break;
            }
        }

        $input_data = '{"operation":{"details":{ "from": "0","limit": "5000","filterby": "' . $IdFiltro . '"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUESTS&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargado de obtener e insertar una resolicion en Service Desk
     * 
     */

    public function setResolucionServiceDesk(string $key, string $folio, string $datos) {
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio . "/resolution/";
        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '         "resolution": {'
                . '             "resolutiontext": "' . $this->mres($datos) . '"'
                . '         }'
                . '     }'
                . ' }'
                . '}';
        $FIELDS = "format=json&"
                . "OPERATION_NAME=EDIT_RESOLUTION&"
                . "INPUT_DATA=" . urlencode($input_data) . "&"
                . "TECHNICIAN_KEY=" . $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $FIELDS);
        $return = curl_exec($ch);
        curl_close($ch);
        $jsonDecode = json_decode($return);
        $this->generateLogResolverSD(array($jsonDecode, $folio));

        return $jsonDecode;
    }

    public function setNoteServiceDesk(string $key, string $folio, string $datos) {
        $html = str_replace('&nbsp', '', $datos);
        $html = str_replace('style="color:#FF0000";', '', $datos);
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio . "/notes/";
        $input_data = '{operation:{details:{notes:{note:{isPublic:true,notesText:"' . $this->mres($html) . '"}}}}}';
        $FIELDS = "format=json"
                . "&OPERATION_NAME=ADD_NOTE"
                . "&TECHNICIAN_KEY=" . $key
                . "&INPUT_DATA=" . urlencode($input_data);
        $datosSD = $this->getDatosSD($URL2 . '?' . $FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    public function setWorkLogServiceDesk(string $key, string $folio, string $datos) {
        $html = strip_tags($datos);
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio . "/worklogs/";
        $input_data = '{operation:{details:{worklogs:{worklog:{description:"' . $this->mres($html) . '",workMinutes:1}}}}}';
        $FIELDS = "format=json"
                . "&OPERATION_NAME=ADD_WORKLOG"
                . "&TECHNICIAN_KEY=" . $key
                . "&INPUT_DATA=" . urlencode($input_data);
        $datosSD = $this->getDatosSD($URL2 . '?' . $FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    private function generateLogResolverSD(array $dataOperationSD) {
        if ($dataOperationSD[0]->operation->result->status !== 'Success') {
            $user = $this->Usuario->getDatosUsuario();
            $date = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $dataToInsert = array(
                'IdUsuario' => $user['Id'],
                'Fecha' => $date,
                'Codigo' => $dataOperationSD[0]->operation->result->status,
                'Mensaje' => $dataOperationSD[0]->operation->result->message,
                'Folio' => $dataOperationSD[1]);
            $this->modeloServiceDesck->saveLogUpgradeSD($dataToInsert);
        }
    }

    private function mres($value) {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    /*
     * Encargado de obtener el nombre del Usuario
     * 
     */

    public function nombreUsuarioServiceDesk(string $key, string $idUsuario) {
        $url2 = 'http://mesadeayuda.cinemex.net:8080/sdpapi/requester/?format=json&OPERATION_NAME=GET_ALL&INPUT_DATA={%22operation%22:{%22details%22:{%22department%22:%22Soporte%20TI%22}}}&TECHNICIAN_KEY=' . $key;
        $stringUsuarios = file_get_contents($url2);
        $objetoUsuarios = json_decode($stringUsuarios);
        $this->validarError($objetoUsuarios);
        $nombreUsuario = '';

        foreach ($objetoUsuarios->operation->details as $value) {
            if ($idUsuario === (string) $value->userid) {
                $nombreUsuario = $value->username;
            }
        }

        return $nombreUsuario;
    }

    /*
     * Encargado de cambiar el estatus de la solicitud en Service Desk
     * 
     */

    public function cambiarEstatusServiceDesk(string $key, string $estatus, string $folio) {
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio;
        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '             "status": ' . $this->mres($estatus)
                . '     }'
                . ' }'
                . '}';
        $FIELDS = "format=json&"
                . "OPERATION_NAME=EDIT_REQUEST&"
                . "INPUT_DATA=" . urlencode($input_data) . "&"
                . "TECHNICIAN_KEY=" . $key;

        $datosSD = $this->getDatosSD($URL2 . '?' . $FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    /*
     * Encargado de consultar el depertamento de IT en SD
     * 
     */

    public function consultarDepartamentoTI(string $key) {
        $input_data = '{"operation":{"details":{"department":""}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_ALL&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->UrlUsers . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        $returnArray = [];
        $i = 0;

        foreach ($datosSD->operation->details as $key => $value) {
            $returnArray[$i]['userId'] = $value->userid;
            $returnArray[$i]['userName'] = $value->username;
            $returnArray[$i]['userEmail'] = $value->emailid;
            $i++;
        }

        return $returnArray;
    }

    public function getViewId(string $viewname, string $key) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST_FILTERS&TECHNICIAN_KEY=' . $key;
        $datosSD = $this->getDatosSD($this->Url . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        $viewid = '';
        foreach ($datosSD->operation->Details as $key => $value) {
            if ($value->VIEWNAME == $viewname) {
                $viewid = $value->VIEWID;
            }
        }
        return $viewid;
    }

    public function getRequestsByFilter(string $viewId, string $key, int $resolucionAux = 1) {
        $_key = $key;
        $usuarios = $this->consultarDepartamentoTI($_key);

        $input_data = '{"operation":{"details":{"from":"0","limit":"2000","filterby":"' . $viewId . '"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUESTS&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $_key;
        $data = $this->getDatosSD($this->Url . '?' . $this->FIELDS);
        $this->validarError($data);
        $returnArray = [];

        if ($resolucionAux == 1) {
            foreach ($data->operation->details as $key => $value) {
                $resolucion = $this->getResolucionFolio($_key, $value->WORKORDERID);
                $resolver = '';
                if (isset($resolucion->operation->Details)) {
                    foreach ($usuarios as $k => $v) {
                        if ($v['userId'] == $resolucion->operation->Details->RESOLVER) {
                            $resolver = $v['userName'];
                        }
                    }
                }

                array_push($returnArray, [
                    'folio' => $value->WORKORDERID,
                    'creador' => $value->CREATEDBY,
                    'solicita' => $value->REQUESTER,
                    'tecnico' => $value->TECHNICIAN,
                    'estatus' => $value->STATUS,
                    'prioridad' => (isset($value->PRIORITY)) ? $value->PRIORITY : '',
                    'asunto' => $value->SUBJECT,
                    'resolucion' => (isset($resolucion->operation->Details)) ? $resolucion->operation->Details->RESOLUTION : '',
                    'fechaResolucion' => (isset($resolucion->operation->Details)) ? date('Y-m-d H:i:s', $resolucion->operation->Details->LASTUPDATEDTIME / 1000) : '',
                    'resolver' => $resolver
                ]);
            }
            return $returnArray;
        } else {
            return $data->operation->details;
        }
    }

    public function getRequestDetails($_id, $_key) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $_key;
        $datosSD = $this->getDatosSD($this->Url . '/' . $_id . '?' . $this->FIELDS);
        $this->validarError($datosSD);
        return $datosSD;
    }

    public function getNotas(string $key, string $folio) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_NOTES&TECHNICIAN_KEY=' . $key;
        $data = json_decode(file_get_contents($this->Url . '/' . $folio . '/notes/?' . $this->FIELDS));
        return $data;
    }

}
