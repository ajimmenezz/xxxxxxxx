<?php

namespace Controladores;

use CI_Controller;

/*
 * Clase que genera la funciones necesarias para todos los controladores
 */

abstract class Controller_Base extends CI_Controller {

    protected $SECCIONES;
    protected $usuario;

    public function __construct() {
        parent::__construct();
        //Limpia la el cache del pagina
        $this->output->set_header('HTTP/1.0 200 OK');
        $this->output->set_header('HTTP/1.1 200 OK');
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->SECCIONES = \Librerias\Generales\Secciones::factory();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->load->helper('url');
        $this->config->load('Carpetas_config');
    }

    /*
     * Metodo que define los eventos que se van a recibir por ajax
     */

    abstract function manejarEvento(string $evento = null);

    /*
     * Se encarga de mostrar la pagina solicitada.
     * 
     * @param string $page recibe el nombre de la pagina solicitada
     * @param array  $datos recibe un array con datos para la pagina si no envian los datos se define como null.
     */

    public function desplegarPantalla(string $page = null, array $datos = null) {
        $data = array();
        $carpeta = null;
        $usuario = $this->usuario->getDatosUsuario();
        $url = explode('/', uri_string());
        if (isset($usuario['IdPerfil'])) {
            if (array_key_exists($url[0], $this->config->item('Secciones'))) {
                $carpeta = uri_string();                
                $this->usuario->validarSession();
                foreach ($this->config->item('Secciones') as $key => $value) {
                    if ($key === $url[0]) {
                        if (in_array($page, $value)) {
                            foreach ($value as $llave => $valor) {
                                if ($page === $valor) {
                                    $data['librerias'] = $llave;
                                }
                            }
                            if ($this->usuario->validarAccesoPagina($data['librerias'])) {
                                $data['title'] = strtoupper($page);
                                $data['menu'] = $this->SECCIONES->getSecciones($usuario);
                                $data['notificaciones'] = $this->SECCIONES->getNotificaciones($usuario['Id']);
                                $data['datos'] = $this->SECCIONES->getDatosPagina($carpeta);
                                $data['usuario'] = $usuario;
                                $data['fechaServidor'] = $this->getFecha();
                                $data['horaServidor'] = $this->getHora();
                                $this->load->view('Plantillas/Cabecera', $data);
                                $this->load->view('Plantillas/Menu', $data);                                
                                $this->load->view($carpeta, $data);
                                $this->load->view('Plantillas/Pie');
                            } else {
                                $this->mostrarError('601', 'Sin acceso', 'No tienes permisos para ingresar a esta pagina.');
                            }
                        } else {
                            //Si no esta definida la pagina muestra esta sección
                            $this->mostrarError('404', 'Recurso no encontrado', 'No se encuentra la pagina que esta solicitando favor de validar la url');
                        }
                    }
                }
            } else {
                //Si no esta definida la pagina muestra esta sección
                $this->mostrarError('404', 'Recurso no encontrado', 'No se encuentra la pagina que esta solicitando favor de validar la url');
            }
        } elseif ($page === 'Recuperar_Acceso') {
            $carpeta = 'Acceso/Recuperar_Acceso';
            $data['titulo'] = strtoupper($page);
            $data['datos'] = $datos;
            $this->load->view($carpeta, $data);
        } elseif ($page === 'Nuevo_Password') {
            $carpeta = 'Acceso/Nuevo_Password';
            $data['titulo'] = strtoupper($page);
            $data['datos'] = $datos;
            $this->load->view($carpeta, $data);
        } elseif ($page === 'Error_Clave') {
            $this->mostrarError('409', 'Error para validar clave', 'No se puede validar la clave de recuperación, favor de reportarlo al área de sistemas');
        } else {
            $this->mostrarError('408', 'Sessión Caducada', 'Su session a terminado de forma automatica por falta de interactividad. Favor de volver a loguearse');
        }
    }

    /*
     * Generera la fecha del servidor
     * 
     * @return string regresa el formato de la fecha
     */

    private function getFecha() {
        //se define zona horaria
        date_default_timezone_set('America/Mexico_City');

        //establece la localizacion
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');

        //fecha 
        $dia = date('d');
        $diaSemana = $dias[date('w')];
        $mes = $meses[date('n') - 1];
        $año = date('Y');
        $fecha = "$diaSemana, $dia de $mes del $año";
        return $fecha;
    }

    /*
     * Genera la hora del servidor
     * 
     * @return string Regresa el formato de la hora actual del servidor
     */

    private function getHora() {
        //se define zona horaria
        date_default_timezone_set('America/Mexico_City');

        //Se manda hora actual del servidor
        $horaActual = date("d M Y G:i:s");
        return $horaActual;
    }

    /*
     * Muestra el error  de la pagina
     */

    private function mostrarError(string $nuemeroError, string $titulo = null, string $descripcion = null) {
        $data = array();
        $carpeta = 'errors/personalizado/error_general';
        $data['title'] = strtoupper($nuemeroError);
        $data['datos'] = array(
            'clave' => $nuemeroError,
            'titulo' => $titulo,
            'descripcion' => $descripcion
        );
        $this->load->view($carpeta, $data);
    }

}
