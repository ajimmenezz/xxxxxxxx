<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Base_General as General;
use Aws\S3\S3Client;

class Catalogos extends General {

    private $DB;
    private $Correo;
    private $usuario;
    private $S3;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Gapsi::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
        $this->S3 = new S3Client([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => [
                'key' => 'AKIAJS7DH4TPDSKDHXSA',
                'secret' => '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'
            ]
        ]);
    }

    public function getClientes() {
        $clientes = $this->DB->getClientes();
        return $clientes;
    }

    public function getSucursales() {
        $sucursales = $this->DB->getSucursales();
        return $sucursales;
    }

    public function getTiposServicio() {
        $sucursales = $this->DB->getTiposServicio();
        return $sucursales;
    }

    public function getTiposBeneficiario() {
        $sucursales = $this->DB->getTiposBeneficiario();
        return $sucursales;
    }

    public function getTiposTransferencia() {
        $sucursales = $this->DB->getTiposTransferencia();
        return $sucursales;
    }

    public function getCategorias() {
        $sucursales = $this->DB->getCategorias();
        return $sucursales;
    }

    public function beneficiarioByTipo(array $datos = []) {
        $beneficiarios = $this->DB->getBeneficiarioByTipo($datos);
        return ['beneficiarios' => $beneficiarios];
    }

    public function categoriasByTipoTrans(array $datos = []) {
        $categorias = $this->DB->getCategoriasByTipoTrans($datos['id']);
        return ['categorias' => $categorias];
    }

    public function subcategoriasByCategoria(array $datos = []) {
        $subcategorias = $this->DB->getSubcategoriasByCategoria($datos['id']);
        return ['subcategorias' => $subcategorias];
    }

    public function conceptosBySubcategoria(array $datos = []) {
        $conceptos = $this->DB->getConceptosBySubcategoria($datos['id']);
        return ['conceptos' => $conceptos];
    }

    public function proyectosByCliente(array $datos = []) {
        $proyectos = $this->DB->proyectosByCliente($datos['id']);
        return ['proyectos' => $proyectos];
    }

    public function sucursalesByProyecto(array $datos = []) {
        $sucursales = $this->DB->sucursalesByProyecto($datos['id']);
        return ['sucursales' => $sucursales];
    }

    public function solicitarGasto(array $datos) {
        $resultado = $this->DB->solicitarGasto($datos);
        if($resultado['code'] == 508){
            return $resultado;
        }
        $last = ($resultado['code'] == 200) ? $resultado['last'] . '/' : '';

        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = './storage/Gastos/' . $last . 'PRE/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosGasto', $carpeta, 'gapsi');
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        $style = ''
                . '<style>
                        table{
                        font-size: 16px;
                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                            border-spacing: 0;
                            width: 90%;
                                    border: none;
                                        display:table;
                                        margin-top:15px;
                                        margin-left:15px;
                                        margin-right:30px;
                                            box-sizing: border-box;
                        }
                        td{
                        border: 1px solid #ddd;
                            text-align: left;
                            padding: 8px;
                                    display: table-cell;
                            vertical-align: inherit;
                        }
                        tr{
                            display: table-row;
                            vertical-align: inherit;
                            border-color: inherit;
                                }
                                tr.alt{background-color: #f2f2f2;
                                }
                        th{

                            display: table-cell;
                            vertical-align: inherit;
                            font-size: 16px;
                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                                    padding-top: 11px;
                            padding-bottom: 11px;
                            background-color: #3FB4A8;
                            color: white;
                                    border: 1px solid #ddd;
                            text-align: left;
                            padding: 8px;
                        }
                        .encabezado{
                            background-color: #4d94ff;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                        height:50px;

                        }
                        h1 {
                        margin-left:15px;
                        margin-top:10px;
                        padding-top:15px;
                        letter-spacing:0.8px;
                                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                                font-size: 24px;
                                font-style: normal;
                                font-variant: normal;
                                font-weight: 500;
                                line-height: 26.4px;
                        }
                        h2 {
                        margin-left:15px;

                        letter-spacing:0.8px;
                                font-family: "Tahoma", "Geneva", sans-serif;
                                font-size: 15px;
                                font-style: bold;
                                font-variant: normal;
                                font-weight: 200;
                                line-height: 10px;
                        }
                        p {
                        margin: 20px 20px 0 15px;
                        letter-spacing:0.5px;
                                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                                font-size: 14px;
                                font-style: normal;
                                font-variant: normal;
                                font-weight: 400;
                                line-height: 20px;
                        }
                        .Titulo{
                        height:120px; margin-top:10px; padding-top:2px; padding-bottom:10px; background-color:#EDEDED;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                        }
                        .boton{
                            background-color:#33CCFF;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                            text-decor:none;
                            display:inline-box;
                            margin-left:15px;
                            margin-top:5px;
                            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            letter-spacing:1px;
                            font-weight: 600;
                            line-height: 20px;
                            margin: 20px 20px 0 30px;
                            padding-left:15px;
                            padding-top:15px;
                            padding-right:15px;
                            padding-bottom:15px;
                            color:white;
                        }
                        </style>';

        if ($resultado['code'] == 200) {
            $bodyMail = ''
                    . ' <div class="encabezado" style="width:100%;  color:white;" >
                            <h1>SOLICITUD DE GASTOS  -   GAPSI</h1>   
                        </div>
                        <div class="Titulo" ><h2>Proyecto: ' . $datos['ProyectoString'] . '</h2>
                            <h2>Cliente: ' . $datos['Cliente'] . '</h2>
                            <h2>Sucursal: ' . $datos['SucursalString'] . '</h2>
                            <h2>Monto total: $' . number_format($datos['Importe'], 2, '.', ",") . '</h2>
                            <h2>Descripcion: ' . $datos['Descripcion'] . '</h2>
                        </div>
                        <p>El usuario: "' . $this->usuario['Nombre'] . '"  ha solicitado su aprobación para el siguiente gasto:</p>
                        <div style ="width:100%; background-color:white; margin-top:10px;">
                            <p>Se requiere que aplique el siguiente gasto:</p>
                        <table>
                            <tr>
                                <th >Categoria</th>
                                <th>Subcategoria</th>
                                <th>Concepto</th>
                                <th >Monto</th>   
                            </tr>';

            $conceptos = json_decode($datos['Conceptos'], true);

            if (isset($conceptos) && count($conceptos) > 0) {
                foreach ($conceptos as $key => $value) {
                    $bodyMail .= ''
                            . '<tr>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['categoria']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['subcategoria']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['concepto']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . number_format($value['monto'], 2, '.', ",")
                            . '     </p>'
                            . ' </td>'
                            . '</tr>';
                }
            }

            $adjuntos = '';
            if ($archivos != "") {
                $adjuntos .= '<h4>Archivos Adjuntos:</h4>';
                $arc = explode(",", $archivos);
                $headers = apache_request_headers();
                foreach ($arc as $key => $value) {
                    $adjuntos .= '<p><a target="_blank" href="' . $headers['Host'] . $value . '">Archivos Adjuntos</a></p>';
                }
                $adjuntos .= '<br />';
            }

            $bodyMail .= ''
                    . '</table>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra en las oficinas de SICCOB <a href="http://192.168.0.30/GAPSI/AplicaGastoSolic?ID=' . $resultado['last'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p><br/><br/>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra FUERA de las oficinas de SICCOB <a href="http://gapsi.dyndns.org/AplicaGastoSolic?ID=' . $resultado['last'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p>'
                    . '<br />' . $adjuntos;

            $titulo = "Solicitud de Gasto";
            $this->Correo->enviarCorreo('gastos@siccob.solutions', array('mrodriguez@siccob.com.mx', 'pruebasiccob@ioitconsulting.com', 'ajimenez@siccob.com.mx', $this->usuario['EmailCorporativo']), $titulo, $bodyMail, explode(",", $archivos), $style);

            $this->DB->insertar("t_archivos_gastos_gapsi", ['IdGasto' => $resultado['last'], 'Archivos' => $archivos, 'Email' => $this->usuario['EmailCorporativo'], 'IdUsuario' => $this->usuario['Id']]);

            return $resultado;
        } else {
            return $resultado;
        }
    }

    public function misGastos() {
        $gastos = $this->DB->getMisGastos();
        return $gastos;
    }

    public function cargaGasto(array $data) {
        $gasto = $this->detallesGasto($data['id']);

        $prefix = 'Gastos/' . $data['id'] . '/PAG/';

        $pagosArray = [];

        $pagos = $this->S3->listObjects([
            'Bucket' => 'gapsi',
            'Prefix' => $prefix
        ]);

        $pagos = $pagos->toArray();

        $pagos = (isset($pagos['Contents'])) ? $pagos['Contents'] : [];

        foreach ($pagos as $key => $value) {
            if ($value['Key'] != $prefix) {
                array_push($pagosArray, 'https://s3-us-west-2.amazonaws.com/gapsi/' . $value['Key']);
            }
        }

        $editable = ($gasto['usuario'] == $this->usuario['Id'] && in_array($gasto['gasto']['Status'], ['Requiere Autorizacion', 'Solicitado'])) ? true : false;

        $datos = [
            'Clientes' => $this->getClientes(),
            'TiposServicio' => $this->getTiposServicio(),
            'TiposBeneficiario' => $this->getTiposBeneficiario(),
            'TiposTransferencia' => $this->getTiposTransferencia(),
            'Proyectos' => $this->proyectosByCliente(['id' => $gasto['gasto']['Cliente']]),
            'Sucursales' => $this->sucursalesByProyecto(['id' => $gasto['gasto']['Proyecto']]),
            'Beneficiarios' => $this->beneficiarioByTipo(['id' => $gasto['gasto']['TipoBeneficiario'], 'proyecto' => $gasto['gasto']['Proyecto']]),
            'Gasto' => $gasto,
            'Editable' => $editable,
            'Pagos' => $pagosArray
        ];
        return [
            'html' => parent::getCI()->load->view('Gapsi/DetallesGasto', $datos, TRUE)
        ];
    }

    public function detallesGasto(int $id) {
        $gasto = $this->DB->detallesGasto($id);
        return $gasto;
    }

    public function guardarCambiosGasto(array $datos) {
        $resultado = $this->DB->guardarCambiosGasto($datos);
        $last = $datos['ID'];

        $archivos = $result = null;
        $CI = parent::getCI();
        $carpeta = './storage/Gastos/' . $last . '/PRE/';
        $archivos = "";
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'fotosGasto', $carpeta, 'gapsi');
            if ($archivos) {
                $archivos = implode(',', $archivos);
            }
        }

        if ($datos['EvidenciasAntes'] !== '') {
            if ($archivos != '') {
                $archivos = $archivos . $datos['EvidenciasAntes'];
            } else {
                $archivos = substr($archivos, 1);
            }
        }

        $style = ''
                . '<style>
                        table{
                        font-size: 16px;
                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                            border-spacing: 0;
                            width: 90%;
                                    border: none;
                                        display:table;
                                        margin-top:15px;
                                        margin-left:15px;
                                        margin-right:30px;
                                            box-sizing: border-box;
                        }
                        td{
                        border: 1px solid #ddd;
                            text-align: left;
                            padding: 8px;
                                    display: table-cell;
                            vertical-align: inherit;
                        }
                        tr{
                            display: table-row;
                            vertical-align: inherit;
                            border-color: inherit;
                                }
                                tr.alt{background-color: #f2f2f2;
                                }
                        th{

                            display: table-cell;
                            vertical-align: inherit;
                            font-size: 16px;
                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                                    padding-top: 11px;
                            padding-bottom: 11px;
                            background-color: #3FB4A8;
                            color: white;
                                    border: 1px solid #ddd;
                            text-align: left;
                            padding: 8px;
                        }
                        .encabezado{
                            background-color: #4d94ff;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                        height:50px;

                        }
                        h1 {
                        margin-left:15px;
                        margin-top:10px;
                        padding-top:15px;
                        letter-spacing:0.8px;
                                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                                font-size: 24px;
                                font-style: normal;
                                font-variant: normal;
                                font-weight: 500;
                                line-height: 26.4px;
                        }
                        h2 {
                        margin-left:15px;

                        letter-spacing:0.8px;
                                font-family: "Tahoma", "Geneva", sans-serif;
                                font-size: 15px;
                                font-style: bold;
                                font-variant: normal;
                                font-weight: 200;
                                line-height: 10px;
                        }
                        p {
                        margin: 20px 20px 0 15px;
                        letter-spacing:0.5px;
                                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                                font-size: 14px;
                                font-style: normal;
                                font-variant: normal;
                                font-weight: 400;
                                line-height: 20px;
                        }
                        .Titulo{
                        height:120px; margin-top:10px; padding-top:2px; padding-bottom:10px; background-color:#EDEDED;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                        }
                        .boton{
                            background-color:#33CCFF;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                            text-decor:none;
                            display:inline-box;
                            margin-left:15px;
                            margin-top:5px;
                            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                            font-size: 14px;
                            font-style: normal;
                            font-variant: normal;
                            letter-spacing:1px;
                            font-weight: 600;
                            line-height: 20px;
                            margin: 20px 20px 0 30px;
                            padding-left:15px;
                            padding-top:15px;
                            padding-right:15px;
                            padding-bottom:15px;
                            color:white;
                        }
                        </style>';

        if ($resultado['code'] == 200) {
            $bodyMail = ''
                    . ' <div class="encabezado" style="width:100%;  color:white;" >
                            <h1>SOLICITUD DE GASTOS  -   GAPSI</h1>   
                        </div>
                        <div class="Titulo" ><h2>Proyecto: ' . $datos['ProyectoString'] . '</h2>
                            <h2>Cliente: ' . $datos['Cliente'] . '</h2>
                            <h2>Sucursal: ' . $datos['SucursalString'] . '</h2>
                            <h2>Monto total: $' . number_format($datos['Importe'], 2, '.', ",") . '</h2>
                            <h2>Descripcion: ' . $datos['Descripcion'] . '</h2>
                        </div>
                        <p>El usuario: "' . $this->usuario['Nombre'] . '"  ha solicitado su aprobación para el siguiente gasto:</p>
                        <div style ="width:100%; background-color:white; margin-top:10px;">
                            <p>Se requiere que aplique el siguiente gasto:</p>
                        <table>
                            <tr>
                                <th >Categoria</th>
                                <th>Subcategoria</th>
                                <th>Concepto</th>
                                <th >Monto</th>   
                            </tr>';

            $conceptos = json_decode($datos['Conceptos'], true);

            if (isset($conceptos) && count($conceptos) > 0) {
                foreach ($conceptos as $key => $value) {
                    $bodyMail .= ''
                            . '<tr>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['categoria']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['subcategoria']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . $value['concepto']
                            . '     </p>'
                            . ' </td>'
                            . ' <td>'
                            . '     <p>'
                            . '     ' . number_format($value['monto'], 2, '.', ",")
                            . '     </p>'
                            . ' </td>'
                            . '</tr>';
                }
            }

            $adjuntos = '';
            if ($archivos != "") {
                $adjuntos .= '<h4>Archivos Adjuntos:</h4>';
                $arc = explode(",", $archivos);
                $headers = apache_request_headers();
                foreach ($arc as $key => $value) {
                    $adjuntos .= '<p><a target="_blank" href="' . $headers['Host'] . $value . '">Archivos Adjuntos</a></p>';
                }
                $adjuntos .= '<br />';
            }

            $bodyMail .= ''
                    . '</table>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra en las oficinas de SICCOB <a href="http://192.168.0.30/GAPSI/AplicaGastoSolic?ID=' . $last . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p><br/><br/>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra FUERA de las oficinas de SICCOB <a href="http://gapsi.dyndns.org/AplicaGastoSolic?ID=' . $last . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p>'
                    . '<br />' . $adjuntos;

            $titulo = "Solicitud de Gasto";
            $this->Correo->enviarCorreo('gastos@siccob.solutions', array('mrodriguez@siccob.com.mx', 'pruebasiccob@ioitconsulting.com', 'ajimenez@siccob.com.mx', $this->usuario['EmailCorporativo']), $titulo, $bodyMail, explode(",", $archivos), $style);

            $this->DB->actualizar("t_archivos_gastos_gapsi", ['Archivos' => $archivos, 'Email' => $this->usuario['EmailCorporativo'], 'IdUsuario' => $this->usuario['Id']], ['IdGasto' => $datos['ID']]);

            return $resultado;
        } else {
            return $resultado;
        }
    }

    public function eliminarArchivo(array $datos) {
        $return = $this->DB->eliminarArchivo($datos);
        if ($return['code'] == 200) {
            $s3Result = $this->S3->deleteObject([
                'Bucket' => 'gapsi',
                'Key' => str_replace("/storage/", "", $datos['Source'])
            ]);
            if (file_exists("." . $datos['Source'])) {
                unlink("." . $datos['Source']);
            }
        }
        return $return;
    }

    public function marcarLeido(array $datos) {
        $return = $this->DB->marcarLeido($datos);
        return $return;
    }

    public function comprobarGastos() {
        $datos = $this->DB->getComprobarGastos();
        $resultado = ['datos' => $datos];
        return array('resultado' => $resultado, 'vistaTabla' => parent::getCI()->load->view('Gapsi/Mis-Gastos', $resultado, TRUE));
    }

    public function comprobacionRegistro($datos) {
        $idRegistro = $datos['idGasto'];
        $monto = $datos['monto'];
        $typeFiles = null;
        $CI = parent::getCI();
        $carpeta = './storage/Gastos/' . $idRegistro . '/FACT/';
//        $carpeta = 'Gastos/' . $idRegistro . '/FACT/';

        if (!empty($_FILES)) {
            $typeFiles = $_FILES['inputArchivoComprobante']['type'];
        }

        if (in_array('text/xml', $typeFiles)) {
            $archivosComprobantes = $_FILES;
            $respuesta = $this->comprobarXML($archivosComprobantes);
            if ($respuesta === true) {
                $archivos = setMultiplesArchivos($CI, 'inputArchivoComprobante', $carpeta, 'gapsi');
                $respuesta = $this->validarComprobanteGastos($monto, $archivos, $datos);                
            }
        } else {
            $archivos = setMultiplesArchivos($CI, 'inputArchivoComprobante', $carpeta,'gapsi');
            return $this->cambiarNombre($archivos, $idRegistro);
//            $respuesta = $this->DB->registrarSinXML($datos);
        }
        return $respuesta;
    }

    // Comprobar numero de archivos XML y PDF
    public function comprobarXML($archivosComprobantes) {

        $nombreComprobante = [];
        foreach ($archivosComprobantes as $key => $archivoComprobante) {
            $nombreComprobante = $archivoComprobante['name'];
            $arrayArchivos = [];
            $arrayTipoArchivo = [];


            foreach ($nombreComprobante as $key => $nombreComprobante) {
                $respuesta = null;
                $extension = pathinfo($nombreComprobante, PATHINFO_EXTENSION);
                array_push($arrayArchivos, array('extensiones' => $extension));
                array_push($arrayTipoArchivo, $extension);

                if (in_array('xml', $arrayTipoArchivo)) {
                    $unXML = 1;
                    $contadorXML = array_count_values(array_column($arrayArchivos, 'extensiones'))['xml'];

                    if ($contadorXML > 1) {
                        $unXML = 2;
                    }

                    if ($unXML === 1) {
                        if (in_array('pdf', $arrayTipoArchivo)) {
                            $unPDF = array_count_values(array_column($arrayArchivos, 'extensiones'))['pdf'];
                            $contadorPDF = $unPDF;

                            if ($contadorPDF > 1) {
                                $respuesta = ['code' => 500, 'errorBack' => 'Se seleccionaron más de 1 PDF. Por favor verifique sus archivos.'];
                            } else {
                                $respuesta = true;
                            }
                        } else {
                            $respuesta = ['code' => 500, 'errorBack' => 'Recuerda, por cada XML debe haber un PDF.'];
                        }
                    } else {
                        $respuesta = ['code' => 500, 'errorBack' => 'Solo es necesario un XML con un PDF'];
                    }
                }
            }
            return $respuesta;
        }
    }

    //Obtener UUID y validar monto y version de XML
    public function validarComprobanteGastos($monto, $archivos, $datos) {

        foreach ($archivos as $key => $value) {
            $extension = pathinfo($value, PATHINFO_EXTENSION);
            if ($extension === 'xml') {
                $xml = simplexml_load_file(getcwd() . $value);

                $arrayComprobante = (array) $xml->xpath('//cfdi:Comprobante')[0];
                $resultadoComprobante = $this->validarTotalXML($arrayComprobante, $monto);
                if ($resultadoComprobante['code'] != 200) {
                    $this->eliminaArchivos($archivos);
                    return ['code' => 500, 'errorBack' => $resultadoComprobante['error']];
                }

                $arrayReceptor = (array) $xml->xpath('//cfdi:Receptor')[0];
                $resultadoComprobante = $this->validarReceptorXML($arrayReceptor);
                if ($resultadoComprobante['code'] != 200) {
                    $this->eliminaArchivos($archivos);
                    return ['code' => 500, 'errorBack' => $resultadoComprobante['error']];
                }

                $cfdi = new LeerCFDI();
                $cfdi->cargaXml(getcwd() . $value);
                $valorUUID = $cfdi->uuid();
                if (isset($valorUUID)) {
                    $datos = ['idGasto' => $datos['idGasto'],
                        'Monto' => $datos['monto'],
                        'Comentario' => null,
                        'Status' => 'Enviado',
                        'UUID' => $valorUUID];

                    return $this->DB->insertarComprobanteGapsi($datos);
                } else {
                    return ['code' => 500, 'errorBack' => "No se encuentra UUID"];
                }
            }
        }
    }

    public function eliminaArchivos(array $archivos) {
        foreach ($archivos as $k => $v) {
            try {
                unlink('.' . $v);
            } catch (Exception $ex) {
                
            }
        }
    }

    //Validar Version de archivo XML
    public function validarTotalXML(array $datos, float $total) {
        $arrayReturn = [
            'code' => 200,
            'error' => 'OK'
        ];

        $total = abs($total);

        foreach ($datos as $k => $nodoComprobante) {
            if (isset($nodoComprobante['Version'])) {
                if ($nodoComprobante['Version'] === '3.3' || $nodoComprobante['Version'] === 'V3.3' || $nodoComprobante['Version'] === 'V 3.3') {
                    $resultadoComprobante = TRUE;
                } else {
                    $arrayReturn['code'] = 400;
                    $arrayReturn['error'] = 'La Version de XML es incorrecta. Es necesario que el CFDI tenga la versión 3.3';
                }
            } else {
                $arrayReturn['code'] = 400;
                $arrayReturn['error'] = 'La etiqueta de versión del CFDI no existe. Verifique su archivo';
            }

            if (isset($nodoComprobante['Total'])) {
                $totalFloat = (float) $nodoComprobante['Total'];
                if ($totalFloat >= ((float) $total - 0.99) && $totalFloat <= ((float) $total) + 0.99) {
                    
                } else {
                    $arrayReturn['code'] = 400;
                    $arrayReturn['error'] = 'El total de la factura no corresponde al capturado para comprobacion. Factura:$' . $totalFloat . ' y Monto:$' . $total;
                }
            } else {
                $arrayReturn['code'] = 400;
                $arrayReturn['error'] = 'La etiqueta Total del CFDI no existe. Verifique su archivo';
            }
        }

        return $arrayReturn;
    }

    //Validar monto de archivo XML
    public function validarReceptorXML(array $datos) {
        $arrayReturn = [
            'code' => 200,
            'error' => 'OK'
        ];

        foreach ($datos as $k => $nodoReceptor) {
            if (isset($nodoReceptor['Rfc'])) {
                if ($nodoReceptor['Rfc'] === 'SSO0101179Z7') {
                    
                } else {
                    $arrayReturn['code'] = 400;
                    $arrayReturn['error'] = 'El receptor (RFC) no coincide con SSO0101179Z7. Verifique su archivo';
                }
            } else {
                return 'La etiqueta RFC del Receptor no existe. Verifique su archivo';
            }
        }

        return $arrayReturn;
    }

    public function cambiarNombre($archivos, $idRegistro) {

        $nuevoNombre = "./storage/Gastos/" . $idRegistro . "/FACT/" . $idRegistro;
        $contador = 0;

        foreach ($archivos as $key => $ruta) {
            
            $rutaArchivo = "." . $ruta;
            if (file_exists($rutaArchivo)) {
                $extension = explode(".", $ruta);
                $nuevoNombre1 = $nuevoNombre . "." . $extension[1];
                if (file_exists($nuevoNombre1)) {
                    $contador++;
                    $nuevoNombre2 = $nuevoNombre . "(" . $contador . ")." . $extension[1];
                    rename($rutaArchivo, $nuevoNombre2);
                    $respuestaArchivo = ['code' => 200, 'errorBack' => 'Comprobante de pago Registrado'];
                } else {
                    if (rename($rutaArchivo, $nuevoNombre1)) {
                        $respuestaArchivo = ['code' => 200, 'errorBack' => 'Comprobante de pago Registrado'];
                    } else {
                        $respuestaArchivo =  ['code' => 500, 'errorBack' => 'El comprobante no se ha registrado correctamente'];
                    }
                }
            } else {
                $respuestaArchivo = ['code' => 500, 'errorBack' => 'La ruta no es correcta'];
            }
        }
        return $respuestaArchivo;
    }
    
    public function actualizarComprobacion($datos) {

        $consulta = $this->DB->consultaRegistro($datos);
        if ($consulta) {
            $this->DB->marcarComprobado($datos);
            $this->DB->actualizarMontoComprobado($datos);
            return ['code' => 200, "error" => "ok"];
        } else {
            return ['code' => 500, "error" => "Necesitas registrar comprobantes"];
        }
    }
    
    public function terminarComprobante($datos) {
        $consulta = $this->DB->consultaRegistro($datos);
        
        if($consulta){
            return ['code' => 200, "error" => true];
        }else{
            return ['code' => 500, "error" => "No es posible teminar comprobacion"];
        }
    }

}

//Clase para obtener informacion de XML
class LeerCFDI {

    /**
     * Namespaces
     */
    private $namespaces;

    /**
     * Archivo XML
     */
    private $xml;

    /**
     * Serie del CFDI
     */
    private $serie;

    /**
     * Folio del CFDI
     */
    private $folio;

    /**
     * RFC del emisor
     */
    private $rfcEmisor;

    /**
     * RFC del receptor
     */
    private $rfcReceptor;

    /**
     * Fecha del CFDI
     */
    private $fecha;

    /**
     * Total del CFDI
     */
    private $total;

    /**
     * Tipo de comprobante
     */
    private $tipoComprobante;

    /**
     * UUID del CFDI
     */
    private $uuid;

    /**
     * archivoXML Ruta del archivo XML
     */
    function cargaXml($archivoXML) {

        if (file_exists($archivoXML)) {
            libxml_use_internal_errors(true);
            $this->xml = new \SimpleXMLElement($archivoXML, null, true);
            $this->namespaces = $this->xml->getNamespaces(true);
        } else {
            throw new Exception("Error al cargar archivo XML, verifique que el archivo exista.", 1);
        }
    }

    /**
     * Obtener el RFC del Emisor
     */
    function rfcEmisor() {

        foreach ($this->xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $emisor) {
            $this->rfcEmisor = $emisor['rfc'] != "" ? $emisor['rfc'] : $emisor['Rfc'];
        }

        return $this->rfcEmisor;
    }

    /**
     * Obtener el RFC del Receptor
     */
    function rfcReceptor() {

        foreach ($this->xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $receptor) {
            $this->rfcReceptor = $receptor['rfc'] != "" ? $receptor['rfc'] : $receptor['Rfc'];
        }

        return $this->rfcReceptor;
    }

    /**
     * Obtener el RFC  del CFDI
     */
    function total() {

        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->total = $comprobante['total'] != "" ? $comprobante['total'] : $comprobante['Total'];
        }
        return $this->total;
    }

    /**
     * Obtener la serie del CFDI
     */
    function serie() {

        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->serie = $comprobante['serie'] != "" ? $comprobante['serie'] : $comprobante['Serie'];
        }

        return $this->serie;
    }

    /**
     * Obtener elfolio del CFDI
     */
    function folio() {

        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->folio = $comprobante['folio'] != "" ? $comprobante['folio'] : $comprobante['Folio'];
        }

        return $this->folio;
    }

    /**
     * Obtener el la fecha del CFDI
     */
    function fecha() {

        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->fecha = $comprobante['fecha'] != "" ? $comprobante['fecha'] : $comprobante['Fecha'];
        }

        return $this->fecha;
    }

    /**
     * Obtener el tipo del comprobante del  CFDI (Ingreso o Egreso);
     */
    function tipoComprobante() {

        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->tipoComprobante = $comprobante['tipoDeComprobante'] != "" ? $comprobante['tipoDeComprobante'] : $comprobante['TipoDeComprobante'];
        }

        if (strcmp(strtolower($this->tipoComprobante), 'ingreso') == 0 || strcmp(strtolower($this->tipoComprobante), 'i') == 0) {
            $this->tipoComprobante = "I";
        } else {
            $this->tipoComprobante = "E";
        }

        return $this->tipoComprobante;
    }

    /**
     * Obtener el UUID de la factura
     */
    function uuid() {

        $this->xml->registerXPathNamespace('t', $this->namespaces['tfd']);

        foreach ($this->xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $this->uuid = "{$tfd['UUID']}";
        }

        return $this->uuid;
    }

}
