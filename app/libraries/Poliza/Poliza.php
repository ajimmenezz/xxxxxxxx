<?php

namespace Librerias\Poliza;

use Controladores\Controller_Base_General as General;
use Librerias\Generales\PDF as PDF;

class Poliza extends General {

    private $usuario;
    private $DBP;
    private $catalogo;
    private $servicio;
    private $seguimiento;
    private $pdf;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->servicio = \Librerias\Generales\Servicio::factory();
        $this->seguimiento = \Librerias\Poliza\Seguimientos::factory();
        parent::getCI()->load->helper(array('FileUpload', 'date'));
        $this->pdf = new PDFAux();
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
        foreach ($consultaAreaCategoria as $value) {
            if(!array_key_exists($value['Areas'], $pushArea)){
                $pushArea[$value['Areas']] = [];
            }
             
            if(!array_key_exists($value['Etiqueta'], $pushArea[$value['Areas']])){
                $pushArea[$value['Areas']][$value['Etiqueta']] = [];
            }
                        
            array_push($pushArea[$value['Areas']][$value['Etiqueta']], $value['Punto']);
            array_push($pushRevision,array('area' => $value['Areas'], 'idRevisionArea' => $value['Id'], 'punto' => $value['Punto']));
            
        }
        
        foreach ($pushRevision as $revision) {
            
            $datosRevision = array('servicio' => $datos['servicio'],'idCategoria' => $datos['categoria'], 'idRevisionArea' => $revision['idRevisionArea'], 'punto' => $revision['punto']);
            $consulta = $this->DBP->mostrarRevisionPunto($datosRevision);
            
            foreach ($consulta as $value2) {
                array_push($pushChecklist, $value2);
            }
        }
        
        return ['pushArea' => $pushArea, 'pushRevision' => $pushRevision, 'pushChecklist' => $pushChecklist];
    }
    
    public function guardarPuntoRevision(array $datos) {   
       
        $CI = parent::getCI();
        $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/';
        $archivos = setMultiplesArchivos($CI, 'inputArchivoPunto', $carpeta);
                        
        if ($archivos) {
            $consulta = $this->DBP->mostrarRevisionPunto($datos);
            $archivosImplode = implode(',', $archivos);
            
            if(empty($consulta)){
                $arrayDatos = array(
                                'IdServicio' => $datos['servicio'],
                                'idCategoria' => $datos['idCategoria'],
                                'idRevisionArea' => $datos['idRevisionArea'],
                                'Punto' => $datos['punto'],
                                'Evidencia' => $archivosImplode
                                );
                $resultado = $this->DBP->insertarRevisionPunto($arrayDatos);
            }
            
            $evidenciaNueva = null;
            if(!empty($consulta[0]['Evidencia'])){
                $evidenciaNueva =  $consulta[0]['Evidencia'] . ',' . $archivos[0];
                
                $arrayActualizar = array('Id' => $consulta[0]['Id'],'evidencia' => $evidenciaNueva,'tipoActualizar' => 1);
                $resultado = $this->DBP->actulaizarRevisionPunto($arrayActualizar);
            }
            
            
        }else{
            $resultado = "No hay ninguna evidencia registrada";
        }
        
        return $resultado;
    }
    
//    public function consultarRevisionPunto(array $datos) {
//        
//        $consulta = $this->DBP->mostrarRevisionPunto($datos);
//        return $consulta;
//        
//    }
//    
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
            $datosActualizar = Array('Id' => $consultaPunto[0]['Id'] , 'evidencia' => $evidenciaImplode, 'tipoActualizar' => 1);
            
            $actualizarEvidencia = $this->DBP->actulaizarRevisionPunto($datosActualizar);
            
        }
        return true;
    }
    
    public function actualizarRevisionPunto(array $datos) {
        
        $consultaPunto = $this->DBP->mostrarRevisionPunto($datos);
        
        $datosActualizae = Array('Flag' => 0, 'Id' => $consultaPunto[0]['Id'], 'tipoActualizar' => 2);
        $actualizarFlag = $this->DBP->actulaizarRevisionPunto($datosActualizae);
//        print_r($actualizarFlag);
//        return true;
        return $actualizarFlag;
    }
    
    public function revisionTecnica(array $datos) {
        
        $generales = $this->servicio->getGeneralesByServicio($datos['servicio']);
        $data = [
            'areaPunto' => $this->seguimiento->consultaAreaPuntoXSucursal($generales['IdSucursal'],'Area, Punto')
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
        return array_merge($insertar,['listaFallas' => $lista]);
    }
    
    public function mostrarFallasTecnicasCheclist(array $servicio) {
        $consulta = $this->DBP->mostrarFallasTecnicas($servicio['servicio']);
        return $consulta;
    }
    
    public function actualizarRevisionTecnica(array $datos) {
        if (isset($datos['idRevision'])) {
            $consultaRevision = $this->DBP->mostrarFallasTecnicas($datos['servicio'],$datos['idRevision']);
            return ['modal' => parent::getCI()->load->view('Poliza/Modal/FormularioRevisionTecnicaChecklist.php', ['data' => $consultaRevision[0]], TRUE)];
        }
    }
    
    public function editarRevisionTecnicaChecklist(array $datos) {
        
        $this->DBP->actualizaFallasTecnicas($datos);
        $consultar = $this->DBP->mostrarFallasTecnicas($datos['servicio']);
        return $consultar;
        
    }
    
    public function pruebaPDF(){
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial','B',16);
        $this->pdf->Cell(40,10,'Hola, Mundo',1);
        $this->pdf->Cell(60,10,'Hecho con FPDF.',0,1,'C');  
        $this->pdf->firma('Yoselin', 'pregramadora');
        $carpeta = $this->pdf->definirArchivo('PruebaPDF', 'PruebaPDF');
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }
       
}

class PDFAux extends PDF {

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

}