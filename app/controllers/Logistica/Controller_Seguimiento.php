<?php

use Controladores\Controller_Base as Base;

class Controller_Seguimiento extends Base {

    private $seguimiento;
    private $Servicio;
    private $rutas;
    private $notas;
    private $ServiciosGeneral;

    public function __construct() {
        parent::__construct();
        $this->seguimiento = \Librerias\Logistica\Seguimiento::factory();
        $this->Servicio = \Librerias\Generales\ServiciosTicket::factory();
        $this->rutas = \Librerias\Logistica\Rutas::factory();
        $this->notas = \Librerias\Generales\Notas::factory();
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
            case 'Actualizar_Envio':
                $resultado = $this->seguimiento->actualizarEnvio($this->input->post());
                break;
            case 'Guardar_Evidencia':
                $resultado = $this->seguimiento->setEvidencia($this->input->post());
                break;
            case 'Eliminar_Evidencia':
                $resultado = $this->seguimiento->eliminarEvidencia($this->input->post());
                break;
            case 'VerificarExistente':
                $resultado = $this->seguimiento->verificarExistente($this->input->post());
                break;
            case 'Actualizar_Recoleccion':
                $resultado = $this->seguimiento->actualizarTraficoRecoleccion($this->input->post());
                break;
            case 'Actualizar_Servicio':
                $resultado = $this->Servicio->actualizarServicio($this->input->post());
                break;
            case 'MostrarFormularioRutas':
                $resultado = $this->rutas->mostrarFormularioRutas($this->input->post());
                break;
            case 'NuevaRuta':
                $resultado = $this->rutas->nuevaRuta($this->input->post());
                break;
            case 'DescargarFormato':
                $resultado = $this->seguimiento->descargarFormato($this->input->post());
                break;
            case 'CargarFormato':
                $resultado = $this->seguimiento->cargarFormato($this->input->post());
                break;
            case 'Generar_Recoleccion_Distribucion':
                $resultado = $this->seguimiento->setRecoleccionDistribucion($this->input->post());
                break;
            case 'Generar_Destino_Distribucion':
                $resultado = $this->seguimiento->setNuevoDestinoDistribucion($this->input->post());
                break;
            case 'Generar_Material_Destino_Distribucion':
                $resultado = $this->seguimiento->setMaterialDestinoDistribucion($this->input->post());
                break;
            case 'Obtener_Material_Distribucion':
                $resultado = $this->seguimiento->obtenerInformacionDestinoDistribucion($this->input->post());
                break;
            case 'Concluir_Destino_Distribucion':                
                $resultado = $this->seguimiento->concluirDestionoServicio($this->input->post());
                break;
            case 'Cancelar_Destino_Distribucion':                
                $resultado = $this->seguimiento->cancelarDestinoDistribucion($this->input->post());
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
            case 'EmpezarRuta':
                $resultado = $this->rutas->actualizarRuta('2', $this->input->post());
                break;
            case 'Guardar_Nota_Servicio':
                $resultado = $this->notas->setNotaServicio($this->input->post());
                break;
            case 'ActualizaNotas':
                $resultado = $this->notas->actualizaNotas($this->input->post());
                break;
            case 'Servicio_ToPdf':                          
                $resultado = $this->ServiciosGeneral->getServicioToPdf($this->input->post());
                break;
        }
        echo json_encode($resultado);
    }

}
