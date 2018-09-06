<?php

namespace Librerias\Tesoreria;

use Controladores\Controller_Base_General as General;

class Catalogos extends General {

    private $catalogo;
    private $DBT;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DBT = \Modelos\Modelo_Tesoreria::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioViaticos(array $datos) {
        $data = array();
        $data['tecnicosAsociados'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                            Id, 
                                                                            nombreUsuario(Id) Nombre,
                                                                            puestoByUsuario(Id) Puesto
                                                                        FROM cat_v3_usuarios 
                                                                        WHERE IdPerfil in(83,30, 57) 
                                                                        ORDER BY Puesto ASC, Nombre ASC');
        return array('formulario' => parent::getCI()->load->view('Tesoreria/Formularios/FormularioViaticos', $data, TRUE), 'datos' => $data);
    }

    public function mostrarTablaSucursalesAsociado(array $datos) {
        $data['sucursales'] = $this->catalogo->catConsultaGeneral('SELECT 
                                                                        cvs.Id, 
                                                                        cvs.Nombre,
                                                                        (SELECT Monto FROM cat_v3_viaticos_outsourcing WHERE IdSucursal = cvs.Id) Monto
                                                                    FROM cat_v3_sucursales cvs
                                                                    WHERE cvs.IdResponsable = "' . $datos['tecnico'] . '"
                                                                    ORDER BY cvs.Nombre ASC');

        return array('formulario' => parent::getCI()->load->view('Tesoreria/Formularios/TablaSucursalesMontoOutsorcing', $data, TRUE), 'datos' => $data);
    }

    public function guardarViaticosOutsourcing(array $datos) {
        $resultado = $this->DBT->guardarViaticosOutsourcing($datos);

        if ($resultado) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function guardarMontosOutsourcing(array $datos) {
        $resultado = $this->DBT->guardarMontosOutsourcing($datos);

        if ($resultado) {
            return $resultado;
        } else {
            return FALSE;
        }
    }

    public function catalogoViaticosOutsourcing() {
        return $this->catalogo->catConsultaGeneral('SELECT 
                                                    *, 
                                                    nombreUsuario(IdTecnico) Outsourcing,
                                                sucursal(IdSucursal) Sucursal
                                                FROM cat_v3_viaticos_outsourcing
                                                ORDER BY Outsourcing ASC, Sucursal ASC');
    }
    
    public function tablaMontosVueltasOutsourcing() {
        return $this->catalogo->catConsultaGeneral('SELECT * FROM t_montos_x_vuelta_outsourcing');
    }

}
