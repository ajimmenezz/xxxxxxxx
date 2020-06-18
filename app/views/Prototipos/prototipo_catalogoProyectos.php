<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>ADIST | <?php echo $title ?></title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- ================== COMIENZA BASE DE ESTILOS CSS  ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="assets/css/animate.min.css" rel="stylesheet" />
        <link href="assets/css/style.min.css" rel="stylesheet" />
        <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
        <link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
        <!-- ================== FINALIZA BASE DE ESTILOS CSS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS CSS DE LA PAGINA================== -->
        <link href="assets/plugins/DataTables/css/data-table.css" rel="stylesheet" />
        <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
        <link href="assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
        <link href="assets/css/customize/prototipo_catalogoProyectos.css" rel="stylesheet"/>
        <!-- ================== FINALIZANDO ARCHIVOS CSS DE LA PAGINA ================== -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
        <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
                <script src="assets/crossbrowserjs/html5shiv.js"></script>
                <script src="assets/crossbrowserjs/respond.min.js"></script>
                <script src="assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]-->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="assets/plugins/pace/pace.min.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS JS DE LA PAGINA================== -->        
        <script src="assets/plugins/DataTables/js/jquery.dataTables.js"></script>
        <script src="assets/plugins/DataTables/js/dataTables.responsive.js"></script>
        <script src="assets/plugins/select2/dist/js/select2.min.js"></script>
        <script src="assets/plugins/parsley/dist/parsley.js"></script>
        <script src="assets/plugins/parsley/src/i18n/es.js"></script>
        <script src="assets/js/apps.min.js"></script>
        <script src="assets/js/customize/prototipo_catalogoProyectos.js"></script>
        <!-- ================== FINALIZANDO ARCHIVOS JS DE LA PAGINA ================== -->

    </head>
    <body class="pace-top">
        <!-- Empezando #page-loader -->
        <div id="page-loader" class="fade in"><span class="spinner"></span></div>
        <!-- Finalizando #page-loader -->
        <!-- Empezando pagina-contenedor -->
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
            <!-- Empezando Cabezera -->
            <div id="header" class="header navbar navbar-default navbar-fixed-top">
                <!-- Empezando contenedor-fluido -->
                <div class="container-fluid">
                    <!-- Empezando mobile sidebar expand / collapse button -->
                    <div class="navbar-header">
                        <a href="prototipo_nuevo" class="navbar-brand"><span class="fa fa-desktop"></span> ADIST</a>
                        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!-- Finalizando mobile sidebar expand / collapse button -->

                    <!-- Empezando cabecera de navegacion derecha -->
                    <ul class="nav navbar-nav navbar-right">
                        <!--Empezando barra de busqueda-->
                        <li>
                            <!--se eliminio barra de buquedea ya por el momento no se utiliza-->
                        </li>
                        <!--Finalizando barra de busqueda-->

                        <!--Empezando Notificaciones Tickets-->
                        <li class="dropdown">
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                                <i class="fa fa-bell-o"></i>
                                <span class="label bg-blue-lighter">5</span>
                            </a>
                            <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                                <li class="dropdown-header">Notificaciones (5)</li>
                                <li class="media">
                                    <a href="javascript:;">
                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Ticket Nuevo</h6>
                                            <div class="text-muted f-s-11"># 123456</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="media">
                                    <a href="javascript:;">
                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Ticket Nuevo</h6>
                                            <div class="text-muted f-s-11"># 3218796</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="media">
                                    <a href="javascript:;">
                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Ticket Nuevo</h6>
                                            <div class="text-muted f-s-11"># 4568153</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="media">
                                    <a href="javascript:;">
                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Ticket Nuevo</h6>
                                            <div class="text-muted f-s-11"># 7891532</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="media">
                                    <a href="javascript:;">
                                        <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Ticket Nuevo</h6>
                                            <div class="text-muted f-s-11"># 123987</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-footer text-center">
                                    <a href="javascript:;">Ver mas</a>
                                </li>
                            </ul>
                        </li>
                        <!--Finalizando Notificaciones Tickets-->

                        <!--Empezando Seccion del usuario-->
                        <li class="dropdown navbar-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="assets/img/user-13.jpg" alt="" />
                                <span class="hidden-xs">Administrador</span> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <li class="arrow"></li>
                                <li><a href="javascript:;">Editar Perfil</a></li>
                                <li><a href="javascript:;">Reportar Sistemas</a></li>
                                <li class="divider"></li>
                                <li><a href="prototipo_login.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                        <!-- Finalizando Seccion del usuario-->
                    </ul>
                    <!-- Finalizando cabecera de navegacion derecha -->
                </div>
                <!-- Finalizando contenedor-fluido -->
            </div>
            <!-- Finalizando cabezera -->

            <!-- Empezando #sidebar -->
            <div id="sidebar" class="sidebar">
                <!-- Empezando sidebar scrollbar -->
                <div data-scrollbar="true" data-height="100%">
                    <!-- Empezando sidebar usuario -->
                    <ul class="nav">
                        <li class="nav-profile">
                            <div class="image">
                                <a href="javascript:;"><img src="assets/img/user-13.jpg" alt="" /></a>
                            </div>
                            <div class="info">
                                Administrador
                                <small class="text-center m-t-10"><br />Miercoles, 28 de Octubre del 2015<br /><span id="hora">Hora : 18:11:19 pm</span></small>
                            </div>
                        </li>
                    </ul>
                    <!-- Finalizando sidebar usuario -->

                    <!-- Empezando sidebar menu nav -->
                    <ul class="nav" id="menuPrincipal">
                        <!-- Empezando sidebar boton oculatar menu nav -->
                        <li id="hideMenu"><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
                        <!-- Finalizando sidebar boton oculatar menu nav -->

                        <!-- Link para Dashboard -->
                        <li class="has-sub">
                            <a href="javascript:;">
                                <i class="fa fa-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <!-- Finalizando Link para Dashboard -->

                        <!--Empezando titulo del menu nav-->
                        <li class="nav-header">Menu Principal</li>
                        <!--Finalizando titulo del menu nav-->

                        <!--Empezando opciones del menu nav-->
                        <!--aqui se mostraran la ocpines del menu principal segun el perfil que tengan-->
                        <li class="has-sub">
                            <a href="javascript:;">
                                <span class="badge pull-right">10</span>
                                <i class="fa fa-bell"></i> 
                                <span>Notificaciones</span>
                            </a>
                        </li>
                        <li class="has-sub ">
                            <a href="prototipo_solicitud"> 
                                <i class="fa fa-file-text-o"></i>
                                <span>Solicitud</span>
                            </a>      
                        </li>
                        <li class="has-sub ">
                            <a href="prototipo_nuevo"> 
                                <i class="fa fa-building"></i>
                                <span>Nuevo</span>
                            </a>      
                        </li>
                        <li class="has-sub ">
                            <a href="prototipo_plantilla"> 
                                <i class="fa fa-files-o"></i>
                                <span>Plantillas</span>
                            </a>      
                        </li>
                        <li class="has-sub">
                            <a href="javascript:;"> 
                                <i class="fa fa-eye"></i>
                                <span>Seguimiento</span>
                            </a>      
                        </li>
                        <li class="has-sub">
                            <a href="javascript:;"> 
                                <i class="fa fa-bar-chart"></i>
                                <span>Gantt</span>
                            </a>      
                        </li>
                        <li class="has-sub">
                            <a href="javascript:;"> 
                                <i class="fa fa-check-square-o"></i>
                                <span>Concluidos</span>
                            </a>      
                        </li>                       
                        <li class="has-sub">
                            <a href="javascript:;"> 
                                <i class="fa fa-pencil-square-o"></i>
                                <span>Reportes</span>
                            </a>      
                        </li>
                        <li class="has-sub active">
                            <a href="prototipo_catalogoProyectos">                                 
                                <i class="fa fa-database"></i>
                                <span>Catálogos</span>
                            </a>
                        </li>
                        <li class="has-sub">
                            <a href="prototipo_catalogoCompras">                                 
                                <i class="fa fa-database"></i>
                                <span>Catálogos compras</span>
                            </a>
                        </li>
                        <li class="has-sub">
                            <a href="prototipo_catalogoVentas">                                 
                                <i class="fa fa-database"></i>
                                <span>Catálogos ventas</span>
                            </a>
                        </li>
                        <!--Finalizando opciones del menu nav-->
                    </ul>
                    <!-- Finalizando sidebar menu nav -->
                </div>
                <!-- Finalizando sidebar scrollbar -->
            </div>
            <div class="sidebar-bg"></div>
            <!-- Finalizando #sidebar -->

            <!-- Empezando #contenido -->
            <div id="content" class="content">
                <!-- Empezando titulo de la pagina -->
                <h1 class="page-header">Catálogos <small>de proyectos</small></h1>
                <!-- Finalizando titulo de la pagina -->

                <!-- Empezando panel catálogo de sistemas especiales -->
                <div class="panel panel-inverse">
                    <!--Empezando cabecera del panel-->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
                        </div>
                        <h4 class="panel-title">Sistemas Especiales</h4>
                    </div>
                    <!--Finalizando cabecera del panel-->
                    <!--Empezando cuerpo del panel-->
                    <div class="panel-body">
                        <!--Inicia formulario para catálogo de sistema especial-->
                        <form action="/" method="POST">
                            <fieldset>                                                                                                                                                                                        
                                <!--Empezando campos fila 1 -->
                                <div class="row m-t-10">                                  
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="nombreSistema">Nombre</label>
                                            <div class="form-inline">
                                                <input type="text" class="form-control" id="inputNombreSistemaEspecial" placeholder="Ingresa el nuevo sistema" style="width: 60%"/>
                                                <a href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Empezando Separador-->
                                    <div class="col-md-12">
                                        <div class="underline m-b-15 m-t-15"></div>
                                    </div>
                                    <!--Finalizando Separador-->
                                </div>
                                <!--Finalizando campos fila 1-->                               
                                <!--Empezando tabla fila 2 -->
                                <div class="row"> 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <table id="data-table-sistemasEspeciales" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="all">Nombre</th>
                                                        <th class="all">Estatus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Redes</td>
                                                        <td>Activo</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Canalizaciones</td>
                                                        <td>Activo</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>    
                                    </div> 
                                </div>
                                <!--Finalizando tabla fila 2-->
                            </fieldset>
                        </form>
                        <!-- Finaliza formulario para catálogo de sistema especial-->
                    </div>
                    <!--Finalizando cuerpo del panel-->
                </div>
                <!-- Finalizando panel catálogo de sistemas especiales -->

                <!-- Empezando panel tareas de proyectos-->
                <div class="panel panel-inverse">
                    <!--Empezando cabecera del panel-->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>                            
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>                            
                        </div>
                        <h4 class="panel-title">Tareas de proyectos</h4>
                    </div>
                    <!--Finalizando cabecera del panel-->
                    <!--Empezando cuerpo del panel-->
                    <div class="panel-body">
                        <!--Empezando fila 1-->
                        <div class="row m-t-10">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="selectSistemaEspecial">Sistema Especial</label>
                                    <select id="selectSistemaEspecial" class="form-control" style="width: 100%" required>
                                        <option value="">Seleccionar</option>
                                    </select>   
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="nombreTareaProyecto">Nombre</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" id="inputNombreTareaProyecto" placeholder="Ingresa el nueva tarea" style="width: 60%"/>
                                        <a href="javascript:;" class="btn btn-success m-r-5 "><i class="fa fa-plus"></i> Agregar</a>
                                    </div>
                                </div>
                            </div>
                            <!--Empezando Separador-->
                            <div class="col-md-12">
                                <div class="underline m-b-15 m-t-15"></div>
                            </div>
                            <!--Finalizando Separador-->
                        </div>
                        <!--Finalizando fila 1-->
                        <!--Empezando tabla fila 2-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <table id="data-table-tareaSistemaEspecial" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="all">Nombre</th>
                                                <th class="all">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tendido Cableado Datos Lobby</td>
                                                <td>Activo</td>
                                            </tr>
                                            <tr>
                                                <td>Instalación de sensores de incendio y bocinas en salas</td>
                                                <td>Activo</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>    
                            </div> 
                        </div>
                        <!--Finalizando tabla fila 2-->
                    </div>
                    <!--Finalizando cuerpo del panel-->
                </div>
                <!-- Finalizando panel tareas de proyectos -->

            </div>
            <!-- Finalizando #contenido -->

            <!-- Empezando boton scroll para regresar a la parete superior de la pagina -->
            <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
            <!-- Fin de  boton scroll -->
        </div>
        <!-- Finalizando pagina-contenedor -->

        <!--cuadro de dialogo-->
        <div class="modal modal-message fade" id="modal-dialogo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal" id="btnModalAbortar">Cancelar</a>
                        <a href="javascript:;" class="btn btn-sm btn-primary" id="btnModalConfirmar">Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
        <!--fin de cuadro de dialogo-->
    </body>
</html>

