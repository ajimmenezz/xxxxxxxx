<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Minuta
 *
 * @author Alberto
 */
class Minuta extends General {

    private $DBM;
    private $Notificacion;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBM = \Modelos\Modelo_Minuta::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /*
     * Encargada de generar una nueva minuta
     *  $datos = datos para insertar en la BD
     */

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
            return $this->mostrarMinutas();
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
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();
        if (!empty($datos['id'])) {
            $data['idUsuario'] = $usuario['Id'];
            $data['archivosMinutas'] = $this->mostrarArchivosMinutas($datos['id']);
            $archivos = $this->DBM->mostrarNombreArchivo($datos['id']);
            $data['archivo'] = explode(',', $archivos[0]['Archivo']);
        }
        $data['miembros'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'));
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
        $nombreAA = $this->DBM->verificarNombreAA($datos['nombreArchivoAdicional'], $datos['id']);
        if ($nombreAA === false) {
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
                        'Nombre' => $datos['nombreArchivoAdicional'],
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
        } else {
            return 'repetido';
        }
    }

    /*
     * Metodo para solo para editar estatus del historial de minutas
     * 
     * @param array $datos recibe los datos para actualizar el estatus(flag)
     * @return array devuelve una array con los valores de la consulta en caso de error un mensajes del tipo de error.
     */

    public function cambiarEstatusMinuta(array $dato) {
        $verificacion = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $verificacion = $this->DBM->getEstatus(array('id' => $dato['id'], 'usuario' => $usuario['Id']));
        if ($verificacion) {
            $consulta = $this->DBM->actualizarEstatus(array('Flag' => '0'), array('Id' => $dato['id']));
            if (!empty($consulta)) {
                return $this->mostrarArchivosMinutas($dato['idMinuta']);
            } else {
                return 'error';
            }
        } else {
            return 'sinPermiso';
        }
    }

    /*
     * Metodo para solo para editar los miembros de la minuta y la minuta original
     * 
     * @param array $datos recibe los datos para poder actualizar la minuta
     * @return array devuelve un true y en caso de error un false.
     */

    public function actualizarMinuta(array $datos) {
        $CI = parent::getCI();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datosMinuta = $this->DBM->getDatosMinuta($datos['id']);
        $miembros = $datos['miembros'];
        $nuevoNombreArchivo = '';
        if (!empty($_FILES)) {
            $carpeta = 'minutas/' . $datos['id'] . '/';
            eliminarArchivo($datos['minutaAnterior']);
            $archivos = setMultiplesArchivos($CI, 'actualizaEvidencia', $carpeta);
            $descripcion = 'se cambio el archivo original';
            if (!empty($archivos)) {
                $archivos = implode(',', $archivos);
                $consulta = $this->DBM->actualizarMinuta(array(
                    'Archivo' => $archivos), array('Id' => $datos['id'])
                );
                $nuevoNombreArchivo = $archivos;
            } else {
                return array('actualizacion' => FALSE);
            }
        } else {
            $descripcion = 'agrego o quito uno o más miembros';
        }
        if (gettype($miembros) !== 'string') {
            $miembros = implode(',', $miembros);
        }
        $consulta = $this->DBM->actualizarMinuta(array('Miembros' => $miembros), array('Id' => $datos['id']));
        if (!empty($consulta)) {
            $miembros = explode(',', $miembros);
            foreach ($miembros as $key => $value) {
                $datosUsuario = $this->DBM->getDatosUsuario($value);
                $data['departamento'] = $usuario['IdDepartamento'];
                $data['remitente'] = $usuario['Id'];
                $data['destintario'] = $value;
                $data['tipo'] = $datos['tipo'];
                $data['descripcion'] = $descripcion;
                $this->Notificacion->setNuevaNotificacion(
                        $data, 'Nueva Notificación ', 'El usuario <b>' . $usuario['Nombre'] . ',</b> ' . $descripcion . ' a la Minuta: ' . $datos['nombre'], $datosUsuario
                );
            }
            return array('actualizacion' => TRUE, 'urlArchivo' => $nuevoNombreArchivo);
        } else {
            return array('actualizacion' => FALSE);
        }
    }

}
