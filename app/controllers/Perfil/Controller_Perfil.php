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
            case 'MostrarFormularioPerfilUsuario':
                $resultado = $this->perfil->mostrarFormularioPerfilUsuario($this->input->post());
                break;
            case 'ActualizarPerfilUsuario':
                $resultado = $this->perfil->actualizarPerfilUsuario($this->input->post());
                break;
//            case 'Ingresar':
//                $resultado = $this->usuario->validarUsuario(trim($this->input->post('usuario')), trim($this->input->post('password')), $this->input->ip_address());
//                break;
//            case "Actualizar_Logueo":
//                $resultado = $this->usuario->registroSalida(trim($this->input->post('logueo')));
//                break;
//            case "Recuperar_Acceso":
//                $resultado = $this->correo->validarCorreo(trim($this->input->post('email')));
//                break;
//            case "Modificar_Password":
//                $resultado = $this->usuario->actualizaPassword(array('password' => trim($this->input->post('nuevo')), 'usuario' => trim($this->input->post('usuario')), 'id' => trim($this->input->post('id'))));
//                break;
//            case "MostrarPerfil":
//                $resultado = $this->perfil->Perfil($this->input->post());
//                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

}
