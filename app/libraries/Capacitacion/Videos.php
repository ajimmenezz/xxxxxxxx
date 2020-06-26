<?php

namespace Librerias\Capacitacion;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Videos
 *
 * @author asus
 */
class Videos extends General {

    private $DBV;
    private $Notificacion;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBV = \Modelos\Modelo_Videos::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();        
    }
    
    /*
     * Metodo para mostrar la lista de videos de la capacitación
     * 
     * @param array $datos con el id de la capacitacion y filtrar los videos
     * @return array en forma de html
     */
    public function cargaVideos(array $datos) {
        $data = array();
        $data['respuesta'] = $this->DBV->cargaVideosCapacitacion($datos['id']);
        $data['listaVideos'] = parent::getCI()->load->view('Capacitacion/Modal/Lista_Videos',$data,true);
        
        return $data;
    }
    

    public function minutaNueva(array $datos) {
        $archivos = null;
        $data = array();
        $array = array();
        $CI = parent::getCI();
        $fechaCreacion = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $hora = mdate('%H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $array = explode(",", $datos['miembros']);
        $numeroMinuta = $this->DBM->setMinuta(array(
            'IdUsuario' => $usuario['Id'],
            'fechaCreacion' => $fechaCreacion,
            'nombre' => $datos['nombre'],
            'fecha' => $datos['fecha'] . ' ' . $hora,
            'ubicacion' => $datos['ubicacion'],
            'miembros' => $datos['miembros'],
            'descripcion' => $datos['descripcion']
        ));
        $carpeta = 'minutas/' . $numeroMinuta . '/';

        $archivos = setMultiplesArchivos($CI, 'evidenciasMinuta', $carpeta);
        if ($archivos) {
            $archivos = implode(',', $archivos);
            $this->DBM->insertarArchivos(array(
                'Archivo' => $archivos
                    ), array('Id' => $numeroMinuta)
            );
            foreach ($array as $key => $value) {
                $datosUsuario = $this->DBM->getDatosUsuario($value);
                $data['departamento'] = $usuario['IdDepartamento'];
                $data['remitente'] = $usuario['Id'];
                $data['destintario'] = $value;
                $data['tipo'] = $datos['tipo'];
                $data['descripcion'] = $datos['descripcion'];
                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nueva Notificación ', 'El usuario <b>' . $usuario['Nombre'] . '</b> levantó una nueva minuta llamada: <b class="f-s-16">' . $datos['nombre'] . '</b>.<br><br>
                             Con la siguiente descripción: <p><b>' . $datos['descripcion'] . '</b> </p>.', $datosUsuario
                );
            }
            return $datos['nombre'];
        } else {
            return FALSE;
        }
    }

    public function mostrarMinutas() {
        $usuario = $this->Usuario->getDatosUsuario();
        $IdUsuario = $usuario['Id'];
        return $this->DBM->getMinutas($IdUsuario);
    }

    /*
     * Metodo para mostrar el html del seguimiento de la minuta
     * 
     * @param array $datos que el id para poder hacer los pedidos al BD dependiendo el Id
     * @return array en forma de html
     */

    public function modalActualizarMinuta(array $datos) {
        $data = array();
        $data['archivosMinutas'] = $this->mostrarArchivosMinutas($datos['id']);
        $data['miembros'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'));
        $data['archivo'] = $this->DBM->mostrarNombreArchivo($datos['id']);
        return array('formulario' => parent::getCI()->load->view('Generales/Modal/ActualizarMinutaResumen', $data, TRUE), 'datos' => $data);
    }

    /*
     * Metodo para mostrar la tabla t_archivo_minuta
     * 
     * @param string $Id lo recibe poder visualizar los datos
     * @return array que es la consulta a la tabla t_archivo_minuta.
     */

    public function mostrarArchivosMinutas(string $Id) {
        return $this->DBM->getArchivosMinutas($Id);
    }

    /*
     * Metodo para insertar un archivo nuevo en la tabla t_archivo_minuta
     * 
     * @param array $datos recibe el nombre de la tabla en BD
     * @return array que es la consulta a la tabla t_archivo_minuta.
     */

    public function archivoNuevo(array $datos) {
        $archivos = null;
        $usuarios = array();
        $CI = parent::getCI();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $carpeta = 'minutas/' . $datos['id'] . '/';
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciasActualizarMinuta', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
                $consulta = $this->DBM->setArchivoNuevo(array(
                    'IdMinuta' => $datos['id'],
                    'IdUsuario' => $usuario['Id'],
                    'Fecha' => $fecha,
                    'Archivo' => $archivos
                ));
                if (isset($consulta)) {
                    $usuarios = explode(",", $datos['usuarios']);
                    foreach ($usuarios as $key => $value) {
                        $datosUsuario = $this->DBM->getDatosUsuario($value);
                        $data['departamento'] = $usuario['IdDepartamento'];
                        $data['remitente'] = $usuario['Id'];
                        $data['destintario'] = $value;
                        $data['tipo'] = $datos['tipo'];
                        $data['descripcion'] = 'Se agrego otro archivo a la minuta ' . $datos['nombre'];
                        $this->Notificacion->setNuevaNotificacion(
                                $data, 'Nueva Notificación ', 'El usuario <b>' . $usuario['Nombre'] . '</b> ha subido otro archivo a la minuta: <b class="f-s-16">' . $datos['nombre'] . '</b>.', $datosUsuario
                        );
                    }
                    return $this->mostrarArchivosMinutas($datos['id']);
                } else {
                    return parent::tipoError();
                }
            } else {
                return FALSE;
            }
        }
    }

}
