<?php

namespace Librerias\Poliza;

use Controladores\Controller_Base_General as General;

class Poliza extends General {

    private $usuario;
    private $DBP;
    private $catalogo;
    private $servicio;

    public function __construct() {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Registro_Usuario::factory();
        $this->DBP = \Modelos\Modelo_Poliza::factory();
        $this->catalogo = \Librerias\Generales\Catalogo::factory();
        $this->servicio = \Librerias\Generales\Servicio::factory();
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

        if (in_array('227', $usuario['PermisosAdicionales']) || in_array('227', $usuario['Permisos'])) {
            $vueltasAsociados = $this->consultaTodasVueltasAsociados();
        } else if (in_array('228', $usuario['PermisosAdicionales']) || in_array('228', $usuario['Permisos'])) {
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
                                                                    cvu.IdJefe,
                                                                    (SELECT estatus(IdEstatus) FROM t_servicios_ticket WHERE Id = tfo.IdServicio) EstatusServicio
                                                                    FROM t_facturacion_outsourcing tfo
                                                                    INNER JOIN t_servicios_ticket tst
                                                                    ON tst.Id = tfo.IdServicio
                                                                    INNER JOIN cat_v3_usuarios cvu
                                                                    ON cvu.Id = tst.Atiende
                                                                    WHERE cvu.IdJefe = "' . $usuario['Id'] . '"
                                                                    AND (CASE
                                                                            WHEN tfo.Vuelta = 1 THEN tst.IdEstatus IN(3,4)
                                                                            WHEN tfo.Vuelta > 1 THEN tst.IdEstatus = 4 END)
                                                                    AND tfo.IdEstatus = 8
                                                                    AND tfo.Fecha >= "2018-09-06"
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
                                                                        AND tfo.Fecha >= "2018-09-06"
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
                                                                    AND tfo.Fecha >= "2018-09-06"
                                                                    ORDER BY tfo.Folio ASC');
        } else {
            $vueltasAsociados = array();
        }

        return $vueltasAsociados;
    }

    public function consultaTodasVueltasAsociados() {
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
                                                    WHERE tfo.Fecha >= "2018-09-06"
                                                    ORDER BY tfo.Folio ASC');
    }

}
