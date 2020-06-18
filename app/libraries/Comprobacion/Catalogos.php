<?php

namespace Librerias\Comprobacion;

use Controladores\Controller_Base_General as General;

class Catalogos extends General {

    private $DB;
    private $Correo;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->DB = \Modelos\Modelo_Comprobacion::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    public function formularioAgregarConcepto(array $datos) {

        $datos = [
            'tiposComprobante' => $this->getTiposComprobante(),
            'usuarios' => $this->getUsuarios(),
            'sucursales' => $this->getSucursales(),
            'generales' => ($datos['id'] > 0) ? $this->DB->getConceptos($datos['id'])[0] : [],
            'alternativas' => ($datos['id'] > 0) ? $this->DB->getAlternativasByConcepto($datos['id']) : [],
        ];

        return [
            'html' => parent::getCI()->load->view('Comprobacion/Formularios/AgregarEditarConcepto', $datos, TRUE)
        ];
    }

    public function agregarConcepto(array $datos) {
        return $this->DB->guardarConcepto($datos);
    }

    public function getTiposComprobante() {
        return $this->DB->getTiposComprobante();
    }

    public function getUsuarios() {
        return $this->DB->getUsuarios();
    }

    public function getSucursales() {
        return $this->DB->getSucursales();
    }

    public function formularioAgregarFondoFijo(array $datos) {
        $data = [
            'usuarios' => $this->getUsuarios(),
            'generales' => ($datos['id'] != 0) ? $this->DB->getFondosFijos($datos['id'])[0] : []
        ];
        return [
            'formulario' => parent::getCI()->load->view('Comprobacion/Formularios/AgregarEditarFondoFijo', $data, TRUE)
        ];
    }

    public function agregarFondoFijo(array $datos) {
        return $this->DB->guardarFondoFijo($datos);
    }

    public function inhabilitarFF(array $datos) {
        return $this->DB->habInhabFF($datos, 0);
    }    

    public function habilitarFF(array $datos) {
        return $this->DB->habInhabFF($datos, 1);
    }
    
    public function inhabilitarConcepto(array $datos) {
        return $this->DB->habInhabConcepto($datos, 0);
    }
    
    public function habilitarConcepto(array $datos) {
        return $this->DB->habInhabConcepto($datos, 1);
    }
}
