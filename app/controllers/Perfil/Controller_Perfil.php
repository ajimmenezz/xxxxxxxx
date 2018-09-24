<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Perfil extends Base {

    private $perfil;

//    private $ServiciosTicket;

    public function __construct() {
        parent::__construct();
//        $this->correo = \Librerias\Generales\Correo::factory();
        $this->perfil = \Librerias\Generales\Usuario::factory();
//        $this->load->library('session');
    }

    /*
     * Se encarga de mostrar la pagina login cuando accesa por primera vez
     * al sistema el usuario.
     */

    public function index() {
        
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'ActualizarPerfilUsuario':
                $resultado = $this->perfil->actualizarPerfilUsuario($this->input->post());
                break;
            case 'ActualizarFotoUsuario':
                $resultado = $this->perfil->actualizarFotoUsuario($this->input->post());
                break;
            case 'MostrarFormularioPerfilUsuario':
                $resultado = $this->perfil->mostrarFormularioPerfilUsuario($this->input->post());
                break;
            case 'MostrarFormularioCambiarFoto':
                $resultado = $this->perfil->mostrarFormularioCambiarFoto($this->input->post());
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
