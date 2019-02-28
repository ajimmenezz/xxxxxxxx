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
        <link rel="shortcut icon" href="/assets/img/favicon.ico">

        <!-- ================== COMIENZA BASE DE ESTILOS CSS  ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">        
        <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/assets/plugins/lightbox/css/lightbox.css" rel="stylesheet" />
        <link href="/assets/css/animate.min.css" rel="stylesheet" />
        <link href="/assets/css/style.min.css" rel="stylesheet" />
        <link href="/assets/css/style-responsive.min.css" rel="stylesheet" />
        <link href="/assets/css/theme/default.css" rel="stylesheet" id="theme" />

        <!-- ================== FINALIZA BASE DE ESTILOS CSS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS CSS DE LA PAGINA================== -->
        <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />

        <?php
        $pluginsCss = null;
        $personalCss = null;
        $pluginsJs = null;
        $personalJs = null;
        $rand = rand(0, 32767);


        foreach ($menu['Menu'] as $elemntos) {
            foreach ($elemntos as $key => $modulo) {
                foreach ($modulo as $indice) {
                    foreach ($indice as $pagina => $datosLibrerias) {
                        if ($pagina == $librerias) {
                            if (!empty($datosLibrerias['pluginsCss'])) {
                                foreach ($datosLibrerias['pluginsCss'] as $plugin) {
                                    $pluginsCss .= '<link href="/assets/plugins/' . $plugin . '.css?r=' . $rand . '" rel="stylesheet"/>';
                                }
                            }
                            if (!empty($datosLibrerias['css'])) {
                                foreach ($datosLibrerias['css'] as $css) {
                                    $personalCss .= '<link href="/assets/css/customize/' . $css . '.css?r=' . $rand . '" rel="stylesheet"/>';
                                }
                            }
                            if (!empty($datosLibrerias['pluginsJs'])) {
                                foreach ($datosLibrerias['pluginsJs'] as $pluginJs) {
                                    $pluginsJs .= '<script src="/assets/plugins/' . $pluginJs . '.js?r=' . $rand . '"></script>';
                                }
                            }
                            if (!empty($datosLibrerias['js'])) {
                                foreach ($datosLibrerias['js'] as $js) {
                                    $personalJs .= '<script src="/assets/js/customize/' . $js . '.js?r=' . $rand . '"></script>';
                                }
                            }
                        }
                    }
                }
            }
        }
        echo $pluginsCss;
        ?>
        <link href="/assets/css/customize/Base/Base.css" rel="stylesheet" id="theme" />
        <?php
        echo $personalCss;
        ?>
        <!-- ================== FINALIZANDO ARCHIVOS CSS DE LA PAGINA ================== -->

        <script src="/assets/js/customize/Calendar/catalogo_calendar.js"></script>
        <script src="https://apis.google.com/js/api.js"></script>

        <!-- ================== EMPEZANDO Google Charts ================= -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- ================== FINALIZANDO Google Charts ================= -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script src="/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
        <!--<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>-->
        <!--[if lt IE 9]>
                <script src="assets/crossbrowserjs/html5shiv.js"></script>
                <script src="assets/crossbrowserjs/respond.min.js"></script>
                <script src="assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]-->
        <script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="/assets/plugins/lightbox/js/lightbox-2.6.min.js"></script>
        <script src="/assets/plugins/pace/pace.min.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS JS DE LA PAGINA================== -->
        <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
        <?php
        echo $pluginsJs;
        ?>
        <script src="/assets/js/apps.min.js"></script>       
<!--        <script src="/assets/js/customize/Base/Base.js"></script>       
        <script src="/assets/js/customize/Base/Socket.js"></script>       -->
        <?php
        echo $personalJs;
        ?>

        <!-- ================== FINALIZANDO ARCHIVOS JS DE LA PAGINA ================== -->

        <!-- ================== EMPEZANDO SMART LOOK================== -->

        <script type="text/javascript">
//            window.smartlook || (function (d) {
//                var o = smartlook = function () {
//                    o.api.push(arguments)
//                }, h = d.getElementsByTagName('head')[0];
//                var c = d.createElement('script');
//                o.api = new Array();
//                c.async = true;
//                c.type = 'text/javascript';
//                c.charset = 'utf-8';
//                c.src = '//rec.smartlook.com/recorder.js';
//                h.appendChild(c);
//            })(document);
//            smartlook('init', 'b2ff341c34242150ed3e8ffe2249aa6c666dcc4b');
        </script>

        <!-- ================== FINALIZANDO SMART LOOK ================== -->  


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
                        <li id="notificaciones-cabecera" class="dropdown <?php
                        if ($menu['Area'] === '0') {
                            echo 'hidden';
                        }
                        ?>" >
                                <?php
                                $cantidad = null;
                                $tickets = null;
                                $contador = 1;
                                foreach ($notificaciones as $key => $value) {
                                    if (!array_key_exists('cantidad', $value)) {
                                        if ($contador <= 5) {
                                            $cantidad = $contador;
                                            $tickets .= ' <li class="media">
                                                        <a href="/Generales/Notificaciones">
                                                            <div class="media-left"><i class="fa fa-envelope media-object bg-blue-lighter"></i></div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading">' . $value['Tipo'] . '</h6>
                                                            <div class="text-muted f-s-11">
                                                                    <p>' . $value['Departamento'] . '</p>
                                                                    <p>' . $value['Fecha'] . '</p>
                                                            </div>
                                                            </div>
                                                        </a>
                                                    </li>';
                                        }
                                    }
                                    $contador++;
                                }
                                ?>
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                                <i class="fa fa-bell-o"></i>
                                <?php
                                if (!empty($cantidad)) {
                                    echo '<span class="label">' . $cantidad . '</span>';
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                                <?php
                                if (!empty($cantidad)) {
                                    echo '<li class="dropdown-header">Notificaciones (' . $cantidad . ')</li>';
                                } else {
                                    echo '<li class="dropdown-header">Sin Notificaciones </li>';
                                }
                                ?>                                    
                                <?php echo $tickets; ?>
                                <li class="dropdown-footer text-center">
                                    <a href="/Generales/Notificaciones">Ver mas</a>
                                </li>
                            </ul>
                        </li>
                        <!--Finalizando Notificaciones Tickets-->                                               


                        <?php
                        $permisosCompletosTodosServicios = FALSE;
                        if (in_array('209', $usuario['PermisosAdicionales'])) {
                            $permisosCompletosTodosServicios = TRUE;
                        } else if (in_array('209', $usuario['Permisos'])) {
                            $permisosCompletosTodosServicios = TRUE;
                        }
                        ?>
                        <!--Empezando Seccion del usuario-->
                        <li class = "dropdown navbar-user">
                            <a href = "javascript:;" class = "dropdown-toggle" data-toggle = "dropdown">
                                <?php (empty($datosUsuario['UrlFoto'])) ? $foto = '/assets/img/user-13.jpg' : $foto = $datosUsuario['UrlFoto']; ?>
                                <img src="<?php echo $foto; ?>" alt="" />
                                <span class="hidden-xs"><?php echo $usuario['Nombre']; ?></span> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <li class="arrow"></li>
                                <li><a href="/Configuracion/Perfil">Editar Perfil</a></li>
                                <li><a href="javascript:;">Reportar Sistemas</a></li>
                                <?php
                                if ($permisosCompletosTodosServicios) {
                                    ?>
                                    <li class="divider"></li>
                                    <li><a id="btnInformacionSD" href="javascript:;" >Información SD</a></li>
                                    <li><a id="btnAgregarVueltaCorrectivo" href="javascript:;" >Agregar Vuelta Correctivo</a></li>
                                    <li><a id="btnAgregarVueltaMantenimiento" href="javascript:;" >Agregar Vuelta Mantenimiento</a></li>
                                    <li><a id="btnCrearPDFVueltaMantenimiento" href="javascript:;" >Crear PDF Vuelta Mantenimiento</a></li>
                                <?php } ?>
                                <li class="divider"></li>
                                <li><a href="javascript:;" id="cerrar-sesion" >Cerrar Sesión</a></li>
                            </ul>
                        </li>
                        <!-- Finalizando Seccion del usuario-->

                        <!--Empezando icono de ayuda-->
                        <li>
                            <a id="btnAyudaSistema" href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-20">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <!--se eliminio barra de buquedea ya por el momento no se utiliza-->
                        </li>
                        <!--Finalizando icono de ayuda-->
                    </ul>
                    <!-- Finalizando cabecera de navegacion derecha -->
                </div>
                <!-- Finalizando contenedor-fluido -->
            </div>
            <!-- Finalizando cabezera -->