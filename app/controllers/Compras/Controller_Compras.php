<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Compras extends Base
{

    private $catalogo;
    private $compras;

    public function __construct()
    {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->compras = \Librerias\Compras\Compras::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null)
    {
        switch ($evento) {
            case 'MostrarDatosSucursales':
                $resultado = $this->compras->mostrarDatosProyectos($this->input->post());
                break;
            case 'MostrarDatosBeneficiarios':
                $resultado = $this->compras->mostrarDatosBeneficiarios($this->input->post());
                break;
            case 'MostrarFormularioOrdenCompra':
                $resultado = $this->compras->mostrarFormularioOrdenCompra($this->input->post());
                break;
            case 'MostrarEditarOrdenCompra':
                $resultado = $this->compras->mostrarEditarOrdenCompra($this->input->post());
                break;
            case 'GuardarOrdenCompra':
                $resultado = $this->compras->guardarOrdenCompra($this->input->post());
                break;
            case 'ActulizarOrdenCompra':
                $resultado = $this->compras->actualizarOrdenCompra($this->input->post());
                break;
            case 'ConsultaListaRequisiciones':
                $resultado = $this->compras->consultaListaRequisiciones($this->input->post());
                break;
            case 'CrearPDFGastoOrdenCompra':
                $resultado = $this->compras->crearPDFGastoOrdenCompra($this->input->post());
                break;
            case 'ConsultaListaOrdenesCompra':
                $resultado = $this->compras->consultaListaOrdenesCompra($this->input->post());
                break;
            case 'ConsultaRequisicionesOrdenCompra':
                $resultado = $this->compras->consultaRequisicionesOrdenCompra($this->input->post());
                break;
            case 'ConsultaPartidasOrdenCompraAnteriores':
                $resultado = $this->compras->consultaPartidasOrdenCompraAnteriores($this->input->post());
                break;
            case 'VerificarExisteOrdenCompra':
                $resultado = $this->compras->verificarExisteOrdenCompra($this->input->post());
                break;
                //Nuevos metodos para generar solicitudes de compra., editar solicitudes de compra y autorizar
                //Solicitudes de compra           
            case 'SolicitarCompra':
                $resultado = $this->compras->solicitarCompra($this->input->post());
                break;
            case 'FormularioEditarSolicitudCompra':
                $resultado = $this->compras->formularioEditarSolicitudCompra($this->input->post());
                break;
            case 'EliminarArchivosSolicitud':
                $resultado = $this->compras->eliminarArchivosSolicitud($this->input->post());
                break;
            case 'GuardarCambiosSolicitudCompra':
                $resultado = $this->compras->guardarCambiosSolicitudCompra($this->input->post());
                break;
            case 'FormularioAutorizarSolicitudCompra':
                $resultado = $this->compras->formularioAutorizarSolicitudCompra($this->input->post());
                break;
            case 'AutorizarSolicitudCompra':
                $resultado = $this->compras->autorizarSolicitudCompra($this->input->post());
                break;
            case 'RechazarSolicitudCompra':
                $resultado = $this->compras->rechazarSolicitudCompra($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }
}
