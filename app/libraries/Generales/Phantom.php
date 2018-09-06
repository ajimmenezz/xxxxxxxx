<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Phantom
 *
 * @author Alonso
 */
class Phantom extends General {

    private $archivoJS;
    private $size;
    private $pixeles;

    public function __construct() {
        parent::__construct();
        $this->archivoJS = '/assets/js/customize/Phantom/renderLetter.js';
        $this->size = 'Letter';
        $this->pixeles = '1224x1584';
    }

    public function htmlToPdf(string $archivoSalida, string $ruta, array $datosServicio = NULL) {
        $nombreArchivo = basename($archivoSalida);
        $phantomArchivo = str_replace($nombreArchivo, 'archivoPhantom.pdf', $archivoSalida);
        $cabecera = '';

        if (file_exists($phantomArchivo)) {
            unlink($phantomArchivo);
        }

        if (file_exists($nombreArchivo)) {
            unlink($nombreArchivo);
        }

        if (!empty($datosServicio)) {
            if (!empty($datosServicio['Sucursal'] && !empty($datosServicio['Folio']))) {
                $sucursal = str_replace(" ", "_", $datosServicio['Sucursal']);
                $cabecera = 'Sucursal:' . $sucursal . '-FolioSD:' . $datosServicio['Folio'];
            } else if (!empty($datosServicio['Sucursal']) && empty($datosServicio['Folio'])) {
                $sucursal = str_replace(" ", "_", $datosServicio['Sucursal']);
                $cabecera = 'Sucursal:' . $sucursal;
            } else if (empty($datosServicio['Sucursal']) && !empty($datosServicio['Folio'])) {
                $cabecera = 'FolioSD:' . $datosServicio['Folio'];
            } else if (!empty($datosServicio['Sucursal']) && $datosServicio['Folio'] === '0') {
                $sucursal = str_replace(" ", "_", $datosServicio['Sucursal']);
                $cabecera = 'Sucursal:' . $sucursal;
            }
        }
        
        $cabecera = str_replace("(", "", $cabecera);
        $cabecera = str_replace(")", "", $cabecera);

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows')) {
            exec('phantomjs ' . $this->archivoJS . ' ' . $ruta . ' ' . $archivoSalida . ' ' . $this->size . ' ' . $cabecera, $out);
        } else {
            exec('phantomjs ' . $this->archivoJS . ' ' . $ruta . ' ' . $phantomArchivo . ' ' . $this->size . ' ' . $cabecera, $out);
            exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . $archivoSalida . ' ' . $phantomArchivo);
            unlink($phantomArchivo);
        }
        return $archivoSalida;
    }

}
