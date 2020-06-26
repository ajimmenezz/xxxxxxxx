<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Rutas extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de consultar la tabla dependiendo de la sentencia
     * @param string $sentencia recibe la sentencia para hacer la consulta
     * @return array regresa todos los datos de una o varias tablas
     */

    public function getSentenciaRuta(string $sentencia) {
        $consulta = $this->consulta($sentencia);
        return $consulta;
    }

    /*
     * Encargado de actualizar
     *  @param string $tabla = tabla en la BD
     *  @param string $datos = datos para actualizar
     *  @param string $where = id que necesitamos para saber que campos se modificaran
     *  @return boolean TRUE si fue correcto de lo contrario el tipo de error
     */

    public function actualizarRuta(string $tabla, array $datos, array $where) {
        $consulta = $this->actualizar($tabla, $datos, $where);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return parent::tipoError();
        }
    }

    /*
     * Encargado de insertar 
     *  @param $datos = sentenci la cual trae la tabla y los datos para insertar
     *  @return boolean TRUE si fue correcto de lo contrario ELSE
     */

    public function insertarRutaNueva(string $datos) {
        $consulta = parent::connectDBPrueba()->query($datos);
        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Metodo que obtiene los datos del chofer que va atender el servicio
     * $usuario = id de usuario
     * 
     */

    public function getDatosChofer(string $usuario) {
        $datos = array();
        $consulta = $this->encontrar('cat_v3_usuarios', array('Id' => $usuario));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['IdUsuario'] = $usuario;
                $datos['Nombre'] = $value['Nombre'];
                $datos['Perfil'] = $value['IdPerfil'];
                $datos['EmailCorporativo'] = $value['EmailCorporativo'];
            }
            $perfil = $this->consulta('
                SELECT 
                cp.*,
                cvds.IdArea 
                FROM cat_perfiles cp INNER JOIN cat_v3_departamentos_siccob cvds 
                ON cp.IdDepartamento = cvds.Id 
                WHERE cp.Id = ' . $datos['Perfil']
            );

            foreach ($perfil as $value) {
                $datos['Perfil'] = $value['Nombre'];
                $datos['IdDepartamento'] = $value['IdDepartamento'];
            }
            return $datos;
        }
    }

}
