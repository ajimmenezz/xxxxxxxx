<?php

namespace Librerias\RH;

use Controladores\Controller_Base_General as General;

class Perfiles extends General {

    private $usuario;
    private $catalogo;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarModal(array $datos) {
        $data = array();
        $usuario = $this->usuario->getDatosUsuario();
        $data['Autorizacion'] = FALSE;
        if (in_array('35', $usuario['PermisosAdicionales'])) {
            $data['Autorizacion'] = TRUE;
        } elseif (in_array('35', $usuario['Permisos'])) {
            $data['Autorizacion'] = TRUE;
        }
        $data['areas'] = $this->catalogo->catAreas('3', array('Flag' => '1'));
        $data['departamentos'] = $this->catalogo->catDepartamentos('3', array('Flag' => '1'));
        $data['permisos'] = $this->catalogo->catPermisos('3');
        if (!empty($datos)) {
            $data['idArea'] = $this->catalogo->catConsultaGeneral('SELECT u.Id, d.IdArea,u.IdDepartamento FROM cat_perfiles u INNER JOIN cat_v3_departamentos_siccob d on u.IdDepartamento = d.Id INNER JOIN cat_v3_areas_siccob p ON d.IdArea = p.Id WHERE u.Id = ' . $datos['Perfil']);
            $data['permiso'] = $this->catalogo->catConsultaGeneral('SELECT Permisos FROM cat_perfiles WHERE Id = ' . $datos['Perfil']);
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_perfiles WHERE Id = ' . $datos['Perfil']);
        } else {
            $data['idArea'] = null;
            $data['permiso'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('RH/Modal/ActualizarPerfil', $data, TRUE), 'datos' => $data);
    }

}
