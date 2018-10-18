<?php

namespace Librerias\Compras;

use Controladores\Controller_Base_General as General;

class Compras extends General {

    private $catalogo;
    private $DBSAE;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DBSAE = \Modelos\Modelo_SAE7::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioOrdenCompra(array $datos) {
        $data = array();
        $data['proveedores'] = $this->DBSAE->consultaProveedoresSAE();
        $data['almacenes'] = $this->DBSAE->consultaAlmacenesSAE();
        return array('formulario' => parent::getCI()->load->view('Compras/Formularios/formularioOrdenCompra', $data, TRUE));
    }

    //Obtiene datos para mandar al modal Actualizar Usuario 
    public function mostrarFormularioUsuarios(array $datos) {
        $data = array();
        $data['perfiles'] = $this->catalogo->catPerfiles('3');
        $data['permisos'] = $this->catalogo->catPermisos('3');
        $data['idPerfil'] = $this->catalogo->catConsultaGeneral('SELECT IdPerfil FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        $data['permiso'] = $this->catalogo->catConsultaGeneral('SELECT PermisosAdicionales FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_usuarios WHERE Id = \'' . $datos[0] . '\'');
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/ActualizarUsuario', $data, TRUE), 'datos' => $data);
    }

}
