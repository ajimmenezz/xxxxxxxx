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

    public function guardarDatosPersonal(array $datos) {

        $resultado = $this->DBU->actualizarTRHAltaPersonal($datos);

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
        return $resultado;
    }

    public function guardarDatosAcademicosUsuario(array $datos) {
        if (isset($datos['id'])) {
            $datos['idUsuario'] = $datos['id'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHAcademicos($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosIdiomasUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHIdiomas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosComputacionalesUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHSoftware($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSoftware(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosSistemasEspecialesUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHSistemas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSistemas(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function guardarDatosAutomovilUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $consulta = $this->DBU->getPersonal('SELECT * FROM t_rh_conduccion WHERE IdUsuario = "' . $datos['idUsuario'] . '"');

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
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaCaptura'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->insertarTRHDependientes($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHDependientes(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosAcademicosUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHAcademicos($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosIdiomasUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHIdiomas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosSoftwareUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHSoftware($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSoftware(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosSistemasUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHSistemas($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHSistemas(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function actualizarDatosDependientesUsuario(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

        $datos['fechaMod'] = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $resultado = $this->DBU->actualizarCampoTRHDependientes($datos);

        if (!empty($resultado)) {
            return $this->DBU->consultaTRHDependientes(array('IdUsuario' => $datos['idUsuario']));
        } else {
            return FALSE;
        }
    }

    public function eliminarDatos(array $datos) {
        if (isset($datos['idUsuario'])) {
            $datos['idUsuario'] = $datos['idUsuario'];
        } else {
            $usuario = $this->usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
        }

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
                $datosArray = $this->DBU->consultaTRHAcademicos(array('IdUsuario' => $datos['idUsuario']));
                break;
            case 'idiomas':
                $datosArray = $this->DBU->consultaTRHIdiomas(array('IdUsuario' => $datos['idUsuario']));
                break;
            case 'software':
                $datosArray = $this->DBU->consultaTRHSoftware(array('IdUsuario' => $datos['idUsuario']));
                break;
            case 'sistemas':
                $datosArray = $this->DBU->consultaTRHSistemas(array('IdUsuario' => $datos['idUsuario']));
                break;
            case 'dependientes':
                $datosArray = $this->DBU->consultaTRHDependientes(array('IdUsuario' => $datos['idUsuario']));
                break;
        }

        if (!empty($resultado)) {
            return $datosArray;
        } else {
            return FALSE;
        }
    }

    public function guardarDatosCovid(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();
        $datos['idUsuario'] = $usuario['Id'];
        
        try {
            $datosCovid = $this->DBU->consultaTRHCovid($datos);

            if (empty($datosCovid)) {
                $this->DBU->insertarTRHCovid($datos);
            } else {
                $this->DBU->actualizarTRHCovid($datos);
            }

            return ['code' => 200, 'message' => 'Correcto'];
        } catch (\Exception $ex) {
            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }

}
