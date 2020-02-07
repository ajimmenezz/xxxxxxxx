<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */



/* Routers para el manejo de Compras */
$route['RecursosHumanos/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';

/* Routers para el manejo de Recursos Humanos */
$route['Almacen/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Almacen/Seguimiento/(:any)'] = 'Almacen/Controller_Seguimiento/manejarEvento/$1';
$route['Almacen/Catalogo/(:any)'] = 'Almacen/Controller_Catalogos/manejarEvento/$1';

/* Routers para el manejo de Logistica */
$route['Logistica/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Logistica/EventoLogistica/(:any)'] = 'Logistica/Controller_Logistica/manejarEvento/$1';
$route['Logistica/EventoRutas/(:any)'] = 'Logistica/Controller_Rutas/manejarEvento/$1';
$route['Logistica/Seguimiento/(:any)'] = 'Logistica/Controller_Seguimiento/manejarEvento/$1';
$route['Logistica/EventoDashboard/(:any)'] = 'Logistica/Controller_Dashboard/manejarEvento/$1';

/* Routers para el manejo de las seccion de ayuda */
$route['Ayuda/(:any)'] = 'Ayuda/Controller_Ayuda/manejarEvento/$1';

/* Routers para el manejo de Administrador */
$route['Administrador/(:any)'] = 'Administrador/Controller_Administrador/desplegarPantalla/$1';
$route['Administrador/EventoUsuario/(:any)'] = 'Administrador/Controller_Administrador/manejarEvento/$1';
$route['Administrador/EventoCatalogo/(:any)'] = 'Administrador/Controller_Permisos/manejarEvento/$1';
$route['Administrador/EventoCatalogoCliente/(:any)'] = 'Administrador/Controller_Administrador/manejarEvento/$1';
$route['Administrador/EventoCatalogoSucursales/(:any)'] = 'Administrador/Controller_Administrador/manejarEvento/$1';
$route['Administrador/EventoCatalogoProveedores/(:any)'] = 'Administrador/Controller_Administrador/manejarEvento/$1';
$route['Administrador/EventoCatalogoAreasAtencion/(:any)'] = 'Administrador/Controller_Administrador/manejarEvento/$1';
$route['Administrador/Seguimiento/(:any)'] = 'Administrador/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Poliza */
$route['Poliza/(:any)'] = 'Poliza/Controller_Poliza/desplegarPantalla/$1';
$route['Poliza/Evento/(:any)'] = 'Poliza/Controller_Poliza/manejarEvento/$1';
$route['Poliza/Seguimiento/(:any)'] = 'Poliza/Controller_Seguimiento/manejarEvento/$1';
$route['Poliza/EventoCatalogoRegionesCliente/(:any)'] = 'Poliza/Controller_Poliza/manejarEvento/$1';
$route['Poliza/EventoCatalogos/(:any)'] = 'Poliza/Controller_Catalogos/manejarEvento/$1';
$route['Poliza/ReportesPoliza/(:any)'] = 'Poliza/Controller_ReportesPoliza/manejarEvento/$1';
$route['Poliza/Tester/(:any)'] = 'Generales/Controller_Tester/manejarEvento/$1';
$route['Poliza/EventoCatalogoRevisionFisica/(:any)'] = 'Poliza/Controller_Poliza/manejarEvento/$1';
$route['Poliza/Inventarios/(:any)'] = 'Poliza/Controller_ReportesPoliza/manejarEvento/$1';
$route['Poliza/Seguimiento/Servicio/Atender'] = 'V2/Controller_ServicioTicket/atenderServicio';
$route['Poliza/Seguimiento/Servicio/GuardarInformacionGeneral'] = 'V2/Controller_ServicioTicket/guardarInformacionGeneral';
$route['Poliza/Seguimiento/Servicio/Folio/(:any)'] = 'V2/Controller_ServicioTicket/setFolio';


/* Routers para el manejo nuevo de Proyectos */
$route['Proyectos2/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Proyectos2/Catalogos/(:any)'] = 'Proyectos2/Controller_Catalogos/manejarEvento/$1';
$route['Proyectos2/Planeacion/(:any)'] = 'Proyectos2/Controller_Planeacion/manejarEvento/$1';
$route['Proyectos2/Almacen/(:any)'] = 'Proyectos2/Controller_Almacen/manejarEvento/$1';
$route['Proyectos2/Tareas/(:any)'] = 'Proyectos2/Controller_Tareas/manejarEvento/$1';

/* Routers para el manejo de Proyectos */
$route['Proyectos/(:any)'] = 'Proyectos/Controller_Proyectos/desplegarPantalla/$1';
$route['Proyectos/Nuevo/(:any)'] = 'Proyectos/Controller_Proyectos/manejarEvento/$1';
$route['Proyectos/Evento/(:any)'] = 'Proyectos/Controller_Proyectos/manejarEvento/$1';
$route['Proyectos/EventoCatalogo/(:any)'] = 'Proyectos/Controller_Catalogo/manejarEvento/$1';
$route['Proyectos/Seguimiento/(:any)'] = 'Proyectos/Controller_Seguimiento/manejarEvento/$1';
$route['Proyectos/Tareas/(:any)'] = 'Proyectos/Controller_TareasTecnico/manejarEvento/$1';

/* Routers para el manejo de secciones Configuracion */
$route['Configuracion/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Configuracion/PerfilUsuario/(:any)'] = 'Perfil/Controller_Perfil/manejarEvento/$1';

/* Routers para el manejo de secciones generales */
$route['Generales/(:any)'] = 'Generales/Controller_Notificaciones/desplegarPantalla/$1';
$route['Generales/Notificacion/(:any)'] = 'Generales/Controller_Notificaciones/manejarEvento/$1';
$route['Generales/Calendario/(:any)'] = 'Generales/Controller_Calendario/manejarEvento/$1';
$route['Generales/Solicitud/(:any)'] = 'Generales/Controller_Solicitud/manejarEvento/$1';
$route['Generales/Solicitud_Editar/(:any)'] = 'Generales/Controller_Editar/manejarEvento/$1';
$route['Generales/Servicio/(:any)'] = 'Generales/Controller_Servicios/manejarEvento/$1';
$route['Generales/Notas/(:any)'] = 'Generales/Controller_Notas/manejarEvento/$1';
$route['Generales/Minuta/(:any)'] = 'Generales/Controller_Minuta/manejarEvento/$1';
$route['Generales/Archivos/(:any)'] = 'Generales/Controller_Archivos/manejarEvento/$1';
$route['Generales/Buscar/(:any)'] = 'Generales/Controller_Buscar/manejarEvento/$1';
$route['Generales/ServiceDesk/(:any)'] = 'Generales/Controller_ServiceDesk/manejarEvento/$1';
$route['Generales/Dispositivos/(:any)'] = 'Generales/Controller_Dispositivos/manejarEvento/$1';
$route['Generales/Dashboard/(:any)'] = 'Generales/Controller_Dashboard/manejarEvento/$1';
$route['Generales/Dashboard_Gapsi/(:any)'] = 'Gapsi/Controller_GestorProyectos/manejarEvento/$1';
$route['Generales/Tester/(:any)'] = 'Generales/Controller_Tester/manejarEvento/$1';
$route['Generales/Dashboard_Generico/Mostrar_Graficas'] = 'V2/Controller_Dashboard/getDatosDashboards';
$route['Generales/Dashboard_Generico/Mostrar_Datos_Actualizados'] = 'V2/Controller_Dashboard/getDatosActualizados';

/* Routers para el manejo de páginas de PhantomJS */
$route['Phantom/Servicio/(:any)'] = 'Phantom/Controller_Phantom/servicioPhantom/$1';
$route['PhantomV2/Servicio/(:any)'] = 'Phantom/Controller_Phantom/servicioPhantomV2/$1';
$route['Phantom/Servicio/(:any)/(:any)'] = 'Phantom/Controller_Phantom/servicioPhantom/$1/$2';
$route['PhantomV2/Servicio/(:any)/(:any)'] = 'Phantom/Controller_Phantom/servicioPhantomV2/$1/$2';
$route['Phantom/TicketOld/(:any)'] = 'Phantom/Controller_Phantom/ticketOldPhantom/$1';
$route['Phantom/Folio/(:any)'] = 'Phantom/Controller_Phantom/mostrarServiciosFolio/$1';
$route['Servicio/(:any)'] = 'Generales/Controller_Servicios/manejarEvento/$1';

/* Routers para el manejo de Recursos Humanos */
$route['RH/(:any)'] = 'RH/Controller_Dashboard/desplegarPantalla/$1';
$route['RH/Evento/(:any)'] = 'Controller_Dashboard/manejarEvento/$1';
$route['RH/EventoAltaPersonal/(:any)'] = 'RH/Controller_RH/manejarEvento/$1';
$route['RH/EventoCatalogoArea/(:any)'] = 'RH/Controller_Areas/manejarEvento/$1';
$route['RH/EventoCatalogo/(:any)'] = 'RH/Controller_Perfiles/manejarEvento/$1';
$route['RH/EventoCatalogoDepartamento/(:any)'] = 'RH/Controller_Departamentos/manejarEvento/$1';
$route['RH/Seguimiento/(:any)'] = 'RH/Controller_Seguimiento/manejarEvento/$1';
$route['RH/EventoCatalogosPerfil/(:any)'] = 'RH/Controller_Catalogos_Perfil/manejarEvento/$1';
$route['RH/EventoPermisosVacaciones/(:any)'] = 'RH/Controller_PermisosVacaciones/manejarEvento/$1';
$route['RH/Catalogos_Permisos/Nuevo_Registro/(:any)'] = 'V2/Controller_Catalogos/nuevoRegistro/$1';
$route['RH/Catalogos_Permisos/Actualizar_Registro/(:any)'] = 'V2/Controller_Catalogos/actualizarRegistro/$1';
$route['RH/CalendarioPermisos/(:any)'] = 'Calendar/Controller_Calendar/manejarEvento/$1';

/* Routers para el manejo de la seccion Capacitacion */
$route['Capacitacion/(:any)'] = 'Capacitacion/Controller_Capacitacion/desplegarPantalla/$1';
$route['Capacitacion/EventoCargaVideos/(:any)'] = 'Capacitacion/Controller_Capacitacion/manejarEvento/$1';

/* Routers para el manejo de la seccion Acceso */
$route['Api/reportar'] = 'API/Api_Acceso';
$route['Acceso/(:any)'] = 'Controller_Acceso/manejarEvento/$1';
$route['Nuevo_Password'] = 'Controller_Acceso/nuevoPassword';
$route['Nuevo_Password/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Nuevo_Password/Acceso/(:any)'] = 'Controller_Acceso/manejarEvento/$1';
$route['Logout'] = 'Controller_Acceso/cerrarSesion';

/* Routers para el inicio del sistema */
$route['(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['default_controller'] = 'Controller_Acceso';
$route['404_override'] = 'Controller_Acceso/desplegarPantalla';
$route['translate_uri_dashes'] = FALSE;

/* Routers para el manejo de Compras */
$route['Compras/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Compras/Seguimiento/(:any)'] = 'Compras/Controller_Seguimiento/manejarEvento/$1';
$route['Compras/Compras/(:any)'] = 'Compras/Controller_Compras/manejarEvento/$1';

/* Routers para el manejo de Contabilidad */
$route['Contabilidad/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Contabilidad/Seguimiento/(:any)'] = 'Contabilidad/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Contabilidad */
$route['Documentacion/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Documentacion/Documentacion/(:any)'] = 'Documentacion/Controller_Documentacion/manejarEvento/$1';

/* Routers para el manejo de Laboratorio */
$route['Laboratorio/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Laboratorio/Seguimiento/(:any)'] = 'Laboratorio/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de SalasX4D */
$route['Salas4D/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Salas4D/Dashboard/(:any)'] = 'Salas4D/Controller_Dashboard/manejarEvento/$1';
$route['Salas4D/Seguimiento/(:any)'] = 'Salas4D/Controller_Seguimiento/manejarEvento/$1';
$route['Salas4D/Inventario/(:any)'] = 'Salas4D/Controller_Inventario/manejarEvento/$1';
$route['Salas4D/EventoCatalogos/(:any)'] = 'Salas4D/Controller_Catalogos/manejarEvento/$1';

/* Routers para el manejo de Redes */
$route['Redes/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Redes/Seguimiento/(:any)'] = 'Redes/Controller_Seguimiento/manejarEvento/$1';
$route['Redes/SeguimientoCE/SeguimientoGeneral/Atender'] = 'V2/Controller_ServicioTicket/atenderServicio';
$route['Redes/SeguimientoCE/SeguimientoGeneral/Seguimiento/(:any)'] = 'V2/Controller_ServicioTicket/seguimientoServicio/$1';
$route['Redes/SeguimientoCE/SeguimientoGeneral/Folio/(:any)'] = 'V2/Controller_ServicioTicket/setFolio';
$route['Redes/SeguimientoCE/SeguimientoGeneral/Accion/(:any)'] = 'V2/Controller_ServicioTicket/runEvento/$1';
$route['Redes/SeguimientoCE/SeguimientoGeneral/agregarProblema'] = 'V2/Controller_ServicioTicket/setProblema';
$route['Redes/SeguimientoCE/SeguimientoGeneral/eliminarFolio'] = 'V2/Controller_ServicioTicket/eliminarFolio';
$route['Redes/SeguimientoCE/SeguimientoGeneral/material'] = 'V2/Controller_ServicioTicket/getMaterial';
$route['Redes/SeguimientoCE/SeguimientoGeneral/guardarSolucion'] = 'V2/Controller_ServicioTicket/setSolucion';
$route['Redes/SeguimientoCE/SeguimientoGeneral/concluir'] = 'V2/Controller_ServicioTicket/setConcluir';
$route['Redes/SeguimientoCE/SeguimientoGeneral/exportarPDF'] = 'V2/Controller_ServicioTicket/getPDF';
$route['Redes/SeguimientoCE/SeguimientoGeneral/borrarEvidencias'] = 'V2/Controller_ServicioTicket/deleteEvidencias';
$route['Redes/SeguimientoCE/SeguimientoGeneral/validarServicio'] = 'V2/Controller_ServicioTicket/validarServicio';
$route['Redes/SeguimientoCE/SeguimientoGeneral/rechazarServicio'] = 'V2/Controller_ServicioTicket/rechazarServicio';
$route['Redes/SeguimientoCE/Catalogo/(:any)'] = 'Almacen/Controller_Catalogos/manejarEvento/$1';

/* Routers para el manejo de MesaDeAyuda */
$route['MesaDeAyuda/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['MesaDeAyuda/Seguimiento/(:any)'] = 'MesaDeAyuda/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Administradores de Proyectos */
$route['AdminProyectos/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['AdminProyectos/SAEReports/(:any)'] = 'SAEReports/Controller_SAEReports/manejarEvento/$1';

/* Routers para el manejo de Sistemas */
$route['Sistemas/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Sistemas/Seguimiento/(:any)'] = 'Sistemas/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Tesoreria */
$route['Tesoreria/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Tesoreria/Seguimiento/(:any)'] = 'Tesoreria/Controller_Seguimiento/manejarEvento/$1';
$route['Tesoreria/Facturacion/(:any)'] = 'Tesoreria/Controller_Tesoreria/manejarEvento/$1';
$route['Tesoreria/EventoCatalogos/(:any)'] = 'Tesoreria/Controller_Catalogos/manejarEvento/$1';
$route['Tesoreria/Fondo_Fijo/(:any)'] = 'Tesoreria/Controller_FondoFijo/manejarEvento/$1';

/* Routers para el manejo de Mercadotecnia */
$route['Mercadotecnia/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Mercadotecnia/Seguimiento/(:any)'] = 'Mercadotecnia/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Cimos */
$route['Cimos/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Cimos/Seguimiento/(:any)'] = 'Cimos/Controller_Seguimiento/manejarEvento/$1';
$route['Cimos/Reportes/(:any)'] = 'Cimos/Controller_Reportes/manejarEvento/$1';

/* Routers para el manejo de Reportes SAE */
$route['ReportesSAE/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['ReportesSAE/Compras/(:any)'] = 'SAEReports/Controller_SAEReports/manejarEvento/$1';

/* Routers para el manejo de páginas de Detalles */
$route['Detalles/Solicitud/(:any)'] = 'Generales/Controller_Detalles/detallesSolicitud/$1';
$route['Detalles/Servicio/(:any)'] = 'Generales/Controller_Detalles/detallesServicio/$1';

/* Routers para el manejo de Metodos y Procedimientos */
$route['MetodosProcedimientos/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['MetodosProcedimientos/Seguimiento/(:any)'] = 'MetodosProcedimientos/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Metodos y Procedimientos */
$route['FacturacionCobranza/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['FacturacionCobranza/Seguimiento/(:any)'] = 'FacturacionCobranza/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Reportes SAE */
$route['ReportesPoliza/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';

/* Routers para el manejo de Gapsi */
$route['Gapsi/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Gapsi/Gasto/(:any)'] = 'Gapsi/Controller_Gasto/manejarEvento/$1';

/* Routers para el manejo de Proveedores */
$route['Proveedores/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Proveedores/Seguimiento/(:any)'] = 'Proveedores/Controller_Seguimiento/manejarEvento/$1';

/* Routers para el manejo de Reporte de Proyectos Especiales */
$route['Reportes/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Reportes/PEV2/(:any)'] = 'Reportes/Controller_ProyectosEspecialesV2/manejarEvento/$1';

/* Routers para el manejo de Comprobaciones */
$route['Comprobacion/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Comprobacion/Catalogos/(:any)'] = 'Comprobacion/Controller_Catalogos/manejarEvento/$1';
$route['Comprobacion/Fondo_Fijo/(:any)'] = 'Tesoreria/Controller_FondoFijo/manejarEvento/$1';

/* Routers para el manejo de Localizacion */
$route['Localizacion/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Localizacion/Seguimiento/(:any)'] = 'Localizacion/Controller_Localizacion/manejarEvento/$1';

/* Routers para el manejo de Fondo Fijo */
$route['FondoFijo/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['FondoFijo/Catalogos/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/Depositar/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/MiFondo/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/Autorizar/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/SaldosTecnico/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/MovimientosTecnico/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';
$route['FondoFijo/DetallesMovimiento/(:any)'] = 'FondoFijo/Controller_FondoFijo/manejarEvento/$1';

/* Routers para el manejo de Instalaciones de Equipo */
$route['Instalaciones/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Instalaciones/Seguimiento/(:any)'] = 'Instalaciones/Controller_Instalaciones/manejarEvento/$1';

/* Routers para el manejo de Instalaciones de Equipo */
$route['Prime/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
$route['Prime/Inventario/(:any)'] = 'Prime/Controller_Inventario/manejarEvento/$1';

$route['SegundoPlano/(:any)'] = 'SegundoPlano/Controller_SegundoPlano/$1';

$route['Reportes/Lexmark/(:any)'] = 'Reportes/Controller_PrinterLexmark/manejarEvento/$1';
$route['Reportes/SD/(:any)'] = 'Reportes/Controller_Servicedesk/manejarEvento/$1';

$route['Error/(:any)'] = 'Controller_Error/$1';


/* Routers para dashboard gpasi gastos */
//$route['Localizacion/(:any)'] = 'Controller_Acceso/desplegarPantalla/$1';
