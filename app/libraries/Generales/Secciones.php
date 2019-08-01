<?php

namespace Librerias\Generales;

use Controladores\Controller_Datos_Usuario as General;

class Secciones extends General
{

    private $Catalogo;
    private $Notificacion;
    private $Solicitud;
    private $Personal;
    private $Minuta;
    private $Archivos;
    private $DB_Adist2;
    private $DBP;
    private $DBC;
    private $DBPO;
    private $Proyecto;
    private $Servicios;
    private $Videos;
    private $Rutas;
    private $Seguimiento;
    private $DashboardGeneral;
    private $SAE;
    private $Tesoreria;
    private $ModeloSalas4D;
    private $ModeloEditarSolicitudesServicios;
    private $CatalogoTesoreria;
    private $Proyectos2;
    private $Gapsi;
    private $PEV2;
    private $Documentacion;
    private $ModeloComprobacion;
    private $FondoFijo;
    private $ModeloTesoreria;
    private $Compras;
    private $ubicaphone;
    private $ModeloDashboard;
    private $permisosVacaciones;
    private $autorizarpermisos;
    private $GapsiProyecto;
    private $fondoFijo;
    private $instalaciones;
    private $prime;
    private $seccionCE;
    
    private $gestorProyectos;

    public function __construct()
    {
        parent::__construct();
        parent::getCI()->config->load('Menu_config');
        parent::getCI()->config->load('Pagina_config');

        $this->Personal = \Librerias\Generales\Usuario::factory();
        $this->Catalogo = \Librerias\Generales\Catalogo::factory();
        $this->Notificacion = \Librerias\Generales\Notificacion::factory();
        $this->Calendario = \Librerias\Generales\Calendario::factory();
        $this->Solicitud = \Librerias\Generales\Solicitud::factory();
        $this->Minuta = \Librerias\Generales\Minuta::factory();
        $this->Proyecto = \Librerias\Proyectos\Proyecto::factory();
        $this->Servicios = \Librerias\Generales\ServiciosTicket::factory();
        $this->Archivos = \Librerias\Generales\Archivos::factory();
        $this->Videos = \Librerias\Capacitacion\Videos::factory();
        $this->Rutas = \Librerias\Logistica\Rutas::factory();
        $this->Seguimiento = \Librerias\Logistica\Seguimiento::factory();
        $this->Poliza = \Librerias\Poliza\Poliza::factory();
        $this->Seguimientos = \Librerias\Poliza\Seguimientos::factory();
        //        $this->DB_Adist2 = \Modelos\Modelo_DB_Adist2::factory(); Este objeto se estara utilizando para proyectos
        $this->DBP = \Modelos\Modelo_Proyectos::factory();
        $this->DBC = \Modelos\Modelo_Catalogo_Proyectos::factory();
        $this->DashboardGeneral = \Librerias\Generales\Dashboard::factory();
        $this->SAE = \Librerias\SAEReports\Reportes::factory();
        $this->Tesoreria = \Librerias\Tesoreria\Tesoreria::factory();
        $this->ModeloSalas4D = \Modelos\Modelo_Salas4D::factory();
        $this->ModeloEditarSolicitudesServicios = \Modelos\Modelo_EditarSolicitud::factory();
        $this->CatalogoTesoreria = \Librerias\Tesoreria\Catalogos::factory();
        $this->Proyectos2 = \Librerias\Proyectos2\Catalogos::factory();
        $this->Gapsi = \Librerias\Gapsi\Catalogos::factory();
        $this->PEV2 = \Librerias\Reportes\PEV2::factory();
        $this->DBP2 = \Modelos\Modelo_Proyectos2::factory();
        $this->Documentacion = \Librerias\Documentacion\Documentacion::factory();
        $this->ModeloComprobacion = \Modelos\Modelo_Comprobacion::factory();
        $this->FondoFijo = \Librerias\Tesoreria\FondoFijo::factory();
        $this->ModeloTesoreria = \Modelos\Modelo_Tesoreria::factory();
        $this->Compras = \Librerias\Compras\Compras::factory();
        $this->PerfilUsuario = \Librerias\RH\Perfil_Usuario::factory();
        //        $this->ubicaphone = \Librerias\WebServices\Ubicaphone::factory();
        $this->ModeloDashboard = \Modelos\Modelo_Dashboard::factory();
        $this->permisosVacaciones = \Librerias\RH\Permisos_Vacaciones::factory();
        $this->autorizarpermisos = \Librerias\RH\Autorizar_permisos::factory();
        $this->GapsiProyecto = \Librerias\Gapsi\GerstorProyectosGAPSI::factory();
        $this->fondoFijo = \Librerias\FondoFijo\FondoFijo::factory();
        $this->instalaciones = \Librerias\Instalaciones\Instalaciones::factory();
        $this->prime = \Librerias\Prime\Inventario::factory();
        $this->seccionCE = \Librerias\V2\PaquetesTicket\GestorServicios::factory();
    }

    /*
     * Regresa las opciones del menu que tendra disponible el usuario 
     * a partir de su perfil y permisos
     * 
     * @return array regresa la lista de menu y modulos para el usuario
     */

    public function getSecciones(array $usuario)
    {
        $menu = array();
        $permisos = array();
        $catalogo = null;
        $areaActiva = $this->Catalogo->catAreas('3', null, array('Id' => $usuario['IdArea']));
        foreach ($usuario['Permisos'] as $key => $value) {
            $catalogo = $this->Catalogo->catPermisos('3', null, array('Id' => $value));
            if (!empty($catalogo)) {
                array_push($permisos, $catalogo[0]['Permiso']);
            }
        }

        $usuario['Permisos'] = array_replace($usuario['Permisos'], $permisos);
        $modulos = parent::getCI()->config->item('Modulos');
        foreach ($modulos as $modulo => $dato) {
            $temporal = array();
            foreach (parent::getCI()->config->item($modulo) as $seccion => $datos) {
                foreach ($datos as $key => $item) {
                    if ($key === 'Permiso') {
                        if (in_array($item, $usuario['Permisos'])) {
                            if ($areaActiva[0]['Flag'] === '1') {
                                array_push($temporal, array($seccion => $datos));
                            } else if ($seccion === 'Perfil-Configuracion') {
                                array_push($temporal, array($seccion => $datos));
                            }
                        }
                    }
                }
            }
            if (!empty($temporal)) {
                array_push($menu, array($modulo => $temporal));
            }
        }
        return array('Menu' => $menu, 'Modulos' => $modulos, 'Area' => $areaActiva[0]['Flag']);
    }

    /*
     * Se encarga de obtener la notificaciones del usuario.
     */

    public function getNotificaciones(string $usuario)
    {
        return $this->Notificacion->getNotificacionesMenuCabecera($usuario);
    }

    /*
     * Regresa los datos (selects,datatables,etc) que se requieren para la pagina.
     * 
     * @param string $pagina recibe la pagina que se va a cargar.
     * @return array regresa los datos para la pagina
     *  
     */

    public function getDatosPagina(string $url)
    {
        $datos = array();
        $usuario = $this->Usuario->getDatosUsuario();
        switch ($url) {
            case 'RH/Areas':
                $datos['ListaAreas'] = $this->Catalogo->CatAreas("3");
                break;
            case 'Generales/Notificaciones':
                $datos['Notificaciones'] = $this->Notificacion->getNotificaciones();
                break;
            case 'Generales/Calendario':
                $datos['Areas'] = $this->Calendario->getCalendarAreasPermissions();
                break;
            case 'Generales/Solicitud_Nueva':
                $datos['CatalogoAreas'] = $this->Catalogo->catAreas('3', array('Flag' => '1'));
                $datos['CatalogoPrioridades'] = $this->Catalogo->catPrioridades('3');
                $datos['CatalogoUsuarios'] = $this->Catalogo->catUsuarios('3', array('Flag' => '1'));
                $datos['CatalogoClientesActivos'] = $this->Solicitud->clientesActivos();
                break;
            case 'Generales/Solicitud_Generadas':
                $datos['solicitudesGeneradas'] = $this->Solicitud->getSolicitudesGeneradas();
                break;
            case 'Generales/Solicitud_Asignada':
                $datos['solicitudesAsignadas'] = $this->Solicitud->getSolicitudesAsignadas();
                break;
            case 'Generales/Solicitud_Autorizacion':
                $datos['solicitudesAutorizacion'] = $this->Solicitud->getSolicitudesAurtorizacion();
                break;
            case 'Generales/Solicitud_Editar':
                $datos['departamentos'] = $this->ModeloEditarSolicitudesServicios->getDepartamentos();
                break;
            case 'Generales/Buscar':
                $datos['usuario'] = $this->Usuario->getDatosUsuario();
                break;
            case 'Proyectos2/Catalogos':
                $datos['Sistemas'] = $this->Proyectos2->getSistemas();
                $datos['Tipos'] = $this->Proyectos2->getTipos();
                $datos['Conceptos'] = $this->Proyectos2->getConceptos();
                $datos['Areas'] = $this->Proyectos2->getAreas();
                $datos['Ubicaciones'] = $this->Proyectos2->getUbicaciones();
                $datos['Accesorios'] = $this->Proyectos2->getAccesorios();
                $datos['Material'] = $this->Proyectos2->getMaterial();
                $datos['Kits'] = $this->Proyectos2->getKits();
                break;
            case 'Proyectos2/Planeacion':
                $datos['ProyectosSinAtender'] = $this->DBP->getProyectosSinAtender();
                break;
            case 'Proyectos2/Almacen':
                $datos['ProyectosAlmacenSAE'] = $this->DBP2->getProyectosAlmacenSAE();
                break;
            case 'Proyectos2/Tareas':
                $datos['Tareas'] = $this->DBP2->getTareasProyectos();
                $datos['TodasTareas'] = $this->DBP2->tienePermisoTodasTareas();
                break;
            case 'Proyectos/Nuevo':
                //                $datos['Clientes'] = $this->DBP->getClientes();
                $datos['Sistemas'] = $this->DBP->getSistemas();
                $datos['Tipo'] = $this->DBP->getTiposProyecto();
                $datos['Complejos'] = $this->DBP->getSucursales();
                $datos['Asistentes'] = $this->DBP->getAsistentesProyecto();
                $datos['Lideres'] = $this->Catalogo->catLideres('3');
                $datos['ProyectosSinAtender'] = $this->DBP->getProyectosSinAtender();
                break;
            case 'Proyectos/NuevoAdminProyect':
                $datos['Sistemas'] = $this->DBP->getSistemas();
                $datos['Tipo'] = $this->DBP->getTiposProyecto();
                $datos['Complejos'] = $this->DBP->getSucursales();
                $datos['Asistentes'] = $this->DBP->getAsistentesProyecto();
                $datos['Lideres'] = $this->Catalogo->catLideres('3');
                $datos['ProyectosSinAtender'] = $this->DBP->getProyectosSinAtender();
                break;
            case 'Proyectos/Seguimiento':
                $datos['ProyectosIniciados'] = $this->DBP->getProyectosIniciados();
                break;
            case 'Proyectos/TareasTecnico':
                $datos['TareasTecnico'] = $this->DBP->getTareasTecnico($usuario['Id']);
                break;
            case 'Proyectos/Catalogo':
                $datos['TiposProyectos'] = $this->DBC->getTiposProyecto();
                $datos['Tareas'] = $this->DBC->getTareas();
                break;
            case 'RH/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('3');
                break;
            case 'RH/Perfiles':
                $datos['Autorizacion'] = FALSE;
                if (in_array('35', $usuario['PermisosAdicionales'])) {
                    $datos['Autorizacion'] = TRUE;
                } elseif (in_array('35', $usuario['Permisos'])) {
                    $datos['Autorizacion'] = TRUE;
                }
                $datos['Permisos'] = $this->Catalogo->catPermisos("3");
                $datos['SelectAreas'] = $this->Catalogo->catAreas("3", array('Flag' => '1'));
                $datos['ListaPerfiles'] = $this->Catalogo->catPerfiles("3");
                break;
            case 'RH/Permisos_vacaciones':
                $datos['departamento'] = $this->permisosVacaciones->buscarDepartamento();
                $datos['tipoAusencia'] = $this->permisosVacaciones->obtenerTiposAusencia();
                $datos['motivoAusencia'] = $this->permisosVacaciones->obtenerMotivoAusencia();
                $datos['permisosAusencias'] = $this->permisosVacaciones->obtenerPermisosAusencia($usuario['Id']);
                $datos['enviarCorreos'] = $this->permisosVacaciones->enviarCorreoSiccob();
                break;
            case 'RH/Autorizar_permisos':
                $datos['misSubordinados'] = $this->autorizarpermisos->buscarSubordinados($usuario['Id']);
                break;
            case 'RH/Catalogos_Permisos':
                  $datos['TipoAsencia'] = array('ausencia');
                  $datos['TipoRechazo'] = array('rechazo');
//                $datos['misSubordinados'] = $this->autorizarpermisos->buscarSubordinados($usuario['Id']);
                break;
            case 'Poliza':
                $datos['TiposProyectos'] = $this->DBPO->getTiposProyecto();
                $datos['ProyectosSinAtender'] = $this->DBPO->getProyectosSinAtender();
            case 'Administrador/Permisos':
                $datos['ListaPermisos'] = $this->Catalogo->catPermisos("3");
                break;
            case 'RH/Resumen_Personal':
                $datos['ListaPersonal'] = $this->Personal->AltaPersonal("3");
                break;
            case 'Administrador/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('19');
                break;
            case 'Administrador/Resumen_Usuarios':
                $datos['ListaUsuarios'] = $this->Catalogo->catUsuarios("3");
                break;
            case 'RH/Departamentos':
                $datos['ListaDepartamentos'] = $this->Catalogo->catDepartamentos("3");
                break;
            case 'Administrador/Clientes':
                $datos['ListaClientes'] = $this->Catalogo->catClientes("3");
                break;
            case 'Administrador/Sucursales':
                $datos['ListaSucursales'] = $this->Catalogo->catSucursales("3");
                break;
            case 'Administrador/Proveedores':
                $datos['ListaProveedores'] = $this->Catalogo->catProveedores("3");
                break;
            case 'Generales/Minuta_Resumen':
                $datos['ListaMinutas'] = $this->Minuta->mostrarMinutas();
                break;
            case 'Logistica/Regiones':
                $datos['ListaRegiones'] = $this->Catalogo->catRegionesLogistica("3");
                $datos['Sucursales'] = $this->Catalogo->catSucursales("3", array('Flag' => '1'));
                break;
            case 'Generales/Archivo_Nuevo':
                $datos['SelectArchivos'] = $this->Archivos->mostrarArchivosFormatos();
                break;
            case 'Generales/Archivo_Resumen':
                $datos['SelectArchivos'] = $this->Archivos->mostrarArchivosFormatos();
                break;
            case 'Almacen/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('12');
                break;
            case 'Almacen/Seguimiento_Servicios':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('16');
                break;
            case 'Almacen/Almacenes':
                $datos['ListaAlmacenes'] = $this->Catalogo->catAlmacenesVirtuales('3');
                break;
            case 'Almacen/Lineas':
                $datos['ListaLineas'] = $this->Catalogo->catLineasEquipo('3');
                break;
            case 'Almacen/Sublineas':
                $datos['ListaSublineas'] = $this->Catalogo->catSublineasEquipo('3');
                break;
            case 'Almacen/Marcas':
                $datos['ListaMarcas'] = $this->Catalogo->catMarcasEquipo('3');
                break;
            case 'Almacen/Modelos':
                $datos['ListaModelos'] = $this->Catalogo->catModelosEquipo('3');
                break;
            case 'Almacen/Componentes':
                $datos['ListaComponentes'] = $this->Catalogo->catComponentesEquipo('3');
                break;
            case 'Capacitacion/Videos':
                $datos['ListaCapacitaciones'] = $this->Catalogo->catCapacitacionesVideo('3');
                break;
            case 'Logistica/Rutas':
                $datos['ListaRutas'] = $this->Rutas->listaRutas();
                break;
            case 'Logistica/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('17');
                break;
            case 'Logistica/Dashboard':
                $datos['Fechas'] = $this->DashboardGeneral->getFechasInicialesDashboard();
                $datos['Generadas'] = $this->DashboardGeneral->getSolicitudesGeneradas();
                $datos['ServiciosArea'] = $this->DashboardGeneral->getServiciosAreaLogistica();
                $datos['Usuario'] = $this->Usuario->getDatosUsuario();
                break;
            case 'Poliza/Solicitudes_Multimedia':
                $datos['ListaSolicitudesMultimedia'] = $this->Poliza->getSolicitudesMultimedia();
                break;
            case 'Compras/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('15');
                break;
            case 'Compras/Solicitud_Compra':
                $datos['Clientes'] = $this->Gapsi->getClientes();
                $datos['Productos'] = $this->Compras->getSAEProducts();
                break;
            case 'Compras/Mis_Solicitudes_Compra':
                $datos['Solicitudes'] = $this->Compras->getListaMisSolicitudes();
                break;
            case 'Compras/Autorizar_Solicitudes_Compra':
                $datos['Solicitudes'] = $this->Compras->getListaSolicitudesPorAutorizar();
                break;
            case 'Compras/Ordenes_Compra':
                $datos['ListaOrdenesCompra'] = $this->Compras->consultaListaOrdenesCompra();
                break;
            case 'Contabilidad/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('22');
                break;
            case 'Laboratorio/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('10');
                break;
            case 'Salas4D/Dashboard':
                $datos['Fechas'] = $this->ModeloSalas4D->getFechasInicialesDashboard();
                $datos['Estatus'] = $this->ModeloSalas4D->getGroupEstatus();
                $datos['Prioridades'] = $this->ModeloSalas4D->getGroupPrioridades();
                $datos['Tipos'] = $this->ModeloSalas4D->getGroupTipos();
                break;
            case 'Generales/Dashboard':
                $datos['Fechas'] = $this->ModeloDashboard->getFechasInicialesDashboard();
                $datos['Estatus'] = $this->ModeloDashboard->getGroupEstatus();
                $datos['Prioridades'] = $this->ModeloDashboard->getGroupPrioridades();
                $datos['Tipos'] = $this->ModeloDashboard->getGroupTipos();
                break;
            case 'Salas4D/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('7');
                break;
            case 'Salas4D/Inventario':
                $datos['Sucursales'] = $this->ModeloSalas4D->getSucursales4D();
                break;
            case 'Redes/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('5');
                break;
            case 'MesaDeAyuda/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('4');
                break;
            case 'Poliza/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('11');
                break;
            case 'Administrador/AreasAtencion':
                $datos['ListaAreasAtencion'] = $this->Catalogo->catAreasAtencion("3");
                break;
            case 'AdminProyectos/Inventarios':
                $datos['Almacenes'] = $this->SAE->getAlamacenesSAE();
                break;
            case 'Sistemas/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('34');
                break;
            case 'Tesoreria/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('20');
                break;
            case 'Tesoreria/Facturacion':
                $datos['TablaFacturacion'] = $this->Tesoreria->mostrarTablaDependiendoUsuario();
                break;
            case 'Tesoreria/Fondo_Fijo':
                $datos['FondosFijos'] = $this->FondoFijo->getFondosFijos();
                break;
            case 'Poliza/Regiones_Cliente':
                $datos['ListaRegionesCliente'] = $this->Catalogo->catRegionesCliente("3");
                break;
            case 'Mercadotecnia/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('37');
                break;
            case 'Cimos/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('39');
                break;
            case 'Generales/Validaciones_Servicios':
                $datos['Servicios'] = $this->Servicios->getServiciosEnValidacion();
                break;
            case 'Poliza/Catalogo_Fallas':
                $datos['ListaClasificacionFallas'] = $this->Catalogo->catClasificacionFallas('3');
                $datos['ListaTiposFallas'] = $this->Catalogo->catTiposFallas('3');
                $datos['ListaFallasEquipo'] = $this->Catalogo->catFallasEquipo('3');
                $datos['ListaFallasRefaccion'] = $this->Catalogo->catFallasRefaccion('3');
                break;
            case 'Poliza/Catalogo_Soluciones_Equipo':
                $datos['ListaSolucionesEquipo'] = $this->Catalogo->catSolucionesEquipo("3");
                break;
            case 'ReportesPoliza/Problemas_Faltantes_Manttos':
                $datos['ListaRegionesCliente'] = $this->Catalogo->catRegionesCliente("3", array('Flag' => '1'));
                $datos['Sucursales'] = $this->Catalogo->catSucursales("3", array('Flag' => '1'));
                break;
            case 'Salas4D/Catalogo_Tipos_Sistema':
                $datos['ListaTiposSistema'] = $this->Catalogo->catX4DTiposSistema('3');
                $datos['ListaEquipos'] = $this->Catalogo->catX4DEquipos('3');
                $datos['ListaMarcas'] = $this->Catalogo->catX4DMarcas('3');
                $datos['ListaModelos'] = $this->Catalogo->catX4DModelos('3');
                $datos['ListaComponentes'] = $this->Catalogo->catX4DComponentes('3');
                $datos['ActividadesMantenimiento'] = $this->Catalogo->catX4DActividadesMantenimiento('3', array('Flag' => '1'));
                $datos['TiposdeSistemas'] = $this->Catalogo->catX4DTiposSistema('3', array('Flag' => '1'));
                break;
            case 'Salas4D/Catalogo_Ubicaciones':
                $datos['ListaUbicaciones'] = $this->Catalogo->catX4DUbicaciones('3');
                break;
            case 'Poliza/Cinemex_Validaciones':
                $datos['ListaCinemexValidaciones'] = $this->Catalogo->catCinemexValidaciones('3');
                break;
            case 'MetodosProcedimientos/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('26');
                break;
            case 'FacturacionCobranza/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados('21');
                break;
            case 'Poliza/Servicios_Sin_Firma':
                $datos['Servicios'] = $this->Poliza->serviciosSinFirma();
                break;
            case 'Poliza/Resumen_Vueltas_Asociados':
                $datos['ListaVueltasAsociados'] = $this->Poliza->resumenVueltasAsociadosFolio();
                break;
            case 'Tesoreria/Catalogo_Outsorcing':
                $datos['MontosOutsourcing'] = $this->CatalogoTesoreria->tablaMontosVueltasOutsourcing();
                $datos['ListaViaticosOutsourcing'] = $this->CatalogoTesoreria->catalogoViaticosOutsourcing();
                break;
            case 'Gapsi/Solicitar-Gasto':
                $datos['Clientes'] = $this->Gapsi->getClientes();
                $datos['TiposServicio'] = $this->Gapsi->getTiposServicio();
                $datos['TiposBeneficiario'] = $this->Gapsi->getTiposBeneficiario();
                $datos['TiposTransferencia'] = $this->Gapsi->getTiposTransferencia();
                break;
            case 'Gapsi/Mis-Gastos':
                $datos['Gastos'] = $this->Gapsi->misGastos();
                break;
            case 'Reportes/Proyectos-Especiales':
                $datos['proyectos'] = $this->PEV2->getProyectosespeciales();
                break;
            case 'Proveedores/Seguimiento':
                $datos['Servicios'] = $this->Servicios->getServiciosAsignados($usuario['IdDepartamento']);
                break;
            case 'Poliza/Catalogo_Checklist':
                $datos['Categorias'] = $this->Poliza->mostrarCategorias();
                $datos['ListaPreguntas'] = $this->Poliza->mostrarListaPreguntas();
                break;
            case 'Documentacion/Carta_Responsiva':
                $datos['tecnicosCartaResponsiva'] = $this->Documentacion->mostrarTecnicosCartaResponsiva();
                break;
            case 'Comprobacion/Catalogos':
                $datos['Conceptos'] = $this->ModeloComprobacion->getConceptos();
                $datos['FondoFijoXUsuario'] = $this->ModeloComprobacion->getFondosFijos();
                break;
            case 'Comprobacion/Fondo_Fijo':
                $datos['listaComprobaciones'] = $this->ModeloTesoreria->getDetallesFondoFijoXUsuario($usuario['Id']);
                $datos['usuario'] = $this->ModeloTesoreria->getNombreUsuarioById($usuario['Id']);
                $datos['saldo'] = $this->ModeloTesoreria->getSaldoByUsuario($usuario['Id']);
                $datos['saldoGasolina'] = $this->ModeloTesoreria->getSaldoGasolinaByUsuario($usuario['Id']);
                $datos['xautorizar'] = $this->ModeloTesoreria->getSaldoXAutorizarByUsuario($usuario['Id']);
                $datos['rechazado'] = $this->ModeloTesoreria->getSaldoRechazadoSinPagar($usuario['Id']);
                break;
            case 'Comprobacion/Autorizar_Fondo_Fijo':
                $datos['listaComprobaciones'] = $this->ModeloTesoreria->getComprobacionesXAutorizar($usuario['Id']);
                break;
            case 'Localizacion/Dispositivos':
                $datos['dispositivos'] = $this->ubicaphone->cargaDispositivosGlobal();
                break;
            case 'Poliza/Seguimiento_Equipos':
                $datos['tablaEquipos'] = $this->Seguimientos->mostrarTabla();
                $datos['permisoNuevoRegistro'] = $this->Seguimientos->permisoNuevoRegistro();
                break;
            case 'Configuracion/Perfil':
                $datos['datosUsuario'] = $this->PerfilUsuario->datosPerfilUsuario();
                $datos['catalogos'] = $this->PerfilUsuario->datosCatalogosUsuario();
                break;
            case 'RH/Catalogos_Perfil':
                $datos['DocumentosRecibidos'] = $this->Catalogo->catRhDocumentosEstudio('3');
                $datos['EstadoCivil'] = $this->Catalogo->catRhEdoCivil('3');
                $datos['Idiomas'] = $this->Catalogo->catRhHabilidadesIdioma('3');
                $datos['NivelesEstudio'] = $this->Catalogo->catRhNivelEstudio('3');
                $datos['NivelesHabilidad'] = $this->Catalogo->catRhNivelHabilidad('3');
                $datos['Sexos'] = $this->Catalogo->catRhSexo('3');
                $datos['Sistemas'] = $this->Catalogo->catRhHabilidadesSistema('3');
                $datos['Software'] = $this->Catalogo->catRhHabilidadesSoftware('3');
                break;
            case 'Generales/Dashboard_Gapsi':
//                $datos['Datos'] = $this->GapsiProyecto->getDatosGeneralesProyectos();
                $datos['Proyectos'] = $this->GapsiProyecto->getListProjects();
                $datos['TiposProyectos'] = $this->GapsiProyecto->getProjectTypes();
                break;
            case 'FondoFijo/Catalogos':
                $datos['TiposCuenta'] = $this->fondoFijo->getTiposCuenta();
                $datos['Usuarios'] = $this->fondoFijo->getUsuarios();
                $datos['Conceptos'] = $this->fondoFijo->getConceptos();
                break;
            case 'FondoFijo/Depositar':
                $datos['Usuarios'] = $this->fondoFijo->getUsuariosConFondoFijo();
                break;
            case 'FondoFijo/MiFondo':
                $datos['Cuentas'] = $this->fondoFijo->getSaldosCuentasXUsuario($usuario['Id']);
                break;
            case 'FondoFijo/Autorizar':
                $datos['Pendientes'] = $this->fondoFijo->pendientesXAutorizar($usuario['Id']);
                break;
            case 'Instalaciones/Seguimiento':
                $datos['Pendientes'] = $this->instalaciones->getInstalacionesPendientes($usuario['Id']);
                break;
            case 'Prime/Inventario':
                $datos['Sucursales'] = $this->prime->getSucursalesPrime();
                break;
            case 'Redes/SeguimientoCE':
                $datos['infoServicios'] = $this->seccionCE->getDatosServicios();
                break;
            default:
                break;
        }
        return $datos;
    }

    /*
     * Regresa la seccion alcance segun el perfil del usuario
     * 
     */

    public function getAlcance($tipoProyecto, $idProyecto)
    {
        $data = array();
        $indice = array();
        $data['tipoProyecto'] = $tipoProyecto;
        $data['accesorios'] = $this->DB_Adist2->getAccesoriosTipoProyecto($tipoProyecto);
        $data['concepto'] = $this->DB_Adist2->getConcepto($tipoProyecto);
        $consulta = $this->DB_Adist2->getAlcanceProyecto($idProyecto);

        if (!empty($consulta)) {
            foreach ($consulta as $registro) {
                foreach ($registro as $key => $value) {
                    array_push($indice, $key);
                }
                break;
            }
            $data['tablaResumen'] = array($indice, $consulta);
        } else {
            $data['tablaResumen'] = array();
        }
        $data['areas'] = array();
        $data['ubicacion'] = array();
        if (!empty($data['concepto'])) {
            foreach ($data['concepto'] as $concepto) {
                $consulta = $this->DB_Adist2->getAreaConcepto($concepto['Id']);
                array_push($data['areas'], $consulta);
                foreach ($consulta as $area) {
                    $consulta = $this->DB_Adist2->getUbicacionArea($area['Id']);
                    array_push($data['ubicacion'], $consulta);
                }
            }
        }
        return array('formulario' => parent::getCI()->load->view('Proyectos/Modal/Alcance', $data, TRUE), 'datos' => $data);
    }

    /*
     * Regresa el contenido de la seccion de ayuda
     * 
     */

    public function getAyuda(string $ayuda)
    {
        return array('informacion' => parent::getCI()->load->view('Ayuda/' . $ayuda, '', TRUE));
    }
}
