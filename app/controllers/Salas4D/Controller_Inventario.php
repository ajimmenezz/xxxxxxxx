<?php

use Controladores\Controller_Base as Base;

class Controller_Inventario extends Base {

    private $inventarios;

    public function __construct() {
        parent::__construct();
        $this->inventarios = \Librerias\Salas4D\Inventario::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'InventarioSucursal':
                $resultado = $this->inventarios->mostrarInventarioSucursal($this->input->post());
                break;
            case 'FormularioAgregarElemento':
                $resultado = $this->inventarios->mostrarFormularioAgregarElemento($this->input->post());
                break;
            case 'GuardaElementos':
                $resultado = $this->inventarios->guardaElementos($this->input->post());
                break;
            case 'GuardaSublementos':
                $resultado = $this->inventarios->guardaSubelementos($this->input->post());
                break;
            case 'GuardaElementosFoto':
                $resultado = $this->inventarios->guardaElementosFoto($this->input->post());
                break;
            case 'GuardaSubelementoFoto':
                $resultado = $this->inventarios->guardaSubelementosFoto($this->input->post());
                break;
            case 'FormularioAgregarSubelementos':
                $resultado = $this->inventarios->mostrarFormularioAgregarSubelementos($this->input->post());
                break;
            case 'ListaSubelementos':
                $resultado = $this->inventarios->mostrarListaSubelementos($this->input->post());
                break;
            case 'CargaInfoElemento':
                $resultado = $this->inventarios->cargaInfoElemento($this->input->post());
                break;
            case 'CargaInfoSublemento':
                $resultado = $this->inventarios->cargaInfoSubelemento($this->input->post());
                break;
            case 'EliminarSubelemento':
                $resultado = $this->inventarios->eliminarSubelemento($this->input->post());
                break;
            case 'EliminarArchivoElemento':
                $resultado = $this->inventarios->eliminarArchivoElemento($this->input->post());
                break;
            case 'GuardaCambiosElemento':
                $resultado = $this->inventarios->guardaCambiosElemento($this->input->post());
                break;
            case 'EliminarElemento':
                $resultado = $this->inventarios->eliminarElemento($this->input->post());
                break;
            case 'VersionImprimible':
                $resultado = $this->inventarios->crearReporte($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
