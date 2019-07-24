<?php

namespace Librerias\Generales;

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
     * Versión del CFDI
     */
    private $version;

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

        if (gettype($this->rfcEmisor) == 'object') {
            $_emisor = (array) $this->rfcEmisor;
            $this->rfcEmisor = $_emisor[0];
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

        if (gettype($this->rfcReceptor) == 'object') {
            $_receptor = (array) $this->rfcReceptor;
            $this->rfcReceptor = $_receptor[0];
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

        if (gettype($this->serie) == 'object') {
            $_serie = (array) $this->serie;
            $this->serie = $_serie[0];
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

        if (gettype($this->folio) == 'object') {
            $_folio = (array) $this->folio;
            $this->folio = $_folio[0];
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

        if (gettype($this->fecha) == 'object') {
            $_fecha = (array) $this->fecha;
            $this->fecha = $_fecha[0];
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

    /**
     * Obtiene la version el CFDI
     */
    function version() {
        foreach ($this->xml->xpath('//cfdi:Comprobante') as $comprobante) {
            $this->version = $comprobante['version'] != "" ? $comprobante['version'] : $comprobante['Version'];
        }

        if (gettype($this->version) == 'object') {
            $_version = (array) $this->version;
            $this->version = $_version[0];
        }

        return $this->version;
    }

    /**
     * Valida la informaci{on del CFDI para fines de Siccob y Residig
     */
    function validar(float $total = 0, array $receptores = ['SSO0101179Z7', 'RSD130305DI7', 'RRC130605555']) {
        $total = abs($total);
        $arrayReturn = [
            'code' => 200,
            'error' => '',
            'data' => [
                'serie' => $this->serie(),
                'folio' => $this->folio(),
                'receptor' => $this->rfcReceptor(),
                'fecha' => $this->fecha(),
                'total' => $this->total(),
                'uuid' => $this->uuid(),
                'version' => $this->version()
            ]
        ];

        if (!in_array($this->version(), ['3.3', 'V3.3', 'V 3.3'])) {
            $arrayReturn['code'] = 500;
            $arrayReturn['error'] = 'La Version de XML es incorrecta. Es necesario que el CFDI tenga la versión 3.3';
        }

        if ((float) $this->total() >= ((float) $total) - 0.99 && (float) $this->total() <= ((float) $total) + 0.99) {
            $arrayReturn['data']['total'] = (float) $this->total();
        } else {
            $arrayReturn['code'] = 500;
            $arrayReturn['error'] = 'El total de la factura no corresponde al capturado para comprobacion. Factura:$' . (float) $this->total . ' y Monto:$' . $total;
        }

        if (in_array($this->rfcReceptor(), $receptores)) {
            $arrayReturn['data']['receptor'] = $this->rfcReceptor();
        } else {
            $arrayReturn['code'] = 500;
            $arrayReturn['error'] = 'El receptor (RFC) no coincide con nuestros registros. Verifique su archivo';
        }

        return $arrayReturn;
    }
}
