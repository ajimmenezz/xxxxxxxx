<?php

namespace Librerias\Administrador;

use Controladores\Controller_Base_General as General;
use \Librerias\Generales\Registro_Usuario as Usuario;

class Administrador extends General {

    private $catalogo;
    private $usuario;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->usuario = Usuario::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioPermisos(array $datos) {
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/formularioPermisos', '', TRUE));
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

    public function mostrarFormularioSucursales(array $datos) {
        $data = array();
        $usuario = $this->usuario->getDatosUsuario();
        $data['idPerfil'] = $usuario['IdPerfil'];
        $data['usuarios'] = $this->catalogo->catUsuarios("3", array('Flag' => '1'));
        $data['clientes'] = $this->catalogo->catClientes("3", array('Flag' => '1'));
        $data['regiones'] = $this->catalogo->catRegionesCliente("3", array('Flag' => '1'));
        $data['unidadesNegocio'] = $this->catalogo->catUnidadeNegocio("3", array('Flag' => '1'));
        $data['paises'] = $this->catalogo->catLocalidades("1");
        
        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                    b.Id AS IdCliente,
                                                                    a.IdRegionCliente,
                                                                    c.Id AS IdPais, 
                                                                    d.Id AS IdEstado, 
                                                                    e.Id AS IdMunicipio, 
                                                                    f.Id AS IdColonia, 
                                                                    g.Id AS IdUsuario,
                                                                    a.IdUnidadNegocio
                                                                FROM cat_v3_sucursales a 
                                                                INNER JOIN cat_v3_clientes b 
                                                                        ON b.Id = a.IdCliente
                                                                INNER JOIN cat_v3_paises c 
                                                                        ON c.Id = a.IdPais 
                                                                INNER JOIN cat_v3_estados d 
                                                                        ON a.IdEstado = d.Id 
                                                                INNER JOIN cat_v3_municipios e 
                                                                        ON a.IdMunicipio = e.Id 
                                                                INNER JOIN cat_v3_colonias f 
                                                                        ON a.IdColonia = f.Id 
                                                                INNER JOIN cat_v3_usuarios g 
                                                                        ON a.IdResponsable = g.Id 
                                                                WHERE a.Id = "' . $datos['sucursal'] . '"');
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_sucursales WHERE Id = ' . $datos['sucursal']);
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/FormularioSucursal', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioClientes(array $datos) {
        $data = array();
        $data['paises'] = $this->catalogo->catLocalidades('1');
        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT b.Id AS IdPais, c.Id AS IdEstado, d.Id AS IdMunicipio, e.Id AS IdColonia FROM cat_v3_clientes a INNER JOIN cat_v3_paises b ON b.Id = a.IdPais INNER JOIN cat_v3_estados c ON a.IdEstado = c.Id INNER JOIN cat_v3_municipios d ON a.IdMunicipio = d.Id INNER JOIN cat_v3_colonias e ON a.IdColonia = e.Id WHERE a.Id = ' . $datos['cliente']);
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/FormularioCliente', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioProveedor(array $datos) {
        $data = array();
        $data['paises'] = $this->catalogo->catLocalidades('1');
        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT IdPais, IdEstado, IdMunicipio, IdColonia from cat_v3_proveedores cvp WHERE cvp.Id = ' . $datos['proveedor']);
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_proveedores WHERE Id = ' . $datos['proveedor']);
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/FormularioProveedor', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioAreasAtencion(array $datos) {
        $data = array();
        $data['clientes'] = $this->catalogo->catClientes("3", array('Flag' => '1'));
        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral('SELECT IdCliente FROM cat_v3_areas_atencion WHERE Id = "' . $datos['areaAtencion'] . '"');
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_areas_atencion WHERE Id = "' . $datos['areaAtencion'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Administrador/Modal/FormularioAreasAtencion', $data, TRUE), 'datos' => $data);
    }

}
