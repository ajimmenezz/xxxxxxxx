<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Archivos
 *
 * @author Alberto
 */
class Archivos extends General {

    private $DBA;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBA = \Modelos\Modelo_Archivo::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    /*
     * Encargada de generar un nuevo archivo
     *  $datos = datos para insertar en la BD
     */

    public function archivoNuevoFormato(array $datos) {
        $archivos = null;
        $CI = parent::getCI();
        $fechaCreacion = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $dato = array('Nombre' => $datos['nombre']);
        $varificarArchivo = $this->DBA->verficarRepetidoArchivo($dato);
        if (!empty($_FILES)) {
            if ($varificarArchivo === false) {
                return 'existe';
            } else {
                $numeroArchivo = $this->DBA->setArchivoFormato(array(
                    'IdTipoArchivo' => $datos['tipo'],
                    'IdUsuario' => $usuario['Id'],
                    'Nombre' => $datos['nombre'],
                    'Descripcion' => $datos['descripcion'],
                    'Fecha' => $fechaCreacion,
                    'Flag' => '1'
                        ), $dato);
                $carpeta = 'archivos/' . $numeroArchivo . '/';
                $archivos = setMultiplesArchivos($CI, 'evidenciaArchivo', $carpeta);
                if ($archivos) {
                    $archivos = implode(',', $archivos);
                    $this->DBA->actualizarArchivo(array(
                        'Url' => $archivos
                            ), array('Id' => $numeroArchivo)
                    );
                } else {
                    return parent::tipoError();
                }
                return true;
            }
        } else {
            return 'falta';
        }
    }

    /*
     * Metodo para mostrar el html para la edicion de archivos
     * 
     * @param array $datos que el id para poder hacer los pedidos al BD dependiendo el Id
     * @return array en formato de html
     */

    public function modalActualizarArchivo(array $datos) {
        $data = array();
        $data['SelectTipo'] = $this->mostrarArchivosFormatos();
        $data['tipo'] = $this->DBA->mostrarTipoArchivo($datos['id']);
        $data['descripcion'] = $this->DBA->mostrarDescripcionArchivo($datos['id']);
        $data['archivo'] = $this->DBA->mostrarNombreArchivo($datos['id']);
        $data['historico'] = $this->DBA->getHistoricoArchivos($datos['id']);
        return array('formulario' => parent::getCI()->load->view('Generales/Modal/ActualizarArchivosResumen', $data, TRUE), 'datos' => $data);
    }

    /*
     * Metodo para mostrar la tabla t_archivos_formatos
     * 
     * @param string $Id lo recibe poder visualizar los datos
     * @return array que es la consulta a la tabla t_archivo_formatos.
     */

    public function mostrarArchivosTabla(array $dato) {
        return $this->DBA->getArchivosTabla(array('IdTipoArchivo' => $dato['tipo']));
    }

    /*
     * Metodo para mostrar la tabla historico_archivos_formatos
     * 
     * @return array que es la consulta a la tabla historico_archivos_formatos.
     */

    public function mostrarArchivosFormatos() {
        return $this->Catalogo->catArchivosFormatos('3');
    }

    /*
     * Metodo para actualizar un archivo en la tabla t_archivo_formato
     * 
     * @param array $datos recibe el nombre de la tabla en BD
     * @return array que es la consulta a la tabla t_archivo_formato.
     */

    public function actualizarArchivo(array $datos) {
        $archivos = null;
        $CI = parent::getCI();
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $carpeta = 'archivos/' . $datos['id'] . '/';
        if (!empty($_FILES)) {
            $archivos = setMultiplesArchivos($CI, 'evidenciasActualizarArchivo', $carpeta);
            if ($archivos) {
                $archivos = implode(',', $archivos);
                $consulta = $this->DBA->actualizarArchivo(array(
                    'IdTipoArchivo' => $datos['tipo'],
                    'IdUsuario' => $usuario['Id'],
                    'Nombre' => $datos['nombre'],
                    'Descripcion' => $datos['descripcion'],
                    'Fecha' => $fecha,
                    'Url' => $archivos
                        ), array('Id' => $datos['id']));
                $nombreArchivo = $this->DBA->mostrarNombreArchivo($datos['id']);
                $nombreArchivo = implode(',', $nombreArchivo[0]);
                $data = $this->DBA->getHistoricoArchivos($datos['id']);
                $data[0]['NombreArchivo'] = $nombreArchivo;
                return $data;
//                return array('Tabla' => $data, 'NombreArchivo' => $nombreArchivo);
            } else {
                return FALSE;
            }
        } else {
            $consulta = $this->DBA->actualizarArchivo(array(
                'IdTipoArchivo' => $datos['tipo'],
                'IdUsuario' => $usuario['Id'],
                'Nombre' => $datos['nombre'],
                'Descripcion' => $datos['descripcion'],
                    ), array('Id' => $datos['id']));
            return $this->DBA->getHistoricoArchivos($datos['id']);
        }
    }

    /*
     * Metodo para mostrar poder editar en dado caso de que sea el usuario que subio el archivo
     * 
     * @return TRUE si el usuario es el mismo que subio el archivo y else de lo contrario
     */

    public function verificarUsuario(array $dato) {
        $usuario = $this->Usuario->getDatosUsuario();
        if($usuario['Id'] === $dato['id']){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }

}
