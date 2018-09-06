<!--
 * Description: Vista de los detalles del servicio
 *
 * @author: Alberto Barcenas
 *
-->
<html>
    <head>
        <meta charset="utf-8" />
        <title>ADIST | Detalles del Servicio</title>
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
        <link href="/assets/css/customize/Generales/buscar.css" rel="stylesheet" id="theme" />
        <link href="/assets/css/customize/Generales/notas.css" rel="stylesheet" id="theme" />
        <link href="/assets/css/customize/Generales/servicios.css" rel="stylesheet" id="theme" />
        <link href="/assets/css/customize/Generales/imageWithDelete.css" rel="stylesheet" id="theme" />
        <style>
            .timeline-icon > a > i{
                padding-top: 12px !important;
            }
        </style>
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

    <body>
        <!-- begin #page-loader -->
        <!--<div id="page-loader" class="fade in"><span class="spinner"></span></div>-->
        <!-- end #page-loader -->

        <!-- begin #page-container -->
        <div id="page-container" class="fade page-without-sidebar">

            <!-- begin #content -->
            <div id="content" class="content">
                <!-- begin page-header -->
                <h1 class="page-header">Detalles del Servicio <?php echo $idServicio ?></h1>
                <!-- end page-header -->
                <!-- Empezando panel detalles-->
                <div id="seccion-detalles" class="panel panel-inverse borde-sombra panel-with-tabs">
                    <!--Empezando cabecera del panel-->
                    <div class="panel-heading">
                        <div class="btn-group pull-right" data-toggle="buttons"></div>
                        <div class="tab-overflow overflow-right">
                            <ul class="nav nav-tabs nav-tabs-inverse">                    
                                <li><a href="#Solicitud" data-toggle="tab">Solicitud</a></li>                    
                                <li class="active"><a href="#Servicio" data-toggle="tab">Servicio</a></li>                    
                                <li><a href="#Historial" data-toggle="tab">Historial</a></li>                    
                                <li><a href="#Conversacion" data-toggle="tab">Conversación</a></li>                    
                            </ul>
                        </div>            
                    </div>
                    <!--Finalizando cabecera del panel-->
                    <div class="tab-content">

                        <!--Empezando la seccion de solicitud-->
                        <div class="tab-pane fade" id="Solicitud">
                            <div class="panel-body" id="panel-detalles-solicitud">
                                <div class="row">
                                    <div class="col-md-12">                        
                                        <div class="form-group">
                                            <h3 class="m-t-10">Detalles de la solicitud <?php echo $detalles['Id']; ?></h3>
                                            <div class="underline m-b-15 m-t-15"></div>
                                        </div>    
                                    </div> 
                                </div>
                                <div class="row m-t-15">
                                    <div class="col-md-2 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13"># Solicitud:</label>
                                            <input type="text" value="<?php echo $detalles['Id']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-6">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13"># Ticket:</label>
                                            <input type="text" value="<?php echo $detalles['Ticket']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Solicita:</label>
                                            <input type="text" value="<?php echo $detalles['SolicitaString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Departamento:</label>                    
                                            <input type="text" value="<?php echo $detalles['DepartamentoString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Prioridad:</label>
                                            <input type="text" value="<?php echo $detalles['PrioridadString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Atiende:</label>
                                            <input type="text" value="<?php echo $detalles['AtiendeString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Estatus:</label>
                                            <input type="text" value="<?php echo $detalles['EstatusString']; ?>" class="form-control f-w-600 text-center f-s-15" disabled="disabled" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Fecha:</label>
                                            <div class='input-group' id='fechaSolicitud'>
                                                <?php
                                                $fecha = ($detalles['FechaCreacion'] != '') ? substr($detalles['FechaCreacion'], 0, 10) : '';
                                                ?>
                                                <input type='date' id="txtFechaSolicitud" class="form-control f-w-600 f-s-15" value="<?php echo $fecha; ?>" disabled="disabled" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11 col-sm-11 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Asunto:</label>                    
                                            <input type="text" id="txtAsuntoSolicitud" class="form-control f-w-500 f-s-15"  value="<?php echo $detalles['Asunto']; ?>" disabled="disabled"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11 col-sm-11 col-xs-12">
                                        <div class="form-group">
                                            <label class="f-w-700 f-s-13">Descripción de solicitud:</label>                    
                                            <textarea id="txtDescripcionSolicitud" class="form-control f-s-15" placeholder="Descripción de la solicitud" rows="8" disabled="disabled"><?php echo $detalles['Descripcion']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (array_key_exists("Evidencias", $detalles) && $detalles['Evidencias'] != '') {
                                    ?>

                                    <div class="row">
                                        <div class="col-md-11 col-sm-11 col-xs-12">
                                            <div class="form-group">
                                                <label class="f-w-700 f-s-13">Archivos adjuntos:</label>                                            
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">                                                          
                                                        <?php
                                                        $evidencias = explode(",", $detalles['Evidencias']);
                                                        foreach ($evidencias as $key => $value) {
                                                            echo '<div class="thumbnail-pic m-5 p-5">';
                                                            $ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                                                            switch ($ext) {
                                                                case 'png': case 'jpeg': case 'jpg': case 'gif':
                                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="' . $value . '" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                    break;
                                                                case 'xls': case 'xlsx':
                                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/excel_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                    break;
                                                                case 'doc': case 'docx':
                                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/word_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                    break;
                                                                case 'pdf':
                                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/pdf_icon.png" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                    break;
                                                                default :
                                                                    echo '<a class="imagenesSolicitud" target="_blank" href="' . $value . '"><img src="/assets/img/Iconos/no-thumbnail.jpg" class="img-responsive img-thumbnail" style="max-height:160px !important;" alt="Evidencia" /></a>';
                                                                    break;
                                                            }
                                                            echo '</div>';
                                                        }
                                                        ?>                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <!--Finalizando la seccion de solicitud-->

                        <!--Empezando la seccion de servicio-->
                        <div class="tab-pane fade active in" id="Servicio">
                            <div class="panel-body" id="panel-detalles-servicio">
                                <?php echo $servicio; ?>
                            </div>
                        </div>
                        <!--Finalizando la seccion de servicio-->

                        <!--Empezando la seccion de historial-->
                        <div class="tab-pane fade" id="Historial">
                            <div class="panel-body" id="panel-historial-servicio">
                                <?php echo $historial; ?>
                            </div>
                        </div>
                        <!--Finalizando la seccion de servicio-->

                        <!--Empezando la seccion de servicio-->
                        <div class="tab-pane fade" id="Conversacion">
                            <div class="panel-body" id="panel-conversacion-servicio">
                                <?php echo $conversacion; ?>
                            </div>
                        </div>
                        <!--Finalizando la seccion de servicio-->
                    </div>

                </div>
                <!-- Finalizando detalles -->

            </div>
            <!-- end #content -->

        </div>
        <!-- end page container -->

    </body>
</html>
