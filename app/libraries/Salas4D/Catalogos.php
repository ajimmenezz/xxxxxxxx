<?php

namespace Librerias\Salas4D;

use Controladores\Controller_Base_General as General;

class Catalogos extends General {

    private $catalogo;
    private $DBC;

    public function __construct() {
        parent::__construct();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->DBC = \Modelos\Modelo_Catalogo::factory();
        parent::getCI()->load->helper('date');
    }

    public function mostrarFormularioTipoSistema(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_tipos_sistema WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioTipoSistema', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioEquipo(array $datos) {
        $data = array();
        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_equipos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioEquipo', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioMarca(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_marcas WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioMarca', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioModelo(array $datos) {
        $data = array();

        $data['lineas'] = $this->catalogo->catX4DEquipos('3', array('Flag' => '1'));
        $data['marcas'] = $this->catalogo->catX4DMarcas('3', array('Flag' => '1'));

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral("select                                                                 
                                                                cxele.IdEquipo,                                                                
                                                                cxele.IdMarca,                                                                
                                                                cxele.ClaveSAE                                                                
                                                                from cat_v3_x4d_elementos cxele 
                                                                inner join cat_v3_x4d_equipos cxe on cxele.IdEquipo = cxe.Id
                                                                inner join cat_v3_x4d_marcas cxm on cxele.IdMarca = cxm.Id
                                                                where cxele.Id = '" . $datos['id'] . "'");
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_elementos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioModelo', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioComponente(array $datos) {
        $data = array();

        $data['elementos'] = $this->catalogo->catX4DModelos('3', array('Flag' => '1'));
        $data['marcas'] = $this->catalogo->catX4DMarcas('3', array('Flag' => '1'));

        if (!empty($datos)) {
            $data['ids'] = $this->catalogo->catConsultaGeneral("SELECT                                                                
                                                                cxs.IdElemento,
                                                                cxs.IdMarca,
                                                                cxs.ClaveSAE
                                                                from cat_v3_x4d_subelementos cxs where cxs.Id = '" . $datos['id'] . "';");
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_subelementos WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['ids'] = null;
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioComponente', $data, TRUE), 'datos' => $data);
    }

    public function mostrarFormularioUbicacion(array $datos) {
        $data = array();

        if (!empty($datos)) {
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_x4d_ubicaciones WHERE Id = "' . $datos['id'] . '"');
        } else {
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Salas4D/Modal/FormularioUbicacion', $data, TRUE), 'datos' => $data);
    }

    public function multiselectX4D(string $operacion, array $where = null) {
        switch ($operacion) {
            //Muestra los datos de la tabla Equipos X4D
            case '1':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_equipos', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Marcas X4D
            case '2':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_marcas', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            //Muestra los datos de la tabla Municipios
            case '3':
                $consulta = $this->DBC->getArticulos('cat_v3_x4d_modelos', $where);
                if (!empty($consulta)) {
                    return $consulta;
                } else {
                    return FALSE;
                }
                break;
            default:
                break;
        }
    }

    public function borrarActividadesMantenimientoX4D(array $datos) {

        $consulta = $this->DBC->getArticulos('cat_v3_actividades_mantto_salas4d', array('Id' => $datos['id']));
        $arrayDB = $consulta;
        $arrayVacio = [];
        foreach ($arrayDB as $key => $value) {

            $consulta2 = $this->DBC->getArticulos('cat_v3_actividades_mantto_salas4d', array('IdPadre' => $value['Id']));
            foreach ($consulta2 as $key => $val) {
                $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array('Flag' => '0'), array('Id' => $val['Id']));

                $consulta3 = $this->DBC->getArticulos('cat_v3_actividades_mantto_salas4d', array('IdPadre' => $val['Id']));

                foreach ($consulta3 as $key => $va) {
                    $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array('Flag' => '0'), array('Id' => $va['Id']));
                }
            }

            $this->DBC->actualizarUnicoDato('cat_v3_actividades_mantto_salas4d', array('Flag' => '0'), array('Id' => $value['Id']));
        }
    }

    public function guardarActividadesMantenimientoX4D(array $datos) {


        $this->catalogo->getArticulos('cat_v3_actividades_mantto_salas4d', array('IdPadre' => $datos['padre'], 'IdSistema' => $datos['sistema'], 'Nombre' => $datos['actividad']));
    }

    public function obtenerActividadesMantenimientoJson() {
        $arrayDB = $this->catalogo->catX4DActividadesMantenimiento('3', array('Flag' => '1'));
        $arraySistemas = $this->catalogo->catX4DTiposSistema('3', array('Flag' => '1'));

        $json = [];
        foreach ($arraySistemas as $k1 => $v1) {
            array_push($json, [
                'id' => 'sistema-' . $v1['Id'],
                'parent' => '#',
                'text' => $v1['Nombre'],
                'li_attr' => ['sistema' => $v1['Id']],
                'state' => ['opened' => true, 'disbaled' => true]
            ]);
        }

        foreach ($arrayDB as $key => $value) {
            array_push($json, [
                'id' => $value['Id'],
                'parent' => ($value['IdPadre'] != '' && $value['IdPadre'] != 0 ) ? $value['IdPadre'] : 'sistema-' . $value['IdSistema'],
                'text' => $value['Nombre'],
                'li_attr' => ['sistema' => $value['IdSistema']]
            ]);
        }

        return ['json' => $json];
    }

}
