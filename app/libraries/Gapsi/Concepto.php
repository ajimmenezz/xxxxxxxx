<?php

namespace Librerias\Gapsi;

use Controladores\Controller_Datos_Usuario as General;

class Concepto extends General {

    private $id;
    private $nombre;
    private $categoria;
    private $subCategoria;    
    private $tipoTranferencia;
    private $moneda;
    private $cantidad;
    private $dbConcepto;

    public function __construct(string $idConcepto) {
        parent::__construct();
    }

    public function getDatos() {
        return array();
    }

    public function getInformacion(string $tipoTransferencia) {
        return double;
    }

    public function getCantidad(string $tipoTransferencia) {
        return array();
    }

}
