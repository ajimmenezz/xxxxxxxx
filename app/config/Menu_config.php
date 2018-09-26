<?php

/*
 * Aqui se definen las variables para el menu del sistema.
 */

// Modulos del sistema
$config['Modulos'] = array(
    'Generales' => array(),
    'Configuracion' => array(),
    'Solicitudes' => array('icono' => 'fa  fa-file-o'),
    'Administrador' => array('icono' => 'fa fa-bank'),
    'Admin Proyectos' => array('icono' => 'fa fa-calculator'),
    'Almacen' => array('icono' => 'fa fa-database'),
    'Archivos y Formatos' => array('icono' => 'fa fa-file-word-o'),
    'Capacitacion' => array('icono' => 'fa fa-mortar-board'),
    'Catalogos Generales' => array('icono' => 'fa fa-database'),
    'CIMOS' => array('icono' => 'fa fa-leaf'),
    'Compras' => array('icono' => 'fa fa-barcode'),
    'Contabilidad' => array('icono' => 'fa fa-book'),
    'Facturación y Cobranza' => array('icono' => 'fa fa-book'),
    'Gapsi' => array('icono' => 'fa fa-money'),
    'Laboratorio' => array('icono' => 'fa fa-wrench'),
    'Logistica' => array('icono' => 'fa fa-pie-chart'),
    'Mercadotecnia' => array('icono' => 'fa fa-female'),
    'Mesa de Ayuda' => array('icono' => 'fa fa-filter'),
    'Métodos y Procedimientos' => array('icono' => 'fa fa-shield'),
    'Minutas' => array('icono' => 'fa fa-file-text-o'),
    'Poliza' => array('icono' => 'fa fa-ticket'),
    'Proveedores' => array('icono' => 'fa fa-cubes'),
    'Proyectos' => array('icono' => 'fa fa-building'),
    'Redes' => array('icono' => 'fa fa-sliders'),
    'Reportes' => array('icono' => 'fa fa-pencil-square-o'),
    'Reportes Poliza' => array('icono' => 'fa fa-signal'),
    'Reportes SAE' => array('icono' => 'fa fa-signal'),
    'RH' => array('icono' => 'fa fa-archive'),
    'Salas X4D' => array('icono' => 'fa fa-video-camera'),
    'Sistemas' => array('icono' => 'fa fa-server'),
    'Tesoreria' => array('icono' => 'fa fa-money')
);


//Menu por secciones
$config['Generales'] = array(
    'Dashboard-Administrador' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-line-chart',
        'Url' => '/Administrador/Dashboard',
        'css' => array(),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'Administrador/dashboard'),
        'pluginsJs' => array(),
        'Permiso' => 'VGDA'
    ),
    'Dashboard-Proyecto' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-dashboard',
        'Url' => '/Proyectos/Dashboard',
        'css' => array(),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'Proyectos/dashboard'),
        'pluginsJs' => array(),
        'Permiso' => 'VGDP'
    ),
    'Dashboard-Salas4D' => array(
        'liga' => 'Dashboard Salas 4D',
        'icono' => 'fa fa-line-chart',
        'Url' => '/Salas4D/Dashboard',
        'css' => array(
            'Dashboard/Dashboard'
        ),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Salas4D/dashboard', 'Base/Tabla', 'Base/Charts'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
        ),
        'Permiso' => 'VD4D'
    ),
    'Dashboard-Logistica' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-dashboard',
        'Url' => '/Logistica/Dashboard',
        'css' => array(
            'Dashboard/Dashboard'
        ),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Logistica/dashboard', 'Base/Tabla', 'Base/Charts'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VGDLS'
    ),
    'Dashboard-Poliza' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-dashboard',
        'Url' => 'Dashboard-Poliza',
        'css' => array(),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'Poliza/dashboard'),
        'pluginsJs' => array(),
        'Permiso' => 'VGDPO'
    ),
    'Dashboard-Proyectos' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-dashboard',
        'Url' => 'Dashboard-Proyectos',
        'css' => array(),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'Proyectos/dashboard'),
        'pluginsJs' => array(),
        'Permiso' => 'VGDPRO'
    ),
    'Dashboard-RH' => array(
        'liga' => 'Dashboard',
        'icono' => 'fa fa-dashboard',
        'Url' => '/RH/Dashboard',
        'css' => array(),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'RH/dashboard'),
        'pluginsJs' => array(),
        'Permiso' => 'VGDRH'
    ),
    'Disp-Moviles' => array(
        'liga' => 'Disp. Móviles',
        'icono' => 'fa fa-mobile',
        'Url' => '/Generales/Dispositivos-Moviles',
        'css' => array('Generales/disp-moviles'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Generales/disp-moviles'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
        ),
        'Permiso' => 'VGDM'
    ),
    'Notificaciones' => array(
        'liga' => 'Notificaciones',
        'icono' => 'fa fa-bell',
        'Url' => '/Generales/Notificaciones',
        'css' => array('Generales/notificaciones'),
        'pluginsCss' => array(
            'DataTables/css/data-table'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Generales/notificaciones'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive'
        ),
        'Permiso' => 'VGN'
    ),
    'Calendario' => array(
        'liga' => 'Calendario',
        'icono' => 'fa fa-calendar',
        'Url' => '/Generales/Calendario',
        'css' => array('Generales/calendario'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'fullcalendar-3.6.1/fullcalendar.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Generales/calendario'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'fullcalendar-3.6.1/lib/moment.min',
            'fullcalendar-3.6.1/fullcalendar.min',
            'fullcalendar-3.6.1/locale-all'
        ),
        'Permiso' => 'VCASERV'
    ),
    'Buscar' => array(
        'liga' => 'Búscar',
        'icono' => 'fa fa-search',
        'Url' => '/Generales/Buscar',
        'css' => array('Generales/buscar', 'Generales/notas', 'Generales/servicios', 'Generales/imageWithDelete'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Fecha', 'Base/Select', 'Generales/buscar'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VBUST'
    ),
    'Validaciones' => array(
        'liga' => 'Validaciones',
        'icono' => 'fa fa-legal',
        'Url' => '/Generales/Validaciones_Servicios',
        'css' => array('Generales/buscar', 'Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'select2/dist/css/select2.min',
            'jquery-fileUpload/css/fileinput.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/fileUpload', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Nota', 'Base/Servicio', 'Generales/validaciones_servicios'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VGV'
    )
);

$config['Configuracion'] = array(
    'Perfil-Configuracion' => array(
        'liga' => 'Perfil',
        'icono' => '',
        'Url' => '',
        'css' => array('Configuracion/perfil'),
        'pluginsCss' => array(),
        'js' => array('Base/Base', 'Base/Socket', 'Configuracion/perfil'),
        'pluginsJs' => array(),
        'Permiso' => 'VCPE'
    )
);

$config['Solicitudes'] = array(
    'Solicitud-Nueva' => array(
        'liga' => 'Nueva',
        'icono' => '',
        'Url' => '/Generales/Solicitud_Nueva',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-fileUpload/css/fileinput.min',
            'parsley/src/parsley',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/fileUpload', 'Base/Servicio', 'Base/Nota', 'Generales/solicitud_nueva'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VGSN'
    ),
    'Solicitud-Generadas' => array(
        'liga' => 'Generadas',
        'icono' => '',
        'Url' => '/Generales/Solicitud_Generadas',
        'css' => array('Generales/solicitud_generadas', 'Generales/notas'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-fileUpload/css/fileinput.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/fileUpload', 'Base/Nota', 'Base/Servicio', 'Generales/solicitud_generadas'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VGSG'
    ),
    'Solicitud-Asignadas' => array(
        'liga' => 'Asignadas',
        'icono' => '',
        'Url' => '/Generales/Solicitud_Asignada',
        'css' => array('Generales/solicitud_asignada'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Generales/solicitud_asignada'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VGSA'
    ),
    'Solicitud-Autorizacion' => array(
        'liga' => 'Autorización',
        'icono' => '',
        'Url' => '/Generales/Solicitud_Autorizacion',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Generales/solicitud_autorizacion'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VGSAU'
    ),
    'Solicitud-Editar' => array(
        'liga' => 'Editar',
        'icono' => '',
        'Url' => '/Generales/Solicitud_Editar',
        'css' => array('Generales/imageWithDelete'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-tag-it/css/jquery.tagit',
            'jquery-fileUpload/css/fileinput.min',
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Tabla', 'Generales/solicitud_editar'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-tag-it/js/tag-it.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
        ),
        'Permiso' => 'VGSED'
    )
);

$config['Reportes'] = array(
    'Busqueda-ADV2' => array(
        'liga' => 'Búsqueda V2',
        'icono' => 'fa fa-search',
        'Url' => '/Reportes/BusquedaV2',
        'css' => array(
            'Dashboard/Dashboard'
        ),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Charts', 'Base/Select', 'Reportes/busquedaV2'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VBAV2'
    ),
    'Proyectos-Especiales-V2' => array(
        'liga' => 'Proyectos Especiales',
        'icono' => 'fa fa-search',
        'Url' => '/Reportes/Proyectos-Especiales',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Reportes/proyectos-especiales-v2'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VPEV2'
    )
);

$config['Proyectos'] = array(
    'Catalogo-Proyectos' => array(
        'liga' => 'Catálogos',
        'icono' => '',
        'Url' => '/Proyectos2/Catalogos',
        'css' => array('Proyectos2/Catalogo'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'bootstrap-combobox/css/bootstrap-combobox',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Proyectos2/catalogos'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'DataTables/js/dataTables.jumpToData',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'bootstrap-combobox/js/bootstrap-combobox'
        ),
        'Permiso' => 'VCP'
    ),
    'Planeacion-Proyectos' => array(
        'liga' => 'Planeacion',
        'icono' => '',
        'Url' => '/Proyectos2/Planeacion',
        'css' => array('Proyectos2/Catalogo'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'bootstrap-combobox/css/bootstrap-combobox',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Fecha', 'Proyectos2/planeacion'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'DataTables/js/dataTables.jumpToData',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'bootstrap-combobox/js/bootstrap-combobox',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VPN'
    ),   
    'Almacen-Proyectos' => array(
        'liga' => 'Almacén',
        'icono' => '',
        'Url' => '/Proyectos2/Almacen',
        'css' => array('Proyectos2/Catalogo'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'bootstrap-combobox/css/bootstrap-combobox',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Fecha', 'Proyectos2/almacen'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'DataTables/js/dataTables.jumpToData',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'bootstrap-combobox/js/bootstrap-combobox',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VAP'
    ), 
    'Seguimiento-Tareas' => array(
        'liga' => 'Seguimiento Tareas',
        'icono' => '',
        'Url' => '/Proyectos2/Tareas',
        'css' => array('Proyectos2/Catalogo'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-fileUpload/css/fileinput.min',
            'parsley/src/parsley',
            'bootstrap-combobox/css/bootstrap-combobox',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Fecha', 'Base/fileUpload', 'Proyectos2/tareas'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'DataTables/js/dataTables.jumpToData',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-combobox/js/bootstrap-combobox',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VSTP'
    ), 
    'Seguimiento-Proyectos' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Proyectos/Seguimiento',
        'css' => array('Proyecto/seguimiento'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'jquery-fileUpload/css/fileinput.min',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'Gantt/codebase/dhtmlxgantt'
        ),
        'js' => array(
            'Componentes/Pagina',
            'Componentes/Tablas/Tabla',
            'Componentes/Tablas/TablaBasica',
            'Componentes/Tablas/TablaColumnaOculta',
            'Componentes/Formulario',
            'Componentes/Socket',
            'Componentes/FileUpload/Upload',
            'Componentes/FileUpload/FileUpload_Basico',
            'Componentes/Selects/Select',
            'Componentes/Selects/SelectBasico',
            'Componentes/Selects/SelectMultiple',
            'Componentes/Fecha',
            'Componentes/Inputs/Input',
            'Componentes/Modal/ModalBase',
            'Componentes/Modal/Modal',
            'Componentes/Modal/Alertas',
            'Componentes/Gantt/Gantt',
            'Proyectos/Paginas/PaginaProyecto',
            'Proyectos/Paginas/PaginaSeguimiento',
            'Proyectos/Controladores/Controller_Seguimiento'
        ),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'Gantt/codebase/dhtmlxgantt',
            'Gantt/codebase/locale/locale_es',
            'Gantt/codebase/ext/dhtmlxgantt_tooltip'
        ),
        'Permiso' => 'VPSE'
    ),
    'Tareas-Proyectos' => array(
        'liga' => 'Tareas',
        'icono' => 'fa fa-tasks',
        'Url' => '/Proyectos/TareasTecnico',
        'css' => array('Proyecto/tareasTecnico'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'jquery-fileUpload/css/fileinput.min',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'Gantt/codebase/dhtmlxgantt'
        ),
        'js' => array(
            'Componentes/Pagina',
            'Componentes/Tablas/Tabla',
            'Componentes/Tablas/TablaBasica',
            'Componentes/Tablas/TablaColumnaOculta',
            'Componentes/Formulario',
            'Componentes/Socket',
            'Componentes/FileUpload/Upload',
            'Componentes/FileUpload/FileUpload_Basico',
            'Componentes/Selects/Select',
            'Componentes/Selects/SelectBasico',
            'Componentes/Selects/SelectMultiple',
            'Componentes/Fecha',
            'Componentes/Inputs/Input',
            'Componentes/Modal/ModalBase',
            'Componentes/Modal/Modal',
            'Componentes/Modal/Alertas',
            'Proyectos/Paginas/PaginaProyecto',
            'Proyectos/Paginas/PaginaTareas',
            'Proyectos/Controladores/Controller_TareasTecnico'
        ),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'Gantt/codebase/dhtmlxgantt',
            'Gantt/codebase/locale/locale_es',
            'Gantt/codebase/ext/dhtmlxgantt_tooltip'
        ),
        'Permiso' => 'VTP'
    )
//    'Gantt-Proyectos' => array(
//        'liga' => 'Gantt',
//        'icono' => 'fa fa-bar-chart',
//        'Url' => 'javascript:;',
//        'css' => array(),
//        'pluginsCss' => array(),
//        'js' => array(),
//        'pluginsJs' => array(),
//        'Permiso' => 'VPG'
//    ),
//    'Concluidos-Proyectos' => array(
//        'liga' => 'Concluidos',
//        'icono' => 'fa fa-check-square-o',
//        'Url' => 'javascript:;',
//        'css' => array(),
//        'pluginsCss' => array(),
//        'js' => array(),
//        'pluginsJs' => array(),
//        'Permiso' => 'VPC'
//    )
);

$config['Poliza'] = array(
    'Seguimiento-Poliza' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Poliza/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'bootstrap-wizard/css/bwizard.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Base/Nota', 'Poliza/seguimiento'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'masked-input/masked-input.min',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'bootstrap-wizard/js/bwizard',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VPOSE'
    ),
    'Servicios-Correctivos-Sin-Firma' => array(
        'liga' => 'Servicios Correctivos',
        'icono' => 'fa fa-eye',
        'Url' => '/Poliza/Servicios_Sin_Firma',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'bootstrap-wizard/css/bwizard.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Base/Nota', 'Poliza/servicios_sin_firma'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'masked-input/masked-input.min',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'bootstrap-wizard/js/bwizard',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VPOSE'
    ),
    'Catalogo-Cinemex-Validaciones' => array(
        'liga' => 'Cinemex Validadores',
        'icono' => '',
        'Url' => '/Poliza/Cinemex_Validaciones',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'parsley/src/parsley',
            'select2/dist/css/select2.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/cinemex_validaciones_poliza'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min',
            'select2/dist/js/select2.min'
        ),
        'Permiso' => 'VCCV'
    ),
    'Catalogo-Fallas-Poliza' => array(
        'liga' => 'Fallas Poliza',
        'icono' => '',
        'Url' => '/Poliza/Catalogo_Fallas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/fallas_poliza'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCFP'
    ),
    'Catalogo-Soluciones-Equipo' => array(
        'liga' => 'Soluciones de Equipo',
        'icono' => '',
        'Url' => '/Poliza/Catalogo_Soluciones_Equipo',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/soluciones-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCSOE'
    ),
    'Solicitudes-Multimedia' => array(
        'liga' => 'Solicitudes a Mutimedia',
        'icono' => 'fa fa-list-alt',
        'Url' => '/Poliza/Solicitudes_Multimedia',
        'css' => array('Base/Base'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Fecha', 'Base/fileUpload', 'Base/Tabla', 'Poliza/solicitudes_multimedia'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min'
        ),
        'Permiso' => 'VPSM'
    ), 'Resumen-Vueltas-Asocidaos' => array(
        'liga' => 'Resumen Vueltas',
        'icono' => 'fa fa-file',
        'Url' => '/Poliza/Resumen_Vueltas_Asociados',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'bootstrap-wizard/css/bwizard.min',
            'DrawingBoard/css/drawingboard.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Botones', 'Poliza/resumen_vueltas_asociados'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'bootstrap-wizard/js/bwizard',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VPRVA'
    ),
    'Catalogo-Checklist' => array(
        'liga' => 'Catalogo Checklist',
        'icono' => '',
        'Url' => '/Poliza/Catalogo_Checklist',
        'css' => array('Poliza/Catalogo_Checklist'),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla','Poliza/catalogo_checklist'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'DataTables/js/dataTables.jumpToData',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'select2/dist/js/select2.min'
        ),
        'Permiso' => 'VCCHECK'
    )
);

$config['Logistica'] = array(
    'Seguimiento-logistica' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Logistica/Seguimiento',
        'css' => array('Logistica/seguimiento', 'Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Fecha', 'Base/fileUpload', 'Base/Select', 'Base/Servicio', 'Base/Tabla', 'Logistica/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VLS'
    ),
    'Rutas-logistica' => array(
        'liga' => 'Rutas',
        'icono' => 'fa fa-globe',
        'Url' => '/Logistica/Rutas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Fecha', 'Base/Select', 'Base/Tabla', 'Logistica/rutas'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VLRU'
    ),
    'Regiones-logistica' => array(
        'liga' => 'Regiones Logistica',
        'icono' => '',
        'Url' => '/Logistica/Regiones',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/regiones'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VLR'
    )
);

$config['Catalogos Generales'] = array(
    'Catalogo-Areas' => array(
        'liga' => 'Áreas Siccob',
        'icono' => '',
        'Url' => '/RH/Areas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/areas'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCAR'
    ),
    'Catalogo-Areas-Atencion' => array(
        'liga' => 'Áreas de Atención',
        'icono' => '',
        'Url' => '/Administrador/AreasAtencion',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/areas-atencion'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VCAA'
    ),
    'Catalogo-Clientes' => array(
        'liga' => 'Clientes',
        'icono' => '',
        'Url' => '/Administrador/Clientes',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/clientes'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCC'
    ),
    'Catalogo-Departamentos' => array(
        'liga' => 'Despartamentos Siccob',
        'icono' => '',
        'Url' => '/RH/Departamentos',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/departamentos'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCDE'
    ),
    'Catalogo-Permisos' => array(
        'liga' => 'Permisos',
        'icono' => '',
        'Url' => '/Administrador/Permisos',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/permisos'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCPERM'
    ),
    'Catalogo-Perfiles' => array(
        'liga' => 'Puestos Siccob',
        'icono' => '',
        'Url' => '/RH/Perfiles',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/perfiles'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCPER'
    ),
    'Catalogo-Proveedores' => array(
        'liga' => 'Proveedores',
        'icono' => '',
        'Url' => '/Administrador/Proveedores',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/proveedores'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCPR'
    ),
    'Catalogo-Regiones-Cliente' => array(
        'liga' => 'Regiones de Cliente',
        'icono' => '',
        'Url' => '/Poliza/Regiones_Cliente',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/regiones-cliente'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCRC'
    ),
    'Catalogo-Sucursales' => array(
        'liga' => 'Sucursales',
        'icono' => '',
        'Url' => '/Administrador/Sucursales',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/sucursales'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCS'
    )
);

$config['RH'] = array(
    'Seguimiento-RH' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/RH/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'RH/seguimiento', 'Base/Botones', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VRHSE'
    ),
    'Resumen_Personal' => array(
        'liga' => 'Resumen de Personal',
        'icono' => 'fa fa-list-alt',
        'Url' => '/RH/Resumen_Personal',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Fecha', 'Base/Tabla', 'RH/personal'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VRHRP'
    )
);

$config['Capacitacion'] = array(
    'Capacitacion-Videos' => array(
        'liga' => 'Videos',
        'icono' => 'fa fa-video-camera',
        'Url' => '/Capacitacion/Videos',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Capacitacion/videos'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es'
        ),
        'Permiso' => 'VCVI'
    )
);

$config['Administrador'] = array(
    'Seguimiento_Administrador' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Administrador/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Administrador/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VASE'
    ),
    'Resumen_Usuarios' => array(
        'liga' => 'Resumen_Usuarios',
        'icono' => 'fa fa-users',
        'Url' => '/Administrador/Resumen_Usuarios',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Servicio', 'Base/fileUpload', 'Administrador/resumen_usuarios'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VARU'
    )
);
$config['Minutas'] = array(
    'Minuta_Resumen' => array(
        'liga' => 'Resumen de Minutas',
        'icono' => 'fa fa-list-alt',
        'Url' => '/Generales/Minuta_Resumen',
        'css' => array('Base/Base', 'Generales/minuta_resumen'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Fecha', 'Base/fileUpload', 'Base/Tabla', 'Generales/minuta_resumen'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VMR'
    )
);
$config['Archivos y Formatos'] = array(
    'Archivo_Nuevo' => array(
        'liga' => 'Nuevo',
        'icono' => 'fa fa-file-archive-o',
        'Url' => '/Generales/Archivo_Nuevo',
        'css' => array('Base/Base'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Fecha', 'Base/Tabla', 'Base/fileUpload', 'Archivos/archivo_nuevo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VAN'
    ),
    'Archivo_Resumen' => array(
        'liga' => 'Resumen de Archivos',
        'icono' => 'fa fa-list-alt',
        'Url' => '/Generales/Archivo_Resumen',
        'css' => array('Base/Base', 'Base/Socket', 'Generales/minuta_resumen'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-fileUpload/css/fileinput.min',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Tabla', 'Archivos/archivo_resumen'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VAR'
    )
);

$config['Almacen'] = array(
    'Seguimiento-Almacen-Serv' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Almacen/Seguimiento_Servicios',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Almacen/seguimiento_servicios', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VALMSE'
    ),
    'Seguimiento-Almacen' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Almacen/Seguimiento',
        'css' => array('Generales/servicios'),
        'pluginsCss' => array(
            'DataTables/css/data-table'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Almacen/seguimiento'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive'
        ),
        'Permiso' => 'VAS'
    ),
    'Catalogo-Almacenes-Virtuales' => array(
        'liga' => 'Almacenes Virtuales',
        'icono' => '',
        'Url' => '/Almacen/Almacenes',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Fecha', 'Catalogos/almacenes-virtuales'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
        ),
        'Permiso' => 'VCAV'
    ),
    'Catalogo-Lineas-Equipo' => array(
        'liga' => 'Líneas Equipo',
        'icono' => '',
        'Url' => '/Almacen/Lineas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/lineas-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCLE'
    ),
    'Catalogo-Sublineas-Equipo' => array(
        'liga' => 'Sublíneas Equipo',
        'icono' => '',
        'Url' => '/Almacen/Sublineas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/sublineas-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCSE'
    ),
    'Catalogo-Marcas-Equipo' => array(
        'liga' => 'Marcas Equipo',
        'icono' => '',
        'Url' => '/Almacen/Marcas',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/marcas-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCME'
    ),
    'Catalogo-Modelos-Equipo' => array(
        'liga' => 'Modelos Equipo',
        'icono' => '',
        'Url' => '/Almacen/Modelos',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/modelos-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCMOE'
    ),
    'Catalogo-Componentes-Equipo' => array(
        'liga' => 'Componentes Equipo',
        'icono' => '',
        'Url' => '/Almacen/Componentes',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/componentes-equipo'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VCCOE'
    )
);

$config['Redes'] = array(
    'Seguimiento-Redes' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Redes/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Redes/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VRESE'
    )
);

$config['Salas X4D'] = array(
    'Seguimiento-Salas4D' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Salas4D/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit',
            'jstree/dist/themes/default/style.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Salas4D/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min',
            'jstree/dist/jstree.min'
        ),
        'Permiso' => 'VSASE'
    ),
    'Inventario-Salas4D' => array(
        'liga' => 'Inventario',
        'icono' => '',
        'Url' => '/Salas4D/Inventario',
        'css' => array('Generales/imageWithDelete'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Salas4D/inventario'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VIS4D'
    ),
    'Catalogo-Tipos-Sistema-Salas4D' => array(
        'liga' => 'Catalogos',
        'icono' => '',
        'Url' => '/Salas4D/Catalogo_Tipos_Sistema',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jstree/dist/themes/default/style.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/tipos_sistema_salas_x4d'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min',
            'jstree/dist/jstree.min'
        ),
        'Permiso' => 'VSC'
    ),
    'Catalogo-Ubicaciones-4D' => array(
        'liga' => 'Ubicaciones',
        'icono' => '',
        'Url' => '/Salas4D/Catalogo_Ubicaciones',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Catalogos/ubicaciones_x4d'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'masked-input/masked-input.min'
        ),
        'Permiso' => 'VSCU'
    )
);

$config['Laboratorio'] = array(
    'Seguimiento-Laboratorio' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Laboratorio/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Laboratorio/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VLASE'
    )
);

$config['Compras'] = array(
    'Seguimiento-Compras' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Compras/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Compras/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VCOMSE'
    )
);

$config['Contabilidad'] = array(
    'Seguimiento-Contabilidad' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Contabilidad/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Contabilidad/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VCOSE'
    )
);

$config['Mesa de Ayuda'] = array(
    'Seguimiento-Mesa' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/MesaDeAyuda/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'MesaDeAyuda/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VMESE'
    )
);

$config['Admin Proyectos'] = array(
    'Reporte-SAE-Inventarios' => array(
        'liga' => 'Inventarios SAE',
        'icono' => 'fa fa-eye',
        'Url' => '/AdminProyectos/Inventarios',
        'css' => array('Generales/notas'),
        'pluginsCss' => array(
            'DataTables/css/data-table'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'AdminProyectos/SAEReports', 'Base/Nota'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'moment/moment-locales.min',
            'moment/es'
        ),
        'Permiso' => 'VRSAEINV'
    )
);

$config['Sistemas'] = array(
    'Seguimiento-Sistemas' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Sistemas/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Sistemas/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VSISSE'
    )
);

$config['Tesoreria'] = array(
    'Seguimiento-Tesoreria' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Tesoreria/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/fileUpload', 'Base/Select', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Tesoreria/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VTESE'
    ),
    'Facturacion-Tesoreria' => array(
        'liga' => 'Facturacion',
        'icono' => 'fa fa-file-text',
        'Url' => '/Tesoreria/Facturacion',
        'css' => array('Generales/notas', 'Generales/servicios', 'Generales/minuta_resumen'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'select2/dist/css/select2.min'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/fileUpload', 'Base/Tabla', 'Base/Select', 'Base/Servicio', 'Tesoreria/facturacion', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'select2/dist/js/select2.min'
        ),
        'Permiso' => 'VTEFA'
    ),
    'Outsorcing' => array(
        'liga' => 'Outsourcing',
        'icono' => '',
        'Url' => '/Tesoreria/Catalogo_Outsorcing',
        'css' => array('Generales/notas', 'Generales/servicios', 'Generales/minuta_resumen'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'select2/dist/css/select2.min',
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Tabla', 'Tesoreria/catalogo_outsorcing'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'select2/dist/js/select2.min',
        ),
        'Permiso' => 'VTEO'
    )
);

$config['Mercadotecnia'] = array(
    'Seguimiento-Mercadotecnia' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Mercadotecnia/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Mercadotecnia/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VMERCASE'
    )
);

$config['CIMOS'] = array(
    'Seguimiento-Cimos' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Cimos/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Cimos/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VCISE'
    ),
    'Reportes-Cimos' => array(
        'liga' => 'Reportes',
        'icono' => 'fa fa-pie-chart',
        'Url' => '/Cimos/Reportes',
        'css' => array(),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/Botones', 'Cimos/reportes'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VCISE'
    )
);

$config['Reportes SAE'] = array(
    'Compras' => array(
        'liga' => 'Compras',
        'icono' => 'fa fa-search',
        'Url' => '/ReportesSAE/Compras',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Base/Fecha', 'ReportesSAE/compras'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VRSAEC'
    )
);

$config['Reportes Poliza'] = array(
    'Problemas-Faltantes-Mantts' => array(
        'liga' => 'Reportes y Faltantes Mantenimientos',
        'icono' => 'fa fa-search',
        'Url' => '/ReportesPoliza/Problemas_Faltantes_Manttos',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Tabla', 'Base/Select', 'Base/Fecha', 'Poliza/reportes_problemas_faltantes_manttos'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VRSAEC'
    )
);

$config['Métodos y Procedimientos'] = array(
    'Seguimiento-Metodos-Procedimientos' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/MetodosProcedimientos/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/fileUpload', 'Base/Select', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'MetodosProcedimientos/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VPMP'
    )
);

$config['Facturación y Cobranza'] = array(
    'Seguimiento-Facturacion-Cobranza' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/FacturacionCobranza/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/fileUpload', 'Base/Select', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'FacturacionCobranza/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VFCSE'
    )
);

$config['Gapsi'] = array(
    'Solicitud-Gasto' => array(
        'liga' => 'Solicitar Gasto',
        'icono' => '',
        'Url' => '/Gapsi/Solicitar-Gasto',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/fileUpload', 'Gapsi/solicitar-gasto'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
        ),
        'Permiso' => 'VSGGAP'
    ),
    'Mis-Gastos' => array(
        'liga' => 'Mis Gastos',
        'icono' => '',
        'Url' => '/Gapsi/Mis-Gastos',
        'css' => array(),
        'pluginsCss' => array(
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'parsley/src/parsley'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/Tabla', 'Base/fileUpload', 'Gapsi/mis-gastos'),
        'pluginsJs' => array(
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'select2/dist/js/select2.min',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'moment/moment-locales.min',
            'moment/es',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
        ),
        'Permiso' => 'VSGGAP'
    )
);

$config['Proveedores'] = array(
    'Seguimiento-Proveedores' => array(
        'liga' => 'Seguimiento',
        'icono' => 'fa fa-eye',
        'Url' => '/Proveedores/Seguimiento',
        'css' => array('Generales/notas', 'Generales/servicios'),
        'pluginsCss' => array(
            'bootstrap-datepicker/css/datepicker',
            'bootstrap-datepicker/css/datepicker3',
            'DataTables/css/data-table',
            'select2/dist/css/select2.min',
            'jquery-tag-it/css/jquery.tagit',
            'parsley/src/parsley',
            'jquery-fileUpload/css/fileinput.min',
            'bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'DrawingBoard/css/drawingboard.min',
            'jquery-tag-it/css/jquery.tagit'
        ),
        'js' => array('Base/Base', 'Base/Socket', 'Base/Select', 'Base/fileUpload', 'Base/Servicio', 'Base/Tabla', 'Base/Botones', 'Proveedores/seguimiento', 'Base/Nota'),
        'pluginsJs' => array(
            'bootstrap-datepicker/js/bootstrap-datepicker',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es',
            'DataTables/js/jquery.dataTables',
            'DataTables/js/dataTables.responsive',
            'jquery-tag-it/js/tag-it.min',
            'parsley/dist/parsley',
            'parsley/src/i18n/es',
            'jquery-fileUpload/js/fileinput',
            'jquery-fileUpload/js/es',
            'moment/moment-locales.min',
            'moment/es',
            'select2/dist/js/select2.min',
            'bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min',
            'DrawingBoard/js/drawingboard.min',
            'masked-input/masked-input.min',
            'jquery-tag-it/js/tag-it.min'
        ),
        'Permiso' => 'VPROSE'
    )
);
