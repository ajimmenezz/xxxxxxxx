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

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', 300);
        $this->Url = "http://mesadeayuda.cinemex.net:8080/sdpapi/request";
        $this->UrlUsers = "http://mesadeayuda.cinemex.net:8080/sdpapi/requester/";
        $this->modeloServiceDesck = \Modelos\Modelo_ServiceDesk::factory();
    }

    /*
     * Encargado de obtener todos lo folios asiganados al tecnico
     * 
     */

    public function getFoliosTecnico(string $key) {
        $input_data = '{"operation":{"details":{ "from": "0","limit": "5000","filterby": "All_Pending_User"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUESTS&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        return file_get_contents($this->Url . '?' . $this->FIELDS);
    }

    /*
     * Encargaado de obtener los detalles del folio
     * 
     */

    public function getDetallesFolio(string $key, string $folio) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST&TECHNICIAN_KEY=' . $key;
        if (stristr($this->Url . '/' . $folio . '?' . $this->FIELDS, 'HTTP request failed!') === FALSE) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Url . '/' . $folio . '?' . $this->FIELDS);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->FIELDS);
            $return = curl_exec($ch);
            curl_close($ch);

            return json_decode($return);
        } else {
            return '';
        }
    }

    /*
     * Encargaado de obtener la resolucion del folio
     * 
     */

    public function getResolucionFolio(string $key, string $folio) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_RESOLUTION&TECHNICIAN_KEY=' . $key;
        if (stristr($this->Url . '/' . $folio . '?' . $this->FIELDS, 'HTTP request failed!') === FALSE) {
            $json = json_decode(@file_get_contents($this->Url . '/' . $folio . '?' . $this->FIELDS));

            if (!empty($json)) {
                return file_get_contents($this->Url . '/' . $folio . '?' . $this->FIELDS);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    /*
     * Encargado de obtener la lista de los tecnicos de Service Desk
     * 
     */

    public function getTecnicosSD(string $key) {
        $Url2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/technician";
        $input_data = '{"operation":{"details":{ "parameter": { "name":"department", "value" : ""}}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_ALL&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url2 . '?' . $this->FIELDS);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->FIELDS);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }

    /*
     * Encargado de reasingar el folio en SD
     * 
     */

    public function reasignarFolioSD(string $folio, string $tecnico, string $key) {
        $input_data = '{"operation":{"details":{"technicianid":"' . $tecnico . '"}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=ASSIGN_REQUEST&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;

        if (stristr($this->Url . '/' . $folio . '?' . $this->FIELDS, 'HTTP request failed!') === FALSE) {
            $json = json_decode(file_get_contents($this->Url . '/' . $folio . '?' . $this->FIELDS));

            if (!empty($json)) {
                return file_get_contents($this->Url . '/' . $folio . '?' . $this->FIELDS);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    /*
     * Encargado de agregar resolicion del folio en SD
     * 
     */

    public function resolucionFolioSD(string $folio, string $tecnico, string $key, string $descripcion) {
        $input_data = '{"operation":{"details":{"resolution":{"resolutiontext":"' . $descripcion . '"}}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=EDIT_RESOLUTION&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        return file_get_contents($this->Url . '/' . $folio . '/resolution?' . $this->FIELDS);
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
        return file_get_contents($this->Url . '?' . $this->FIELDS);
    }

    /*
     * Encargado de obtener e insertar una resolicion en Service Desk
     * 
     */

    public function setResolucionServiceDesk(string $key, string $folio, string $datos) {
        $resolicionesAnteriores = json_decode($this->getResolucionFolio($key, $folio));
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio . "/resolution/";
        if (!empty($resolicionesAnteriores->operation->Details->RESOLUTION)) {
            $datosAnterioresResolicion = $resolicionesAnteriores->operation->Details->RESOLUTION;
        } else {
            $datosAnterioresResolicion = '';
        }

        $stringInicio = '*****BEGIN SICCOB RESOLUTION*****';
        $stringFin = '*****END SICCOB RESOLUTION*****';
        $posInicio = strpos($datosAnterioresResolicion, $stringInicio);

        if ($posInicio !== false) {
            $posFin = strpos($datosAnterioresResolicion, $stringFin) + 30;
            $longitud = ($posFin - $posInicio) + 1;
            $stringRemove = substr($datosAnterioresResolicion, $posInicio, $longitud);
            $datosAnterioresResolicion = str_replace($stringRemove, '', $datosAnterioresResolicion);
        }

        /* Concatena la nueva resolución con la resolución anterior */
        $nuevaResolucion = ''
                . $stringInicio
                . "<br>"
                . $datos
                . "<br>"
                . $stringFin
                . stripslashes(trim($datosAnterioresResolicion));

        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '         "resolution": {'
                . '             "resolutiontext": "' . $this->mres($nuevaResolucion) . '"'
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

    public function setResolucionServiceDesk2(string $key, string $folio, string $datos) {
        $resolicionesAnteriores = json_decode($this->getResolucionFolio($key, $folio));
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio . "/resolution/";
        if (!empty($resolicionesAnteriores->operation->Details->RESOLUTION)) {
            $datosAnterioresResolicion = $resolicionesAnteriores->operation->Details->RESOLUTION;
        } else {
            $datosAnterioresResolicion = '';
        }
        $nuevaResolucion = $datos . '<br><br>' . stripslashes($datosAnterioresResolicion);

        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '         "resolution": {'
                . '             "resolutiontext": "' . $this->mres($nuevaResolucion) . '"'
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

        return json_decode($return);
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

    public function d(string $key, string $estatus, string $folio) {
        $URL2 = "http://mesadeayuda.cinemex.net:8080/sdpapi/request/" . $folio;

        $input_data = ''
                . '{'
                . ' "operation": {'
                . '     "details": {'
                . '             "status": ' . $estatus
                . '     }'
                . ' }'
                . '}';
        $FIELDS = "format=json&"
                . "OPERATION_NAME=EDIT_REQUEST&"
                . "INPUT_DATA=" . urlencode($input_data) . "&"
                . "TECHNICIAN_KEY=" . $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $FIELDS);
        $return = curl_exec($ch);
        curl_close($ch);
        return json_decode($return);
    }

    /*
     * Encargado de consultar el depertamento de IT en SD
     * 
     */

    public function consultarDepartamentoTI(string $key) {
        $input_data = '{"operation":{"details":{"department":""}}}';
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_ALL&INPUT_DATA=' . urlencode($input_data) . '&TECHNICIAN_KEY=' . $key;
        $data = json_decode(file_get_contents($this->UrlUsers . '?' . $this->FIELDS));
        $returnArray = [];
        $i = 0;

        foreach ($data->operation->details as $key => $value) {
            $returnArray[$i]['userId'] = $value->userid;
            $returnArray[$i]['userName'] = $value->username;
            $returnArray[$i]['userEmail'] = $value->emailid;
            $i++;
        }

        return $returnArray;
    }

    public function getViewId(string $viewname, string $key) {
        $this->FIELDS = 'format=json&OPERATION_NAME=GET_REQUEST_FILTERS&TECHNICIAN_KEY=' . $key;
        $data = json_decode(file_get_contents($this->Url . '?' . $this->FIELDS));
        $viewid = '';
        foreach ($data->operation->Details as $key => $value) {
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
        $data = json_decode(file_get_contents($this->Url . '?' . $this->FIELDS));

        $returnArray = [];

        if ($resolucionAux == 1) {
            foreach ($data->operation->details as $key => $value) {
                $resolucion = json_decode($this->getResolucionFolio($_key, $value->WORKORDERID));
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
        $data = json_decode(file_get_contents($this->Url . '/' . $_id . '?' . $this->FIELDS));
        return $data;
    }

}
