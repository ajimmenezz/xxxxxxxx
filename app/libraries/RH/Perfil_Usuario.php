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

        return $data;
    }

    public function datosCatalogosUsuario() {
        $data = array();
        $data['paises'] = $this->catalogo->catLocalidades('1');
        $data['estadoCivil'] = $this->catalogo->catRhEdoCivil('3');
        $data['sexo'] = $this->catalogo->catRhSexo('3');
        $data['nivelEstudio'] = $this->catalogo->catRhNivelEstudio('3');
        $data['documentosEstudio'] = $this->catalogo->catRhDocumentosEstudio('3');
        $data['habilidadesIdioma'] = $this->catalogo->catRhHabilidadesIdioma('3');
        $data['habilidadesSoftware'] = $this->catalogo->catRhHabilidadesSoftware('3');
        $data['nivelHabilidades'] = $this->catalogo->catRhNivelHabilidad('3');
        $data['habilidadesSistema'] = $this->catalogo->catRhHabilidadesSistema('3');

        return $data;
    }

    public function guardarDatosPersonalesUsuario(array $datos) {
        var_dump($datos);
    }

}
