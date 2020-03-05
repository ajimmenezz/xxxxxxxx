<?php

namespace Modelos;

use Librerias\Modelos\Base as Modelo_Base;

class Modelo_Poliza extends Modelo_Base
{

    private $usuario;

    public function __construct()
    {
        parent::__construct();
        $this->usuario = \Librerias\Generales\Usuario::getCI()->session->userdata();
    }

    /*
     * Encargado de la lista de todos los tipos de proyectos vigentes
     * 
     * @return array regresa Id y Nombre de los tipos de proyectos vigentes
     */

    public function getTiposProyecto()
    {
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

    public function getProyectosSinAtender()
    {
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

    public function getSolicitudesMultimedia(string $email)
    {
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

    public function setSolicitudesMultimedia(array $datos)
    {
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

    public function actualizarSolicitudesMultimedia(array $datos)
    {
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

    public function consultaSolicitudesMinuta(string $dato)
    {
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

    public function insertarEvidenciasSM(array $data, string $campoBD, array $datos)
    {
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

    public function actualizarEvidenciasSM(array $data, string $campoBD, array $datos)
    {
        $consulta = parent::connectDBAdist2()->query("UPDATE t_tiempos_multimedia 
                                                      SET " . $campoBD . "='" . $datos['Archivo'] . "', FechaCaptura= '" . $data['FechaCaptura'] . "'
                                                      WHERE Ticket=" . $data['Ticket']);
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function mostrarNombreEvidencia(string $campoBD, string $ticket)
    {
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

    public function insertarServiciosTicketCorrectivosSolicitudes(array $datos, array $datos2, array $datosExtra)
    {
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
                'Cantidad' => $value[2]
            ));
        }

        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datosExtra['Servicio'],
            'IdTipoProblema' => $datosExtra['TipoProblema'],
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datos['FechaCreacion'],
            'RecibeSolicitud' => $recibeSolicitud
        ));
        $this->insertar('t_servicios_relaciones', array(
            'IdServicioOrigen' => $datosExtra['Servicio'],
            'IdServicioNuevo' => $IdServicio
        ));

        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarCorrectivosSolicitudes(array $datos2, array $datosExtra)
    {
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
                'Cantidad' => $value[2]
            ));
        }
        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datosExtra['Servicio'],
            'IdTipoProblema' => $datosExtra['TipoProblema'],
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datosExtra['FechaCreacion']
        ));

        $this->terminaTransaccion();
        return $datosExtra['Servicio'];
    }

    public function insertarCorrectivoProblemasRespaldo(array $datos, array $datos2)
    {
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
            'Fecha' => $datos['Fecha']
        ));
        $IdServicio = parent::connectDBPrueba()->insert_id();

        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datos2['IdServicio'],
            'IdTipoProblema' => $datos2['IdTipoProblema'],
            'IdUsuario' => $datos2['IdUsuario'],
            'Fecha' => $datos['Fecha']
        ));
        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarServicioCorrectivoSolicitudGarantiaRespaldo(array $dataInformacionGarantia, array $dataNuevoServicio, array $dataCorrectivosSolicitudesEquipo, array $dataCorrectivosProblemas)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_garantia_respaldo', $dataInformacionGarantia);
        $this->insertar('t_servicios_ticket', $dataNuevoServicio);
        $IdServicio = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solicitudes_equipo', array(
            'IdServicioOrigen' => $dataCorrectivosSolicitudesEquipo['IdServicioOrigen'],
            'IdServicio' => $IdServicio,
            'IdModelo' => $dataCorrectivosSolicitudesEquipo['IdModelo'],
            'Cantidad' => $dataCorrectivosSolicitudesEquipo['Cantidad']
        ));
        $this->insertar('t_correctivos_problemas', $dataCorrectivosProblemas);
        $this->insertar('t_servicios_relaciones', array(
            'IdServicioOrigen' => $dataCorrectivosSolicitudesEquipo['IdServicioOrigen'],
            'IdServicioNuevo' => $IdServicio
        ));

        $this->terminaTransaccion();
        return $IdServicio;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionEquipo(array $dataCorrectivosSoluciones, string $solucion)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solucion_sin_equipo', array(
            'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
            'IdSolucionEquipo' => $solucion
        ));

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionRefaccion(array $dataCorrectivosSoluciones, array $dataCorrectivosSolucionesRefaccion)
    {
        $this->iniciaTransaccion();


        /* Se ejecuta el siguiente código para que se desbloqueen todos los
         * productos bloqueados en la última solución, ya sean componentes 
         * o equipos completos         
         */

        $this->queryBolean("update
                            t_inventario
                            set Bloqueado = 0
                            where Id in (
                            select 
                            IdInventario 
                            from t_correctivos_solucion_refaccion 
                            where IdSolucionCorrectivo in (select MAX(Id) from t_correctivos_soluciones where IdServicio = '" . $dataCorrectivosSoluciones['IdServicio'] . "')
                            and IdInventario is not null
                            and IdInventario <> '')");

        $this->queryBolean("update t_correctivos_solucion_refaccion set IdInventario = null where IdSolucionCorrectivo = (select MAX(Id) from t_correctivos_soluciones where IdServicio = '" . $dataCorrectivosSoluciones['IdServicio'] . "')");
        /**/

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();

        foreach ($dataCorrectivosSolucionesRefaccion as $value) {
            if (isset($value['Id'])) {
                $this->insertar('t_correctivos_solucion_refaccion', array(
                    'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
                    'IdRefaccion' => $value['IdProducto'],
                    'Cantidad' => $value['Cantidad'],
                    'IdInventario' => $value['Id']
                ));
                $this->actualizar("t_inventario", ['Bloqueado' => 1], ['Id' => $value['Id']]);
            } else {
                $this->insertar(
                    't_correctivos_solucion_refaccion',
                    array(
                        'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
                        'IdRefaccion' => $value[0],
                        'Cantidad' => $value[2]
                    )
                );
            }
        }

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarServicioCorrectivoSolicitudesSolucionCambio(array $dataCorrectivosSoluciones, string $equipo, string $serie, array $dataCenso, string $inventario = null, string $operacion)
    {
        $this->iniciaTransaccion();

        /* Se ejecuta el siguiente código para que se desbloqueen todos los
         * productos bloqueados en la última solución, ya sean componentes 
         * o equipos completos         
         */

        $this->queryBolean("update
                            t_inventario
                            set Bloqueado = 0
                            where Id in (
                            select 
                            IdInventario 
                            from t_correctivos_solucion_cambio 
                            where IdSolucionCorrectivo in (select MAX(Id) from t_correctivos_soluciones where IdServicio = '" . $dataCorrectivosSoluciones['IdServicio'] . "')
                            and IdInventario is not null
                            and IdInventario <> '')");

        $this->queryBolean("update t_correctivos_solucion_cambio set IdInventario = null where IdSolucionCorrectivo = (select MAX(Id) from t_correctivos_soluciones where IdServicio = '" . $dataCorrectivosSoluciones['IdServicio'] . "')");
        /**/

        $this->insertar('t_correctivos_soluciones', $dataCorrectivosSoluciones);
        $IdCorrectivoSoluciones = parent::connectDBPrueba()->insert_id();
        $this->insertar('t_correctivos_solucion_cambio', array(
            'IdSolucionCorrectivo' => $IdCorrectivoSoluciones,
            'IdModelo' => $equipo,
            'Serie' => $serie,
            'IdInventario' => $inventario
        ));

        $this->actualizar("t_inventario", ['Bloqueado' => 1], ['Id' => $inventario]);

        if (in_array($operacion, [2, '2'])) {
            $this->eliminar('t_censos', array('IdServicio' => $dataCenso['IdServicioCenso'], 'IdArea' => $dataCenso['IdArea'], 'IdModelo' => $dataCenso['IdModelo'], 'Punto' => $dataCenso['Punto']));
            $this->insertar('t_censos', array(
                'IdServicio' => $dataCenso['IdServicioCenso'],
                'IdArea' => $dataCenso['IdArea'],
                'IdModelo' => $equipo,
                'Punto' => $dataCenso['Punto'],
                'Serie' => $serie,
                'Extra' => $dataCenso['Terminal']
            ));
        }

        $this->terminaTransaccion();
        return $IdCorrectivoSoluciones;
    }

    public function insertarCorrectivosSolicitudesProblemas(array $datos, array $datosExtra)
    {
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
                'Cantidad' => $value[2]
            ));
        }
        $this->insertar('t_correctivos_problemas', array(
            'IdServicio' => $datos['servicio'],
            'IdTipoProblema' => $idTipoProblema,
            'IdUsuario' => $datosExtra['Usuario'],
            'Fecha' => $datosExtra['FechaCreacion'],
            'RecibeSolicitud' => '3'
        ));

        $this->terminaTransaccion();
        return TRUE;
    }

    public function consultaCorrectivosSolucionesServicio(string $servicio)
    {
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

    public function consultaCorreoCoordinadorPoliza()
    {
        $consulta = $this->consulta('SELECT 
                                        EmailCorporativo 
                                    FROM cat_v3_usuarios
                                    WHERE IdPerfil = 46');
        return $consulta;
    }

    public function consultaCategorias(int $idCategoria = null)
    {
        $condicion = (!is_null($idCategoria)) ? " where Id = '" . $idCategoria . "'" : '';
        $consulta = $this->consulta("SELECT 
                                        Id,
                                        Nombre,
                                        if(Flag = 1, 'Activo', 'Inactivo') as Estatus,
                                        Flag
                                    FROM cat_v3_checklist_poliza_categorias" . $condicion);
        return $consulta;
    }

    public function mostrarCategoriaRevisionPunto()
    {
        $consulta = $this->consulta("SELECT * 
                                    FROM t_checklist_revision_area tcra
                                    INNER JOIN cat_v3_checklist_poliza_categorias cvcpc on tcra.IdCategoria = cvcpc.Id
                                    GROUP BY tcra.IdCategoria");

        return $consulta;
    }

    public function agregarCategoria(string $categoria)
    {
        $insertar = $this->insertar("cat_v3_checklist_poliza_categorias", array('Nombre' => mb_strtoupper($categoria)));
        if (!is_null($insertar)) {
            return ['Id' => $this->ultimoId()];
        } else {
            return ['Id' => null, 'error' => $this->tipoError()];
        }
    }

    public function editarCategoria(array $datosCategoria)
    {
        $editar = $this->actualizar('cat_v3_checklist_poliza_categorias', array('Nombre' => $datosCategoria['Nombre'], 'Flag' => $datosCategoria['Flag']), array('Id' => $datosCategoria['Id']));

        if (!is_null($editar)) {
            return ['categoria' => $this->consultaCategorias($datosCategoria['Id'])];
        }
    }

    public function consultaListaPreguntas(int $idPregunta = null, int $idCategoria = null)
    {
        $condicion = (!is_null($idPregunta)) ? " WHERE Id = '" . $idPregunta . "'" : '';
        $consultaCategoria = (!is_null($idCategoria)) ? " WHERE IdCategoria = '" . $idCategoria . "'" : '';
        $consulta = $this->consulta("SELECT cvcf.Id,
                                        cvcf.IdCategoria,
                                        (SELECT cvcpc.Nombre FROM cat_v3_checklist_poliza_categorias cvcpc WHERE cvcpc.Id = cvcf.IdCategoria)as NombreCategoria,
                                        cvcf.Concepto,
                                        cvcf.Etiqueta,
                                        cvcf.AreasAtencion,
                                        (select GROUP_CONCAT(Nombre SEPARATOR '<br/>') as Areas from cat_v3_areas_atencion cvaa where concat('\"',cvaa.Id,'\"') REGEXP concat('\"',replace(cvcf.AreasAtencion,',','\"|\"'),'\"')) as Areas,
                                        if(Flag = 1, 'Activo', 'Inactivo') as Estatus,		
                                        cvcf.Flag
                                    FROM cat_v3_checklist_conceptos_fisicos cvcf" . $condicion . $consultaCategoria);

        return $consulta;
    }

    public function consultaAreasAtencion()
    {
        $arrayAreaAtencion = array();

        $consulta = $this->consulta("SELECT * FROM cat_v3_areas_atencion WHERE Flag = 1 ");
        foreach ($consulta as $value) {
            array_push($arrayAreaAtencion, array('id' => $value['Id'], 'text' => $value['Nombre']));
        }
        return $arrayAreaAtencion;
    }

    public function insertarPregunta(array $datos)
    {
        $insertar = $this->insertar('cat_v3_checklist_conceptos_fisicos', $datos);
        if (!is_null($insertar)) {
            return ['Id' => $this->ultimoId()];
        } else {
            return ['Id' => null, 'error' => $this->tipoError()];
        }
    }

    public function editarPregunta(array $datosPregunta)
    {

        $editar = $this->actualizar('cat_v3_checklist_conceptos_fisicos', $datosPregunta, array('Id' => $datosPregunta['Id']));
        $consultaPregunta = $this->consultaListaPreguntas($datosPregunta['Id']);

        if (!is_null($editar)) {
            return ['pregunta' => $consultaPregunta[0]];
        }
    }

    public function consultarRevisionArea(array $datos)
    {
        $consulta = $this->consulta("SELECT * FROM t_checklist_revision_area WHERE IdServicio = '" . $datos['servicio'] . "' AND IdCategoria = '" . $datos['idCategoria'] . "'");
        return $consulta;
    }

    public function mostrarRevisionAreaCategoria(array $datos)
    {

        $revisionArea = $this->consulta("SELECT
                                        tcra.Id,
                                        tcra.IdServicio,
                                        tcra.IdCategoria,
                                        tcra.IdAreaAtencion,
                                        tcra.IdConceptoFisico,
                                        areaAtencion(tcra.IdAreaAtencion) as Areas,
                                            CONCAT((SELECT Concepto FROM cat_v3_checklist_conceptos_fisicos cvccf WHERE cvccf.Id = tcra.IdConceptoFisico), '<br/>'  ,
                                            (SELECT Etiqueta FROM cat_v3_checklist_conceptos_fisicos cvccf WHERE cvccf.Id = tcra.IdConceptoFisico)) AS Etiqueta,    
                                        tc.Punto
                                        FROM t_checklist_revision_area tcra 
                                        inner join t_censos tc on tc.IdServicio = (select vucc.IdServicio from v_ultimo_censo_complejo vucc where vucc.IdSucursal = (select IdSucursal from t_servicios_ticket where Id = tcra.IdServicio)) and tc.IdArea = tcra.IdareaAtencion
                                        WHERE tcra.Flag = 0
                                        AND tcra.IdCategoria = '" . $datos['categoria'] . "'
                                        AND tcra.IdServicio = '" . $datos['servicio'] . "'
                                        group by Areas, Punto");
        return $revisionArea;
    }

    public function actualizarSucursal($datos)
    {
        $editar = $this->actualizar(
            't_servicios_ticket',
            array(
                'IdSucursal' => $datos['sucursal'],
            ),
            array('Id' => $datos['servicio'])
        );
        return $editar;
    }

    public function nombreArea($dato, $sucursal)
    {

        $consultaCenso = $this->consulta("SELECT * 
                                        FROM t_censos 
                                        WHERE IdServicio = (SELECT IdServicio FROM v_ultimo_censo_complejo WHERE IdServicio = (SELECT MAX(IdServicio) FROM v_ultimo_censo_complejo WHERE  idSucursal = " . $sucursal . ")) 
                                        GROUP BY IdArea");

        foreach ($consultaCenso as $value) {

            if ($value['IdArea'] == $dato) {

                $consulta = $this->consulta("SELECT Id,Nombre FROM cat_v3_areas_atencion WHERE Flag = 1 AND Id = " . $dato);
                return $consulta[0];
            }
        }
    }

    public function insertarRevisionAreas($datos)
    {
        if (isset($datos['DatosTabla'])) {

            foreach ($datos['DatosTabla'] as $value) {

                $datosInsertar = array(
                    'IdServicio' => $datos['IdServicio'],
                    'IdConceptoFisico' => $value['IdConceptoFisico'],
                    'IdCategoria' => $datos['IdCategoria'],
                    'IdAreaAtencion' => $value['IdAreaAtencion'],
                    'Flag' => $value['Flag']
                );

                $consulta = $this->consulta("SELECT * 
                                            FROM t_checklist_revision_area
                                            WHERE Id = (SELECT MAX(Id) FROM t_checklist_revision_area 
                                                        WHERE IdServicio = '" . $datosInsertar['IdServicio'] . "'
                                                        AND IdCategoria = '" . $datosInsertar['IdCategoria'] . "'
                                                        AND IdAreaAtencion = '" . $datosInsertar['IdAreaAtencion'] . "' 
                                                        AND IdConceptoFisico = '" . $datosInsertar['IdConceptoFisico'] . "')");

                if (!empty($consulta)) {
                    $registro = $this->actualizar("t_checklist_revision_area", array('Flag' => $datosInsertar['Flag']), array('Id' => $consulta[0]['Id']));
                } else {
                    $registro = $this->insertar('t_checklist_revision_area', $datosInsertar);
                }
            }

            if (!is_null($registro)) {
                return true;
            }
        } else {
            return false;
        }
    }

    public function obtenerIdRevicionArea(array $datos)
    {
        $consulta = $this->consulta("select 
                                            tcra.Id
                                    from cat_v3_areas_atencion cvaa
                                    inner join t_checklist_revision_area tcra
                                    on cvaa.Id = tcra.IdAreaAtencion
                                    where Nombre = '" . $datos['idRevisionArea'] . "'
                                    AND tcra.IdCategoria = '" . $datos['idCategoria'] . "'              
                                    AND tcra.IdServicio = '" . $datos['servicio'] . "'");
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                return $value['Id'];
            }
        } else {
            return null;
        }
    }

    public function obtenerEvidenciasPuntosCheckList(array $datos)
    {
        $consulta = $this->consulta('SELECT 
                                        Id,
                                        Evidencia 
                                    FROM
                                        t_checklist_revision_punto
                                    WHERE
                                        IdServicio = ' . $datos['servicio'] . ' AND IdCategoria = ' . $datos['idCategoria'] . '
                                            AND IdRevisionArea = ' . $datos['idRevisionArea'] . '
                                            AND Punto = ' . $datos['punto'] . '
                                            AND Flag = 1');
        if (!empty($consulta)) {
            foreach ($consulta as $value) {
                return array('Id' => $value['Id'], 'Evidencia' => $value['Evidencia']);
            }
        } else {
            return null;
        }
    }

    public function actualizarEvidencia(array $datos)
    {
        $consulta = $this->actualizar('t_checklist_revision_punto', array('Evidencia' => $datos['Evidencia']), array('Id' => $datos['Id']));
        if (!empty($consulta)) {
            return true;
        } else {
            return NULL;
        }
    }

    public function mostrarRevisionPunto(array $datos)
    {
        $consulta = $this->consulta("SELECT
                                        tcrp.Id,
                                        tcrp.IdServicio,
                                        tcrp.IdCategoria,
                                        tcrp.IdRevisionArea,
                                        tcrp.Punto,
                                        (SELECT areaAtencion(tcra.IdAreaAtencion) as Areas FROM t_checklist_revision_area tcra WHERE Id = '" . $datos['idRevisionArea'] . "') as Area,
                                        tcrp.Evidencia,
                                        tcrp.Flag
                                    FROM t_checklist_revision_punto tcrp
                                    WHERE IdCategoria = '" . $datos['idCategoria'] . "'
                                    AND Flag = 1
                                    AND IdServicio = '" . $datos['servicio'] . "'
                                    AND IdRevisionArea = '" . $datos['idRevisionArea'] . "'
                                    AND Punto = '" . $datos['punto'] . "'");

        if (!is_null($consulta)) {
            return $consulta;
        } else {
            return "No existe registro";
        }
    }

    public function insertarRevisionPunto(array $datos)
    {
        $insertar = $this->insertar('t_checklist_revision_punto', $datos);

        if (!empty($insertar)) {
            return true;
        } else {
            return NULL;
        }
    }

    public function actulaizarRevisionPunto(array $datos)
    {
        $tabla = "t_checklist_revision_punto";

        if ($datos['tipoActualizar'] == 1) {
            // actualiza evidencias
            $actualizar = $this->actualizar($tabla, array('Evidencia' => $datos['evidencia']), array('Id' => $datos['Id']));
        } else if ($datos['tipoActualizar'] == 2) {
            // actualiza flag
            $actualizar = $this->actualizar($tabla, array('Flag' => $datos['Flag']), array('Id' => $datos['Id']));
        }

        if (!is_null($actualizar)) {
            return $actualizar;
        } else {
            return "Error al guardar informacion";
        }
    }

    public function mostrarFallasTecnicas($servicio, $idRevision = null)
    {
        $condicion = (!is_null($idRevision)) ? " AND Id = '" . $idRevision . "'" : '';
        $consultaFallas = "SELECT 
                                tcrt.Id,
                                tcrt.IdArea,
                                tcrt.Punto,
                                tcrt.IdModelo,
                                tcrt.Terminal,
                                tcrt.IdTipoDiagnostico,
                                tcrt.IdComponente,
                                tcrt.IdTipoFalla,
                                tcrt.Evidencias,
                                tcrt.IdFalla,
                                concat(areaAtencion(tcrt.IdArea),' ',tcrt.Punto) as AreaPunto,
                                modelo(tcrt.IdModelo) as Equipo,
                                tcrt.Serie,
                                (select Nombre from cat_v3_componentes_equipo where Id = tcrt.IdComponente) as Componente,
                                (select Nombre from cat_v3_tipos_diagnostico_correctivo where Id = tcrt.IdTipoDiagnostico) as TipoDiagnostico,
                                CASE	
                                when tcrt.IdTipoDiagnostico in (2,3) then (select Nombre from cat_v3_fallas_equipo where Id = tcrt.IdFalla)	
                                when tcrt.IdTipoDiagnostico = 4 then (select Nombre from cat_v3_fallas_refaccion where Id = tcrt.IdFalla)
                                else ''
                                END as Falla,
                                DATE_FORMAT(tcrt.Fecha,'%d/%m/%Y') as Fecha,
                                tcrt.Flag                                
                            FROM 
                            t_checklist_revision_tecnica tcrt
                            where tcrt.IdServicio = '" . $servicio . "'
                            and tcrt.Flag = 1" . $condicion;

        $consulta = $this->consulta($consultaFallas);
        return $consulta;
    }

    public function guardarRevisionTecnicaCheck(array $datos)
    {

        $this->iniciaTransaccion();

        $this->insertar("t_checklist_revision_tecnica", $datos);

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function actualizaFallasTecnicas(array $datos)
    {
        $actualizar = $this->actualizar("t_checklist_revision_tecnica", array('Flag' => $datos['estatusRevision']), array('Id' => $datos['idRevisionTecnica'], 'IdServicio' => $datos['servicio']));
        return $actualizar;
    }

    //PDF

    public function consultaRevisionPunotPDF($servicio)
    {
        $consulta = $this->consulta("SELECT 
                                        tcrp.Id,
                                        tcrp.IdServicio,
                                        (SELECT Nombre FROM cat_v3_checklist_poliza_categorias ctcpc WHERE ctcpc.Id = tcrp.IdCategoria) as Categoria,
                                        AREAATENCION(tcra.IdAreaAtencion) AS Areas,
                                        CONCAT('Punto ',tcrp.Punto) Punto,
                                        cvccf.Concepto,
                                        cvccf.Etiqueta,
                                        tcrp.Evidencia
                                    FROM t_checklist_revision_punto tcrp
                                    INNER JOIN t_checklist_revision_area tcra on tcra.Id = tcrp.IdRevisionArea
                                    INNER JOIN cat_v3_checklist_conceptos_fisicos cvccf on cvccf.Id = tcra.IdConceptoFisico
                                    WHERE tcrp.Flag = 1 
                                    AND tcrp.IdServicio = '" . $servicio . "'");
        return $consulta;
    }

    public function concluirServicio(array $dato)
    {

        $this->actualizar('t_servicios_ticket', array(
            'IdEstatus' => $dato['Estatus'],
            'FechaConclusion' => $dato['FechaConclusion'],
            'Firma' => $dato['Firma'],
            'NombreFirma' => $dato['NombreFirma'],
            'CorreoCopiaFirma' => $dato['CorreoCopiaFirma'],
            'FechaFirma' => $dato['FechaFirma']
        ), array('Id' => $dato['servicio']));


        return $this->consulta("SELECT * FROM t_servicios_ticket where Id = '" . $dato['servicio'] . "'");
    }

    public function getNombreServicio(string $servicio)
    {
        $consulta = $this->consulta("select
                                            tipoServicio(tst.IdTipoServicio) as nombreServicio
                                    from t_servicios_ticket tst
                                    where Id = '" . $servicio . "'");
        return $consulta[0];
    }

    public function mostrarServicio($servicio)
    {
        $consulta = $this->consulta("select * from t_servicios_ticket where Id = '" . $servicio . "'");
        return $consulta;
    }

    public function insertarNuevoServicioCorrectivo(array $datos)
    {
        $respuesta = null;
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $consultaChecklistRevisionTecnica = $this->mostrarFallasTecnicas($datos['IdServicio']);

        foreach ($consultaChecklistRevisionTecnica as $falla) {


            $this->iniciaTransaccion();
            $descripcionCorrectivo = "Seguimiento de Diagnostico en Checklist para el equipo " . $falla['Equipo'] . " con la Serie " . $falla['Serie'] . " por falla " . $falla['Falla'];

            // insertando en t_servicios_ticket
            $datosInsertarTicket = array(
                'Ticket' => $datos['Ticket'],
                'IdSolicitud' => $datos['IdSolicitud'],
                'IdTipoServicio' => 20,
                'IdSucursal' => $datos['IdSucursal'],
                'IdEstatus' => 2,
                'Solicita' => $this->usuario['Id'],
                'Atiende' => $this->usuario['Id'],
                'FechaCreacion' => $fecha,
                'FechaInicio' => $fecha,
                'Descripcion' => $descripcionCorrectivo,
                'IdServicioOrigen' => $datos['IdServicio']
            );

            $insertarTicket = $this->insertar('t_servicios_ticket', $datosInsertarTicket);

            if (!empty($insertarTicket)) {
                $insertoTicket = "t_servicios_ticket ok";
                $ultimaIdServicioTicket = $this->ultimoId();
            } else {
                $insertoTicket = "t_servicios_ticket error";
            }

            // insertando en t_correctivos_generales            
            $datosInsertarCorrectivos = array(
                'IdServicio' => $ultimaIdServicioTicket,
                'IdArea' => $falla['IdArea'],
                'Punto' => $falla['Punto'],
                'IdModelo' => $falla['IdModelo'],
                'Serie' => $falla['Serie'],
                'Terminal' => $falla['Terminal'],
            );

            $insertarCorrectivos = $this->insertar('t_correctivos_generales', $datosInsertarCorrectivos);

            if (!empty($insertarCorrectivos)) {
                $insertarCorrectivosGenerales = "t_correctivos_generales ok";
                $ultimoIdCorrectivosGenerales = $this->ultimoId();
            } else {
                $insertarCorrectivosGenerales = "t_correctivos_generales error";
            }

            // insertando en t_correctivos_diagnostico            
            $consultaCorrectivosGenerales = $this->consulta("SELECT * FROM t_correctivos_generales WHERE Id = '" . $ultimoIdCorrectivosGenerales . "'");

            $datosInsertarCorrectivosDiagnostico = array(
                'IdServicio' => $consultaCorrectivosGenerales[0]['IdServicio'],
                'IdTipoDiagnostico' => $falla['IdTipoDiagnostico'],
                'IdUsuario' => $this->usuario['Id'],
                'IdComponente' => $falla['IdComponente'],
                'IdTipoFalla' => $falla['IdTipoFalla'],
                'IdFalla' => $falla['IdFalla'],
                'FechaCaptura' => $fecha,
                'Evidencias' => $falla['Evidencias']
            );

            $insertarCorrectivosDiagnostico = $this->insertar('t_correctivos_diagnostico', $datosInsertarCorrectivosDiagnostico);

            if (!empty($insertarCorrectivosDiagnostico)) {
                $insertarCorrectivosDiagnostico = "t_correctivos_diagnostico ok";
            } else {
                $insertarCorrectivosDiagnostico = "t_correctivos_diagnostico error";
            }

            if ($this->estatusTransaccion() === FALSE) {
                $this->roolbackTransaccion();
                $respuesta = 0;
                //                $return_array['inserto'] = "no paso";
                //                $return_array = array('insertoTicket' => $insertoTicket, 'insertoCorrectivoGeneral' => $insertarCorrectivosGenerales, 'insertarCorrectivosDiagnostico' => $insertarCorrectivosDiagnostico);
            } else {
                $this->commitTransaccion();
                $this->actualizar("t_checklist_revision_tecnica", array('FlagInsert' => 1), array('Id' => $falla['Id']));
                $respuesta = 1;
                //                $return_array['code'] = $datos;
                //                $return_array = array('insertoTicket' => $insertoTicket, 'insertoCorrectivoGeneral' => $insertarCorrectivosGenerales, 'insertarCorrectivosDiagnostico' => $insertarCorrectivosDiagnostico);
            }
        }
        return $respuesta;
    }

    // SELECTS ------------- Seguimiento Equipos
    public function estatusAllab($idServicio)
    {
        if (!empty($idServicio)) {
            $consulta = $this->consulta("SELECT * FROM t_equipos_allab WHERE IdServicio = '" . $idServicio . "'");
            foreach ($consulta as $value) {
                return ['Id' => $value['Id'], 'IdEstatus' => $value['IdEstatus'], 'Flag' => $value['Flag'], 'IdTipoMovimiento' => $value['IdTipoMovimiento']];
            }
        } else {
            return false;
        }
    }

    public function consultaTablaServicioAllab()
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT cve.Nombre FROM cat_v3_estatus cve WHERE cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM t_equipos_allab tea
                                    INNER JOIN t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                    INNER JOIN v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE tea.IdEstatus NOT IN('31','6')");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabSupervisor(string $usuario)
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT 
                                                cvs.Nombre
                                            FROM
                                                cat_v3_sucursales cvs
                                            WHERE
                                                cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT 
                                                cve.Nombre
                                            FROM
                                                cat_v3_estatus cve
                                            WHERE
                                                cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM
                                        t_equipos_allab tea
                                            INNER JOIN
                                        t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                            INNER JOIN
                                        v_equipos ve ON ve.Id = tea.IdModelo
                                            INNER JOIN
                                        cat_v3_sucursales cvs ON cvs.Id = tst.IdSucursal
                                            INNER JOIN
                                        cat_v3_regiones_cliente cvrc ON cvrc.Id = cvs.IdRegionCliente
                                    WHERE
                                        cvrc.IdResponsableInterno = '" . $usuario . "'
                                        AND    
                                        tea.IdEstatus != '6'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabTecnico(string $usuario)
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT cve.Nombre FROM cat_v3_estatus cve WHERE cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM t_equipos_allab tea
                                    INNER JOIN t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                    INNER JOIN v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE tea.IdUsuario = '" . $usuario . "'
                                    AND tea.IdEstatus NOT IN('31','6')");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabPerfil(string $estatus)
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT cve.Nombre FROM cat_v3_estatus cve WHERE cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM t_equipos_allab tea
                                    INNER JOIN t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                    INNER JOIN v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE tea.IdEstatus IN(" . $estatus . ")");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabPerfilLogistica()
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT cve.Nombre FROM cat_v3_estatus cve WHERE cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM t_equipos_allab tea
                                    INNER JOIN t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                    INNER JOIN v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE
                                    (CASE
                                        WHEN tea.IdTipoMovimiento = '1' THEN tea.IdEstatus IN ('12', '26', '27', '30', '32','33','34','36','37','48')
                                        WHEN tea.IdTipoMovimiento = '3' THEN tea.IdEstatus IN ('12', '26', '27', '30', '34', '36') OR tea.IdEstatus = '38' AND Flag = '0'
                                    END)
                                    AND tea.IdEstatus != '6'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabPerfilAlmacen()
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT 
                                                cvs.Nombre
                                            FROM
                                                cat_v3_sucursales cvs
                                            WHERE
                                                cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT 
                                                cve.Nombre
                                            FROM
                                                cat_v3_estatus cve
                                            WHERE
                                                cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT 
                                                Nombre
                                            FROM
                                                cat_v3_equipos_allab_tipo_movimiento
                                            WHERE
                                                Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM
                                        t_equipos_allab tea
                                            INNER JOIN
                                        t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                            INNER JOIN
                                        v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE
                                        (CASE
                                            WHEN tea.IdTipoMovimiento = '1' THEN tea.IdEstatus IN ('4', '12', '28', '29', '30', '32', '33', '34', '36', '39', '48', '49')
                                            WHEN tea.IdTipoMovimiento = '2' THEN tea.IdEstatus IN ('2','4', '12', '28', '29', '30', '31',  '32', '33', '34', '36', '39', '48', '49')
                                            WHEN
                                                tea.IdTipoMovimiento = '3'
                                            THEN
                                                tea.IdEstatus = '2' AND Flag = 0 OR tea.IdEstatus IN ('38','30','36', '41')
                                                    AND (SELECT 
                                                        IdEstatus
                                                    FROM
                                                        t_equipos_allab_solicitud_refaccion
                                                    WHERE
                                                        IdRegistro = tea.Id) = '7'
                                        END)
                                        AND tea.IdEstatus != '6'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaTablaServicioAllabPerfilLaboratorio()
    {
        $consulta = $this->consulta("SELECT 
                                        tea.Id,
                                        tst.Id as IdServicio,
                                        tst.Ticket,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.Id = tst.IdSucursal) as NombreSucursal,
                                        ve.Equipo,
                                        tea.FechaValidacion,
                                        tea.IdEstatus,
                                        (SELECT cve.Nombre FROM cat_v3_estatus cve WHERE cve.Id = tea.IdEstatus) as NombreEstatus,
                                        tea.IdRefaccion,
                                        (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = tea.IdRefaccion) AS Refaccion,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento WHERE Id = tea.IdTipoMovimiento) TipoMovimiento
                                    FROM t_equipos_allab tea
                                    INNER JOIN t_servicios_ticket tst ON tst.Id = tea.IdServicio
                                    INNER JOIN v_equipos ve ON ve.Id = tea.IdModelo
                                    WHERE
                                    (CASE
                                        WHEN tea.IdTipoMovimiento = '1' THEN tea.IdEstatus IN ('28','29','30','32','33','4','34','36','39','48','49') OR tea.IdEstatus = '2' AND Flag = '1' OR tea.IdEstatus = '12' AND Flag = '0'
                                        WHEN tea.IdTipoMovimiento = '2' THEN tea.IdEstatus IN ('12','28','29','33','39','48','49') OR tea.IdEstatus = '4' AND Flag = '1' OR tea.IdEstatus = '2' AND Flag = '1'
                                        WHEN tea.IdTipoMovimiento = '3' THEN tea.IdEstatus IN ('41')
                                    END)
                                    AND tea.IdEstatus != '6'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function consultaDatosValidacion(array $datos = null)
    {
        $condicion = "";
        $valor = "";
        if (!empty($datos['IdRefaccion'])) {
            $valor = " ,cvce.Nombre as Refaccion";
            $condicion = " INNER JOIN cat_v3_componentes_equipo cvce ON cvce.Id = tea.IdRefaccion ";
        }
        $consulta = $this->consulta("SELECT
                                        tea.IdEstatus,
                                        tst.Ticket,
                                        tea.IdServicio,
                                        tea.FechaValidacion,
                                        (SELECT cveatm.Nombre FROM cat_v3_equipos_allab_tipo_movimiento cveatm WHERE cveatm.Id = tea.IdTipoMovimiento) AS TipoMovimiento,
                                        CONCAT(tst.Id,' - ',tst.Descripcion) AS Servicio,
                                        CONCAT(trp.Nombres,' ',trp.ApMaterno) AS NombrePersonal,
                                        (SELECT Nombre FROM cat_v3_tipos_diagnostico_correctivo WHERE Id = tcd.IdTipoDiagnostico) AS TipoDiagnostico,
                                        (SELECT Nombre FROM cat_v3_tipos_falla WHERE Id = tcd.IdTipoFalla) AS TipoFalla,
                                        (SELECT Nombre FROM cat_v3_fallas_equipo WHERE Id= tcd.IdFalla) AS Falla,
                                        (SELECT Nombre FROM cat_v3_equipos_allab_tipo_movimiento cveatm WHERE cveatm.Id = tea.IdTipoMovimiento) AS Movimiento,
                                        ve.Equipo" . $valor . "
                                        ,'Lectura',
                                        nombreUsuario(tea.IdTecnicoSolicita) AS TecnicoSolicita,
                                        sucursal(tst.IdSucursal) AS Sucursal
                                    FROM 
                                            t_equipos_allab tea
                                    INNER JOIN 
                                            t_servicios_ticket tst on tst.Id = tea.IdServicio
                                    INNER JOIN
                                            t_rh_personal trp ON trp.IdUsuario = tea.IdPersonalValida
                                    INNER JOIN
                                            v_equipos ve ON ve.Id = tea.IdModelo" . $condicion . "
                                    INNER JOIN
                                            t_correctivos_diagnostico tcd ON tcd.IdServicio = tea.IdServicio
                                    WHERE 
                                            tea.IdServicio = '" . $datos['idServicio'] . "'");

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function consultaSolicitudGuiaTecnico(int $idServicio)
    {
        $datosServicio = $this->estatusAllab($idServicio);

        if (!empty($datosServicio)) {
            $consultaGuia = $this->consulta("SELECT 
                                                teaet.*,
                                                (SELECT Nombre FROM cat_v3_paqueterias cvp WHERE cvp.Id = teaet.IdPaqueteria) AS Paqueteria
                                            FROM
                                                t_equipos_allab_envio_tecnico teaet
                                            WHERE 
                                                    IdRegistro = '" . $datosServicio['Id'] . "'");
            if (!empty($consultaGuia)) {
                return $consultaGuia;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function consultaRecepcionAlmacen(array $datos)
    {
        $datosServcio = $this->estatusAllab($datos['IdServicio']);
        $idRecepcion = null;
        $consultaRecepcion = $this->consulta("SELECT 
                                                tear.Id,
                                                CONCAT(trp.Nombres,' ',trp.ApMaterno,' ',trp.ApPaterno) AS UsuarioRecibe,
                                                tear.Fecha,
                                                tear.Archivos
                                            FROM
                                                t_equipos_allab_recepciones tear
                                            INNER JOIN
                                                t_rh_personal trp ON trp.IdUsuario = tear.IdUsuario
                                            WHERE
                                                tear.IdRegistro = '" . $datosServcio['Id'] . "' AND
                                                tear.IdDepartamento = '" . $datos['IdDepartamento'] . "' AND
                                                tear.IdEstatus = '" . $datos['IdEstatus'] . "'");

        foreach ($consultaRecepcion as $value) {
            $idRecepcion = $value['Id'];
        }
        $recpcionProblema = $this->consulta("SELECT 
                                                tearp.Fecha,
                                                tearp.Problema,
                                                tearp.Archivos
                                            FROM
                                                t_equipos_allab_recepciones_problemas tearp
                                            WHERE
                                                    tearp.Id = '" . $idRecepcion . "'");

        if (!empty($recpcionProblema)) {
            return array('recepcion' => $consultaRecepcion, 'recepcionProblema' => $recpcionProblema);
        } else {
            return array('recepcion' => $consultaRecepcion, 'recepcionProblema' => []);
        }
    }

    public function consultaEnvioLogistica(array $datos)
    {
        $datosServcio = $this->estatusAllab($datos['IdServicio']);

        $consulta = $this->consulta("SELECT 
                                        teael.Id,
                                        (SELECT cvp.Nombre FROM cat_v3_paqueterias cvp WHERE cvp.Id = teael.IdPaqueteria AND cvp.Flag = 1) AS paqueteria,
                                        teael.Guia,
                                        teael.FechaEnvio,
                                        teael.ArchivosEnvio,
                                        teael.IdTipoLugarRecepcion,
                                        (SELECT cveatlr.Nombre FROM cat_v3_equipos_allab_tipo_lugar_recepcion cveatlr WHERE cveatlr.Id = teael.IdTipoLugarRecepcion) AS DondeRecibe,
                                        (SELECT cvs.Nombre FROM cat_v3_sucursales cvs WHERE cvs.IdCliente = 1 AND Id = teael.IdSucursal) AS Sucursal,
                                        teael.IdSucursal,
                                        teael.Recibe,
                                        teael.ArchivosEntrega,
                                        teael.FechaRecepcion,
                                        teael.CuentaSiccob,
                                        teael.IdUsuarioTransito,
                                        teael.IdPaqueteria,
                                        nombreUsuario(teael.IdUsuarioTransito) Chofer
                                    FROM
                                        t_equipos_allab_envio_logistica teael
                                    WHERE 
                                        teael.IdRegistro = '" . $datosServcio['Id'] . "'");
        if (!empty($consulta)) {
            return $consulta;
        }
    }

    public function actualizaInventariosMovimientosXConslusionCorrectivo(int $id)
    {
        $this->iniciaTransaccion();

        /* Obtiene la última solución del correctivo y el tipo de solución */
        $solucion = $this->consulta("select Id, IdTipoSolucion from t_correctivos_soluciones where IdServicio = '" . $id . "' order by Id desc limit 1");
        if (!empty($solucion)) {
            $_idSolucion = $solucion[0]['Id'];
            $_tipoSolucion = $solucion[0]['IdTipoSolucion'];

            switch ($_tipoSolucion) {
                case 2:
                case '2':
                    $_refaccionesUtilizadas = $this->consulta("select 
                                                                * 
                                                                from t_inventario 
                                                                where Id in (select 
                                                                    IdInventario 
                                                                    from t_correctivos_solucion_refaccion 
                                                                    where IdSolucionCorrectivo = '" . $_idSolucion . "'
                                                                    and IdInventario is not null
                                                                    and IdInventario <> '')
                                                                and IdTipoProducto = 2;");
                    if (!empty($_refaccionesUtilizadas)) {
                        foreach ($_refaccionesUtilizadas as $key => $value) {
                            /*
                             * Agrega al inventario la refacciòn dañada
                             * que se supone que el usuario retira cuando
                             * deja otra refacciòn
                             */
                            $this->insertar("t_inventario", [
                                'IdAlmacen' => $value['IdAlmacen'],
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => 22,
                                'Cantidad' => 1,
                                'Bloqueado' => 0
                            ]);

                            /*
                             * Obtiene el id de almacèn virtual
                             * correspondiente al complejo del servicio
                             */
                            $almacen = $this->consulta(""
                                . "select "
                                . "Id "
                                . "from cat_v3_almacenes_virtuales "
                                . "where IdTipoAlmacen = 2 "
                                . "and IdReferenciaAlmacen = (select IdSucursal from t_servicios_ticket where Id = '" . $id . "')");
                            $almacen = $almacen[0]['Id'];

                            /*
                             * Inserta el movimiento de salida de la refacciòn 
                             * dañada del almacèn virtual del complejo
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $id,
                                'IdAlmacen' => $almacen,
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => 22,
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => 1,
                                'Serie' => '',
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);

                            /*
                             * Obtiene el Id del movimiento de salida
                             */
                            $enlazado = $this->ultimoId();

                            /*
                             * Inserta el movimiento de entrada de la refacciòn
                             * dañada al almacèn del usuario
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdMovimientoEnlazado' => $enlazado,
                                'IdTipoMovimiento' => 5,
                                'IdServicio' => $id,
                                'IdAlmacen' => $value['IdAlmacen'],
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => 22,
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => 1,
                                'Serie' => '',
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);

                            /*
                             * Actualiza el almacen del producto utilizado
                             */
                            $this->actualizar("t_inventario", [
                                'IdAlmacen' => $almacen,
                                'Bloqueado' => 0
                            ], ['Id' => $value['Id']]);

                            /*
                             * Inserta el movimiento de salida del almacèn del usuario
                             * que refiere a la refacciòn utilizada en el servicio
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $id,
                                'IdAlmacen' => $value['IdAlmacen'],
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => $value['IdEstatus'],
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => $value['Cantidad'],
                                'Serie' => $value['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);
                            /*
                             * Obtiene el Id del movimiento de salida
                             * del almacèn del usuario
                             */
                            $enlazado = $this->ultimoId();

                            /*
                             * Inserta el movimiento de entrada al almacèn del complejo
                             * de la refacciòn utilizada en el servicio
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdMovimientoEnlazado' => $enlazado,
                                'IdTipoMovimiento' => 5,
                                'IdServicio' => $id,
                                'IdAlmacen' => $almacen,
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => $value['IdEstatus'],
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => $value['Cantidad'],
                                'Serie' => $value['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);
                        }
                    }
                    break;

                case 3:
                case '3':
                    $datosEquipo = $this->consulta("select IdModelo, Serie from t_correctivos_generales where IdServicio = '" . $id . "'")[0];

                    $_equiposUtilizados = $this->consulta("select 
                                                                * 
                                                                from t_inventario 
                                                                where Id in (select 
                                                                    IdInventario 
                                                                    from t_correctivos_solucion_cambio 
                                                                    where IdSolucionCorrectivo = '" . $_idSolucion . "'
                                                                    and IdInventario is not null
                                                                    and IdInventario <> '')
                                                                and IdTipoProducto = 1;");
                    if (!empty($_equiposUtilizados)) {
                        foreach ($_equiposUtilizados as $key => $value) {
                            /*
                             * Agrega al inventario el equipo dañado
                             * que se supone que el usuario retira cuando
                             * deja otro equipo completo. 
                             */

                            $idInventario = $this->consulta("
                            select Id 
                            from t_inventario 
                            where IdTipoProducto = 1 
                            and IdProducto = '" . $datosEquipo['IdModelo'] . "' 
                            and Serie = '" . $datosEquipo['Serie'] . "' 
                            and Serie <> 'ILEGIBLE'");
                            if (!empty($idInventario) && isset($idInventario[0]['Id']) && $idInventario[0]['Id'] > 0) {
                                $this->actualizar("t_inventario", [
                                    'IdAlmacen' => $value['IdAlmacen'],
                                    'IdEstatus' => 22,
                                    'Bloqueado' => 0
                                ], ['Id' => $idInventario[0]['Id']]);
                            } else {
                                $this->insertar("t_inventario", [
                                    'IdAlmacen' => $value['IdAlmacen'],
                                    'IdTipoProducto' => 1,
                                    'IdProducto' => $datosEquipo['IdModelo'],
                                    'IdEstatus' => 22,
                                    'Cantidad' => 1,
                                    'Serie' => $datosEquipo['Serie'],
                                    'Bloqueado' => 0
                                ]);
                            }

                            /*
                             * Obtiene el id de almacèn virtual
                             * correspondiente al complejo del servicio
                             */
                            $almacen = $this->consulta(""
                                . "select "
                                . "Id "
                                . "from cat_v3_almacenes_virtuales "
                                . "where IdTipoAlmacen = 2 "
                                . "and IdReferenciaAlmacen = (select IdSucursal from t_servicios_ticket where Id = '" . $id . "')");
                            $almacen = $almacen[0]['Id'];

                            /*
                             * Inserta el movimiento de salida del equipo
                             * dañado del almacèn virtual del complejo
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $id,
                                'IdAlmacen' => $almacen,
                                'IdTipoProducto' => 1,
                                'IdProducto' => $datosEquipo['IdModelo'],
                                'IdEstatus' => 22,
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => 1,
                                'Serie' => $datosEquipo['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);

                            /*
                             * Obtiene el Id del movimiento de salida
                             */
                            $enlazado = $this->ultimoId();

                            /*
                             * Inserta el movimiento de entrada del equipo
                             * dañado al almacèn del usuario
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdMovimientoEnlazado' => $enlazado,
                                'IdTipoMovimiento' => 5,
                                'IdServicio' => $id,
                                'IdAlmacen' => $value['IdAlmacen'],
                                'IdTipoProducto' => 1,
                                'IdProducto' => $datosEquipo['IdModelo'],
                                'IdEstatus' => 22,
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => 1,
                                'Serie' => $datosEquipo['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);

                            /*
                             * Actualiza el almacen del producto utilizado
                             */
                            $this->actualizar("t_inventario", [
                                'IdAlmacen' => $almacen,
                                'Bloqueado' => 0
                            ], ['Id' => $value['Id']]);

                            /*
                             * Inserta el movimiento de salida del almacèn del usuario
                             * que refiere a la refacciòn utilizada en el servicio
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdTipoMovimiento' => 4,
                                'IdServicio' => $id,
                                'IdAlmacen' => $value['IdAlmacen'],
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => $value['IdEstatus'],
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => $value['Cantidad'],
                                'Serie' => $value['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);
                            /*
                             * Obtiene el Id del movimiento de salida
                             * del almacèn del usuario
                             */
                            $enlazado = $this->ultimoId();

                            /*
                             * Inserta el movimiento de entrada al almacèn del complejo
                             * de la refacciòn utilizada en el servicio
                             */
                            $this->insertar("t_movimientos_inventario", [
                                'IdMovimientoEnlazado' => $enlazado,
                                'IdTipoMovimiento' => 5,
                                'IdServicio' => $id,
                                'IdAlmacen' => $almacen,
                                'IdTipoProducto' => $value['IdTipoProducto'],
                                'IdProducto' => $value['IdProducto'],
                                'IdEstatus' => $value['IdEstatus'],
                                'IdUsuario' => $this->usuario['Id'],
                                'Cantidad' => $value['Cantidad'],
                                'Serie' => $value['Serie'],
                                'Fecha' => mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'))
                            ]);
                        }
                    }
                    break;

                default:
                    break;
            }
        }

        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400, 'error' => $this->tipoError()];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function insertarEquiposAllabRevicionLaboratorioHistorial(array $datos)
    {
        $this->iniciaTransaccion();

        $revisionLaboratorio = $this->consultaEquiposAllabRevicionLaboratorio($datos);
        if (!empty($revisionLaboratorio)) {
            $idRevision = $revisionLaboratorio[0]['Id'];
        } else {
            $idRevision = $this->insertarEquiposAllabRevicionLaboratorio($datos);
        }

        $this->insertar('t_equipos_allab_revision_laboratorio_historial', [
            'IdRevision' => $idRevision,
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'Comentarios' => $datos['comentarios'],
            'Archivos' => $datos['archivos']
        ]);

        $this->cambiarEsatus(array('idEstatus' => '2', 'flag' => '1', 'id' => $datos['id'], 'fecha' => $datos['fecha']));

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function insertarEquiposAllabRevicionLaboratorio(array $datos)
    {
        $this->insertar('t_equipos_allab_revision_laboratorio', [
            'IdRegistro' => $datos['id'],
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'IdEstatus' => '29'
        ]);

        $idRevision = parent::connectDBPrueba()->insert_id();

        return $idRevision;
    }

    public function actualizarEquiposAllabRevicionLaboratorio(array $datos)
    {
        $this->iniciaTransaccion();

        $this->actualizar('t_equipos_allab_revision_laboratorio', array(
            'IdEstatus' => '4',
        ), array('IdRegistro' => $datos['id']));

        $consultaLaboratorioRefacciones = $this->consulta('SELECT 
                                                                tearlr.IdInventario,
                                                                ti.*
                                                            FROM
                                                                t_equipos_allab_revision_laboratorio_refacciones tearlr
                                                                    INNER JOIN
                                                                t_equipos_allab_revision_laboratorio tearl
                                                                    ON tearlr.IdRevision = tearl.Id
                                                                    INNER JOIN
                                                                t_inventario ti ON tearlr.IdInventario = ti.Id
                                                                    WHERE tearl.IdRegistro = " ' . $datos['id'] . ' "
                                                                    AND Flag = "1"');

        foreach ($consultaLaboratorioRefacciones as $key => $value) {
            $this->actualizar('t_inventario', array('IdEstatus' => '40'), ['Id' => $value['IdInventario']]);

            $arrayMovimientoInventarioSalida = array(
                'IdTipoMovimiento' => '4',
                'IdServicio' => $datos['idServicio'],
                'IdAlmacen' => $value['IdAlmacen'],
                'IdTipoProducto' => '2',
                'IdProducto' => $value['IdInventario'],
                'IdEstatus' => '17',
                'IdUsuario' => $datos['idUsuario'],
                'Cantidad' => $value['Cantidad'],
                'Serie' => $value['Serie'],
                'Fecha' => $datos['fecha']
            );

            $this->insertar('t_movimientos_inventario', $arrayMovimientoInventarioSalida);

            $idMovimientoInventarioSalida = parent::connectDBPrueba()->insert_id();

            $arrayTablaInventario = array(
                'IdAlmacen' => $value['IdAlmacen'],
                'IdTipoProducto' => '2',
                'IdProducto' => $value['IdInventario'],
                'IdEstatus' => '22',
                'Cantidad' => $value['Cantidad'],
                'Serie' => $value['Serie']
            );

            $this->insertar('t_inventario', $arrayTablaInventario);

            $arrayMovimientoInventarioEntrada = array(
                'IdMovimientoEnlazado' => $idMovimientoInventarioSalida,
                'IdTipoMovimiento' => '5',
                'IdServicio' => $datos['idServicio'],
                'IdAlmacen' => $value['IdAlmacen'],
                'IdTipoProducto' => '2',
                'IdProducto' => $value['IdInventario'],
                'IdEstatus' => '22',
                'IdUsuario' => $datos['idUsuario'],
                'Cantidad' => $value['Cantidad'],
                'Serie' => $value['Serie'],
                'Fecha' => $datos['fecha']
            );

            $this->insertar('t_movimientos_inventario', $arrayMovimientoInventarioEntrada);
        }

        $this->cambiarEsatus($datos);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaEquiposAllabRevicionLaboratorio(array $datos)
    {
        $consulta = $this->consulta('SELECT * FROM t_equipos_allab_revision_laboratorio WHERE IdRegistro = " ' . $datos['id'] . ' "');
        return $consulta;
    }

    public function insertarEquiposAllabRecepcionesProblemas(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_equipos_allab_recepciones_problemas', [
            'IdRecepcion' => $datos['idRecepcion'],
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'Problema' => $datos['comentarios'],
            'Archivos' => $datos['archivos']
        ]);

        $this->cambiarEsatus($datos);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaComentariosAdjuntosSolicitudEquipo(int $id)
    {
        $consulta = $this->consulta('select 
                                        tearlh.Id,
                                        nombreUsuario(tearlh.IdUsuario) as Usuario,
                                        (select 
                                                UrlFoto
                                            from
                                                t_rh_personal
                                            where
                                                IdUsuario = tearlh.IdUsuario) as UrlFoto,
                                        DATE_FORMAT(tearlh.Fecha, "%M %e, %Y - %l:%i %p") as Fecha,
                                        tearlh.Comentarios as Nota,
                                        tearlh.Archivos as Adjuntos
                                    from
                                        t_equipos_allab_revision_laboratorio_historial tearlh
                                        left join
                                    t_equipos_allab_revision_laboratorio tearl ON tearl.Id = tearlh.IdRevision
                                        left join
                                    t_equipos_allab tea ON tea.Id = tearl.IdRegistro
                                    where tea.Id = "' . $id . '"
                                    order by tearlh.Id desc');
        return $consulta;
    }

    public function consultaRecepcionesProblemasSolicitudEquipo(array $datos)
    {
        $consulta = $this->consulta('SELECT 
                                            tearp.Id,
                                    nombreUsuario(tearp.IdUsuario) as Usuario,
                                    (select 
                                            UrlFoto
                                        from
                                            t_rh_personal
                                        where
                                            IdUsuario = tearp.IdUsuario) as UrlFoto,
                                    DATE_FORMAT(tearp.Fecha, "%M %e, %Y - %l:%i %p") as Fecha,
                                    tearp.Problema as Nota,
                                    tearp.Archivos as Adjuntos
                                    FROM
                                        t_equipos_allab_recepciones_problemas tearp
                                            left join
                                        t_equipos_allab_recepciones tear ON tear.Id = tearp.IdRecepcion
                                            left join
                                        t_equipos_allab tea ON tea.Id = tear.IdRegistro
                                    where tea.Id = "' . $datos['id'] . '"
                                    AND tear.IdDepartamento = "' . $datos['idDepartamento'] . '"
                                    order by tearp.Id desc');
        return $consulta;
    }

    public function insertarEquiposAllabRecepcionesCambiarEstatus(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertarEquiposAllabRecpciones($datos);

        $this->cambiarEsatus($datos);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function insertarEquiposAllabRecpciones(array $datos)
    {
        $this->iniciaTransaccion();
        $this->insertar('t_equipos_allab_recepciones', [
            'IdRegistro' => $datos['id'],
            'IdDepartamento' => $datos['idDepartamento'],
            'IdEstatus' => $datos['idEstatus'],
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'Archivos' => $datos['archivos']
        ]);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function actualizarEquiposAllabRecepciones(array $datos)
    {
        $this->iniciaTransaccion();
        $this->actualizar('t_equipos_allab_recepciones', array(
            'IdEstatus' => $datos['idEstatus'],
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'Archivos' => $datos['archivos']
        ), array('IdRegistro' => $datos['id'], 'IdDepartamento' => $datos['idDepartamento']));
        $this->cambiarEsatus($datos);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function cambiarEsatus(array $datos)
    {
        $resultado = $this->actualizar('t_equipos_allab', array('IdEstatus' => $datos['idEstatus'], 'Flag' => $datos['flag'], 'FechaEstatus' => $datos['fecha']), ['Id' => $datos['id']]);

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function consultaTicketsUsuario(array $datos)
    {
        $consulta = $this->consulta('SELECT
                                        tst.Ticket,
                                        (SELECT Folio FROM t_solicitudes WHERE Id = tst.IdSolicitud) AS Folio
                                    FROM
                                        t_servicios_ticket tst
                                    WHERE
                                        tst.Atiende = "' . $datos['usuario'] . '"
                                    AND tst.IdEstatus = "' . $datos['estatus'] . '"
                                    AND tst.Id NOT IN (SELECT IdServicio FROM t_equipos_allab WHERE IdServicio = tst.Id)
                                    GROUP BY Ticket');
        return $consulta;
    }

    public function consultaServiciosUsuario(array $datos)
    {
        $consulta = $this->consulta('SELECT 
                                        tst.Id,
                                        tst.Descripcion,
                                        tcg.IdModelo,
                                        tcg.Serie
                                    FROM
                                        t_servicios_ticket tst
                                        INNER JOIN t_correctivos_generales tcg on tst.Id = tcg.IdServicio
                                    WHERE
                                        Ticket = "' . $datos['ticket'] . '"
                                    AND tst.Id NOT IN (SELECT IdServicio FROM t_equipos_allab WHERE IdServicio = tst.Id)');
        return $consulta;
    }

    public function mostrarTipoPersonaValida()
    {
        $personaValida = $this->consulta("SELECT 
                                            cp.Id, cp.Nombre
                                        FROM
                                            cat_perfiles cp
                                        WHERE
                                            cp.Id IN (38 , 39, 46)");
        return $personaValida;
    }

    public function mostrarNombrePersonalValida($datos)
    {
        $datosPersonal = $this->consulta("SELECT 
                                            cvu.Id,CONCAT(trp.Nombres,' ',trp.ApPaterno) AS Nombre
                                        FROM
                                            t_rh_personal trp INNER JOIN cat_v3_usuarios cvu on trp.IdUsuario = cvu.Id
                                        WHERE 
                                            IdPerfil = '" . $datos . "'
                                        AND Flag = '1'");

        return $datosPersonal;
    }

    public function mostrarEquipo()
    {
        $equipo = $this->consulta("SELECT * FROM v_equipos");
        return $equipo;
    }

    public function mostrarRefaccionXEquipo($dato)
    {
        $refaccionXequipo = $this->consulta("SELECT * FROM cat_v3_componentes_equipo WHERE IdModelo = '" . $dato . "' AND Flag = 1");
        return $refaccionXequipo;
    }

    public function consultaEquiposAllab(int $idServicio = null)
    {
        if (!empty($idServicio)) {
            $consulta = $this->consulta("SELECT
                                             *, nombreUsuario(IdUsuario) NombreUsuario,
                                             nombreUsuario(IdTecnicoSolicita) Tecnico
                                         FROM t_equipos_allab 
                                         WHERE IdServicio = '" . $idServicio . "'");
        } else {
            $consulta = $this->consulta("SELECT * FROM t_equipos_allab");
        }

        return $consulta;
    }

    public function insertarValidacionTecnico($dato)
    {
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if ($dato['IdRefaccion'] === '') {
            $dato['IdRefaccion'] = null;
        }

        if ($dato['Serie'] === '') {
            $dato['Serie'] = null;
        }

        $datos = array(
            'IdServicio' => $dato['IdServicio'],
            'IdPersonalValida' => $dato['IdPersonalValida'],
            'FechaValidacion' => $dato['FechaValidacion'],
            'IdTipoMovimiento' => $dato['IdTipoMovimiento'],
            'IdModelo' => $dato['IdModelo'],
            'Serie' => $dato['Serie'],
            'IdRefaccion' => $dato['IdRefaccion'],
            'IdUsuario' => $this->usuario['Id'],
            'IdEstatus' => 2,
            'FechaEstatus' => $fecha,
            'IdTecnicoSolicita' => $dato['idTecnicoSolicita'],
            'Flag' => 0
        );

        $insertar = $this->insertar('t_equipos_allab', $datos);

        if (!empty($insertar)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function mostrarEquipoDanado($idModelo)
    {
        $equipoDanado = $this->consulta("SELECT 
                                            *
                                        FROM
                                            v_equipos
                                        WHERE
                                            Id = '" . $idModelo . "'");

        return $equipoDanado;
    }

    public function mostrarPaqueterias()
    {
        $consulta = $this->consulta("SELECT * FROM cat_v3_paqueterias WHERE Flag = 1");
        if (!empty($consulta)) {
            return $consulta;
        }
    }

    public function insertarEnvioGuia(array $datos, array $estatus)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_equipos_allab_envio_tecnico', $datos);
        $this->cambiarEsatus($estatus);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function actualizarEnvioGuia(array $datos, array $datosEstatus, string $id)
    {
        $this->iniciaTransaccion();

        $this->actualizar('t_equipos_allab_envio_tecnico', $datos, ['Id' => $id]);

        $this->cambiarEsatus($datosEstatus);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaIdRegistro(array $datos)
    {
        if (isset($datos['idEstatus'])) {
            $idEstatus = 'AND IdEstatus = "' . $datos['idEstatus'] . '"';
        } else {
            $idEstatus = '';
        }

        $equipoDanado = $this->consulta('SELECT 
                                            Id
                                        FROM
                                            t_equipos_allab_recepciones tear
                                        WHERE IdRegistro = "' . $datos['idRegistro'] . '"
                                        AND IdDepartamento = "' . $datos['idDepartamento'] . '"
                                        ' . $idEstatus);

        return $equipoDanado;
    }

    public function consultaComponentesEquipoInvetario(array $datos)
    {
        $equipoDanado = $this->consulta('select 
                                            ti.Id,
                                            ti.Serie,
                                                ti.IdProducto,
                                            (SELECT 
                                                    Nombre
                                                FROM
                                                    cat_v3_componentes_equipo
                                                WHERE
                                                    Id = IdProducto) AS Nombre
                                        from
                                            t_inventario ti
                                        INNER JOIN cat_v3_componentes_equipo cvce
                                                ON ti.IdProducto = cvce.Id
                                        where
                                            ti.IdAlmacen in (select 
                                                    Id
                                                from
                                                    cat_v3_almacenes_virtuales
                                                where
                                                    IdResponsable = "' . $datos['idUsuario'] . '"
                                                        or (IdTipoAlmacen = 1
                                                        and IdReferenciaAlmacen = "' . $datos['idUsuario'] . '"))
                                                and ti.IdTipoProducto = "2"
                                                and ti.IdEstatus = 17
                                                and ti.Bloqueado = 0
                                                        and cvce.IdModelo = "' . $datos['idModelo'] . '"');

        return $equipoDanado;
    }

    public function laboratorioRefacciones(array $datos)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_equipos_allab_revision_laboratorio_refacciones', array(
            'IdRevision' => $datos['idRevision'],
            'IdUsuario' => $datos['idUsuario'],
            'Fecha' => $datos['fecha'],
            'IdInventario' => $datos['idInvetario'],
            'Cantidad' => $datos['cantidad'],
            'Flag' => $datos['flag']
        ));

        $this->actualizar('t_inventario', array('Bloqueado' => '1'), ['Id' => $datos['idInvetario']]);

        $this->cambiarEsatus($datos);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaListaRefaccionesUtilizadasServicio(string $idServicio)
    {
        $equipoDanado = $this->consulta('SELECT 
                                                tearlr.Id,
                                                tearlr.Cantidad,
                                                tearlr.IdInventario,
                                                (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = ti.IdProducto) AS Nombre
                                         FROM t_equipos_allab_revision_laboratorio_refacciones tearlr
                                        INNER JOIN t_equipos_allab_revision_laboratorio tearl
                                        ON tearl.Id = tearlr.IdRevision
                                        INNER JOIN t_equipos_allab tea
                                        ON tea.Id = tearl.IdRegistro
                                        INNER JOIN t_inventario ti
                                        ON tearlr.IdInventario = ti.Id
                                        WHERE tea.IdServicio = " ' . $idServicio . ' "
                                        AND tearlr.Flag = "1"');

        return $equipoDanado;
    }

    public function flagearRefaccionUtilizada(array $datos)
    {
        $resultado = $this->actualizar('t_equipos_allab_revision_laboratorio_refacciones', array('Flag' => $datos['flag']), ['Id' => $datos['id']]);
        $this->actualizar('t_inventario', array('Bloqueado' => '0'), ['Id' => $datos['idInventario']]);

        if (!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function consultaHistorialRegistro(array $datos)
    {
        $equipoDanado = $this->consulta('SELECT 
                                            *
                                        FROM
                                            t_equipos_allab_revision_laboratorio_historial tearlh
                                                INNER JOIN
                                            t_equipos_allab_revision_laboratorio tearl ON tearl.Id = tearlh.IdRevision
                                        WHERE
                                            tearl.IdRegistro = " ' . $datos['id'] . ' "');

        return $equipoDanado;
    }

    public function insertarEnvioLogistica(array $datos, array $datosEstatus)
    {
        $this->iniciaTransaccion();

        $this->insertar('t_equipos_allab_envio_logistica', $datos);
        $this->cambiarEsatus($datosEstatus);


        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function actualizarEnvioLogistica(array $datos, array $datosEstatus)
    {
        $this->iniciaTransaccion();

        $this->actualizar("t_equipos_allab_envio_logistica", $datos, ['IdRegistro' => $datosEstatus['id']]);
        $this->cambiarEsatus($datosEstatus);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaInventarioAlmacenesVirtuales(array $datos)
    {
        $equipoDanado = $this->consulta('SELECT 
                                            *
                                        FROM
                                            t_inventario ti
                                                INNER JOIN
                                            cat_v3_almacenes_virtuales cvav ON ti.IdAlmacen = cvav.Id
                                        WHERE
                                            cvav.IdReferenciaAlmacen = "' . $datos['idUsuario'] . '"
                                                AND ti.IdProducto = "' . $datos['producto'] . '"
                                                AND ti.IdTipoProducto = "' . $datos['tipoProducto'] . '"');

        return $equipoDanado;
    }

    public function insertarEquiposAllabSolicitudRefaccion(array $datos)
    {
        $resultado = $this->insertar('t_equipos_allab_solicitud_refaccion', [
            'IdRegistro' => $datos['id'],
            'IdUsuario' => $datos['idUsuario'],
            'IdEstatus' => $datos['estatus'],
            'FechaEstatus' => $datos['fechaEstatus']
        ]);

        return $resultado;
    }

    public function actualizarEquiposAllabSolicitudesRefaccion(array $datos)
    {
        $resultado = $this->actualizar("t_equipos_allab_solicitud_refaccion", array(
            'IdUsuario' => $datos['idUsuario'],
            'IdEstatus' => $datos['idEstatus'],
            'FechaEstatus' => $datos['fechaEstatus'],
            'Cobrable' => $datos['cobrable']
        ), ['IdRegistro' => $datos['id']]);

        return $resultado;
    }

    public function consultaEquiposAllabSolicitudesRefaccion(string $idServicio)
    {
        $consulta = $this->consulta('SELECT 
                                            teasr.*
                                        FROM
                                            t_equipos_allab_solicitud_refaccion teasr
                                        INNER join t_equipos_allab tea
                                        ON teasr.IdRegistro = tea.Id
                                        WHERE tea.IdServicio = " ' . $idServicio . ' "');

        return $consulta;
    }

    public function consultaInventarioAlmacen(array $datos)
    {
        $consulta = $this->consulta('select 
                                        ti.Id,
                                        ti.Serie,
                                        (CASE 
                                                WHEN IdTipoProducto = "1" THEN (SELECT Nombre FROM cat_v3_modelos_equipo WHERE Id = IdProducto)
                                                WHEN IdTipoProducto = "2" THEN (SELECT Nombre FROM cat_v3_componentes_equipo WHERE Id = IdProducto)
                                        END) AS Producto
                                        from t_inventario ti
                                        where ti.IdAlmacen in (
                                                select 
                                                Id
                                                from cat_v3_almacenes_virtuales
                                                where IdResponsable = "' . $datos['idUsuario'] . '" /*IdUsuario*/
                                                or (IdTipoAlmacen = 1 and IdReferenciaAlmacen = "' . $datos['idUsuario'] . '" /*IdUsuario*/)
                                        )
                                        and ti.IdTipoProducto = "' . $datos['tipoProducto'] . '" /*Cambio dependiendo equipo o refaccion*/
                                        and ti.IdEstatus = 17
                                        and ti.Bloqueado = 0');

        return $consulta;
    }

    public function insertarRefaccionRefacciones(array $datos, array $datosEstatus)
    {
        $this->iniciaTransaccion();

        foreach ($datos['listaProductos'] as $key => $value) {
            $arraySolicitudRefaccionRefacciones = array(
                'IdSolicitudRefaccion' => $datos['idSolicitudRefaccion'],
                'IdInventario' => $value
            );

            $this->insertar('t_equipos_allab_solicitud_refaccion_refacciones', $arraySolicitudRefaccionRefacciones);
            $this->actualizar("t_inventario", ['Bloqueado' => 1], ['Id' => $value]);
        }

        $this->cambiarEsatus($datosEstatus);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function consultaRefaccionEquipoUtilizadoAlmacen(array $datos)
    {
        $consulta = $this->consulta('SELECT 
                                            (CASE
                                                WHEN
                                                    ti.IdTipoProducto = "1"
                                                THEN
                                                    (SELECT 
                                                            Nombre
                                                        FROM
                                                            cat_v3_modelos_equipo
                                                        WHERE
                                                            Id = ti.IdProducto)
                                                WHEN
                                                    ti.IdTipoProducto = "2"
                                                THEN
                                                    (SELECT 
                                                            Nombre
                                                        FROM
                                                            cat_v3_componentes_equipo
                                                        WHERE
                                                            Id = ti.IdProducto)
                                            END) AS Producto,
                                            ti.Serie
                                        FROM
                                            t_equipos_allab_solicitud_refaccion_refacciones teasrr
                                                INNER JOIN
                                            t_equipos_allab_solicitud_refaccion teasr ON teasrr.IdSolicitudRefaccion = teasr.Id
                                                INNER JOIN
                                            t_inventario ti ON teasrr.IdInventario = ti.Id
                                                INNER JOIN t_equipos_allab tea ON teasr.IdRegistro = tea.Id
                                        WHERE
                                            tea.IdServicio = "' . $datos['idServicio'] . '"');

        return $consulta;
    }

    public function consultPostByProfiles(string $idProfile, string $idTechnical)
    {
        $answerQuery = $this->consulta("SELECT 
                                        EmailCorporativo
                                    FROM
                                        cat_v3_usuarios cvu
                                    WHERE
                                        cvu.IdPerfil IN(" . $idProfile . ")
                                    OR cvu.Id = '" . $idTechnical . "'");

        return $answerQuery;
    }

    public function consultSupervisorAndTechnicalMail(string $idTechnical)
    {
        $answerQuery = $this->consulta("(SELECT 
                                            cvu.EmailCorporativo
                                        FROM
                                            cat_v3_usuarios cvu
                                        WHERE cvu.Id = '" . $idTechnical . "')
                                        UNION
                                        SELECT 
                                            (SELECT 
                                                    EmailCorporativo
                                                FROM
                                                    cat_v3_usuarios
                                                WHERE
                                                    Id = cvrc.IdResponsableInterno) EmailCorporativo
                                        FROM
                                            cat_v3_usuarios cvu
                                                INNER JOIN
                                            t_servicios_ticket tst ON tst.Atiende = cvu.Id
                                                INNER JOIN
                                            cat_v3_sucursales cvs ON cvs.Id = tst.IdSucursal
                                                INNER JOIN
                                            cat_v3_regiones_cliente cvrc ON cvrc.Id = cvs.IdRegionCliente
                                        WHERE
                                            cvu.Id = '" . $idTechnical . "'
                                        GROUP BY cvrc.IdResponsableInterno");

        return $answerQuery;
    }

    public function consultationServiceAndRequest(string $service)
    {
        $answerQuery = $this->consulta("SELECT 
                                            ts.Ticket,
                                                ts.Folio
                                        FROM
                                            t_servicios_ticket tst
                                        INNER JOIN t_solicitudes ts ON ts.Id = tst.IdSolicitud
                                        WHERE
                                            tst.Id = '" . $service . "'");

        return $answerQuery;
    }

    public function transparencyFromLaboratoryToEarehouse(array $dataTransparencyFromLaboratoryToEarehouse, array $dataStatus)
    {
        $this->iniciaTransaccion();

        foreach ($dataTransparencyFromLaboratoryToEarehouse['listaProductos'] as $key => $value) {
            $inquiryQuery = $this->consulta('SELECT * FROM t_inventario WHERE Id = "' . $value . '"');

            $dataMovementInventoryTransfer = array(
                'IdTipoMovimiento' => '2',
                'IdServicio' => $dataTransparencyFromLaboratoryToEarehouse['idServicio'],
                'IdAlmacen' => $inquiryQuery[0]['IdAlmacen'],
                'IdTipoProducto' => $inquiryQuery[0]['IdTipoProducto'],
                'IdProducto' => $value,
                'IdEstatus' => '17',
                'IdUsuario' => $dataTransparencyFromLaboratoryToEarehouse['idUsuario'],
                'Cantidad' => $inquiryQuery[0]['Cantidad'],
                'Serie' => $inquiryQuery[0]['Serie'],
                'Fecha' => $dataTransparencyFromLaboratoryToEarehouse['fecha']
            );
            $this->actualizar('t_inventario', array('IdAlmacen' => '58'), ['Id' => $value]);
            $this->insertar('t_movimientos_inventario', $dataMovementInventoryTransfer);


            $idMovementInventoryTransfer = parent::connectDBPrueba()->insert_id();

            $inquiryQueryUpdated = $this->consulta('SELECT * FROM t_inventario WHERE Id = "' . $value . '"');

            $dataMovementInventoryEntry = array(
                'IdMovimientoEnlazado' => $idMovementInventoryTransfer,
                'IdTipoMovimiento' => '3',
                'IdServicio' => $dataTransparencyFromLaboratoryToEarehouse['idServicio'],
                'IdAlmacen' => $inquiryQueryUpdated[0]['IdAlmacen'],
                'IdTipoProducto' => $inquiryQueryUpdated[0]['IdTipoProducto'],
                'IdProducto' => $value,
                'IdEstatus' => '17',
                'IdUsuario' => $dataTransparencyFromLaboratoryToEarehouse['idUsuario'],
                'Cantidad' => $inquiryQueryUpdated[0]['Cantidad'],
                'Serie' => $inquiryQueryUpdated[0]['Serie'],
                'Fecha' => $dataTransparencyFromLaboratoryToEarehouse['fecha']
            );

            $this->insertar('t_movimientos_inventario', $dataMovementInventoryEntry);
        }

        $this->cambiarEsatus($dataStatus);

        $this->terminaTransaccion();
        if ($this->estatusTransaccion() === false) {
            $this->roolbackTransaccion();
            return ['code' => 400];
        } else {
            $this->commitTransaccion();
            return ['code' => 200];
        }
    }

    public function previousQuoteQuery(string $service)
    {
        $answerQuery = $this->consulta("select
                count(*) as Total
                from t_servicios_ticket tst
                where tst.IdServicioOrigen = '" . $service . "'
                and tst.IdTipoServicio = 41");

        return $answerQuery;
    }

    public function getAdvanceService(string $service)
    {
        $answerQuery = $this->consulta("SELECT 
                                            tsa . *
                                        FROM
                                            t_servicios_avance tsa
                                                INNER JOIN
                                            t_servicios_ticket tst ON tsa.IdServicio = tst.Id
                                        WHERE IdServicio = '" . $service . "'
                                        AND tsa.Flag = '1'
                                        ORDER BY Id DESC
                                        LIMIT 1");

        return $answerQuery;
    }

    public function insertarBitacoraReporteFalso(array $datos)
    {
        $resultado = $this->insertar('t_correctivos_bitacora_reporte_falso', $datos);
        return $resultado;
    }

    public function consultaBitacoraReporteFalso(string $servicio)
    {
        $consulta = $this->consulta('SELECT 
                                        tcbrf.*,
                                        (SELECT 
                                                UrlFoto
                                            FROM
                                                t_rh_personal
                                            WHERE
                                                Id = tcbrf.IdUsuario) AS Foto,
                                        nombreUsuario(tcbrf.IdUSuario) AS Usuario
                                    FROM
                                        t_correctivos_bitacora_reporte_falso AS tcbrf
                                    WHERE tcbrf.IdServicio = "' . $servicio . '"');
        return $consulta;
    }
}
