<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Catalogos_Perfil extends General {

    private $usuario;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    public function guardarCatalogosPerfil(array $datos) {
        $datos['Flag'] = '1';

        switch ($datos['operacion']) {
            case 'documentoRecibido':
                $resultado = $this->catalogo->catRhDocumentosEstudio('1', $datos);
                break;
            case 'estadoCivil':
                $resultado = $this->catalogo->catRhEdoCivil('1', $datos);
                break;
            case 'idioma':
                $resultado = $this->catalogo->catRhHabilidadesIdioma('1', $datos);
                break;
            case 'nivelEstudio':
                $resultado = $this->catalogo->catRhNivelEstudio('1', $datos);
                break;
            case 'nivelHabilidad':
                $resultado = $this->catalogo->catRhNivelHabilidad('1', $datos);
                break;
            case 'sexo':
                $resultado = $this->catalogo->catRhSexo('1', $datos);
                break;
            case 'sistema':
                $resultado = $this->catalogo->catRhHabilidadesSistema('1', $datos);
                break;
            case 'software':
                $resultado = $this->catalogo->catRhHabilidadesSoftware('1', $datos);
                break;
        }

        return $resultado;
    }

    public function actualizarCatalogosPerfil(array $datos) {

        switch ($datos['operacion']) {
            case 'documentoRecibido':
                $resultado = $this->catalogo->catRhDocumentosEstudio('2', $datos);
                break;
            case 'estadoCivil':
                $resultado = $this->catalogo->catRhEdoCivil('2', $datos);
                break;
            case 'idioma':
                $resultado = $this->catalogo->catRhHabilidadesIdioma('2', $datos);
                break;
            case 'nivelEstudio':
                $resultado = $this->catalogo->catRhNivelEstudio('2', $datos);
                break;
            case 'nivelHabilidad':
                $resultado = $this->catalogo->catRhNivelHabilidad('2', $datos);
                break;
            case 'sexo':
                $resultado = $this->catalogo->catRhSexo('2', $datos);
                break;
            case 'sistema':
                $resultado = $this->catalogo->catRhHabilidadesSistema('2', $datos);
                break;
            case 'software':
                $resultado = $this->catalogo->catRhHabilidadesSoftware('2', $datos);
                break;
        }

        return $resultado;
    }

}
