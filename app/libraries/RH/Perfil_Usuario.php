<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Perfil_Usuario extends General {

    private $usuario;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DBU = \Modelos\Modelo_Usuario::factory();
        parent::getCI()->load->helper('date');
    }

    public function datosPerfilUsuario() {
        $usuario = $this->usuario->getDatosUsuario();
        $data = array();
        $data['datosUsuario'] = $this->DBU->consultaTRHPersonal(array('IdUsuario' => $usuario['Id']));
        $data['datosConduccion'] = $this->DBU->consultaTRHConduccion(array('IdUsuario' => $usuario['Id']));

        return $data;
    }

    public function datosGuardadosPerfilUsuario() {
        $usuario = $this->usuario->getDatosUsuario();
        $data = array();
        $data['datosUsuario'] = $this->DBU->consultaTRHPersonal(array('IdUsuario' => $usuario['Id']));
        $data['datosConduccion'] = $this->DBU->consultaTRHConduccion(array('IdUsuario' => $usuario['Id']));
        $data['datosAcademicos'] = $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $usuario['Id']));
        $data['datosIdiomas'] = $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $usuario['Id']));
        $data['datosSoftware'] = $this->DBU->consultaTRHSoftware(array('IdUsuario' => $usuario['Id']));
        $data['datosSistemas'] = $this->DBU->consultaTRHSistemas(array('IdUsuario' => $usuario['Id']));
        $data['datosDependientes'] = $this->DBU->consultaTRHDependientes(array('IdUsuario' => $usuario['Id']));
        $data['nivelEstudio'] = $this->catalogo->catRhNivelEstudio('3');
        $data['documentosEstudio'] = $this->catalogo->catRhDocumentosEstudio('3');
        $data['habilidadesIdioma'] = $this->catalogo->catRhHabilidadesIdioma('3');
        $data['nivelHabilidades'] = $this->catalogo->catRhNivelHabilidad('3');
        $data['habilidadesSoftware'] = $this->catalogo->catRhHabilidadesSoftware('3');
        $data['habilidadesSistema'] = $this->catalogo->catRhHabilidadesSistema('3');

        return $data;
    }

    public function datosCatalogosUsuario() {
        $data = array();
        $data['paises'] = $this->catalogo->catLocalidades('1');
        $data['estadoCivil'] = $this->catalogo->catRhEdoCivil('3', array('Flag' => '1'));
        $data['sexo'] = $this->catalogo->catRhSexo('3', array('Flag' => '1'));
        $data['nivelEstudio'] = $this->catalogo->catRhNivelEstudio('3', array('Flag' => '1'));
        $data['documentosEstudio'] = $this->catalogo->catRhDocumentosEstudio('3', array('Flag' => '1'));
        $data['habilidadesIdioma'] = $this->catalogo->catRhHabilidadesIdioma('3', array('Flag' => '1'));
        $data['habilidadesSoftware'] = $this->catalogo->catRhHabilidadesSoftware('3', array('Flag' => '1'));
        $data['nivelHabilidades'] = $this->catalogo->catRhNivelHabilidad('3', array('Flag' => '1'));
        $data['habilidadesSistema'] = $this->catalogo->catRhHabilidadesSistema('3', array('Flag' => '1'));

        return $data;
    }

    public function guardarDatosPersonalesUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $datos['id'] = $usuario['Id'];
        $resultado = $this->DBU->actualizarTRHPersonal($datos);

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
        return $resultado;
    }

    public function guardarDatosAcademicosUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHAcademicos($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosIdiomasUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHIdiomas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosComputacionalesUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHSoftware($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSoftware(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosSistemasEspecialesUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $datos['idUsuario'] = $usuario['Id'];
        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHSistemas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSistemas(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosAutomovilUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $consulta = $this->DBU->getPersonal('SELECT * FROM t_rh_conduccion WHERE IdUsuario = "' . $usuario['Id'] . '"');
        $datos['idUsuario'] = $usuario['Id'];

        if (empty($consulta)) {
            $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $resultado = $this->DBU->insertarTRHConduccion($datos);
        } else {
            $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $resultado = $this->DBU->actualizarTRHConduccion($datos);
        }

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
        
        return $resultado;
    }

    public function guardarDatosDependientesEconomicosUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['idUsuario'] = $usuario['Id'];
        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHDependientes($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHDependientes(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosAcademicosUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHAcademicos($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosIdiomasUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHIdiomas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosSoftwareUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHSoftware($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSoftware(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosSistemasUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHSistemas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSistemas(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosDependientesUsuario(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHDependientes($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHDependientes(array('IdUsuario' => $usuario['Id']));
        } else {
            return FALSE;
        }
    }

    public function eliminarDatos(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $tabla = '';
        switch ($datos['tabla']) {
            case 'academicos':
                $tabla = 't_rh_academicos';
                break;
            case 'idiomas':
                $tabla = 't_rh_idiomas';
                break;
            case 'software':
                $tabla = 't_rh_software';
                break;
            case 'sistemas':
                $tabla = 't_rh_sistemas';
                break;
            case 'dependientes':
                $tabla = 't_rh_dependientes';
                break;
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $datos['tablaNombre'] = $tabla;

        $resultado = $this->DBU->eliminarTRH($datos);

        switch ($datos['tabla']) {
            case 'academicos':
                $datosArray = $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $usuario['Id']));
                break;
            case 'idiomas':
                $datosArray = $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $usuario['Id']));
                break;
            case 'software':
                $datosArray = $this->DBU->consultaTRHSoftware(array('IdUsuario' => $usuario['Id']));
                break;
            case 'sistemas':
                $datosArray = $this->DBU->consultaTRHSistemas(array('IdUsuario' => $usuario['Id']));
                break;
            case 'dependientes':
                $datosArray = $this->DBU->consultaTRHDependientes(array('IdUsuario' => $usuario['Id']));
                break;
        }

        if (!empty($resultado)) {
            return $datosArray;
        } else {
            return FALSE;
        }
    }

}
