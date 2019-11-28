<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;
use Librerias\Generales\PDF as PDF;

/**
 * Description of ServiciosTicket
 *
 * @author Freddy
 */
class ServiciosTicket extends General
{

    private $DBST;
    private $DBCS;
    private $DBS;
    private $DBB;
    private $DBTO;
    private $Notificacion;
    private $Catalogo;
    private $ServiceDesk;
    private $SeguimientoLogistica;
    private $SeguimientoPoliza;
    private $Notas;
    private $Phantom;
    private $Correo;
    private $InformacionServicios;
    private $MSP;
    private $pdf;
    private $DBA;
    private $Ticket;

    public function __construct()
    {
        parent::__construct();
        $this->DBST = \Modelos\Modelo_ServicioTicket::factory();
        $this->DBS = \Modelos\Modelo_Solicitud::factory();
        $this->DBB = \Modelos\Modelo_Busqueda::factory();
        $this->DBCS = \Modelos\Modelo_Salas4D::factory();
        $this->DBMP = \Modelos\Modelo_Poliza::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->ServiceDesk = \Librerias\WebServices\ServiceDesk::factory();
        $this->SeguimientoLogistica = \Librerias\Logistica\Seguimiento::factory();
        $this->SeguimientoPoliza = \Librerias\Poliza\Seguimientos::factory();
        $this->Notas = \Librerias\Generales\Notas::factory();
        $this->Phantom = \Librerias\Generales\Phantom::factory();
        $this->Correo = \Librerias\Generales\Correo::factory();
        $this->InformacionServicios = \Librerias\WebServices\InformacionServicios::factory();
        $this->MSP = \Modelos\Modelo_SegundoPlano::factory();
        $this->DBTO = \Modelos\Modelo_TicketsOld::factory();
        $this->DBT = \Modelos\Modelo_Tesoreria::factory();
        $this->pdf = new PDFAux();
        $this->DBA = \Modelos\Modelo_InventarioConsignacion::factory();
        $this->Ticket = \Librerias\Generales\Ticket::factory();

        parent::getCI()->load->helper(array('date'));
    }

    /*
     * Encargado de obtener los servicios del area
     * 
     * 
     */

    public function getServiciosAsignados(string $departamento, string $folio = NULL)
    {
        if (!empty($folio)) {
            $whereFolio = 'AND Folio = "' . $folio . '"';
            $routinQueryAll = 'call getServiciosAreaByDepartamentoFolio("' . $departamento . '", "' . $folio . '")';
            $routinQuerySupervisor = 'call getServiciosByDepartamentoFolio("' . $departamento . '", "' . $folio . '")';
        } else {
            $whereFolio = '';
            $routinQueryAll = 'call getServiciosAreaByDepartamento("' . $departamento . '")';
            $routinQuerySupervisor = 'call getServiciosByDepartamento("' . $departamento . '")';
        }
        //En el arreglo se agregan los perfiles que van a poder ver todas los servicios del departamento.
        //        $perfilGerente = array('1', '2', '3', '4');
        $perfilGerente = [];
        $usuario = $this->Usuario->getDatosUsuario();
        $permisosCompletosTodosServicios = FALSE;
        $permisosCompletosTodosServiciosDepartamento = FALSE;

        if (in_array($usuario['Id'], $perfilGerente)) {
            $permisosCompletosTodosServicios = TRUE;
        } else if (in_array('213', $usuario['PermisosAdicionales'])) {
            $permisosCompletosTodosServicios = TRUE;
        } else if (in_array('213', $usuario['Permisos'])) {
            $permisosCompletosTodosServicios = TRUE;
        }

        if (in_array('69', $usuario['PermisosAdicionales'])) {
            $permisosCompletosTodosServiciosDepartamento = TRUE;
        } else if (in_array('69', $usuario['Permisos'])) {
            $permisosCompletosTodosServiciosDepartamento = TRUE;
        }

        if ($permisosCompletosTodosServicios === TRUE) {
            $consulta = $this->DBST->getServicios($routinQueryAll);
            $this->DBST->limpiarFuncion();
            return $consulta;
        } else if ($permisosCompletosTodosServiciosDepartamento === TRUE || in_array('STSXX' . $departamento, $usuario['PermisosString'])) {
            $consulta = $this->DBST->getServicios($routinQuerySupervisor);
            $this->DBST->limpiarFuncion();
            return $consulta;
        } else {
            $queryUnion = '';
            $queryUnionMantenimientoCenso = 'union
            select
                tst.Id,								
                tst.Ticket,
                tipoServicio(tst.IdTipoServicio) as Servicio,
                usuario((select Solicita from t_solicitudes where Id = tst.IdSolicitud)) as Solicita,
                tst.FechaCreacion,
                tst.Descripcion,
                tst.IdEstatus,
                tst.IdSolicitud,
                estatus(tst.IdEstatus)as NombreEstatus,
                (SELECT Folio FROM t_solicitudes WHERE Id = tst.IdSolicitud) Folio
            from t_servicios_ticket tst inner join cat_v3_servicios_departamento csd
            on tst.IdTipoServicio = csd.Id or tst.IdTipoServicio = 9
            INNER JOIN t_solicitudes AS ts
            ON ts.Id = tst.IdSolicitud
            where tst.IdEstatus in (1,2,3,10,12)
            ' . $whereFolio . '
            and tst.IdTipoServicio in (11,12) group by tst.Id desc ';
            $queryUnionLogistica = 'union
            select
                tst.Id,								
                tst.Ticket,
                tipoServicio(tst.IdTipoServicio) as Servicio,
                usuario((select Solicita from t_solicitudes where Id = tst.IdSolicitud)) as Solicita,
                tst.FechaCreacion,
                tst.Descripcion,
                tst.IdEstatus,
                tst.IdSolicitud,
                estatus(tst.IdEstatus)as NombreEstatus,
                (SELECT Folio FROM t_solicitudes WHERE Id = tst.IdSolicitud) Folio
            from t_servicios_ticket tst inner join cat_v3_servicios_departamento csd
            on tst.IdTipoServicio = csd.Id or tst.IdTipoServicio = 9
            INNER JOIN t_solicitudes AS ts
            ON ts.Id = tst.IdSolicitud
            where tst.IdEstatus in (1,2,3,10,12)
            ' . $whereFolio . '
            and (csd.IdDepartamento = 5 or tst.IdTipoServicio = 9) group by tst.Id desc ';

            if (in_array('76', $usuario['PermisosAdicionales'])) {
                $queryUnion = $queryUnionMantenimientoCenso;
            } else if (in_array('76', $usuario['Permisos'])) {
                $queryUnion = $queryUnionMantenimientoCenso;
            }

            if (in_array('207', $usuario['PermisosAdicionales'])) {
                $queryUnion = $queryUnionLogistica;
            } else if (in_array('207', $usuario['Permisos'])) {
                $queryUnion = $queryUnionLogistica;
            }

            return $this->DBST->getServicios('
            select 
                tst.Id,
                tst.Ticket,
                tipoServicio(tst.IdTipoServicio) as Servicio,
                usuario((select Solicita from t_solicitudes where Id = tst.IdSolicitud)) as Solicita,                
                tst.FechaCreacion,
                tst.Descripcion,
                tst.IdEstatus,
                tst.IdSolicitud,
                estatus(tst.IdEstatus)as NombreEstatus,
                (SELECT Folio FROM t_solicitudes WHERE Id = tst.IdSolicitud) Folio
            from t_servicios_ticket tst inner join cat_v3_servicios_departamento csd
            on tst.IdTipoServicio = csd.Id or tst.IdTipoServicio = 9
            INNER JOIN t_solicitudes AS ts
            ON ts.Id = tst.IdSolicitud
            where tst.Atiende = ' . $usuario['Id'] . '
            and tst.IdEstatus in (1,2,3,10,12)
            AND tst.IdTipoServicio != 45
            ' . $whereFolio . '
            and (csd.IdDepartamento = ' . $departamento . ' or tst.IdTipoServicio = 9) group by tst.Id desc '
                . $queryUnion);
        }
    }

    /*
     * Encargado de obtener los servicios en estatus de validacion
     * 
     * 
     */

    public function getServiciosEnValidacion()
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $permisoValidacion = 'AND ts.Solicita = "' . $usuario['Id'] . '" AND tst.IdTipoServicio != "11"';

        if (in_array('79', $usuario['PermisosAdicionales']) || in_array('79', $usuario['Permisos'])) {
            $permisoValidacion = '';
        } elseif (in_array('80', $usuario['PermisosAdicionales']) || in_array('80', $usuario['Permisos'])) {
            $permisoValidacion = ' and (tst.Atiende in (select Id from cat_v3_usuarios where IdPerfil in (select Id from cat_perfiles cp where IdDepartamento = 11) AND IdDepartamento != "7")) ';
        }
        //elseif (in_array('82', $usuario['Permisos']) || in_array('82', $usuario['PermisosAdicionales'])) {
        //            $permisoValidacion = ' and (tst.Atiende in (select Id from cat_v3_usuarios where IdPerfil in (select Id from cat_perfiles cp where cp.IdDepartamento = 7))) ';
        //        }

        return $this->DBST->getServicios('
                SELECT 
                    tst.Id,
                    tst.Ticket,
                    tipoServicio(tst.IdTipoServicio) as Servicio,
                    nombreUsuario((select Solicita from t_solicitudes where Id = tst.IdSolicitud)) as Solicita,
                    tst.FechaCreacion,
                    tst.Descripcion,
                    tst.IdEstatus,
                    tst.IdSolicitud,
                    estatus(tst.IdEstatus)as NombreEstatus,
                    nombreUsuario(tst.Atiende) as Atiende,
                    tst.Atiende as IdAtiende,
					(SELECT IdPerfil FROM cat_v3_usuarios WHERE Id = tst.Atiende) AS IdPerfil,
					(SELECT IdDepartamento FROM cat_perfiles WHERE Id = IdPerfil) AS IdDepartamento
                FROM t_servicios_ticket tst
                INNER JOIN t_solicitudes ts
                    ON ts.Id = tst.IdSolicitud
                WHERE tst.IdEstatus = "5"'
            . $permisoValidacion);
    }

    /*
     * Encargado de generar un servicio
     * 
     */

    public function setServicio(array $datos, string $servicio = null)
    {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $atiende = $this->DBST->getDatosAtiende($datos['Atiende']);
        $numeroServicio = $this->DBST->setNuevoServicio($datos);
        if (!empty($numeroServicio)) {
            if ($datos['IdTipoServicio'] === '5') {
                $trafico = $this->DBST->setServicioTrafico(array(
                    'IdServicio' => $numeroServicio,
                    'IdUsuarioCaptura' => $datos['Solicita'],
                    'FechaCaptura' => $datos['FechaCreacion']
                ));
            }
            $data['departamento'] = $atiende['IdDepartamento'];
            $data['remitente'] = $usuario['Id'];
            $data['tipo'] = '7';
            $data['descripcion'] = 'La genero el servicio <b class="f-s-16">' . $numeroServicio . '</b> del ticket ' . $datos['Ticket'];

            $this->Notificacion->setNuevaNotificacion(
                $data,
                'Nuevo servicio',
                'El usuario <b>' . $usuario['Nombre'] . '</b> a generado el servicio "<strong>' . $servicio . '</strong>" del ticket ' . $datos['Ticket'] . '<br>
                        La fecha de creacion fue el ' . $datos['FechaCreacion'] . '. <br> Por lo que se solicita que se atienda lo mas pronto posible el servicio.',
                $atiende
            );

            return $numeroServicio;
        } else {
            return FALSE;
        }
    }

    /*
     * Encargado de actualizar el servicio
     * 
     */

    public function actualizarServicio(array $datos)
    {
        $data = array();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $usuario = $this->Usuario->getDatosUsuario();
        $datosServicio = $this->DBST->getDatosServicio($datos['servicio']);
        $idSolicitud = $datosServicio['IdSolicitud'];
        $data['datosServicio'] = $datosServicio;
        $data['notas'] = $this->Notas->getNotasByServicio($datos['servicio'], $idSolicitud);
        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
        $data['idPerfil'] = $usuario['IdPerfil'];

        if ($datosServicio['tieneSeguimiento'] !== '0') {

            //No eliminar se ocupara despues
            //        if ($datosServicio['IdTipoServicio'] === '4') {
            //            if ($datos['operacion'] === '1') {
            //                //Inicia un servicio para dar de alta un personal en un proyecto
            //                $data['informacion'] = $this->actualizarServicioPersonalProyecto($datos['servicio'], $datosServicio, $fecha);
            //                $data['formulario'] = parent::getCI()->load->view('RH/Modal/SeguimientoPersonalProyecto', $data, TRUE);
            //            } else if ($datos['operacion'] === '2') {
            //                //Obtiene la informacion del servicio
            //                $data['informacion'] = $this->getServicioPersonalProyecto($datos['servicio'], $datosServicio);
            //                $data['formulario'] = parent::getCI()->load->view('RH/Modal/SeguimientoPersonalProyecto', $data, TRUE);
            //            } else if ($datos['operacion'] === '3') {
            //                //Agregan asistente al proyecto.                
            //                $data['informacion'] = $this->setAsistenteProyectoPersonal($datos, $fecha);
            //            } else if ($datos['operacion'] === '4') {
            //                //Eliminando asistente del proyecto.                
            //                $data['informacion'] = $this->eliminarAsistenteProyectoPersonal($datos);
            //            } else if ($datos['operacion'] === '5') {
            //                //Concluye el servicio de personal de proyecto.
            //                $data['informacion'] = $this->concluirServicioProyectoPersonal($datos, $fecha);
            //            } else if ($datos['operacion'] === '7') {
            //                return($datosServicio['IdTipoServicio']);
            //            }
            //        } else 
            if ($datosServicio['IdTipoServicio'] === '5') {
                if ($datos['operacion'] === '1') {
                    //Inicia un servicio para seguimiento de trafico
                    $data['informacion'] = $this->actualizarServicioTrafico($datos['servicio'], $datosServicio, $fecha);
                    $data['serviciosAsignados'] = $this->getServiciosAsignados('17');
                    $data['formulario'] = parent::getCI()->load->view('Logistica/Modal/FormularioSeguimiento', $data, TRUE);
                } else if ($datos['operacion'] === '2') {
                    //Obtiene la informacion del servicio de trafico
                    $data['informacion'] = $this->getServicioTrafico($datos['servicio'], $datosServicio, $usuario);
                    $data['serviciosAsignados'] = $this->getServiciosAsignados('17');
                    $data['formulario'] = parent::getCI()->load->view('Logistica/Modal/FormularioSeguimiento', $data, TRUE);
                } else if ($datos['operacion'] === '3') {
                    //Encargado de actualizar los datos generales de seguimiento logistica
                    $data['serviciosAsignados'] = $this->actualizarTraficoGenerales($datos, $fecha, $usuario, $datosServicio);
                    $datosServicio = $this->DBST->getDatosServicio($datos['servicio']);
                    $data['datosServicio'] = $datosServicio;
                    $data['informacion'] = $this->getServicioTrafico($datos['servicio'], $datosServicio, $usuario);
                    $data['formulario'] = parent::getCI()->load->view('Logistica/Modal/FormularioSeguimiento', $data, TRUE);
                } else if ($datos['operacion'] === '4') {
                    // guardar y actualizar el material de un servcicio de trafico
                    $data['materialActualizado'] = $this->setMaterialServicioTrafico($datos);
                } else if ($datos['operacion'] === '5') {
                    //concluir un servicio
                    if ($datos['idTipoTrafico'] === '1') {
                        $this->SeguimientoLogistica->actualizarEnvio($datos['datosFormulario']);
                    } else if ($datos['idTipoTrafico'] === '2') {
                        $this->SeguimientoLogistica->actualizarTraficoRecoleccion($datos['datosFormulario']);
                    }

                    $data['informacion'] = $this->concluirServicioTrafico($datos['servicio'], $datosServicio, $fecha, $usuario);
                } else if ($datos['operacion'] === '7') {
                    return ($datosServicio['IdTipoServicio']);
                }
            } else if ($datosServicio['IdTipoServicio'] === '6') {
                switch ($datos['operacion']) {
                        //inicia servicio seguimiento de los servicios mantenimiento preventivo salasx4d
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('7');
                        break;
                    case '2':
                        $data['informacion'] = $this->getServicioMantenimientoSalas(array('ticket' => $datosServicio['Ticket'], 'servicio' => $datos['servicio']));
                        $data['formulario'] = parent::getCI()->load->view('Salas4D/Modal/FormularioSeguimientoMantenimiento', $data, TRUE);
                        break;
                }
            } else if ($datosServicio['IdTipoServicio'] === '7') {
                switch ($datos['operacion']) {
                        //inicia servicio seguimiento de los servicios mantenimiento correctivo salasx4d
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('7');
                        break;
                    case '2':
                        $data['tipoSolucion'] = $this->DBCS->getTipoSolucion();
                        $data['getSolucionByServicio'] = $this->DBCS->getSolucionByServicio(array('servicio' => $datos['servicio']));
                        $data['consultarServicio'] = $this->DBCS->getCorrectivosGenerales(array('servicio' => $datos['servicio']));
                        $data['sucursal4D'] = $this->DBCS->getSucursales4D();
                        $data['tipoFalla'] = $this->DBCS->getTipoFalla();
                        $data['informacion'] = $this->getServicioMantenimientoSalas(array('ticket' => $datosServicio['Ticket'], 'servicio' => $datos['servicio']));
                        $data['formulario'] = parent::getCI()->load->view('Salas4D/Modal/FormularioSeguimientoMantenimientoCorrectivo', $data, TRUE);
                        break;
                }
            } else if ($datosServicio['IdTipoServicio'] === '10') {
                /* Aqui comienzan las lineas de seguimiento de los servicios de Uber */
                switch ($datos['operacion']) {
                        /* Inicia el servicio de Uber */
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('4');
                        break;
                        /* Obtiene el formulario del seguimiento para servicios de Uber */
                    case '2':
                        $data['formulario'] = parent::getCI()->load->view('MesaDeAyuda/Modal/FormularioSeguimiento', $data, TRUE);
                        break;
                        /* Concluye el servicio de Uber y retorna los servicios del departamento */
                    case '3':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '5', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('4');
                        unset($data['datosServicio']);
                        unset($data['notas']);
                        break;
                }
            } else if ($datosServicio['IdTipoServicio'] === '11') {
                /* Aqui comienzan las lineas de seguimiento de los servicios de Censo */
                switch ($datos['operacion']) {
                        /* Inicia el servicio de Censo */
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
                        break;
                        /* Obtiene el formulario del seguimiento para servicios de Censo */
                    case '2':
                        $data['servicio'] = $datos['servicio'];
                        $data['informacionDatosGenerales'] = $this->DBST->consultaGeneral('SELECT * FROM t_censos_generales WHERE IdServicio = "' . $datos['servicio'] . '"');
                        $data['informacionDatosCenso'] = $this->SeguimientoPoliza->consultaTodosCensoServicio($datos['servicio']);
                        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($datosServicio['Ticket']);
                        $data['areasAtencion'] = $this->Catalogo->catAreasAtencion('3', array('Flag' => '1'));
                        //$data['Infostatus']= $this->DBST->consulta('select * from hist_salas4d_mantto_actividades'); 
                        $data['modelos'] = $this->Catalogo->catModelosEquipo('3', array('Flag' => '1'));
                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
                        $data['documentacionFirmada'] = $this->consultaDocumentacioFirmadaServicio($datos['servicio']);
                        $data['formulario'] = parent::getCI()->load->view('Poliza/Modal/formularioSeguimientoServicioCenso', $data, TRUE);
                        break;
                        /* Concluye el servicio de censo y retorna los servicios del departamento */
                    case '3':
                        $consulta = $this->SeguimientoPoliza->guardarDatosCenso($datos);
                        if (!empty($consulta)) {
                            $this->verificarServicio($datos);
                            $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        }
                        break;
                }
            } else if ($datosServicio['IdTipoServicio'] === '12') {
                /* Aqui comienzan las lineas de seguimiento de los servicios de Mantenimiento */
                switch ($datos['operacion']) {
                        /* Inicia el servicio de Mantenimiento */
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
                        break;
                        /* Obtiene el formulario del seguimiento para servicios de Mantenimiento */
                    case '2':
                        $data['servicio'] = $datos['servicio'];
                        $data['informacion'] = $this->getServicioMantenimiento($datos['servicio'], $datosServicio['Ticket']);
                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
                        $data['formulario'] = parent::getCI()->load->view('Poliza/Modal/formularioSeguimientoServicioMantenimiento', $data, TRUE);
                        break;
                        /* Concluye el servicio de Mantenimiento y retorna los servicios del departamento */
                    case '3':
                        $this->verificarServicio($datos);
                        $data['informacionServicio']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        break;
                }
            } else if ($datosServicio['IdTipoServicio'] === '20' || $datosServicio['IdTipoServicio'] === '27') {
                $data['historialAvancesProblemas'] = $this->mostrarHistorialAvancesProblemas($datos['servicio']);
                $data['bitacoraReporteFalso'] = $this->SeguimientoPoliza->mostrarBitacoraReporteFalso($datos['servicio']);
                /* Aqui comienzan las lineas de seguimiento de los servicios de Correctivo */
                switch ($datos['operacion']) {
                        /* Inicia el servicio de Correctivo */
                    case '1':
                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
                        $this->setStatusSD($datosServicio['Folio']);
                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
                        break;
                        /* Obtiene el formulario del seguimiento para servicios de Correctivo */
                    case '2':
                        $data['informacion'] = $this->getServicioCorrectivo($datos['servicio'], $datosServicio['Ticket']);
                        $data['servicio'] = $datos['servicio'];
                        $usuario = $this->Usuario->getDatosUsuario();
                        /*
                         * Revisa si se ocuparon componentes del inventario para este servicio y no omitirlos 
                         * por estar bloqueados                         
                         */
                        $componentesUtilizadosStock = '';
                        if (isset($data['informacion']['correctivosSolucionRefaccion'])) {
                            foreach ($data['informacion']['correctivosSolucionRefaccion'] as $key => $value) {
                                $componentesUtilizadosStock .= ',' . $value['IdInventario'];
                            }

                            $componentesUtilizadosStock = ($componentesUtilizadosStock != '') ? substr($componentesUtilizadosStock, 1) : '';
                        }

                        /*
                         * Revisa si se ocuparon equipos del inventario para este servicio y no omitirlos 
                         * por estar bloqueados                         
                         */
                        $equiposUtilizadosStock = '';
                        if (isset($data['informacion']['correctivosSolucionCambio'])) {
                            foreach ($data['informacion']['correctivosSolucionCambio'] as $key => $value) {
                                $equiposUtilizadosStock .= ',' . $value['IdInventario'];
                            }

                            $equiposUtilizadosStock = ($equiposUtilizadosStock != '') ? substr($equiposUtilizadosStock, 1) : '';
                        }


                        /*
                         * * Se determina si se tiene o no el permiso para que el usuario dentro de las soluciones determine
                         * que usará algún componente y equipo completo del stock de inventario a consignación
                         */
                        $data['usarStock'] = (in_array(293, $usuario['PermisosAdicionales']) || in_array(293, $usuario['Permisos'])) ? true : false;
                        if (isset($data['informacion']['informacionDatosGeneralesCorrectivo'][0])) {
                            $data['inventarioComponentes'] = $this->DBA->getComponentesDisponiblesParaServicio($usuario['Id'], $data['informacion']['informacionDatosGeneralesCorrectivo'][0]['IdModelo'], $componentesUtilizadosStock);
                            $data['inventarioEquipos'] = $this->DBA->getEquiposDisponiblesParaServicio($usuario['Id'], $data['informacion']['informacionDatosGeneralesCorrectivo'][0]['IdModelo'], $equiposUtilizadosStock);
                        } else {
                            $data['inventarioComponentes'] = [];
                            $data['inventarioEquipos'] = [];
                        }
                        $data['formulario'] = parent::getCI()->load->view('Poliza/Modal/formularioSeguimientoServicioCorrectivo', $data, TRUE);
                        break;
                        /* Concluye el servicio de Correctivo y retorna los servicios del departamento */
                    case '3':
                        $this->verificarServicio($datos);
                        $data['informacionServicio']['serviciosAsignados'] = $this->getServiciosAsignados('11');
                        break;
                }
            }
            //            else if ($datosServicio['IdTipoServicio'] === '27') {
            //                switch ($datos['operacion']) {
            //                    case '1':
            //                        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2', '4');
            //                        $this->setStatusSD($datosServicio['Folio']);
            //                        $data['informacion']['serviciosAsignados'] = $this->getServiciosAsignados('11');
            //                        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
            //                        break;
            //                    case '2':
            //                        $data['informacion'] = $this->getServicioChecklist(array('ticket' => $datosServicio['Ticket'], 'servicio' => $datos['servicio']));
            //                        $data['catalogoCategorias'] = $this->DBMP->consultaCategorias();
            //                        $data['categoriasRevisionPunto'] = $this->DBMP->mostrarCategoriaRevisionPunto();
            //                        $data['formulario'] = parent::getCI()->load->view('Poliza/InformacionGeneralChecklist', $data, TRUE);
            //                        break;
            //                }
            //            }
        } else {
            $request_url = explode("/", $_SERVER['REQUEST_URI']);
            $idDepartamento = 0;
            switch ($request_url[1]) {
                case 'Administrador':
                    $idDepartamento = '19';
                    break;
                case 'Compras':
                    $idDepartamento = '15';
                    break;
                case 'Contabilidad':
                    $idDepartamento = '22';
                    break;
                case 'Laboratorio':
                    $idDepartamento = '10';
                    break;
                case 'Logistica':
                    $idDepartamento = '17';
                    break;
                case 'MesaDeAyuda':
                    $idDepartamento = '4';
                    break;
                case 'Poliza':
                    $idDepartamento = '46';
                    break;
                case 'Redes':
                    $idDepartamento = '5';
                    break;
                case 'RH':
                    $idDepartamento = '3';
                    break;
                case 'SalasX4D':
                    $idDepartamento = '7';
                    break;
                case 'Sistemas':
                    $idDepartamento = '34';
                    break;
                case 'Almacen':
                    $idDepartamento = '16';
                    break;
                case 'FacturacionCobranza':
                    $idDepartamento = '41';
                    break;
            }

            if ($datos['operacion'] === '1') {
                return $this->modalServicioSinEspecificar($datosServicio, $datos['servicio'], $fecha, $idDepartamento, $idSolicitud);
            } else if ($datos['operacion'] === '2') {
                return $this->modalServicioSinEspecificar($datosServicio, $datos['servicio'], null, $idDepartamento, $idSolicitud);
            } else if ($datos['operacion'] === '3') {
                return $this->actualizarServicioGeneral($datos, $usuario, $fecha);
            } else if ($datos['operacion'] === '4') {
                $actualizar = $this->actualizarServicioGeneral($datos, $usuario, $fecha);
                if ($actualizar) {
                    return $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '5', $idDepartamento);
                }
            } else if ($datos['operacion'] === '7') {
                return $this->verificarServicio($datos['servicio'], $fecha, $datos['ticket'], $datos['idSolicitud']);
            }
        }
        return $data;
    }

    /*
     * Encargado de actualizar un servicio de personal de proyecto
     * 
     */

    private function actualizarServicioPersonalProyecto(string $servicio, array $datosServicio, string $fecha)
    {
        $data = array();
        $datosProyecto = $this->DBST->getDatosProyecto($datosServicio['Ticket']);
        $datosSolicitud = $this->DBS->getDatosSolicitud($datosServicio['IdSolicitud']);
        $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => '2',
            'FechaInicio' => $fecha
        ), array('Id' => $servicio));
        if (!empty($consulta)) {
            $data['asistentes'] = $this->DBST->getAsistentes();
            $data['asistentesProyecto'] = $this->DBST->getAsistentesProyecto($servicio);
            $data['datosProyecto'] = $datosProyecto;
            $data['datosSolicitud'] = $datosSolicitud;
            $data['serviciosAsignados'] = $this->getServiciosAsignados('3');
        }
        return $data;
    }

    /*
     * Encargado de actualizar el servicio del trafico
     * 
     */

    private function actualizarServicioTrafico(string $servicio, array $datosServicio, string $fecha)
    {
        $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => '2',
            'FechaInicio' => $fecha
        ), array('Id' => $servicio));
        if (!empty($consulta)) {
            $this->setStatusSD($datosServicio['Folio']);
            $data['datosTrafico'] = $this->DBST->getDatosTrafico($servicio);
            $data['tiposTrafico'] = $this->Catalogo->catTiposTrafico('3');
            $data['tiposOrigenDestino'] = $this->Catalogo->catTiposOrigenDestino('3');
            $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($datosServicio['Ticket']);
            $data['probedores'] = $this->Catalogo->catProveedores('3', array('Flag' => '1'));
            $data['rutas'] = $this->DBST->getRutas($data['datosTrafico']['Ruta']);
            $data['ListaMaterial'] = $this->DBST->getMaterial();
            $data['ListaTiposEnvio'] = $this->Catalogo->catTiposEnvio('3');
            $data['ListaConsolidados'] = $this->Catalogo->catTiposConsolidados('3');
            $data['ListaPaqueteria'] = $this->Catalogo->catTiposPaqueteria('3');
            $data['datosEnvio'] = $this->DBST->getDatosEnvio($servicio);
            $data['datosRecoleccion'] = $this->DBST->getDatosRecoleccion($servicio);
            $data['serviciosAsignados'] = $this->getServiciosAsignados('17');
        }
        return $data;
    }

    /*
     * Encargado de obtener lo datos del servicio de personal para un proyecto
     * 
     */

    private function getServicioPersonalProyecto(string $servicio, array $datosServicio)
    {
        $data = array();
        $datosProyecto = $this->DBST->getDatosProyecto($datosServicio['Ticket']);
        $datosSolicitud = $this->DBS->getDatosSolicitud($datosServicio['IdSolicitud']);
        $data['asistentes'] = $this->DBST->getAsistentes();
        $data['asistentesProyecto'] = $this->DBST->getAsistentesProyecto($servicio);
        $data['datosProyecto'] = $datosProyecto;
        $data['datosSolicitud'] = $datosSolicitud;
        return $data;
    }

    /*
     * Encargado de obtener lo datos del servicio de un trafico
     * 
     */

    private function getServicioTrafico(string $servicio, array $datosServicio, array $usuario)
    {
        $data = array();
        //En el arreglo se agregan los perfiles que van a poder ver todas los servicios del departamento.
        $data['datosTrafico'] = $this->DBST->getDatosTrafico($servicio);
        $data['tiposTrafico'] = $this->Catalogo->catTiposTrafico('3');
        $data['tiposOrigenDestino'] = $this->Catalogo->catTiposOrigenDestino('3');
        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($datosServicio['Ticket']);
        $data['probedores'] = $this->Catalogo->catProveedores('3', array('Flag' => '1'));
        $data['rutas'] = $this->DBST->getRutas($data['datosTrafico']['Ruta']);
        $data['ListaEquipos'] = $this->DBST->getMaterial('1');
        $data['ListaMaterial'] = $this->DBST->getMaterial();
        $data['ListaTiposEnvio'] = $this->Catalogo->catTiposEnvio('3');
        $data['ListaConsolidados'] = $this->Catalogo->catTiposConsolidados('3');
        $data['ListaPaqueteria'] = $this->Catalogo->catTiposPaqueteria('3');
        $data['datosEnvio'] = $this->DBST->getDatosEnvio($servicio);
        $data['datosRecoleccion'] = $this->DBST->getDatosRecoleccion($servicio);
        $data['listaEnviosDistribucion'] = $this->DBST->obtenerListaEnviosDistribucion($servicio);
        $data['equiposDistribuciones'] = $this->DBST->obtenerEquiposParaDistribuciones($servicio);
        $data['equiposFaltantesDistribuciones'] = $this->DBST->obtenerEquiposFaltantesDistribuciones($servicio);
        $data['folio'] = $this->DBST->getServicios('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $datosServicio['Ticket'] . '"');
        return $data;
    }

    /*
     * Encargado de guardar la lista de asistentes del proyecto
     * 
     */

    private function setAsistenteProyectoPersonal(array $datos, string $fecha)
    {
        return $this->DBST->setAsistenteProyecto(array(
            'IdServicio' => $datos['servicio'],
            'IdUsuario' => $datos['usuario'],
            'IdProyecto' => $datos['proyecto'],
            'IdEstatus' => '11',
            'FechaAsignacion' => $fecha
        ));
    }

    /*
     * Encargado de eliminar un asistente del proyecto
     * 
     */

    private function eliminarAsistenteProyectoPersonal(array $datos)
    {
        return $this->DBST->eliminarAsistenteProyecto(array(
            'IdServicio' => $datos['servicio'],
            'IdUsuario' => $datos['usuario'],
            'IdProyecto' => $datos['proyecto']
        ));
    }

    /*
     * Encargado de concluir un servicio de personal para proyecto
     * 
     */

    private function concluirServicioProyectoPersonal(array $datos, string $fecha)
    {
        $servicio = $this->DBST->getDatosServicio($datos['servicio']);
        $datosSolicitud = $this->DBS->getDatosSolicitud($servicio['IdSolicitud']);
        $usuario = $this->Usuario->getDatosUsuario();
        $solicitante = $this->DBS->getDatosSolicitante($datosSolicitud['Solicita']);
        if (!empty($servicio)) {
            $actualizarSolicitud = $this->DBS->actualizarSolicitud('t_solicitudes', array(
                'IdEstatus' => '4',
                'FechaConclusion' => $fecha,
            ), array('Id' => $servicio['IdSolicitud']));
            if (!empty($actualizarSolicitud)) {
                $actualizarServicio = $this->DBST->actualizarServicio('t_servicios_ticket', array(
                    'IdEstatus' => '4',
                    'FechaConclusion' => $fecha
                ), array('Id' => $datos['servicio']));
                if (!empty($actualizarServicio)) {
                    $this->enviarNotificacion(array(
                        'Departamento' => $datosSolicitud['IdDepartamento'],
                        'remitente' => $usuario['Id'],
                        'tipo' => '4',
                        'descripcion' => 'La solicitud <b class="f-s-16">' . $servicio['IdSolicitud'] . '</b> ha sido concluida por el usuario ' . $usuario['Nombre'],
                        'titulo' => 'Solicitud Concluida',
                        'mensaje' => 'El usuario <b>' . $usuario['Nombre'] . '</b> a concluido la solicitud <b class="f-s-16">' . $servicio['IdSolicitud'] . '</b>.<br>
                                Por tal motivo se a terminado el servicio de reclutamiento de personal del proyecto con ticket ' . $servicio['Ticket'] . ' .'
                    ), $solicitante);
                    return $this->getServiciosAsignados('3');
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * Encargada de generar la notificacion
     * 
     * @param array $datos Recibe los valores de el departamento, remitente, tipo y descripcion para la notificación
     * @param array $atiende Recibe los datos del usuario al que sera enviada la notificación.
     * 
     */

    private function enviarNotificacion(array $datos, array $atiende = null)
    {
        $data['departamento'] = $datos['Departamento'];
        $data['remitente'] = $datos['remitente'];
        $data['tipo'] = $datos['tipo'];
        $data['descripcion'] = $datos['descripcion'];

        $this->Notificacion->setNuevaNotificacion($data, $datos['titulo'], $datos['mensaje'], $atiende);
    }

    /*
     * Encargado de guardar y actualizar el material de un servcicio de trafico
     * 
     */

    private function setMaterialServicioTrafico(array $datos)
    {
        $materialAgregado = true;
        $this->DBST->eliminarMaterial(array('IdServicio' => $datos['servicio']));
        foreach ($datos['material'] as $value) {
            $consulta = $this->DBST->actualizarMaterial(array(
                'IdServicio' => $datos['servicio'],
                'IdTipoEquipo' => $value[3],
                'IdModelo' => ($value[3] !== '4') ? $value[4] : NULL,
                'DescripcionOtros' => ($value[3] !== '4') ? '' : $value[0],
                'Serie' => $value[1],
                'Cantidad' => $value[2]
            ));
            if (!$consulta) {
                $materialAgregado = false;
                break;
            }
        }

        if ($materialAgregado) {
            $materialAgregado = $this->DBST->getDatosTrafico($datos['servicio']);
        }
        return $materialAgregado;
    }

    /* Encargado de actualizar los datos generales del seguimiento logistica
     * 
     */

    private function actualizarTraficoGenerales(array $datos, string $fecha, array $usuario, array $datosServicio)
    {
        $data = array();
        $data2 = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $tipoOrigen = $this->obtenerTipoCampo('1', $datos['origen']);
        $tipoDestino = $this->obtenerTipoCampo('2', $datos['destino']);
        $consulta = $this->DBST->actualizarServicio('t_traficos_generales', array(
            'IdTipoTrafico' => $datos['tipoTrafico'],
            'IdTipoOrigen' => $datos['tipoOrigen'],
            $tipoOrigen => $datos['origen'],
            'IdTipoDestino' => $datos['tipoDestino'],
            $tipoDestino => $datos['destino'],
        ), array('IdServicio' => $datos['servicio']));
        if (!empty($datos['ruta'])) {
            $this->DBST->setServiciosRuta(array(
                'IdRuta' => $datos['ruta'],
                'IdServicio' => $datos['servicio'],
                'IdUsuarioCaptura' => $usuario['Id'],
                'FechaCaptura' => $fecha,
                'Flag' => '1'
            ), array('IdServicio' => $datos['servicio']));
            $datosRuta = $this->DBST->getServicios('select IdEstatus from t_rutas_logistica where Id = ' . $datos['ruta']);
            if (!empty($datosRuta)) {
                $this->DBST->actualizarServicio('t_servicios_ticket', array('IdEstatus' => $datosRuta[0]['IdEstatus']), array('Id' => $datos['servicio']));
            }
        } else {
            $this->DBST->actualizarServicio('t_servicios_ticket', array('IdEstatus' => '2'), array('Id' => $datos['servicio']));
        }

        $serviciosAsiganados = $this->getServiciosAsignados('17');
        return $serviciosAsiganados;
    }

    /* Encargado de sellecionar el nombre del campo en la BD
     * 
     */

    private function obtenerTipoCampo(string $operacion, string $tipo)
    {
        switch ($operacion) {
            case '1':
                if (is_numeric($tipo)) {
                    $array = 'IdOrigen';
                    return $array;
                } else {
                    $array = 'OrigenDireccion';
                    return $array;
                }
                break;
            case '2':
                if (is_numeric($tipo)) {
                    $array = 'IdDestino';
                    return $array;
                } else {
                    $array = 'DestinoDireccion';
                    return $array;
                }
        }
    }

    /*
     * Encargado de Concluir servicio de trafico
     * 
     */

    private function concluirServicioTrafico(string $servicio, array $datosServicio, string $fecha, array $usuario)
    {
        $data = array();
        $data['servicioConcluido'] = true;
        $tipoTrafico = null;
        $generales = $this->SeguimientoLogistica->verificarExistente(array('servicio' => $servicio, 'operacion' => '5'));
        $url = true;

        if (!empty($generales)) {
            foreach ($generales as $value) {
                $tipoTrafico = $value['IdTipoTrafico'];
                $Origen = $value['NombreOrigen'];
                $Destino = $value['NombreDestino'];
                if ($value['IdTipoTrafico'] === '' || $value['IdTipoTrafico'] === NULL) {
                    $data['servicioConcluido'] = False;
                }
                if ($value['IdTipoOrigen'] === '' || $value['IdTipoOrigen'] === NULL) {
                    $data['servicioConcluido'] = False;
                } else if ($value['IdTipoOrigen'] === '1' || $value['IdTipoOrigen'] === '2') {
                    if ($value['IdOrigen'] === '' || $value['IdOrigen'] === NULL) {
                        $data['servicioConcluido'] = False;
                    }
                } else if ($value['IdTipoOrigen'] === '3') {
                    if ($value['OrigenDireccion'] === '' || $value['OrigenDireccion'] === NULL) {
                        $data['servicioConcluido'] = False;
                    }
                }

                if ($value['IdTipoDestino'] === '' || $value['IdTipoDestino'] === NULL) {
                    $data['servicioConcluido'] = False;
                } else if ($value['IdTipoDestino'] === '1' || $value['IdTipoDestino'] === '2') {
                    if ($value['IdDestino'] === '' || $value['IdDestino'] === NULL) {
                        $data['servicioConcluido'] = False;
                    }
                } else if ($value['IdTipoDestino'] === '3') {
                    if ($value['DestinoDireccion'] === '' || $value['DestinoDireccion'] === NULL) {
                        $data['servicioConcluido'] = False;
                    }
                }

                if ($value['Ruta'] === '' || $value['Ruta'] === Null) {
                    $data['servicioConcluido'] = False;
                }
            }

            if ($data['servicioConcluido']) {
                if ($tipoTrafico === '1') {
                    $envio = $this->SeguimientoLogistica->verificarExistente(array('servicio' => $servicio, 'operacion' => '3'));
                    if (!empty($envio)) {
                        foreach ($envio as $value) {

                            if ($value['FechaEnvio'] === '0000-00-00 00:00:00' || $value['FechaEnvio'] === NULL) {
                                $data['servicioConcluido'] = False;
                            }

                            if ($value['IdTipoEnvio'] === '' || $value['IdTipoEnvio'] === NULL) {
                                $data['servicioConcluido'] = False;
                            } else if ($value['IdTipoEnvio'] === '1') {
                                if ($value['FechaEntrega'] === '0000-00-00 00:00:00' || $value['FechaEntrega'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }
                                if ($value['NombreRecibe'] === '' || $value['NombreRecibe'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }
                            } else if ($value['IdTipoEnvio'] === '2' || $value['IdTipoEnvio'] === '3') {
                                if ($value['FechaEnvio'] === '0000-00-00 00:00:00' || $value['FechaEnvio'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }

                                if ($value['IdPaqueteria'] === '' || $value['IdPaqueteria'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }

                                if ($value['Guia'] === '' || $value['Guia'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }

                                if ($value['FechaEntrega'] === '0000-00-00 00:00:00' || $value['FechaEntrega'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }

                                if ($value['NombreRecibe'] === '' || $value['NombreRecibe'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                }

                                if ($value['UrlEntrega'] === '' || $value['UrlEntrega'] === NULL) {
                                    $data['servicioConcluido'] = False;
                                    $url = False;
                                    $data['mensaje'] = 'Debes subir Evidencia de Entrega.';
                                }
                            }
                        }
                        if (!$data['servicioConcluido']) {
                            if (!$url) {
                                $data['tituloMensaje'] = 'Concluir Servicio';
                            } else {
                                $data['tituloMensaje'] = 'Concluir Servicio';
                                $data['mensaje'] = 'Para concluir el servicio es necesario llene todos los campos obligatorios en la sección de envió';
                            }
                        }
                    } else {
                        $data['servicioConcluido'] = False;
                        $data['tituloMensaje'] = 'Concluir Servicio';
                        $data['mensaje'] = 'Para concluir el servicio es necesario llene todos los campos obligatorios en la sección de envió.';
                    }
                } else if ($tipoTrafico === '2') {
                    $recoleccion = $this->SeguimientoLogistica->verificarExistente(array('servicio' => $servicio, 'operacion' => '4'));
                    if (!empty($recoleccion)) {
                        foreach ($recoleccion as $value) {
                            if ($value['Fecha'] === '0000-00-00 00:00:00' || $value['Fecha'] === NULL) {
                                $data['servicioConcluido'] = False;
                            }
                            if ($value['NombreEntrega'] === '' || $value['NombreEntrega'] === NULL) {
                                $data['servicioConcluido'] = False;
                            }
                            if ($value['UrlRecoleccion'] === '' || $value['UrlRecoleccion'] === NULL) {
                                $data['servicioConcluido'] = False;
                                $url = False;
                                $data['mensaje'] = 'Debes subir Evidencia de Recolección.';
                            }
                        }

                        if (!$data['servicioConcluido']) {
                            if (!$url) {
                                $data['tituloMensaje'] = 'Concluir Servicio';
                            } else {
                                $data['tituloMensaje'] = 'Concluir Servicio';
                                $data['mensaje'] = 'Para concluir el servicio es necesario llene todos los campos obligatorios en la sección de recolección.';
                            }
                        }
                    } else {
                        $data['servicioConcluido'] = False;
                        $data['tituloMensaje'] = 'Concluir Servicio';
                        $data['mensaje'] = 'Para concluir el servicio es necesario llene todos los campos obligatorios en la sección de recolección.';
                    }
                } else if ($tipoTrafico === '3') {

                    $material = $this->DBST->obtenerEquiposFaltantesDistribuciones($servicio);
                    $destinos = $this->DBST->consultaGeneral('select IdEstatus from t_traficos_distribuciones where IdServicio = ' . $servicio . ' and IdEstatus <> 6');

                    if (!empty($material)) {
                        $data['servicioConcluido'] = False;
                        $data['tituloMensaje'] = 'Concluir Servicio';
                        $data['mensaje'] = 'Para concluir el servicio es necesario que todo el material ya esta asignado a un destino y conclido.';
                    } else {
                        foreach ($destinos as $destino) {
                            if ($destino['IdEstatus'] !== '4') {
                                $data['servicioConcluido'] = False;
                                $data['tituloMensaje'] = 'Concluir Servicio';
                                $data['mensaje'] = 'Para concluir el servicio es necesario que todos los destinos esten concluidos.';
                                break;
                            }
                        }
                    }
                }
            } else {
                $data['tituloMensaje'] = 'Concluir Servicio';
                $data['mensaje'] = 'Para concluir el servicio es necesario llene todos los campos obligatorios y debe estar asignada a una ruta. Por favor de validar la sección de generales.';
            }
        }

        if ($data['servicioConcluido']) {
            $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', array('IdEstatus' => '5', 'FechaConclusion' => $fecha), array('Id' => $servicio));
            $equipos = $this->DBST->getDatosTrafico($servicio);

            if ($equipos['Material'] !== NULL) {
                if (!empty($datosServicio['Folio'])) {
                    $html = '<div><h3>Servicio de Trafico</h3></div>';
                    $html .= '<div>Origen: ' . $Origen . '</div><div>Destino: ' . $Destino . '</div><div>Equipos : </div>';

                    foreach ($equipos['Material'] as $value) {
                        $html .= '<div>'
                            . '&nbsp;&nbsp;&nbsp;&nbsp;Tipo: ' . $value['Tipo'] . '&nbsp;&nbsp;&nbsp;&nbsp;'
                            . '&nbsp;&nbsp;&nbsp;&nbsp;Modelo: ' . $value['Nombre'] . '&nbsp;&nbsp;&nbsp;&nbsp;'
                            . '&nbsp;&nbsp;&nbsp;&nbsp;Serie: ' . $value['Serie'] . '&nbsp;&nbsp;&nbsp;&nbsp;'
                            . '&nbsp;&nbsp;&nbsp;&nbsp;Cantidad: ' . $value['Cantidad'] . ''
                            . '</div>';
                    }

                    if ($tipoTrafico === '1') {

                        foreach ($envio as $key => $value) {
                            if ($value['IdTipoEnvio'] === '1') {
                                $html .= '<div>Información de la Entrega del Envio : </div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Fecha y Hora : ' . $value['FechaEnvio'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Quien Recibe : ' . $value['NombreRecibe'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Comentarios : ' . $value['ComentariosEntrega'] . '</div>';
                                $evidencias = explode(',', $value['UrlEntrega']);
                                foreach ($evidencias as $key => $url) {
                                    $direccion = 'http://' . $_SERVER['HTTP_HOST'] . $url;
                                    $html .= "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$direccion' target='_blank'>Evidencia" . (++$key) . "</a></div>";
                                }
                            } else if ($value['IdTipoEnvio'] === '2' || $value['IdTipoEnvio'] === '3') {

                                $html .= '<div>Información del Envio : </div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Fecha y Hora de Envio: ' . $value['FechaEnvio'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Paqueteria : ' . $value['NombrePaqueteria'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Guia : ' . $value['Guia'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Comentarios : ' . $value['ComentariosEntrega'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Evidencias :</div>';
                                $evidencias = explode(',', $value['UrlEnvio']);
                                foreach ($evidencias as $key => $url) {
                                    $direccion = 'http://' . $_SERVER['HTTP_HOST'] . $url;
                                    $html .= "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$direccion' target='_blank'>Evidencia" . (++$key) . "</a></div>";
                                }
                                $html .= '<div>Información de la Entrega : </div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Fecha y Hora Entrega: ' . $value['FechaEnvio'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Quien Recibe : ' . $value['NombreRecibe'] . '</div>';
                                $html .= '<div>&nbsp;&nbsp;&nbsp;&nbsp;Comentarios : ' . $value['ComentariosEntrega'] . '</div>';
                                $evidenciasEntrega = explode(',', $value['UrlEntrega']);
                                foreach ($evidenciasEntrega as $key => $url) {
                                    $direccion = 'http://' . $_SERVER['HTTP_HOST'] . $url;
                                    $html .= "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$direccion' target='_blank'>Evidencia" . (++$key) . "</a></div>";
                                }
                            }
                        }
                    } else if ($tipoTrafico === '2') {
                        $html .= '<div>Información de la Recolección : </div>';
                        foreach ($recoleccion as $value) {
                            $html .= '<div> Fecha y Hora : ' . $value['Fecha'] . '</div>';
                            $html .= '<div> Quien Entrega : ' . $value['NombreEntrega'] . '</div>';
                            $html .= '<div> Comentarios : ' . $value['ComentariosRecoleccion'] . '</div>';
                            $evidencias = explode(',', $value['UrlRecoleccion']);
                            foreach ($evidencias as $key => $url) {
                                $direccion = 'http://' . $_SERVER['HTTP_HOST'] . $url;
                                $html .= "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$direccion' target='_blank'>Evidencia" . (++$key) . "</a></div>";
                            }
                        }
                    }
                    $this->InformacionServicios->setNoteAndWorkLog(array('key' => $usuario['SDKey'], 'folio' => $datosServicio['Folio'], 'html' => $html));
                }
                $data['tituloMensaje'] = 'Servicio Concluido';
                $data['mensaje'] = 'Se concluyó el servicio con éxito.';
            } else {
                $data['mensaje'] = 'Falta Equipos.';
            }
        }


        $data['mensaje'] = '<div class="row">
                                <div class="col-md-12 text-center">
                                    ' . $data['mensaje'] . '
                                </div>
                            </div>';
        $data['serviciosAsignados'] = $this->getServiciosAsignados('17');
        return $data;
    }

    /*
     * Encargado de generar el modal para nuevo servicio
     * 
     */

    public function modalServicioNuevo(array $datos)
    {
        $data = array();
        $usuario = $usuario = $this->Usuario->getDatosUsuario();
        $departamento = $this->DBST->consultaGeneral(
            'SELECT 
                    IdDepartamento 
                    FROM cat_v3_servicios_departamento
                    WHERE Id = (SELECT IdTipoServicio FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '")'
        );
        if ($departamento[0]['IdDepartamento'] === '0') {
            $data['tipoServicio'] = $this->Catalogo->catServiciosDepartamento('3', array('departamento' => $usuario['IdDepartamento']));
            $data['atiende'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => $usuario['IdDepartamento']));
        } else {
            $data['tipoServicio'] = $this->Catalogo->catServiciosDepartamento('3', array('departamento' => $departamento[0]['IdDepartamento']));
            $data['atiende'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => $departamento[0]['IdDepartamento']));
        }
        return array('formulario' => parent::getCI()->load->view('Generales/Modal/formularioServicioNuevo', $data, TRUE), 'datos' => $data);
    }

    /*
     * Encargado de armar el arreglo para crear un nuevo servicio
     * 
     */

    public function servicioNuevo(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        if ($datos['IdTipoServicio'] === '27') {
            $datosTicket = $this->crearTicketSDProactivo($datos);
            if ($datosTicket !== FALSE) {
                $datos['IdSolicitud'] = $datosTicket['idSolicitud'];
                $datos['Ticket'] = $datosTicket['ticket'];
            }
        }

        $data = array(
            'Ticket' => $datos['Ticket'],
            'IdSolicitud' => $datos['IdSolicitud'],
            'IdTipoServicio' => $datos['IdTipoServicio'],
            'IdEstatus' => '1',
            'Solicita' => $usuario['Id'],
            'Atiende' => $datos['Atiende'],
            'FechaCreacion' => $fecha,
            'Descripcion' => $datos['Descripcion'],
            'IdServicioOrigen' => $datos['servicio']
        );

        $consulta = $this->setServicio($data, $datos['servicio']);

        if (!empty($consulta)) {
            return $this->getServiciosAsignados($usuario['IdDepartamento']);
        } else {
            return FALSE;
        }
    }

    public function crearTicketSDProactivo(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();

        try {
            $this->DBS->iniciaTransaccion();
            $datosSolicitudAnterior = $this->DBS->getDatosSolicitud($datos['IdSolicitud']);

            $solicitudNueva = 'insert t_solicitudes set 
                Ticket = ' . $datosSolicitudAnterior['Ticket'] . ',
                IdTipoSolicitud = "4",
                IdEstatus = ' . $datosSolicitudAnterior['IdEstatus'] . ',
                IdDepartamento = ' . $datosSolicitudAnterior['IdDepartamento'] . ',
                IdPrioridad = ' . $datosSolicitudAnterior['IdPrioridad'] . ',
                FechaCreacion = now(),
                Solicita = ' . $usuario['Id'] . ', 
                IdServicioOrigen = "' . $datos['servicio'] . '", 
                IdSucursal = "' . $datosSolicitudAnterior['IdSucursal'] . '",
                FechaTentativa = "' . $datosSolicitudAnterior['FechaTentativa'] . '",
                FechaLimite = "' . $datosSolicitudAnterior['FechaLimite'] . '"';
            $idSolicitud = $this->DBS->setSolicitud($solicitudNueva);

            if ($idSolicitud !== FALSE) {
                $datosSolicitud = $this->DBS->getDatosSolicitud($idSolicitud);
                $informacionFolio = $this->ServiceDesk->getDetallesFolio($usuario['SDKey'], $datosSolicitudAnterior['Folio']);
                $informacionSDAnterior = json_decode(json_encode($informacionFolio), True);
                $informacionSD = '"subject": "Correctivo Proactivo",
                                    "description": "' . $datos['Descripcion'] . '",
                                    "status": "En Atención",
                                    "requester": "SOPORTE SICCOB",
                                    "Nombre del Gerente": "' . $informacionSDAnterior["Nombre del Gerente"] . '",
                                    "item": "' . $informacionSDAnterior["ITEM"] . '",
                                    "technician": "' . $informacionSDAnterior["TECHNICIAN"] . '",
                                    "mode": "' . $informacionSDAnterior["MODE"] . '",
                                    "priority": "' . $informacionSDAnterior["PRIORITY"] . '",
                                    "group": "' . $informacionSDAnterior["GROUP"] . '",
                                    "level": "' . $informacionSDAnterior["LEVEL"] . '",
                                    "category": "' . $informacionSDAnterior["CATEGORY"] . '",
                                    "subcategory": "' . $informacionSDAnterior["SUBCATEGORY"] . '"';
                $datosSD = $this->ServiceDesk->getTicketServiceDesk($usuario['SDKey'], $informacionSD);
                $folio = $datosSD->operation->Details->WORKORDERID;
                $ticket = $this->Ticket->setTicket(array('Folio' => $folio), array('descripcion' => $datos['Descripcion'], 'cliente' => $datosSolicitud['IdCliente']));

                $this->DBS->cambiarEstatusSolicitud(array(
                    'Folio' => $folio,
                    'Ticket' => $ticket
                ), array('Id' => $idSolicitud));
                $this->DBS->setDatosSolicitudInternas('t_solicitudes_internas', array('IdSolicitud' => $idSolicitud, 'Descripcion' => $datos['Descripcion'], 'Asunto' => $datos['Descripcion']));
            }

            $this->DBS->commitTransaccion();
            return array('idSolicitud' => $idSolicitud, 'ticket' => $ticket);
        } catch (\Exception $ex) {
            $this->DBS->roolbackTransaccion();
            return FALSE;
        }
    }

    /*
     * Devuelve el arreglo de los servicios generados a partir de una solicitud
     * 
     */

    public function getServiciosBySolicitud(string $solicitud, bool $mostrarConcluidos = false)
    {
        $conluidos = ($mostrarConcluidos) ? '' : 'and tst.IdEstatus != 4';
        $consulta = $this->DBST->getServicios(''
            . 'SELECT '
            . 'tst.Id,tst.Ticket, '
            . 'tipoServicio(tst.IdTipoServicio) as Servicio, '
            . 'tst.FechaCreacion, '
            . 'tst.Descripcion, '
            . 'tst.IdEstatus, '
            . 'estatus(tst.IdEstatus)as NombreEstatus, '
            . 'nombreUsuario(tst.Atiende) as Atiende, '
            . 'nombreUsuario(ts.Solicita) as Solicita '
            . 'from t_solicitudes ts inner join t_servicios_ticket tst '
            . 'on ts.Ticket = tst.Ticket '
            . 'where ts.Id = "' . $solicitud . '"'
            . $conluidos);

        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    /* Encargado de generar el modal para cancelar servicio
     * 
     */

    public function modalServicioCancelar(array $datos)
    {
        $data = array();
        return array('formulario' => parent::getCI()->load->view('Generales/Modal/formularioServicioCancelar', $data, TRUE), 'datos' => $data);
    }

    /*
     * Encargado de cancelar el servicio y 
     * 
     */

    public function servicioCancelar(array $datos)
    {
        try {
            $usuario = $this->Usuario->getDatosUsuario();
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $data = array(
                'IdEstatus' => '6'
            );
            $verificarEstatusServicio = $this->DBST->consultaGeneral('SELECT 
                                                                tst.IdEstatus
                                                            FROM t_servicios_ticket tst
                                                            WHERE tst.Id = "' . $datos['servicio'] . '"
                                                            AND IdEstatus IN(4,5,6,10)');

            if (empty($verificarEstatusServicio)) {
                $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', $data, array('Id' => $datos['servicio']));
                if (!empty($consulta)) {
                    $data = array(
                        'IdUsuario' => $usuario['Id'],
                        'IdEstatus' => '6',
                        'IdServicio' => $datos['servicio'],
                        'Nota' => $datos['Descripcion'],
                        'Fecha' => $fecha
                    );
                    $informacionServicio = $this->DBST->consultaServicio($datos['servicio']);
                    $verificarEstatusTicket = $this->DBST->consultaGeneral('SELECT 
                                                                IdEstatus,
                                                                IdSolicitud
                                                            FROM t_servicios_ticket tst
                                                            WHERE IdSolicitud = ' . $informacionServicio[0]['IdSolicitud'] . '
                                                            AND IdEstatus IN(10,5,2,1)');
                    if (!$verificarEstatusTicket) {
                        $serviciosConcluidos = FALSE;
                        $serviciosConcluidosCancelados = $this->DBST->consultaGeneral('SELECT 
                                                                IdEstatus,
                                                                IdSolicitud
                                                            FROM t_servicios_ticket tst
                                                            WHERE IdSolicitud = ' . $informacionServicio[0]['IdSolicitud'] . '
                                                            AND IdEstatus IN(4,6)');
                        foreach ($serviciosConcluidosCancelados as $key => $value) {
                            if ($value['IdEstatus'] === '4') {
                                $serviciosConcluidos = TRUE;
                            }
                        }

                        if ($serviciosConcluidos) {
                            $estatusSolicitud = '4';
                        } else {
                            $estatusSolicitud = '6';
                        }

                        $this->DBST->actualizarServicio('t_solicitudes', array(
                            'IdEstatus' => $estatusSolicitud,
                            'FechaConclusion' => $fecha
                        ), array('Id' => $serviciosConcluidosCancelados[0]['IdSolicitud']));
                        $this->DBST->concluirTicketAdist2(array(
                            'Estatus' => 'CONCLUIDO',
                            'Flag' => '1',
                            'F_Cierre' => '0',
                            'Id_Orden' => $datos['ticket']
                        ));
                    }
                    $notas = $this->DBST->setNuevoElemento('t_notas_servicio', $data);
                    if (!empty($notas)) {
                        $serviciosAsignados = $this->getServiciosAsignados($usuario['IdDepartamento']);
                        return $serviciosAsignados;
                    } else {
                        throw new \Exception('Vuelva a interntarlo.');
                    }
                } else {
                    throw new \Exception('Vuelva a interntarlo.');
                }
            } else {
                throw new \Exception('No puede cancelar este servicio por el estatus que se encuentra.');
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function modalServicioSinEspecificar(array $datosServicio, string $servicio, string $fecha = null, string $departamento = null, $idSolcitud = null)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();

        if ($datosServicio['IdEstatus'] === '1') {
            $data['informacion']['serviciosAsignados'] = $this->cambiarEstatusServicioTicket($servicio, $fecha, '2', $departamento);
            $this->setStatusSD($datosServicio['Folio']);
        }
        $data['informacionServicioGeneral'] = $this->DBST->consultaGeneral('SELECT * FROM t_servicios_generales WHERE IdServicio ="' . $servicio . '"');

        if (!empty($data['informacionServicioGeneral'])) {
            $data['archivo'] = explode(',', $data['informacionServicioGeneral'][0]['Archivos']);
        } else {
            $data['archivo'] = null;
        }

        $data['servicio'] = $servicio;
        $data['notas'] = $this->Notas->getNotasByServicio($servicio, $idSolcitud);

        $data['historialAvancesProblemas'] = $this->mostrarHistorialAvancesProblemas($servicio);
        $data['datosServicio'] = $datosServicio;
        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($datosServicio['Ticket']);
        $data['idSucursal'] = $this->DBST->consultaGeneral('SELECT 
                                                                    tst.IdSucursal,
                                                                IF(tst.IdSucursal IS NULL,
                                                                    (SELECT 
                                                                            IdSucursal
                                                                        FROM
                                                                            t_solicitudes
                                                                        WHERE
                                                                            Id = tst.IdSolicitud), tst.IdSucursal) AS IdSucursal
                                                            FROM
                                                                t_servicios_ticket tst
                                                            WHERE
                                                                Id = "' . $servicio . '"');
        $data['folio'] = $this->DBST->consultaGeneral('SELECT Folio FROM t_solicitudes WHERE Ticket = "' . $data['datosServicio']['Ticket'] . '"');
        $data['idPerfil'] = $usuario['IdPerfil'];

        if ($usuario['IdPerfil'] === '83' || $usuario['IdDepartamento'] === '19') {
            $data['botonAgregarVuelta'] = '<li id="btnAgregarVuelta"><a href="#"><i class="fa fa-plus"></i> Agregar Vuelta</a></li>';
        } else {
            $data['botonAgregarVuelta'] = '';
        }

        $data['formulario'] = parent::getCI()->load->view('Generales/Modal/formularioSeguimientoServicioSinClasificar', $data, TRUE);
        return $data;
    }

    public function mostrarHistorialAvancesProblemas(string $servicio)
    {
        $data = array();
        $data['avanceServicio'] = $this->consultaAvanceServicio($servicio);
        return parent::getCI()->load->view('Generales/Detalles/HistorialAvancesProblemas', $data, TRUE);
    }

    public function cambiarEstatusServicioTicket(string $servicio, string $fecha, string $estatus, string $departamento = null)
    {
        $data = array();

        if ($estatus === '4' || $estatus === '5' || $estatus === '10') {
            $campoFecha = 'FechaConclusion';
        } elseif ($estatus === '1' || $estatus === '2') {
            $campoFecha = 'FechaInicio';
        }

        $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => $estatus,
            $campoFecha => $fecha
        ), array('Id' => $servicio));
        if (!empty($consulta)) {
            if ($departamento !== null) {
                return $data['serviciosAsignados'] = $this->getServiciosAsignados($departamento);
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function actualizarServicioGeneral(array $datos, array $usuario, string $fecha)
    {
        $consulta = $this->DBST->consultaGeneral('SELECT Id FROM t_servicios_generales WHERE IdServicio =' . $datos['servicio']);

        if (!empty($datos['sucursal'])) {
            $this->DBST->actualizarServicio('t_servicios_ticket', array('IdSucursal' => $datos['sucursal']), array('Id' => $datos['servicio']));
        }

        $datosServicio = array(
            'IdUsuario' => $usuario['Id'],
            'IdServicio' => $datos['servicio'],
            'Descripcion' => $datos['descripcion'],
            'Fecha' => $fecha
        );
        if (!empty($_FILES)) {
            $CI = parent::getCI();
            $carpeta = 'Servicios/Servicio-' . $datos['servicio'] . '/EvidenciasServicioGeneral/';
            $archivos = setMultiplesArchivos($CI, 'evidenciasSinClasificar', $carpeta);
            $archivos = implode(',', $archivos);
            if (!empty($datos['previews'])) {
                $archivos = $datos['previews'] . "," . $archivos;
            }
            if (!empty($archivos) && $archivos != '') {
                $resultado = '';
                if (!empty($consulta)) {
                    $resultado = $this->DBST->actualizarServicio(
                        't_servicios_generales',
                        array(
                            'IdUsuario' => $usuario['Id'],
                            'IdServicio' => $datos['servicio'],
                            'Descripcion' => $datos['descripcion'],
                            'Archivos' => $archivos,
                            'Fecha' => $fecha
                        ),
                        array('IdServicio' => $datos['servicio'])
                    );
                } else {
                    $resultado = $this->DBST->setNuevoElemento(
                        't_servicios_generales',
                        array(
                            'IdUsuario' => $usuario['Id'],
                            'IdServicio' => $datos['servicio'],
                            'Descripcion' => $datos['descripcion'],
                            'Archivos' => $archivos,
                            'Fecha' => $fecha
                        )
                    );
                }
                if (!empty($resultado)) {
                    return $resultado;
                } else {
                    return ['result' => false];
                }
            }
            $consulta = '';
        } else {
            if (!empty($consulta)) {
                $resultado = $this->DBST->actualizarServicio('t_servicios_generales', $datosServicio, array('IdServicio' => $datos['servicio']));
                if (!empty($resultado)) {
                    return $resultado;
                } else {
                    return FALSE;
                }
            } else {
                $resultado = $this->DBST->setNuevoElemento('t_servicios_generales', $datosServicio);
                if (!empty($consulta)) {
                    return $resultado;
                } else {
                    return FALSE;
                }
            }
        }
    }

    /* Inicia los servicios */

    public function iniciaServicio(string $servicio)
    {
        $consulta = $this->DBST->actualizarServicio('t_servicios_ticket', array(
            'IdEstatus' => '2',
            'FechaInicio' => 'now()'
        ), array('Id' => $servicio));
        if (!empty($consulta)) {
            return $consulta;
        } else {
            return FALSE;
        }
    }

    public function verificarServicio(array $datos)
    {
        try {
            $this->DBST->iniciaTransaccion();
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '4');
            $serviciosTicket = $this->DBST->consultaGeneral('SELECT Id FROM t_servicios_ticket WHERE Ticket = "' . $datos['ticket'] . '" AND IdEstatus in(10,5,2,1)');
            $contador = 0;
            $linkPDF = '';

            $datosDescripcionConclusion = $this->DBST->consultaGeneral('SELECT
                                            tst.Descripcion AS DescripcionServicio,
                                            tst.IdSolicitud,
                                            tsi.Asunto AS AsuntoSolicitud,
                                            tsi.Descripcion AS DescripcionSolicitud,
                                            (SELECT Folio FROM t_solicitudes WHERE Id = tst.IdSolicitud) Folio
                                           FROM t_servicios_ticket tst
                                           INNER JOIN t_solicitudes_internas tsi
                                           ON tsi.IdSolicitud = tst.IdSolicitud
                                           WHERE tst.Id = "' . $datos['servicio'] . '"');

            //$linkPdf = $this->getServicioToPdf(array('servicio' => $datos['servicio']));
            $linkPdf = $pdf = $this->InformacionServicios->definirPDF(array('servicio' => $datos['servicio']));
            $host = $_SERVER['SERVER_NAME'];

            if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                $path = 'http://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '.pdf';
            } else {
                $path = 'http://' . $host . '/' . $linkPdf;
            }

            if (empty($serviciosTicket)) {
                $this->concluirSolicitud($fecha, $datos['idSolicitud']);
                $this->concluirTicket($datos['ticket']);

                $serviciosConcluidos = $this->DBST->consultaGeneral('SELECT 
                                                                        tse.Id, 
                                                                        tse.Ticket,
                                                                        nombreUsuario(tso.Atiende) Atiende,
                                                                        (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = tso.Atiende) CorreoAtiende,
                                                                        tso.Solicita
                                                                FROM t_servicios_ticket tse
                                                                INNER JOIN t_solicitudes tso
                                                                ON tse.IdSolicitud = tso.Id
                                                                WHERE tse.Ticket = "' . $datos['ticket'] . '"');

                foreach ($serviciosConcluidos as $key => $value) {
                    $contador++;
                    $linkPdfServiciosConcluidos = $this->InformacionServicios->definirPDF(array('servicio' => $datos['servicio']));
                    $infoServicioServiciosConcluidos = $this->getInformacionServicio($value['Id']);
                    $tipoServicioServiciosConcluidos = stripAccents($infoServicioServiciosConcluidos[0]['NTipoServicio']);

                    if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
                        $path = 'http://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $value['Id'] . '/Pdf/Ticket_' . $value['Ticket'] . '_Servicio_' . $value['Id'] . '_' . $tipoServicioServiciosConcluidos . '.pdf';
                        $linkDetallesSolicitud = 'http://siccob.solutions/Detalles/Solicitud/' . $datosDescripcionConclusion[0]['IdSolicitud'];
                    } else {
                        $path = 'http://' . $host . '/' . $linkPdfServiciosConcluidos;
                        $linkDetallesSolicitud = 'http://' . $host . '/Detalles/Solicitud/' . $datosDescripcionConclusion[0]['IdSolicitud'];
                    }

                    $linkPDF .= '<br>Ver Servicio PDF-' . $contador . ' <a href="' . $path . '" target="_blank">Aquí</a>';
                }

                $titulo = 'Solicitud Concluida';
                $linkSolicitud = 'Ver detalles de la Solicitud <a href="' . $linkDetallesSolicitud . '" target="_blank">Aquí</a>';
                $textoCorreo = '<p>Estimado(a) <strong>' . $value['Atiende'] . ',</strong> se ha concluido la Solicitud.</p><br>Ticket: <strong>' . $value['Ticket'] . '</strong><br> Número Solicitud: <strong>' . $datosDescripcionConclusion[0]['IdSolicitud'] . '</strong><br><br>' . $linkSolicitud . '<br>' . $linkPDF;

                $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $textoCorreo);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($value['CorreoAtiende']), $titulo, $mensajeFirma);
            }

            foreach ($datosDescripcionConclusion as $value) {
                if (isset($value['Folio'])) {
                    if ($value['Folio'] !== NULL) {
                        if ($value['Folio'] !== '0') {
                            $this->agregarVueltaAsociadoMantenimiento(array(
                                'servicio' => $datos['servicio'],
                                'folio' => $value['Folio']
                            ));

                            $this->InformacionServicios->verifyProcess($datos);
                        }
                    }
                }
            }

            $this->DBST->commitTransaccion();
            return ['code' => 200, 'message' => 'correcto', 'link' => $linkPDF];
        } catch (\Exception $ex) {
            $this->DBST->roolbackTransaccion();
            return array('code' => 400, 'message' => $ex->getMessage(), 'link' => $linkPDF);
        }
    }

    public function agregarVueltaAsociadoMantenimiento(array $datos)
    {
        $arrayDatosServicio = $this->DBST->getDatosServicio($datos['servicio']);
        if ($arrayDatosServicio['IdTipoServicio'] === '12') {
            $arrayDatosAtiende = $this->DBST->getDatosAtiende($arrayDatosServicio['Atiende']);
            if ($arrayDatosAtiende['IdPerfil'] === '83') {
                $vueltasAnterioresFolio = $this->DBT->vueltasFacturasOutsourcing($datos['folio']);
                if (empty($vueltasAnterioresFolio)) {
                    $this->guardarVueltaAsociadoMantenimiento(array(
                        'servicio' => $datos['servicio'],
                        'folio' => $datos['folio'],
                        'atiende' => $arrayDatosServicio['Atiende'],
                        'nombreAtiende' => $arrayDatosServicio['NombreAtiende'],
                        'nombreFirma' => $arrayDatosServicio['NombreFirma'],
                        'firma' => $arrayDatosServicio['Firma'],
                        'ticket' => $arrayDatosServicio['Ticket'],
                        'idSucursal' => $arrayDatosServicio['IdSucursal']
                    ));
                }
            }
        }
    }

    public function guardarVueltaAsociadoMantenimiento(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $fechaVuelta = mdate('%Y-%m-%d_%H-%i-%s', now('America/Mexico_City'));

        $idFacturacionOutSourcing = $this->DBT->guardarVueltaOutsourcing(array(
            'IdServicio' => $datos['servicio'],
            'Vuelta' => '1',
            'Folio' => $datos['folio'],
            'Fecha' => $fecha,
            'IdUsuario' => $datos['atiende'],
            'Gerente' => $datos['nombreFirma'],
            'FirmaGerente' => $datos['firma'],
            'IdEstatus' => '8',
            'FechaEstatus' => $fecha
        ));

        $pdf = $this->pfdAsociadoVueltaServicioMantenimiento($datos);

        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'http://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Asociados/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $fechaVuelta . '.pdf';
        } else {
            $path = 'http://' . $host . $pdf;
        }

        $consulta = $this->DBST->actualizarServicio(
            't_facturacion_outsourcing',
            array(
                'Archivo' => $path,
            ),
            array('Id' => $idFacturacionOutSourcing)
        );

        if ($consulta) {
            $key = $this->InformacionServicios->getApiKeyByUser($datos['atiende']);
            $informacionSD = $this->ServiceDesk->getDetallesFolio($key, $datos['folio']);

            if (isset($informacionSD->SHORTDESCRIPTION)) {
                $detallesSD = $informacionSD->SHORTDESCRIPTION;
            } else {
                $detallesSD = '';
            }

            $titulo = 'Documentación de Vuelta';
            $linkPDF = '<br>Ver PDF Resumen Vuelta <a href="' . $path . '" target="_blank">Aquí</a>';

            $descripcionVuelta = '<br><br>Folio: <strong>' . $datos['folio'] . '</strong>
                <br>Descripción de Service Desk: <strong>' . $detallesSD . '</strong>';

            $correoAtiende = $this->DBT->consultaCorreoUsuario($datos['atiende']);
            $textoUsuario = '<p>Estimado(a) <strong>' . $datos['nombreAtiende'] . ',</strong> se le ha mandado el documento de la vuelta que realizo.</p>' . $linkPDF . $descripcionVuelta;
            $this->enviarCorreoConcluido(array($correoAtiende), $titulo, $textoUsuario);

            $correoSupervisorZona = $this->DBST->consultaGeneral('SELECT 
                                                        (SELECT IdResponsableInterno FROM cat_v3_regiones_cliente WHERE Id = IdRegionCliente)SupervisorZona,
                                                        (SELECT EmailCorporativo FROM cat_v3_usuarios WHERE Id = SupervisorZona)CorreoSupervisorZona
                                                    FROM cat_v3_sucursales
                                                    WHERE Id = "' . $datos['idSucursal'] . '"');

            $textoSupervisorZona = '<p><strong>Supervisor,</strong> se le ha mandado el documento de la vuelta que realizo el técnico <strong>' . $usuario['Nombre'] . '</strong>.</p>' . $linkPDF . $descripcionVuelta;
            $this->enviarCorreoConcluido(array($correoSupervisorZona[0]['CorreoSupervisorZona']), $titulo, $textoSupervisorZona);
        }
    }

    public function pfdAsociadoVueltaServicioMantenimiento(array $datos)
    {
        $fechaVuelta = mdate('%Y-%m-%d_%H-%i-%s', now('America/Mexico_City'));
        $datosServicio = $this->DBST->getDatosServicio($datos['servicio']);
        $totalAreaPuntos = $this->DBST->totalAreaPuntos($datos);
        $totalLineas = $this->DBST->totalLineasCenso($datos);
        $pline1 = 9;
        $pline2 = 201;

        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');

        $this->pdf->SetXY(0, 8);
        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Cell(0, 0, "Sucursal:" . $datosServicio['Sucursal'] . "-Folio:" . $datos['folio'], 0, 0, 'R');

        $this->pdf->SetXY(0, 27);
        $this->pdf->SetFont("helvetica", "", 11);
        $this->pdf->Cell(0, 0, "Resumen de Vuelta", 0, 0, 'R');

        $this->pdf->Ln('10');
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(0, 0, utf8_decode("Información General del Folio"), 0, 0, 'L');
        $y = $this->pdf->GetY() + 4;
        $this->pdf->Line($pline1, $y, $pline2, $y);

        $this->pdf->SetXY(8, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Folio");

        $this->pdf->SetXY(8, 54);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datos['folio']);

        $this->pdf->SetXY(65, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Ticket");

        $this->pdf->SetXY(65, 54);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datos['ticket']);

        $this->pdf->SetXY(110, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Vuelta");

        $this->pdf->SetXY(110, 54);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, '1');

        $this->pdf->SetXY(158, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Fecha de Vuelta");

        $this->pdf->SetXY(158, 54);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $fecha);

        $this->pdf->SetXY(8, 62);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Tipo Servicio");

        $this->pdf->SetXY(8, 68);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datosServicio['TipoServicio']);

        $this->pdf->SetXY(65, 62);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Sucursal");

        $this->pdf->SetXY(65, 68);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datosServicio['Sucursal']);

        $this->pdf->SetXY(110, 62);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "No. Servicio");

        $this->pdf->SetXY(110, 68);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datos['servicio']);

        $this->pdf->SetXY(158, 62);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Atiende");

        $this->pdf->SetXY(158, 68);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, $datosServicio['NombreAtiende']);

        $this->pdf->Image('.' . $datosServicio['Firma'], 80, 80, 50, 0, 'PNG');

        $this->pdf->SetXY(90, 105);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, "Firma Gerente");

        $this->pdf->SetXY(8, 120);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, utf8_decode("Descripción"));

        $this->pdf->SetXY(8, 126);
        $this->pdf->SetFont("helvetica", "", 10);
        $this->pdf->Cell(180, 0, utf8_decode($datosServicio['DescripcionServicio']));

        $this->pdf->AddPage();
        $this->pdf->Image('./assets/img/siccob-logo.png', 10, 8, 20, 0, 'PNG');

        $this->pdf->SetXY(0, 8);
        $this->pdf->SetFont("helvetica", "", 9);
        $this->pdf->Cell(0, 0, "Sucursal:" . $datosServicio['Sucursal'] . "-Folio:" . $datos['folio'], 0, 0, 'R');

        $this->pdf->Ln('30');
        $this->pdf->SetFont("helvetica", "B", 11);
        $this->pdf->Cell(0, 0, utf8_decode("Resumen del Servicio"), 0, 0, 'L');
        $y = $this->pdf->GetY() + 4;
        $this->pdf->Line($pline1, $y, $pline2, $y);

        $this->pdf->SetXY(8, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, utf8_decode("Total de Puntos"));

        $this->pdf->SetFillColor(226, 231, 235);
        $this->pdf->Ln('3');

        $headers = ['Área', 'Puntos'];
        $widths = [45, 45];

        $this->pdf->SetX('9');
        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $push_right = 0;

        $this->pdf->SetFont('helvetica', 'B', 10);
        foreach ($headers as $key => $value) {
            $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
            $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
            $push_right += $w;
            $this->pdf->SetXY($x + $push_right, $y);
        }

        $this->pdf->Ln();
        $fill = false;

        $this->pdf->SetFont('helvetica', '', 8);
        $height = 6;
        $sumaPuntos = array_sum(array_column($totalAreaPuntos, 'Puntos'));
        array_push($totalAreaPuntos, array('Area' => 'TOTAL', 'Puntos' => $sumaPuntos));

        foreach ($totalAreaPuntos as $key => $value) {
            $this->pdf->SetX('9');
            $this->pdf->MultiCell(45, $height, $value['Area'], 1, 'L', $fill);
            $this->pdf->SetXY(54, $this->pdf->GetY() - 6);
            $this->pdf->MultiCell(45, $height, $value['Puntos'], 1, 'C', $fill);
            $fill = !$fill;
        }

        $this->pdf->SetXY(110, 48);
        $this->pdf->SetFont("helvetica", "B", 10);
        $this->pdf->Cell(15, 0, utf8_decode("Total de Equipos"));

        $this->pdf->SetFillColor(226, 231, 235);

        $headers = ['Linea', 'Total'];
        $widths = [45, 45];

        $this->pdf->SetXY('111', '51');
        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();
        $push_right = 0;

        $this->pdf->SetFont('helvetica', 'B', 10);
        foreach ($headers as $key => $value) {
            $w = (isset($widths[$key])) ? $widths[$key] : $widthStandar;
            $this->pdf->MultiCell($w, 6, utf8_decode($value), 1, 'C', true);
            $push_right += $w;
            $this->pdf->SetXY($x + $push_right, $y);
        }

        $this->pdf->Ln();
        $fill = false;

        $this->pdf->SetFont('helvetica', '', 8);

        foreach ($totalLineas as $key => $value) {
            $this->pdf->SetX('111');
            $this->pdf->MultiCell(45, $height, $value['Linea'], 1, 'L', $fill);
            $this->pdf->SetXY(156, $this->pdf->GetY() - 6);
            $this->pdf->MultiCell(45, $height, $value['Total'], 1, 'C', $fill);
            $fill = !$fill;
        }

        $carpeta = $this->pdf->definirArchivo('Servicios/Servicio-' . $datos['servicio'] . '/Pdf/Asociados', 'Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $fechaVuelta);
        $this->pdf->Output('F', $carpeta, true);
        $carpeta = substr($carpeta, 1);
        return $carpeta;
    }

    public function enviarCorreoConcluido(array $correo, string $titulo, string $texto)
    {
        $mensaje = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $correo, $titulo, $mensaje);
    }

    public function concluirSolicitud(string $fecha, string $idSolicitud)
    {
        $consulta = $this->DBST->actualizarServicio('t_solicitudes', array(
            'IdEstatus' => '4',
            'FechaConclusion' => $fecha
        ), array('Id' => $idSolicitud));

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function concluirTicket(string $ticket)
    {
        $consulta = $this->DBST->concluirTicketAdist2(array(
            'Estatus' => 'CONCLUIDO',
            'Flag' => '1',
            'F_Cierre' => '0',
            'Id_Orden' => $ticket
        ));

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function reabrirSolicitud(string $idSolicitud)
    {
        $consulta = $this->DBST->actualizarServicio('t_solicitudes', array(
            'IdEstatus' => '2',
            'FechaConclusion' => NULL
        ), array('Id' => $idSolicitud));

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function reabrirTicket(string $ticket)
    {
        $consulta = $this->DBST->concluirTicketAdist2(array(
            'Estatus' => 'EN PROCESO DE ATENCION',
            'Flag' => '0',
            'F_Cierre' => '0',
            'Id_Orden' => $ticket
        ));

        if (!empty($consulta)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function reabrirServicio(array $datos)
    {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $autorizacion = FALSE;
        $autorizacionServicio = FALSE;

        if (in_array('177', $usuario['PermisosAdicionales']) || in_array('177', $usuario['Permisos'])) {
            $autorizacion = TRUE;
        }

        if (in_array('182', $usuario['PermisosAdicionales']) || in_array('182', $usuario['Permisos'])) {
            $areaUsuario = $this->DBST->getDatosAtiende($usuario['Id']);
            $atiende = $this->DBST->consultaGeneral('SELECT Atiende FROM t_servicios_ticket WHERE Id  = "' . $datos['servicio'] . '"');
            $areaAtiende = $this->DBST->getDatosAtiende($atiende[0]['Atiende']);
            if ($areaUsuario['IdArea'] === $areaAtiende['IdArea']) {
                $autorizacionServicio = TRUE;
            }
        }

        if ($autorizacion || $autorizacionServicio) {
            $idAtiende = $this->DBST->consultaGeneral('SELECT Atiende FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
            $atiende = $this->DBST->getDatosAtiende($idAtiende[0]["Atiende"]);
            $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
            $descripcion = 'Se ha Reabrio el Servicio del siguiente Ticket: ' . $datos['ticket'];

            $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '2');
            $this->copiarArchivoFirma($datos['servicio'], $fecha);

            $data = array(
                'IdUsuario' => $usuario['Id'],
                'IdEstatus' => '2',
                'IdServicio' => $datos['servicio'],
                'Nota' => $datos['descripcion'],
                'Fecha' => $fecha
            );

            $estatusSolicitud = $this->DBST->consultaGeneral('SELECT IdEstatus FROM t_solicitudes WHERE Id = "' . $datos['idSolicitud'] . '"');

            if ($estatusSolicitud[0]['IdEstatus'] === '4') {
                $this->reabrirSolicitud($datos['idSolicitud']);
                $this->reabrirTicket($datos['ticket']);
            }

            $this->DBST->actualizarServicio('t_servicios_ticket', array(
                'Firma' => NULL,
                'NombreFirma' => NULL,
                'CorreoCopiaFirma' => NULL,
                'FechaFirma' => NULL
            ), array('Id' => $datos['servicio']));

            $notas = $this->DBST->setNuevoElemento('t_notas_servicio', $data);

            $data['departamento'] = $atiende['IdDepartamento'];
            $data['remitente'] = $usuario['Id'];
            $data['tipo'] = '18';
            $data['descripcion'] = $descripcion;

            $this->Notificacion->setNuevaNotificacion($data, 'Se reabrio el Servicio', $descripcion, $atiende);

            if (!empty($notas)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function rechazarServicio(array $datos)
    {
        $data = array();
        $usuario = $this->Usuario->getDatosUsuario();
        $atiende = $this->DBST->getDatosAtiende($datos['atiende']);
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));

        $this->cambiarEstatusServicioTicket($datos['servicio'], $fecha, '10', '0');
        $this->copiarArchivoFirma($datos['servicio'], $fecha);

        $data = array(
            'IdUsuario' => $usuario['Id'],
            'IdEstatus' => '10',
            'IdServicio' => $datos['servicio'],
            'Nota' => $datos['descripcion'],
            'Fecha' => $fecha
        );

        $this->DBST->actualizarServicio('t_servicios_ticket', array(
            'Firma' => NULL,
            'NombreFirma' => NULL,
            'CorreoCopiaFirma' => NULL,
            'FechaFirma' => NULL
        ), array('Id' => $datos['servicio']));

        $descripcion = 'Se ha Rechazado Servicio del siguiente Ticket: ' . $datos['ticket'];
        $notas = $this->DBST->setNuevoElemento('t_notas_servicio', $data);
        $data['departamento'] = $atiende['IdDepartamento'];
        $data['remitente'] = $usuario['Id'];
        $data['tipo'] = '18';
        $data['descripcion'] = $descripcion;

        $this->Notificacion->setNuevaNotificacion($data, 'Se rechazo el Servicio', $descripcion, $atiende);

        if (!empty($notas)) {
            return $this->getServiciosBySolicitud($datos['idSolicitud']);
        } else {
            return FALSE;
        }
    }

    private function copiarArchivoFirma(string $servicio, string $fecha)
    {
        $urlFirmaServicio = $this->DBST->consultaGeneral('SELECT Firma FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
        $fechaNueva = str_replace(" ", "_", $fecha);

        if ($urlFirmaServicio[0]['Firma'] !== NULL) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows')) {
                $nuevaUrlFirmaServicioLinux = str_replace("/", "\\", $urlFirmaServicio[0]['Firma']);
            } else {
                $nuevaUrlFirmaServicioLinux = $urlFirmaServicio[0]['Firma'];
            }
            $nuevaUrlFirmaServicio = str_replace(".png", "_" . $fechaNueva, $nuevaUrlFirmaServicioLinux);
            $nuevaUrlFirmaServicio = str_replace(":", "-", $nuevaUrlFirmaServicio);

            copy(getcwd() . $nuevaUrlFirmaServicioLinux, getcwd() . $nuevaUrlFirmaServicio . ".png");
        }
    }

    private function getServicioMantenimiento(string $servicio, string $ticket)
    {
        $data = array();
        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($ticket);
        $data['informacionDatosGeneralesMantenimiento'] = $this->DBST->consultaGeneral('SELECT * FROM t_mantenimientos_generales WHERE IdServicio = "' . $servicio . '"');
        if (!empty($data['informacionDatosGeneralesMantenimiento'])) {
            $sucursal = $data['informacionDatosGeneralesMantenimiento'][0]['IdSucursal'];
            $data['puntosCensos'] = $this->SeguimientoPoliza->consultaPuntosCensadosMantenimiento($sucursal, $servicio);
            $data['areaYPunto'] = $this->SeguimientoPoliza->consultaAreaPuntoXSucursal($sucursal, 'Area, Punto');
            $data['areaAtencion'] = $this->SeguimientoPoliza->consultaAreaPuntoXSucursal($sucursal, 'Area');
            $data['idSucursal'] = $sucursal;
            $data['documentacionFirmada'] = $this->consultaDocumentacioFirmadaServicio($servicio);
        }
        $data['modelos'] = $this->Catalogo->catModelosEquipo('3', array('Flag' => '1'));
        $data['problemasAdicionales'] = $this->SeguimientoPoliza->consultaProblemasAdicionales($servicio);
        return $data;
    }

    private function getServicioMantenimientoSalas(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $permisoActividades = FALSE;

        if (in_array('220', $usuario['PermisosAdicionales'])) {
            $permisoActividades = TRUE;
        } else if (in_array('220', $usuario['Permisos'])) {
            $permisoActividades = TRUE;
        }

        $data = array();
        $data['consultaInfoMantenimiento'] = $this->Catalogo->catX4DActividadesMantenimiento('3', array('Flag' => '1'));
        $data['consultaSistemasMantenimiento'] = $this->Catalogo->catX4DTiposSistemas('3', array('Flag' => '1'));
        $data['usuario'] = $this->Usuario->getDatosUsuario();
        $data['actividaesAutorizadas'] = $this->DBST->consultaGeneral('SELECT * FROM t_actividades_autorizadas_salas4d');
        $data['usuariosDepto'] = $this->DBST->consultaGeneral('select 
                                                                   Id,
                                           nombreUsuario(Id) as Nombre
                                           from cat_v3_usuarios 
                                           where IdPerfil in (select Id from cat_perfiles where IdDepartamento = 7)');
        $data['sucursales'] = $this->consultaSucursalesXSalas4D();
        $data['sucursalesXSolicitudCliente'] = $this->consultaSucursalesXSolicitudCliente($datos['ticket']);
        $sucursal = $this->DBST->consultaGeneral('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        $data['sucursal'] = $sucursal[0]['IdSucursal'];
        $data['permisoActividades'] = $permisoActividades;

        if (in_array('223', $usuario['PermisosAdicionales'])) {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
        } else if (in_array('223', $usuario['Permisos'])) {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
        } else {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
        }

        return $data;
    }

    private function getServicioCorrectivo(string $servicio, string $ticket)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $data = array();
        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($ticket);
        $data['informacionDatosGeneralesCorrectivo'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_generales WHERE IdServicio = "' . $servicio . '"');
        $data['listaPaqueteria'] = $this->Catalogo->catTiposPaqueteria('3');
        $data['listaConsolidados'] = $this->Catalogo->catTiposConsolidados('3');
        $data['listaUsuarios'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'));
        $data['atiende'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'), array('IdDepartamento' => '16'));
        $data['listaCinemexValidadores'] = $this->Catalogo->catCinemexValidadores('3', array(), array('Flag' => '1'));
        $data['atiendeLaboratorio'] = $this->DBST->consultaGeneral('SELECT 
                                                                            a.Id as IdUsuario,
                                                                            nombreUsuario(a.Id) AS Nombre
                                                                        FROM cat_v3_usuarios a 
                                                                        INNER JOIN cat_perfiles b ON b.Id = a.IdPerfil 
                                                                        WHERE b.IdDepartamento in ("11","10","16")
                                                                        AND a.Flag = 1');
        if (!empty($data['informacionDatosGeneralesCorrectivo'])) {
            $sucursal = $this->DBST->consultaGeneral('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $servicio . '"');
            $data['sucursal'] = $sucursal[0]['IdSucursal'];
            $data['idCliente'] = $this->DBST->consultaGeneral('SELECT IdCliente FROM cat_v3_sucursales WHERE Id = "' . $sucursal[0]['IdSucursal'] . '"');
            $data['diagnosticoEquipo'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_diagnostico WHERE Id = (SELECT MAX(Id) FROM t_correctivos_diagnostico WHERE IdServicio = "' . $servicio . '")');
            if (!empty($data['diagnosticoEquipo'])) {
                $data['evidenciasDiagnosticoEquipo'] = explode(',', $data['diagnosticoEquipo'][0]['Evidencias']);
            }
            $data['tiposFallasEquipos'] = $this->SeguimientoPoliza->consultaTiposFallasEquipos(array('equipo' => $data['informacionDatosGeneralesCorrectivo'][0]['IdModelo']));
            $data['tiposFallasEquiposImpericia'] = $this->SeguimientoPoliza->consultaTiposFallasEquiposImpericia(array('equipo' => $data['informacionDatosGeneralesCorrectivo'][0]['IdModelo']));
            $data['catalogoComponentesEquipos'] = $this->SeguimientoPoliza->consultaRefacionXEquipo(array('equipo' => $data['informacionDatosGeneralesCorrectivo'][0]['IdModelo']));
            $data['catalogoFallasEquipo'] = $this->Catalogo->catFallasEquipo('3', array('Flag' => '1'));
            $data['solicitudesRefaccionServicios'] = $this->SeguimientoPoliza->consultaCorrectivosSolicitudRefaccion($servicio);
            $data['solicitudesEquiposServicios'] = $this->DBST->consultaGeneral('select 
                                                                                        tcse.IdServicio as Servicio,
                                                                                        nombreUsuario(tst.Solicita) as Solicitante,
                                                                                        tst.FechaCreacion,
                                                                                        estatus(tst.IdEstatus) as Estatus,
                                                                                        group_concat(ve.Equipo," _ ",tcse.Cantidad) as EquipoCantidad,
                                                                                    	group_concat(tcse.Id) as Id
                                                                                        from t_correctivos_solicitudes_equipo tcse inner join t_servicios_ticket tst
                                                                                        on tcse.IdServicio = tst.Id
                                                                                    inner join v_equipos ve
                                                                                        on tcse.IdModelo = ve.Id
                                                                                    where tcse.IdServicioOrigen = "' . $servicio . '" 
                                                                                    group by Servicio');
            $data['EquiposXLinea'] = $this->SeguimientoPoliza->consultaEquiposXLinea(array('equipo' => $data['informacionDatosGeneralesCorrectivo'][0]['IdModelo']));
            $data['idTipoProblema'] = $this->DBST->consultaGeneral('SELECT Id, IdTipoProblema FROM t_correctivos_problemas WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');

            if (!empty($data['idTipoProblema'])) {
                if ($data['idTipoProblema'][0]['IdTipoProblema'] === '3') {
                    $data['correctivoGarantiaRespaldo'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_garantia_respaldo WHERE IdServicio = "' . $servicio . '" ORDER BY Id DESC LIMIT 1');
                    if ($data['correctivoGarantiaRespaldo'][0]['EsRespaldo'] === '0' && $data['correctivoGarantiaRespaldo'][0]['SolicitaEquipo'] === '1') {
                        $data['solicitudEquipoRespaldo'] = $this->DBST->consultaGeneral('SELECT 
                                                                                            nombreUsuario(tst.Atiende) Atiende,
                                                                                            tst.FechaCreacion
                                                                                        FROM t_servicios_relaciones tsr
                                                                                        INNER JOIN t_servicios_ticket tst
                                                                                            ON tsr.IdServicioNuevo = tst.Id
                                                                                        WHERE tsr.IdServicioOrigen = "' . $servicio . '" 
                                                                                        AND tst.IdTipoServicio = 21
                                                                                        ORDER BY tsr.Id DESC LIMIT 1');
                    } else {
                        $data['solicitudEquipoRespaldo'] = NULL;
                    }
                } else {
                    $data['correctivoGarantiaRespaldo'] = NULL;
                    $data['solicitudEquipoRespaldo'] = NULL;
                }

                $data['envioEntrega'] = $this->DBST->consultaGeneral('SELECT Id, Tipo FROM (
                                                            SELECT Id, IdProblemaCorrectivo, FechaCapturaEnvio AS Fecha, "Envio" AS Tipo FROM t_correctivos_envios_equipo WHERE IdProblemaCorrectivo = "' . $data['idTipoProblema'][0]['Id'] . '"
                                                            UNION
                                                            SELECT Id, IdProblemaCorrectivo, Fecha, "Entrega" AS Tipo FROM t_correctivos_entregas_equipo WHERE IdProblemaCorrectivo = "' . $data['idTipoProblema'][0]['Id'] . '") AS TablaEnvioEntrega 
                                                            ORDER BY Fecha DESC LIMIT 1');

                if (!empty($data['envioEntrega'])) {
                    if ($data['envioEntrega'][0]['Tipo'] === 'Envio') {
                        $data['envioEquipo'] = $this->DBB->consultaCorrectivoEnviosEquipoProblemas($servicio);
                        $data['entregaEquipo'] = NULL;
                        if (!empty($data['envioEquipo'])) {
                            $data['evidenciasEnvioEquipo'] = explode(',', $data['envioEquipo'][0]['EvidenciasEnvio']);
                            $data['evidenciasEntregaEquipo'] = explode(',', $data['envioEquipo'][0]['EvidenciasEntrega']);
                        } else {
                            $data['evidenciasEnvioEquipo'] = NULL;
                            $data['evidenciasEntregaEquipo'] = NULL;
                        }
                    } else {
                        $entregaEquipo = $this->DBB->consultaCorrectivoEntregasEquipo($servicio);

                        if (empty($entregaEquipo)) {
                            $data['entregaEquipo'] = NULL;
                        } else {
                            $data['entregaEquipo'] = $entregaEquipo;
                        }
                        $data['envioEquipo'] = NULL;
                    }
                } else {
                    $data['envioEntrega'] = NULL;
                    $data['envioEquipo'] = NULL;
                }
            } else {
                $data['correctivoGarantiaRespaldo'] = NULL;
                $data['solicitudEquipoRespaldo'] = NULL;
                $data['envioEntrega'] = NULL;
                $data['envioEquipo'] = NULL;
            }
            $data['catalogoSolucionesEquipo'] = $this->SeguimientoPoliza->consultaCatalogoSolucionesEquipoXEquipo(array('equipo' => $data['informacionDatosGeneralesCorrectivo'][0]['IdModelo']));
            $data['correctivosSoluciones'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_soluciones WHERE Id = (SELECT MAX(Id) FROM t_correctivos_soluciones WHERE IdServicio = "' . $servicio . '")');
            if (!empty($data['correctivosSoluciones'])) {
                $data['evidenciasCorrectivosSoluciones'] = explode(',', $data['correctivosSoluciones'][0]['Evidencias']);
                switch ($data['correctivosSoluciones'][0]['IdTipoSolucion']) {
                    case '1':
                        $data['correctivosSinEquipo'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_solucion_sin_equipo WHERE IdSolucionCorrectivo = "' . $data['correctivosSoluciones'][0]['Id'] . '"');
                        break;
                    case '2':
                        $data['correctivosSolucionRefaccion'] = $this->DBST->consultaGeneral('SELECT 
                                                                                                    tcsr.*,
                                                                                                    (SELECT Nombre From cat_v3_componentes_equipo WHERE Id = tcsr.IdRefaccion) NombreRefaccion 
                                                                                                FROM t_correctivos_solucion_refaccion tcsr
                                                                                                WHERE tcsr.IdSolucionCorrectivo = "' . $data['correctivosSoluciones'][0]['Id'] . '"');
                        break;
                    case '3':
                        $data['correctivosSolucionCambio'] = $this->DBST->consultaGeneral('SELECT * FROM t_correctivos_solucion_cambio WHERE IdSolucionCorrectivo = "' . $data['correctivosSoluciones'][0]['Id'] . '"');
                        break;
                }
            } else {
                $data['evidenciasCorrectivosSoluciones'] = NULL;
            }
        } else {
            $data['diagnosticoEquipo'] = NULL;
            $data['catalogoComponentesEquipos'] = NULL;
            $data['tiposFallasEquipos'] = NULL;
            $data['idTipoProblema'] = NULL;
            $data['correctivoGarantiaRespaldo'] = NULL;
            $data['envioEntrega'] = NULL;
            $data['envioEquipo'] = NULL;
            $data['correctivosSoluciones'] = NULL;
            $data['evidenciasCorrectivosSoluciones'] = NULL;
            $data['idCliente'] = NULL;
        }

        if ($usuario['IdPerfil'] === '83' || $usuario['IdDepartamento'] === '19' || $usuario['IdDepartamento'] === '11') {
            $data['botonAgregarVuelta'] = '<li id="btnAgregarVuelta"><a href="#"><i class="fa fa-plus"></i> Agregar Vuelta</a></li>';
        } else {
            $data['botonAgregarVuelta'] = '';
        }

        if (in_array('291', $usuario['PermisosAdicionales'])) {
            $data['campoObservaciones'] = '';
        } else if (in_array('291', $usuario['Permisos'])) {
            $data['campoObservaciones'] = '';
        } else {
            $data['campoObservaciones'] = 'hidden';
        }
        return $data;
    }

    public function concluirServicioSolicitudTicket()
    {
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $cuerentayochoMenos = mdate("%Y-%m-%d %H:%i:%s", strtotime("-48 hour"));
            $serviciosTicket = $this->DBST->consultaGeneral('SELECT * FROM t_servicios_ticket WHERE FechaConclusion <= "' . $cuerentayochoMenos . '" AND IdEstatus = 5');
            foreach ($serviciosTicket as $value) {
                $censoMantenimiento = $this->DBST->consultaGeneral('SELECT IdTipoServicio FROM t_servicios_ticket WHERE Id = "' . $value['Id'] . '"');
                if ($censoMantenimiento[0]['IdTipoServicio'] !== '11') {
                    if ($censoMantenimiento[0]['IdTipoServicio'] !== '12') {
                        $serviciosSalas4d = $this->DBST->consultaGeneral('SELECT 
                                                                                tst.IdTipoServicio
                                                                            FROM
                                                                                t_servicios_ticket AS tst
                                                                            INNER JOIN cat_v3_servicios_departamento AS cvsd
                                                                             ON cvsd.Id = tst.IdTipoServicio
                                                                            WHERE cvsd.IdDepartamento = "7"
                                                                            AND tst.Id = "' . $value['Id'] . '"');
                        if (empty($serviciosSalas4d)) {
                            $this->verificarServicio(array('servicio' => $value['Id'], 'ticket' => $value['Ticket'], 'idSolicitud' => $value['IdSolicitud']));
                        }
                    }
                }
            }
        }
    }

    public function consultaAvanceServicio(string $servicio)
    {
        $data = $this->DBST->servicioAvanceProblema($servicio);

        if (!empty($data)) {
            foreach ($data as $key => $item) {
                $tablaEquipos = $this->DBST->serviciosAvanceEquipo($item['Id']);
                array_push($data[$key], array('tablaEquipos' => $tablaEquipos));
            }
        }

        return $data;
    }

    public function getServicioToPdf(array $servicio, string $nombreExtra = NULL)
    {
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $nombreExtra = (is_null($nombreExtra)) ? '' : $nombreExtra;
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . $nombreExtra . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'] . $nombreExtra;
        $datosServicio = $this->DBST->consultaGeneral('SELECT
                                                sucursal(IdSucursal) Sucursal,
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio
                                            FROM t_servicios_ticket
                                            WHERE Id = "' . $servicio['servicio'] . '"');
        $link = $this->Phantom->htmlToPdf($archivo, $ruta, $datosServicio[0]);
        return ['link' => $link];
    }

    public function getInformacionServicio(string $servicio)
    {
        $sentencia = ""
            . "select ts.Id as Solicitud, "
            . "nombreUsuario(ts.Solicita) as Solicitante, "
            . "ts.FechaCreacion as FechaSolicitud, "
            . "estatus(ts.IdEstatus) as EstatusSolicitud, "
            . "(select Descripcion from t_solicitudes_internas tsi where tsi.IdSolicitud = ts.Id) as DescripcionSolicitud, "
            . "tst.Ticket, "
            . "tipoServicio(tst.IdTipoServicio) as TipoServicio, "
            . "replace(tipoServicio(tst.IdTipoServicio),' ','') as NTipoServicio, "
            . "tst.FechaCreacion as FechaServicio, "
            . "estatus(tst.IdEstatus) as EstatusServicio, "
            . "tst.Descripcion as DescripcionServicio, "
            . "case "
            . " when ts.IdEstatus in (4,'4') then "
            . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, ts.FechaConclusion))*60) "
            . " when ts.IdEstatus in (6,'6') then "
            . "     '' "
            . " else "
            . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , ts.FechaCreacion, now()))*60) "
            . "end as TiempoSolicitud, "
            . ""
            . "case "
            . " when tst.IdEstatus  in (4,'4') then "
            . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, tst.FechaConclusion))*60) "
            . " when tst.IdEstatus  in (6,'6') then "
            . "     '' "
            . " else "
            . "     SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , tst.FechaCreacion, now()))*60) "
            . "end as TiempoServicio "
            . "from t_servicios_ticket tst INNER JOIN t_solicitudes ts "
            . "on tst.IdSolicitud = ts.Id "
            . "where tst.Id = '" . $servicio . "';";
        return $this->DBST->consultaGeneral($sentencia);
    }

    public function guardarDocumentacionFirma(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $fecha = mdate('%Y-%m-%d %H:%i:%s', now('America/Mexico_City'));
        $img = $datos['img'];
        $img = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $img));
        $data = base64_decode($img);
        $direccionFirma = '/storage/Archivos/imagenesFirmas/DocumentacionFirma/' . str_replace(' ', '_', 'Firma_' . $datos['ticket'] . '_' . $datos['servicio']) . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $direccionFirma, $data);
        $fechaNueva = str_replace(' ', '_', $fecha);
        $fechaNueva = str_replace(':', '-', $fechaNueva);
        $linkPdf = $this->getDocumentoFirmadoPDF(array('servicio' => $datos['servicio']), $fechaNueva);
        $infoServicio = $this->getInformacionServicio($datos['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $host = $_SERVER['SERVER_NAME'];

        if ($host === 'siccob.solutions' || $host === 'www.siccob.solutions') {
            $path = 'http://siccob.solutions/storage/Archivos/Servicios/Servicio-' . $datos['servicio'] . '/Pdf/DocumentacionFirmada/Ticket_' . $datos['ticket'] . '_Servicio_' . $datos['servicio'] . '_' . $tipoServicio . '_' . $fechaNueva . '.pdf';
        } else {
            $path = 'http://' . $host . '/' . $linkPdf['link'];
        }

        $correo = $datos['correo'];

        if (is_array($correo)) {
            $correo = implode(",", $correo);
        }

        $consulta = $this->DBST->setNuevoElemento('t_servicios_documentacion_firmada', array(
            'IdServicio' => $datos['servicio'],
            'IdEstatus' => $datos['datosConcluir']['estatus'],
            'IdUsuario' => $usuario['Id'],
            'Fecha' => $fecha,
            'Recibe' => $datos['recibe'],
            'Correos' => $correo,
            'Firma' => $direccionFirma,
            'UrlArchivo' => $linkPdf['link']
        ));
        $PDF = '<br>Ver PDF <a href="' . $path . '" target="_blank">Aquí</a>';
        $descripcion = 'Descripción: <strong>Se le ha mandado un documento del avance del día de hoy.</strong><br>';
        $titulo = 'Documento Firmado - Avance';
        $texto = '<p>Estimado(a) <strong>' . $datos['recibe'] . '</strong>, se le ha mandado el reporte firmado.</p>' . $descripcion . $PDF;

        $mensajeFirma = $this->Correo->mensajeCorreo($titulo, $texto);
        $this->Correo->enviarCorreo('notificaciones@siccob.solutions', $datos['correo'], $titulo, $mensajeFirma);

        if ($consulta) {
            $departamento = $this->DBST->getServicios('SELECT 
                                                (SELECT Folio FROM t_solicitudes WHERE Id = IdSolicitud) Folio,
                                                (SELECT IdDepartamento FROM cat_v3_servicios_departamento WHERE Id = tst.IdTipoServicio) IdDepartamento
                                            FROM 
                                            t_servicios_ticket tst
                                            WHERE tst.Id = "' . $datos['servicio'] . '"');
            if ($departamento[0]['IdDepartamento'] === '11') {
                $correoSupervisor = $this->SeguimientoPoliza->consultaCorreoSupervisorXSucursal($datos['datosConcluir']['sucursal']);
                $textoUsuario = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento del avance del servicio que realizo.</p>' . $PDF;
                $mensajeAtiende = $this->Correo->mensajeCorreo($titulo, $textoUsuario);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($usuario['EmailCorporativo']), $titulo, $mensajeAtiende);

                $textoSupervisor = '<p><strong>Estimado(a) ' . $usuario['Nombre'] . ',</strong> se le ha mandado el documento del servicio que realizo el técnico ' . $usuario['Nombre'] . '.</p>' . $PDF;
                $mensajeSupervisor = $this->Correo->mensajeCorreo($titulo, $textoSupervisor);
                $this->Correo->enviarCorreo('notificaciones@siccob.solutions', array($correoSupervisor[0]['CorreoSupervisor']), $titulo, $mensajeSupervisor);

                $correoCordinadorPoliza = $this->DBST->getServicios('SELECT EmailCorporativo FROM cat_v3_usuarios WHERE IdPerfil = 46');
                $textoCoordinadorPoliza = '<p><strong>Cordinador de Poliza,</strong> se le ha mandado el documento de la conclusión del servicio que realizo el personal ' . $usuario['Nombre'] . '.</p>' . $PDF;
                foreach ($correoCordinadorPoliza as $key => $value) {
                    $this->enviarCorreoConcluido(array($value['EmailCorporativo']), $titulo, $textoCoordinadorPoliza);
                }
            }

            $descripcion = "<div>" . $fecha . "</div><div>AVANCE DE SERVICIO</div><div><a href='" . $path . "'>Documento PDF</a></div>";
            $key = $this->InformacionServicios->getApiKeyByUser($usuario['Id']);
            $this->InformacionServicios->setNoteAndWorkLog(array('key' => $key, 'folio' => $departamento[0]['Folio'], 'html' => $descripcion));

            return $this->consultaDocumentacioFirmadaServicio($datos['servicio']);
        } else {
            return FALSE;
        }
    }

    public function getDocumentoFirmadoPDF(array $servicio, string $fecha)
    {
        $infoServicio = $this->getInformacionServicio($servicio['servicio']);
        $tipoServicio = stripAccents($infoServicio[0]['NTipoServicio']);
        $archivo = 'storage/Archivos/Servicios/Servicio-' . $servicio['servicio'] . '/Pdf/DocumentacionFirmada/Ticket_' . $infoServicio[0]['Ticket'] . '_Servicio_' . $servicio['servicio'] . '_' . $tipoServicio . '_DocumentacionFirmada_' . $fecha . '.pdf ';
        $ruta = 'http://' . $_SERVER['HTTP_HOST'] . '/Phantom/Servicio/' . $servicio['servicio'];
        $link = $this->Phantom->htmlToPdf($archivo, $ruta);
        return ['link' => $link];
    }

    public function consultaDocumentacioFirmadaServicio(string $servicio, string $limite = null)
    {
        ($limite !== null) ? $limitarServicio = 'ORDER BY Id DESC LIMIT 1' : $limitarServicio = '';
        $consulta = $this->DBST->consultaGeneral('SELECT 
                                                        Fecha,
                                                        Recibe,
                                                        Correos,
                                                        (SELECT Nombre FROM cat_v3_estatus WHERE Id = tsdf.IdEstatus) Estatus,
                                                        UrlArchivo,
                                                        Firma
                                                    FROM t_servicios_documentacion_firmada tsdf
                                                    WHERE tsdf.IdServicio =  "' . $servicio . '"'
            . $limitarServicio);

        return $consulta;
    }

    public function consultaSucursalesXSolicitudCliente(string $ticket)
    {
        $sucursal = $this->DBTO->getServicioTicket($ticket);
        $return = $this->DBST->consultaGeneral('SELECT 
                                                Id,
                                                sucursalCliente(Id) Nombre,
                                                cliente(IdCliente) Cliente
                                            FROM cat_v3_sucursales
                                            WHERE Flag = 1
                                            ORDER BY Nombre ASC');
        //                                            AND IdCliente = "' . $sucursal[0]['Cliente'] . '"
        return $return;
    }

    public function consultaSucursalesXSalas4D()
    {
        return $this->DBST->consultaGeneral('SELECT 
                                                Id,
                                                sucursalCliente(Id) Nombre  
                                            FROM cat_v3_sucursales
                                            WHERE Flag = 1
                                            AND Salas4D = 1');
    }

    private function getServicioChecklist(array $datos)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $permisoActividades = FALSE;

        if (in_array('220', $usuario['PermisosAdicionales'])) {
            $permisoActividades = TRUE;
        } else if (in_array('220', $usuario['Permisos'])) {
            $permisoActividades = TRUE;
        }

        $data = array();
        $data['consultaInfoMantenimiento'] = $this->Catalogo->catX4DActividadesMantenimiento('3', array('Flag' => '1'));
        $data['consultaSistemasMantenimiento'] = $this->Catalogo->catX4DTiposSistemas('3', array('Flag' => '1'));
        $data['usuario'] = $this->Usuario->getDatosUsuario();
        $data['actividaesAutorizadas'] = $this->DBST->consultaGeneral('SELECT * FROM t_actividades_autorizadas_salas4d');
        $data['usuariosDepto'] = $this->DBST->consultaGeneral('select 
                                                                   Id,
                                           nombreUsuario(Id) as Nombre
                                           from cat_v3_usuarios 
                                           where IdPerfil in (select Id from cat_perfiles where IdDepartamento = 7)');
        $data['sucursales'] = $this->consultaSucursalesXSolicitudCliente($datos['ticket']);
        $sucursal = $this->DBST->consultaGeneral('SELECT IdSucursal FROM t_servicios_ticket WHERE Id = "' . $datos['servicio'] . '"');
        $data['sucursalesXSolicitudCliente'] = $this->consultaSucursalesXSolicitudCliente($datos['ticket']);
        $data['sucursal'] = $sucursal[0]['IdSucursal'];
        $data['permisoActividades'] = $permisoActividades;

        if (in_array('223', $usuario['PermisosAdicionales'])) {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
        } else if (in_array('223', $usuario['Permisos'])) {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4($datos['servicio']);
        } else {
            $data['actividades'] = $this->DBCS->getActividadesSeguimientoActividadesSalas4Usuario($datos['servicio'], $usuario['Id']);
        }

        return $data;
    }

    public function setStatusSD(string $folio = NULL)
    {
        $usuario = $this->Usuario->getDatosUsuario();
        $key = $this->InformacionServicios->getApiKeyByUser($usuario['Id']);

        if (!empty($folio)) {
            $datosSD = $this->ServiceDesk->getDetallesFolio($key, $folio);

            if (!isset($datosSD->operation->result->status)) {
                if ($datosSD->STATUS === 'Abierto') {
                    $this->ServiceDesk->cambiarEstatusServiceDesk($key, 'En Atención', $folio);
                }
            }
        }
    }

    public function eliminarAvanceProblema(array $datos)
    {
        try {
            $this->DBST->iniciaTransaccion();
            $this->DBST->flagearServicioAvance($datos);
            $arrayServiciosAvanceEquipo = $this->DBST->serviciosAvanceEquipo($datos['idAvanceProblema']);

            if ($arrayServiciosAvanceEquipo) {
                $this->DBST->flagearServicioAvanceEquipo($datos);
            }

            $historialAvanceProblema = $this->mostrarHistorialAvancesProblemas($datos['idServicio']);
            $this->DBST->commitTransaccion();

            return ['code' => 200, 'message' => $historialAvanceProblema];
        } catch (\Exception $ex) {
            $this->DBST->roolbackTransaccion();

            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }

    public function consultaAvanceProblema(array $datos)
    {
        $data = array();
        try {
            $this->DBST->iniciaTransaccion();
            $data['avanceProblema'] = $this->DBST->consultaAvanceProblema($datos['id']);
            $data['serviciosAvanceEquipo'] = $this->DBST->serviciosAvanceEquipo($datos['id']);

            if (!empty($data['avanceProblema'][0])) {
                $data['archivo'] = explode(',', $data['avanceProblema'][0]['Archivos']);
            } else {
                $data['archivo'] = null;
            }

            $this->DBST->commitTransaccion();

            return ['code' => 200, 'message' => $data];
        } catch (\Exception $ex) {
            $this->DBST->roolbackTransaccion();

            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }

    public function eliminarEvidenciaAvanceProblema(array $datos)
    {
        try {
            $informaionAvanceProblema = $this->DBST->consultaAvanceProblema($datos['id']);
            $archivos = explode(',', $informaionAvanceProblema[0]['Archivos']);

            foreach ($archivos as $key => $value) {
                if ($datos['key'] === $value) {
                    unset($archivos[$key]);
                }
            }

            if (eliminarArchivo($datos['key'])) {
                $this->DBST->actualizarAvanceProblema(array(
                    'campos' => array('Archivos' => implode(',', $archivos)),
                    'where' => array('Id' => $datos['id'])
                ));
                return ['code' => 200, 'message' => 'correcto'];
            }
        } catch (\Exception $ex) {
            $this->DBST->roolbackTransaccion();

            return ['code' => 400, 'message' => $ex->getMessage()];
        }
    }
}

class PDFAux extends PDF
{

    function Footer()
    {
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
