<!-- Empezando #contenido -->
<div id="administracion-cursos" class="content">
    <!-- begin page-header -->
    <h1 class="page-header">Cursos asignados</h1>
    <!-- end page-header -->

    <div id="tablaAsigCursos" class="panel panel-inverse" data-sortable-id="ui-widget-1" style="display:block;">
        <input id="valorIdUsuario" class="hidden" value="<?php echo $datos['idUsuario']; ?>" />
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-green">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Avance total</h4>
                            <p><?php echo $datos['cursos']['avance'] ?>%</p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                    <div class="stats-link">
                        <a href="javascript:;"></a>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-red">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Faltante total</h4>
                            <p><?php echo $datos['cursos']['feltante'] ?>%</p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="widget widget-stats bg-blue">
                        <div class="stats-icon"></div>
                        <div class="stats-info">
                            <h4>Total de cursos</h4>
                            <p><?php echo $datos['cursos']['totalCursos'] ?></p>	
                        </div>
                        <div class="stats-link">
                            <a href="javascript:;"></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    En esta sección encontraras una serie de cursos en línea que se te han asignado a tu perfil, los cuales tiene 
                    como finalidad crear un aporte profecional en tu formación. Por lo tanto esta 
                    herramienta web te permite ir registrando el progreso de cada uno de los cursos que vas tomando. <br><br>

                    <b>Nota Es importante mencionar que el área de Capacitación estará al pendiente de progreso que lleves.</b><br>
                </div>
            </div>

            <!-- begin tabla cursos -->
            <div class="row" style="margin-top:50px;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabla-cursosAsignados" class="table table-hover table-striped table-bordered no-wrap" style="cursor:pointer" width="100%">
                            <thead>
                                <tr>
                                    <th class="never">Id</th>
                                    <td class="all">Curso</td>
                                    <td class="all">Avance</td>
                                    <td class="all">Fecha de asignación</td>
                                    <td class="all">Estatus</td>
                                    <td class="all">Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //var_dump($datos['cursos']['cursos']);
                                if (!empty($datos['cursos']['cursos'])) {
                                    foreach ($datos['cursos']['cursos'] as $key => $value) {
                                        if ($value['estatus'] === '1' && $value['Porcentaje'] === '100') {
                                            $accion = '<span style="color: blue;"> <i class="fa fa-check-square"></i> Completado</span>';
                                        } elseif ($value['estatus'] === '1' && $value['Porcentaje'] < '100' && $value['Porcentaje'] > '0') {
                                            $accion = '<a href="javascript:;" class="btn btn-link btn-xs btn-continuar-curso" data-id="' . $value['id'] . '"><strong style="color: gold;"> <i class="fa fa-fast-forward"></i> Continuar</strong></a>';
                                        } elseif ($value['estatus'] === '1' && $value['Porcentaje'] === '0') {
                                            $accion = '<a href="javascript:;" class="btn btn-link btn-xs btn-comenzar-curso" data-id="' . $value['id'] . '"><strong style="color: forestgreen;"> <i class="fa fa-youtube-play"></i> Comenzar</strong></a>';
                                        } else {
                                            $accion = '<strong><i class="fa fa-ban"></i> Suspendido</strong>';
                                        }

                                        echo '<tr>';
                                        echo '<td>' . $value['id'] . '</td>';
                                        echo '<td>' . $value['Nombre'] . '</td>';
                                        echo '<td>' . $value['Porcentaje'] . '%</td>';
                                        echo '<td>' . $value['fechaAsignacion'] . '</td>';
                                        echo '<td>' . $value['EstatusNombre'] . '</td>';
                                        echo '<td>' . $accion . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end tabla cursos -->
        </div>
    </div>

    <!-- inicio contenido continuar curso -->
    <div id="asigCursoContinuar" class="panel panel-inverse" data-sortable-id="ui-widget-1" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-5" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>
                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para
                        empezar el curso: <a href="#">https://url</a></p>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p class="divFaltante"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-red">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Avance total</h4>
                                    <p class="divAvance"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 div-tabla-temario">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table id="tabla-temario" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td></td>
                                    <td><span class="temarioTablaCompletado" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>
                                    <td><span class="temarioTablaTerminar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <p class="text-justify">                                        
                        Realiza las siguientes instrucciones para registar tu avance: <br><br>
                        1.- En la tabla de temarios identifica el tema que deseas subir a tu avanze. <br>
                        2.- Da un clic sobre el botón terminar de la columna acciones. <br>
                        3.- El sistema muestra un formulario.<br>
                        4.- Ingresa un comentario del tema que estas ingresando.<br>
                        5.- Sube tus evidencias (solo formatos png y jpg).<br>
                        6.- Guarda tu avance. <br>
                    </p>
                    <b style="font-size:12px;">Nota: Cuando guardes el avance ya no podrás modificarlo solo te permitirá consultarlo.</b>
                </div>
            </div>
        </div>
    </div>  

    <!-- inicio contenido continuar, continuar curso evidencias -->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="temarioComenzarCurso" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-sm-12 col-md-5" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>
                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para
                        empezar el curso: <a href="#">https://url</a></p>
                </div>

                <div class="col-sm-12 col-md-7">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p class="divFaltante"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-red">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Avance total</h4>
                                    <p class="divAvance"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 div-tabla-temario">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table id="tabla-temario-completado" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
<!--                                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><span class="temarioTablaCompletado"  style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><span class="temarioTablaTerminar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    Comentarios <br>
                    <textarea id="avanceComentario" class="form-control" placeholder="Comentarios" rows="5" disabled></textarea>
                    <div id="gallery" class="gallery">
                        <!--                        <div class="image gallery-group-1">
                                                    <div class="image-inner">
                                                        <a href="assets/img/gallery/gallery-1.jpg" data-lightbox="gallery-group-1">
                                                            <img src="http://localhost/plantilla/admin/template_content_ajax/assets/img/gallery/gallery-1.jpg" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="image-info">
                                                        <h5>Fecha</h5>
                                                    </div>
                                                </div>-->
                    </div>
                    <div class="text-right">
                        <button type="button" id="btnCerrarCompletarAvanceCurso" class="btn btn-white text-right" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <!-- fin contenido continuar, continuar curso evidencias-->

    <!-- inicio contenido continuar, terminar curso evidencias -->
    <div class="panel panel-inverse" data-sortable-id="ui-widget-1" id="temarioTerminarCurso" style="display:none;">
        <div class="panel-heading">
            <h4 class="panel-title">Cursos</h4>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-sm-12 col-md-5" style="float:left;">
                    <span style="font-size:15px;"><b style="font-size:18px;">Curso </b> Gestión de proyectos</span>

                    <p style="margin-top:25px;">En esta sección encuentras los temas que lleva el curso. Cada tema equivale a un porcentaje del total del
                        curso por lo que la información del lado derechose estará actualizando. Ingresa a la siguiente liga para
                        empezar el curso: <a href="#">https://url</a></p>

                </div>

                <div class="col-sm-12 col-md-7" style="float-right">
                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-green">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Faltante total</h4>
                                    <p class="divFaltante"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="widget widget-stats bg-red">
                                <div class="stats-icon"></div>
                                <div class="stats-info">
                                    <h4>Avance total</h4>
                                    <p class="divAvance"></p>	
                                </div>
                                <div class="stats-link">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 div-tabla-temario">
                    <h4>Temario</h4>
                    <div class="underline m-b-15 m-t-15"></div>
                    <div class="table-responsive">
                        <table id="tabla-temario-terminar" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Modulo</td>
                                    <td>Avance</td>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
<!--                                <tr>
                                    <td></td>
                                    <td><span id="temarioContentTablaCompletado" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #348fe2;"><i class="fa fa-edit"></i>Completado</span></td>
                                    <td><span id="temarioContentTablaComenzar" style="cursor: pointer; margin: 5px; font-size: 13px;  color: #00acac; "><i class="fa fa-youtube-play" ></i>Terminar</span></td>
                                </tr>-->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4>Avances</h4>
                    <div class="underline m-b-15 m-t-15"></div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Comentarios</p>
                                    <textarea id="cometariosAvanceCurso" class="form-control" placeholder="Comentarios" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-12 m-t-10">
                                    <div id="errorCometariosAvanceCurso"></div>
                                </div>
                            </div>
                            <div class="row m-t-15">
                                <div class="col-md-12 text-center">
                                    <button id="btn-cancel-avance" type="button" class="btn btn-white m-r-5 m-b-5 btn-sm">Cancelar avance</button>
                                    <button id="btn-registrar-avance" type="button" class="btn btn-success m-r-5 m-b-5 btn-sm">Registrar avance</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <!--<div id="fileupload">-->
                                <div class="row fileupload-buttonbar">
                                    <div class="col-md-12">
                                        <span class="btn btn-success btn-sm fileinput-button">
                                            <span>Agregar archivos</span>
                                            <input id="multiFiles" type="file" name="files[]" multiple>
                                        </span>
                                        <button type="reset" class="btn btn-danger btn-sm cancel">
                                            <span>Borrar</span>
                                        </button>
                                        <span class="fileupload-process"></span>
                                    </div>

                                </div>
                                <!--                                    <div class="row">
                                                                        <div class="col-md-12 fileupload-progress fade">
                                                                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                                                            </div>
                                                                            <div class="progress-extended">&nbsp;</div>
                                                                        </div>
                                                                    </div>-->
                            <!--<input type="file" id="multiupload" name="uploadFiledd[]" multiple >-->
                                <!--<button type="button" id="upcvr" class="btn btn-primary">Start Upload</button>-->
                                <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                                <p id="msg"></p>
                                <!--</div>-->
                                <!--<div id="uploadsts"></div>-->
<!--                                <input id="sortpicture" type="file" name="sortpic" />
                            <button id="upload">Upload</button>-->

                                <!--<input type="file" id="multiFiles" name="files[]" multiple="multiple"/>-->
                                <!--<button id="upload">Upload</button>-->


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>


        </div>
    </div>
</div>  
<!-- fin contenido continuar, terminar curso evidencias-->


<!-- fin contenido continuar curso -->

</div>
<!-- Finalizando #contenido -->

<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
    <td class="col-md-1">
    <span class="preview"></span>
    </td>
    <td>
    <p class="name">{%=file.name%}</p>
    <strong class="error text-danger"></strong>
    </td>
    <td>
    <p class="size">Processing...</p>
    </td>
    <td>
    {% if (!i) { %}
    <button class="btn btn-white btn-sm cancel">
    <span>Cancel</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
    <td>
    <span class="preview">
    {% if (file.thumbnailUrl) { %}
    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
    {% } %}
    </span>
    </td>
    <td>
    <p class="name">
    {% if (file.url) { %}
    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
    {% } else { %}
    <span>{%=file.name%}</span>
    {% } %}
    </p>
    {% if (file.error) { %}
    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
    {% } %}
    </td>
    <td>
    <span class="size">{%=o.formatFileSize(file.size)%}</span>
    </td>
    <td>
    {% if (file.deleteUrl) { %}
    <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
    <i class="glyphicon glyphicon-trash"></i>
    <span>Delete</span>
    </button>
    <input type="checkbox" name="delete" value="1" class="toggle">
    {% } else { %}
    <button class="btn btn-warning cancel">
    <i class="glyphicon glyphicon-ban-circle"></i>
    <span>Cancel</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>

