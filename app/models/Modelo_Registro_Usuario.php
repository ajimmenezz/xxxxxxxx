<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

/*
 * Modelo encargado de las consultas de la base de datos para el acceso.
 * 
 * Hereda funcionalidad de la clase Base donde se definen las funciones generales.
 *  
 * Recibe peticiones de las siguientes clases 
 * Registro usuario
 * Correo
 * 
 */

class Modelo_Registro_Usuario extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de validar si el usuario existe en la base de datos
     * 
     * Solicita a la base de datos que encontrar el usuario comparando
     * su usuairo y password. Si regresa un resultado solicita el nombre del perfil
     * a la base de datos y la foto del usuario a la base de datos. Y regresa 
     * los datos del usuario 
     * 
     * @param array $data recibe los datos usuario y password
     * @return array regresa los datos del usuario o arry vacio
     */

    public function buscarUsuario(array $data) {
        $consulta = $this->encontrar('cat_v3_usuarios', $data);
        if (!empty($consulta)) {
            return $this->datosSession($consulta);
        } else {
            $consulta = $this->encontrar('cat_v3_usuarios', array('Email' => $data['Usuario'], 'Password' => $data['Password']));
            if (!empty($consulta)) {
                return $this->datosSession($consulta);
            } else {
                $consulta = $this->encontrar('cat_v3_usuarios', array('EmailCorporativo' => $data['Usuario'], 'Password' => $data['Password']));
                if (!empty($consulta)) {
                    return $this->datosSession($consulta);
                } else {
                    return $consulta;
                }
            }
        }
    }

    /*
     * Encargado de generar un arreglo para la varible se session del usuario
     * 
     * Recibe los datos del usuario y obtiene el perfil, permisos y su foto del 
     * usuario. Toda la informacion la guarda en un arreglo que regresara para 
     * poder posteriomente generar la variable de session
     * 
     * @param array $consulta recibe de la consulta que se obtubo de validar el usuario
     * @return array regresa los datos del usuario o un array vacio.
     */

    private function datosSession(array $datosUsuario) {
        $datos = array();
        foreach ($datosUsuario as $value) {
            $nombre = $this->consulta('SELECT 
                                        nombreUsuario(Id) Nombre
                                        FROM cat_v3_usuarios 
                                    WHERE Id = ' . $value['Id']
            );
            $datos['Id'] = $value['Id'];
            $datos['Email'] = $value['Email'];
            $datos['EmailCorporativo'] = $value['EmailCorporativo'];
            $datos['Nombre'] = $nombre[0]['Nombre'];
            $datos['Perfil'] = $value['IdPerfil'];
            $datos['IdPerfil'] = $value['IdPerfil'];
            $datos['PermisosAdicionales'] = explode(',', $value['PermisosAdicionales']);
            $datos['SDKey'] = $value['SDKey'];
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
            if (!empty($value['Permisos'])) {
                $datos['Permisos'] = explode(',', $value['Permisos']);
                array_push($datos['Permisos'], '18');
            } else {
                $datos['Permisos'] = array('18');
            }
            $datos['IdArea'] = $value['IdArea'];
            $datos['IdDepartamento'] = $value['IdDepartamento'];
        }
        $foto = $this->encontrar('t_rh_personal', array('Id' => $datos['Id']));
        foreach ($foto as $value) {
            $datos['Foto'] = $value['UrlFoto'];
        }
        
        $datos['PermisosString'] = array();
        
        foreach ($datos['Permisos'] as $value2) {
            $permiso = $this->consulta('SELECT Permiso FROM cat_v3_permisos WHERE Id = "' . $value2 . '"');
            array_push($datos['PermisosString'], $permiso[0]['Permiso']);
        }
        return $datos;
    }

    /*
     * Encargado de validar si ya existe en la base de datos un logeo del usuario. 
     * Valida si el usuario ya cuenta con un registro previo de logueo.
     * 
     * @param string $usuario recibe el Id del usuario.
     * @return array regresa el ID del log o array vacio
     */

    public function buscarRegistroLogueo(string $usuario) {
        $datos = array();
        $consulta = $this->consulta('select Id from t_log_acceso where IdUsuario = ' . $usuario . ' and (FechaSalida is null or FechaSalida = "")');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                $datos['id'] = $value['Id'];
            }
        }
        return $datos;
    }

    /*
     * Encargado de generar el registro del logueo del usuario
     * 
     * Genera el registro en la base de datos cuando el usuario ingresa al sistema
     * @param array $data recibe el id usuario y la fecha ingreso
     * @return boolean regresa si true o false de la consulta
     */

    public function generarRegistroLogueo(array $data) {
        $consulta = $this->insertar('t_log_acceso', $data);
        return parent::connectDBPrueba()->insert_id();
    }

    /*
     * Encargado de acutlizar el logueo del usuario
     * 
     * Actualiza el registro en la base de datos cuando el usuario sale del sistema
     * @param array $data recibe el id usuario o id del logueo y la fecha ingreso
     * @return boolean regresa si true o false de la consulta
     */

    public function registroSalida(array $data) {
        if (array_key_exists('Id', $data)) {
            $consulta = $this->actualizar('t_log_acceso', array('FechaSalida' => $data['FechaSalida'], 'IdTipoSalida' => '1'), array('Id' => $data['Id']));
            if (empty($consulta)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $consulta = $this->consulta('select Id from t_log_acceso where IdUsuario = ' . $data['IdUsuario'] . ' order by Id desc limit 1');
            if (!empty($consulta)) {
                foreach ($consulta as $value) {
                    $id = $value['Id'];
                }
                $consulta = $this->actualizar('t_log_acceso', array('FechaSalida' => $data['FechaSalida'], 'IdTipoSalida' => '1'), array('Id' => $id));
                if (empty($consulta)) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        }
    }

    /*
     * Entrega los datos de registro de la clave enviada para la recuperacion 
     * de password
     * 
     * @param array $data recibe la clave 
     * @return array regresa el Id, Fecha y Flag
     */

    public function getRecuperarPassword(array $data) {
        $datos = array();
        $fila = $this->encontrar('t_recuperacion_password', $data);
        foreach ($fila as $value) {
            $datos['Id'] = $value['Id'];
            $datos['Fecha'] = $value['Fecha'];
            $datos['Flag'] = $value['Flag'];
        }
        return $datos;
    }

    /*
     * Actualiza el regitro para recuperar el password a flag cero
     * 
     * @param array $data Recive el id del registro actualizar
     * @return string regresa el numero de filas actualizadas
     */

    public function actualizarRecuperarPassword(array $data) {
        $fila = $this->actualizar('t_recuperacion_password', array('Flag' => 0), $data);
        return $fila;
    }

    /*
     * Se encarga de actualizar el password del usuario y el regisrto de 
     * recuperacion de password actualiza el flag a cero.
     * 
     * @param array $data recibe el nuevo password encryptado
     * @return string regresa el numero de la fila actaulizada
     */

    public function actualizarPassword(array $data) {
        $fila = $this->actualizar('cat_v3_usuarios', array('Password' => $data['password']), array('Usuario' => $data['usuario']));
        $respuesta = $this->actualizar('t_recuperacion_password', array('Flag' => 0), array('Id' => $data['id']));
        return $fila;
    }

    /*
     * Encargado de validar si ya existe en la base de datos un logeo del usuario. 
     * Valida si el usuario ya cuenta con un registro previo de logueo.
     * 
     * @param string $usuario recibe el Id del usuario.
     * @return array regresa el ID del log o array vacio
     */

    public function buscarRegistroAcceso(string $usuario) {
        $datos = array();
        $consulta = $this->consulta('select Id from t_log_acceso where IdUsuario = ' . $usuario);
        if (empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
