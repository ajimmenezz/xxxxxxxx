<?php

namespace Librerias\Poliza;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\PDF as PDF;

class Poliza extends General {

    private $usuario;
    private $DBP;
    private $DBST;
    private $catalogo;
    private $servicio;
    private $seguimiento;
    private $Correo;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->servicio = \Librerias\Generales\Servicio::factory();
        $this->seguimiento = \Librerias\Poliza\Seguimientos::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
    }

    public function getSolicitudesMultimedia() {
        $usuario = $this->usuario->getDatosUsuario();
        return $this->DBP->getSolicitudesMultimedia($usuario['Email']);
    }

    /*
     * Metodo para mostrar el formulario de solicitud a multimedia
     * 
     * @param array $datos que el ticket para poder hacer los pedidos al BD dependiendo el ticket
     * @return array en forma de html
     */

    public function formularioSolicitudMultimedia(array $datos) {
        $data = array();
        $data['detallesSM'] = $this->DBP->consultaSolicitudesMinuta($datos['ticket']);
        $evidenciaSolicitud = $this->DBP->mostrarNombreEvidencia('EvidenciaSolicitud', $datos['ticket']);
        if (!empty($evidenciaSolicitud)) {
            $data['evidenciaSolicitud'] = explode(',', $evidenciaSolicitud[0]['EvidenciaSolicitud']);
        }
        $evidenciaApoyo = $this->DBP->mostrarNombreEvidencia('EvidenciaApoyo', $datos['ticket']);
        if (!empty($evidenciaApoyo)) {
            $data['evidenciaApoyo'] = explode(',', $evidenciaApoyo[0]['EvidenciaApoyo']);
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/formularioSolicitudMultimedia', $data, TRUE), 'datos' => $data);
    }

    /*
     * Encargada de insertar los datos a la BD
     *  $datos = datos para insertar en la BD
     */

    public function insertarSolicitudMultimedia(array $datos) {
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->usuario->getDatosUsuario();
        $array = array(
            'Ticket' => $datos['ticket'],
            'FechaCaptura' => $fechaCaptura,
            'FechaSolicitud' => $datos['fechaSolicitaron'],
            'FechaApoyo' => $datos['fechaRecibieron'],
            'IdUsuario' => $usuario['Id']
        );
        $verificarSM = $this->DBP->consultaSolicitudesMinuta($datos['ticket']);
        if (empty($verificarSM)) {
            $consulta = $this->DBP->setSolicitudesMultimedia($array);
            if (!empty($consulta)) {
                return $consulta;
            } else {
                return FALSE;
            }
        } else {
            $consulta = $this->DBP->actualizarSolicitudesMultimedia($array);
            if (!empty($consulta)) {
                return $consulta;
            } else {
                return FALSE;
            }
        }
    }

    /*
     * Metodo para insertar una evidencia nueva
     * 
     * @param array $datos recibe los datos para insertar
     * @return TRUE si se cumplen las condiciones y ELSE si no.
     */

    public function nuevaEvidenciaSM(array $datos) {
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->usuario->getDatosUsuario();
        $array = array(
            'Ticket' => $datos['ticket'],
            'FechaCaptura' => $fechaCaptura,
            'IdUsuario' => $usuario['Id']
        );
        $CI = parent::getCI();
        foreach ($_FILES as $key => $value) {
            $nombre = $key;
        }
        if (isset($nombre)) {
            if ($nombre === 'evidenciaSolicitaron') {
                $carpeta = 'solicitudesMultimedia/SM-' . $datos['ticket'] . '/EvidenciasSolicitaron/';
                $campoBD = 'EvidenciaSolicitud';
            } else if ($nombre === 'evidenciaRecibieron') {
                $carpeta = 'solicitudesMultimedia/SM-' . $datos['ticket'] . '/EvidenciasRecibieron/';
                $campoBD = 'EvidenciaApoyo';
            }

            $archivos = setMultiplesArchivos($CI, $nombre, $carpeta);
            if ($archivos) {
                $verificarSM = $this->DBP->consultaSolicitudesMinuta($datos['ticket']);
                if (empty($verificarSM)) {
                    $archivos = implode(',', $archivos);
                    $consulta = $this->DBP->insertarEvidenciasSM($array, $campoBD, array(
                        'Archivo' => $archivos
                    ));
                    if (!empty($consulta)) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                } else {
                    if (!empty($verificarSM[0][$campoBD])) {
                        $evidencias = explode(',', $verificarSM[0][$campoBD]);
                        if (!empty($evidencias)) {
                            foreach ($evidencias as $value) {
                                array_push($archivos, $value);
                            }
                        }
                        $archivos = implode(',', $archivos);
                        $longitud = strlen($archivos);
                        $ultimaComa = strrpos($archivos, ',', -1);
                        if ($longitud === ($ultimaComa + 1)) {
                            $archivos = substr($archivos, 0, $ultimaComa);
                        }
                    } else {
                        $archivos = implode(',', $archivos);
                    }
                    $consulta = $this->DBP->actualizarEvidenciasSM($array, $campoBD, array(
                        'Archivo' => $archivos
                    ));
                    if (!empty($consulta)) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar la evidencia 
     * 
     * @param array $datos recibe los datos para actualizar
     * @return mensaje si se cumplen las condiciones y ELSE si no.
     */

    public function eliminarEvidenciaSM(array $datos) {
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $posicionInicial = strpos($datos['key'], 'SM-') + 9;
        $posicionFinal = strpos($datos['key'], '/', $posicionInicial);
        $diferencia = $posicionFinal - $posicionInicial;
        $servicio = substr($datos['key'], $posicionInicial, $diferencia);
        $evidencias = $this->DBP->mostrarNombreEvidencia($datos['id'][0], $datos['id'][1]);
        $evidencias = explode(',', $evidencias[0][$datos['id'][0]]);
        if (in_array($datos['key'], $evidencias)) {
            foreach ($evidencias as $key => $value) {
                if ($value === $datos['key']) {
                    unset($evidencias[$key]);
                }
            }
            $archivos = implode(',', $evidencias);
            $array = array(
                'Ticket' => $datos['id'][1],
                'FechaCaptura' => $fechaCaptura
            );
            $consulta = $this->DBP->actualizarEvidenciasSM($array, $datos['id'][0], array(
                'Archivo' => $archivos
            ));
            if (!empty($consulta)) {
                eliminarArchivo($datos['key']);
            } else {
                return FALSE;
            }
        }
    }

    public function mostrarFormularioRegionesCliente(array $datos) {
        $data = array();
        $data['clientes'] = $this->catalogo->catClientes("3", array('Flag' => '1'));
        $data['responsablesSiccob'] = $this->catalogo->catUsuarios("3", array('Flag' => '1'));
        if (!empty($datos)) {
            $data['operacion'] = 'Actualizar';
            $data['flag'] = $this->catalogo->catConsultaGeneral('SELECT Flag FROM cat_v3_regiones_cliente WHERE Id = "' . $datos['regionCliente'] . '"');
        } else {
            $data['operacion'] = 'Guardar';
            $data['flag'] = null;
        }
        return array('formulario' => parent::getCI()->load->view('Poliza/Modal/FormularioRegionesCliente', $data, TRUE), 'datos' => $data);
    }

    public function serviciosSinFirma() {
        return $this->catalogo->catConsultaGeneral('SELECT 
                                                        *,
                                                        estatus(IdEstatus)as NombreEstatus,
                                                        tipoServicio(IdTipoServicio) as Servicio
                                                    FROM t_servicios_ticket 
                                                    WHERE IdTipoServicio = 20
                                                    AND IdEstatus = 4
                                                    AND Firma IS NULL');
    }

    public function datosServicioSinFirma(array $datos) {
        $data = array();
        $data['idCliente'] = $this->servicio->consultaIdClienteSucursal(array('servicio' => $datos['servicio']));
        $data['encargadosTI'] = $this->catalogo->catCinemexValidadores('3', array(), array('Flag' => '1'));

        return $data;
    }

    public function resumenVueltasAsociadosFolio() {
        $usuario = $this->usuario->getDatosUsuario();
        $fechaLimiteVueltas = '"2018-09-07"';

        if (in_array('227', $usuario['PermisosAdicionales']) || in_array('227', $usuario['Permisos'])) {
            $vueltasAsociados = $this->consultaTodasVueltasAsociados($fechaLimiteVueltas);
        } else if (in_array('228', $usuario['PermisosAdicionales']) || in_array('228', $usuario['Permisos'])) {
            $vueltasAsociados = $this->catalogo->catConsultaGeneral('SELECT 
                                                                        tfo.Id,
                                                                        tfo.IdServicio,
                                                                        tfo.Folio,
                                                                        tfo.Fecha,
                                                                        tfo.Archivo,
                                                                        tfo.Vuelta,
                                                                        tst.Ticket,
                                                                        sucursal(tst.IdSucursal) Sucursal,
                                                                        estatus(tfo.IdEstatus) Estatus,
                                                                        nombreUsuario(tfo.IdUsuario) NombreAtiende,
                                                                        tst.Atiende,
                                                                        cvs.IdRegionCliente,
                                                                        cvrc.IdResponsableInterno,
                                                                        (SELECT 
                                                                                estatus(IdEstatus)
                                                                            FROM
                                                                                t_servicios_ticket
                                                                            WHERE
                                                                                Id = tfo.IdServicio) EstatusServicio
                                                                    FROM
                                                                        t_facturacion_outsourcing tfo
                                                                            INNER JOIN
                                                                        t_servicios_ticket tst ON tst.Id = tfo.IdServicio
                                                                            INNER JOIN
                                                                        cat_v3_sucursales cvs ON cvs.Id = tst.IdSucursal
                                                                            INNER JOIN
                                                                        cat_v3_regiones_cliente cvrc ON cvrc.Id = cvs.IdRegionCliente
                                                                    WHERE
                                                                        cvrc.IdResponsableInterno = "' . $usuario['Id'] . '"
                                                                            AND (CASE
                                                                            WHEN tfo.Vuelta = 1 THEN tst.IdEstatus IN (3 , 4)
                                                                            WHEN tfo.Vuelta > 1 THEN tst.IdEstatus = 4
                                                                        END)
                                                                            AND tfo.IdEstatus = 8
                                                                            AND tfo.Fecha >= ' . $fechaLimiteVueltas . '
                                                                            AND tst.FechaCreacion >= ' . $fechaLimiteVueltas . '
                                                                    ORDER BY tfo.Folio ASC');
            if (empty($vueltasAsociados)) {
                $vueltasAsociados = $this->catalogo->catConsultaGeneral('SELECT 
                                                                            tfo.Id,
                                                                            tfo.IdServicio,
                                                                            tfo.Folio,
                                                                            tfo.Fecha,
                                                                            tfo.Archivo,
                                                                            tfo.Vuelta,
                                                                            tst.Ticket,
                                                                            sucursal(tst.IdSucursal) Sucursal,
                                                                            nombreUsuario(tfo.IdUsuario) NombreAtiende,
                                                                            estatus(tfo.IdEstatus) Estatus,
                                                                            estatus(tst.IdEstatus) AS EstatusServicio
                                                                        FROM
                                                                            t_facturacion_outsourcing tfo
                                                                                INNER JOIN
                                                                            t_servicios_ticket tst ON tst.Id = tfo.IdServicio
                                                                        WHERE
                                                                            (CASE
                                                                                WHEN tfo.Vuelta = 1 THEN tst.IdEstatus IN (3 , 4)
                                                                                WHEN tfo.Vuelta > 1 THEN tst.IdEstatus = 4
                                                                            END)
                                                                                AND tfo.IdEstatus = 8
                                                                        AND tfo.Fecha >= ' . $fechaLimiteVueltas . '
                                                                        AND tst.FechaCreacion >= ' . $fechaLimiteVueltas . '
                                                                        ORDER BY tfo.Folio ASC');
            }
        } else if (in_array('229', $usuario['PermisosAdicionales']) || in_array('229', $usuario['Permisos'])) {
            $vueltasAsociados = $this->catalogo->catConsultaGeneral('SELECT
                                                                    tfo.Id,
                                                                    tfo.IdServicio,
                                                                    tfo.Folio,
                                                                    tfo.Fecha,
                                                                    tfo.Archivo,
                                                                    tfo.Vuelta,
                                                                    tst.Ticket,
                                                                    sucursal(IdSucursal) Sucursal,
                                                                    estatus(tfo.IdEstatus) Estatus,
                                                                    nombreUsuario(tfo.IdUsuario) NombreAtiende,
                                                                    tst.Atiende,
                                                                    (SELECT estatus(IdEstatus) FROM t_servicios_ticket WHERE Id = tfo.IdServicio) EstatusServicio
                                                                    FROM t_facturacion_outsourcing tfo
                                                                    INNER JOIN t_servicios_ticket tst
                                                                    ON tst.Id = tfo.IdServicio
                                                                    WHERE Atiende = "' . $usuario['Id'] . '"
                                                                    AND tfo.Fecha >= ' . $fechaLimiteVueltas . '
                                                                    AND tst.FechaCreacion >= ' . $fechaLimiteVueltas . '
                                                                    ORDER BY tfo.Folio ASC');
        } else {
            $vueltasAsociados = array();
        }

        return $vueltasAsociados;
    }

    public function consultaTodasVueltasAsociados(string $fechaLimiteVueltas) {
        return $this->catalogo->catConsultaGeneral('SELECT 
                                                        tfo.Id,
                                                        tfo.IdServicio,
                                                        tfo.Folio,
                                                        tfo.Fecha,
                                                        tfo.Archivo,
                                                        tfo.Vuelta,
                                                        tst.Ticket,
                                                        sucursal(tst.IdSucursal) Sucursal,
                                                        nombreUsuario(tfo.IdUsuario) NombreAtiende,
                                                        estatus(tfo.IdEstatus) Estatus,
                                                        estatus(tst.IdEstatus) AS EstatusServicio
                                                    FROM
                                                        t_facturacion_outsourcing tfo
                                                            INNER JOIN
                                                        t_servicios_ticket tst ON tst.Id = tfo.IdServicio
                                                    WHERE tfo.Fecha >= ' . $fechaLimiteVueltas . '
                                                    AND tst.FechaCreacion >= ' . $fechaLimiteVueltas . '
                                                    ORDER BY tfo.Folio ASC');
    }

    public function mostrarCategorias() {
        $categorias = $this->DBP->consultaCategorias();
        return $categorias;
    }

    public function mostrarListaPreguntas() {
        $preguntas = $this->DBP->consultaListaPreguntas();
        return $preguntas;
    }

    public function agregarCategoria(array $datos) {
        $consulta = $this->DBP->consultaCategorias();
        $arrayCartegoria = array();
        $nuevaCategoria = mb_strtoupper($datos['nuevaCategoria']);

        foreach ($consulta as $categoria) {
            array_push($arrayCartegoria, $categoria['Nombre']);
        }

        if (in_array($nuevaCategoria, $arrayCartegoria)) {
            return [
                'code' => 500,
                'error' => "El nombre de la categoria ya se encuentra registrada"
            ];
        } else {
            $insertar = $this->DBP->agregarCategoria(mb_strtoupper($datos['nuevaCategoria']));
            return [
                'id' => $insertar['Id'],
                'categoria' => mb_strtoupper($datos['nuevaCategoria']),
                'code' => 200,
                'succes' => "Se agrego nueva categoria"
            ];
        }
    }

    public function actulizarCategoria(array $idCategoria) {
        if (isset($idCategoria['idCategoria'])) {
            $consultaCategoria = $this->DBP->consultaCategorias($idCategoria['idCategoria']);
            return ['modal' => parent::getCI()->load->view('Poliza/Modal/EditarCategoria.php', ['data' => $consultaCategoria[0]], TRUE)];
        }
    }

    public function editarCategoria(array $datosCategoria) {
        $editar = $this->DBP->editarCategoria($datosCategoria);
        return $editar['categoria'][0];
    }

    public function modalPregunta() {
        $arrayCategoria = Array();

        $selectCategoria = $this->DBP->consultaCategorias();
        foreach ($selectCategoria as $value) {
            array_push($arrayCategoria, array('id' => $value['Id'], 'text' => $value['Nombre']));
        }

        $data = [
            'categoria' => $arrayCategoria,
            'areaAtencion' => $this->DBP->consultaAreasAtencion()
        ];

        return ['modal' => parent::getCI()->load->view('Poliza/Modal/AgregarPregunta.php', $data, TRUE),
            'categoria' => $data['categoria'],
            'areaAtencion' => $data['areaAtencion']];
    }

    public function guardarPregunta(array $datos) {

        $areaAtencion = implode(',', $datos['areaAtencion']);
        $datosPregunta = array(
            'IdCategoria' => $datos['categoria'],
            'Concepto' => $datos['concepto'],
            'Etiqueta' => $datos['etiqueta'],
            'AreasAtencion' => $areaAtencion
        );
        $insertar = $this->DBP->insertarPregunta($datosPregunta);

        $consulta = $this->DBP->consultaListaPreguntas($insertar['Id']);

        foreach ($consulta as $valor) {
            return [
                'succes' => "La pregunta ya se encuentra registrada",
                'Id' => $valor['Id'],
                'NombreCategoria' => $valor['NombreCategoria'],
                'Concepto' => $valor['Concepto'],
                'Etiqueta' => $valor['Etiqueta'],
                'Estatus' => $valor['Estatus']
            ];
        }
    }

    public function editarPregunta(array $datosPregunta) {

        $areaAtencion = implode(',', $datosPregunta['areaAtencion']);
        $arrayPregunta = array('Id' => $datosPregunta['Id'],
            'IdCategoria' => $datosPregunta['categoria'],
            'Concepto' => $datosPregunta['concepto'],
            'Etiqueta' => $datosPregunta['etiqueta'],
            'AreasAtencion' => $areaAtencion,
            'Flag' => $datosPregunta['estatus']
        );

        return $this->DBP->editarPregunta($arrayPregunta);
    }

    public function mostrarPregunta($idPregunta) {
        if (isset($idPregunta['idPregunta'])) {

            $arrayCategoria = Array();

            $selectCategoria = $this->DBP->consultaCategorias();
            foreach ($selectCategoria as $value) {
                array_push($arrayCategoria, array('id' => $value['Id'], 'text' => $value['Nombre']));
            }

            $consultaPregunta = $this->DBP->consultaListaPreguntas($idPregunta['idPregunta']);

            $data = [
                'categoria' => $arrayCategoria,
                'areaAtencion' => $this->DBP->consultaAreasAtencion()
            ];

            return ['modal' => parent::getCI()->load->view('Poliza/Modal/EditarPregunta.php', ['data' => $consultaPregunta[0]], TRUE),
                'categoria' => $data['categoria'],
                'areaAtencion' => $data['areaAtencion'],
                'consultaPregunta' => $this->DBP->consultaListaPreguntas($idPregunta['idPregunta'])];
        }
    }

    public function obtenerPreguntaPorCategoria($idCategoria) {

        $listaPreguntas = $this->DBP->consultaListaPreguntas(null, $idCategoria['IdCategoria']);

        $datosListaPreguntas = Array();
        foreach ($listaPreguntas as $datos) {
            $areaAtencion = explode(",", $datos['AreasAtencion']);
            foreach ($areaAtencion as $area) {
                $consultaArea = $this->DBP->nombreArea($area, $idCategoria['sucursal']);
                $datoChecklist = Array('Id' => $datos['Id'],
                    'IdCategoria' => $datos['IdCategoria'],
                    'NombreCategoria' => $datos['NombreCategoria'],
                    'Concepto' => $datos['Concepto'],
                    'Etiqueta' => $datos['Etiqueta'],
                    'Estatus' => $datos['Estatus'],
                    'Flag' => $datos['Flag'],
                    'IdArea' => $consultaArea['Id'],
                    'Nombre' => $consultaArea['Nombre']);

                array_push($datosListaPreguntas, $datoChecklist);
            }
        }
        if (!empty($datosListaPreguntas)) {
            return array($datosListaPreguntas);
        } else {
            return ['error' => "No hay información para mostrar"];
        }
    }

    public function guardarInformacionGeneral(array $datos) {

        if ($datos['guardarTipo'] == 1) {
            $insertar = $this->DBP->actualizarSucursal($datos);
        } else if ($datos['guardarTipo'] == 2) {
            $datosInsertar = array(
                'IdServicio' => $datos['servicio'],
                'IdCategoria' => $datos['idCategoria'],
                'DatosTabla' => json_decode($datos['datosTabla'], true)
            );

            $insertar = $this->DBP->insertarRevisionAreas($datosInsertar);
        }

        if ($insertar) {
            return ['code' => 200, 'succes' => "informacion guardada"];
        } else {
            return ['code' => 500, 'error' => "Selecciona la informacion"];
        }
    }

    public function mostrarPuntoRevision(array $datos) {

        $consultaAreaCategoria = $this->DBP->mostrarRevisionAreaCategoria($datos);

        $pushArea = [];
        $pushRevision = [];
        $datosRevision = [];
        $pushChecklist = [];
        $arrayIdArea = [];
        foreach ($consultaAreaCategoria as $value) {
            if (!array_key_exists($value['Areas'], $pushArea)) {
                $pushArea[$value['Areas']] = [];
                $arrayIdArea[$value['Areas']] = $value['IdAreaAtencion'];
            }

            if (!array_key_exists($value['Etiqueta'], $pushArea[$value['Areas']])) {
                $pushArea[$value['Areas']][$value['Etiqueta']] = [];
            }

            array_push($pushArea[$value['Areas']][$value['Etiqueta']], $value['Punto']);
            array_push($pushRevision, array('area' => $value['Areas'], 'idRevisionArea' => $value['Id'], 'punto' => $value['Punto']));
        }

        foreach ($pushRevision as $revision) {

            $datosRevision = array('servicio' => $datos['servicio'], 'idCategoria' => $datos['categoria'], 'idRevisionArea' => $revision['idRevisionArea'], 'punto' => $revision['punto']);
            $consulta = $this->DBP->mostrarRevisionPunto($datosRevision);

            foreach ($consulta as $value2) {
                array_push($pushChecklist, $value2);
            }
        }

        return ['pushArea' => $pushArea, 'pushRevision' => $pushRevision, 'pushChecklist' => $pushChecklist, 'arrayIdArea' => $arrayIdArea];
    }

    public function guardarPuntoRevision(array $datos) {
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/';
        $archivos = implode(',', setMultiplesArchivos($CI, 'inputArchivoPunto', $carpeta));
        $idRevisionArea = $this->DBP->obtenerIdRevicionArea($datos);
        $datos['idRevisionArea'] = $idRevisionArea;
        $evidencia = $this->DBP->obtenerEvidenciasPuntosCheckList($datos);

        if (empty($evidencia)) {
            $arrayDatos = array(
                'IdServicio' => $datos['servicio'],
                'idCategoria' => $datos['idCategoria'],
                'idRevisionArea' => $datos['idRevisionArea'],
                'Punto' => $datos['punto'],
                'Evidencia' => $archivos
            );
            if (empty($this->DBP->insertarRevisionPunto($arrayDatos))) {
                return NULL;
            }
        } else {
            $evidencia['Evidencia'] .= ($evidencia['Evidencia'] !== '') ? ',' . $archivos : $archivos;
            if (empty($this->DBP->actualizarEvidencia($evidencia))) {
                return NULL;
            }
        }

        return $this->mostrarPuntoRevision(array('servicio' => $datos['servicio'], 'categoria' => $datos['idCategoria']));
    }

    public function eliminarEvidenciaChecklist(array $datos) {

        $idRevisionArea = $this->DBP->obtenerIdRevicionArea($datos);
        $datos['idRevisionArea'] = $idRevisionArea;
        $evidencias = $this->DBP->obtenerEvidenciasPuntosCheckList($datos);
        $evidencia = explode(",", $evidencias['Evidencia']);

        foreach (array_keys($evidencia, $datos['url']) as $key) {
            unset($evidencia[$key]);
        }

        $actualizarEvidencia = implode(",", $evidencia);
        $datosActualizar = Array('Evidencia' => $actualizarEvidencia, 'Id' => $evidencias['Id']);
        $this->DBP->actualizarEvidencia($datosActualizar);

        return $this->mostrarPuntoRevision(array('servicio' => $datos['servicio'], 'categoria' => $datos['idCategoria']));
    }

    public function eliminarEvidenciaRevisionPunto(array $datos) {

        $consultaPunto = $this->DBP->mostrarRevisionPunto($datos['extra']);

        $eivedenciasExplote = explode(",", $consultaPunto[0]['Evidencia']);

        if (in_array($datos['key'], $eivedenciasExplote)) {
            foreach ($eivedenciasExplote as $key => $value) {
                if ($value === $datos['key']) {
                    unset($eivedenciasExplote[$key]);
                }
            }

            $evidenciaImplode = implode(',', $eivedenciasExplote);
            $datosActualizar = Array('Id' => $consultaPunto[0]['Id'], 'evidencia' => $evidenciaImplode, 'tipoActualizar' => 1);

            $actualizarEvidencia = $this->DBP->actulaizarRevisionPunto($datosActualizar);
        }
        return true;
    }

    public function actualizarRevisionPunto(array $datos) {

        $idRevisionArea = $this->DBP->obtenerIdRevicionArea($datos);
        $datos['idRevisionArea'] = $idRevisionArea;
        $consultaPunto = $this->DBP->mostrarRevisionPunto($datos);

        $datosActualizae = Array('Flag' => 0, 'Id' => $consultaPunto[0]['Id'], 'tipoActualizar' => 2);
        $actualizarFlag = $this->DBP->actulaizarRevisionPunto($datosActualizae);
        return $actualizarFlag;
    }

    public function revisionTecnica(array $datos) {

        $generales = $this->servicio->getGeneralesByServicio($datos['servicio']);
        $data = [
            'areaPunto' => $this->seguimiento->consultaAreaPuntoXSucursal($generales['IdSucursal'], 'Area, Punto')
        ];
        return [
            'html' => parent::getCI()->load->view('Poliza/Modal/RevisionTecnicaChecklist.php', $data, TRUE),
            'sucursal' => $generales['IdSucursal']
        ];
    }

    public function guardarRevisionTecnicaChecklist(array $datos) {
        $fechaCaptura = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->usuario->getDatosUsuario();

        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/Evidencias/';
        $archivos = setMultiplesArchivos($CI, $datos['fileInput'], $carpeta);
        $archivosImplode = implode(',', $archivos);

        $datosInsertar = Array(
            'IdUsuario' => $usuario['Id'],
            'IdServicio' => $datos['servicio'],
            'IdArea' => $datos['area'],
            'Punto' => $datos['punto'],
            'IdModelo' => $datos['modelo'],
            'Serie' => $datos['serie'],
            'Terminal' => $datos['terminal'],
            'IdTipoDiagnostico' => $datos['tipoDiagnostico'],
            'IdComponente' => $datos['componente'],
            'IdTipoFalla' => $datos['tipoFalla'],
            'IdFalla' => $datos['idFalla'],
            'Fecha' => $fechaCaptura,
            'Evidencias' => $archivosImplode
        );

        $insertar = $this->DBP->guardarRevisionTecnicaChecklist($datosInsertar);
        $lista = $this->mostrarFallasTecnicasCheclist(['servicio' => $datos['servicio']]);
        return array_merge($insertar, ['listaFallas' => $lista]);
    }

    public function mostrarFallasTecnicasCheclist(array $servicio) {
        $consulta = $this->DBP->mostrarFallasTecnicas($servicio['servicio']);
        return $consulta;
    }

    public function actualizarRevisionTecnica(array $datos) {
        if (isset($datos['idRevision'])) {
            $consultaRevision = $this->DBP->mostrarFallasTecnicas($datos['servicio'], $datos['idRevision']);
            return ['modal' => parent::getCI()->load->view('Poliza/Modal/FormularioRevisionTecnicaChecklist.php', ['data' => $consultaRevision[0]], TRUE)];
        }
    }

    public function editarRevisionTecnicaChecklist(array $datos) {

        $this->DBP->actualizaFallasTecnicas($datos);
        $consultar = $this->DBP->mostrarFallasTecnicas($datos['servicio']);
        return $consultar;
    }

    //informacion para PDF

    public function mostrarRevisionPuntoPDF(array $datos) {
        $consultaRevisionPunto = $this->DBP->consultaRevisionPunotPDF($datos['servicio']);
        return $consultaRevisionPunto;
    }

    public function mostrarRevisionTecnicaPDF(array $datos) {
        $consultaRevisionTecnica = $this->DBP->mostrarFallasTecnicas($datos['servicio']);
        return $consultaRevisionTecnica;
    }

    public function guardarConclusionChecklist(array $datos) {
        $usuario = $this->usuario->getDatosUsuario();

        $correo = implode(",", $datos['correo']);
        $datosServicio = $this->DBST->getDatosServicio($datos['servicio']);
        $titulo = 'Se concluyo el servicio';

        $host = $_SERVER['SERVER_NAME'];
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $imgFirma = $datos['img'];
        $imgFirma = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $imgFirma));
        $dataFirma = base64_decode($imgFirma);

        $direccionFirma = '/storage/Archivos/imagenesFirmas/Checklist/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $dataFirma);

        $arrayServicio = array(
//            'Estatus' => '4',
            'FechaConclusion' => $fecha,
            'Firma' => $direccionFirma,
            'NombreFirma' => $datos['nombreFirma'],
            'CorreoCopiaFirma' => $correo,
            'FechaFirma' => $fecha,
            'servicio' => $datos['servicio'],
        );

        $pdf = $this->pdfServicioChecklist($datos['servicio']);

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'https://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $usuario['Id'] . '/Pdf/Ticket_' . $datos['Ticket'] . '_Servicio_' . $usuario['Id'] . '_' . $nombreServ . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $pdf;
        }

        $actualizarServicio = $this->DBP->concluirServicio($arrayServicio);

        if ($actualizarServicio) {
            $linkPDF = '<br>Para descargar el archivo PDF de conclusión <a href="' . $path . '" target="_blank">dar click aqui</a>';
            $textoCorreo = '<p>Se notifica que el servicio de ' . $datosServicio['TipoServicio'] . ' con numero de ticket ' . $datos['ticket'] . ' se a concluido por ' . $usuario['Nombre'] . '<br>' . $linkPDF . '</p>';

            foreach ($actualizarServicio as $key => $value) {
                $this->enviarCorreoConcluido(array($value['CorreoCopiaFirma']), $titulo, $textoCorreo);
                return TRUE;
            }
        }

        return false;
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto) {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function pdfServicioChecklist(array $datos) {
        $datosServicio = $this->DBST->getDatosServicio($datos['servicio']);
        $revisionFisica = $this->DBP->consultaRevisionPunotPDF($datos['servicio']);
        $revisionTecnica = $this->DBP->mostrarFallasTecnicas($datos['servicio']);
//        $pline1 = 9;
//        $pline2 = 201;
        $this->pdf = new PDFAux("Sucursal: " . $datosServicio['Sucursal'] . " \n Resumen de Servicio - Checklist");
        $this->pdf->body();

        $this->pdf->subTitulo('Revisión Fisica');
        foreach ($revisionFisica as $revision) {

            $listaEvidencia = explode(",", $revision['Evidencia']);
            $total = count($listaEvidencia);
            if ($total == 7) {
                $this->pdf->BasicTable(array('Categoria', 'Area', ''), array(
                    array($revision['Categoria'], $revision['Areas'], ''),
                    array($revision['Etiqueta'], '', ''),
                    array($revision['Punto'], '', '')
                ));

                $this->pdf->Ln(10);
                $this->pdf->tablaImagenes($listaEvidencia);
                $this->pdf->Ln(20);
            }
        }


//        $title = '20000 Leguas de Viaje Submarino';
//        $this->pdf->SetTitle($title);
//        $this->pdf->SetAuthor('Julio Verne');
//        $this->pdf->PrintChapter(1, 'UN RIZO DE HUIDA', '20k_c1.txt');
//        $this->pdf->PrintChapter(2, 'LOS PROS Y LOS CONTRAS', '20k_c2.txt');
//        
//        
//        $this->pdf->SetAutoPageBreak(false);
//        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
//        $this->pdf->AddPage();
//        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
//
//        $this->pdf->SetXY(0, 8);
//        $this->pdf->SetFont("helvetica", "", 8);
//        $this->pdf->Cell(0, 0, "Sucursal: " . $datosServicio['Sucursal'], 0, 0, 'R');
//
//        $this->pdf->SetXY(0, 27);
//        $this->pdf->SetFont("helvetica", "", 9);
//        $this->pdf->Cell(0, 0, "Resumen de Servicio - Checklist", 0, 0, 'R');
//
//        $this->pdf->Ln('10');
//        $this->pdf->SetFont("helvetica", "", 9);
//        $this->pdf->Cell(0, 0, utf8_decode("Información General del Servicio"), 0, 0, 'L');
//        $y = $this->pdf->GetY() + 4;
//        $this->pdf->Line($pline1, $y, $pline2, $y);
//
//        $this->pdf->SetXY(8, 48);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, utf8_decode("Número de Ticket"));
//        $this->pdf->SetXY(8, 54);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['Ticket']);
//
//        $this->pdf->SetXY(8, 65);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, "Tipo de Servicio");
//        $this->pdf->SetXY(8, 71);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['TipoServicio']);
//
//        $this->pdf->SetXY(65, 65);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, "Sucursal");
//        $this->pdf->SetXY(65, 71);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['Sucursal']);
//
//        $this->pdf->SetXY(110, 65);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, "No. Servicio");
//        $this->pdf->SetXY(110, 71);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datos['servicio']);
//
//        $this->pdf->SetXY(158, 65);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, "Personal que Atiende");
//        $this->pdf->SetXY(158, 71);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['NombreAtiende']);
//
//        $this->pdf->Ln('12');
//        $this->pdf->SetFont("helvetica", "", 9);
//        $this->pdf->Cell(0, 0, utf8_decode("Documentación del servicio"), 0, 0, 'L');
//        $y = $this->pdf->GetY() + 4;
//        $this->pdf->Line($pline1, $y, $pline2, $y);
//
//        $this->pdf->SetXY(8, 95);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, "Estatus Servicio");
//        $this->pdf->SetXY(8, 102);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, utf8_decode($datosServicio['Estatus']));
//
//        $this->pdf->SetXY(65, 95);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, utf8_decode("Fecha Creación"));
//        $this->pdf->SetXY(65, 102);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['FechaCreacion']);
//
//        $this->pdf->SetXY(110, 95);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, utf8_decode("Fecha Inicio"));
//        $this->pdf->SetXY(110, 102);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['FechaInicio']);
//
//        $this->pdf->SetXY(158, 95);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, utf8_decode("Fecha Conclusión"));
//        $this->pdf->SetXY(158, 102);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, $datosServicio['FechaConclusion']);
//
//        $this->pdf->SetXY(8, 115);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(15, 0, utf8_decode("Descripción del Servicio"));
//        $this->pdf->SetXY(8, 119);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(180, 0, utf8_decode($datosServicio['DescripcionServicio']));
//
//        $this->pdf->SetXY(8, 130);
//        $this->pdf->SetFont("helvetica", "B", 10);
//        $this->pdf->Cell(0, 0, utf8_decode("Firma Cierre"), 0, 0, 'C');
//        $this->pdf->Image('.' . $datosServicio['Firma'], 80, 140, 50, 0, 'PNG');
//
//        $this->pdf->SetXY(8, 174);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(0, 0, utf8_decode($datosServicio['NombreFirma']), 0, 0, 'C');
//        $this->pdf->SetXY(8, 179);
//        $this->pdf->SetFont("helvetica", "", 10);
//        $this->pdf->Cell(0, 0, $fecha, 0, 0, 'C');
//
//        $this->pdf->AddPage();
//        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
//
//        $this->pdf->SetXY(0, 27);
//        $this->pdf->Ln('10');
//        $this->pdf->SetFont("helvetica", "", 11);
//        $this->pdf->Cell(0, 0, utf8_decode("Revisión Fisica"), 0, 0, 'L');
//        $y = $this->pdf->GetY() + 4;
//        $this->pdf->Line($pline1, $y, $pline2, $y);
//        
//        foreach ($revisionFisica as $revision) {
//            
//            $y = $this->pdf->GetY() + 15; //48
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(15, 0, utf8_decode("Categoria"));
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(15, 0, utf8_decode("Área"));
//
//            $y = $this->pdf->GetY() + 6; //54
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(52, 0, $revision['Categoria']);
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(100, 0, $revision['Areas']);
//            
//            $y = $this->pdf->GetY() + 9; //71
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(180, 0, utf8_decode($revision['Etiqueta']));
//
//            $y = $this->pdf->GetY() + 7; //78
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(15, 0, $revision['Punto']);
//
////            $evidencias = $revision['Evidencia'];
////            $evidenciaExplode = explode(",", $evidencias);
////            $y = $this->pdf->GetY() + 8; //86
////            $x = 8;
////            $contador = 0;
////            $this->pdf->SetXY($x, $y);
////            foreach ($evidenciaExplode as $value2) {
////                $this->pdf->Image('.' . $value2, $x, $y, 40, 35);
////                $x += 65;
////                $contador++;
////                if($contador == 1){
////                    $this->pdf->SetY($this->pdf->GetY()+45);
////                }
////                if($contador == 3){
////                    $x = 8;
////                    $y += 45;
////                    $this->pdf->SetY($y);
////                    $contador = 0;
////                    $this->pdf->CheckPageBreak($y);
////
////                }     
////            }    
////            $y = $this->pdf->GetY();
////            $this->pdf->SetY($y);
//        }
//            $this->pdf->CheckPageBreak($y);
//        
//        $this->pdf->AddPage();
//        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');
//        $this->pdf->SetXY(0, 27);
//
//        foreach ($revisionTecnica as $key => $valor) {
//            $this->pdf->Ln('10');
//            $this->pdf->SetFont("helvetica", "", 11);
//            $this->pdf->Cell(0, 0, utf8_decode("Revisión Tecnica " . $valor['AreaPunto']), 0, 0, 'L');
//            $y = $this->pdf->GetY() + 4;
//            $this->pdf->Line($pline1, $y, $pline2, $y);
//
//            $y = $this->pdf->GetY() + 15; //42
//            $this->pdf->SetFont("helvetica", "B", 10);
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(15, 0, utf8_decode("Área y Punto"));
//
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(15, 0, "Equipo");
//
//            $this->pdf->SetXY(158, $y);
//            $this->pdf->Cell(15, 0, "Serie");
//
//            $y = $this->pdf->GetY() + 6; //48
//            $this->pdf->SetFont("helvetica", "", 10);
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(180, 0, $valor['AreaPunto']);
//
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(180, 0, $valor['Equipo']);
//
//            $this->pdf->SetXY(158, $y);
//            $this->pdf->Cell(180, 0, $valor['Serie']);
//
//            $y = $this->pdf->GetY() + 11; //59
//            $this->pdf->SetFont("helvetica", "B", 10);
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(180, 0, "Componente");
//
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(15, 0, utf8_decode("Tipo de diagnostico"));
//
//            $this->pdf->SetXY(120, $y);
//            $this->pdf->Cell(15, 0, "Falla");
//
//            $this->pdf->SetXY(158, $y);
//            $this->pdf->Cell(15, 0, "Fecha");
//
//            $y = $this->pdf->GetY() + 6; //65
//            $this->pdf->SetFont("helvetica", "", 10);
//            $this->pdf->SetXY(8, $y);
//            $this->pdf->Cell(180, 0, utf8_decode($valor['Componente']));
//
//            $this->pdf->SetXY(65, $y);
//            $this->pdf->Cell(180, 0, $valor['TipoDiagnostico']);
//
//            $this->pdf->SetXY(120, $y);
//            $this->pdf->Cell(180, 0, $valor['Falla']);
//
//            $this->pdf->SetXY(158, $y);
//            $this->pdf->Cell(180, 0, $valor['Fecha']);
//            
//            
//        }
//        $this->pdf->SetY($y);
//        $this->pdf->CheckPageBreak($y);
        $carpeta = $this->pdf->definirArchivo('PruebaPDF', 'PruebaPDF');
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

    function paginaInformacionGeneral() {
        $this->pdf->subTitulo('Información General del Servicio');

        $this->pdf->BasicTable(array('Numero Ticket'), array(
            array($datosServicio['Ticket'])
        ));

        $this->pdf->BasicTable(array('Tipo de Servicio', 'Sucursal', 'No. Servicio', 'Personal que Atiende'), array(
            array($datosServicio['TipoServicio'], $datosServicio['Sucursal'], $datos['servicio'], $datosServicio['NombreAtiende'])
        ));

        $this->pdf->subTitulo('Documentación del servicio');
    }

}

class PDFAux extends PDF {

    private $contenidoHeader;

    public function __construct($contenido, $orientation = 'P', $unit = 'mm', $size = 'A4') {
        parent::__construct($orientation, $unit, $size);
        $this->contenidoHeader = $contenido;
    }

    function Header() {
        $this->SetFont('Helvetica', 'I', 10);
        $this->Image('./assets/img/siccob-logo.png', 10, 5, 10, 15, 'PNG');
        $this->SetXY(25, 5);
        $this->MultiCell(0, 7, $this->contenidoHeader, 0, 'R');
    }

    function subTitulo(string $titulo) {
        $this->Ln();
        $this->SetFont("helvetica", "", 9);
        $this->Cell(0, 10, utf8_decode($titulo));
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
    }

    function body() {
        $this->AddPage();
    }

    function Footer() {
        $fecha = date('d/m/Y');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Helvetica', 'I', 10);
        // Print centered page number
        $this->Cell(120, 10, utf8_decode('Fecha de Generación: ') . $fecha, 0, 0, 'L');
        $this->Cell(68, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    // Tabla simple
    function BasicTable($header, $data) {
        $this->Ln();
        $ancho = ($this->GetPageWidth() - 20) / count($header);
        // Cabecera
        foreach ($header as $col) {
            $this->Cell($ancho, 7, $col, 1);
        }
        $this->Ln();
        // Datos
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell($ancho, 6, $col, 1);
            }
            $this->Ln();
        }
    }

    function tablaImagenes(array $imagenes) {
        $countFilas = ((count($imagenes) / 4) < 0.5) ? round(count($imagenes) / 4, 0, PHP_ROUND_HALF_UP) + 1 : round(count($imagenes) / 4, 0, PHP_ROUND_HALF_UP);
        $columna = 0;
        $listaImagenes = array();
        $tempImagenes = array();

        for ($j = 0; $j < $countFilas; $j++) {

            foreach ($imagenes as $imagen) {
                if ($columna < 4) {
                    array_push($tempImagenes, $imagen);
                    $columna += 1;
                }
            }
            array_push($listaImagenes, $tempImagenes);

            for ($i = 0; $i < 4; $i++) {
                unset($imagenes[$i]);
            }
            $tempImagenes = array();
            $columna = 0;
        }

        $ancho = $this->GetPageWidth() - 20;
        $y = $this->GetY(); //86
        $x = 20;
        foreach ($listaImagenes as $row) {
            foreach ($row as $col) {
                if ($x < $ancho) {
                    $this->Cell(0, 10, $y . " - " . $x, 1);
                    $this->Image('.' . $col, $x, $y, 40, 35);
                    $x += 35;
                }                                 
            }
            $x = 20;
            $y = $this->GetY() + 30;
        }
    }

}
