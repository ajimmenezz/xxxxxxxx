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
        $beneficiarios = $this->DB->getBeneficiarioByTipo($datos['id']);
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

        if ($resultado['code'] == 200) {
            $bodyMail = ''
                    . '<div style="font-size: 80%;">'
                    . '<h1 style="background:#305cac"><span style="color:white">AUTORIZACION DE GASTOS - GAPSI</span></h1>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Proyecto: ' . $datos['ProyectoString'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Cliente: ' . $datos['Cliente'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Sucursal: ' . $datos['SucursalString'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Monto total: $' . number_format($datos['Importe'], 2, '.', ",") . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Descripción: ' . $datos['Descripcion'] . '</h2>'
                    . '<br />'
                    . '</div>'
                    . '<p>El usuario: "' . $this->usuario['Nombre'] . '"  ha solicitado su aprobación para el siguiente gasto:</p>'
                    . '<table class="m_4241017372003712731MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="90%" style="width:90.0%;margin-left:11.25pt;border-collapse:collapse">'
                    . ' <thead>'
                    . '     <tr>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Categoría</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Subcategoría</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Concepto</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Monto</th>'
                    . '     </tr>'
                    . ' </thead>'
                    . ' <tbody>';

            $conceptos = json_decode($datos['Conceptos'], true);

            if (isset($conceptos) && count($conceptos) > 0) {
                foreach ($conceptos as $key => $value) {
                    $bodyMail .= ''
                            . '<tr>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['categoria']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['subcategoria']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['concepto']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . number_format($value['monto'], 2, '.', ",")
                            . '         </span></p>'
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
                    . ' </tbody>'
//                    . ' <tfoot>'
//                    . '     <tr>'
//                    . '         <th colspan="3" style="text-align:right; padding:10px">TOTAL</th>'
//                    . '         <th style="padding:10px">$' . number_format($datos['Importe'], 2, '.', ",") . '</th>'
//                    . '     </tr>'
//                    . ' </tfoot>'
                    . '</table>'
                    . '<br />' . $adjuntos
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra en las oficinas de SICCOB <a href="http://192.168.0.30/GAPSI/AplicaGastoSolic?ID=' . $resultado['last'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p><br/><br/>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra FUERA de las oficinas de SICCOB <a href="http://gapsi.dyndns.org/GAPSI/AplicaGastoSolic?ID=' . $resultado['last'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p>';

            $titulo = "Autorización Requerida";
            $this->Correo->enviarCorreo('gastos@siccob.solutions', array('jdiaz@siccob.com.mx', 'mrodriguez@siccob.com.mx', 'pruebasiccob@ioitconsulting.com', 'ajimenez@siccob.com.mx', $this->usuario['EmailCorporativo']), $titulo, $bodyMail, explode(",", $archivos));

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
            'Beneficiarios' => $this->beneficiarioByTipo(['id' => $gasto['gasto']['TipoBeneficiario']]),
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

        if ($resultado['code'] == 200) {
            $bodyMail = ''
                    . '<div style="font-size: 80%;">'
                    . '<h1 style="background:#305cac"><span style="color:white">AUTORIZACION DE GASTOS - GAPSI</span></h1>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Proyecto: ' . $datos['ProyectoString'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Cliente: ' . $datos['Cliente'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Sucursal: ' . $datos['SucursalString'] . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Monto total: $' . number_format($datos['Importe'], 2, '.', ",") . '</h2>'
                    . '<h2 style="background:#ededed;margin-right: 0cm;margin-left: 11.25pt;font-size: 11.5pt;font-family: \'Tahoma\',sans-serif;letter-spacing: .6pt;font-weight: normal;">Descripción: ' . $datos['Descripcion'] . '</h2>'
                    . '<br />'
                    . '</div>'
                    . '<p>El usuario: "' . $this->usuario['Nombre'] . '"  ha modificado la solicitud de gasto. Los detalles son los siguientes:</p>'
                    . '<table class="m_4241017372003712731MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="90%" style="width:90.0%;margin-left:11.25pt;border-collapse:collapse">'
                    . ' <thead>'
                    . '     <tr>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Categoría</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Subcategoría</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Concepto</th>'
                    . '         <th style="border: solid #dddddd 1.0pt;background: #3fb4a8;padding: 6.0pt 6.0pt 6.0pt 6.0pt; color:white;">Monto</th>'
                    . '     </tr>'
                    . ' </thead>'
                    . ' <tbody>';

            $conceptos = json_decode($datos['Conceptos'], true);

            if (isset($conceptos) && count($conceptos) > 0) {
                foreach ($conceptos as $key => $value) {
                    $bodyMail .= ''
                            . '<tr>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['categoria']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['subcategoria']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . $value['concepto']
                            . '         </span></p>'
                            . ' </td>'
                            . ' <td style="border:solid #dddddd 1.0pt;border-top:none;padding:6.0pt 6.0pt 6.0pt 6.0pt">'
                            . '     <p class="MsoNormal" style="margin-top:11.25pt">'
                            . '         <span style="font-family:&quot;Trebuchet MS&quot;,sans-serif">'
                            . '         ' . number_format($value['monto'], 2, '.', ",")
                            . '         </span></p>'
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
                    . ' </tbody>'
//                    . ' <tfoot>'
//                    . '     <tr>'
//                    . '         <th colspan="3" style="text-align:right; padding:10px">TOTAL</th>'
//                    . '         <th style="padding:10px">$' . number_format($datos['Importe'], 2, '.', ",") . '</th>'
//                    . '     </tr>'
//                    . ' </tfoot>'
                    . '</table>'
                    . '<br />' . $adjuntos
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra en las oficinas de SICCOB <a href="http://192.168.0.30/GAPSI/AplicaGastoSolic?ID=' . $datos['ID'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p><br/><br/>'
                    . '<p>Para aplicarlo de click en el siguiente link Si se encuentra FUERA de las oficinas de SICCOB <a href="http://gapsi.dyndns.org/GAPSI/AplicaGastoSolic?ID=' . $datos['ID'] . '" style="text-decoration:none;"><span class="boton"> Ingresar >></span></a></p>';

            $titulo = "Autorización Requerida - Cambios en la solicitud";
            $this->Correo->enviarCorreo('gastos@siccob.solutions', array('jdiaz@siccob.com.mx', 'mrodriguez@siccob.com.mx', 'pruebasiccob@ioitconsulting.com', 'ajimenez@siccob.com.mx', $this->usuario['EmailCorporativo']), $titulo, $bodyMail, explode(",", $archivos));

            $this->DB->actualizar("t_archivos_gastos_gapsi", ['Archivos' => $archivos, 'Email' => $this->usuario['EmailCorporativo'], 'IdUsuario' => $this->usuario['Id']], ['IdGasto' => $datos['ID']]);

            return $resultado;
        } else {
            return $resultado;
        }
    }

}
