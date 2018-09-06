<?php

use Controladores\Controller_Base as Base;

class Controller_Catalogos extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = new \Librerias\Proyectos2\Catalogos();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'AgregarSistema':
                $resultado = $this->catalogo->agregarSistema($this->input->post());
                break;
            case 'FormularioEditarSistema':
                $resultado = $this->catalogo->formularioEditarSistema($this->input->post());
                break;
            case 'EditarSistema':
                $resultado = $this->catalogo->editarSistema($this->input->post());
                break;
            case 'AgregarTipo':
                $resultado = $this->catalogo->agregarTipo($this->input->post());
                break;
            case 'FormularioEditarTipo':
                $resultado = $this->catalogo->formularioEditarTipo($this->input->post());
                break;
            case 'EditarTipo':
                $resultado = $this->catalogo->editarTipo($this->input->post());
                break;
            case 'FormularioAgregarConcepto':
                $resultado = $this->catalogo->formularioAgregarConcepto();
                break;
            case 'AgregarConcepto':
                $resultado = $this->catalogo->agregarConcepto($this->input->post());
                break;
            case 'FormularioEditarConcepto':
                $resultado = $this->catalogo->formularioEditarConcepto($this->input->post());
                break;
            case 'EditarConcepto':
                $resultado = $this->catalogo->editarConcepto($this->input->post());
                break;
            case 'FormularioAgregarArea':
                $resultado = $this->catalogo->formularioAgregarArea();
                break;
            case 'AgregarArea':
                $resultado = $this->catalogo->agregarArea($this->input->post());
                break;
            case 'FormularioEditarArea':
                $resultado = $this->catalogo->formularioEditarArea($this->input->post());
                break;
            case 'EditarArea':
                $resultado = $this->catalogo->editarArea($this->input->post());
                break;
            case 'FormularioAgregarUbicacion':
                $resultado = $this->catalogo->formularioAgregarUbicacion();
                break;
            case 'AgregarUbicacion':
                $resultado = $this->catalogo->agregarUbicacion($this->input->post());
                break;
            case 'FormularioEditarUbicacion':
                $resultado = $this->catalogo->formularioEditarUbicacion($this->input->post());
                break;
            case 'EditarUbicacion':
                $resultado = $this->catalogo->editarUbicacion($this->input->post());
                break;
            case 'FormularioAgregarAccesorio':
                $resultado = $this->catalogo->formularioAgregarAccesorio();
                break;
            case 'AgregarAccesorio':
                $resultado = $this->catalogo->agregarAccesorio($this->input->post());
                break;
            case 'FormularioEditarAccesorio':
                $resultado = $this->catalogo->formularioEditarAccesorio($this->input->post());
                break;            
            case 'EditarAccesorio':
                $resultado = $this->catalogo->editarAccesorio($this->input->post());
                break;
            case 'FormularioAgregarMaterial':
                $resultado = $this->catalogo->formularioAgregarMaterial();
                break;
            case 'AgregarMaterial':
                $resultado = $this->catalogo->agregarMaterial($this->input->post());
                break;            
            case 'FormularioEditarMaterial':
                $resultado = $this->catalogo->formularioEditarMaterial($this->input->post());
                break;
            case 'EditarMaterial':
                $resultado = $this->catalogo->editarMaterial($this->input->post());
                break;
            case 'FormularioAgregarKit':
                $resultado = $this->catalogo->formularioAgregarKit();
                break;
            case 'AgregarEditarKit':
                $resultado = $this->catalogo->agregarEditarKit($this->input->post());
                break;
            case 'FormularioEditarKit':
                $resultado = $this->catalogo->formularioEditarKit($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
