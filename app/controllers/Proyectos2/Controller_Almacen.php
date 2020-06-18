<?php

use Controladores\Controller_Base as Base;

class Controller_Almacen extends Base {

    private $planeacion;

    public function __construct() {
        parent::__construct();
        $this->planeacion = new \Librerias\Proyectos2\Planeacion();
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'FormularioDetallesProyectoAlmacen':
                $resultado = $this->planeacion->formularioDetallesProyectoAlmacen($this->input->post());
                break;
            case 'AsignarAlmacenVirtual':
                $resultado = $this->planeacion->asignarAlmacenVirtual($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
