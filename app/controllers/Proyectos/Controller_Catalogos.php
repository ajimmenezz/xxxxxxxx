<?php

use Controladores\Controller_Base as Base;

class Controller_Catalogos extends Base {

    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->catalogo = new \Librerias\Proyectos\Catalogos();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Nuevo_Tipo':
                $resultado = $this->catalogo->setTipoProyecto($this->input->post('tipo'),$this->input->post('descripcion'));
                break;
            case 'Actualizar_Tipo':
                $resultado = $this->catalogo->actualizarTipoProyecto($this->input->post());
                break;
            case 'Obtener_Tipo':
                $resultado = $this->catalogo->getTipoProyecto();
                break;
            case 'Nueva_Tarea':
                $resultado = $this->catalogo->setTareaProyecto($this->input->post('tarea'),$this->input->post('tipo'));
                break;
            case 'Actualizar_Tarea':
                $resultado = $this->catalogo->actualizarTarea($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
