<?php

use Controladores\Controller_Base as Base;

/*
 * Clase encargada de llevar los procesos para el acceso al sistema y el 
 * arranque del mismo.
 */

class Controller_Acceso extends Base {

    private $correo;
    private $ServiciosTicket;

    public function __construct() {
        parent::__construct();
        $this->correo = \Librerias\Generales\Correo::factory();
        $this->ServiciosTicket = \Librerias\Generales\ServiciosTicket::factory();
        $this->load->library('session');
    }

    /*
     * Se encarga de mostrar la pagina login cuando accesa por primera vez
     * al sistema el usuario.
     */

    public function index() {
//        $this->ServiciosTicket->concluirServicioSolicitudTicket();
        $usuario = $this->usuario->getDatosUsuario();
        if (isset($usuario['Id'])) {
            $urlBase = 'http://' . $_SERVER['HTTP_HOST'];
            $url = $this->usuario->getUrlPerfil($usuario['Permisos']);
            redirect($urlBase . $url);
        } else {
            $data['title'] = "Login";
            $this->load->view('Acceso/Login', $data);
        }
    }

    /*
     * Se encarga se recivir eventos ajax de la vista
     * 
     * @param string $evento recibe el tipo de evento
     * @return json regresa una repuesta de tipo json.
     */

    public function manejarEvento(string $evento = null) {
        switch ($evento) {
            case 'Ingresar':
                $resultado = $this->usuario->validarUsuario(trim($this->input->post('usuario')), trim($this->input->post('password')), $this->input->ip_address());
                break;
            case "Actualizar_Logueo":
                $resultado = $this->usuario->registroSalida(trim($this->input->post('logueo')));
                break;
            case "Recuperar_Acceso":
                $resultado = $this->correo->validarCorreo(trim($this->input->post('email')));
                break;
            case "Modificar_Password":
                $resultado = $this->usuario->actualizaPassword(array('password' => trim($this->input->post('nuevo')), 'usuario' => trim($this->input->post('usuario')), 'id' => trim($this->input->post('id'))));
                break;
            default:
                $resultado = FALSE;
                break;
        }
        echo json_encode($resultado);
    }

    /*
     * Se encarga de valida si la liga esta vigente para que el 
     * usuario defina un nuevo password 
     */

    public function nuevoPassword() {
        $clave = $this->input->get('id');
        $respuesta = $this->correo->validarLiga(trim($clave));
        $this->desplegarPantalla($respuesta['pagina'], $respuesta);
    }

    /*
     * Se encarga de cerrar la sesion del usuario en el sistema y lo direcciona
     * a la pagina de login
     */

    public function cerrarSesion() {
        $usuario = $this->usuario->getDatosUsuario();
        if (isset($usuario['Id'])) {
            if ($this->usuario->registroSalida()) {
                if ($this->usuario->eliminarUsuario()) {
                    $this->index();
                } else {
                    echo json_encode('No se elimino el usuario');
                }
            } else {
                echo json_encode('No se actualizo en la base de datos el registro del usuario');
            }
        } else {
            $this->index();
        }
    }

}
