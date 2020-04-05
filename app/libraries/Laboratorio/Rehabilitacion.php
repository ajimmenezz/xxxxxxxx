<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Almacen\Inventario as Inventario;
use Librerias\Almacen\Equipo as Equipo;

class Rehabilitacion extends General {

    private $inventario;

    public function __construct() {
        parent::__construct();
        $this->DBI = \Modelos\Modelo_InventarioConsignacion::factory();
        $this->inventario = new Inventario();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    public function getAlmacenUsuario() {
        $usuario = $this->Usuario->getDatosUsuario();

        return $this->inventario->getInventarioUsuario($usuario['Id']);
    }

    public function getModelo(array $datos) {
        $data = array();
        $infoModelo = $this->inventario->getInventarioId($datos['id']);
        $data['infoBitacora'] = $infoModelo;
        $data['infoBitacora']['comentarios'] = $this->inventario->getNotasInventarioId($datos['id']);
        $equipo = new Equipo($infoModelo['idModelo']);
        $idsRehabilitacion = $this->inventario->getIdsRehabilitacion($datos['id']);
        
        if(!empty($idsRehabilitacion)){
            $whereId = ' AND Id NOT IN(' . $idsRehabilitacion . ')';
        }else{
            $whereId = '';
        }
        
        $data['infoBitacora']['refacciones'] = $equipo->getRefaccionesEquipoWhere($whereId);
        $data['infoBitacora']['deshuesar'] = $equipo->getRefaccionesEquipo();

        return array('response' => 200, 'datos' => $data);
    }

    public function setComentario(array $datos) {
        $this->inventario = new Inventario();
        $usuario = $this->Usuario->getDatosUsuario();
        $archivos = NULL;
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Inventarios/Inventario-' . $datos['idInventario'] . '/EvidenciasNota/';
            $archivos = setMultiplesArchivos($CI, 'agregarEvidencia', $carpeta);
            $archivos = implode(',', $archivos);
        }

        $datos['usuario'] = $usuario['Id'];
        $datos['fecha'] = $fechaCaptura;
        $datos['evidencia'] = $archivos;
        $datos['estatus'] = '25';

        if ($datos['operacion'] === 'actualizar') {
            $this->inventario->actualizarNotasInventario($datos);
        } else {
            $this->inventario->setNotaInventario($datos);
        }
                
        return array('response' => 200, 'datos' => $this->inventario->getNotasInventarioId($datos['idInventario']));
    }

}
