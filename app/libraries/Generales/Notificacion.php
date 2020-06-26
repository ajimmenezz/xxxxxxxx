<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

/**
 * Description of Notificacion
 *
 * @author Freddy
 */
class Notificacion extends General {

    private $DBN;
    private $Correo;
    private $Catalogo;

    public function __construct() {
        parent::__construct();
        $this->DBN = \Modelos\Modelo_Notificacion::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    /*
     * Se encarga de obtener las notificaciones del usuario.
     * 
     */

    public function getNotificacionesMenuCabecera(string $usuario) {
        return $this->DBN->getCantidadNotificaciones($usuario);
    }

    /*
     * Se encarga de obtener las notificaciones del usuario
     * 
     */

    public function getNotificaciones() {
        $usuario = $this->Usuario->getDatosUsuario();
        return $this->DBN->getNotificaciones($usuario['Id']);
    }

    /* gi
     * Se encarga de solicitar actualizar la notificacion como vista
     * 
     */

    public function notificacionVista(string $Id) {
        $datos = array();
        $consulta = $this->DBN->actualizarNotificacion(array('Flag' => 0), array('Id' => $Id));
        $usuario = $this->Usuario->getDatosUsuario();
        $datos['Notificaciones'] = $this->getNotificaciones();
        $datos['MenuCabecera'] = $this->getNotificacionesMenuCabecera($usuario['Id']);
        return $datos;
    }

    /*
     * Encargado de crear la notificacion
     */

    public function setNuevaNotificacion(array $datos, string $asunto, string $mensaje, array $usuario = null) {
        $data = array();

        if (empty($usuario)) {
            if ($datos['departamento'] === '11') {
                if (isset($datos['idSolicitud'])) {
                    $data = $this->defineUsersPolicy(array('idSolicitud' => $datos['idSolicitud']));
                } else {
                    $data = $dataUsuersPolicy = $this->DBN->showSupervisorsPolicyCoordinator();
                }
            } else {
                $data = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => $datos['departamento']));
            }
        } else {
            array_push($data, $usuario);
        }

        foreach ($data as $key => $value) {
            $consulta = $this->DBN->setNotificacion(array(
                'IdDepartamento' => $datos['departamento'],
                'Remitente' => $datos['remitente'],
                'Destinatario' => $value['IdUsuario'],
                'IdTipo' => $datos['tipo'],
                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')),
                'Descripcion' => $datos['descripcion'],
                'Flag' => '1'
            ));

            if (!empty($consulta)) {
                $cuerpo = $this->Correo->mensajeCorreo($asunto, $mensaje);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($value['EmailCorporativo']), $asunto, $cuerpo);
            }
        }
    }

    private function defineUsersPolicy(array $dataToDefinePolicyUsers) {
        $idSucursalRequest = $this->DBN->showIdSucursalTableSolicitudes($dataToDefinePolicyUsers['idSolicitud']);

        if ($idSucursalRequest[0]['IdSucursal'] === '0') {
            $dataUsuersPolicy = $this->DBN->showSupervisorsPolicyCoordinator();
        } else {
            $dataUsuersPolicy = $this->DBN->showSupervisorAndBranchTechnician($dataToDefinePolicyUsers['idSolicitud']);
        }

        return $dataUsuersPolicy;
    }

    public function enviarNotificacionEspecifica(array $datos, string $asunto, string $mensaje) {
        $emailCorporativo = $this->DBN->consultaNotificacion('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = "' . $datos['destinatario'] . '"');
        $consulta = $this->DBN->setNotificacion(array(
            'IdDepartamento' => $datos['departamento'],
            'Remitente' => $datos['remitente'],
            'Destinatario' => $datos['destinatario'],
            'IdTipo' => $datos['tipo'],
            'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City')),
            'Descripcion' => $datos['descripcion'],
            'Flag' => '1'
        ));

        if (!empty($consulta)) {
            $cuerpo = $this->Correo->mensajeCorreo($asunto, $mensaje);
            $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($emailCorporativo[0]['EmailCorporativo']), $asunto, $cuerpo);
        }
    }

}
