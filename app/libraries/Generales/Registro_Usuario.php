<?php

namespace Librerias\Generales;

use Controladores\Controller_Base_General as General;

/*
 * Clase encargada de llevar todos los procesos que se requieren del usuario.
 */

class Registro_Usuario extends General {

    private $DBRU;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBRU = \Modelos\Modelo_Registro_Usuario::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();

        parent::getCI()->config->load('Acceso_config');
        parent::getCI()->config->load('Menu_config');
        parent::getCI()->load->library('session');
        parent::getCI()->load->helper('date');
        parent::getCI()->config->load('Acceso_config');
    }

    /*
     * Se encarga de la validacion del usuario
     * Valida si el usuario existe en el sistema y si ya esta logueado. Si valida
     * que todavia no esta logueado genera su registro de logueo y generar sus
     * variables de se.
     * 
     * @param string $user recibe el nombre del usuario
     * @param string $password recibe el password
     * @param string $IP recibe la ip del usuario
     * @return array devuelve resutaltado y logueo.
     */

    public function validarUsuario(string $user = null, string $password = null, string $ip) {
        $datos = array();
        $password = md5(parent::getCI()->security->xss_clean(strip_tags($password)));
        $datos = array('Usuario' => parent::getCI()->security->xss_clean(strip_tags($user)), 'Password' => $password);
        $usuario = $this->DBRU->buscarUsuario($datos);
        var_dump('pumas0');
        var_dump($usuario);
        if (empty($usuario)) {
            var_dump($usuario);
            return array('resultado' => FALSE, 'logueo' => NULL);
        } else {
            $logueo = $this->DBRU->buscarRegistroLogueo($usuario['Id']);
            $acceso = $this->DBRU->buscarRegistroAcceso($usuario['Id']);
            parent::getCI()->session->set_userdata($usuario);
            $fechaIngreso = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $registroLogueo = $this->DBRU->generarRegistroLogueo(array('IdUsuario' => parent::getCI()->session->Id, 'FechaIngreso' => $fechaIngreso, 'DireccionIp' => $ip));
            parent::getCI()->session->set_userdata('Logueo', (string) $registroLogueo);
            $url = $this->getUrlPerfil($usuario['Permisos'], $usuario['IdPerfil']);
//            var_dump($logueo);
            if (empty($logueo)) {
                var_dump('pumas1');
                $usuarioAcceso = parent::getCI()->security->xss_clean(strip_tags($user));
                return array('resultado' => TRUE, 'logueo' => NULL, 'url' => $url, 'acceso' => $acceso, 'id' => parent::getCI()->session->Id, 'usuario' => $usuarioAcceso);
            } else {
                var_dump('pumas2');
                return array('resultado' => FALSE, 'logueo' => $logueo['id'], 'url' => $url);
            }
        }
    }

    /*
     * Este metodo define la url del usuario cuando accesa la primera vez y cuando se ingresa a la url principal sin 
     * que su sesion no caducado.
     * 
     * @param array $permisos Recibe los permisos del usuario
     * @return string Regresa la url que le corresponde al usuario.
     */

    public function getUrlPerfil(array $permisos, int $perfil = 0) {
        //Por el momento no dirije a notificaciones hasta que haya un dashboard definido pero encuanto se tenga se debe programar para 
        //que se muestre el dashboard que le tiene asignado al usuario dependiendo de su perfil.

        switch ($perfil) {
            /* Cliente Salas 4D */
            case 74:
                return 'Salas4D/Dashboard';
                break;
            /* Resto de usuarios y perfiles */
            default :
                if (count($permisos) > 1) {
                    return '/Generales/Notificaciones';
                } else {
                    return '/Configuracion/Perfil';
                }
                break;
        }



//        if (in_array($idPerfil, parent::getCI()->config->item('Acceso_General'))) {
//            $url = 'Configuracion/Perfil';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_Administrador'))) {
//            $url = 'Administrador/Dashboard';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_Proyecto'))) {
//            $url = 'Proyectos/Dashboard';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_Logistica'))) {
//            $url = 'Logistica/Dashboard';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_Almacen'))) {
//            $url = 'Almacen/Dashboard';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_RH'))) {
//            $url = 'RH/Dashboard';
//        } else if (in_array($idPerfil, parent::getCI()->config->item('Acceso_Compras'))) {
//            $url = 'Compras/Dashboard';
//        } else {
//            $url = 'Error404';
//        }
    }

    /*
     * Regresa los datos del usuario
     * @return array regresa los datos de la session
     */

    public function getDatosUsuario() {
        return parent::getCI()->session->userdata();
    }

    /*
     * Se encarga de actualizar el password del usuario. 
     * Convirtiendolo en formato MD5
     * @param array $data recibe el nuevo password, Id usuario y Id de recuperación password
     * @return boolean regresa true actualizo los registro de lo contrario false.
     */

    public function actualizaPassword(array $data) {
        $data['password'] = md5($data['password']);
        $respuesta = $this->DBRU->actualizarPassword($data);
        if (empty($respuesta)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * Se encarga de validar que la sesion del usuario existe.
     * De no existir se regresa a la pagina login.
     */

    public function validarSession() {
        if (isset(parent::getCI()->session->Id)) {
            $consulta = $this->DBRU->buscarRegistroLogueo(parent::getCI()->session->Id);
            if (empty($consulta)) {
                $this->eliminarUsuario();
                redirect('http://' . $_SERVER['HTTP_HOST']);
            } else if ($consulta['id'] !== parent::getCI()->session->Logueo) {
                $this->eliminarUsuario();
                redirect('http://' . $_SERVER['HTTP_HOST']);
            }
        } else {
            redirect('http://' . $_SERVER['HTTP_HOST']);
        }
    }

    /*
     * Se encarga de actualizar la fecha de salida del usuario 
     * 
     * @param string $data recibe el id del usuario de no recibirlo toma el valor como null
     * @return boolean regresa true si registro con exito el registro de lo contrario un false
     */

    public function registroSalida($data = NULL) {
        $fechaSalida = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        if (isset($data)) {
            $respuesta = $this->DBRU->registroSalida(array('Id' => $data, 'FechaSalida' => $fechaSalida));
        } else {
            $respuesta = $this->DBRU->registroSalida(array('IdUsuario' => parent::getCI()->session->Id, 'FechaSalida' => $fechaSalida));
        }
        return $respuesta;
    }

    /*
     * Se encarga de destruir la session del usuario.
     * 
     * @return boolean regresa true si destruyo con exito de lo contrario un false
     */

    public function eliminarUsuario() {
        parent::getCI()->session->unset_userdata('Id');
        return session_destroy();
    }

    /*
     * Se encarga de realizar validar que si la pagina que se quiere ingresar tiene 
     * permisos de acceso. 
     * 
     * @param string $pagina Recibe el nombre de la pagina que se valida
     * @return boolean Regresa un valor booleano. False si no tiene acceso y true si tiene acceso.
     */

    public function validarAccesoPagina(string $pagina) {
        $acceso = FALSE;
        $permisos = array();
        $carpeta = null;
        $usuario = $this->getDatosUsuario();
        foreach ($usuario['Permisos'] as $key => $value) {
            $catalogo = $this->Catalogo->catPermisos('3', null, array('Id' => $value));
            if (!empty($catalogo)) {
                array_push($permisos, $catalogo[0]['Permiso']);
            }
        }
        $usuario['Permisos'] = array_replace($usuario['Permisos'], $permisos);
        $modulos = parent::getCI()->config->item('Modulos');
        foreach ($modulos as $modulo => $dato) {
            foreach (parent::getCI()->config->item($modulo) as $seccion => $datos) {
                if ($seccion === $pagina) {
                    if (in_array($datos['Permiso'], $usuario['Permisos'])) {
                        $acceso = TRUE;
                    }
                }
            }
        }
        return $acceso;
    }

}
