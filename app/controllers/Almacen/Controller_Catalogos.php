<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Catalogos extends Base {

    private $catalogo;
    private $catalogosAlmacen;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->catalogosAlmacen = \Librerias\Almacen\Catalogos::factory();
    }

    /*
     * Se encarga se recibir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'MostrarFormularioLinea':
                $resultado = $this->catalogosAlmacen->mostrarFormularioLinea($this->input->post());
                break;
            case 'NuevaLinea':
                $resultado = $this->catalogo->catLineasEquipo('1', array($this->input->post('nombre'), $this->input->post('descripcion')));
                break;
            case 'ActualizarLinea':
                $resultado = $this->catalogo->catLineasEquipo('2', array($this->input->post('id'), $this->input->post('nombre'), $this->input->post('descripcion'), $this->input->post('estatus')));
                break;
            case 'MostrarFormularioSublinea':
                $resultado = $this->catalogosAlmacen->mostrarFormularioSublinea($this->catalogo->catLineasEquipo('3', array('Flag' => '1')));
                break;
            case 'NuevaSublinea':
                $resultado = $this->catalogo->catSublineasEquipo('1', $this->input->post());
                break;
            case 'MostrarFormularioEditarSublinea':
                $resultado = $this->catalogosAlmacen->mostrarFormularioEditarSublinea(array($this->input->post(), $this->catalogo->catLineasEquipo('3', array('Flag' => '1'))));
                break;
            case 'ActualizarSublinea':
                $resultado = $this->catalogo->catSublineasEquipo('2', $this->input->post());
                break;
            case 'MostrarFormularioMarca':
                $resultado = $this->catalogosAlmacen->mostrarFormularioMarca();
                break;
            case 'NuevaMarca':
                $resultado = $this->catalogo->catMarcasEquipo('1', $this->input->post());
                break;
            case 'MostrarFormularioEditarMarca':
                $resultado = $this->catalogosAlmacen->mostrarFormularioEditarMarca($this->input->post());
                break;
            case 'ActualizarMarca':
                $resultado = $this->catalogo->catMarcasEquipo('2', $this->input->post());
                break;
            case 'MostrarFormularioModelo':
                $resultado = $this->catalogosAlmacen->mostrarFormularioModelo();
                break;
            case 'NuevoModelo':
                $resultado = $this->catalogo->catModelosEquipo('1', $this->input->post());
                break;
            case 'MostrarFormularioEditarModelo':
                $resultado = $this->catalogosAlmacen->mostrarFormularioEditarModelo($this->input->post());
                break;
            case 'ActualizarModelo':
                $resultado = $this->catalogo->catModelosEquipo('2', $this->input->post());
                break;
            case 'MostrarFormularioComponente':
                $resultado = $this->catalogosAlmacen->mostrarFormularioComponente();
                break;
            case 'NuevoComponente':
                $resultado = $this->catalogo->catComponentesEquipo('1', $this->input->post());
                break;
            case 'MostrarFormularioEditarComponente':
                $resultado = $this->catalogosAlmacen->mostrarFormularioEditarComponente($this->input->post());
                break;
            case 'ActualizarComponente':
                $resultado = $this->catalogo->catComponentesEquipo('2', $this->input->post());
                break;
            case 'MostrarFormularioAlmacen':
                $resultado = $this->catalogosAlmacen->mostrarFormularioAlmacen($this->input->post());
                break;
            case 'NuevoAlmacen':
                $resultado = $this->catalogo->catAlmacenesVirtuales('1', array($this->input->post('nombre'), $this->input->post('responsable')));
                break;
            case 'MostrarEditarAlmacen':
                $resultado = $this->catalogosAlmacen->mostrarEditarAlmacen($this->input->post());
                break;
            case 'ActualizarAlmacen':
                $resultado = $this->catalogo->catLineasEquipo('2', array($this->input->post('id'), $this->input->post('nombre'), $this->input->post('descripcion'), $this->input->post('estatus')));
                break;
            case 'MostrarAlamacenVirtual':
                $resultado = $this->catalogosAlmacen->mostrarAlamacenVirtual($this->input->post());
                break;
            case 'AgregarProductoPoliza':
                $resultado = $this->catalogosAlmacen->mostrarFormularioProductoPoliza();
                break;
            case 'CargaComponentesPoliza':
                $resultado = $this->catalogosAlmacen->cargaComponentesPoliza($this->input->post());
                break;
            case 'GuardarProductosInventario':
                $resultado = $this->catalogosAlmacen->guardarProductosInventario($this->input->post());
                break;
            case 'AgregarProductoSalas':
                $resultado = $this->catalogosAlmacen->mostrarFormularioProductoSalas();
                break;
            case 'CargaSubelementosSalas4D':
                $resultado = $this->catalogosAlmacen->cargaSubelementosSalas4D($this->input->post());
                break;
            case 'AgregarProductoSAE':
                $resultado = $this->catalogosAlmacen->mostrarFormularioProductoSAE();
                break;
            case 'MostrarFormularioTraspaso':
                $resultado = $this->catalogosAlmacen->mostrarFormularioTraspaso();
                break;
            case 'MostrarProductosTraspaso':
                $resultado = $this->catalogosAlmacen->mostrarProductosTraspaso($this->input->post());
                break;
            case 'TraspasarProductos':
                $resultado = $this->catalogosAlmacen->traspasarProductos($this->input->post());
                break;
            case 'MostrarTraspasos':
                $resultado = $this->catalogosAlmacen->mostrarTraspasos();
                break;
            case 'ImprimirTraspaso':
                $resultado = $this->catalogosAlmacen->imprimirTraspaso($this->input->post());
                break;
            case 'NuevaAltaInicial':
                $resultado = $this->catalogosAlmacen->nuevaAltaInicial($this->input->post());
                break;
            case 'CerrarAltaInicial':
                $resultado = $this->catalogosAlmacen->cerrarAltaInicial();
                break;
            case 'MostrarAltasIniciales':
                $resultado = $this->catalogosAlmacen->mostrarAltasIniciales();
                break;
            case 'ImprimirAltaInicial':
                $resultado = $this->catalogosAlmacen->imprimirAltaInicial($this->input->post());
                break;
            case 'MostrarKitsEquipos':
                $resultado = $this->catalogosAlmacen->mostrarKitsEquipos();
                break;
            case 'MostrarComponentesEquipoKit':
                $resultado = $this->catalogosAlmacen->mostrarComponentesEquipoKit($this->input->post());
                break;
            case 'GuardarKit':
                $resultado = $this->catalogosAlmacen->guardarKit($this->input->post());
                break;
            case 'MostrarDeshuesarEquipo':
                $resultado = $this->catalogosAlmacen->mostrarDeshuesarEquipo();
                break;
            case 'MostrarComponentesDeshueso':
                $resultado = $this->catalogosAlmacen->mostrarComponentesDeshueso($this->input->post());
                break;
            case 'GuardarComponentesDeshueso':
                $resultado = $this->catalogosAlmacen->guardarComponentesDeshueso($this->input->post());
                break;
            case 'FiltrarMovimientosInventario':
                $resultado = $this->catalogosAlmacen->filtrarMovimientosInventario($this->input->post());
                break;
            case 'RevisaSeriesDuplicadas':
                $resultado = $this->catalogosAlmacen->revisaSeriesDuplicadas($this->input->post());
                break;
            case 'MostrarFormularioHistorialEquipo':
                $resultado = $this->catalogosAlmacen->mostrarFormularioHistorialEquipo();
                break;
            case 'MostrarHistorialEquipo':
                $resultado = $this->catalogosAlmacen->mostrarHistorialEquipo($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
