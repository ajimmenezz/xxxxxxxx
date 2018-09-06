<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base {

    private $Servicio;
    private $catalogo;
    private $seguimientoSalasX4D;
    private $ServiciosGeneral;

    public function __construct() {
        parent::__construct();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->seguimientoSalasX4D = \Librerias\Salas4D\Seguimiento::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->ServiciosGeneral = \Librerias\Generales\Servicio::factory();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Servicio_Datos':
                $resultado = $this->Servicio->actualizarServicio($this->input->post());
                break;
            case 'Servicio_Nuevo_Modal':
                $resultado = $this->Servicio->modalServicioNuevo($this->input->post());
                break;
            case 'Servicio_Nuevo':
                $resultado = $this->Servicio->servicioNuevo($this->input->post());
                break;
            case 'Servicio_Cancelar_Modal':
                $resultado = $this->Servicio->modalServicioCancelar($this->input->post());
                break;
            case 'Servicio_Cancelar':
                $resultado = $this->Servicio->servicioCancelar($this->input->post());
                break;
            case 'Guardar_Nota_Servicio':
                $resultado = $this->notas->setNotaServicio($this->input->post());
                break;
            case 'Guardar_Datos_Generales':
                $resultado = $this->seguimientoSalasX4D->guardarDatosGeneralesSalas4xd($this->input->post());
                break;
            case 'Guardar_Mantenimiento_General':
                $resultado = $this->seguimientoSalasX4D->guardarMantenimientoGeneral($this->input->post());
                break;
            case 'ActualizaNotas':
                $resultado = $this->notas->actualizaNotas($this->input->post());
                break;
            case 'ActividadesSeguimientoMantenimientoJson':
                $resultado = $this->seguimientoSalasX4D->obtenerActividadesSeguimientoJson($this->input->post());
                break;
            case 'SitemasidDatosMantenimiento':
                $resultado = $this->seguimientoSalasX4D->obteneridservicio();
                break;
            case 'GuardarActividadSeguimiento':
                $resultado = $this->seguimientoSalasX4D->guardarX4DActividadesSeguimiento($this->input->post());
                break;
            case 'GuardarIdSeguimiento':
                $resultado = $this->seguimientoSalasX4D->guardarX4DIdSeguimiento($this->input->post());
                break;
            case 'cargarActividades':
                $resultado = $this->seguimientoSalasX4D->obtenerX4DActivdadesSeguimiento();
                break;
            case 'cargarActividadesSeguimiento':
                $resultado = $this->seguimientoSalasX4D->cargarActividadesSeguimiento($this->input->post());
                break;
            case 'Servicio_ToPdf':
                $resultado = $this->ServiciosGeneral->getServicioToPdf($this->input->post());
                break;
            case 'MostrarFormularioSeguimientoActividad':
                $resultado = $this->seguimientoSalasX4D->mostrarFormularioSeguimientoActvidad($this->input->post());
                break;
            case 'MostrarTipoProducto':
                $resultado = $this->seguimientoSalasX4D->mostrarTipoProducto($this->input->post());
                break;
            case 'ElementosSeguimientoActividad':
                $resultado = $this->seguimientoSalasX4D->elementosSeguimientoActividad($this->input->post());
                break;
            case 'SubelementosSeguimientoActividad':
                $resultado = $this->seguimientoSalasX4D->subelementosSeguimientoActividad($this->input->post());
                break;
            case 'SelectTiposProductos':
                $resultado = $this->seguimientoSalasX4D->tipoProductos($this->input->post());
                break;
            case 'VerificarSucursal':
                $resultado = $this->seguimientoSalasX4D->verificarSucursal($this->input->post());
                break;
            case 'ConcluirActividad':
                $resultado = $this->seguimientoSalasX4D->concluirActividad($this->input->post());
                break;
            case 'ActualizaEstatus':
                $resultado = $this->seguimientoSalasX4D->ActualizaEstatus($this->input->post());
                break;
            case 'InformeActividades':
                $resultado = $this->seguimientoSalasX4D->informacionActividades($this->input->post());
                break;
            case 'InformeProducto':
                $resultado = $this->seguimientoSalasX4D->informeProducto($this->input->post());
                break;
            case 'concluirServicoFirma':
                $resultado = $this->seguimientoSalasX4D->concluirServicioFirma($this->input->post());
                break;
            case 'MostrarElementosSucursal':
                $resultado = $this->seguimientoSalasX4D->getElementos($this->input->post());
                break;
            case 'GuardarServicioCorrectivo':
                $resultado = $this->seguimientoSalasX4D->insertarMantenimientoCorrectivo($this->input->post());
                break;
            case 'EditarServicioCorrectivo':
                $resultado = $this->seguimientoSalasX4D->editarMantenimientoCorrectivo($this->input->post());
                break;
            case 'MostrarTipoProductoAlmacen':
                $resultado = $this->seguimientoSalasX4D->mostrarProductoAlmacen($this->input->post());
                break;
            case 'MostrarSubelementoCorrectivo':
                $resultado = $this->seguimientoSalasX4D->getSubelementosByRegistro($this->input->post());
                break;
            case 'GuardarMantenimientoCorrectivo':
                $resultado = $this->seguimientoSalasX4D->insertarArchivoCorrectivo($this->input->post());
                break;
            case 'MostrarSolucionCorrectivo4D':
                $resultado = $this->seguimientoSalasX4D->mostrarSolcuionCorrectivo($this->input->post());
                break;
            case 'EliminarEvidencia':
                $resultado = $this->seguimientoSalasX4D->eliminarEvidencia($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
