<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>ADIST | <?php echo $titulo; ?></title>
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
                <script src="assets/crossbrowserjs/html5shiv.js"></script>
                <script src="assets/crossbrowserjs/respond.min.js"></script>
                <script src="assets/crossbrowserjs/excanvas.min.js"></script>
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
        <script src="/assets/js/customize/Acceso/Nuevo_Password.js"></script>
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
                        <!-- Contenido para recuperar password y usuario -->
                        <form class="margin-bottom-0" id="formNuevoPsw" data-parsley-validate="true">
                            <label class="control-label f-s-18 f-w-400">Definir nuevo Password</label>
                            <div class="checkbox m-b-15"></div>
                            <label class="control-label">
                                El usuario que tienes es: <span class="user f-w-700"> <?php echo $datos['Usuario']; ?></span>
                                <input type="hidden" value="<?php echo $datos['Usuario']; ?>" id="usuario"/>
                                <input type="hidden" value="<?php echo $datos['IdPsw']; ?>" id="pswId"/>
                            </label>
                            <div class="checkbox m-b-15"></div>
                            <label class="control-label">
                                Favor de definir un nuevo password
                            </label>
                            <div class="checkbox m-b-15"></div>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="Nuevo Password" id="inputNuevoPsw" data-parsley-required="true" data-parsley-minlength="8" data-parsley-maxlength="15"/>
                                </div>
                            </div>
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" placeholder="Confirmar Password" id="inputConfirmaNuevoPsw" data-parsley-required="true" data-parsley-equalto="#inputNuevoPsw"/>
                                </div>
                            </div>
                            <div class="row login-buttons">
                                <div class="col-md-12">
                                    <div class="checkbox" id="alertRecuperar">
                                        <!--muestra el mensaje de error para recuperar contraseña-->
                                        <label class="alert hidden" role="alert"></label>
                                    </div>
                                </div>
                            </div>
                            <!--Empezando mensaje-->
                            <div class="row m-b-15">
                                <div class="col-md-12 m-t-20">
                                    <div class="alert alert-warning fade in m-b-15">                            
                                        Para definir password debe complir con los siguientes puntos:
                                        <ul>
                                            <li>una mayuscula</li>
                                            <li>una minuscula</li>
                                            <li>un numero</li>
                                            <li>la longitud minuma 8 y maxima 15</li>
                                        </ul>                          
                                    </div>                        
                                </div>
                            </div>
                            <!--Finalizando mensaje-->
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-success" id="btnGuardar" >Guardar</button>
                                    <button type="button" class="btn btn-success" id="btnlimpiar" >Limpiar</button>
                                </div>
                            </div>
                            <hr />
                            <p class="text-center text-inverse">
                                &copy; Siccob All Right Reserved 2015
                            </p>
                        </form>
                        <!-- Fin de contenido para recuperar password y usuario -->
                    </div>
                    <!-- Finalizando login-contenido -->
                </div>
                <!-- Finalizando contendido-derecho -->
            </div>
            <!-- Finalizando login -->
        </div>
        <!-- Finalizando pagina-contenido -->

        <!--cuadro de dialogo-->

        <!--fin de cuadro de dialogo-->

    </body>
</html>

