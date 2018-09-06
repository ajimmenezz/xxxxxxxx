<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>

        <meta charset="utf-8" />
        <title>ADIST | <?php echo $title; ?> </title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="shortcut icon" href="/assets/img/favicon.ico">

        <!-- ================== COMIENZA BASE DE ESTILOS CSS  ================== -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/assets/css/animate.min.css" rel="stylesheet" />
        <link href="/assets/css/style.min.css" rel="stylesheet" />
        <link href="/assets/css/style-responsive.min.css" rel="stylesheet" />
        <link href="/assets/css/theme/default.css" rel="stylesheet" id="theme" />
        <!-- ================== FINALIZA BASE DE ESTILOS CSS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS CSS DE LA PAGINA================== -->
        <!-- ================== FINALIZANDO ARCHIVOS CSS DE LA PAGINA ================== -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script src="/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
                <script src="/assets/crossbrowserjs/html5shiv.js"></script>
                <script src="/assets/crossbrowserjs/respond.min.js"></script>
                <script src="/assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]-->
        <script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== COMIENZA BASE JS ================== -->
        <script src="/assets/plugins/pace/pace.min.js"></script>
        <!-- ================== FINALIZANDO BASE JS ================== -->

        <!-- ================== EMPEZANDO ARCHIVOS JS DE LA PAGINA================== -->
        <script src="/assets/plugins/parsley/dist/parsley.js"></script>
        <script src="/assets/plugins/parsley/src/i18n/es.js"></script>
        <script src="/assets/js/apps.min.js"></script>        
        <script src="/assets/js/customize/Base/Base.js"></script>
        <script src="/assets/js/customize/Acceso/login.js"></script>

        <!-- ================== FINALIZANDO ARCHIVOS JS DE LA PAGINA ================== -->

    </head>
    <body class="pace-top bg-white">
        <!-- empezando #page-loader -->
        <div id="page-loader" class="fade in"><span class="spinner"></span></div>
        <!-- finalizando #page-loader -->

        <!-- Empezando pagina-contenido -->
        <div id="page-container" class="fade">
            <!-- Empezando login -->
            <div class="login login-with-news-feed">
                <!-- Empezando contenido-izquierdo -->
                <div class="news-feed">
                    <div class="news-image">
                        <img src="/assets/img/login-bg/bg-7.jpg" data-id="login-cover-image" alt="" />
                    </div>
                    <div class="news-caption">
                        <h4 class="caption-title"><i class="fa fa-cube text-success"></i> Adiministrador integral de servicios tecnologicos.</h4>
                    </div>
                </div>
                <!-- Finalizando contenido-izquierdo -->

                <!-- Empezando contendido-derecho -->
                <div class="right-content">
                    <!-- Empezando login-cabecera -->
                    <div class="login-header">
                        <div class="brand">
                            <span class="fa fa-desktop"></span> <span class="adist">A D I S T</span>
                            <small>Adiministrador integral de servicios tecnologicos</small>
                        </div>
                        <div class="icon">
                            <i class="fa fa-sign-in"></i>
                        </div>
                    </div>
                    <!-- Finalizando login-cabecera -->

                    <!-- Empezando login-contenido -->
                    <div class="login-content">
                        <!-- Contenido para accesar al sistema -->
                        <form class="margin-bottom-0" id="ingreso" data-parsley-validate="true">
                            <div class="form-group m-b-15">
                                <input id="inputUsuario" type="text" class="form-control input-lg" placeholder="Usuario" data-parsley-required="true"/>
                            </div>
                            <div class="form-group m-b-15">
                                <input id="inputPassword" type="password" class="form-control input-lg" placeholder="Password" data-parsley-required="true"/>
                            </div>
                            <div class="login-buttons">                               
                                <button id="btnIngresar" type="button" class="btn btn-success btn-block btn-lg" >Entrar</button>
                            </div>
                            <div class="m-t-20 m-b-40 p-b-40">
                                No recuerdo mi contraseña? Click <a id="resetearPws" href="javascript:;" class="text-success">aqui</a> para recuperar.
                            </div>
                            <hr />
                            <p class="text-center text-inverse">
                                &copy; Siccob All Right Reserved 2015
                            </p>
                        </form>
                        <!-- Fin de contenido para accesar al sistema -->
                    </div>
                    <!-- Finalizando login-contenido -->
                </div>
                <!-- Finalizando contendido-derecho -->
            </div>
            <!-- Finalizando login -->
        </div>
        <!-- Finalizando pagina-contenido -->

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

