<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Poliza extends Modelo_Base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getTiposProyecto() {
        $datos = array();
        $consulta = $this->encontrar('cat_tipos_proyecto', array('Flag' => '1'));
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array('Id' => $value['Id'], 'Nombre' => $value['Nombre']));
            }
        }
        return $datos;
    }

    /*
     * Encargado de lista de Proyectos creados que tienen estatus abierto (sin iniciar)
     * 
     * @return array regresa datos de los proyectos creados con estatus abierto (sin iniciar)
     */

    public function getProyectosSinAtender() {
        $datos = array();
        $consulta = $this->consulta('select p.Id,p.Ticket,p.Nombre,e.Nombre Estado,s.Nombre Sucursal,p.FechaInicio,p.FechaTermino '
                . 'from t_proyectos p inner join cat_v3_sucursales s on p.IdSucursal = s.Id '
                . 'inner join cat_v3_estados e on s.IdEstado = e.Id where p.IdEstatus = 1');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                array_push($datos, array(
                    'Id' => $value['Id'],
                    'Ticket' => $value['Ticket'],
                    'Nombre' => $value['Nombre'],
                    'Sucursal' => $value['Sucursal'],
                    'Estado' => $value['Estado'],
                    'FechaInicio' => $value['FechaInicio'],
                    'FechaTermino' => $value['FechaTermino']
                ));
            }
        }
        return $datos;
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getSolicitudesMultimedia(string $email) {
        $datos = array();
        $consulta = parent::connectDBAdist2()->query("call getTicketsIngenieroByEmail('" . $email . "')");
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $item) {
                array_push($datos, array('Ticket' => $item['Ticket'], 'Folio' => $item['Folio'], 'Fecha' => $item['Fecha'], 'Sucursal' => $item['Sucursal'], 'Estatus' => $item['Estatus']));
            }
            return $datos;
        }
    }

    /*
     * Encargado de insertar el registro de las solicitudes de multimedia
     *  $datos = datos para insertar en la tabla t_minutas
     */

    public function setSolicitudesMultimedia(array $datos) {
        $consulta = parent::connectDBAdist2()->query("INSERT INTO t_tiempos_multimedia (IdUsuario, Ticket, FechaSolicitud, FechaApoyo, FechaCaptura) VALUES (" . $datos['IdUsuario'] . ", " . $datos['Ticket'] . ", '" . $datos['FechaSolicitud'] . "', '" . $datos['FechaApoyo'] . "', '" . $datos['FechaCaptura'] . "')");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar el registro de las solicitudes de multimedia
     *  $datos = datos para actualizar en la tabla t_minutas
     */

    public function actualizarSolicitudesMultimedia(array $datos) {
        $consulta = parent::connectDBAdist2()->query("UPDATE t_tiempos_multimedia 
                                                      SET FechaSolicitud='" . $datos['FechaSolicitud'] . "', FechaApoyo='" . $datos['FechaApoyo'] . "', FechaCaptura='" . $datos['FechaCaptura'] . "'
                                                      WHERE Ticket=" . $datos['Ticket']);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de mostrar solicitudes de multimedia
     *  $dato = datos para insertar en la tabla t_minutas
     */

    public function consultaSolicitudesMinuta(string $dato) {
        $datos = array();
        $consulta = parent::connectDBAdist2()->query("SELECT * FROM t_tiempos_multimedia WHERE Ticket=" . $dato);
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $item) {
                array_push($datos, array(
                    'Id' => $item['Id'],
                    'IdUsuario' => $item['IdUsuario'],
                    'Ticket' => $item['Ticket'],
                    'FechaSolicitud' => $item['FechaSolicitud'],
                    'EvidenciaSolicitud' => $item['EvidenciaSolicitud'],
                    'FechaApoyo' => $item['FechaApoyo'],
                    'EvidenciaApoyo' => $item['EvidenciaApoyo'],
                    'FechaCaptura' => $item['FechaCaptura']
                ));
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de insertar las evidencias en solicitudes a multimedia
     *  $data = trae el IdUsuario y ticket de la solicitud
     *  $campoBD = campo donde se va actulizar en la tabla
     *  $datos = datos para insertar en la tabla t_minutas
     */

    public function insertarEvidenciasSM(array $data, string $campoBD, array $datos) {
        $consulta = parent::connectDBAdist2()->query("INSERT INTO t_tiempos_multimedia (IdUsuario, Ticket, " . $campoBD . ", FechaCaptura) VALUES (" . $data['IdUsuario'] . ", " . $data['Ticket'] . ", '" . $datos['Archivo'] . "', '" . $data['FechaCaptura'] . "')");
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar las evnidencias en solicitudes a multimedia
     *  $data = trae el IdUsuario y ticket de la solicitud
     *  $campoBD = campo donde se va actulizar en la tabla
     *  $datos = datos para insertar en la tabla t_minutas
     */

    public function actualizarEvidenciasSM(array $data, string $campoBD, array $datos) {
        $consulta = parent::connectDBAdist2()->query("UPDATE t_tiempos_multimedia 
                                                      SET " . $campoBD . "='" . $datos['Archivo'] . "', FechaCaptura= '" . $data['FechaCaptura'] . "'
                                                      WHERE Ticket=" . $data['Ticket']);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function mostrarNombreEvidencia(string $campoBD, string $ticket) {
        $datos = array();
        $consulta = parent::connectDBAdist2()->query("SELECT " . $campoBD . " FROM t_tiempos_multimedia WHERE Ticket = " . $ticket);
        if (!empty($consulta)) {
            foreach ($consulta->result_array() as $item) {
                array_push($datos, array(
                    $campoBD => $item[$campoBD],
                ));
            }
            return $datos;
        } else {
            return FALSE;
        }
    }

    public function insertarServiciosTicketCorrectivosSolicitudes(array $datos, array $datos2, array $datosExtra) {
        $this->iniciaTransaccion();
        $this->insertar('t_servicios_ticket', $datos);
        $IdServicio = parent::connectDBPrueba()->insert_id();

        if ($datosExtra['Tabla'] === 't_correctivos_solicitudes_equipo') {
            $idTabla = 'IdModelo';
        } else {
            $idTabla = 'IdRefaccion';
        }

        switch ($datosExtra['TipoSolicitud']) {
            case 'almacen':
                $recibeSolicitud = '1';
                break;
            case 'ti':
                $recibeSolicitud = '2';
                break;
        }

        foreach ($datos2 as $value) {
            $this->insertar($datosExtra['Tabla'], array(
                'IdServicioOrigen' => $datosExtra['Servicio'],
                'IdServicio' => $IdServicio,
                $idTabla => $value[0],
                'Cantidad' => $value[2]));
        }

        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datosExtra['Servicio'],
            'IdTipoProblema' => $datosExtra['TipoProblema'],
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datos['FechaCreacion'],
            'RecibeSolicitud' => $recibeSolicitud));
        $this->insertar('t_servicios_relaciones', array(
            'IdServicioOrigen' => $datosExtra['Servicio'],
            'IdServicioNuevo' => $IdServicio));

        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarCorrectivosSolicitudes(array $datos2, array $datosExtra) {
        $this->iniciaTransaccion();

        if ($datosExtra['Tabla'] === 't_correctivos_solicitudes_equipo') {
            $idTabla = 'IdModelo';
        } else {
            $idTabla = 'IdRefaccion';
        }

        foreach ($datos2 as $value) {
            $this->insertar($datosExtra['Tabla'], array(
                'IdServicioOrigen' => $datosExtra['Servicio'],
                'IdServicio' => $datosExtra['Servicio'],
                $idTabla => $value[0],
                'Cantidad' => $value[2]));
        }
        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datosExtra['Servicio'],
            'IdTipoProblema' => $datosExtra['TipoProblema'],
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datosExtra['FechaCreacion']));

        $this->terminaTransaccion();
        return $datosExtra['Servicio'];
    }

    public function insertarCorrectivoProblemasRespaldo(array $datos, array $datos2) {
        $this->iniciaTransaccion();

        if ($datos['EsRespaldo'] === '1') {
            $campoVariantes1 = 'IdModelo';
            $campoVariantes2 = 'Serie';
        } else {
            $campoVariantes1 = 'Autoriza';
            $campoVariantes2 = 'Evidencia';
        }

        $this->insertar('t_correctivos_garantia_respaldo', array(
            'IdServicio' => $datos['IdServicio'],
            'IdUsuario' => $datos['IdUsuario'],
            'EsRespaldo' => $datos['EsRespaldo'],
            'SolicitaEquipo' => $datos['SolicitaEquipo'],
            $campoVariantes1 => $datos['campoVariantes1'],
            $campoVariantes2 => $datos['campoVariantes2'],
            'Fecha' => $datos['Fecha']));
        $IdServicio = parent::connectDBPrueba()->insert_id();

        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datos2['IdServicio'],
            'IdTipoProblema' => $datos2['IdTipoProblema'],
            'IdUsuario' => $datos2['IdUsuario'],
            'Fecha' => $datos['Fecha']));
        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarServicioCorrectivoSolicitudGarantiaRespaldo(array $dataInformacionGarantia, array $dataNuevoServicio, array $dataCorrectivosSolicitudesEquipo, array $dataCorrectivosProblemas) {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_garantia_respaldo', $dataInformacionGarantia);
        $this->insertar('t_servicios_ticket', $dataNuevoServicio);
        $IdServicio = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solicitudes_equipo', array(
            'IdServicioOrigen' => $dataCorrectivosSolicitudesEquipo['IdServicioOrigen'],
            'IdServicio' => $IdServicio,
            'IdModelo' => $dataCorrectivosSolicitudesEquipo['IdModelo'],
            'Cantidad' => $dataCorrectivosSolicitudesEquipo['Cantidad']));
        $this->insertar('t_correctivos_problemas', $dataCorrectivosProblemas);
        $this->insertar('t_servicios_relaciones', array(
            'IdServicioOrigen' => $dataCorrectivosSolicitudesEquipo['IdServicioOrigen'],
            'IdServicioNuevo' => $IdServicio));

        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionEquipo(array $dataCorrectivosSoluciones, string $solucion) {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solucion_sin_equipo', array(
            'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
            'IdSolucionEquipo' => $solucion));

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionRefaccion(array $dataCorrectivosSoluciones, array $dataCorrectivosSolucionesRefaccion) {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();
        foreach ($dataCorrectivosSolucionesRefaccion as $value) {
            $this->insertar('t_correctivos_solucion_refaccion', array(
                'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
                'IdRefaccion' => $value[0],
                'Cantidad' => $value[2]));
        }

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionCambio(array $dataCorrectivosSoluciones, string $equipo, string $serie, array $dataCenso) {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solucion_cambio', array(
            'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
            'IdModelo' => $equipo,
            'Serie' => $serie));

        $this->eliminar('t_censos', array('IdServicio' => $dataCenso['IdServicioCenso'], 'IdArea' => $dataCenso['IdArea'], 'IdModelo' => $dataCenso['IdModelo'], 'Punto' => $dataCenso['Punto']));
        $this->insertar('t_censos', array(
            'IdServicio' => $dataCenso['IdServicioCenso'],
            'IdArea' => $dataCenso['IdArea'],
            'IdModelo' => $equipo,
            'Punto' => $dataCenso['Punto'],
            'Serie' => $serie,
            'Extra' => $dataCenso['Terminal']));

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarCorrectivosSolicitudesProblemas(array $datos, array $datosExtra) {
        $this->iniciaTransaccion();

        if ($datos['tipoSolicitud'] === 'equipo') {
            $tabla = 't_correctivos_solicitudes_equipo';
            $idTabla = 'IdModelo';
            $idTipoProblema = '2';
        } else {
            $tabla = 't_correctivos_solicitudes_refaccion';
            $idTabla = 'IdRefaccion';
            $idTipoProblema = '1';
        }

        foreach ($datos['equiposSolicitudes'] as $value) {
            $this->insertar($tabla, array(
                'IdServicioOrigen' => $datos['servicio'],
                'IdServicio' => $datos['servicio'],
                $idTabla => $value[0],
                'Cantidad' => $value[2]));
        }
        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datos['servicio'],
            'IdTipoProblema' => $idTipoProblema,
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datosExtra['FechaCreacion'],
            'RecibeSolicitud' => '3'));

        $this->terminaTransaccion();
        return TRUE;
    }

    public function consultaCorrectivosSolucionesServicio(string $servicio) {
        $consulta = $this->consulta('SELECT 
                                        *
                                    FROM
                                        t_correctivos_soluciones
                                    WHERE
                                        IdServicio = "' . $servicio . '"
                                    ORDER BY Id DESC
                                    LIMIT 1');
        return $consulta;
    }

    public function consultaCorreoCoordinadorPoliza() {
        $consulta = $this->consulta('SELECT 
                                        EmailCorporativo 
                                    FROM cat_v3_usuarios
                                    WHERE IdPerfil = 46');
        return $consulta;
    }

}
