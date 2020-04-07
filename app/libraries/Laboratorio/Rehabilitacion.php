<?php

namespace Librerias\Laboratorio;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Almacen\Inventario as Inventario;
use Librerias\Almacen\Equipo as Equipo;

class Rehabilitacion extends General {

    private $inventario;
    private $equipo;

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
        $this->equipo = new Equipo($infoModelo['idModelo']);
        $rafaccionesRehabilitacion = $this->setRefaccionesRehabitiacion($datos);
        $data['infoBitacora']['refacciones'] = $rafaccionesRehabilitacion;
        $data['infoBitacora']['deshuesar'] = $this->equipo->getRefaccionesEquipo();

        return array('response' => 200, 'datos' => $data);
    }

    public function setRefaccionesRehabitiacion(array $datos) {
        $usuario = $this->Usuario->getDatosUsuario();
        $idsRehabilitacion = $this->inventario->getIdsRehabilitacion($datos['id']);

        if (!empty($idsRehabilitacion)) {
            $whereId = 'AND cvav.IdReferenciaAlmacen = "' . $usuario['Id'] . '" AND cvce.Id NOT IN(' . $idsRehabilitacion . ')';
        } else {
            $whereId = 'AND cvav.IdReferenciaAlmacen = "' . $usuario['Id'] . '"';
        }

        return $this->equipo->getRefaccionesEquipoRehabilitacion($whereId);
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
        $datos['estatus'] = 25;

        if ($datos['operacion'] === 'actualizar') {
            $datos['evidencia'] = $this->evidenciaActualizarNota($datos['id'], $archivos);
            $this->inventario->actualizarNotasInventario($datos);
        } else {
            $this->inventario->setNotaInventario($datos);
        }

        return array('response' => 200, 'datos' => $this->inventario->getNotasInventarioId($datos['idInventario']));
    }

    public function evidenciaActualizarNota(string $idNota, $archivos) {
        $notaInventario = $this->inventario->getNotaInventarioWhere("WHERE Id = '" . $idNota . "'");

        if (!empty($notaInventario[0]['Archivos']) && !empty($archivos)) {
            $evidencia = $archivos . ',' . $notaInventario[0]['Archivos'];
        } elseif (!empty($notaInventario[0]['Archivos'])) {
            $evidencia = $notaInventario[0]['Archivos'];
        }
        
        return $evidencia;
    }

    public function setRefaccionRehabilitacion(array $datos) {
//        $datos['id'] = '18284';
//        $datos['idRefaccion'] = '197';
//        $datos['bloqueado'] = 1;
        $this->inventario->setInventarioRehabilitacionRefaccion($datos);
        $infoModelo = $this->inventario->getInventarioId($datos['id']);
        $this->equipo = new Equipo($infoModelo['idModelo']);

        return array('response' => 200, 'datos' => $this->setRefaccionesRehabitiacion($datos));
    }

    public function concluirRehabilitacion(array $datos) {
//        $datos['id'] = '18284';
        $comentarios = $this->inventario->getNotasInventarioId($datos['id']);

        if (!empty($comentarios)) {
            $usuario = $this->Usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];
            $this->inventario->setRevisionRehabilitacion($datos);
            return array('response' => 200);
        } else {
            return array('response' => 400, 'message' => 'Falta agregar al menos un comentario.');
        }
    }

    public function concluirDeshuesar(array $datos) {
        $comentarios = $this->inventario->getNotasInventarioId($datos['id']);

        if (!empty($comentarios)) {
            $usuario = $this->Usuario->getDatosUsuario();
            $datos['idUsuario'] = $usuario['Id'];

            $this->inventario->setRefaccionDeshueso($datos);

            return array('response' => 200);
        } else {
            return array('response' => 400, 'message' => 'Falta agregar al menos un comentario.');
        }
    }

}
