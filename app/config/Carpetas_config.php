<?php

/*
 * Esta variable es utilizada para poder obtener los datos del archibo Menu_config.
 * La varible config['secciones'] esta asignada a un array el cual debe de indicarse la key del arreglo como el nombre de la carpeta.
 * Cada uno de estos valores del array contiene un array donde el valor del array debera definirse igual al nombre que aparece en el
 * archivo Menu_Config y su valor es el nombre de la pagina (tal cual esta definida en las vistas).
 *
 */

$config['Secciones'] = array(
    'Acceso' => array('Login', 'Recuperar_Acceso', 'Nuevo_Password'),
    'Configuracion' => array('Perfil-Configuracion' => 'Perfil'),
    'Generales' => array(
        'Agenda' => 'Agenda',
        'Dashboard-Generico' => 'Dashboard_Generico',
        'Dashboard-Siccob' => 'Dashboard',
        'Disp-Moviles' => 'Dispositivos-Moviles',
        'Notificaciones' => 'Notificaciones',
        'Buscar' => 'Buscar',
        'Validaciones' => 'Validaciones_Servicios',
        'Calendario' => 'Calendario',
        'Solicitud-Nueva' => 'Solicitud_Nueva',
        'Solicitud-Generadas' => 'Solicitud_Generadas',
        'Solicitud-Asignadas' => 'Solicitud_Asignada',
        'Solicitud-Seguimiento' => 'Solicitud_Seguimiento',
        'Solicitud-Autorizacion' => 'Solicitud_Autorizacion',
        'Solicitud-Editar' => 'Solicitud_Editar',
        'Minuta_Resumen' => 'Minuta_Resumen',
        'Archivo_Nuevo' => 'Archivo_Nuevo',
        'Archivo_Resumen' => 'Archivo_Resumen',
        'Dashboard_Gapsi' => 'Dashboard_Gapsi', 
        'Tester' => 'Tester'
    ),
    'Administrador' => array(
        'Dashboard-Administrador' => 'Dashboard',
        'Catalogo-Permisos' => 'Permisos',
        'Catalogo-Administrador' => 'Catalogo',
        'Resumen_Usuarios' => 'Resumen_Usuarios',
        'Catalogo-Clientes' => 'Clientes',
        'Catalogo-Sucursales' => 'Sucursales',
        'Catalogo-Areas-Atencion' => 'AreasAtencion',
        'Catalogo-Proveedores' => 'Proveedores',
        'Catalogo' => 'Perfiles',
        'Seguimiento_Administrador' => 'Seguimiento'
    ),
    'Proyectos' => array(
        'Dashboard-Proyecto' => 'Dashboard',
        'Nuevo-Proyectos' => 'Nuevo',
        'Nuevo-Proyectos-Administrativo-Proyectos' => 'NuevoAdminProyect',
        'Seguimiento-Proyectos' => 'Seguimiento',
        'Tareas-Proyectos' => 'TareasTecnico'
    ),
    'Proyectos2' => array(
        'Catalogo-Proyectos' => 'Catalogos',
        'Planeacion-Proyectos' => 'Planeacion',
        'Almacen-Proyectos' => 'Almacen',
        'Seguimiento-Tareas' => 'Tareas',
    ),
    'Logistica' => array(
        'Dashboard-Logistica' => 'Dashboard',
        'Seguimiento-logistica' => 'Seguimiento',
        'Rutas-logistica' => 'Rutas',
        'Regiones-logistica' => 'Regiones'
    ),
    'RH' => array(
        'Dashboard-RH' => 'Dashboard',
        'Resumen_Personal' => 'Resumen_Personal',
        'Catalogo-Areas' => 'Areas',
        'Catalogo-Perfiles' => 'Perfiles',
        'Catalogo-Departamentos' => 'Departamentos',
        'Seguimiento-RH' => 'Seguimiento',
        'Catalogo-Perfil' => 'Catalogos_Perfil',
        'Permisosvacaciones-RH' => 'Permisos_vacaciones',
        'Catalogo-Permisos-RH' => 'Catalogos_Permisos',
        'AutorizarPermisos-RH' => 'Autorizar_permisos',
        'CalendarioPermisos' => 'CalendarioPermisos'
    ),
    'Capacitacion' => array(
        'Capacitacion-Videos' => 'Videos'
    ),
    'Almacen' => array(
        'Seguimiento-Almacen-Serv' => 'Seguimiento_Servicios',
        'Seguimiento-Almacen' => 'Seguimiento',
        'Catalogo-Almacenes-Virtuales' => 'Almacenes',
        'Catalogo-Lineas-Equipo' => 'Lineas',
        'Catalogo-Sublineas-Equipo' => 'Sublineas',
        'Catalogo-Marcas-Equipo' => 'Marcas',
        'Catalogo-Modelos-Equipo' => 'Modelos',
        'Catalogo-Componentes-Equipo' => 'Componentes'
    ),
    'Poliza' => array(
        'Dashboard-Poliza' => 'Dashboard',
        'Seguimiento-Poliza' => 'Seguimiento',
        'SLA' => 'SLA',
        'Solicitudes-Multimedia' => 'Solicitudes_Multimedia',
        'Catalogo-Regiones-Cliente' => 'Regiones_Cliente',
        'Catalogo-Fallas-Poliza' => 'Catalogo_Fallas',
        'Catalogo-Soluciones-Equipo' => 'Catalogo_Soluciones_Equipo',
        'Catalogo-Cinemex-Validaciones' => 'Cinemex_Validaciones',
        'Servicios-Correctivos-Sin-Firma' => 'Servicios_Sin_Firma',
        'Resumen-Vueltas-Asocidaos' => 'Resumen_Vueltas_Asociados',
        'Catalogo-Checklist' => 'Catalogo_Checklist',
        'Seguimiento-Equipos' => 'Seguimiento_Equipos',
        'Reporte-Inventario' => 'Inventarios',
        'Catalogo-Unidades-Negocio' => 'Unidades_Negocio',
        'Catalogo-Sublineas-Area' => 'UnidadesNegocioSublineasArea',
        'Catalogo-Modelos-Area' => 'UnidadesNegocioModelosArea',
        'Catalogo-Unidades-Area' => 'UnidadesNegocioArea'
    ),
    'Redes' => array(
        'Dashboard-Redes' => 'Dashboard',
        'Seguimiento-Redes' => 'Seguimiento',
        'Seguimiento-GeneralRedes' => 'SeguimientoCE',
        'CatalogoSwitchRedes' => 'CatalogoSwitch'
    ),
    'Salas4D' => array(
        'Dashboard-Salas4D' => 'Dashboard',
        'Seguimiento-Salas4D' => 'Seguimiento',
        'Inventario-Salas4D' => 'Inventario',
        'Catalogo-Ubicaciones-4D' => 'Catalogo_Ubicaciones',
        'Catalogo-Tipos-Sistema-Salas4D' => 'Catalogo_Tipos_Sistema'
    ),
    'Laboratorio' => array(
        'Dashboard-Loboratorio' => 'Dashboard',
        'Seguimiento-Laboratorio' => 'Seguimiento',
        'Seguimiento-Rehabilitacion' => 'SeguimientoRehabilitacion'
    ),
    'Contabilidad' => array(
        'Dashboard-Contabilidad' => 'Dashboard',
        'Seguimiento-Contabilidad' => 'Seguimiento'
    ),
    'Compras' => array(
        'Dashboard-Co' => 'Dashboard',
        'Seguimiento-Compras' => 'Seguimiento',
        'Ordenes-Compras' => 'Ordenes_Compra',
        'Facturas-OC' => 'Facturacion_OC',
        'Solicitud-Compra' => 'Solicitud_Compra',
        'Mis-Solicitudes-Compra' => 'Mis_Solicitudes_Compra',
        'Autorizar-Solicitudes-Compra' => 'Autorizar_Solicitudes_Compra'
    ),
    'MesaDeAyuda' => array(
        'Dashboard-Mesa' => 'Dashboard',
        'Seguimiento-Mesa' => 'Seguimiento'
    ),
    'AdminProyectos' => array(
        'Reporte-SAE-Inventarios' => 'Inventarios'
    ),
    'Reportes' => array(
        'Busqueda-ADV2' => 'Busqueda_V2',
        'Proyectos-Especiales-V2' => 'Proyectos-Especiales'
    ),
    'Sistemas' => array(
        'Seguimiento-Sistemas' => 'Seguimiento'
    ),
    'Tesoreria' => array(
        'Dashboard-Tesoreria' => 'Dashboard',
        'Seguimiento-Tesoreria' => 'Seguimiento',
        'Facturacion-Tesoreria' => 'Facturacion',
        'FondoFijo-Tesoreria' => 'Fondo_Fijo',
        'Outsorcing' => 'Catalogo_Outsorcing'
    ),
    'Mercadotecnia' => array(
        'Seguimiento-Mercadotecnia' => 'Seguimiento'
    ),
    'Cimos' => array(
        'Contratos' => 'Contratos',
        'Seguimiento-Cimos' => 'Seguimiento',
        'Reportes-Cimos' => 'Reportes',
    ),
    'ReportesSAE' => array(
        'Compras' => 'Compras',
        'Compras Proyecto' => 'Compras_Proyecto',
        'Movimientos' => 'Movimientos_Almacen',
        'Remisiones' => 'Remisiones'
    ),
    'ReportesPoliza' => array(
        'Problemas-Faltantes-Mantts' => 'Problemas_Faltantes_Manttos'
    ),
    'MetodosProcedimientos' => array(
        'Seguimiento-Metodos-Procedimientos' => 'Seguimiento',
    ),
    'FacturacionCobranza' => array(
        'Seguimiento-Facturacion-Cobranza' => 'Seguimiento',
    ),
    'Gapsi' => array(
        'Solicitud-Gasto' => 'Solicitar-Gasto',
        'Mis-Gastos' => 'Mis-Gastos',
        'Comprobar-Gastos' => 'Comprobar-Gastos'
    ),
    'Proveedores' => array(
        'Seguimiento-Proveedores' => 'Seguimiento'
    ),
    'Documentacion' => array(
        'Carta-Responsiva' => 'Carta_Responsiva'
    ),
    'Comprobacion' => array(
        'Catalogos-Comprobacion' => 'Catalogos',
        'FondoFijo-Comprobacion' => 'Fondo_Fijo',
        'FondoFijo-Autorizacion' => 'Autorizar_Fondo_Fijo',
    ),
    'Localizacion' => array(
        'Localizacion-Dispositivos' => 'Dispositivos'
    ),
    'FondoFijo' => array(
        'Catalogos' => 'Catalogos',  
        'Depositar' => 'Depositar',  
        'MiFondo' => 'MiFondo',   
        'Autorizar' => 'Autorizar',
        'SaldosTecnico' => 'SaldosTecnico'     
    ),
    'Instalaciones' => array(
        'Seguimiento' => 'Seguimiento'  
    ),
    'Prime' => array(
        'Inventario' => 'Inventario'  
    ),
);
